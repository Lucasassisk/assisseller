<?php

namespace App\Http\Controllers\Store;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Setting;
use App\Models\Order;
use App\Models\Customer;
use App\Models\Coupon;
use App\Models\Review;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class StoreController extends Controller
{
    public function index()
    {
        $settings = Setting::allKeyed();
        $products  = Product::where('active', true)->orderBy('id')->get();
        $reviews   = Review::with('product')->where('approved', true)->latest()->get();
        return view('store.index', compact('settings', 'products', 'reviews'));
    }

    public function submitReview(Request $request)
    {
        // Honeypot: bots preenchem esse campo, humanos não
        if ($request->filled('website')) {
            return back()->with('review_sent', true); // silencioso
        }

        $data = $request->validate([
            'name'       => 'required|string|max:100',
            'product_id' => 'nullable|exists:products,id',
            'rating'     => 'required|integer|min:1|max:5',
            'comment'    => 'required|string|max:1000',
        ]);

        Review::create($data);

        return back()->with('review_sent', true);
    }

    public function validateCoupon(Request $request)
    {
        $request->validate(['code' => 'required|string', 'total' => 'required|numeric']);
        $coupon = Coupon::where('code', strtoupper($request->code))->first();

        if (!$coupon || !$coupon->isValid($request->total)) {
            return response()->json(['success' => false, 'error' => 'Cupom inválido ou expirado.']);
        }

        return response()->json([
            'success' => true,
            'coupon'  => [
                'code'      => $coupon->code,
                'type'      => $coupon->type,
                'value'     => $coupon->value,
                'discount'  => $coupon->discountFor($request->total),
                'frete'     => $coupon->type === 'frete',
            ],
        ]);
    }

    public function createOrder(Request $request)
    {
        $request->validate([
            'customer_name'   => 'required|string|max:255',
            'customer_email'  => 'required|email|max:255',
            'customer_phone'  => 'nullable|string|max:20',
            'address'         => 'nullable|string|max:500',
            'city'            => 'nullable|string|max:100',
            'state'           => 'nullable|string|max:2',
            'cep'             => 'nullable|string|max:9',
            'items'           => 'required|array|min:1|max:50',
            'items.*.id'      => 'required|integer|exists:products,id',
            'items.*.qty'     => 'required|integer|min:1|max:100',
            'items.*.size'    => 'nullable|string|max:20',
            'payment_method'  => 'required|string|in:card,pix,boleto',
            'coupon_code'     => 'nullable|string|max:50',
            'stripe_pi_id'    => 'nullable|string|max:255',
        ]);

        // ── Buscar produtos reais do banco e recalcular preços ──
        $requestedItems = collect($request->items);
        $productIds     = $requestedItems->pluck('id')->unique();
        $products       = Product::whereIn('id', $productIds)
                            ->where('active', true)
                            ->get()
                            ->keyBy('id');

        // Rejeitar se algum produto não existir ou estiver inativo
        foreach ($requestedItems as $item) {
            if (!$products->has($item['id'])) {
                return response()->json(['success' => false, 'error' => 'Produto indisponível.'], 422);
            }
        }

        // Montar itens com preços vindos do banco (nunca do cliente)
        $items = $requestedItems->map(fn($item) => [
            'id'    => $item['id'],
            'name'  => $products[$item['id']]->name,
            'size'  => $item['size'] ?? '',
            'qty'   => (int) $item['qty'],
            'price' => (float) $products[$item['id']]->price,
        ])->values()->toArray();

        // Calcular subtotal com preços reais
        $subtotal = array_sum(array_map(fn($i) => $i['price'] * $i['qty'], $items));

        // Frete calculado no backend (mesma lógica do frontend)
        $freteFixo        = floatval(Setting::get('frete_fixo', 12.90));
        $freteGratisAcima = floatval(Setting::get('frete_gratis_acima', 99));
        $shipping         = $subtotal >= $freteGratisAcima ? 0 : $freteFixo;

        // Cupom
        $discount   = 0;
        $couponCode = $request->coupon_code ? strtoupper($request->coupon_code) : null;
        if ($couponCode) {
            $coupon = Coupon::where('code', $couponCode)->first();
            if ($coupon && $coupon->isValid($subtotal)) {
                $discount = $coupon->discountFor($subtotal);
                if ($coupon->type === 'frete') { $shipping = 0; $discount = 0; }
                $coupon->increment('used_count');
            }
        }

        $total = max(0, $subtotal + $shipping - $discount);

        // Upsert cliente
        $customer = Customer::updateOrCreate(
            ['email' => $request->customer_email],
            [
                'name'    => $request->customer_name,
                'phone'   => $request->customer_phone ?? '',
                'address' => $request->address ?? '',
                'city'    => $request->city ?? '',
                'state'   => $request->state ?? '',
                'cep'     => $request->cep ?? '',
            ]
        );

        $paymentStatus = $request->filled('stripe_pi_id') ? 'paid' : 'pending';

        $order = Order::create([
            'order_number'     => Order::generateNumber(),
            'customer_id'      => $customer->id,
            'customer_name'    => $request->customer_name,
            'customer_email'   => $request->customer_email,
            'customer_phone'   => $request->customer_phone ?? '',
            'items'            => $items,
            'subtotal'         => $subtotal,
            'discount'         => $discount,
            'shipping'         => $shipping,
            'total'            => $total,
            'coupon_code'      => $couponCode,
            'payment_method'   => $request->payment_method,
            'payment_status'   => $paymentStatus,
            'order_status'     => 'pending',
            'stripe_pi_id'     => $request->stripe_pi_id ?? null,
            'shipping_address' => [
                'address' => $request->address ?? '',
                'city'    => $request->city ?? '',
                'state'   => $request->state ?? '',
                'cep'     => $request->cep ?? '',
            ],
        ]);

        // Itens + decremento de estoque
        foreach ($items as $item) {
            $order->orderItems()->create([
                'product_id'   => $item['id'],
                'product_name' => $item['name'],
                'size'         => $item['size'],
                'quantity'     => $item['qty'],
                'unit_price'   => $item['price'],
                'total_price'  => $item['price'] * $item['qty'],
            ]);

            Product::where('id', $item['id'])->where('stock', '>', 0)
                ->decrement('stock', $item['qty']);
        }

        try {
            Mail::to($order->customer_email)
                ->send(new \App\Mail\OrderConfirmation($order));
        } catch (\Throwable) {}

        return response()->json([
            'success'      => true,
            'order_number' => $order->order_number,
            'id'           => $order->id,
        ]);
    }

    public function createPaymentIntent(Request $request)
    {
        $request->validate(['amount' => 'required|numeric|min:1']);

        $sk = Setting::get('stripe_sk');
        if (!$sk) {
            return response()->json(['error' => 'Pagamento via cartão não configurado.'], 422);
        }

        \Stripe\Stripe::setApiKey($sk);

        $intent = \Stripe\PaymentIntent::create([
            'amount'   => (int) round($request->amount * 100), // centavos
            'currency' => 'brl',
            'automatic_payment_methods' => ['enabled' => true],
        ]);

        return response()->json(['clientSecret' => $intent->client_secret]);
    }

    public function trackOrder(Request $request)
    {
        $request->validate([
            'order_number' => 'required|string|max:20',
            'email'        => 'required|email|max:255',
        ]);

        // Exige número do pedido E e-mail juntos (não um ou outro)
        $order = Order::where('order_number', strtoupper(trim($request->order_number)))
            ->where('customer_email', strtolower(trim($request->email)))
            ->first();

        if (!$order) {
            return response()->json(['success' => false, 'error' => 'Pedido não encontrado. Verifique o número e o e-mail.']);
        }

        // Retorna apenas o necessário — nunca o objeto completo
        return response()->json([
            'success' => true,
            'order'   => [
                'order_number'   => $order->order_number,
                'order_status'   => $order->order_status,
                'payment_status' => $order->payment_status,
                'payment_method' => $order->payment_method,
                'total'          => $order->total,
                'tracking_code'  => $order->tracking_code,
                'created_at'     => $order->created_at->format('d/m/Y H:i'),
                'items'          => collect($order->items)->map(fn($i) => [
                    'name' => $i['name'],
                    'qty'  => $i['qty'],
                    'size' => $i['size'] ?? '',
                ]),
            ],
        ]);
    }
}
