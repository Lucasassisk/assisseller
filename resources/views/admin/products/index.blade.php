@extends('layouts.admin')
@section('title','Produtos')
@section('content')

<div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:1.5rem">
  <h2 style="font-family:var(--font-display);font-size:1.4rem">Gerenciar Produtos</h2>
  <button class="btn btn-dark" onclick="openProductModal()">+ Novo Produto</button>
</div>

<div class="admin-products-grid" id="admin-product-grid">
  @forelse($products as $product)
  <div class="admin-product-card">
    <div class="admin-product-img">
      @if($product->image_url)
        <img src="{{ $product->image_url }}" alt="{{ $product->name }}">
      @else
        <svg viewBox="0 0 24 24" width="48" height="48" fill="none" stroke="#ccc" stroke-width="1"><path d="M21 16V8a2 2 0 00-1-1.73l-7-4a2 2 0 00-2 0l-7 4A2 2 0 003 8v8a2 2 0 001 1.73l7 4a2 2 0 002 0l7-4A2 2 0 0021 16z"/></svg>
      @endif
      @if($product->badge)
        <span style="position:absolute;top:10px;left:10px;background:var(--accent);color:var(--brand);font-size:9px;font-family:var(--font-head);letter-spacing:1px;text-transform:uppercase;padding:3px 8px;border-radius:20px;font-weight:700">{{ $product->badge }}</span>
      @endif
      <div style="position:absolute;top:10px;right:10px">
        <form method="POST" action="{{ route('admin.products.toggle',$product) }}" style="margin:0">
          @csrf @method('PATCH')
          <button type="submit" style="background:{{ $product->active ? '#dcfce7' : '#fee2e2' }};color:{{ $product->active ? '#15803d' : '#b91c1c' }};border:none;border-radius:20px;font-size:9px;font-family:var(--font-head);font-weight:700;padding:3px 8px;cursor:pointer">
            {{ $product->active ? 'ATIVO' : 'INATIVO' }}
          </button>
        </form>
      </div>
    </div>
    <div class="admin-product-body">
      <div class="admin-product-name">{{ $product->name }}</div>
      <div class="admin-product-price">R$ {{ number_format($product->price,2,',','.') }}</div>
      <div style="font-size:11px;color:var(--gray3);margin-bottom:12px">
        Tam: {{ is_array($product->sizes) ? implode(', ',$product->sizes) : $product->sizes }}
      </div>
      <div class="admin-product-actions">
        <button class="btn btn-outline btn-sm" onclick="editProduct({{ $product->id }})">Editar</button>
        <form method="POST" action="{{ route('admin.products.destroy',$product) }}" onsubmit="return confirm('Excluir produto?')" style="margin:0">
          @csrf @method('DELETE')
          <button type="submit" class="btn btn-sm" style="background:#fee2e2;color:#b91c1c;border:none;cursor:pointer">Excluir</button>
        </form>
      </div>
    </div>
  </div>
  @empty
  <div style="grid-column:1/-1;text-align:center;padding:3rem;color:var(--gray3)">
    <p>Nenhum produto cadastrado.</p>
    <button class="btn btn-dark" style="margin-top:1rem" onclick="openProductModal()">+ Adicionar Primeiro Produto</button>
  </div>
  @endforelse
</div>

