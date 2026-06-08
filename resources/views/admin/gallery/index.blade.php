@extends('layouts.admin')
@section('title','Galeria de Fotos')
@section('content')
<div style="display:grid;grid-template-columns:1fr 360px;gap:1.5rem;align-items:start">
  <div>
    <div class="data-card">
      <div class="data-card-header">
        <h3>Fotos dos Produtos</h3>
        <button class="btn btn-dark" onclick="document.getElementById('gallery-file-input').click()">+ Adicionar Fotos</button>
      </div>
      <div style="padding:1.5rem">

        <!-- Upload area (AJAX) -->
        <div class="gallery-upload-area" id="gallery-drop-zone"
             onclick="document.getElementById('gallery-file-input').click()"
             ondragover="event.preventDefault();this.classList.add('drag-over')"
             ondragleave="this.classList.remove('drag-over')"
             ondrop="event.preventDefault();this.classList.remove('drag-over');handleFileDrop(event.dataTransfer.files)">
          <svg viewBox="0 0 24 24" width="32" height="32" fill="none" stroke="currentColor" stroke-width="1.5"><polyline points="16 16 12 12 8 16"/><line x1="12" y1="12" x2="12" y2="21"/><path d="M20.39 18.39A5 5 0 0018 9h-1.26A8 8 0 103 16.3"/></svg>
          <div style="font-family:var(--font-head);font-size:13px;color:var(--gray3);margin-top:4px">Arraste imagens ou clique para selecionar</div>
          <div style="font-size:11px;color:var(--gray3);margin-top:4px">JPG, PNG, WebP · Máx 5MB por arquivo · Múltiplos arquivos</div>
        </div>
        <input type="file" id="gallery-file-input" name="images[]" accept="image/*" multiple style="display:none" onchange="uploadFiles(this.files)">

        <!-- Upload progress -->
        <div id="upload-progress" style="display:none;margin-bottom:1rem">
          <div style="height:4px;background:var(--gray2);border-radius:2px;overflow:hidden">
            <div id="upload-progress-bar" style="height:100%;background:var(--accent);width:0;transition:width .3s"></div>
          </div>
          <div id="upload-status" style="font-size:12px;color:var(--gray3);margin-top:4px">Enviando...</div>
        </div>

        <div class="gallery-grid" id="gallery-grid">
          @forelse($images as $img)
          <div class="gallery-item {{ $img->product_id ? 'assigned' : '' }}" id="gi-{{ $img->id }}" onclick="selectGalleryImg('{{ $img->url }}', {{ $img->id }})">
            <img src="{{ $img->url }}" alt="{{ $img->filename }}" loading="lazy">
            @if($img->product_id && $img->product)
              <div class="gallery-product-badge">{{ Str::limit($img->product->name, 14) }}</div>
            @endif
            <div class="gallery-item-overlay">
              <button class="gallery-delete-btn" onclick="event.stopPropagation();deleteGalleryImg({{ $img->id }}, this)" title="Excluir">✕</button>
            </div>
          </div>
          @empty
          <div style="grid-column:1/-1;text-align:center;padding:2rem;color:var(--gray3)">Nenhuma foto ainda. Adicione a primeira!</div>
          @endforelse
        </div>
      </div>
    </div>
  </div>

  <!-- Painel lateral: atribuir -->
  <div>
    <div class="settings-panel" style="position:sticky;top:80px">
      <h3>Atribuir Foto</h3>
      <div class="subtitle">Selecione uma foto na galeria e associe a um produto</div>
      <div id="gallery-selected-preview" class="gallery-preview-box">
        <span style="color:var(--gray3);font-size:13px;font-family:var(--font-head)">Nenhuma foto selecionada</span>
      </div>
      <div id="selected-img-name" style="font-size:12px;color:var(--gray3);margin-bottom:1rem;min-height:16px"></div>
      <div class="form-group">
        <label>Produto</label>
        <select id="gallery-product-select">
          <option value="">Selecione um produto...</option>
          @foreach($products as $product)
            <option value="{{ $product->id }}">{{ $product->name }}</option>
          @endforeach
        </select>
      </div>
      <button class="btn btn-dark" style="width:100%;justify-content:center" onclick="assignGalleryImg()">Atribuir ao Produto</button>
      <div id="assign-status" style="margin-top:.8rem;font-size:13px;display:none"></div>
    </div>
  </div>
