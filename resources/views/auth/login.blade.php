@extends('layouts.app')

@section('title', 'Login - JARA')

@section('content')
    <div class="container">
        <div class="auth-card">
            <div class="auth-header">
                <h1>JARA</h1>
                <p>Sistem Manajemen Tugas & Kolaborasi</p>
            </div>

            @if ($errors->any())
                <div class="alert alert-error">
                    {{ $errors->first() }}
                </div>
            @endif

            <form method="POST" action="{{ route('login.process') }}">
                @csrf

                <div class="form-group">
                    <label for="email">Email</label>
                    <input type="email" id="email" name="email" class="form-control"
                           value="{{ old('email') }}" required autofocus>
                </div>

                <div class="form-group">
                    <label for="password">Password</label>
                    <input type="password" id="password" name="password" class="form-control" required>
                </div>

                <button type="submit" class="btn">Login</button>
            </form>
        </div>
    </div>
@endsection