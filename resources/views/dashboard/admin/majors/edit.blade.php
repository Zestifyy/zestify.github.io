    @extends('layouts.admindashboard') {{-- Sesuaikan dengan layout admin Anda --}}

    @section('content')
    <div class="container mx-auto px-4 py-8">
        <h2 class="text-2xl font-semibold mb-6">Edit Jurusan: {{ $major->name }}</h2>
        <div class="bg-white p-6 rounded-lg shadow-md">
            <form action="{{ route('majors.update', $major->id) }}" method="POST"> {{-- Pastikan nama rute ini benar --}}
                @csrf
                @method('PUT')
                <div class="mb-4">
                    <label for="code" class="block text-gray-700 text-sm font-bold mb-2">Kode Jurusan:</label>
                    <input type="text" name="code" id="code" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('code') border-red-500 @enderror" value="{{ old('code', $major->code) }}" required>
                    @error('code')
                        <p class="text-red-500 text-xs italic mt-2">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-4">
                    <label for="name" class="block text-gray-700 text-sm font-bold mb-2">Nama Jurusan:</label>
                    <input type="text" name="name" id="name" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('name') border-red-500 @enderror" value="{{ old('name', $major->name) }}" required>
                    @error('name')
                        <p class="text-red-500 text-xs italic mt-2">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-6">
                    <label for="description" class="block text-gray-700 text-sm font-bold mb-2">Deskripsi:</label>
                    <textarea name="description" id="description" rows="4" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('description') border-red-500 @enderror">{{ old('description', $major->description) }}</textarea>
                    @error('description')
                        <p class="text-red-500 text-xs italic mt-2">{{ $message }}</p>
                    @enderror
                </div>

                <div class="flex items-center justify-between">
                    <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
                        Perbarui Jurusan
                    </button>
                    <a href="{{ route('majors.index') }}" class="inline-block align-baseline font-bold text-sm text-blue-600 hover:text-blue-800"> {{-- Pastikan nama rute ini benar --}}
                        Batal
                    </a>
                </div>
            </form>
        </div>
    </div>
    @endsection
    