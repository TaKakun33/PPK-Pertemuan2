@extends('layouts.app')

@section('title', 'Home - JARA')

@section('content')
    <div class="nav">
        <span class="brand">JARA</span>
        <div class="user">
            {{ Auth::user()->name }}
            <span class="role-badge {{ Auth::user()->role }}">
                {{ Auth::user()->role }}
            </span>
        </div>
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="btn-logout">Logout</button>
        </form>
    </div>

    <div style="max-width: 720px; margin: 40px auto; padding: 0 16px;">
        <div class="card">
            <h2>Selamat datang, {{ Auth::user()->name }}</h2>
            <p class="text-muted" style="margin-top: 8px;">
                Anda login sebagai <strong>{{ Auth::user()->role }}</strong>.
            </p>
            
            <!-- Tombol untuk menuju halaman tasks -->
            <div style="margin-top: 24px;">
                <a href="{{ route('tasks.index') }}" style="display: inline-block; padding: 10px 20px; background-color: #2563eb; color: #ffffff; text-decoration: none; border-radius: 6px; font-weight: 500;">
                    Atur Tugas
                </a>
            </div>
        </div>
    </div>
@endsection