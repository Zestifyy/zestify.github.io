{{-- resources/views/admin/registrations/show.blade.php --}}

@extends('layouts.admindashboard') {{-- Sesuaikan dengan layout admin Anda --}}

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-4xl mx-auto bg-white shadow-lg rounded-lg p-6">
        <h1 class="text-2xl font-bold text-gray-800 mb-6">Detail Pendaftaran Event</h1>

        @if(session('success'))
            <x-alert type="success" message="{{ session('success') }}" />
        @endif
        @if(session('error'))
            <x-alert type="danger" message="{{ session('error') }}" />
        @endif

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6 text-gray-700">
            <div>
                <h2 class="text-xl font-semibold text-gray-800 mb-3">Informasi Peserta</h2>
                <p><strong>Nama:</strong> {{ $registration->user->name ?? 'N/A' }}</p>
                <p><strong>Email:</strong> {{ $registration->user->email ?? 'N/A' }}</p>
                <p><strong>Jurusan:</strong> {{ $registration->user->alumniProfile->major->name ?? 'N/A' }}</p>
                <p><strong>Angkatan:</strong> {{ $registration->user->alumniProfile->graduation_year ?? 'N/A' }}</p>
            </div>
            <div>
                <h2 class="text-xl font-semibold text-gray-800 mb-3">Informasi Event</h2>
                <p><strong>Judul Event:</strong> {{ $registration->event->title ?? 'N/A' }}</p>
                <p><strong>Tanggal Event:</strong> {{ \Carbon\Carbon::parse($registration->event->event_date)->translatedFormat('d F Y') }}</p>
                <p><strong>Waktu Event:</strong> {{ \Carbon\Carbon::parse($registration->event->event_time)->format('H:i') }} WIB</p>
                <p><strong>Lokasi:</strong> {{ $registration->event->location ?? 'N/A' }}</p>
                <p><strong>Berbayar:</strong> {{ $registration->event->is_paid ? 'Ya' : 'Tidak' }}</p>
                @if($registration->event->is_paid)
                    <p><strong>Harga:</strong> Rp {{ number_format($registration->event->price, 0, ',', '.') }}</p>
                @endif
            </div>
        </div>

        <div class="mb-6 border-t pt-6 mt-6">
            <h2 class="text-xl font-semibold text-gray-800 mb-3">Detail Pendaftaran</h2>
            <p><strong>Status:</strong>
                <span class="px-2 inline-flex text-sm leading-5 font-semibold rounded-full
                    @if($registration->status == 'confirmed') bg-green-100 text-green-800
                    @elseif($registration->status == 'pending_confirmation') bg-blue-100 text-blue-800
                    @elseif($registration->status == 'pending_payment') bg-yellow-100 text-yellow-800
                    @else bg-red-100 text-red-800
                    @endif">
                    {{ ucfirst(str_replace('_', ' ', $registration->status)) }}
                </span>
            </p>
            <p><strong>Tanggal Pendaftaran:</strong> {{ $registration->created_at->format('d F Y H:i') }}</p>

            @if($registration->event->is_paid)
                <h3 class="text-lg font-semibold text-gray-800 mt-4 mb-2">Bukti Pembayaran</h3>
                @if($registration->payment_proof)
                    <img src="{{ $registration->payment_proof_url }}" alt="Bukti Pembayaran" class="w-full md:w-1/2 h-auto rounded-lg shadow-md border border-gray-200 mb-4">
                    <p class="text-sm text-gray-600">Pastikan bukti pembayaran valid sebelum mengkonfirmasi.</p>
                @else
                    <p class="text-gray-600">Bukti pembayaran belum diunggah.</p>
                @endif
            @endif
        </div>

        <div class="flex flex-col sm:flex-row gap-4 border-t pt-6 mt-6">
            @if($registration->status === 'pending_confirmation')
                <form action="{{ route('admin.registrations.confirm', $registration->id) }}" method="POST" class="w-full sm:w-auto">
                    @csrf
                    @method('PUT')
                    <button type="submit" class="w-full sm:w-auto bg-green-600 hover:bg-green-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
                        Konfirmasi Pembayaran
                    </button>
                </form>

                <form action="{{ route('admin.registrations.reject', $registration->id) }}" method="POST" class="w-full sm:w-auto" onsubmit="return confirm('Apakah Anda yakin ingin menolak pendaftaran ini?');">
                    @csrf
                    @method('PUT')
                    <button type="submit" class="w-full sm:w-auto bg-red-600 hover:bg-red-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
                        Tolak Pendaftaran
                    </button>
                </form>
            @elseif($registration->status === 'confirmed')
                <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 rounded" role="alert">
                    Pendaftaran ini sudah dikonfirmasi.
                </div>
            @else
                <div class="bg-yellow-100 border-l-4 border-yellow-500 text-yellow-700 p-4 rounded" role="alert">
                    Status pendaftaran saat ini: {{ ucfirst(str_replace('_', ' ', $registration->status)) }}.
                </div>
            @endif

            <form action="{{ route('admin.registrations.destroy', $registration->id) }}" method="POST" class="w-full sm:w-auto mt-4 sm:mt-0" onsubmit="return confirm('Apakah Anda yakin ingin menghapus pendaftaran ini secara permanen?');">
                @csrf
                @method('DELETE')
                <button type="submit" class="w-full sm:w-auto bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
                    Hapus Pendaftaran
                </button>
            </form>
        </div>
    </div>
</div>
@endsection