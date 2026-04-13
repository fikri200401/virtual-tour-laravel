@extends('layouts.app')

@section('content')
    <!-- About Section -->
    <section id="about" class="py-20 bg-white" style="padding-top: 120px;">
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

    <!-- Campus Location -->
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

    @include('partials.contact')
@endsection
