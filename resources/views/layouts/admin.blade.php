<!DOCTYPE html>
<html lang="pt-BR">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta name="csrf-token" content="{{ csrf_token() }}">
<link rel="icon" type="image/svg+xml" href="/favicon.svg">
<title>@yield('title','Admin') · A.Seller</title>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:ital,wght@0,400;0,600;1,400&family=Syne:wght@400;500;600;700&display=swap" rel="stylesheet">
<style>
*,*::before,*::after{box-sizing:border-box;margin:0;padding:0}
:root{
  --brand:#0a0a0a;--accent:#c8a96e;--white:#fff;--off:#faf9f7;
  --gray1:#f4f2ef;--gray2:#e8e4de;--gray3:#a09880;--gray4:#6b6455;
  --success:#15803d;--danger:#b91c1c;--warn:#854d0e;
  --r:8px;--r2:12px;
  --sidebar:260px;
  --topbar:64px;
  --font-display:'Cormorant Garamond',serif;
  --font-head:'Syne',sans-serif;
  --font-body:'Syne',sans-serif;
}

/* ── RESET LAYOUT ── */
html,body{height:100%;overflow:hidden}
body{font-family:var(--font-head);background:#f0eff0;color:var(--brand);display:flex}

/* ── SIDEBAR ── */
#admin-sidebar{
  width:var(--sidebar);
  background:var(--brand);
  color:var(--white);
  display:flex;
  flex-direction:column;
  position:fixed;
  left:0;top:0;bottom:0;
  z-index:100;
  transition:transform .3s;
  overflow:hidden;
}
.sidebar-logo{
  flex-shrink:0;
  padding:2rem 1.5rem 1.5rem;
  border-bottom:1px solid rgba(255,255,255,.08);
}
.sidebar-logo h1{font-family:var(--font-display);font-size:1.8rem;font-weight:700;color:var(--white)}
.sidebar-logo h1 span{color:var(--accent)}
.sidebar-logo small{font-family:var(--font-head);font-size:10px;letter-spacing:2px;text-transform:uppercase;color:rgba(255,255,255,.3)}

/* nav ocupa o espaço restante e faz scroll sem mostrar scrollbar */
.sidebar-nav{
  flex:1;
  min-height:0;
  padding:1rem 0;
  overflow-y:auto;
  scrollbar-width:none;
  -ms-overflow-style:none;
}
.sidebar-nav::-webkit-scrollbar{display:none}

.nav-section-label{font-family:var(--font-head);font-size:9px;letter-spacing:3px;text-transform:uppercase;color:rgba(255,255,255,.25);padding:1rem 1.5rem .4rem}
.sidebar-link{
  display:flex;align-items:center;gap:12px;
  padding:10px 1.5rem;
  font-family:var(--font-head);font-size:13px;letter-spacing:.5px;
  color:rgba(255,255,255,.55);
  cursor:pointer;transition:all .2s;
  text-decoration:none;
  border-left:3px solid transparent;
  margin:1px 0;
  white-space:nowrap;
}
.sidebar-link svg{width:18px;height:18px;stroke:currentColor;fill:none;stroke-width:1.5;flex-shrink:0}
.sidebar-link:hover{color:var(--white);background:rgba(255,255,255,.05);border-left-color:rgba(200,169,110,.4)}
.sidebar-link.active{color:var(--white);background:rgba(200,169,110,.15);border-left-color:var(--accent)}
.sidebar-link.active svg{stroke:var(--accent)}

.sidebar-footer{
  flex-shrink:0;
  padding:1.2rem 1.5rem;
  border-top:1px solid rgba(255,255,255,.08);
}
.sidebar-user{display:flex;align-items:center;gap:10px}
.sidebar-avatar{width:34px;height:34px;border-radius:50%;background:var(--accent);display:flex;align-items:center;justify-content:center;font-family:var(--font-head);font-size:13px;font-weight:700;color:var(--brand);flex-shrink:0}
.sidebar-user-info{flex:1;min-width:0}
.sidebar-user-name{font-size:13px;font-weight:500;color:var(--white);white-space:nowrap;overflow:hidden;text-overflow:ellipsis}
.sidebar-user-role{font-size:11px;color:rgba(255,255,255,.3);font-family:var(--font-head)}

/* ── MOBILE TOGGLE ── */
.sidebar-toggle{display:none;position:fixed;top:16px;left:16px;z-index:200;background:var(--brand);color:var(--white);border:none;border-radius:var(--r);padding:10px 12px;cursor:pointer;font-size:18px;line-height:1}
.sidebar-overlay{display:none;position:fixed;inset:0;background:rgba(0,0,0,.5);z-index:99}

