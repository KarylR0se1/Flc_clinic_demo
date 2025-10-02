@extends('layouts.app')

@section('content')
<div class="max-w-md mx-auto bg-white p-6 rounded shadow">
  <h2 class="text-xl font-bold mb-4">Login</h2>

  <form method="POST" action="{{ route('login.submit') }}">
    @csrf
    <input name="email" type="email" placeholder="Email" required class="w-full mb-2 p-2 border" value="{{ old('email') }}">
    <input name="password" type="password" placeholder="Password" required class="w-full mb-2 p-2 border">
    <label class="inline-flex items-center space-x-2">
      <input type="checkbox" name="remember" class="form-checkbox">
      <span class="text-sm">Remember me</span>
    </label>
    <button type="submit" class="w-full p-2 bg-indigo-600 text-white rounded mt-3">Login</button>
  </form>

  <p class="mt-4 text-sm">
    Don't have an account? <a href="{{ route('register.patient') }}" class="text-blue-600">Register</a>
  </p>
</div>
@endsection
