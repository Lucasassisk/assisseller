@extends('layouts.admin')
@section('title','Dashboard')
@section('content')

<div class="stats-grid">
  <div class="stat-card">
    <div class="stat-card-icon gold"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><line x1="12" y1="1" x2="12" y2="23"/><path d="M17 5H9.5a3.5 3.5 0 000 7h5a3.5 3.5 0 010 7H6"/></svg></div>
    <div class="stat-value">R$ {{ number_format($revenue,2,',','.') }}</div>
    <div class="stat-label">Receita Total</div>
    <div class="stat-change up">↑ acumulado</div>
  </div>
  <div class="stat-card">
    <div class="stat-card-icon blue"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M6 2L3 6v14a2 2 0 002 2h14a2 2 0 002-2V6l-3-4z"/><line x1="3" y1="6" x2="21" y2="6"/></svg></div>
    <div class="stat-value">{{ $totalOrders }}</div>
    <div class="stat-label">Total de Pedidos</div>
    <div class="stat-change up">↑ acumulado</div>
  </div>
  <div class="stat-card">
    <div class="stat-card-icon green"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M17 21v-2a4 4 0 00-4-4H5a4 4 0 00-4 4v2"/><circle cx="9" cy="7" r="4"/></svg></div>
    <div class="stat-value">{{ $totalCustomers }}</div>
    <div class="stat-label">Clientes</div>
    <div class="stat-change up">↑ acumulado</div>
  </div>
  <div class="stat-card">
    <div class="stat-card-icon red"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M21 16V8a2 2 0 00-1-1.73l-7-4a2 2 0 00-2 0l-7 4A2 2 0 003 8v8a2 2 0 001 1.73l7 4a2 2 0 002 0l7-4A2 2 0 0021 16z"/></svg></div>
    <div class="stat-value">{{ $totalProducts }}</div>
    <div class="stat-label">Produtos Ativos</div>
    <div class="stat-change">Em estoque</div>
  </div>
</div>

<!-- CONFIGURAÇÕES RÁPIDAS -->
<div style="margin-bottom:1.5rem">
  <div style="font-family:var(--font-head);font-size:11px;letter-spacing:2px;text-transform:uppercase;color:var(--gray3);margin-bottom:.8rem">Configurações Rápidas</div>
  <div class="quick-grid">

    <!-- STRIPE -->
    @php $stripeOk = !empty($settings['stripe_pk'] ?? '') && !empty($settings['stripe_sk'] ?? ''); @endphp
    <a href="{{ route('admin.settings.index') }}#stripe" class="quick-card">
      <div class="quick-card-icon" style="background:{{ $stripeOk ? '#dcfce7' : '#fef3c7' }}">
        <svg viewBox="0 0 24 24" fill="none" stroke="{{ $stripeOk ? '#15803d' : '#b45309' }}" stroke-width="1.5"><rect x="2" y="5" width="20" height="14" rx="2"/><line x1="2" y1="10" x2="22" y2="10"/></svg>
      </div>
      <div class="quick-card-body">
        <div class="quick-card-title">Stripe / Pagamentos</div>
        @if($stripeOk)
          <div class="quick-card-value" style="color:var(--success)">✔ Configurado</div>
        @else
          <div class="quick-card-value" style="color:#b45309">⚠ Não configurado</div>
        @endif
      </div>
      <svg class="quick-arrow" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><polyline points="9 18 15 12 9 6"/></svg>
    </a>

    <!-- FRETE -->
    <a href="{{ route('admin.settings.index') }}#shipping" class="quick-card">
      <div class="quick-card-icon" style="background:#dbeafe">
        <svg viewBox="0 0 24 24" fill="none" stroke="#1d4ed8" stroke-width="1.5"><rect x="1" y="3" width="15" height="13"/><polygon points="16 8 20 8 23 11 23 16 16 16 16 8"/><circle cx="5.5" cy="18.5" r="2.5"/><circle cx="18.5" cy="18.5" r="2.5"/></svg>
      </div>
      <div class="quick-card-body">
        <div class="quick-card-title">Frete</div>
        <div class="quick-card-value">R$ {{ number_format(floatval($settings['frete_fixo'] ?? 12.9),2,',','.') }} · Grátis ac. R$ {{ number_format(floatval($settings['frete_gratis_acima'] ?? 99),0,',','.') }}</div>
      </div>
      <svg class="quick-arrow" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><polyline points="9 18 15 12 9 6"/></svg>
    </a>

    <!-- TAMANHOS -->
    @php
      $sizesList = array_filter(array_map('trim', explode(',', $settings['product_sizes_global'] ?? 'P,M,G,GG')));
    @endphp
    <a href="{{ route('admin.settings.index') }}#sizes" class="quick-card">
      <div class="quick-card-icon" style="background:#f3e8ff">
        <svg viewBox="0 0 24 24" fill="none" stroke="#7c3aed" stroke-width="1.5"><path d="M21 16V8a2 2 0 00-1-1.73l-7-4a2 2 0 00-2 0l-7 4A2 2 0 003 8v8a2 2 0 001 1.73l7 4a2 2 0 002 0l7-4A2 2 0 0021 16z"/><polyline points="3.27 6.96 12 12.01 20.73 6.96"/><line x1="12" y1="22.08" x2="12" y2="12"/></svg>
      </div>
      <div class="quick-card-body">
        <div class="quick-card-title">Tamanhos Globais</div>
        <div class="quick-card-value">{{ implode(', ', array_slice($sizesList, 0, 5)) }}{{ count($sizesList) > 5 ? ' +'.( count($sizesList)-5).' mais' : '' }}</div>
      </div>
      <svg class="quick-arrow" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><polyline points="9 18 15 12 9 6"/></svg>
    </a>

    <!-- APARÊNCIA / HERO -->
    <a href="{{ route('admin.settings.index') }}#appearance" class="quick-card">
      <div class="quick-card-icon" style="background:#fce7f3;overflow:hidden;padding:0">
        @if(!empty($settings['hero_image'] ?? ''))
          <img src="{{ $settings['hero_image'] }}" style="width:100%;height:100%;object-fit:cover">
        @else
          <svg viewBox="0 0 24 24" fill="none" stroke="#be185d" stroke-width="1.5" style="margin:auto;width:22px;height:22px"><rect x="3" y="3" width="18" height="18" rx="2"/><circle cx="8.5" cy="8.5" r="1.5"/><polyline points="21 15 16 10 5 21"/></svg>
        @endif
      </div>
      <div class="quick-card-body">
        <div class="quick-card-title">Hero / Aparência</div>
        <div class="quick-card-value">{{ $settings['hero_title_1'] ?? 'A.Seller' }} {{ $settings['hero_title_2'] ?? '' }}</div>
      </div>
      <svg class="quick-arrow" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><polyline points="9 18 15 12 9 6"/></svg>
    </a>

  </div>
