@extends('layouts.app')

@section('content')
    <!-- Hero Section -->
    <section id="home" class="hero-section h-screen flex items-center justify-center text-white">
        <div class="text-center px-4">
            <h1 class="text-4xl md:text-6xl font-bold mb-6">{{ e($content['hero_title']) }}</h1>
            <h2 class="text-3xl md:text-5xl font-bold mb-8">{{ e($content['hero_subtitle']) }}</h2>
            <p class="text-xl mb-10 max-w-3xl mx-auto">{{ e($content['hero_description']) }}</p>
            <a href="{{ route('virtual-tour') }}" class="mt-8 bg-yellow-500 hover:bg-yellow-600 text-white font-medium py-3 px-8 rounded-full transition duration-300 inline-flex items-center">
                <i class="fas fa-vr-cardboard mr-2"></i> Mulai Tour
            </a>
        </div>
    </section>

    <!-- Virtual Tour Section -->
    <section id="tour" class="py-20 bg-white">
        <div class="container mx-auto px-6">
            <h2 class="text-3xl font-bold text-center mb-16">VIRTUAL TOUR 360°</h2>
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 items-center">
                <div>
                    <div class="bg-gray-200 rounded-lg overflow-hidden h-96 relative">
                        <img src="https://static.republika.co.id/uploads/member/images/news/2x4cu8nrv8.jpg" alt="Pratinjau virtual tour 360 derajat" class="w-full h-full object-cover">
                    </div>
                </div>
                <div>
                    <h3 class="text-2xl font-semibold mb-4">Jelajahi Kampus Secara Virtual</h3>
                    <p class="mb-6 leading-relaxed">Dengan teknologi virtual tour 360°, Anda dapat menjelajahi berbagai fasilitas Prodi Sistem Informasi Universitas Pamulang dari mana saja dan kapan saja.</p>
                    <div class="space-y-4">
                        <div class="flex items-start">
                            <div class="bg-blue-100 p-2 rounded-full mr-4"><i class="fas fa-check text-blue-600"></i></div>
                            <p>Pandangan 360° berbagai ruangan penting</p>
                        </div>
                        <div class="flex items-start">
                            <div class="bg-blue-100 p-2 rounded-full mr-4"><i class="fas fa-check text-blue-600"></i></div>
                            <p>Navigasi intuitif dengan menu interaktif</p>
                        </div>
                        <div class="flex items-start">
                            <div class="bg-blue-100 p-2 rounded-full mr-4"><i class="fas fa-check text-blue-600"></i></div>
                            <p>Informasi detail setiap fasilitas</p>
                        </div>
                    </div>
                    <a href="{{ route('virtual-tour') }}" class="mt-8 bg-blue-600 hover:bg-blue-700 text-white font-medium py-3 px-8 rounded-full transition duration-300 inline-flex items-center">
                        <i class="fas fa-vr-cardboard mr-2"></i> Mulai Virtual Tour
                    </a>
                </div>
            </div>
        </div>
    </section>

    <!-- Facilities Section -->
    <section id="fasilitas" class="py-20 bg-gray-100">
        <div class="container mx-auto px-6">
            <h2 class="text-3xl font-bold text-center mb-6">{{ e($content['facilities_title']) }}</h2>
            <p class="text-center text-gray-700 mb-16 max-w-2xl mx-auto">{{ e($content['facilities_description']) }}</p>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                @forelse($facilities as $facility)
                    <div class="facility-card bg-white rounded-lg overflow-hidden shadow-md transition duration-300">
                        <div class="h-48 overflow-hidden">
                            <img src="{{ asset($facility->image ?? 'asset/default.jpg') }}" alt="{{ e($facility->name) }}" class="w-full h-full object-cover">
                        </div>
                        <div class="p-6">
                            <h3 class="text-xl font-semibold mb-2">{{ e($facility->name) }}</h3>
                            <p class="text-gray-600">{{ e($facility->description) }}</p>
                        </div>
                    </div>
                @empty
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
                @endforelse
            </div>
        </div>
    </section>

    <!-- Campus Location Section -->
    <section id="lokasi-kampus" class="py-16 bg-white">
        <div class="container mx-auto px-4">
            <h2 class="text-3xl font-bold text-center mb-12 text-gray-800">Lokasi Kampus UNPAM Viktor</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-12">
                <div>
                    <h3 class="text-xl font-semibold mb-4">Denah Kampus</h3>
                    <img src="{{ asset('asset/kampus2.B0WqicWG.jpg') }}" alt="Foto Denah" class="rounded-lg shadow-xl">
                    <p class="text-gray-600 mt-4">Lihat denah kampus untuk orientasi lebih lanjut.</p>
                </div>
                <div>
                    <h3 class="text-xl font-semibold mb-4">Lokasi di Google Maps</h3>
                    <div class="w-full h-[400px]">
                        <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3966.155041658!2d106.6889577!3d-6.3462879!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2e69e5a6e26dc3cd%3A0xccd6344b8021119d!2sUniversitas%20Pamulang%20Kampus%202%20(UNPAM%20Viktor)!5e0!3m2!1sid!2sid!4v1723900000000!5m2!1sid!2sid" width="100%" height="100%" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade" class="rounded-lg shadow-lg"></iframe>
                    </div>
                    <p class="text-gray-600 mt-4">Temukan lokasi kami dengan mudah melalui Google Maps.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- About Section -->
    <section id="about" class="py-20 bg-white">
        <div class="container mx-auto px-6">
            <h2 class="text-3xl font-bold text-center mb-16">TENTANG PRODI SISTEM INFORMASI</h2>
            <div class="flex flex-col md:flex-row items-center">
                <div class="md:w-1/2 mb-10 md:mb-0">
                    <img src="{{ asset('asset/Landscape HMSI.jpeg') }}" alt="Foto Kegiatan" class="rounded-lg shadow-xl">
                </div>
                <div class="md:w-1/2 md:pl-12">
                    <h3 class="text-2xl font-semibold mb-4">Visi & Misi</h3>
                    <p class="mb-6 leading-relaxed">Menjadi program studi unggulan dalam bidang Sistem Informasi yang berdaya saing di tingkat nasional pada tahun 2030.</p>
                    <p class="mb-6 leading-relaxed">Untuk mencapai visi ini, kami berkomitmen untuk:</p>
                    <ul class="list-disc pl-6 mb-6 space-y-2">
                        <li>Menyelenggarakan pendidikan berbasis kompetensi</li>
                        <li>Mengembangkan penelitian inovatif</li>
                        <li>Melaksanakan pengabdian kepada masyarakat</li>
                        <li>Menjalin kerjasama dengan berbagai pihak</li>
                    </ul>
                    <div class="mt-8">
                        <a href="#" class="bg-blue-500 hover:bg-blue-700 text-white font-medium py-2 px-6 rounded-md transition duration-300 inline-block">Selengkapnya</a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Contact Section -->
    @include('partials.contact')
@endsection
