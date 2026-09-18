@extends('layouts.app')

@section('title', 'Edit User - JARA')

@section('content')
<div class="page-container" style="max-width: 520px;">
    <div class="page-header">
        <div>
            <h1 class="page-title">Edit User: {{ $user->name }}</h1>
            <p class="page-subtitle">Perbarui data akun dan peran user</p>
        </div>
    </div>

    @if ($errors->any())
        <div class="alert alert-error">
            <ul style="padding-left: 20px;">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="card">
        <form method="POST" action="{{ route('admin.users.update', $user->id) }}">
            @csrf
            @method('PUT')

            <div class="form-group">
                <label for="name">Nama</label>
                <input type="text" id="name" name="name" class="form-control" value="{{ old('name', $user->name) }}" required>
            </div>

            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" id="email" name="email" class="form-control" value="{{ old('email', $user->email) }}" required>
            </div>

            <div class="form-group">
                <label for="password">Password Baru (opsional)</label>
                <input type="password" id="password" name="password" class="form-control" minlength="8" placeholder="Kosongkan jika tidak ingin mengubah password">
            </div>

            <div class="form-group">
                <label for="role">Peran</label>
                <select id="role" name="role" class="form-control" required>
                    <option value="user" {{ old('role', $user->role) == 'user' ? 'selected' : '' }}>User</option>
                    <option value="collaborator" {{ old('role', $user->role) == 'collaborator' ? 'selected' : '' }}>Kolaborator</option>
                    <option value="admin" {{ old('role', $user->role) == 'admin' ? 'selected' : '' }}>Admin</option>
                </select>
            </div>

            <div style="display: flex; gap: 12px; margin-top: 24px;">
                <button type="submit" class="btn btn-primary" style="flex: 1;">💾 Simpan Perubahan</button>
                <a href="{{ route('admin.users.index') }}" class="btn btn-secondary">Kembali</a>
            </div>
        </form>
    </div>
</div>
@endsection
