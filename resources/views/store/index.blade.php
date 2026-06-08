<!DOCTYPE html>
<html lang="pt-BR">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta name="csrf-token" content="{{ csrf_token() }}">
<link rel="icon" type="image/svg+xml" href="/favicon.svg">
@if(!empty($settings['stripe_pk']))
<script src="https://js.stripe.com/v3/"></script>
@endif
<title>{{ $settings['store_name'] ?? 'A.Seller' }} — Havaianas Brancas</title>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:ital,wght@0,300;0,400;0,600;1,400;1,600&family=Syne:wght@400;500;600;700&display=swap" rel="stylesheet">
<style>
*,*::before,*::after{box-sizing:border-box;margin:0;padding:0}
:root{
  --brand:{{ $settings['brand_color'] ?? '#0a0a0a' }};
  --accent:{{ $settings['accent_color'] ?? '#c8a96e' }};
  --off:{{ $settings['bg_color'] ?? '#faf9f7' }};
  --white:#fff;--gray1:#f4f2ef;--gray2:#e8e4de;--gray3:#a09880;--gray4:#6b6455;
  --success:#2d7a4f;--danger:#c0392b;
  --r:8px;--r2:14px;--r3:24px;
  --shadow:0 2px 8px rgba(0,0,0,.07),0 8px 24px rgba(0,0,0,.05);
  --shadow2:0 12px 40px rgba(0,0,0,.14);
  --font-display:'Cormorant Garamond',serif;
  --font-head:'Syne',sans-serif;
}
html{scroll-behavior:smooth}
body{font-family:var(--font-head);background:var(--off);color:var(--brand);overflow-x:hidden}

/* TOPBAR */
#store-topbar{background:var(--brand);color:var(--white);padding:10px;font-size:11px;font-family:var(--font-head);letter-spacing:2px;text-transform:uppercase;overflow:hidden;white-space:nowrap}
#store-topbar .topbar-track{display:inline-block;animation:topbar-scroll 28s linear infinite}
@keyframes topbar-scroll{0%{transform:translateX(0)}100%{transform:translateX(-50%)}}