</div>

<style>
.data-card{background:var(--white);border-radius:var(--r2);border:1px solid var(--gray2);overflow:hidden;margin-bottom:1.5rem}
.data-card-header{padding:1.2rem 1.5rem;border-bottom:1px solid var(--gray2);display:flex;align-items:center;justify-content:space-between}
.data-card-header h3{font-family:var(--font-display);font-size:1.2rem;font-weight:600}
.gallery-upload-area{border:2px dashed var(--gray2);border-radius:var(--r2);padding:2rem;text-align:center;cursor:pointer;transition:all .2s;margin-bottom:1.5rem;color:var(--gray3)}
.gallery-upload-area:hover,.gallery-upload-area.drag-over{border-color:var(--accent);background:#fdf8f0}
.gallery-grid{display:grid;grid-template-columns:repeat(auto-fill,minmax(100px,1fr));gap:8px}
.gallery-item{aspect-ratio:1;background:var(--gray1);border-radius:var(--r);overflow:hidden;cursor:pointer;position:relative;border:2px solid transparent;transition:border-color .2s}
.gallery-item:hover,.gallery-item.selected{border-color:var(--accent)}
.gallery-item.assigned{border-color:var(--gray2)}
.gallery-item.assigned.selected{border-color:var(--accent)}
.gallery-item img{width:100%;height:100%;object-fit:cover}
.gallery-item-overlay{position:absolute;inset:0;background:rgba(0,0,0,.4);display:none;align-items:flex-start;justify-content:flex-end;padding:6px}
.gallery-item:hover .gallery-item-overlay{display:flex}
.gallery-delete-btn{background:rgba(255,255,255,.9);border:none;border-radius:50%;width:24px;height:24px;cursor:pointer;font-size:10px;color:#b91c1c;font-weight:700;display:flex;align-items:center;justify-content:center}
.gallery-product-badge{position:absolute;bottom:0;left:0;right:0;background:rgba(0,0,0,.65);color:#fff;font-size:9px;font-family:var(--font-head);letter-spacing:.5px;padding:3px 5px;text-align:center;white-space:nowrap;overflow:hidden;text-overflow:ellipsis}
.gallery-preview-box{aspect-ratio:1;background:var(--gray1);border-radius:var(--r);margin-bottom:.5rem;display:flex;align-items:center;justify-content:center;overflow:hidden}
.gallery-preview-box img{width:100%;height:100%;object-fit:cover}
.settings-panel{background:var(--white);border-radius:var(--r2);border:1px solid var(--gray2);padding:1.5rem}
.settings-panel h3{font-family:var(--font-display);font-size:1.4rem;margin-bottom:.4rem}
.settings-panel .subtitle{font-size:13px;color:var(--gray3);margin-bottom:1.5rem;padding-bottom:1.2rem;border-bottom:1px solid var(--gray2)}
@media(max-width:900px){body .admin-main > div{grid-template-columns:1fr!important}}
</style>
<script>
const CSRF = document.querySelector('meta[name="csrf-token"]').content;
let selectedGalleryId = null;

function selectGalleryImg(url, id) {
  document.querySelectorAll('.gallery-item').forEach(el => el.classList.remove('selected'));
  const item = document.getElementById('gi-'+id);
  if (item) item.classList.add('selected');
  selectedGalleryId = id;
  const preview = document.getElementById('gallery-selected-preview');
  preview.innerHTML = '<img src="'+url+'" style="width:100%;height:100%;object-fit:cover">';
  const badge = item ? item.querySelector('.gallery-product-badge') : null;
  document.getElementById('selected-img-name').textContent = badge ? '📦 ' + badge.textContent : '';
  document.getElementById('assign-status').style.display = 'none';
}

function assignGalleryImg() {
  const productId = document.getElementById('gallery-product-select').value;
  if (!selectedGalleryId) return alert('Selecione uma foto primeiro.');
  if (!productId) return alert('Selecione um produto.');
  const statusEl = document.getElementById('assign-status');
  fetch("{{ route('admin.gallery.assign') }}", {
    method: 'POST',
    headers: {'Content-Type':'application/json','X-CSRF-TOKEN':CSRF,'Accept':'application/json'},
    body: JSON.stringify({gallery_id: selectedGalleryId, product_id: productId})
  })
  .then(r => r.json())
  .then(data => {
    if (data.success) {
      statusEl.style.display = 'block';
      statusEl.style.color = 'var(--success)';
      const productName = document.getElementById('gallery-product-select').options[document.getElementById('gallery-product-select').selectedIndex].text;
      statusEl.textContent = '✔ Foto atribuída a: ' + productName;
      // Atualizar badge na galeria
      const item = document.getElementById('gi-'+selectedGalleryId);
      if (item) {
        item.classList.add('assigned');
        let badge = item.querySelector('.gallery-product-badge');
        if (!badge) { badge = document.createElement('div'); badge.className = 'gallery-product-badge'; item.appendChild(badge); }
        badge.textContent = productName.substring(0, 14);
      }
    }
  })
  .catch(() => { statusEl.style.display = 'block'; statusEl.style.color = 'var(--danger)'; statusEl.textContent = 'Erro ao atribuir.'; });
}

function deleteGalleryImg(id, btn) {
  if (!confirm('Excluir esta imagem?')) return;
  const item = document.getElementById('gi-'+id);
  fetch("{{ url('admin/gallery') }}/"+id, {
    method: 'DELETE',
    headers: {'X-CSRF-TOKEN':CSRF,'Accept':'application/json','X-HTTP-Method-Override':'DELETE'}
  })
  .then(r => r.json())
  .then(data => {
    if (data.success) {
      item.style.transition = 'opacity .3s,transform .3s';
      item.style.opacity = '0'; item.style.transform = 'scale(.8)';
      setTimeout(() => item.remove(), 300);
      if (selectedGalleryId === id) {
        selectedGalleryId = null;
        document.getElementById('gallery-selected-preview').innerHTML = '<span style="color:var(--gray3);font-size:13px;font-family:var(--font-head)">Nenhuma foto selecionada</span>';
        document.getElementById('selected-img-name').textContent = '';
      }
    }
  });
}

function uploadFiles(files) {
  if (!files || !files.length) return;
  const progress = document.getElementById('upload-progress');
  const bar = document.getElementById('upload-progress-bar');
  const status = document.getElementById('upload-status');
  progress.style.display = 'block';
  bar.style.width = '10%';
  status.textContent = 'Enviando ' + files.length + ' arquivo(s)...';

  const fd = new FormData();
  fd.append('_token', CSRF);
  for (const f of files) fd.append('images[]', f);

  const xhr = new XMLHttpRequest();
  xhr.upload.onprogress = e => { if (e.lengthComputable) bar.style.width = Math.round(e.loaded/e.total*90) + '%'; };
  xhr.onload = () => {
    bar.style.width = '100%';
    try {
      const data = JSON.parse(xhr.responseText);
      if (data.success && data.images) {
        status.textContent = data.images.length + ' foto(s) adicionada(s)!';
        const grid = document.getElementById('gallery-grid');
        const empty = grid.querySelector('[style*="grid-column"]');
        if (empty) empty.remove();
        data.images.forEach(img => {
          const div = document.createElement('div');
          div.className = 'gallery-item';
          div.id = 'gi-' + img.id;
          div.onclick = () => selectGalleryImg(img.url, img.id);
          div.innerHTML = '<img src="'+img.url+'" loading="lazy"><div class="gallery-item-overlay"><button class="gallery-delete-btn" onclick="event.stopPropagation();deleteGalleryImg('+img.id+', this)">✕</button></div>';
          grid.prepend(div);
        });
        setTimeout(() => { progress.style.display='none'; bar.style.width='0'; }, 2000);
      }
    } catch(e) { status.textContent = 'Erro no upload.'; }
  };
  xhr.open('POST', "{{ route('admin.gallery.upload') }}");
  xhr.send(fd);
}

function handleFileDrop(files) { uploadFiles(files); }
</script>
@endsection