</div>

<div class="data-card">
  <div class="data-card-header">
    <h3>Pedidos Recentes</h3>
    <a href="{{ route('admin.orders.index') }}" class="btn btn-outline btn-sm">Ver Todos</a>
  </div>
  <div class="table-wrap">
    <table>
      <thead><tr><th>Pedido</th><th>Cliente</th><th>Total</th><th>Pgto</th><th>Status</th><th>Data</th><th></th></tr></thead>
      <tbody>
        @forelse($recentOrders as $order)
        <tr>
          <td><strong>{{ $order->order_number }}</strong></td>
          <td>{{ $order->customer_name }}</td>
          <td>R$ {{ number_format($order->total,2,',','.') }}</td>
          <td><span class="status-badge status-{{ $order->payment_status }}">{{ $order->payment_status }}</span></td>
          <td><span class="status-badge status-{{ $order->order_status }}">{{ $order->order_status }}</span></td>
          <td>{{ $order->created_at->format('d/m/Y') }}</td>
          <td><a href="{{ route('admin.orders.show',$order) }}" class="btn btn-outline btn-sm">Ver</a></td>
        </tr>
        @empty
        <tr><td colspan="7" style="text-align:center;padding:2rem;color:var(--gray3)">Nenhum pedido ainda.</td></tr>
        @endforelse
      </tbody>
    </table>
  </div>
</div>

<style>
.stats-grid{display:grid;grid-template-columns:repeat(auto-fit,minmax(180px,1fr));gap:1.2rem;margin-bottom:1.5rem}
.quick-grid{display:grid;grid-template-columns:repeat(auto-fill,minmax(240px,1fr));gap:1rem}
.quick-card{display:flex;align-items:center;gap:14px;background:var(--white);border:1px solid var(--gray2);border-radius:var(--r2);padding:1rem 1.2rem;text-decoration:none;color:inherit;transition:border-color .2s,box-shadow .2s}
.quick-card:hover{border-color:var(--accent);box-shadow:0 2px 8px rgba(0,0,0,.06)}
.quick-card-icon{width:44px;height:44px;border-radius:var(--r);display:flex;align-items:center;justify-content:center;flex-shrink:0}
.quick-card-icon svg{width:22px;height:22px}
.quick-card-body{flex:1;min-width:0}
.quick-card-title{font-family:var(--font-head);font-size:11px;letter-spacing:.5px;text-transform:uppercase;color:var(--gray3);margin-bottom:3px}
.quick-card-value{font-size:13px;font-weight:500;color:var(--brand);white-space:nowrap;overflow:hidden;text-overflow:ellipsis}
.quick-arrow{width:16px;height:16px;color:var(--gray3);flex-shrink:0}
.stat-card{background:var(--white);border-radius:var(--r2);padding:1.5rem;border:1px solid var(--gray2)}
.stat-card-icon{width:44px;height:44px;border-radius:var(--r);display:flex;align-items:center;justify-content:center;margin-bottom:1rem}
.stat-card-icon svg{width:22px;height:22px;stroke:currentColor;fill:none;stroke-width:1.5}
.stat-card-icon.gold{background:#fef3d7;color:#b8880a}
.stat-card-icon.blue{background:#dbeafe;color:#1d4ed8}
.stat-card-icon.green{background:#dcfce7;color:#15803d}
.stat-card-icon.red{background:#fee2e2;color:#b91c1c}
.stat-value{font-family:var(--font-display);font-size:2rem;font-weight:700;line-height:1;margin-bottom:4px}
.stat-label{font-family:var(--font-head);font-size:11px;letter-spacing:1px;text-transform:uppercase;color:var(--gray3)}
.stat-change{font-size:12px;margin-top:6px}
.stat-change.up{color:var(--success)}
</style>
@endsection
