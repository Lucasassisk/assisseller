@extends('layouts.admin')
@section('title','Pedidos')
@section('content')

<div class="data-card">
  <div class="data-card-header">
    <h3>Todos os Pedidos</h3>
    <input class="search-input" placeholder="Buscar pedido, cliente..." id="order-search" oninput="filterOrders(this.value)">
  </div>
  <div class="table-wrap">
    <table id="orders-table">
      <thead><tr><th>Pedido</th><th>Cliente</th><th>Total</th><th>Pgto</th><th>Status</th><th>Data</th><th>Ver</th></tr></thead>
      <tbody>
        @forelse($orders as $order)
        <tr data-search="{{ strtolower($order->order_number.' '.$order->customer_name.' '.$order->customer_email) }}">
          <td><strong>{{ $order->order_number }}</strong></td>
          <td>
            <div>{{ $order->customer_name }}</div>
            <div style="font-size:11px;color:var(--gray3)">{{ $order->customer_email }}</div>
          </td>
          <td>R$ {{ number_format($order->total,2,',','.') }}</td>
          <td><span class="status-badge status-{{ $order->payment_status }}">{{ $order->payment_status }}</span></td>
          <td>
            <form method="POST" action="{{ route('admin.orders.status',$order) }}" style="margin:0">
              @csrf @method('PATCH')
              <select name="status" onchange="this.form.submit()" style="border:1px solid var(--gray2);border-radius:var(--r);padding:4px 8px;font-size:11px;font-family:var(--font-head);background:var(--white);cursor:pointer">
                @foreach(['pending','processing','shipped','delivered','cancelled'] as $s)
                  <option value="{{ $s }}" {{ $order->order_status==$s ? 'selected' : '' }}>{{ ucfirst($s) }}</option>
                @endforeach
              </select>
            </form>
          </td>
          <td style="font-size:12px">{{ $order->created_at->format('d/m/Y H:i') }}</td>
          <td><a href="{{ route('admin.orders.show',$order) }}" class="btn btn-outline btn-sm">Ver</a></td>
        </tr>
        @empty
        <tr><td colspan="7" style="text-align:center;padding:2rem;color:var(--gray3)">Nenhum pedido encontrado.</td></tr>
        @endforelse
      </tbody>
    </table>
  </div>
</div>
{{ $orders->links() }}

<style>
.data-card{background:var(--white);border-radius:var(--r2);border:1px solid var(--gray2);overflow:hidden;margin-bottom:1.5rem}
.data-card-header{padding:1.2rem 1.5rem;border-bottom:1px solid var(--gray2);display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:1rem}
.data-card-header h3{font-family:var(--font-display);font-size:1.2rem;font-weight:600}
.search-input{padding:8px 14px;border:1.5px solid var(--gray2);border-radius:var(--r);font-size:13px;font-family:var(--font-head);min-width:180px;outline:none}
.search-input:focus{border-color:var(--accent)}
.table-wrap{width:100%;overflow-x:auto}
table{width:100%;border-collapse:collapse;min-width:600px}
thead th{padding:10px 12px;text-align:left;font-family:var(--font-head);font-size:10px;letter-spacing:1.5px;text-transform:uppercase;color:var(--gray3);background:var(--gray1);border-bottom:1px solid var(--gray2)}
tbody tr{border-bottom:1px solid var(--gray1)}
tbody tr:hover{background:var(--gray1)}
tbody td{padding:10px 12px;font-size:13px;vertical-align:middle}
.status-badge{padding:4px 10px;border-radius:20px;font-size:10px;font-weight:700;font-family:var(--font-head);letter-spacing:.5px;text-transform:uppercase;white-space:nowrap}
.status-paid,.status-delivered{background:#dcfce7;color:#15803d}
.status-pending{background:#fef9c3;color:#854d0e}
.status-shipped,.status-processing{background:#dbeafe;color:#1d4ed8}
.status-cancelled{background:#fee2e2;color:#b91c1c}
</style>
<script>
function filterOrders(v) {
  v = v.toLowerCase();
  document.querySelectorAll('#orders-table tbody tr[data-search]').forEach(tr => {
    tr.style.display = tr.dataset.search.includes(v) ? '' : 'none';
  });
}
</script>
@endsection