<!-- MODAL PRODUTO -->
<div class="modal-overlay hidden" id="product-modal">
  <div class="modal">
    <div class="modal-header">
      <h2 id="product-modal-title">Novo Produto</h2>
      <button class="modal-close" onclick="closeProductModal()">✕</button>
    </div>
    <div class="modal-body">
      <form id="product-form" method="POST" action="{{ route('admin.products.store') }}">
        @csrf
        <input type="hidden" name="_method" id="form-method" value="POST">
        <input type="hidden" name="_product_id" id="form-product-id" value="">
        <div class="form-group"><label>Nome do Produto</label><input type="text" name="name" id="pm-name" placeholder="Havaianas Brasil Branca" required></div>
        <div class="form-group"><label>Descrição Curta</label><input type="text" name="description" id="pm-desc" placeholder="Clássica com bandeirinha bordada..."></div>
        <div class="form-row">
          <div class="form-group"><label>Preço (R$)</label><input type="number" name="price" id="pm-price" value="37.99" step="0.01" required></div>
          <div class="form-group"><label>Badge</label>
            <select name="badge" id="pm-badge">
              <option value="">Nenhum</option>
              <option value="bestseller">Mais Vendido</option>
              <option value="new">Novo</option>
              <option value="kit">Kit</option>
            </select>
          </div>
        </div>
        <div class="form-group">
          <label>Tamanhos disponíveis</label>
          <input type="hidden" name="sizes" id="pm-sizes-value">
          <div id="pm-sizes-chips" class="sizes-chip-group"></div>
          <div style="display:flex;gap:6px;margin-top:8px">
            <input type="text" id="pm-custom-size" placeholder="Tamanho personalizado..." style="flex:1;padding:7px 10px;border:1.5px solid var(--gray2);border-radius:var(--r);font-size:13px;font-family:var(--font-body);outline:none" onkeydown="if(event.key==='Enter'){event.preventDefault();addCustomSizeModal()}">
            <button type="button" onclick="addCustomSizeModal()" class="btn btn-outline btn-sm">+</button>
          </div>
          <div style="font-size:11px;color:var(--gray3);margin-top:4px">Clique nos tamanhos para selecionar/desmarcar</div>
        </div>
        <div class="form-group"><label>URL da Imagem</label><input type="url" name="image_url" id="pm-image" placeholder="https://..."></div>
        <div class="form-row">
          <div class="form-group"><label>Estoque</label><input type="number" name="stock" id="pm-stock" value="50" min="0"></div>
          <div class="form-group" style="display:flex;align-items:flex-end;padding-bottom:0">
            <label style="display:flex;align-items:center;gap:8px;cursor:pointer">
              <input type="checkbox" name="active" id="pm-active" value="1" checked>
              Produto ativo
            </label>
          </div>
        </div>
        <div style="display:flex;gap:10px;justify-content:flex-end;margin-top:1.5rem">
          <button type="button" class="btn btn-outline" onclick="closeProductModal()">Cancelar</button>
          <button type="submit" class="btn btn-dark">Salvar Produto</button>
        </div>
      </form>
    </div>
  </div>
</div>

<style>
.sizes-chip-group{display:flex;flex-wrap:wrap;gap:6px;padding:8px 0}
.size-chip{padding:5px 12px;border:1.5px solid var(--gray2);border-radius:20px;font-size:12px;font-family:var(--font-head);cursor:pointer;transition:all .15s;background:var(--white);color:var(--gray4);user-select:none}
.size-chip:hover{border-color:var(--accent);color:var(--brand)}
.size-chip.selected{background:var(--brand);border-color:var(--brand);color:var(--white)}
.size-chip.custom{border-style:dashed}
.admin-products-grid{display:grid;grid-template-columns:repeat(auto-fill,minmax(220px,1fr));gap:1.2rem}
.admin-product-card{background:var(--white);border-radius:var(--r2);border:1px solid var(--gray2);overflow:hidden}
.admin-product-img{height:220px;background:var(--gray1);display:flex;align-items:center;justify-content:center;position:relative;overflow:hidden}
.admin-product-img img{width:100%;height:100%;object-fit:contain;padding:8px}
.admin-product-body{padding:1rem}
.admin-product-name{font-family:var(--font-display);font-size:1rem;font-weight:600;margin-bottom:4px}
.admin-product-price{font-size:13px;color:var(--accent);font-weight:600;margin-bottom:4px}
.admin-product-actions{display:flex;gap:8px;flex-wrap:wrap}
.modal-overlay{position:fixed;inset:0;background:rgba(0,0,0,.5);z-index:500;display:flex;align-items:center;justify-content:center;padding:1rem}
.modal-overlay.hidden{display:none}
.modal{background:var(--white);border-radius:var(--r2);width:100%;max-width:560px;max-height:90vh;overflow-y:auto}
.modal-header{padding:1.5rem;border-bottom:1px solid var(--gray2);display:flex;align-items:center;justify-content:space-between}
.modal-header h2{font-family:var(--font-display);font-size:1.4rem}
.modal-close{background:none;border:none;font-size:1.2rem;cursor:pointer;color:var(--gray3);padding:4px 8px}
.modal-body{padding:1.5rem}
.form-row{display:grid;grid-template-columns:1fr 1fr;gap:1rem}
</style>

