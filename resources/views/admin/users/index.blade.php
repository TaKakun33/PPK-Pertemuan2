@extends('layouts.app')

@section('title', 'Kelola User - JARA')

@section('content')
<div class="page-container">
    <div class="page-header">
        <div>
            <h1 class="page-title">Manajemen User</h1>
            <p class="page-subtitle">Kelola akun Admin, User, dan Kolaborator dalam sistem</p>
        </div>
        <div>
            <a href="{{ route('admin.users.create') }}" class="btn btn-primary">+ Tambah User</a>
        </div>
    </div>

    @if (session('success'))
        <div class="alert alert-success"><strong>Berhasil!</strong> {{ session('success') }}</div>
    @endif

    @if (session('error'))
        <div class="alert alert-error"><strong>Perhatian:</strong> {{ session('error') }}</div>
    @endif

    <div class="card" style="padding: 0; overflow: hidden;">
        <div class="table-responsive" style="border: none; border-radius: 0;">
            <table class="table">
                <thead>
                    <tr>
                        <th style="width: 50px;">No</th>
                        <th>Nama</th>
                        <th>Email</th>
                        <th>Peran</th>
                        <th>Tugas Dibuat</th>
                        <th>Tugas Kolaborasi</th>
                        <th style="text-align: right; width: 180px;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($users as $index => $u)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td><strong>{{ $u->name }}</strong></td>
                            <td>{{ $u->email }}</td>
                            <td><span class="role-badge {{ $u->role }}">{{ $u->role }}</span></td>
                            <td>{{ $u->tasks_count }}</td>
                            <td>{{ $u->collaborated_tasks_count }}</td>
                            <td style="text-align: right;">
                                <a href="{{ route('admin.users.edit', $u->id) }}" class="btn btn-secondary btn-sm">Edit</a>
                                <form action="{{ route('admin.users.destroy', $u->id) }}" method="POST" style="display:inline; margin: 0;">
                                    @csrf
                                    @method('DELETE')
                                    <button
                                        type="submit"
                                        class="btn btn-danger btn-sm"
                                        onclick="return confirm('Hapus akun {{ $u->name }}?')"
                                        {{ $u->id === Auth::id() ? 'disabled' : '' }}
                                    >
                                        Hapus
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
