<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreTaskListRequest extends FormRequest
{
    /**
     * FR-12: hanya pengguna yang sudah login (berwenang) yang boleh
     * membuat daftar tugas. Jika false -> Laravel melempar 403.
     */
    public function authorize(): bool
    {
        return $this->user() !== null;
    }

    /**
     * NFR-05: seluruh input wajib divalidasi.
     * Eloquent sendiri sudah memakai prepared statement/parameterized query.
     */
    public function rules(): array
    {
        return [
            'nama' => ['required', 'string', 'min:3', 'max:100'],
            'deskripsi' => ['nullable', 'string', 'max:500'],
        ];
    }

    public function messages(): array
    {
        return [
            'nama.required' => 'Nama daftar tugas wajib diisi.',
            'nama.min' => 'Nama daftar tugas minimal 3 karakter.',
            'nama.max' => 'Nama daftar tugas maksimal 100 karakter.',
        ];
    }
}