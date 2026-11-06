@extends('layouts.app')

@section('title', 'Login — TEFC Portal')

@section('styles')
<style>
.auth-screen {
  min-height: 80vh;
  display: flex;
  align-items: center;
  justify-content: center;
  padding: 4rem 2rem;
  background: var(--sky2);
}
.auth-card {
  background: #fff;
  width: 100%;
  max-width: 450px;
  border-radius: 25px;
  padding: 3rem;
  box-shadow: 0 20px 60px rgba(108, 21, 63, 0.08);
  border: 1px solid var(--border);
}
.auth-head h2 { color: var(--primary); font-size: 2.2rem; font-weight: 800; margin-bottom: 0.5rem; }
.auth-head p { color: var(--muted); font-size: 1rem; }

.form-group { margin-bottom: 1.5rem; }
.form-group label { display: block; font-size: 0.85rem; font-weight: 700; color: var(--primary); text-transform: uppercase; margin-bottom: 0.5rem; }
.form-control { width: 100%; padding: 1rem; border: 2px solid #f0f0f0; border-radius: 12px; font-size: 1rem; transition: all 0.3s; }
.form-control:focus { border-color: var(--primary-light); outline: none; background: var(--sky2); }

.btn-auth { background: var(--primary); color: #fff; width: 100%; padding: 1.1rem; border: none; border-radius: 12px; font-size: 1.1rem; font-weight: 700; cursor: pointer; transition: all 0.3s; margin-top: 1rem; }
.btn-auth:hover { background: var(--primary-dark); transform: translateY(-3px); box-shadow: 0 10px 25px rgba(108, 21, 63, 0.2); }

.auth-footer { text-align: center; margin-top: 2rem; font-size: 0.9rem; color: var(--muted); }
.auth-footer a { color: var(--primary); font-weight: 700; text-decoration: none; }
</style>
@endsection

@section('content')
<div class="auth-screen">
  <div class="auth-card">
    <div class="auth-head">
      <h2>Welcome Back</h2>
      <p>Sign in to your portal account</p>
    </div>

    @if(session('success'))
      <div style="background: #e8f5e9; border: 1px solid #c8e6c9; color: #2e7d32; padding: 1rem 1.2rem; border-radius: 12px; margin-bottom: 1.5rem; font-size: 0.9rem; line-height: 1.5;">
        <i class="fas fa-check-circle me-1"></i> {{ session('success') }}
      </div>
    @endif

    @if(session('error'))
      <div style="background: #ffebee; border: 1px solid #ffcdd2; color: #c62828; padding: 1rem 1.2rem; border-radius: 12px; margin-bottom: 1.5rem; font-size: 0.9rem; line-height: 1.5;">
        <i class="fas fa-exclamation-triangle me-1"></i> {{ session('error') }}
      </div>
    @endif

    @if($errors->any())
      <div style="background: #fff8e1; border: 1px solid #ffe082; color: #b78103; padding: 1rem 1.2rem; border-radius: 12px; margin-bottom: 1.5rem; font-size: 0.9rem; line-height: 1.5;">
        <i class="fas fa-info-circle me-1"></i> {{ $errors->first() }}
      </div>
    @endif

    <form action="{{ url('/login') }}" method="POST">
      @csrf
      <div class="form-group">
        <label>Email Address</label>
        <input type="email" name="email" class="form-control" placeholder="john@example.com" required value="{{ old('email') }}">
        @error('email') <small style="color:red;">{{ $message }}</small> @enderror
      </div>

      <div class="form-group">
        <label>Password</label>
        <input type="password" name="password" class="form-control" placeholder="********" required>
        @error('password') <small style="color:red;">{{ $message }}</small> @enderror
      </div>

      <button type="submit" class="btn-auth">Login to Account</button>
    </form>

    <div class="auth-footer">
      Don't have an account? <a href="{{ url('/register') }}">Register here</a>
    </div>
  </div>
</div>
@endsection
