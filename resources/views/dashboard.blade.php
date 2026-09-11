@extends('layouts.app')

@section('content')

@php
    $tasks = [
        ['title' => 'Contoh tugas 1', 'priority' => 'high', 'due_date' => '2026-09-15', 'status' => 'proses'],
        ['title' => 'Contoh tugas 2', 'priority' => 'low', 'due_date' => '2026-09-20', 'status' => 'selesai'],
    ];
@endphp

@foreach($tasks as $task)
    <p>{{ $task['title'] }}</p>
@endforeach

@endsection