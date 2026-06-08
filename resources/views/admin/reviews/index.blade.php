@extends('layouts.admin')
@section('title','Avaliações')
@section('content')

<div style="display:grid;grid-template-columns:1fr 1fr;gap:1.5rem;align-items:start">

  {{-- PENDENTES --}}
  <div>
    <div class="data-card">
      <div class="data-card-header">
        <h3>Aguardando Aprovação</h3>
        @if($pending->count())
          <span style="background:#fef3c7;color:#92400e;font-size:11px;font-family:var(--font-head);padding:4px 10px;border-radius:20px;font-weight:600">{{ $pending->count() }} pendente(s)</span>
        @endif
      </div>
      <div style="padding:1rem">
        @forelse($pending as $r)
        <div class="review-card review-pending">
          <div class="review-header">
            <div class="review-avatar">{{ strtoupper(substr($r->name,0,1)) }}</div>
            <div>
              <div class="review-name">{{ $r->name }}</div>
              <div class="review-meta">
                @if($r->product) <span>{{ $r->product->name }}</span> · @endif
                {{ $r->created_at->format('d/m/Y') }}
              </div>
            </div>
            <div class="review-stars">
              @for($i=1;$i<=5;$i++)
                <span style="color:{{ $i<=$r->rating ? '#c8a96e' : '#e8e4de' }}">★</span>
              @endfor
            </div>
          </div>
          <p class="review-comment">{{ $r->comment }}</p>
          <div class="review-actions">
            <form method="POST" action="{{ route('admin.reviews.approve', $r) }}" style="display:inline">
              @csrf
              <button type="submit" class="btn btn-dark btn-sm">✔ Aprovar e Publicar</button>
            </form>
            <form method="POST" action="{{ route('admin.reviews.destroy', $r) }}" style="display:inline" onsubmit="return confirm('Excluir avaliação?')">
              @csrf @method('DELETE')
              <button type="submit" class="btn btn-outline btn-sm" style="color:var(--danger);border-color:var(--danger)">✕ Excluir</button>
            </form>
          </div>
        </div>
        @empty
        <div style="text-align:center;padding:2rem;color:var(--gray3);font-size:14px">Nenhuma avaliação pendente.</div>
        @endforelse
      </div>
    </div>
  </div>

  {{-- APROVADAS --}}
  <div>
    <div class="data-card">
      <div class="data-card-header">
        <h3>Publicadas na Loja</h3>
        <span style="background:#dcfce7;color:#15803d;font-size:11px;font-family:var(--font-head);padding:4px 10px;border-radius:20px;font-weight:600">{{ $approved->count() }} publicada(s)</span>
      </div>
      <div style="padding:1rem">
        @forelse($approved as $r)
        <div class="review-card review-approved">
          <div class="review-header">
            <div class="review-avatar">{{ strtoupper(substr($r->name,0,1)) }}</div>
            <div>
              <div class="review-name">{{ $r->name }}</div>
              <div class="review-meta">
                @if($r->product) <span>{{ $r->product->name }}</span> · @endif
                {{ $r->created_at->format('d/m/Y') }}
              </div>
            </div>
            <div class="review-stars">
              @for($i=1;$i<=5;$i++)
                <span style="color:{{ $i<=$r->rating ? '#c8a96e' : '#e8e4de' }}">★</span>
              @endfor
            </div>
          </div>
          <p class="review-comment">{{ $r->comment }}</p>
          <div class="review-actions">
            <form method="POST" action="{{ route('admin.reviews.destroy', $r) }}" style="display:inline" onsubmit="return confirm('Remover da loja?')">
              @csrf @method('DELETE')
              <button type="submit" class="btn btn-outline btn-sm" style="color:var(--danger);border-color:var(--danger)">✕ Remover</button>
            </form>
          </div>
        </div>
        @empty
        <div style="text-align:center;padding:2rem;color:var(--gray3);font-size:14px">Nenhuma avaliação publicada ainda.</div>
        @endforelse
      </div>
    </div>
  </div>

</div>

<style>
.data-card{background:var(--white);border-radius:var(--r2);border:1px solid var(--gray2);overflow:hidden;margin-bottom:1.5rem}
.data-card-header{padding:1.2rem 1.5rem;border-bottom:1px solid var(--gray2);display:flex;align-items:center;justify-content:space-between}
.data-card-header h3{font-family:var(--font-display);font-size:1.2rem;font-weight:600}
.review-card{background:var(--gray1);border-radius:var(--r);padding:1rem;margin-bottom:.8rem;border:1px solid var(--gray2)}
.review-card:last-child{margin-bottom:0}
.review-pending{border-left:3px solid #fbbf24}
.review-approved{border-left:3px solid var(--success)}
.review-header{display:flex;align-items:flex-start;gap:10px;margin-bottom:.6rem}
.review-avatar{width:36px;height:36px;border-radius:50%;background:var(--accent);display:flex;align-items:center;justify-content:center;font-family:var(--font-head);font-size:14px;font-weight:700;color:var(--brand);flex-shrink:0}
.review-name{font-size:14px;font-weight:600;font-family:var(--font-head);color:var(--brand)}
.review-meta{font-size:11px;color:var(--gray3);font-family:var(--font-head)}
.review-stars{margin-left:auto;font-size:16px;white-space:nowrap}
.review-comment{font-size:13px;color:var(--gray4);line-height:1.6;margin-bottom:.8rem}
.review-actions{display:flex;gap:.5rem;flex-wrap:wrap}
@media(max-width:900px){body .admin-main > div{grid-template-columns:1fr!important}}
</style>
@endsection
