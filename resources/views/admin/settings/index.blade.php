@extends('layouts.admin')
@section('title','Configurações')
@section('content')

@if(session('success'))
  <div style="background:#dcfce7;border:1px solid #86efac;color:#15803d;padding:1rem;border-radius:var(--r);margin-bottom:1.5rem;font-size:14px">✔ {{ session('success') }}</div>
@endif

<!-- NAV TABS -->
<div style="display:flex;gap:4px;margin-bottom:1.5rem;flex-wrap:wrap">
  @foreach(['general'=>'Geral','shipping'=>'Frete','sizes'=>'Tamanhos','appearance'=>'Aparência','whatsapp'=>'WhatsApp','stripe'=>'Stripe / Pagamentos','social'=>'Redes Sociais'] as $tab=>$label)
  <button onclick="showTab('{{ $tab }}')" id="tab-btn-{{ $tab }}" class="tab-btn {{ $tab==='general' ? 'active' : '' }}">{{ $label }}</button>
  @endforeach
</div>

<!-- ─── GERAL ─── -->
<div id="tab-general" class="tab-panel">
  <div class="settings-panel">
    <h3>Configurações Gerais</h3>
    <div class="subtitle">Informações básicas da sua loja</div>
    <form method="POST" action="{{ route('admin.settings.update') }}">
      @csrf
      <input type="hidden" name="tab" value="general">
      <div class="form-group"><label>Nome da Loja</label><input type="text" name="store_name" value="{{ $s['store_name'] ?? 'A.Seller' }}"></div>
      <div class="form-group"><label>E-mail de Contato</label><input type="email" name="store_email" value="{{ $s['store_email'] ?? '' }}"></div>
      <div class="form-group"><label>WhatsApp (com DDI, ex: 5562999...)</label><input type="tel" name="store_phone" value="{{ $s['store_phone'] ?? '' }}"></div>
      <button type="submit" class="btn btn-dark">Salvar Configurações</button>
    </form>
  </div>
</div>

<!-- ─── FRETE ─── -->
<div id="tab-shipping" class="tab-panel hidden">
  <div class="settings-panel">
    <h3>Frete e Entrega</h3>
    <div class="subtitle">Configure os valores e prazos de entrega</div>
    <form method="POST" action="{{ route('admin.settings.update') }}">
      @csrf
      <input type="hidden" name="tab" value="shipping">
      <div class="form-row">
        <div class="form-group"><label>Frete Fixo (R$)</label><input type="number" name="frete_fixo" value="{{ $s['frete_fixo'] ?? '12.90' }}" step="0.01"></div>
        <div class="form-group"><label>Frete Grátis acima de (R$)</label><input type="number" name="frete_gratis_acima" value="{{ $s['frete_gratis_acima'] ?? '99' }}"></div>
      </div>
      <div class="form-row">
        <div class="form-group"><label>Prazo Mín. (dias úteis)</label><input type="number" name="frete_prazo_min" value="{{ $s['frete_prazo_min'] ?? '3' }}"></div>
        <div class="form-group"><label>Prazo Máx. (dias úteis)</label><input type="number" name="frete_prazo_max" value="{{ $s['frete_prazo_max'] ?? '8' }}"></div>
      </div>
      <button type="submit" class="btn btn-dark">Salvar</button>
    </form>
  </div>
</div>

