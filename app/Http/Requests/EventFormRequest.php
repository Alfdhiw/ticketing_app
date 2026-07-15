<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class EventFormRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        $user = auth()->user();

        return $user !== null && in_array($user->role, ['admin', 'user'], true);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $rules = [
            'judul' => 'required|string|max:255',
            'kategori_id' => 'required|exists:kategoris,id',
            'deskripsi' => 'required|string',
            'lokasi' => 'required|string|max:255',
            'tanggal_waktu' => 'required|date|after:now',
            'tikets' => 'required|array|min:1',
            'tikets.*.id' => 'nullable|exists:tikets,id',
            'tikets.*.tipe' => 'required|string|in:reguler,premium',
            'tikets.*.harga' => 'required|numeric|min:0',
            'tikets.*.stok' => 'required|integer|min:0',
        ];

        if ($this->isMethod('post')) {
            $rules['gambar'] = 'required|image|mimes:jpg,jpeg,png|max:2048';
        } else {
            $rules['gambar'] = 'nullable|image|mimes:jpg,jpeg,png|max:2048';
        }

        return $rules;
    }

    public function messages(): array
    {
        return [
            'judul.required' => 'Judul event wajib diisi.',
            'judul.max' => 'Judul event maksimal 255 karakter.',
            'kategori_id.required' => 'Kategori event wajib dipilih.',
            'kategori_id.exists' => 'Kategori yang dipilih tidak valid.',
            'deskripsi.required' => 'Deskripsi event wajib diisi.',
            'lokasi.required' => 'Lokasi event wajib diisi.',
            'lokasi.max' => 'Lokasi event maksimal 255 karakter.',
            'tanggal_waktu.required' => 'Tanggal dan waktu event wajib diisi.',
            'tanggal_waktu.date' => 'Tanggal dan waktu event harus format tanggal yang valid.',
            'tanggal_waktu.after' => 'Tanggal dan waktu event harus lebih besar dari waktu saat ini.',
            'gambar.required' => 'Gambar event wajib diunggah.',
            'gambar.image' => 'File gambar harus berupa file gambar.',
            'gambar.mimes' => 'Format gambar harus jpg, jpeg, atau png.',
            'gambar.max' => 'Ukuran gambar maksimal 2 MB.',
            'tikets.required' => 'Minimal harus ada satu tiket.',
            'tikets.array' => 'Data tiket harus berupa array.',
            'tikets.min' => 'Minimal harus ada satu tiket.',
            'tikets.*.tipe.required' => 'Tipe tiket wajib diisi.',
            'tikets.*.tipe.in' => 'Tipe tiket hanya boleh reguler atau premium.',
            'tikets.*.harga.required' => 'Harga tiket wajib diisi.',
            'tikets.*.harga.numeric' => 'Harga tiket harus berupa angka.',
            'tikets.*.harga.min' => 'Harga tiket tidak boleh negatif.',
            'tikets.*.stok.required' => 'Stok tiket wajib diisi.',
            'tikets.*.stok.integer' => 'Stok tiket harus berupa angka bulat.',
            'tikets.*.stok.min' => 'Stok tiket tidak boleh negatif.',
            'tikets.*.id.exists' => 'ID tiket yang dipilih tidak valid.',
        ];
    }
}
