@extends('layouts.admin')
@section('title','Cupons')
@section('content')
<div class="admin-two-col">
  <!-- LISTA -->
  <div>
    <div class="data-card">
      <div class="data-card-header"><h3>Cupons Ativos</h3></div>
      <div style="padding:1.5rem">
        @forelse($coupons as $coupon)
        <div style="background:var(--gray1);border-radius:var(--r2);padding:1.2rem;margin-bottom:1rem;display:flex;align-items:center;justify-content:space-between;gap:1rem;flex-wrap:wrap">
          <div>
            <div style="font-family:var(--font-head);font-size:1.1rem;font-weight:700;letter-spacing:2px;color:{{ $coupon->active ? 'var(--accent)' : 'var(--gray3)' }}">{{ $coupon->code }}</div>
            <div style="font-size:12px;color:var(--gray3);margin-top:4px">
              @if($coupon->type==='percent') {{ $coupon->value }}% de desconto
              @elseif($coupon->type==='fixed') R$ {{ number_format($coupon->value,2,',','.') }} de desconto
              @else Frete Grátis @endif
              · {{ $coupon->used_count }}/{{ $coupon->max_uses }} usos
              @if($coupon->expires_at) · Expira {{ \Carbon\Carbon::parse($coupon->expires_at)->format('d/m/Y') }} @endif
            </div>
          </div>
          <div style="display:flex;gap:8px;align-items:center">
            <form method="POST" action="{{ route('admin.coupons.toggle',$coupon) }}" style="margin:0">
              @csrf @method('PATCH')
              <button type="submit" class="btn btn-sm" style="background:{{ $coupon->active ? '#dcfce7' : '#fee2e2' }};color:{{ $coupon->active ? '#15803d' : '#b91c1c' }};border:none;cursor:pointer">
                {{ $coupon->active ? 'Ativo' : 'Inativo' }}
              </button>
            </form>
            <form method="POST" action="{{ route('admin.coupons.destroy',$coupon) }}" onsubmit="return confirm('Excluir cupom?')" style="margin:0">
              @csrf @method('DELETE')
              <button type="submit" class="btn btn-sm" style="background:#fee2e2;color:#b91c1c;border:none;cursor:pointer">✕</button>
            </form>
          </div>
        </div>
        @empty
        <div style="text-align:center;padding:2rem;color:var(--gray3)">Nenhum cupom ainda. Crie o primeiro!</div>
        @endforelse
      </div>
    </div>
  </div>

  <!-- CRIAR CUPOM -->
  <div class="settings-panel" style="position:sticky;top:80px">
    <h3>Criar Cupom</h3>
    <div class="subtitle">Configure um novo cupom de desconto</div>
    <form method="POST" action="{{ route('admin.coupons.store') }}">
      @csrf
      <div class="form-group">
        <label>Código do Cupom</label>
        <input type="text" name="code" id="cp-code" placeholder="VERAO10" style="text-transform:uppercase" oninput="this.value=this.value.toUpperCase();updateCouponPreview()" required>
      </div>
      <div class="form-group">
        <label>Tipo de Desconto</label>
        <select name="type" id="cp-type" onchange="updateCouponPreview()">
          <option value="percent">Percentual (%)</option>
          <option value="fixed">Valor Fixo (R$)</option>
          <option value="frete">Frete Grátis</option>
        </select>
      </div>
      <div class="form-group" id="cp-value-group">
        <label>Valor do Desconto</label>
        <input type="number" name="value" id="cp-value" value="10" min="0" oninput="updateCouponPreview()">
      </div>
      <div class="form-group"><label>Mín. de Compra (R$)</label><input type="number" name="min_value" value="0" min="0"></div>
      <div class="form-group"><label>Usos Máximos</label><input type="number" name="max_uses" value="100" min="1"></div>
      <div class="form-group"><label>Válido até</label><input type="date" name="expires_at"></div>
      <div style="background:var(--gray1);border-radius:var(--r);padding:1rem;margin-bottom:1rem;text-align:center">
        <div style="font-size:11px;color:var(--gray3);font-family:var(--font-head);margin-bottom:4px">PRÉVIA DO CUPOM</div>
        <div id="cp-preview" style="font-family:var(--font-head);font-size:1.2rem;font-weight:700;letter-spacing:3px;color:var(--accent)">VERAO10 — 10% OFF</div>
      </div>
      <button type="submit" class="btn btn-dark" style="width:100%;justify-content:center">Criar Cupom</button>
    </form>
  </div>
</div>
<style>
.admin-two-col{display:grid;grid-template-columns:1fr 340px;gap:1.5rem;align-items:start}
@media(max-width:1100px){.admin-two-col{grid-template-columns:1fr}}
.data-card{background:var(--white);border-radius:var(--r2);border:1px solid var(--gray2);overflow:hidden;margin-bottom:1.5rem}
.data-card-header{padding:1.2rem 1.5rem;border-bottom:1px solid var(--gray2);display:flex;align-items:center;justify-content:space-between}
.data-card-header h3{font-family:var(--font-display);font-size:1.2rem;font-weight:600}
.settings-panel{background:var(--white);border-radius:var(--r2);border:1px solid var(--gray2);padding:1.5rem}
.settings-panel h3{font-family:var(--font-display);font-size:1.4rem;margin-bottom:.4rem}
.settings-panel .subtitle{font-size:13px;color:var(--gray3);margin-bottom:1.5rem;padding-bottom:1.2rem;border-bottom:1px solid var(--gray2)}
</style>
<script>
function updateCouponPreview() {
  const code = document.getElementById('cp-code').value || 'CUPOM';
  const type = document.getElementById('cp-type').value;
  const val  = document.getElementById('cp-value').value || '10';
  const grp  = document.getElementById('cp-value-group');
  grp.style.display = type === 'frete' ? 'none' : '';
  let label = type === 'percent' ? val + '% OFF' : type === 'fixed' ? 'R$ ' + val + ' OFF' : 'FRETE GRÁTIS';
  document.getElementById('cp-preview').textContent = code + ' — ' + label;
}
</script>
@endsection