/* ── CONTENT ── */
#admin-content{
  margin-left:var(--sidebar);
  flex:1;
  display:flex;
  flex-direction:column;
  height:100vh;
  overflow:hidden;
}
.admin-topbar{
  flex-shrink:0;
  background:var(--white);
  border-bottom:1px solid var(--gray2);
  padding:0 2rem;
  height:var(--topbar);
  display:flex;align-items:center;justify-content:space-between;
  z-index:50;
}
.admin-page-title{font-family:var(--font-display);font-size:1.6rem;font-weight:600;color:var(--brand)}
.topbar-right{display:flex;align-items:center;gap:1rem}

/* scroll só nesta área — sem scrollbar visível */
.admin-main{
  flex:1;
  min-height:0;
  padding:1.5rem;
  overflow-y:auto;
  overflow-x:hidden;
  scrollbar-width:none;
  -ms-overflow-style:none;
}
.admin-main::-webkit-scrollbar{display:none}

/* ── BUTTONS ── */
.btn{display:inline-flex;align-items:center;gap:6px;padding:10px 20px;border-radius:var(--r);font-family:var(--font-head);font-size:13px;font-weight:500;letter-spacing:.5px;cursor:pointer;transition:all .2s;text-decoration:none;border:1.5px solid transparent}
.btn-dark{background:var(--brand);color:var(--white);border-color:var(--brand)}
.btn-dark:hover{background:#333;border-color:#333}
.btn-gold{background:var(--accent);color:var(--brand);border-color:var(--accent)}
.btn-outline{background:transparent;color:var(--brand);border-color:var(--gray2)}
.btn-outline:hover{border-color:var(--brand)}
.btn-sm{padding:6px 12px;font-size:12px}

/* ── FORMS ── */
.form-group{margin-bottom:1.2rem}
.form-group label{display:block;font-size:12px;font-family:var(--font-head);letter-spacing:.5px;text-transform:uppercase;color:var(--gray4);margin-bottom:6px}
.form-group input,.form-group select,.form-group textarea{width:100%;padding:10px 14px;border:1.5px solid var(--gray2);border-radius:var(--r);font-size:14px;font-family:var(--font-body);background:var(--white);color:var(--brand);outline:none;transition:border-color .2s}
.form-group input:focus,.form-group select:focus,.form-group textarea:focus{border-color:var(--accent)}

/* ── ALERTS ── */
.alert-success{background:#dcfce7;border:1px solid #86efac;color:#15803d;padding:1rem;border-radius:var(--r);margin-bottom:1.5rem;font-size:14px}
.alert-error{background:#fee2e2;border:1px solid #fca5a5;color:#b91c1c;padding:1rem;border-radius:var(--r);margin-bottom:1.5rem;font-size:14px}

/* ── MOBILE ── */
@media(max-width:768px){
  .sidebar-toggle{display:block}
  .sidebar-overlay{display:block;opacity:0;pointer-events:none;transition:opacity .3s}
  .sidebar-overlay.open{opacity:1;pointer-events:all}
  #admin-sidebar{transform:translateX(calc(-1 * var(--sidebar)))}
  #admin-sidebar.open{transform:translateX(0)}
  #admin-content{margin-left:0}
  .admin-topbar{padding-left:60px}
  .admin-main{padding:.8rem}
}
</style>
</head>
<body>

<button class="sidebar-toggle" onclick="toggleSidebar()" title="Menu">☰</button>
<div class="sidebar-overlay" id="sidebar-overlay" onclick="toggleSidebar()"></div>

<aside id="admin-sidebar">
  <div class="sidebar-logo">
    <h1>A.<span>Seller</span></h1>
    <small>Admin Panel</small>
  </div>

  <nav class="sidebar-nav">
    <div class="nav-section-label">Principal</div>

    <a href="{{ route('admin.dashboard') }}" class="sidebar-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
      <svg viewBox="0 0 24 24"><rect x="3" y="3" width="7" height="7"/><rect x="14" y="3" width="7" height="7"/><rect x="14" y="14" width="7" height="7"/><rect x="3" y="14" width="7" height="7"/></svg>
      Dashboard
    </a>

    <a href="{{ route('admin.orders.index') }}" class="sidebar-link {{ request()->routeIs('admin.orders*') ? 'active' : '' }}">
      <svg viewBox="0 0 24 24"><path d="M6 2L3 6v14a2 2 0 002 2h14a2 2 0 002-2V6l-3-4z"/><line x1="3" y1="6" x2="21" y2="6"/><path d="M16 10a4 4 0 01-8 0"/></svg>
      Pedidos
    </a>

    <a href="{{ route('admin.products.index') }}" class="sidebar-link {{ request()->routeIs('admin.products*') ? 'active' : '' }}">
      <svg viewBox="0 0 24 24"><path d="M21 16V8a2 2 0 00-1-1.73l-7-4a2 2 0 00-2 0l-7 4A2 2 0 003 8v8a2 2 0 001 1.73l7 4a2 2 0 002 0l7-4A2 2 0 0021 16z"/></svg>
      Produtos
    </a>

    <a href="{{ route('admin.customers.index') }}" class="sidebar-link {{ request()->routeIs('admin.customers*') ? 'active' : '' }}">
      <svg viewBox="0 0 24 24"><path d="M17 21v-2a4 4 0 00-4-4H5a4 4 0 00-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 00-3-3.87"/><path d="M16 3.13a4 4 0 010 7.75"/></svg>
      Clientes
    </a>

    <a href="{{ route('admin.gallery.index') }}" class="sidebar-link {{ request()->routeIs('admin.gallery*') ? 'active' : '' }}">
      <svg viewBox="0 0 24 24"><rect x="3" y="3" width="18" height="18" rx="2"/><circle cx="8.5" cy="8.5" r="1.5"/><polyline points="21 15 16 10 5 21"/></svg>
      Galeria de Fotos
    </a>

    <a href="{{ route('admin.coupons.index') }}" class="sidebar-link {{ request()->routeIs('admin.coupons*') ? 'active' : '' }}">
      <svg viewBox="0 0 24 24"><path d="M20.59 13.41l-7.17 7.17a2 2 0 01-2.83 0L2 12V2h10l8.59 8.59a2 2 0 010 2.82z"/><line x1="7" y1="7" x2="7.01" y2="7"/></svg>
      Cupons de Desconto
    </a>

    <a href="{{ route('admin.reviews.index') }}" class="sidebar-link {{ request()->routeIs('admin.reviews*') ? 'active' : '' }}">
      <svg viewBox="0 0 24 24"><path d="M21 15a2 2 0 01-2 2H7l-4 4V5a2 2 0 012-2h14a2 2 0 012 2z"/><line x1="9" y1="10" x2="15" y2="10"/></svg>
      Avaliações
    </a>

    <a href="{{ route('admin.settings.index') }}" class="sidebar-link {{ request()->routeIs('admin.settings*') ? 'active' : '' }}">
      <svg viewBox="0 0 24 24"><circle cx="12" cy="12" r="3"/><path d="M19.07 4.93l-1.41 1.41M5.34 18.66l-1.41 1.41M2 12h2M20 12h2M19.07 19.07l-1.41-1.41M5.34 5.34L3.93 3.93M12 2v2M12 20v2"/></svg>
      Configurações
    </a>

    <div class="nav-section-label">Sistema</div>

    <a href="{{ route('store') }}" target="_blank" class="sidebar-link">
      <svg viewBox="0 0 24 24"><path d="M18 13v6a2 2 0 01-2 2H5a2 2 0 01-2-2V8a2 2 0 012-2h6"/><polyline points="15 3 21 3 21 9"/><line x1="10" y1="14" x2="21" y2="3"/></svg>
      Ver Loja
    </a>

    <a href="{{ route('logout') }}" class="sidebar-link"
       onclick="event.preventDefault();document.getElementById('logout-form').submit()">
      <svg viewBox="0 0 24 24"><path d="M9 21H5a2 2 0 01-2-2V5a2 2 0 012-2h4"/><polyline points="16 17 21 12 16 7"/><line x1="21" y1="12" x2="9" y2="12"/></svg>
      Sair
    </a>

    <form id="logout-form" method="POST" action="{{ route('logout') }}" style="display:none">@csrf</form>
  </nav>

  <div class="sidebar-footer">
    <div class="sidebar-user">
      <div class="sidebar-avatar">{{ strtoupper(substr(Auth::user()->name ?? 'A', 0, 1)) }}</div>
      <div class="sidebar-user-info">
        <div class="sidebar-user-name">{{ Auth::user()->name ?? 'Admin' }}</div>
        <div class="sidebar-user-role">Administrador</div>
      </div>
    </div>
  </div>
</aside>

<div id="admin-content">
  <div class="admin-topbar">
    <div class="admin-page-title">@yield('title','Dashboard')</div>
    <div class="topbar-right">
      <span style="font-size:13px;color:var(--gray3)">Olá, {{ Auth::user()->name ?? 'Admin' }}!</span>
      <a href="{{ route('store') }}" target="_blank" class="btn btn-gold btn-sm">Ver Loja →</a>
    </div>
  </div>

  <div class="admin-main">
    @if(session('success'))
      <div class="alert-success">✔ {{ session('success') }}</div>
    @endif
    @if(session('error'))
      <div class="alert-error">✖ {{ session('error') }}</div>
    @endif
    @yield('content')
  </div>
</div>

<script>
function toggleSidebar() {
  document.getElementById('admin-sidebar').classList.toggle('open');
  document.getElementById('sidebar-overlay').classList.toggle('open');
}
</script>
</body>
</html>
