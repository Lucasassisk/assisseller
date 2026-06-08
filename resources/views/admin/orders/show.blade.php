@extends('layouts.admin')
@section('page-title', 'Pedido ' . $order->order_number)

@section('content')
<div class="page-header">
  <h2>Pedido {{ $order->order_number }}</h2>
  <a href="{{ route('admin.orders.index') }}" class="btn btn-outline btn-sm">← Voltar</a>
</div>

<div style="display:grid;grid-template-columns:1fr 340px;gap:1.5rem">
  <div>
    {{-- Itens --}}
    <div class="card" style="margin-bottom:1.5rem">
      <div class="card-head"><h3>Itens do Pedido</h3></div>
      <div class="table-wrap">
        <table>
          <thead><tr><th>Produto</th><th>Tamanho</th><th>Qtd</th><th>Unit.</th><th>Total</th></tr></thead>
          <tbody>
            @foreach($order->orderItems as $item)
            <tr>
              <td><strong>{{ $item->product_name }}</strong></td>
              <td>{{ $item->size ?: '—' }}</td>
              <td>{{ $item->quantity }}</td>
              <td>R$ {{ number_format($item->unit_price, 2, ',', '.') }}</td>
              <td><strong>R$ {{ number_format($item->total_price, 2, ',', '.') }}</strong></td>
            </tr>
            @endforeach
          </tbody>
        </table>
      </div>
      <div style="padding:1rem 1.5rem;border-top:1px solid var(--gray2)">
        <div style="display:flex;justify-content:space-between;font-size:13px;margin-bottom:.5rem">
          <span class="text-muted">Subtotal</span><span>R$ {{ number_format($order->subtotal, 2, ',', '.') }}</span>
        </div>
        @if($order->discount > 0)
        <div style="display:flex;justify-content:space-between;font-size:13px;margin-bottom:.5rem;color:var(--success)">
          <span>Desconto ({{ $order->coupon_code }})</span><span>- R$ {{ number_format($order->discount, 2, ',', '.') }}</span>
        </div>
        @endif
        <div style="display:flex;justify-content:space-between;font-size:13px;margin-bottom:.75rem">
          <span class="text-muted">Frete</span><span>{{ $order->shipping > 0 ? 'R$ '.number_format($order->shipping,2,',','.') : 'Grátis' }}</span>
        </div>
        <div style="display:flex;justify-content:space-between;font-size:1.1rem;font-weight:700;font-family:var(--font-display)">
          <span>Total</span><span>R$ {{ number_format($order->total, 2, ',', '.') }}</span>
        </div>
      </div>
    </div>

    {{-- Endereço --}}
    @if($order->shipping_address)
    <div class="settings-section">
      <h3>Endereço de Entrega</h3>
      <div class="sub" style="margin-bottom:0">
        {{ $order->shipping_address['address'] ?? '' }}<br>
        {{ $order->shipping_address['city'] ?? '' }}{{ isset($order->shipping_address['state']) ? ' — '.$order->shipping_address['state'] : '' }}<br>
        CEP: {{ $order->shipping_address['cep'] ?? '' }}
      </div>
    </div>
    @endif
  </div>

  <div>
    {{-- Status --}}
    <div class="settings-section" style="margin-bottom:1.5rem">
      <h3>Status do Pedido</h3>
      <div class="sub">Atualize o status e código de rastreio</div>
      <form method="POST" action="{{ route('admin.orders.status', $order) }}">
        @csrf @method('PATCH')
        <div class="form-group">
          <label>Status</label>
          <select name="status" class="form-control">
            @foreach(['pending'=>'Pendente','paid'=>'Pago','preparing'=>'Preparando','shipped'=>'Enviado','delivered'=>'Entregue','cancelled'=>'Cancelado'] as $v=>$l)
              <option value="{{ $v }}" {{ $order->order_status===$v?'selected':'' }}>{{ $l }}</option>
            @endforeach
          </select>
        </div>
        <div class="form-group">
          <label>Código de Rastreio</label>
          <input type="text" name="tracking_code" class="form-control" value="{{ $order->tracking_code }}" placeholder="BR123456789BR">
        </div>
        <button type="submit" class="btn btn-dark" style="width:100%">Atualizar Status</button>
      </form>
    </div>

    {{-- Cliente --}}
    <div class="settings-section">
      <h3>Cliente</h3>
      <div style="font-size:13px;display:flex;flex-direction:column;gap:.5rem">
        <div><strong>{{ $order->customer_name }}</strong></div>
        <div class="text-muted">{{ $order->customer_email }}</div>
        <div class="text-muted">{{ $order->customer_phone }}</div>
        <hr class="divider" style="margin:.5rem 0">
        <div class="text-muted">Pagamento: <strong>{{ ucfirst($order->payment_method) }}</strong></div>
        <div class="text-muted">Pedido em: <strong>{{ $order->created_at->format('d/m/Y H:i') }}</strong></div>
        <div style="margin-top:.25rem"><span class="badge badge-{{ $order->order_status }}">{{ $order->status_label }}</span></div>
      </div>
    </div>
  </div>
</div>
@endsection