<script>
const products = @json($products->keyBy('id'));
const globalSizes = @json(array_values($globalSizes));

let selectedSizes = [];

function renderSizeChips(productSizes) {
  selectedSizes = productSizes ? [...productSizes] : [...globalSizes];
  const container = document.getElementById('pm-sizes-chips');
  container.innerHTML = '';
  // Chips dos tamanhos globais
  const allChips = new Set([...globalSizes, ...selectedSizes]);
  allChips.forEach(size => {
    const chip = document.createElement('span');
    chip.className = 'size-chip' + (selectedSizes.includes(size) ? ' selected' : '') + (!globalSizes.includes(size) ? ' custom' : '');
    chip.textContent = size;
    chip.onclick = () => toggleSizeChip(size, chip);
    container.appendChild(chip);
  });
  updateSizesInput();
}

function toggleSizeChip(size, chip) {
  const idx = selectedSizes.indexOf(size);
  if (idx >= 0) { selectedSizes.splice(idx, 1); chip.classList.remove('selected'); }
  else { selectedSizes.push(size); chip.classList.add('selected'); }
  updateSizesInput();
}

function updateSizesInput() {
  document.getElementById('pm-sizes-value').value = selectedSizes.join(',');
}

function addCustomSizeModal() {
  const inp = document.getElementById('pm-custom-size');
  const size = inp.value.trim().toUpperCase();
  if (!size) return;
  inp.value = '';
  if (!selectedSizes.includes(size)) {
    selectedSizes.push(size);
    const container = document.getElementById('pm-sizes-chips');
    const chip = document.createElement('span');
    chip.className = 'size-chip selected custom';
    chip.textContent = size;
    chip.onclick = () => toggleSizeChip(size, chip);
    container.appendChild(chip);
    updateSizesInput();
  }
}

function openProductModal(id = null) {
  const form = document.getElementById('product-form');
  const title = document.getElementById('product-modal-title');
  if (id && products[id]) {
    const p = products[id];
    title.textContent = 'Editar Produto';
    document.getElementById('pm-name').value = p.name;
    document.getElementById('pm-desc').value = p.description || '';
    document.getElementById('pm-price').value = p.price;
    document.getElementById('pm-badge').value = p.badge || '';
    renderSizeChips(Array.isArray(p.sizes) ? p.sizes : (p.sizes ? [p.sizes] : globalSizes));
    document.getElementById('pm-image').value = p.image_url || '';
    document.getElementById('pm-stock').value = p.stock;
    document.getElementById('pm-active').checked = p.active == 1;
    document.getElementById('form-method').value = 'PUT';
    document.getElementById('form-product-id').value = id;
    form.action = `/admin/products/${id}`;
  } else {
    title.textContent = 'Novo Produto';
    form.reset();
    document.getElementById('pm-price').value = '37.99';
    document.getElementById('pm-stock').value = '50';
    document.getElementById('pm-active').checked = true;
    document.getElementById('form-method').value = 'POST';
    document.getElementById('form-product-id').value = '';
    form.action = "{{ route('admin.products.store') }}";
    renderSizeChips(globalSizes);
  }
  document.getElementById('product-modal').classList.remove('hidden');
}

function editProduct(id) { openProductModal(id); }
function closeProductModal() { document.getElementById('product-modal').classList.add('hidden'); }
</script>
@endsection