<!-- ─── TAMANHOS ─── -->
<div id="tab-sizes" class="tab-panel hidden">
  <div class="settings-panel" style="max-width:680px">
    <h3>Tamanhos Globais</h3>
    <div class="subtitle">Lista padrão de tamanhos para todos os produtos. Cada produto pode sobrescrever com tamanhos próprios.</div>
    <form method="POST" action="{{ route('admin.settings.update') }}">
      @csrf
      <input type="hidden" name="tab" value="sizes">
      <div class="form-group">
        <label>Tamanhos disponíveis (separados por vírgula)</label>
        <input type="text" name="product_sizes_global" id="sizes-global-input"
               value="{{ $s['product_sizes_global'] ?? '33-34,35-36,37-38,39-40,41-42,43-44' }}"
               placeholder="33-34,35-36,37-38,39-40,41-42,43-44">
        <div style="font-size:12px;color:var(--gray3);margin-top:6px">Pode misturar letras e números: P,M,G,GG,34,35,36</div>
      </div>
      <div style="margin-bottom:1.2rem">
        <label style="display:block;font-size:12px;letter-spacing:.5px;text-transform:uppercase;color:var(--gray4);margin-bottom:8px">Pré-visualização</label>
        <div id="sizes-preview-chips" style="display:flex;flex-wrap:wrap;gap:6px"></div>
      </div>
      <div style="border-top:1px solid var(--gray2);margin-bottom:1.2rem;padding-top:1.2rem">
        <label style="display:block;font-size:12px;letter-spacing:.5px;text-transform:uppercase;color:var(--gray4);margin-bottom:8px">Adicionar tamanho rápido</label>
        <div style="display:flex;gap:6px;flex-wrap:wrap">
          @foreach(['33-34','35-36','37-38','39-40','41-42','43-44'] as $sz)
            <button type="button" class="quick-size-btn" onclick="addQuickSize('{{ $sz }}')">{{ $sz }}</button>
          @endforeach
        </div>
      </div>
      <button type="submit" class="btn btn-dark">Salvar Tamanhos</button>
    </form>
  </div>
</div>

<!-- ─── APARÊNCIA ─── -->
<div id="tab-appearance" class="tab-panel hidden">
  <div class="admin-two-col">
    <div class="settings-panel">
      <h3>Conteúdo do Hero</h3>
      <div class="subtitle">Edite os textos da página inicial</div>
      <form method="POST" action="{{ route('admin.settings.update') }}">
        @csrf
        <input type="hidden" name="tab" value="appearance">
        <div class="form-group"><label>Título linha 1</label><input type="text" name="hero_title_1" value="{{ $s['hero_title_1'] ?? 'Havaianas' }}"></div>
        <div class="form-group"><label>Título linha 2 (destaque itálico)</label><input type="text" name="hero_title_2" value="{{ $s['hero_title_2'] ?? 'Brancas' }}"></div>
        <div class="form-group"><label>Título linha 3</label><input type="text" name="hero_title_3" value="{{ $s['hero_title_3'] ?? 'do Brasil' }}"></div>
        <div class="form-group"><label>Descrição</label><textarea name="hero_desc" rows="3" style="resize:vertical">{{ $s['hero_desc'] ?? '' }}</textarea></div>
        <div class="form-group"><label>Texto do Banner Topo</label><input type="text" name="topbar_text" value="{{ $s['topbar_text'] ?? '' }}"></div>
        <button type="submit" class="btn btn-dark">Salvar Textos</button>
      </form>
    </div>
    <div class="settings-panel">
      <h3>Foto do Hero</h3>
      <div class="subtitle">Imagem principal da página inicial</div>
      <form method="POST" action="{{ route('admin.settings.hero-image') }}" enctype="multipart/form-data">
        @csrf
        <div class="form-group"><label>Enviar nova foto</label><input type="file" name="hero_image" accept="image/*" style="padding:8px 0"></div>
        <div class="form-group"><label>Ou cole uma URL</label><input type="url" name="hero_image_url" value="{{ $s['hero_image'] ?? '' }}" placeholder="https://..."></div>
        @if(!empty($s['hero_image']))
          <img src="{{ $s['hero_image'] }}" style="width:100%;border-radius:var(--r);margin-bottom:1rem;max-height:200px;object-fit:cover">
        @endif
        <button type="submit" class="btn btn-dark">Aplicar Imagem</button>
      </form>
      <div style="border-top:1px solid var(--gray2);margin:1.5rem 0 1rem"></div>
      <h3>Vídeo do Hero</h3>
      <form method="POST" action="{{ route('admin.settings.update') }}">
        @csrf
        <input type="hidden" name="tab" value="video">
        <div class="form-group"><label>URL do vídeo (YouTube, Vimeo ou .mp4)</label><input type="text" name="hero_video" value="{{ $s['hero_video'] ?? '' }}" placeholder="https://youtube.com/watch?v=..."></div>
        <div class="form-group">
          <label>Comportamento</label>
          <select name="hero_video_opts">
            <option value="autoplay" {{ ($s['hero_video_opts'] ?? 'autoplay') === 'autoplay' ? 'selected' : '' }}>Autoplay silencioso</option>
            <option value="controls" {{ ($s['hero_video_opts'] ?? '') === 'controls' ? 'selected' : '' }}>Com controles</option>
          </select>
        </div>
        <div style="display:flex;gap:10px;flex-wrap:wrap">
          <button type="submit" class="btn btn-dark">Aplicar Vídeo</button>
          <button type="submit" name="hero_video" value="" class="btn btn-outline">Remover Vídeo</button>
        </div>
      </form>
    </div>
  </div>
