<!DOCTYPE html>
<html lang="pt-BR">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width,initial-scale=1">
<title>Pedido Confirmado · A.Seller</title>
</head>
<body style="margin:0;padding:0;background:#f4f2ef;font-family:'Helvetica Neue',Helvetica,Arial,sans-serif;color:#0a0a0a">

<table width="100%" cellpadding="0" cellspacing="0" style="background:#f4f2ef;padding:40px 20px">
  <tr><td align="center">
    <table width="600" cellpadding="0" cellspacing="0" style="max-width:600px;width:100%">

      {{-- HEADER --}}
      <tr><td style="background:#0a0a0a;border-radius:12px 12px 0 0;padding:32px 40px;text-align:center">
        <div style="font-family:Georgia,serif;font-size:32px;font-weight:700;color:#fff;letter-spacing:1px">
          A.<span style="color:#c8a96e">Seller</span>
        </div>
        <div style="color:rgba(255,255,255,.4);font-size:11px;letter-spacing:3px;text-transform:uppercase;margin-top:4px">Confirmação de Pedido</div>
      </td></tr>

      {{-- BODY --}}
      <tr><td style="background:#fff;padding:40px">

        <p style="font-size:22px;font-family:Georgia,serif;margin:0 0 8px">Olá, {{ $order->customer_name }}! 👋</p>
        <p style="color:#6b6455;font-size:15px;line-height:1.6;margin:0 0 28px">
          Recebemos o seu pedido e ele já está sendo processado. Assim que for enviado, você receberá o código de rastreamento.
        </p>

        {{-- ORDER NUMBER --}}
        <div style="background:#f4f2ef;border-radius:10px;padding:20px 24px;margin-bottom:28px;text-align:center">
          <div style="font-size:11px;letter-spacing:3px;text-transform:uppercase;color:#a09880;margin-bottom:6px">Número do Pedido</div>
          <div style="font-size:28px;font-weight:700;font-family:Georgia,serif;color:#0a0a0a;letter-spacing:2px">{{ $order->order_number }}</div>
        </div>

        {{-- ITEMS --}}
        <div style="margin-bottom:24px">
          <div style="font-size:11px;letter-spacing:2px;text-transform:uppercase;color:#a09880;margin-bottom:12px;padding-bottom:8px;border-bottom:1px solid #e8e4de">Itens do Pedido</div>
          @foreach($order->items as $item)
          <div style="display:flex;justify-content:space-between;align-items:center;padding:10px 0;border-bottom:1px solid #f4f2ef">
            <div>
              <div style="font-size:14px;font-weight:600">{{ $item['name'] ?? '' }}</div>
              @if(!empty($item['size']))<div style="font-size:12px;color:#a09880">Tamanho: {{ $item['size'] }}</div>@endif
              <div style="font-size:12px;color:#a09880">Qtd: {{ $item['qty'] ?? 1 }}</div>
            </div>
            <div style="font-size:14px;font-weight:600">R$ {{ number_format(($item['price'] ?? 0) * ($item['qty'] ?? 1), 2, ',', '.') }}</div>
          </div>
          @endforeach
        </div>

        {{-- TOTALS --}}
        <table width="100%" cellpadding="0" cellspacing="0" style="margin-bottom:28px">
          <tr>
            <td style="font-size:13px;color:#6b6455;padding:5px 0">Subtotal</td>
            <td align="right" style="font-size:13px;color:#6b6455">R$ {{ number_format($order->subtotal, 2, ',', '.') }}</td>
          </tr>
          @if($order->discount > 0)
          <tr>
            <td style="font-size:13px;color:#15803d;padding:5px 0">Desconto</td>
            <td align="right" style="font-size:13px;color:#15803d">- R$ {{ number_format($order->discount, 2, ',', '.') }}</td>
          </tr>
          @endif
          @if($order->shipping > 0)
          <tr>
            <td style="font-size:13px;color:#6b6455;padding:5px 0">Frete</td>
            <td align="right" style="font-size:13px;color:#6b6455">R$ {{ number_format($order->shipping, 2, ',', '.') }}</td>
          </tr>
          @endif
          <tr>
            <td style="font-size:16px;font-weight:700;padding:12px 0 5px;border-top:2px solid #0a0a0a">Total</td>
            <td align="right" style="font-size:16px;font-weight:700;padding:12px 0 5px;border-top:2px solid #0a0a0a">R$ {{ number_format($order->total, 2, ',', '.') }}</td>
          </tr>
        </table>

        {{-- PAYMENT --}}
        <div style="background:#f4f2ef;border-radius:10px;padding:16px 20px;margin-bottom:28px">
          <span style="font-size:12px;color:#a09880;text-transform:uppercase;letter-spacing:1px">Pagamento: </span>
          <span style="font-size:13px;font-weight:600">
            @if($order->payment_method === 'card') 💳 Cartão de Crédito
            @elseif($order->payment_method === 'pix') ⚡ Pix
            @else 🧾 Boleto
            @endif
          </span>
          &nbsp;&nbsp;
          <span style="font-size:12px;background:{{ $order->payment_status === 'paid' ? '#dcfce7' : '#fef3c7' }};color:{{ $order->payment_status === 'paid' ? '#15803d' : '#92400e' }};padding:3px 10px;border-radius:20px">
            {{ $order->payment_status === 'paid' ? 'Pago' : 'Aguardando pagamento' }}
          </span>
        </div>

        {{-- SHIPPING ADDRESS --}}
        @if(!empty($order->shipping_address['address']))
        <div style="margin-bottom:28px">
          <div style="font-size:11px;letter-spacing:2px;text-transform:uppercase;color:#a09880;margin-bottom:8px">Endereço de Entrega</div>
          <div style="font-size:14px;line-height:1.7;color:#0a0a0a">
            {{ $order->shipping_address['address'] ?? '' }}<br>
            {{ $order->shipping_address['city'] ?? '' }}{{ !empty($order->shipping_address['state']) ? ' - '.$order->shipping_address['state'] : '' }}<br>
            CEP: {{ $order->shipping_address['cep'] ?? '' }}
          </div>
        </div>
        @endif

        <p style="font-size:14px;color:#6b6455;line-height:1.7;margin:0">
          Dúvidas? Fale com a gente pelo WhatsApp ou responda este e-mail.
        </p>

      </td></tr>

      {{-- FOOTER --}}
      <tr><td style="background:#0a0a0a;border-radius:0 0 12px 12px;padding:24px 40px;text-align:center">
        <p style="color:rgba(255,255,255,.4);font-size:12px;margin:0">
          © {{ date('Y') }} A.Seller · Todos os direitos reservados
        </p>
      </td></tr>

    </table>
  </td></tr>
</table>

</body>
</html>
