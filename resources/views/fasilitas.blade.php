@extends('layouts.app')

@section('content')
    <!-- Facilities Section -->
    <section id="facilities" class="py-20 bg-gray-100" style="padding-top: 120px;">
        <div class="container mx-auto px-6">
            <h2 class="text-3xl font-bold text-center mb-6">FASILITAS UNPAM VIKTOR</h2>
            <p class="text-center text-gray-700 mb-16 max-w-2xl mx-auto">Temukan berbagai fasilitas modern yang mendukung proses belajar mengajar di Program Studi Sistem Informasi</p>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                <div class="facility-card bg-white rounded-lg overflow-hidden shadow-md transition duration-300">
                    <div class="h-48 overflow-hidden">
                        <img src="https://storage.googleapis.com/workspace-0f70711f-8b4e-4d94-86f1-2a93ccde5887/image/c873bb31-8c0a-47d0-b7b9-5a92aeec00cb.png" alt="Laboratorium Komputer" class="w-full h-full object-cover">
                    </div>
                    <div class="p-6">
                        <h3 class="text-xl font-semibold mb-2">Laboratorium Komputer</h3>
                        <p class="text-gray-600">Fasilitas komputer dengan spesifikasi tinggi untuk praktikum pemograman.</p>
                    </div>
                </div>
                <div class="facility-card bg-white rounded-lg overflow-hidden shadow-md transition duration-300">
                    <div class="h-48 overflow-hidden">
                        <img src="{{ asset('asset/perpustakaan 2.webp') }}" alt="Perpustakaan" class="w-full h-full object-cover">
                    </div>
                    <div class="p-6">
                        <h3 class="text-xl font-semibold mb-2">Perpustakaan</h3>
                        <p class="text-gray-600">Ribuan koleksi buku dan jurnal di Perpustakaan Universitas Pamulang.</p>
                    </div>
                </div>
                <div class="facility-card bg-white rounded-lg overflow-hidden shadow-md transition duration-300">
                    <div class="h-48 overflow-hidden">
                        <img src="{{ asset('asset/kelas.jpg') }}" alt="Kelas Ber-AC" class="w-full h-full object-cover">
                    </div>
                    <div class="p-6">
                        <h3 class="text-xl font-semibold mb-2">Kelas Ber-AC</h3>
                        <p class="text-gray-600">Ruangan belajar nyaman dengan fasilitas wifi untuk pembelajaran interaktif.</p>
                    </div>
                </div>
                <div class="facility-card bg-white rounded-lg overflow-hidden shadow-md transition duration-300">
                    <div class="h-48 overflow-hidden">
                        <img src="{{ asset('asset/Auditorium.webp') }}" alt="Auditorium" class="w-full h-full object-cover">
                    </div>
                    <div class="p-6">
                        <h3 class="text-xl font-semibold mb-2">Auditorium</h3>
                        <p class="text-gray-600">Ruang serba guna berkapasitas sekitar 4.000 orang untuk seminar, wisuda, dan acara kampus.</p>
                    </div>
                </div>
                <div class="facility-card bg-white rounded-lg overflow-hidden shadow-md transition duration-300">
                    <div class="h-48 overflow-hidden">
                        <img src="{{ asset('asset/MAsjid.jpg') }}" alt="Masjid" class="w-full h-full object-cover">
                    </div>
                    <div class="p-6">
                        <h3 class="text-xl font-semibold mb-2">Masjid</h3>
                        <p class="text-gray-600">Tempat untuk beribadah yang nyaman dan luas.</p>
                    </div>
                </div>
                <div class="facility-card bg-white rounded-lg overflow-hidden shadow-md transition duration-300">
                    <div class="h-48 overflow-hidden">
                        <img src="https://storage.googleapis.com/workspace-0f70711f-8b4e-4d94-86f1-2a93ccde5887/image/5d6d84f9-51cd-40de-8d73-d1e61c9c31d5.png" alt="Parkiran" class="w-full h-full object-cover">
                    </div>
                    <div class="p-6">
                        <h3 class="text-xl font-semibold mb-2">Parkiran</h3>
                        <p class="text-gray-600">Gedung parkir yang luas untuk kendaraan mobil dan motor.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    @include('partials.contact')
@endsection
