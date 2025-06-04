@extends('layouts.admindashboard') {{-- Menggunakan layout admin --}}

@section('content')
<div class="container mx-auto px-6 py-8">
    <h1 class="text-4xl font-extrabold text-gray-800 mb-10 tracking-wide text-center">Detail Jurusan: {{ $major->name }}</h1>

    <div class="bg-white shadow-xl rounded-lg overflow-hidden max-w-2xl mx-auto p-8 border border-gray-200">
        <div class="space-y-4 text-gray-700">
            <p class="text-lg"><strong>ID:</strong> {{ $major->id }}</p>
            <p class="text-lg"><strong>Nama Jurusan:</strong> {{ $major->name }}</p>
            <p class="text-lg"><strong>Deskripsi:</strong> {{ $major->description ?? 'Tidak ada deskripsi.' }}</p>
            <p class="text-lg"><strong>Dibuat Pada:</strong> {{ $major->created_at->format('d M Y H:i') }}</p>
            <p class="text-lg"><strong>Terakhir Diperbarui:</strong> {{ $major->updated_at->format('d M Y H:i') }}</p>
        </div>

        <div class="mt-8 flex justify-end space-x-4">
            <a href="{{ route('majors.edit', $major->id) }}"
               class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700
                      transition duration-300 ease-in-out transform hover:scale-105">
                <i class="fas fa-edit mr-2"></i> Edit Jurusan
            </a>
            <a href="{{ route('majors.index') }}"
               class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50
                      transition duration-300 ease-in-out transform hover:scale-105">
                Kembali ke Daftar
            </a>
        </div>
    </div>
</div>
@endsection
