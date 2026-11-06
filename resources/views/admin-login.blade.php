@extends('layouts.app')

@section('title', 'Admin CMS — TEFC')

@section('styles')
<style>
.login-screen {
  min-height: calc(100vh - 120px);
  display: flex;
  align-items: center;
  justify-content: center;
  padding: 2rem;
  background: linear-gradient(135deg, var(--primary-dark), var(--primary) 58%, #a0245e);
}
.login-card {
  width: 100%;
  max-width: 430px;
  background: #fff;
  border-radius: 10px;
  padding: 2.5rem;
  box-shadow: 0 18px 55px rgba(0,0,0,0.25);
}
.fg { margin-bottom: 1.2rem; }
.fg label { display: block; font-size: 0.75rem; font-weight: 700; margin-bottom: 6px; color: var(--text); }
.fg input { width: 100%; border: 1px solid var(--border); border-radius: 6px; padding: 0.7rem; font-size: 0.85rem; }
.btn-primary { background: var(--primary); color: #fff; border: 0; border-radius: 6px; padding: 0.8rem; font-weight: 700; width: 100%; cursor: pointer; }
.btn-primary:hover { background: var(--primary-dark); }
.alert { padding: 0.8rem; border-radius: 6px; font-size: 0.8rem; margin-bottom: 1rem; }
.alert.err { background: #fff0f0; border: 1px solid #f2c1bd; color: #c0392b; }
</style>
@endsection

@section('content')
<div class="login-screen">
  <div class="login-card">
    
    @if ($errors->any())
      <div class="alert err">
        {{ $errors->first() }}
      </div>
    @endif
    
    <form method="POST" action="{{ route('login') }}">
        @csrf
        <div class="fg">
          <label>Email Address</label>
          <input type="email" name="email" value="{{ old('email') }}" required autofocus placeholder="admin@tefc.gov.in">
        </div>
        <div class="fg">
          <label>Password</label>
          <input type="password" name="password" required placeholder="Enter password">
        </div>
        <button type="submit" class="btn-primary">Login to CMS</button>
    </form>
  </div>
</div>
@endsection