/* NAV */
nav{background:var(--white);border-bottom:1px solid var(--gray2);padding:0 2rem;height:70px;display:flex;align-items:center;justify-content:space-between;position:sticky;top:0;z-index:50;box-shadow:0 1px 0 var(--gray2)}
.nav-logo{font-family:var(--font-display);font-size:1.8rem;font-weight:600;color:var(--brand);text-decoration:none;cursor:pointer}
.nav-logo span{color:var(--accent)}
.nav-links{display:flex;gap:2.5rem;list-style:none}
.nav-links a{font-size:12px;letter-spacing:2px;text-transform:uppercase;color:var(--gray4);text-decoration:none;cursor:pointer;transition:color .2s}
.nav-links a:hover,.nav-links a.accent{color:var(--accent)}
.nav-right{display:flex;align-items:center;gap:1rem}
.cart-btn{position:relative;background:none;border:none;cursor:pointer;padding:.5rem;color:var(--brand)}
.cart-btn svg{width:22px;height:22px}
.cart-count{position:absolute;top:-2px;right:-2px;background:var(--accent);color:#fff;border-radius:50%;width:18px;height:18px;font-size:10px;font-family:var(--font-head);font-weight:700;display:flex;align-items:center;justify-content:center;display:none}

/* HERO */
.hero{min-height:92vh;display:grid;grid-template-columns:1fr 1fr;align-items:center;max-width:1280px;margin:0 auto;padding:4rem 2rem;gap:4rem}
.hero-eyebrow{font-family:var(--font-head);font-size:11px;letter-spacing:4px;text-transform:uppercase;color:var(--accent);margin-bottom:1.5rem;display:flex;align-items:center;gap:12px}
.hero-eyebrow::before{content:'';display:block;width:32px;height:1px;background:var(--accent)}
.hero h1{font-family:var(--font-display);font-size:clamp(3.5rem,6vw,5.5rem);line-height:1;font-weight:600;margin-bottom:1.5rem;color:var(--brand)}
.hero h1 em{font-style:italic;color:var(--accent)}
.hero-desc{font-size:16px;color:var(--gray4);line-height:1.8;margin-bottom:2.5rem;max-width:420px}
.hero-price{font-family:var(--font-display);font-size:3rem;font-weight:700;color:var(--brand);margin-bottom:2rem;line-height:1}
.hero-price small{font-size:1rem;font-family:var(--font-head);color:var(--gray3);font-weight:400;display:block;margin-bottom:6px;letter-spacing:1px}
.hero-btns{display:flex;gap:14px;flex-wrap:wrap}
.hero-right{display:flex;align-items:center;justify-content:center;position:relative}
.hero-img-wrap{width:480px;height:540px;border-radius:var(--r3);background:linear-gradient(145deg,#f0ede6,#e8e4db);display:flex;align-items:center;justify-content:center;overflow:hidden;position:relative}
.hero-img-wrap img,.hero-video{width:100%;height:100%;object-fit:cover;border-radius:var(--r3);display:block}
.hero-video-iframe{width:100%;height:100%;border:none;border-radius:var(--r3)}
.hero-float-badge{position:absolute;bottom:30px;left:-20px;background:var(--white);border-radius:var(--r2);padding:16px 20px;box-shadow:var(--shadow2);border:1px solid var(--gray2)}
.hero-float-badge .num{font-family:var(--font-display);font-size:2rem;font-weight:700;color:var(--accent)}
.hero-float-badge .label{font-family:var(--font-head);font-size:10px;letter-spacing:1px;text-transform:uppercase;color:var(--gray3)}

/* BTNS */
.btn{display:inline-flex;align-items:center;gap:8px;padding:.85rem 1.75rem;border-radius:var(--r);font-family:var(--font-head);font-size:13px;font-weight:600;letter-spacing:1px;cursor:pointer;border:none;transition:all .2s;text-decoration:none;text-transform:uppercase}
.btn-dark{background:var(--brand);color:var(--white)}
.btn-dark:hover{background:#333}
.btn-outline{background:transparent;color:var(--brand);border:1.5px solid var(--brand)}
.btn-outline:hover{background:var(--brand);color:var(--white)}
.btn-accent{background:var(--accent);color:var(--white)}
.btn-accent:hover{opacity:.88}
.btn-sm{padding:.55rem 1rem;font-size:12px}
.btn-full{width:100%;justify-content:center}

/* PRODUCTS */
.store-section{max-width:1280px;margin:0 auto;padding:5rem 2rem}
.section-head{text-align:center;margin-bottom:3.5rem}
.section-eyebrow{font-family:var(--font-head);font-size:11px;letter-spacing:4px;text-transform:uppercase;color:var(--accent);margin-bottom:.75rem}
.section-title{font-family:var(--font-display);font-size:clamp(2rem,4vw,3rem);font-weight:600}
.products-grid{display:grid;grid-template-columns:repeat(auto-fill,minmax(260px,1fr));gap:2rem}
.product-card{background:var(--white);border-radius:var(--r2);overflow:hidden;border:1px solid var(--gray2);transition:all .3s;cursor:pointer}
.product-card:hover{transform:translateY(-6px);box-shadow:var(--shadow2)}
.product-img-area{aspect-ratio:1/1;background:var(--gray1);display:flex;align-items:center;justify-content:center;position:relative;overflow:hidden}
.product-img-area img{width:100%;height:100%;object-fit:contain;transition:transform .4s;padding:12px}
.product-card:hover .product-img-area img{transform:scale(1.04)}
.product-badge{position:absolute;top:12px;left:12px;background:var(--accent);color:#fff;font-size:10px;letter-spacing:1px;text-transform:uppercase;padding:4px 10px;border-radius:20px;font-family:var(--font-head);font-weight:600}
.product-body{padding:1.25rem}
.product-name{font-family:var(--font-display);font-size:1.2rem;font-weight:600;margin-bottom:.25rem}
.product-price{font-size:1.1rem;font-weight:700;color:var(--accent);margin-bottom:1rem}
.size-grid{display:flex;flex-wrap:wrap;gap:6px;margin-bottom:1rem}
.size-btn{width:36px;height:36px;border:1.5px solid var(--gray2);background:none;border-radius:var(--r);font-size:12px;font-family:var(--font-head);cursor:pointer;transition:all .2s;display:flex;align-items:center;justify-content:center}
.size-btn:hover,.size-btn.selected{border-color:var(--brand);background:var(--brand);color:#fff}
.product-footer{display:flex;align-items:center;justify-content:space-between;gap:1rem}
.qty-ctrl{display:flex;align-items:center;gap:.5rem}
.qty-ctrl button{width:30px;height:30px;border:1.5px solid var(--gray2);background:none;border-radius:var(--r);cursor:pointer;font-size:16px;display:flex;align-items:center;justify-content:center;transition:all .2s}
.qty-ctrl button:hover{border-color:var(--brand);background:var(--brand);color:#fff}
.qty-ctrl span{width:24px;text-align:center;font-weight:600}

/* REVIEWS CAROUSEL */
.reviews-carousel-wrap{position:relative;overflow:hidden;padding:0 48px}
.reviews-track{display:flex;gap:1.5rem;transition:transform .4s ease}
.review-slide{flex:0 0 calc(33.333% - 1rem);background:var(--gray1);border-radius:16px;padding:1.8rem;border:1px solid var(--gray2)}
.review-stars-disp{font-size:22px;margin-bottom:1rem;letter-spacing:2px}
.review-text{font-family:var(--font-display);font-size:1.15rem;color:var(--brand);line-height:1.7;margin-bottom:1.5rem;font-style:italic}
.review-author{display:flex;align-items:center;gap:10px}
.review-avi{width:38px;height:38px;border-radius:50%;background:var(--accent);display:flex;align-items:center;justify-content:center;font-family:var(--font-head);font-size:14px;font-weight:700;color:var(--brand);flex-shrink:0}
.carousel-btn{position:absolute;top:50%;transform:translateY(-50%);background:var(--white);border:1.5px solid var(--gray2);border-radius:50%;width:40px;height:40px;font-size:22px;cursor:pointer;display:flex;align-items:center;justify-content:center;color:var(--brand);transition:all .2s;z-index:2}
.carousel-btn:hover{background:var(--brand);color:var(--white);border-color:var(--brand)}
.carousel-prev{left:0}.carousel-next{right:0}
.carousel-dots{display:flex;justify-content:center;gap:6px;margin-top:1.5rem}
.carousel-dot{width:8px;height:8px;border-radius:50%;background:var(--gray2);border:none;cursor:pointer;padding:0;transition:background .2s}
.carousel-dot.active{background:var(--accent)}
.star-rating{display:flex;gap:4px;margin-bottom:.3rem}
.star-pick{font-size:28px;cursor:pointer;color:#e8e4de;transition:color .15s;line-height:1}
.star-pick.on{color:#c8a96e}
@media(max-width:900px){.review-slide{flex:0 0 calc(50% - .75rem)}}
@media(max-width:600px){.review-slide{flex:0 0 100%;}.reviews-carousel-wrap{padding:0 36px}}

/* FEATURES */
.features-sec{background:var(--white);padding:5rem 2rem}
.features-inner{max-width:1280px;margin:0 auto}
.features-grid{display:grid;grid-template-columns:repeat(auto-fit,minmax(200px,1fr));gap:2.5rem;margin-top:3rem}
.feature-item{text-align:center}
.feature-icon{width:56px;height:56px;background:var(--off);border-radius:var(--r2);display:flex;align-items:center;justify-content:center;margin:0 auto 1rem;color:var(--accent)}
.feature-icon svg{width:26px;height:26px}
.feature-title{font-family:var(--font-display);font-size:1.1rem;font-weight:600;margin-bottom:.4rem}
.feature-desc{font-size:13px;color:var(--gray3);line-height:1.6}

/* CART */
.cart-overlay{position:fixed;inset:0;background:rgba(0,0,0,.4);z-index:90;display:none}
.cart-overlay.open{display:block}
.cart-drawer{position:fixed;right:0;top:0;bottom:0;width:420px;max-width:95vw;background:var(--white);z-index:100;transform:translateX(100%);transition:transform .35s cubic-bezier(.4,0,.2,1);display:flex;flex-direction:column;box-shadow:var(--shadow2)}
.cart-drawer.open{transform:translateX(0)}
.cart-head{padding:1.5rem 2rem;border-bottom:1px solid var(--gray2);display:flex;align-items:center;justify-content:space-between}
.cart-head h2{font-family:var(--font-display);font-size:1.4rem;font-weight:600}
.cart-items{flex:1;overflow-y:auto;padding:1.5rem 2rem}
.cart-item{display:flex;gap:1rem;padding:1rem 0;border-bottom:1px solid var(--gray1)}
.cart-item-img{width:70px;height:70px;border-radius:var(--r);background:var(--gray1);overflow:hidden;flex-shrink:0}
.cart-item-img img{width:100%;height:100%;object-fit:contain;padding:4px}
.cart-item-info{flex:1}
.cart-item-name{font-family:var(--font-display);font-size:1rem;font-weight:600}
.cart-item-size{font-size:12px;color:var(--gray3)}
.cart-item-price{font-weight:700;color:var(--accent)}
.cart-footer{padding:1.5rem 2rem;border-top:1px solid var(--gray2)}
.cart-subtotal{display:flex;justify-content:space-between;font-size:15px;margin-bottom:1rem;font-weight:600}
.checkout-btn{width:100%;padding:1rem;background:var(--brand);color:#fff;border:none;border-radius:var(--r);font-family:var(--font-head);font-size:14px;font-weight:600;letter-spacing:1px;cursor:pointer;transition:background .2s}
.checkout-btn:hover{background:#333}

/* CHECKOUT */
#page-checkout,#page-success{display:none;max-width:680px;margin:4rem auto;padding:0 2rem}
#page-checkout.active,#page-success.active{display:block}
.checkout-title{font-family:var(--font-display);font-size:2rem;font-weight:600;margin-bottom:2rem}
.checkout-section{background:var(--white);border-radius:var(--r2);border:1px solid var(--gray2);padding:1.5rem;margin-bottom:1.5rem}
.checkout-section h3{font-family:var(--font-display);font-size:1.2rem;margin-bottom:1.25rem}
.form-group{margin-bottom:1rem}
.form-group label{display:block;font-size:10px;letter-spacing:1.5px;text-transform:uppercase;color:var(--gray3);margin-bottom:.35rem}
.form-control-store{width:100%;padding:.7rem 1rem;border:1.5px solid var(--gray2);border-radius:var(--r);font-size:14px;font-family:var(--font-head);color:var(--brand)}
.form-control-store:focus{outline:none;border-color:var(--accent)}
.form-row{display:grid;grid-template-columns:1fr 1fr;gap:1rem}
.payment-opt{display:flex;gap:.75rem;flex-wrap:wrap;margin-bottom:1rem}
.payment-btn{padding:.6rem 1.2rem;border:1.5px solid var(--gray2);border-radius:var(--r);background:none;font-size:13px;font-family:var(--font-head);cursor:pointer;transition:all .2s}
.payment-btn.active,.payment-btn:hover{border-color:var(--brand);background:var(--brand);color:#fff}
.order-summary{background:var(--gray1);border-radius:var(--r);padding:1rem;margin-bottom:1rem;font-size:13px}
.order-summary-row{display:flex;justify-content:space-between;padding:.35rem 0}
.order-summary-row.total{font-weight:700;font-size:15px;border-top:1px solid var(--gray2);margin-top:.35rem;padding-top:.5rem}
.pay-btn{width:100%;padding:1.1rem;background:var(--brand);color:#fff;border:none;border-radius:var(--r);font-family:var(--font-head);font-size:14px;font-weight:600;letter-spacing:1px;cursor:pointer;transition:background .2s}
.pay-btn:hover{background:#333}
.pay-btn:disabled{opacity:.6;cursor:not-allowed}

/* SUCCESS */
.success-box{text-align:center;padding:3rem 2rem;background:var(--white);border-radius:var(--r3);border:1px solid var(--gray2)}
.success-icon{width:72px;height:72px;background:#d1fae5;border-radius:50%;display:flex;align-items:center;justify-content:center;margin:0 auto 1.5rem;font-size:2rem}
.success-title{font-family:var(--font-display);font-size:2rem;font-weight:600;margin-bottom:.5rem}
.success-sub{color:var(--gray3);margin-bottom:2rem}

/* FOOTER */
footer{background:var(--brand);color:rgba(255,255,255,.6);padding:4rem 2rem 2rem}
.footer-inner{max-width:1280px;margin:0 auto}
.footer-grid{display:grid;grid-template-columns:2fr 1fr 1fr 1fr;gap:3rem;margin-bottom:3rem}
.footer-brand{font-family:var(--font-display);font-size:2.5rem;color:var(--white);margin-bottom:.75rem}
.footer-brand span{color:var(--accent)}
.footer-desc{font-size:13px;line-height:1.8;max-width:280px}
.footer-col h4{font-family:var(--font-head);font-size:11px;letter-spacing:2px;text-transform:uppercase;color:var(--white);margin-bottom:1rem}
.footer-col ul{list-style:none}
.footer-col li{padding:5px 0;font-size:13px}
.footer-col a{color:rgba(255,255,255,.5);text-decoration:none;cursor:pointer;transition:color .2s}
.footer-col a:hover{color:var(--accent)}
.footer-bottom{border-top:1px solid rgba(255,255,255,.1);padding-top:1.5rem;display:flex;justify-content:space-between;align-items:center;font-size:12px;flex-wrap:wrap;gap:1rem}
.social-links{display:flex;gap:14px;align-items:center}
.social-links a{color:rgba(255,255,255,.5);transition:color .2s;display:inline-flex}
.social-links a:hover{color:var(--accent)}
.social-links svg{width:20px;height:20px;fill:currentColor}

/* WA */
.wa-float{position:fixed;bottom:2rem;right:2rem;z-index:80;display:flex;flex-direction:column;align-items:flex-end;gap:.75rem}
.wa-fab{width:60px;height:60px;border-radius:50%;background:#25D366;border:none;cursor:pointer;box-shadow:0 4px 20px rgba(37,211,102,.4);display:flex;align-items:center;justify-content:center;transition:transform .2s}
.wa-fab:hover{transform:scale(1.08)}
.wa-fab svg{width:30px;height:30px;fill:#fff}
.wa-bubble{background:var(--white);border-radius:var(--r2);box-shadow:var(--shadow2);width:300px;display:none}
.wa-bubble.open{display:block}
.wa-bubble-head{background:#075E54;padding:1rem 1.25rem;border-radius:var(--r2) var(--r2) 0 0;display:flex;align-items:center;gap:.75rem}
.wa-name{font-weight:600;color:#fff;font-size:14px}
.wa-status{font-size:11px;color:rgba(255,255,255,.7)}
.wa-msgs{padding:.75rem;display:flex;flex-direction:column;gap:.5rem}
.wa-msg-btn{padding:.6rem 1rem;background:var(--off);border:none;border-radius:var(--r);text-align:left;font-size:12px;font-family:var(--font-head);cursor:pointer;transition:background .2s;color:var(--brand)}
.wa-msg-btn:hover{background:var(--gray2)}

/* TOAST */
.toast{position:fixed;bottom:2rem;left:50%;transform:translateX(-50%) translateY(100px);background:var(--brand);color:#fff;padding:.85rem 1.75rem;border-radius:var(--r);font-size:13px;font-family:var(--font-head);z-index:999;transition:transform .3s;box-shadow:var(--shadow2);white-space:nowrap}
.toast.show{transform:translateX(-50%) translateY(0)}
.toast.success{background:var(--success)}
.toast.error{background:var(--danger)}

@media(max-width:768px){
  .hero{grid-template-columns:1fr;min-height:auto;padding:3rem 1.5rem}
  .hero-right{display:none}
  .footer-grid{grid-template-columns:1fr 1fr;gap:2rem}
  .nav-links{display:none}
}
</style>
</head>
<body>

<div id="store-topbar">
  <span class="topbar-track">{{ $settings['topbar_text'] ?? '🇧🇷 Frete Grátis acima de R$99 · Pix com 5% · Parcele em 3x' }} &nbsp;·&nbsp; {{ $settings['topbar_text'] ?? '🇧🇷 Frete Grátis acima de R$99 · Pix com 5% · Parcele em 3x' }} &nbsp;&nbsp;&nbsp;</span>
</div>

<nav>
  <a class="nav-logo" onclick="showPage('store')">A.<span>Seller</span></a>
  <ul class="nav-links">
    <li><a onclick="document.getElementById('products').scrollIntoView({behavior:'smooth'})">Produtos</a></li>
    <li><a onclick="document.getElementById('features-sec').scrollIntoView({behavior:'smooth'})">Diferenciais</a></li>
    <li><a onclick="document.getElementById('store-footer').scrollIntoView({behavior:'smooth'})">Contato</a></li>
    <li><a onclick="document.getElementById('rastrear-section').scrollIntoView({behavior:'smooth'})" style="cursor:pointer">Rastrear Pedido</a></li>
  </ul>
  <div class="nav-right">
    <button class="cart-btn" onclick="toggleCart()" aria-label="Carrinho">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M6 2L3 6v14a2 2 0 002 2h14a2 2 0 002-2V6l-3-4z"/><line x1="3" y1="6" x2="21" y2="6"/><path d="M16 10a4 4 0 01-8 0"/></svg>
      <span class="cart-count" id="cart-count">0</span>
    </button>
  </div>
</nav>

{{-- STORE PAGE --}}
<div id="page-store">
  {{-- HERO --}}
  <section class="hero">
    <div class="hero-left">
      <div class="hero-eyebrow">Coleção Exclusiva</div>
      <h1>
        {{ $settings['hero_title_1'] ?? 'Havaianas' }}<br>
        <em>{{ $settings['hero_title_2'] ?? 'Brancas' }}</em><br>
        {{ $settings['hero_title_3'] ?? 'do Brasil' }}
      </h1>
      <p class="hero-desc">{{ $settings['hero_desc'] ?? 'Sandálias originais com design icônico.' }}</p>
      <div class="hero-price"><small>A partir de</small>R$ {{ $products->min('price') ? number_format($products->min('price'), 2, ',', '.') : '37,99' }}</div>
      <div class="hero-btns">
        <button class="btn btn-dark" onclick="document.getElementById('products').scrollIntoView({behavior:'smooth'})">Ver Produtos</button>
        <button class="btn btn-outline" onclick="document.getElementById('features-sec').scrollIntoView({behavior:'smooth'})">Diferenciais</button>
      </div>
    </div>
    <div class="hero-right">
      <div class="hero-img-wrap" id="hero-media-wrap">
        @if(!empty($settings['hero_video']))
          @php
            $videoUrl = $settings['hero_video'];
            $autoplay = ($settings['hero_video_opts'] ?? 'autoplay') === 'autoplay';
            preg_match('/(?:youtube\.com\/(?:watch\?v=|embed\/)|youtu\.be\/)([a-zA-Z0-9_-]{11})/', $videoUrl, $ytMatch);
            preg_match('/vimeo\.com\/(\d+)/', $videoUrl, $vmMatch);
          @endphp
          @if(!empty($ytMatch[1]))
            <iframe class="hero-video-iframe" src="https://www.youtube.com/embed/{{ $ytMatch[1] }}?{{ $autoplay ? 'autoplay=1&mute=1&loop=1&playlist='.$ytMatch[1].'&controls=0&rel=0' : 'controls=1&rel=0' }}" allow="autoplay; fullscreen" allowfullscreen></iframe>
          @elseif(!empty($vmMatch[1]))
            <iframe class="hero-video-iframe" src="https://player.vimeo.com/video/{{ $vmMatch[1] }}?{{ $autoplay ? 'autoplay=1&muted=1&loop=1&background=1' : 'controls=1' }}" allow="autoplay; fullscreen" allowfullscreen></iframe>
          @elseif(str_contains($videoUrl, '.mp4') || str_contains($videoUrl, '.webm'))
            <video class="hero-video" src="{{ $videoUrl }}" {{ $autoplay ? 'autoplay muted loop playsinline' : 'controls' }}></video>
          @endif
        @elseif(!empty($settings['hero_image']))
          <img src="{{ $settings['hero_image'] }}" alt="{{ $settings['store_name'] ?? 'A.Seller' }}">
        @else
          <svg viewBox="0 0 260 200" fill="none" style="width:200px;opacity:.3"><ellipse cx="130" cy="170" rx="110" ry="22" fill="#888"/><rect x="20" y="80" width="220" height="60" rx="30" fill="#fff" stroke="#ccc" stroke-width="2"/><rect x="80" y="30" width="38" height="90" rx="16" fill="#fff" stroke="#ccc" stroke-width="2" transform="rotate(-10 80 30)"/><rect x="144" y="30" width="38" height="90" rx="16" fill="#fff" stroke="#ccc" stroke-width="2" transform="rotate(10 144 30)"/></svg>
        @endif
        <div class="hero-float-badge">
          <div class="num">★ 4.9</div>
          <div class="label">+2.400 avaliações</div>
        </div>
      </div>
    </div>
  </section>

  {{-- PRODUTOS --}}
  <section class="store-section" id="products">
    <div class="section-head">
      <div class="section-eyebrow">Nossos Produtos</div>
      <h2 class="section-title">Havaianas Originais</h2>
    </div>
    <div class="products-grid">
      @forelse($products as $product)
      <div class="product-card" data-id="{{ $product->id }}" data-name="{{ $product->name }}" data-price="{{ $product->price }}" data-image="{{ $product->image_url }}">
        <div class="product-img-area">
          @if($product->image_url)
            <img src="{{ $product->image_url }}" alt="{{ $product->name }}" loading="lazy" onerror="this.style.display='none'">
          @else
            <svg viewBox="0 0 80 80" fill="none" style="width:60px;opacity:.2"><rect x="10" y="30" width="60" height="20" rx="10" fill="#888"/><rect x="28" y="10" width="12" height="28" rx="5" fill="#888" transform="rotate(-10 28 10)"/><rect x="40" y="10" width="12" height="28" rx="5" fill="#888" transform="rotate(10 40 10)"/></svg>
          @endif
          @if($product->badge)
            <div class="product-badge">{{ $product->badge }}</div>
          @endif
        </div>
        <div class="product-body">
          <div class="product-name">{{ $product->name }}</div>
          <div class="product-price">R$ {{ number_format($product->price, 2, ',', '.') }}</div>
          @php $sizes = is_array($product->sizes) ? $product->sizes : json_decode($product->sizes ?? '[]', true) ?? []; @endphp
          @if(count($sizes))
          <div class="size-grid">
            @foreach($sizes as $size)
            <button class="size-btn" onclick="selectSize(this, '{{ $product->id }}')">{{ $size }}</button>
            @endforeach
          </div>
          @endif
          <div class="product-footer">
            <div class="qty-ctrl">
              <button onclick="changeQty({{ $product->id }}, -1)">−</button>
              <span id="qty-{{ $product->id }}">1</span>
              <button onclick="changeQty({{ $product->id }}, 1)">+</button>
            </div>
            <button class="btn btn-dark btn-sm" onclick="addToCart({{ $product->id }})">Adicionar</button>
          </div>
        </div>
      </div>
      @empty
      <div style="grid-column:1/-1;text-align:center;padding:4rem;color:var(--gray3)">Nenhum produto disponível no momento.</div>
      @endforelse
    </div>
  </section>

  {{-- DIFERENCIAIS --}}
  <section class="features-sec" id="features-sec">
    <div class="features-inner">
      <div class="section-head">
        <div class="section-eyebrow">Por que escolher</div>
        <h2 class="section-title">A.Seller</h2>
      </div>
      <div class="features-grid">
        <div class="feature-item">
          <div class="feature-icon"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg></div>
          <div class="feature-title">100% Original</div>
          <div class="feature-desc">Havaianas legítimas, direto da fábrica com garantia de autenticidade.</div>
        </div>
        <div class="feature-item">
          <div class="feature-icon"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><rect x="1" y="3" width="15" height="13"/><polygon points="16 8 20 8 23 11 23 16 16 16 16 8"/><circle cx="5.5" cy="18.5" r="2.5"/><circle cx="18.5" cy="18.5" r="2.5"/></svg></div>
          <div class="feature-title">Entrega Rápida</div>
          <div class="feature-desc">Enviamos em até 24h. Frete grátis acima de R$99.</div>
        </div>
        <div class="feature-item">
          <div class="feature-icon"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><polyline points="23 6 13.5 15.5 8.5 10.5 1 18"/><polyline points="17 6 23 6 23 12"/></svg></div>
          <div class="feature-title">Melhor Preço</div>
          <div class="feature-desc">Compramos direto do distribuidor e repassamos a economia.</div>
        </div>
        <div class="feature-item">
          <div class="feature-icon"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M21 15a2 2 0 01-2 2H7l-4 4V5a2 2 0 012-2h14a2 2 0 012 2z"/></svg></div>
          <div class="feature-title">Suporte 24h</div>
          <div class="feature-desc">Atendimento via WhatsApp a qualquer hora.</div>
        </div>
      </div>
    </div>
  </section>

  {{-- AVALIAÇÕES --}}
  <section id="reviews-section" style="padding:5rem 2rem;background:var(--white);border-top:1px solid var(--gray2)">
    <div style="max-width:1100px;margin:0 auto">
      <div style="text-align:center;margin-bottom:3rem">
        <div style="font-family:var(--font-head);font-size:11px;letter-spacing:3px;text-transform:uppercase;color:var(--accent);margin-bottom:.8rem">Quem comprou aprovou</div>
        <h2 style="font-family:var(--font-display);font-size:2.8rem;font-weight:400;color:var(--brand)">Avaliações dos Clientes</h2>
      </div>

      @if($reviews->count())
      {{-- CARROSSEL --}}
      <div class="reviews-carousel-wrap">
        <div class="reviews-track" id="reviews-track">
          @foreach($reviews as $r)
          <div class="review-slide">
            <div class="review-stars-disp">
              @for($i=1;$i<=5;$i++)<span style="color:{{ $i<=$r->rating ? '#c8a96e' : '#e8e4de' }}">★</span>@endfor
            </div>
            <p class="review-text">"{{ $r->comment }}"</p>
            <div class="review-author">
              <div class="review-avi">{{ strtoupper(substr($r->name,0,1)) }}</div>
              <div>
                <div style="font-weight:600;font-size:14px;font-family:var(--font-head)">{{ $r->name }}</div>
                @if($r->product)<div style="font-size:12px;color:var(--gray3);font-family:var(--font-head)">Comprou: {{ $r->product->name }}</div>@endif
              </div>
            </div>
          </div>
          @endforeach
        </div>
        <button class="carousel-btn carousel-prev" onclick="carouselPrev()" aria-label="Anterior">‹</button>
        <button class="carousel-btn carousel-next" onclick="carouselNext()" aria-label="Próximo">›</button>
        <div class="carousel-dots" id="carousel-dots"></div>
      </div>
      @endif

      {{-- FORMULÁRIO --}}
      <div style="max-width:560px;margin:3rem auto 0">
        @if(session('review_sent'))
          <div style="background:#dcfce7;border:1px solid #86efac;color:#15803d;padding:1rem 1.2rem;border-radius:12px;text-align:center;font-family:var(--font-head);font-size:14px;margin-bottom:1.5rem">
            ✔ Avaliação enviada! Ela aparecerá na loja após aprovação.
          </div>
        @endif
        <div style="background:var(--gray1);border-radius:16px;padding:2rem;border:1px solid var(--gray2)">
          <h3 style="font-family:var(--font-display);font-size:1.6rem;margin-bottom:.3rem">Deixe sua avaliação</h3>
          <p style="font-size:13px;color:var(--gray3);font-family:var(--font-head);margin-bottom:1.5rem">Sua opinião aparecerá na loja após revisão.</p>
          <form method="POST" action="{{ route('store.review') }}">
            @csrf
            {{-- Honeypot: campo invisível para humanos, bots preenchem e são rejeitados --}}
            <div style="display:none" aria-hidden="true">
              <input type="text" name="website" tabindex="-1" autocomplete="off">
            </div>
            <div style="margin-bottom:1rem">
              <label style="display:block;font-family:var(--font-head);font-size:11px;letter-spacing:1px;text-transform:uppercase;color:var(--gray4);margin-bottom:6px">Seu nome</label>
              <input type="text" name="name" required placeholder="Ex: Maria S." value="{{ old('name') }}"
                style="width:100%;padding:11px 14px;border:1.5px solid var(--gray2);border-radius:8px;font-size:14px;font-family:var(--font-head);outline:none;background:var(--white);transition:border-color .2s"
                onfocus="this.style.borderColor='var(--accent)'" onblur="this.style.borderColor='var(--gray2)'">
            </div>
            <div style="margin-bottom:1rem">
              <label style="display:block;font-family:var(--font-head);font-size:11px;letter-spacing:1px;text-transform:uppercase;color:var(--gray4);margin-bottom:6px">Produto comprado</label>
              <select name="product_id" style="width:100%;padding:11px 14px;border:1.5px solid var(--gray2);border-radius:8px;font-size:14px;font-family:var(--font-head);outline:none;background:var(--white);transition:border-color .2s" onfocus="this.style.borderColor='var(--accent)'" onblur="this.style.borderColor='var(--gray2)'">
                <option value="">Selecione o produto...</option>
                @foreach($products as $p)
                  <option value="{{ $p->id }}" {{ old('product_id')==$p->id?'selected':'' }}>{{ $p->name }}</option>
                @endforeach
              </select>
            </div>
            <div style="margin-bottom:1rem">
              <label style="display:block;font-family:var(--font-head);font-size:11px;letter-spacing:1px;text-transform:uppercase;color:var(--gray4);margin-bottom:8px">Nota</label>
              <div class="star-rating" id="star-rating">
                @for($i=1;$i<=5;$i++)
                  <span class="star-pick" data-val="{{ $i }}" onclick="setRating({{ $i }})">★</span>
                @endfor
              </div>
              <input type="hidden" name="rating" id="rating-input" value="{{ old('rating',5) }}">
            </div>
            <div style="margin-bottom:1.2rem">
              <label style="display:block;font-family:var(--font-head);font-size:11px;letter-spacing:1px;text-transform:uppercase;color:var(--gray4);margin-bottom:6px">Comentário</label>
              <textarea name="comment" required rows="3" placeholder="Conte sua experiência com o produto..."
                style="width:100%;padding:11px 14px;border:1.5px solid var(--gray2);border-radius:8px;font-size:14px;font-family:var(--font-head);outline:none;background:var(--white);resize:vertical;transition:border-color .2s"
                onfocus="this.style.borderColor='var(--accent)'" onblur="this.style.borderColor='var(--gray2)'">{{ old('comment') }}</textarea>
            </div>
            <button type="submit" style="width:100%;padding:13px;background:var(--brand);color:var(--white);border:none;border-radius:8px;font-family:var(--font-head);font-size:14px;font-weight:600;letter-spacing:.5px;cursor:pointer;transition:background .2s" onmouseover="this.style.background='#333'" onmouseout="this.style.background='var(--brand)'">
              Enviar Avaliação
            </button>
          </form>
        </div>
      </div>
    </div>
  </section>

  {{-- FOOTER --}}
  {{-- RASTREAR PEDIDO --}}
  <section id="rastrear-section" style="background:var(--off);padding:5rem 2rem;border-top:1px solid var(--gray2)">
    <div style="max-width:560px;margin:0 auto;text-align:center">
      <div style="font-family:var(--font-head);font-size:11px;letter-spacing:3px;text-transform:uppercase;color:var(--accent);margin-bottom:1rem">Pós-compra</div>
      <h2 style="font-family:var(--font-display);font-size:2.5rem;font-weight:400;margin-bottom:1rem">Rastrear Pedido</h2>
      <p style="color:var(--gray3);font-size:15px;line-height:1.7;margin-bottom:2rem">Informe o número do seu pedido e e-mail para acompanhar o status da entrega.</p>
      <div id="track-form" style="background:var(--white);border-radius:16px;padding:2rem;border:1px solid var(--gray2);text-align:left">
        <div style="margin-bottom:1rem">
          <label style="display:block;font-family:var(--font-head);font-size:11px;letter-spacing:1px;text-transform:uppercase;color:var(--gray4);margin-bottom:6px">Número do Pedido</label>
          <input id="track-number" type="text" placeholder="ASL-001234" style="width:100%;padding:12px 16px;border:1.5px solid var(--gray2);border-radius:8px;font-size:15px;font-family:var(--font-head);outline:none;transition:border-color .2s" onfocus="this.style.borderColor='var(--accent)'" onblur="this.style.borderColor='var(--gray2)'">
        </div>
        <div style="margin-bottom:1.5rem">
          <label style="display:block;font-family:var(--font-head);font-size:11px;letter-spacing:1px;text-transform:uppercase;color:var(--gray4);margin-bottom:6px">E-mail do Pedido</label>
          <input id="track-email" type="email" placeholder="seu@email.com" style="width:100%;padding:12px 16px;border:1.5px solid var(--gray2);border-radius:8px;font-size:15px;font-family:var(--font-head);outline:none;transition:border-color .2s" onfocus="this.style.borderColor='var(--accent)'" onblur="this.style.borderColor='var(--gray2)'">
        </div>
        <button onclick="trackOrder()" style="width:100%;padding:14px;background:var(--brand);color:var(--white);border:none;border-radius:8px;font-family:var(--font-head);font-size:14px;font-weight:600;letter-spacing:1px;cursor:pointer;transition:background .2s" onmouseover="this.style.background='#333'" onmouseout="this.style.background='var(--brand)'">
          CONSULTAR PEDIDO
        </button>
        <div id="track-result" style="display:none;margin-top:1.5rem;padding:1.2rem;border-radius:8px;font-size:14px;line-height:1.7"></div>
      </div>
      <p style="margin-top:1.5rem;font-size:13px;color:var(--gray3)">
        Dúvidas? <a onclick="waMessage('Olá! Preciso rastrear meu pedido.')" style="color:var(--accent);cursor:pointer;text-decoration:underline">Fale pelo WhatsApp</a>
      </p>
    </div>
  </section>

  <footer id="store-footer">
    <div class="footer-inner">
      <div class="footer-grid">
        <div>
          <div class="footer-brand">A.<span>Seller</span></div>
          <p class="footer-desc">A melhor seleção de Havaianas Brancas do Brasil. Qualidade, originalidade e preço justo.</p>
        </div>
        <div class="footer-col">
          <h4>Loja</h4>
          <ul>
            <li><a onclick="document.getElementById('products').scrollIntoView({behavior:'smooth'})">Produtos</a></li>
            <li><a onclick="document.getElementById('features-sec').scrollIntoView({behavior:'smooth'})">Diferenciais</a></li>
          </ul>
        </div>
        <div class="footer-col">
          <h4>Suporte</h4>
          <ul>
            <li><a onclick="waMessage('Olá! Quero rastrear meu pedido.')">Rastrear Pedido</a></li>
            <li><a onclick="waMessage('Olá! Preciso de ajuda.')">WhatsApp</a></li>
          </ul>
        </div>
        <div class="footer-col">
          <h4>Empresa</h4>
          <ul>
            <li><a href="/admin" style="color:rgba(255,255,255,.5)">Área Admin</a></li>
          </ul>
        </div>
      </div>
      <div class="footer-bottom">
        <span>© {{ date('Y') }} A.Seller · Todos os direitos reservados</span>
        <div class="social-links">
          @if(!empty($settings['social_instagram']))
            <a href="{{ $settings['social_instagram'] }}" target="_blank" title="Instagram"><svg viewBox="0 0 24 24"><path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zM12 0C8.741 0 8.333.014 7.053.072 2.695.272.273 2.69.073 7.052.014 8.333 0 8.741 0 12c0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98C8.333 23.986 8.741 24 12 24c3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98C15.668.014 15.259 0 12 0zm0 5.838a6.162 6.162 0 100 12.324 6.162 6.162 0 000-12.324zM12 16a4 4 0 110-8 4 4 0 010 8zm6.406-11.845a1.44 1.44 0 100 2.881 1.44 1.44 0 000-2.881z"/></svg></a>
          @endif
          @if(!empty($settings['social_facebook']))
            <a href="{{ $settings['social_facebook'] }}" target="_blank" title="Facebook"><svg viewBox="0 0 24 24"><path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/></svg></a>
          @endif
          @if(!empty($settings['social_tiktok']))
            <a href="{{ $settings['social_tiktok'] }}" target="_blank" title="TikTok"><svg viewBox="0 0 24 24"><path d="M12.525.02c1.31-.02 2.61-.01 3.91-.02.08 1.53.63 3.09 1.75 4.17 1.12 1.11 2.7 1.62 4.24 1.79v4.03c-1.44-.05-2.89-.35-4.2-.97-.57-.26-1.1-.59-1.62-.93-.01 2.92.01 5.84-.02 8.75-.08 1.4-.54 2.79-1.35 3.94-1.31 1.92-3.58 3.17-5.91 3.21-1.43.08-2.86-.31-4.08-1.03-2.02-1.19-3.44-3.37-3.65-5.71-.02-.5-.03-1-.01-1.49.18-1.9 1.12-3.72 2.58-4.96 1.66-1.44 3.98-2.13 6.15-1.72.02 1.48-.04 2.96-.04 4.44-.99-.32-2.15-.23-3.02.37-.63.41-1.11 1.04-1.36 1.75-.21.51-.15 1.07-.14 1.61.24 1.64 1.82 3.02 3.5 2.87 1.12-.01 2.19-.66 2.77-1.61.19-.33.4-.67.41-1.06.1-1.79.06-3.57.07-5.36.01-4.03-.01-8.05.02-12.07z"/></svg></a>
          @endif
          @if(!empty($settings['social_shopee']))
            <a href="{{ $settings['social_shopee'] }}" target="_blank" title="Shopee"><svg viewBox="0 0 24 24" fill="currentColor"><path d="M12 0C9.623 0 7.65 1.973 7.65 4.4c0 .224.017.444.05.659H4.37A1.37 1.37 0 003 6.43l-.97 14.199A1.37 1.37 0 003.4 22h17.2a1.37 1.37 0 001.37-1.371L21 6.43a1.37 1.37 0 00-1.37-1.371h-3.33a4.433 4.433 0 00.05-.659C16.35 1.973 14.377 0 12 0zm0 1.714c1.478 0 2.686 1.208 2.686 2.686 0 .224-.027.441-.078.649H9.392a2.703 2.703 0 01-.078-.649c0-1.478 1.208-2.686 2.686-2.686zm-4.8 8.229a1.029 1.029 0 110 2.057 1.029 1.029 0 010-2.057zm9.6 0a1.029 1.029 0 110 2.057 1.029 1.029 0 010-2.057z"/></svg></a>
          @endif
        </div>
        <span>Pagamento seguro via Stripe</span>
      </div>
    </div>
  </footer>
</div>

{{-- CHECKOUT --}}
<div id="page-checkout">
  <h1 class="checkout-title">Finalizar Compra</h1>
  <button onclick="showPage('store')" class="btn btn-outline btn-sm" style="margin-bottom:1.5rem">← Voltar à loja</button>

  <div class="checkout-section">
    <h3>Seus Dados</h3>
    <div class="form-group"><label>Nome Completo *</label><input type="text" id="ch-name" class="form-control-store" required placeholder="Seu nome completo"></div>
    <div class="form-row">
      <div class="form-group"><label>E-mail *</label><input type="email" id="ch-email" class="form-control-store" required placeholder="seu@email.com"></div>
      <div class="form-group"><label>Telefone</label><input type="tel" id="ch-phone" class="form-control-store" placeholder="(62) 99999-9999"></div>
    </div>
  </div>

  <div class="checkout-section">
    <h3>Endereço de Entrega</h3>
    <div class="form-row">
      <div class="form-group"><label>CEP</label><input type="text" id="ch-zip" class="form-control-store" placeholder="00000-000" oninput="fetchCEP(this.value)" maxlength="9"></div>
      <div class="form-group"><label>Cidade</label><input type="text" id="ch-city" class="form-control-store" placeholder="Goiânia"></div>
    </div>
    <div class="form-row">
      <div class="form-group"><label>Estado</label><input type="text" id="ch-state" class="form-control-store" placeholder="GO" maxlength="2"></div>
      <div class="form-group"><label>Endereço</label><input type="text" id="ch-address" class="form-control-store" placeholder="Rua, número, bairro"></div>
    </div>
  </div>

  <div class="checkout-section">
    <h3>Pagamento</h3>
    <div class="payment-opt">
      <button class="payment-btn active" onclick="setPayment('card', this)">💳 Cartão</button>
      <button class="payment-btn" onclick="setPayment('pix', this)">⚡ Pix</button>
      <button class="payment-btn" onclick="setPayment('boleto', this)">🧾 Boleto</button>
    </div>
    <input type="hidden" id="ch-payment" value="card">

    {{-- Card element do Stripe --}}
    @if(!empty($settings['stripe_pk']))
    <div id="card-element-wrap" style="margin-top:1rem">
      <label style="display:block;font-family:var(--font-head);font-size:11px;letter-spacing:1px;text-transform:uppercase;color:var(--gray4);margin-bottom:8px">Dados do Cartão</label>
      <div id="card-element" style="padding:12px 14px;border:1.5px solid var(--gray2);border-radius:8px;background:var(--white);transition:border-color .2s"></div>
      <div id="card-error" style="color:var(--danger);font-size:12px;margin-top:6px"></div>
    </div>
    @else
    <div id="card-element-wrap" style="display:none"></div>
    <div style="margin-top:1rem;padding:12px 14px;background:#fef3c7;border-radius:8px;font-size:13px;color:#92400e;font-family:var(--font-head)">
      ⚠ Pagamento via cartão não configurado. Entre em contato para finalizar.
    </div>
    @endif

    {{-- Info Pix/Boleto --}}
    <div id="pix-info" style="display:none;margin-top:1rem;padding:12px 14px;background:#f4f2ef;border-radius:8px;font-size:13px;color:var(--gray4);line-height:1.6">
      ⚡ <strong>Pix:</strong> Após confirmar, enviaremos as instruções de pagamento por e-mail.
    </div>
    <div id="boleto-info" style="display:none;margin-top:1rem;padding:12px 14px;background:#f4f2ef;border-radius:8px;font-size:13px;color:var(--gray4);line-height:1.6">
      🧾 <strong>Boleto:</strong> Após confirmar, o boleto será enviado por e-mail em até 1 hora útil.
    </div>
  </div>

  <div class="checkout-section">
    <h3>Cupom de Desconto</h3>
    <div style="display:flex;gap:.75rem">
      <input type="text" id="ch-coupon" class="form-control-store" placeholder="Digite o código" style="flex:1;text-transform:uppercase">
      <button class="btn btn-outline btn-sm" onclick="applyCoupon()">Aplicar</button>
    </div>
    <div id="coupon-feedback" style="margin-top:.5rem;font-size:13px"></div>
  </div>

  <div class="checkout-section">
    <h3>Resumo</h3>
    <div class="order-summary" id="order-summary"></div>
    <button class="pay-btn" id="pay-btn" onclick="processPayment()">🔒 Finalizar Pedido</button>
  </div>
</div>

{{-- SUCCESS --}}
<div id="page-success">
  <div class="success-box">
    <div class="success-icon">✓</div>
    <div class="success-title">Pedido Confirmado!</div>
    <div class="success-sub">Pedido <strong id="success-order-id"></strong> recebido com sucesso.</div>
    <button class="btn btn-dark" onclick="showPage('store')">Continuar Comprando</button>
  </div>
</div>

{{-- CART --}}
<div class="cart-overlay" id="cart-overlay" onclick="toggleCart()"></div>
<div class="cart-drawer" id="cart-drawer">
  <div class="cart-head">
    <h2>Seu Carrinho</h2>
    <button onclick="toggleCart()" style="background:none;border:none;cursor:pointer;font-size:22px;color:var(--gray3)">✕</button>
  </div>
  <div class="cart-items" id="cart-items-list"></div>
  <div class="cart-footer">
    <div class="cart-subtotal"><span>Subtotal</span><span id="cart-total-display">R$ 0,00</span></div>
    <button class="checkout-btn" onclick="goToCheckout()">Finalizar Compra</button>
  </div>
</div>

{{-- WHATSAPP --}}
<div class="wa-float" id="wa-float">
  <div class="wa-bubble" id="wa-bubble">
    <div class="wa-bubble-head">
      <div>
        <div class="wa-name">{{ $settings['wa_name'] ?? 'A.Seller · Suporte' }}</div>
        <div class="wa-status">Online agora</div>
      </div>
    </div>
    <div class="wa-msgs">
      <button class="wa-msg-btn" onclick="waMessage('Olá! Quero saber o preço das Havaianas.')">💬 Preço das Havaianas</button>
      <button class="wa-msg-btn" onclick="waMessage('Olá! Quero rastrear meu pedido.')">📦 Rastrear pedido</button>
      <button class="wa-msg-btn" onclick="waMessage('Olá! Preciso de ajuda.')">🙋 Fale com a gente</button>
    </div>
  </div>
  <button class="wa-fab" onclick="toggleWA()">
    <svg viewBox="0 0 24 24"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/></svg>
  </button>
</div>

<div id="toast" class="toast"></div>

<script>
const WA_NUMBER          = '{{ $settings['wa_number'] ?? '5562994250683' }}';
const CHECKOUT_URL       = '{{ route("store.order") }}';
const COUPON_URL         = '{{ route("store.coupon") }}';
const INTENT_URL         = '{{ route("store.intent") }}';
const CSRF               = document.querySelector('meta[name=csrf-token]').content;
const STRIPE_PK          = '{{ $settings['stripe_pk'] ?? '' }}';
const FRETE_FIXO         = {{ floatval($settings['frete_fixo'] ?? 12.90) }};
const FRETE_GRATIS_ACIMA = {{ floatval($settings['frete_gratis_acima'] ?? 99) }};

// Inicializar Stripe se chave configurada
let stripe = null, cardElement = null;
if (STRIPE_PK) {
  stripe = Stripe(STRIPE_PK);
  const elements = stripe.elements();
  cardElement = elements.create('card', {
    style: { base: { fontFamily: 'Syne, sans-serif', fontSize: '15px', color: '#0a0a0a', '::placeholder': { color: '#a09880' } } }
  });
}

let cart = [];
let appliedCoupon = null;

// ── PAGES ──
function showPage(page) {
  document.getElementById('page-store').style.display     = page === 'store'    ? 'block' : 'none';
  document.getElementById('page-checkout').className      = page === 'checkout' ? 'active' : '';
  document.getElementById('page-success').className       = page === 'success'  ? 'active' : '';
  window.scrollTo(0,0);
}

// ── CART ──
function toggleCart() {
  document.getElementById('cart-drawer').classList.toggle('open');
  document.getElementById('cart-overlay').classList.toggle('open');
}

function changeQty(id, delta) {
  const el = document.getElementById('qty-' + id);
  let val = parseInt(el.textContent) + delta;
  if (val < 1) val = 1;
  el.textContent = val;
}

function selectSize(btn, productId) {
  const parent = btn.closest('.size-grid');
  parent.querySelectorAll('.size-btn').forEach(b => b.classList.remove('selected'));
  btn.classList.add('selected');
}

function addToCart(productId) {
  const card  = document.querySelector(`.product-card[data-id="${productId}"]`);
  const name  = card.dataset.name;
  const price = parseFloat(card.dataset.price);
  const image = card.dataset.image;
  const qty   = parseInt(document.getElementById('qty-' + productId).textContent);
  const sizeBtn = card.querySelector('.size-btn.selected');
  const size    = sizeBtn ? sizeBtn.textContent : '';

  if (card.querySelector('.size-grid') && !size) {
    toast('Selecione um tamanho!', 'error'); return;
  }

  const existing = cart.find(i => i.id === productId && i.size === size);
  if (existing) { existing.qty += qty; }
  else { cart.push({ id: productId, name, price, image, size, qty }); }

  renderCart();
  toggleCart();
  toast(`${name} adicionado ao carrinho!`, 'success');
}

function renderCart() {
  const list  = document.getElementById('cart-items-list');
  const count = document.getElementById('cart-count');
  const total = cart.reduce((s, i) => s + i.price * i.qty, 0);
  const totalQty = cart.reduce((s, i) => s + i.qty, 0);

  count.textContent = totalQty;
  count.style.display = totalQty > 0 ? 'flex' : 'none';

  document.getElementById('cart-total-display').textContent = 'R$ ' + total.toFixed(2).replace('.', ',');

  if (cart.length === 0) {
    list.innerHTML = '<div style="text-align:center;padding:3rem;color:var(--gray3)">Seu carrinho está vazio.</div>';
    return;
  }

  list.innerHTML = cart.map((item, i) => `
    <div class="cart-item">
      <div class="cart-item-img">${item.image ? `<img src="${item.image}" alt="${item.name}">` : ''}</div>
      <div class="cart-item-info">
        <div class="cart-item-name">${item.name}</div>
        <div class="cart-item-size">${item.size ? 'Tam: '+item.size : ''} · ${item.qty}x</div>
        <div class="cart-item-price">R$ ${(item.price * item.qty).toFixed(2).replace('.', ',')}</div>
      </div>
      <button onclick="removeFromCart(${i})" style="background:none;border:none;cursor:pointer;color:var(--gray3);font-size:18px;padding:.25rem">✕</button>
    </div>
  `).join('');
}

function removeFromCart(index) {
  cart.splice(index, 1);
  renderCart();
}

function goToCheckout() {
  if (cart.length === 0) { toast('Carrinho vazio!', 'error'); return; }
  toggleCart();
  renderOrderSummary();
  showPage('checkout');
  setTimeout(mountCardElement, 100);
}

function calcShipping(subtotal) {
  if (appliedCoupon && appliedCoupon.frete) return 0;
  return subtotal >= FRETE_GRATIS_ACIMA ? 0 : FRETE_FIXO;
}

function renderOrderSummary() {
  const subtotal  = cart.reduce((s, i) => s + i.price * i.qty, 0);
  let discount = 0;
  if (appliedCoupon) {
    discount = appliedCoupon.type === 'percent' ? subtotal * appliedCoupon.value / 100
             : appliedCoupon.type === 'fixed'   ? appliedCoupon.value : 0;
  }
  const shipping = calcShipping(subtotal);
  const total    = subtotal - discount + shipping;
  const el = document.getElementById('order-summary');
  const fmt = v => 'R$ ' + v.toFixed(2).replace('.', ',');
  el.innerHTML = `
    ${cart.map(i => `<div class="order-summary-row"><span>${i.name} ${i.size?'('+i.size+')':''} x${i.qty}</span><span>${fmt(i.price*i.qty)}</span></div>`).join('')}
    <div class="order-summary-row"><span>Subtotal</span><span>${fmt(subtotal)}</span></div>
    ${discount > 0 ? `<div class="order-summary-row" style="color:var(--success)"><span>Desconto</span><span>- ${fmt(discount)}</span></div>` : ''}
    <div class="order-summary-row"><span>Frete</span><span>${shipping === 0 ? '<span style="color:var(--success)">Grátis</span>' : fmt(shipping)}</span></div>
    <div class="order-summary-row total"><span>Total</span><span>${fmt(total)}</span></div>
  `;
}

// ── PAYMENT ──
function setPayment(method, btn) {
  document.getElementById('ch-payment').value = method;
  document.querySelectorAll('.payment-btn').forEach(b => b.classList.remove('active'));
  btn.classList.add('active');
  const wrap = document.getElementById('card-element-wrap');
  if (wrap) wrap.style.display = (method === 'card' && stripe) ? 'block' : 'none';
  document.getElementById('pix-info').style.display    = method === 'pix'    ? 'block' : 'none';
  document.getElementById('boleto-info').style.display = method === 'boleto' ? 'block' : 'none';
  if (method === 'card' && stripe) mountCardElement();
}

// Montar card element quando o checkout abrir pela 1ª vez
function mountCardElement() {
  if (!stripe || !cardElement) return;
  const wrap = document.getElementById('card-element-wrap');
  if (wrap && !wrap.dataset.mounted) {
    cardElement.mount('#card-element');
    wrap.dataset.mounted = '1';
    cardElement.on('change', e => {
      document.getElementById('card-error').textContent = e.error ? e.error.message : '';
    });
  }
}

async function applyCoupon() {
  const code = document.getElementById('ch-coupon').value.trim();
  const fb   = document.getElementById('coupon-feedback');
  if (!code) { fb.innerHTML = '<span style="color:var(--danger)">Digite um código.</span>'; return; }
  const subtotal = cart.reduce((s, i) => s + i.price * i.qty, 0);
  fb.innerHTML = 'Verificando...';
  try {
    const res  = await fetch(COUPON_URL, {
      method: 'POST',
      headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': CSRF },
      body: JSON.stringify({ code, total: subtotal })
    });
    const data = await res.json();
    if (!data.success) { fb.innerHTML = `<span style="color:var(--danger)">❌ ${data.error}</span>`; appliedCoupon = null; return; }
    appliedCoupon = data.coupon;
    fb.innerHTML = `<span style="color:var(--success)">✓ Cupom aplicado! Desconto: ${data.coupon.type==='frete'?'Frete Grátis':'R$ '+data.coupon.discount.toFixed(2).replace('.',',')}</span>`;
    renderOrderSummary();
  } catch(e) { fb.innerHTML = '<span style="color:var(--danger)">Erro ao validar cupom.</span>'; }
}

async function processPayment() {
  const btn   = document.getElementById('pay-btn');
  const name  = document.getElementById('ch-name').value.trim();
  const email = document.getElementById('ch-email').value.trim();
  if (!name || !email) { toast('Preencha nome e e-mail!', 'error'); return; }
  btn.disabled = true; btn.textContent = 'Processando...';

  const paymentMethod = document.getElementById('ch-payment').value;
  const subtotal  = cart.reduce((s, i) => s + i.price * i.qty, 0);
  const discount  = appliedCoupon
    ? (appliedCoupon.type === 'percent' ? subtotal * appliedCoupon.value / 100
      : appliedCoupon.type === 'fixed'  ? appliedCoupon.value : 0)
    : 0;
  const shipping = calcShipping(subtotal);
  const total    = subtotal - discount + shipping;

  try {
    let stripePaymentIntentId = null;

    // ── Pagamento com cartão via Stripe ──
    if (paymentMethod === 'card' && stripe && cardElement) {
      if (total < 0.5) throw new Error('Valor mínimo para pagamento com cartão: R$ 0,50.');

      // 1. Criar PaymentIntent no backend
      const intentRes  = await fetch(INTENT_URL, {
        method: 'POST',
        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': CSRF },
        body: JSON.stringify({ amount: total })
      });
      const intentData = await intentRes.json();
      if (intentData.error) throw new Error(intentData.error);

      // 2. Confirmar pagamento com o card element
      const { error, paymentIntent } = await stripe.confirmCardPayment(intentData.clientSecret, {
        payment_method: {
          card: cardElement,
          billing_details: { name, email }
        }
      });
      if (error) throw new Error(error.message);
      stripePaymentIntentId = paymentIntent.id;
    }

    // ── Salvar pedido no backend ──
    const payload = {
      customer_name:   name,
      customer_email:  email,
      customer_phone:  document.getElementById('ch-phone').value || '',
      address:         document.getElementById('ch-address').value || '',
      city:            document.getElementById('ch-city').value || '',
      state:           document.getElementById('ch-state').value || '',
      cep:             document.getElementById('ch-zip').value || '',
      items:           cart.map(i => ({ id: i.id, name: i.name, size: i.size, qty: i.qty, price: i.price })),
      payment_method:  paymentMethod,
      coupon_code:     appliedCoupon ? appliedCoupon.code : null,
      subtotal,
      discount,
      shipping,
      total,
      stripe_pi_id:    stripePaymentIntentId,
      payment_status:  stripePaymentIntentId ? 'paid' : 'pending',
    };

    const res  = await fetch(CHECKOUT_URL, {
      method: 'POST',
      headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': CSRF },
      body: JSON.stringify(payload)
    });
    const data = await res.json();
    if (!data.success) throw new Error(data.error || 'Erro ao registrar pedido.');

    cart = []; appliedCoupon = null; renderCart();
    document.getElementById('success-order-id').textContent = '#' + data.order_number;
    showPage('success');
  } catch(err) {
    toast('❌ ' + err.message, 'error');
  } finally {
    btn.disabled = false; btn.textContent = '🔒 Finalizar Pedido';
  }
}

async function fetchCEP(value) {
  const cep = value.replace(/\D/g, '');
  if (cep.length !== 8) return;
  try {
    const res  = await fetch(`https://viacep.com.br/ws/${cep}/json/`);
    const data = await res.json();
    if (!data.erro) {
      document.getElementById('ch-address').value = `${data.logradouro}, ${data.bairro}`;
      document.getElementById('ch-city').value    = data.localidade;
      document.getElementById('ch-state').value   = data.uf;
    }
  } catch(e) {}
}

// ── WA ──
function toggleWA() { document.getElementById('wa-bubble').classList.toggle('open'); }
function waMessage(text) { window.open(`https://wa.me/${WA_NUMBER}?text=${encodeURIComponent(text)}`, '_blank'); }

// ── REVIEWS CAROUSEL ──
(function(){
  const track = document.getElementById('reviews-track');
  if (!track) return;
  const slides  = track.querySelectorAll('.review-slide');
  const dotsWrap = document.getElementById('carousel-dots');
  if (!slides.length) return;

  function visibleCount() {
    return window.innerWidth <= 600 ? 1 : window.innerWidth <= 900 ? 2 : 3;
  }

  let cur = 0;
  const total = slides.length;

  function maxIndex() { return Math.max(0, total - visibleCount()); }

  function buildDots() {
    dotsWrap.innerHTML = '';
    const pages = maxIndex() + 1;
    for (let i = 0; i < pages; i++) {
      const btn = document.createElement('button');
      btn.className = 'carousel-dot' + (i === cur ? ' active' : '');
      btn.onclick = () => goTo(i);
      dotsWrap.appendChild(btn);
    }
  }

  function goTo(n) {
    cur = Math.max(0, Math.min(n, maxIndex()));
    const slideW = slides[0].getBoundingClientRect().width + 24;
    track.style.transform = `translateX(-${cur * slideW}px)`;
    dotsWrap.querySelectorAll('.carousel-dot').forEach((d,i) => d.classList.toggle('active', i===cur));
  }

  window.carouselNext = () => goTo(cur + 1);
  window.carouselPrev = () => goTo(cur - 1);

  buildDots();
  window.addEventListener('resize', () => { buildDots(); goTo(Math.min(cur, maxIndex())); });

  // auto-play a cada 5s
  setInterval(() => goTo(cur >= maxIndex() ? 0 : cur + 1), 5000);
})();

// ── STAR RATING ──
function setRating(val) {
  document.getElementById('rating-input').value = val;
  document.querySelectorAll('.star-pick').forEach(s => {
    s.classList.toggle('on', parseInt(s.dataset.val) <= val);
  });
}
setRating({{ old('rating', 5) }});

async function trackOrder() {
  const number = document.getElementById('track-number').value.trim();
  const email  = document.getElementById('track-email').value.trim();
  const result = document.getElementById('track-result');
  if (!number || !email) {
    result.style.display = 'block';
    result.style.background = '#fff3cd';
    result.style.border = '1px solid #ffc107';
    result.style.color = '#856404';
    result.innerHTML = '⚠️ Preencha o número do pedido e o e-mail.';
    return;
  }
  result.style.display = 'block';
  result.style.background = '#f8f9fa';
  result.style.border = '1px solid var(--gray2)';
  result.style.color = 'var(--gray3)';
  result.innerHTML = '🔍 Consultando...';
  try {
    const res = await fetch('/checkout/track', {
      method: 'POST',
      headers: {'Content-Type':'application/json','X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]')?.content || ''},
      body: JSON.stringify({order_number: number, email})
    });
    const data = await res.json();
    if (data.success && data.order) {
      const o = data.order;
      const statusLabel = {pending:'Aguardando pagamento',processing:'Em processamento',shipped:'Enviado',delivered:'Entregue',cancelled:'Cancelado'};
      const statusColor = {pending:'#854d0e',processing:'#1d4ed8',shipped:'#1d4ed8',delivered:'#15803d',cancelled:'#b91c1c'};
      const statusBg   = {pending:'#fef9c3',processing:'#dbeafe',shipped:'#dbeafe',delivered:'#dcfce7',cancelled:'#fee2e2'};
      const s = o.order_status;
      result.style.background = '#fff';
      result.style.border = '1px solid var(--gray2)';
      result.style.color = 'var(--brand)';
      result.innerHTML = `
        <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:1rem;flex-wrap:wrap;gap:8px">
          <strong style="font-family:var(--font-display);font-size:1.1rem">${o.order_number}</strong>
          <span style="background:${statusBg[s]};color:${statusColor[s]};padding:4px 12px;border-radius:20px;font-size:11px;font-weight:700;font-family:var(--font-head);letter-spacing:1px;text-transform:uppercase">${statusLabel[s] || s}</span>
        </div>
        <div style="font-size:13px;color:var(--gray3);line-height:1.8">
          <div>Cliente: <strong style="color:var(--brand)">${o.customer_name}</strong></div>
          <div>Total: <strong style="color:var(--accent)">R$ ${parseFloat(o.total).toFixed(2).replace('.',',')}</strong></div>
          <div>Pagamento: <strong style="color:var(--brand)">${o.payment_method === 'pix' ? 'Pix' : o.payment_method === 'card' ? 'Cartão' : o.payment_method}</strong></div>
          ${o.tracking_code ? `<div style="margin-top:8px;padding:8px 12px;background:var(--gray1);border-radius:6px">📦 Código de rastreio: <strong>${o.tracking_code}</strong></div>` : ''}
          ${s === 'shipped' || s === 'delivered' ? `<div style="margin-top:8px"><a href="https://rastreamento.correios.com.br/" target="_blank" style="color:var(--accent)">→ Rastrear nos Correios</a></div>` : ''}
        </div>`;
    } else {
      result.style.background = '#fee2e2';
      result.style.border = '1px solid #fca5a5';
      result.style.color = '#b91c1c';
      result.innerHTML = '❌ Pedido não encontrado. Verifique o número e o e-mail informados.';
    }
  } catch(e) {
    result.style.background = '#fee2e2';
    result.style.border = '1px solid #fca5a5';
    result.style.color = '#b91c1c';
    result.innerHTML = '❌ Erro ao consultar. Tente novamente ou fale pelo WhatsApp.';
  }
}

// ── TOAST ──
let toastTimer;
function toast(msg, type='') {
  const el = document.getElementById('toast');
  el.textContent = msg;
  el.className   = `toast ${type} show`;
  clearTimeout(toastTimer);
  toastTimer = setTimeout(() => el.classList.remove('show'), 3000);
}

// Init
showPage('store');
renderCart();
</script>
</body>
</html>
