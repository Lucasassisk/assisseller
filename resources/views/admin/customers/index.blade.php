@extends('layouts.admin')
@section('title','Clientes')
@section('content')
<div class="data-card">
  <div class="data-card-header">
    <h3>Clientes Cadastrados</h3>
    <input class="search-input" placeholder="Buscar cliente..." id="cust-search" oninput="filterRows('cust-search','customers-table')">
  </div>
  <div class="table-wrap">
    <table id="customers-table">
      <thead><tr><th>Nome</th><th>E-mail</th><th>WhatsApp</th><th>Cidade</th><th>Pedidos</th><th>Total Gasto</th><th>Cadastro</th></tr></thead>
      <tbody>
        @forelse($customers as $c)
        <tr data-search="{{ strtolower($c->name.' '.$c->email.' '.$c->city) }}">
          <td><strong>{{ $c->name }}</strong></td>
          <td>{{ $c->email }}</td>
          <td>
            @if($c->phone)
              <a href="https://wa.me/{{ preg_replace('/\D/','',$c->phone) }}" target="_blank" style="color:var(--accent)">{{ $c->phone }}</a>
            @else — @endif
          </td>
          <td>{{ $c->city ?? '—' }}</td>
          <td>{{ $c->orders_count }}</td>
          <td>R$ {{ number_format($c->orders_sum_total ?? 0,2,',','.') }}</td>
          <td style="font-size:12px">{{ $c->created_at->format('d/m/Y') }}</td>
        </tr>
        @empty
        <tr><td colspan="7" style="text-align:center;padding:2rem;color:var(--gray3)">Nenhum cliente ainda.</td></tr>
        @endforelse
      </tbody>
    </table>
  </div>
</div>
{{ $customers->links() }}
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
</style>
<script>
function filterRows(inputId, tableId) {
  const v = document.getElementById(inputId).value.toLowerCase();
  document.querySelectorAll('#'+tableId+' tbody tr[data-search]').forEach(tr => {
    tr.style.display = tr.dataset.search.includes(v) ? '' : 'none';
  });
}
</script>
@endsection