</div>

<!-- ─── WHATSAPP ─── -->
<div id="tab-whatsapp" class="tab-panel hidden">
  <div class="settings-panel" style="max-width:640px">
    <h3>Configuração do WhatsApp</h3>
    <div class="subtitle">Número e botão flutuante da loja</div>
    <form method="POST" action="{{ route('admin.settings.update') }}">
      @csrf
      <input type="hidden" name="tab" value="whatsapp">
      <div class="form-group"><label>Número WhatsApp Business (com DDI)</label><input type="tel" name="wa_number" value="{{ $s['wa_number'] ?? '' }}" placeholder="5562994250683"></div>
      <div class="form-group"><label>Nome do Atendente / Loja</label><input type="text" name="wa_name" value="{{ $s['wa_name'] ?? 'A.Seller · Suporte' }}"></div>
      <button type="submit" class="btn btn-dark">Salvar Configuração</button>
    </form>
  </div>
</div>

<!-- ─── STRIPE ─── -->
<div id="tab-stripe" class="tab-panel hidden">
  <div class="settings-panel" style="max-width:640px">
    <h3>Stripe / Pagamentos</h3>
    <div class="subtitle">Chaves de API para processar pagamentos com cartão</div>

    @php
      $pk = $s['stripe_pk'] ?? '';
      $sk = $s['stripe_sk'] ?? '';
      $wh = $s['stripe_webhook'] ?? '';
      $stripeConfigured = !empty($pk) && !empty($sk);
      $isTestMode = str_starts_with($pk, 'pk_test_') || str_starts_with($sk, 'sk_test_');
    @endphp

    <!-- Status badge -->
    <div style="display:flex;align-items:center;gap:10px;padding:.9rem 1rem;border-radius:var(--r);margin-bottom:1.5rem;border:1px solid;
      {{ $stripeConfigured ? 'background:#dcfce7;border-color:#86efac;color:#15803d' : 'background:#fef3c7;border-color:#fcd34d;color:#92400e' }}">
      <span style="font-size:18px">{{ $stripeConfigured ? '✔' : '⚠' }}</span>
      <div>
        <div style="font-weight:600;font-size:13px">
          {{ $stripeConfigured ? 'Pagamentos configurados' : 'Pagamentos não configurados' }}
        </div>
        @if($stripeConfigured && $isTestMode)
          <div style="font-size:12px">Modo de teste ativo — use chaves live_ para produção</div>
        @elseif($stripeConfigured)
          <div style="font-size:12px">Modo live · Pagamentos reais habilitados</div>
        @else
          <div style="font-size:12px">Insira suas chaves abaixo para habilitar o checkout</div>
        @endif
      </div>
    </div>

    <!-- Guia -->
    <div style="background:#f8fafc;border:1px solid var(--gray2);border-radius:var(--r);padding:1rem;margin-bottom:1.5rem;font-size:13px;line-height:1.8;color:var(--gray4)">
      <strong style="color:var(--brand)">Como obter suas chaves:</strong><br>
      1. Acesse <strong>dashboard.stripe.com</strong> e faça login<br>
      2. Vá em <strong>Developers → API Keys</strong><br>
      3. Copie a <strong>Publishable Key</strong> (pk_...) e a <strong>Secret Key</strong> (sk_...)<br>
      4. Para webhooks: <strong>Developers → Webhooks → Add endpoint</strong>
    </div>

    <form method="POST" action="{{ route('admin.settings.stripe') }}">
      @csrf
      <div class="form-group">
        <label>Publishable Key (pk_live_... ou pk_test_...)</label>
        <input type="text" name="stripe_pk" value="{{ $pk }}" placeholder="pk_live_..." autocomplete="off">
      </div>
      <div class="form-group">
        <label>
          Secret Key (sk_...) &nbsp;<span style="color:#b91c1c;font-size:11px">⚠ Nunca compartilhe!</span>
        </label>
        <div style="position:relative">
          <input type="password" name="stripe_sk" id="stripe-sk-input" placeholder="{{ $stripeConfigured ? '••••••••••••••••' : 'sk_live_...' }}" autocomplete="off" style="padding-right:44px">
          <button type="button" onclick="toggleStripeKey()" style="position:absolute;right:10px;top:50%;transform:translateY(-50%);background:none;border:none;cursor:pointer;color:var(--gray3);font-size:12px" id="sk-toggle-btn">mostrar</button>
        </div>
        @if($stripeConfigured)
          <div style="font-size:11px;color:var(--gray3);margin-top:4px">Já configurada. Deixe em branco para manter a atual.</div>
        @endif
      </div>
      <div class="form-group">
        <label>Webhook Secret (whsec_...)</label>
        <input type="text" name="stripe_webhook" value="{{ $wh }}" placeholder="whsec_...">
        <div style="font-size:11px;color:var(--gray3);margin-top:4px">Opcional — necessário apenas para confirmar pagamentos via webhook</div>
      </div>
      <button type="submit" class="btn btn-dark">Salvar Chaves Stripe</button>
    </form>
  </div>
