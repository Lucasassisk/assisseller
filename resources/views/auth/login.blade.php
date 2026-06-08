<!DOCTYPE html>
<html lang="pt-BR">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Admin Login · A.Seller</title>
<link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:ital,wght@0,400;0,600;1,400&family=Syne:wght@400;500;600&display=swap" rel="stylesheet">
<style>
*,*::before,*::after{box-sizing:border-box;margin:0;padding:0}
:root{--brand:#0a0a0a;--accent:#c8a96e;--off:#faf9f7;--gray2:#e8e4de;--gray3:#a09880;--r:8px;--font-display:'Cormorant Garamond',serif;--font-head:'Syne',sans-serif}
body{font-family:var(--font-head);background:var(--off);min-height:100vh;display:flex;align-items:center;justify-content:center;padding:2rem}
.login-wrap{width:100%;max-width:400px}
.login-logo{text-align:center;margin-bottom:2.5rem}
.login-logo a{font-family:var(--font-display);font-size:2.5rem;font-weight:600;color:var(--brand);text-decoration:none}
.login-logo a span{color:var(--accent)}
.login-sub{font-size:11px;letter-spacing:2px;text-transform:uppercase;color:var(--gray3);margin-top:.25rem}
.login-card{background:#fff;border-radius:20px;border:1px solid var(--gray2);padding:2.5rem;box-shadow:0 8px 32px rgba(0,0,0,.08)}
.login-card h2{font-family:var(--font-display);font-size:1.5rem;margin-bottom:1.75rem;text-align:center}
.form-group{margin-bottom:1.25rem}
.form-group label{display:block;font-size:10px;letter-spacing:1.5px;text-transform:uppercase;color:var(--gray3);margin-bottom:.4rem;font-weight:500}
.form-group input{width:100%;padding:.75rem 1rem;border:1.5px solid var(--gray2);border-radius:var(--r);font-size:14px;font-family:var(--font-head);transition:border-color .2s}
.form-group input:focus{outline:none;border-color:var(--accent)}
.btn-login{width:100%;padding:1rem;background:var(--brand);color:#fff;border:none;border-radius:var(--r);font-family:var(--font-head);font-size:14px;font-weight:600;letter-spacing:1px;cursor:pointer;transition:background .2s;margin-top:.5rem}
.btn-login:hover{background:#333}
.login-back{text-align:center;margin-top:1.5rem;font-size:13px;color:var(--gray3)}
.login-back a{color:var(--accent);text-decoration:none}
.error-msg{background:#fee2e2;color:#991b1b;border:1px solid #fca5a5;border-radius:var(--r);padding:.75rem 1rem;font-size:13px;margin-bottom:1.25rem}
.remember-row{display:flex;align-items:center;justify-content:space-between;margin-bottom:1rem}
.remember-row label{font-size:12px;color:var(--gray3);display:flex;align-items:center;gap:6px;text-transform:none;letter-spacing:0;cursor:pointer}
</style>
</head>
<body>
<div class="login-wrap">
  <div class="login-logo">
    <a href="{{ route('store') }}">A.<span>Seller</span></a>
    <div class="login-sub">Painel Administrativo</div>
  </div>
  <div class="login-card">
    <h2>Acesso Admin</h2>

    @if($errors->any())
      <div class="error-msg">{{ $errors->first() }}</div>
    @endif
    @if(session('status'))
      <div style="background:#dcfce7;color:#15803d;border:1px solid #86efac;border-radius:var(--r);padding:.75rem 1rem;font-size:13px;margin-bottom:1.25rem">{{ session('status') }}</div>
    @endif

    <form method="POST" action="{{ route('login') }}">
      @csrf
      <div class="form-group">
        <label>E-mail</label>
        <input type="email" name="email" value="{{ old('email') }}" placeholder="seu@email.com" required autofocus autocomplete="email">
      </div>
      <div class="form-group">
        <label>Senha</label>
        <input type="password" name="password" placeholder="••••••••" required autocomplete="current-password">
      </div>
      <div class="remember-row">
        <label><input type="checkbox" name="remember"> Lembrar-me</label>
        @if(Route::has('password.request'))
          <a href="{{ route('password.request') }}" style="font-size:12px;color:var(--accent);text-decoration:none">Esqueci a senha</a>
        @endif
      </div>
      <button type="submit" class="btn-login">ENTRAR NO PAINEL</button>
    </form>
  </div>
  <div class="login-back">
    <a href="{{ route('store') }}">← Voltar para a loja</a>
  </div>
</div>
</body>
</html>