</div>

<!-- ─── REDES SOCIAIS ─── -->
<div id="tab-social" class="tab-panel hidden">
  <div class="settings-panel" style="max-width:640px">
    <h3>Redes Sociais</h3>
    <div class="subtitle">Links que aparecem no rodapé da loja</div>
    <form method="POST" action="{{ route('admin.settings.update') }}">
      @csrf
      <input type="hidden" name="tab" value="social">
      <div class="form-group"><label>Instagram</label><input type="url" name="social_instagram" value="{{ $s['social_instagram'] ?? '' }}" placeholder="https://instagram.com/aseller"></div>
      <div class="form-group"><label>Facebook</label><input type="url" name="social_facebook" value="{{ $s['social_facebook'] ?? '' }}" placeholder="https://facebook.com/aseller"></div>
      <div class="form-group"><label>TikTok</label><input type="url" name="social_tiktok" value="{{ $s['social_tiktok'] ?? '' }}" placeholder="https://tiktok.com/@aseller"></div>
      <div class="form-group"><label>Shopee</label><input type="url" name="social_shopee" value="{{ $s['social_shopee'] ?? '' }}" placeholder="https://shopee.com.br/aseller"></div>
      <button type="submit" class="btn btn-dark">Salvar Redes Sociais</button>
    </form>
  </div>
</div>

<style>
.tab-btn{padding:8px 16px;background:var(--white);border:1.5px solid var(--gray2);border-radius:var(--r);font-family:var(--font-head);font-size:12px;letter-spacing:.5px;cursor:pointer;transition:all .2s;color:var(--gray4)}
.tab-btn:hover{border-color:var(--accent);color:var(--brand)}
.tab-btn.active{background:var(--brand);border-color:var(--brand);color:var(--white)}
.tab-panel.hidden{display:none}
.settings-panel{background:var(--white);border-radius:var(--r2);border:1px solid var(--gray2);padding:1.5rem;margin-bottom:1.5rem}
.settings-panel h3{font-family:var(--font-display);font-size:1.4rem;margin-bottom:.4rem}
.settings-panel .subtitle{font-size:13px;color:var(--gray3);margin-bottom:1.5rem;padding-bottom:1.2rem;border-bottom:1px solid var(--gray2)}
.admin-two-col{display:grid;grid-template-columns:1fr 1fr;gap:1.5rem;align-items:start}
@media(max-width:900px){.admin-two-col{grid-template-columns:1fr}}
.form-row{display:grid;grid-template-columns:1fr 1fr;gap:1rem}
.size-chip-preview{display:inline-flex;align-items:center;gap:5px;padding:4px 10px;background:var(--gray1);border:1px solid var(--gray2);border-radius:20px;font-size:12px;font-family:var(--font-head)}
.size-chip-preview button{background:none;border:none;cursor:pointer;color:var(--gray3);font-size:11px;padding:0;line-height:1}
.size-chip-preview button:hover{color:var(--danger)}
.quick-size-btn{padding:5px 12px;background:var(--white);border:1.5px solid var(--gray2);border-radius:20px;font-size:12px;font-family:var(--font-head);cursor:pointer;transition:all .15s}
.quick-size-btn:hover{border-color:var(--accent);background:#fdf8f0}
</style>
<script>
function showTab(name) {
  document.querySelectorAll('.tab-panel').forEach(p => p.classList.add('hidden'));
  document.querySelectorAll('.tab-btn').forEach(b => b.classList.remove('active'));
  document.getElementById('tab-'+name).classList.remove('hidden');
  document.getElementById('tab-btn-'+name).classList.add('active');
  if (history.replaceState) history.replaceState(null, null, '#'+name);
}
const hash = location.hash.replace('#','');
if (hash && document.getElementById('tab-'+hash)) showTab(hash);

// ── Tamanhos globais ──
function renderSizeChips() {
  const input = document.getElementById('sizes-global-input');
  const container = document.getElementById('sizes-preview-chips');
  if (!input || !container) return;
  const sizes = input.value.split(',').map(s => s.trim()).filter(Boolean);
  container.innerHTML = sizes.map(s =>
    `<span class="size-chip-preview">${s}<button type="button" onclick="removeSize('${s.replace(/'/g,"\\'")}')">✕</button></span>`
  ).join('');
}
function removeSize(size) {
  const input = document.getElementById('sizes-global-input');
  const sizes = input.value.split(',').map(s => s.trim()).filter(s => s && s !== size);
  input.value = sizes.join(',');
  renderSizeChips();
}
function addQuickSize(size) {
  const input = document.getElementById('sizes-global-input');
  const sizes = input.value.split(',').map(s => s.trim()).filter(Boolean);
  if (!sizes.includes(size)) {
    sizes.push(size);
    input.value = sizes.join(',');
    renderSizeChips();
  }
}
document.addEventListener('DOMContentLoaded', () => {
  const input = document.getElementById('sizes-global-input');
  if (input) {
    renderSizeChips();
    input.addEventListener('input', renderSizeChips);
  }
});

// ── Stripe secret toggle ──
function toggleStripeKey() {
  const inp = document.getElementById('stripe-sk-input');
  const btn = document.getElementById('sk-toggle-btn');
  if (inp.type === 'password') { inp.type = 'text'; btn.textContent = 'ocultar'; }
  else { inp.type = 'password'; btn.textContent = 'mostrar'; }
}
</script>
@endsection
