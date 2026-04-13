<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Virtual Tour Prodi Sistem Informasi - Universitas Pamulang')</title>
    <link rel="icon" type="image/png" href="{{ asset('asset/logo-unpam-300x291.png') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @stack('styles')
</head>
<body class="font-sans bg-gray-100" x-data="{ mobileMenuOpen: false }">

    <!-- Navbar -->
    <nav class="navbar fixed w-full z-50 text-white" x-data="{ scrolled: false }"
         x-init="window.addEventListener('scroll', () => { scrolled = window.scrollY > 50 })"
         :class="{ 'scrolled': scrolled, 'bg-gray-600': {{ request()->routeIs('fasilitas', 'tentang') ? 'true' : 'false' }} }">
        <div class="container mx-auto px-6 py-3 flex justify-between items-center">
            <div class="flex items-center">
                <img src="{{ asset('asset/logo-unpam-300x291.png') }}" alt="Logo Universitas Pamulang" class="h-12">
                <div class="ml-4">
                    <h1 class="text-xl font-bold">UNIVERSITAS PAMULANG</h1>
                    <p class="text-sm">Prodi Sistem Informasi</p>
                </div>
            </div>
            <div class="hidden md:flex space-x-8">
                <a href="{{ route('home') }}" class="hover:text-yellow-300">Beranda</a>
                <a href="{{ route('virtual-tour') }}" class="hover:text-yellow-300">Virtual Tour</a>
                <a href="{{ route('fasilitas') }}" class="hover:text-yellow-300">Fasilitas</a>
                <a href="{{ route('tentang') }}" class="hover:text-yellow-300">Tentang</a>
                @if(request()->routeIs('login'))
                    <a href="{{ route('login') }}" class="hover:text-gray-200">Login Admin</a>
                @endif
            </div>
            <button class="md:hidden focus:outline-none" @click="mobileMenuOpen = !mobileMenuOpen">
                <i class="fas fa-bars text-2xl"></i>
            </button>
        </div>
        <!-- Mobile Menu -->
        <div x-show="mobileMenuOpen" x-transition class="md:hidden bg-blue-700 px-6 py-4 space-y-3">
            <a href="{{ route('home') }}" class="block hover:text-yellow-300">Beranda</a>
            <a href="{{ route('virtual-tour') }}" class="block hover:text-yellow-300">Virtual Tour</a>
            <a href="{{ route('fasilitas') }}" class="block hover:text-yellow-300">Fasilitas</a>
            <a href="{{ route('tentang') }}" class="block hover:text-yellow-300">Tentang</a>
        </div>
    </nav>

    @yield('content')

    <!-- Footer -->
    <footer class="bg-gray-800 text-white py-12">
        <div class="container mx-auto px-4">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-12 justify-items-center text-center">
                <div>
                    <img src="{{ asset('asset/logo-unpam-300x291.png') }}" alt="Logo Universitas Pamulang" class="h-16 w-16 mb-4">
                    <p class="text-gray-400 max-w-sm">Universitas Pamulang memberikan pendidikan berkualitas untuk masa depan yang lebih baik.</p>
                </div>
                <div>
                    <h4 class="text-lg font-semibold mb-4">Tautan Cepat</h4>
                    <ul class="space-y-2">
                        <li><a href="https://rl-pmb.unpam.ac.id/" class="text-gray-400 hover:text-white" target="_blank">Penerimaan Mahasiswa Baru</a></li>
                        <li><a href="https://unpam.ac.id/kampus-2-unpam-viktor" class="text-gray-400 hover:text-white" target="_blank">Informasi Universitas Pamulang</a></li>
                        <li><a href="https://unpam.ac.id/profil-unpam" class="text-gray-400 hover:text-white" target="_blank">Profil UNPAM</a></li>
                        <li><a href="https://unpam.ac.id/visi-misi" class="text-gray-400 hover:text-white" target="_blank">Visi dan Misi</a></li>
                        <li><a href="https://unpam.ac.id/sejarah" class="text-gray-400 hover:text-white" target="_blank">Histori</a></li>
                    </ul>
                </div>
                <div>
                    <h4 class="text-lg font-semibold mb-4">Ikuti Kami</h4>
                    <div class="flex justify-center space-x-4 mb-4">
                        <a href="https://www.instagram.com/sisteminformasi.unpam/" target="_blank" class="bg-gray-700 p-2 rounded-full hover:bg-blue-600">FB</a>
                        <a href="https://www.instagram.com/UNPAM/" target="_blank" class="bg-gray-700 p-2 rounded-full hover:bg-purple-600">IG</a>
                        <a href="https://www.youtube.com/@unpamofficial" target="_blank" class="bg-gray-700 p-2 rounded-full hover:bg-red-600">YT</a>
                        <a href="https://wa.me/6281395333634" target="_blank" class="bg-gray-700 p-2 rounded-full hover:bg-green-500">WA</a>
                    </div>
                    <div class="bg-gray-700 p-2 rounded-lg">
                        <h4 class="font-medium mb-2">Hubungi Kami Via WhatsApp</h4>
                        <p class="text-gray-300 text-sm mb-4">Butuh bantuan? Chat langsung dengan admin kami untuk informasi pendaftaran, jadwal kunjungan, atau pertanyaan lainnya.</p>
                        <div class="flex items-center justify-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 text-green-400" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/>
                            </svg>
                            <span class="text-sm">Akan kami Respon</span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="border-t border-gray-700 mt-12 pt-8 text-center text-gray-400">
                <p>&copy; 2025 Universitas Pamulang</p>
            </div>
        </div>
    </footer>

    @stack('scripts')
    <script>
        // Navbar scroll effect
        window.addEventListener('scroll', function() {
            const navbar = document.querySelector('.navbar');
            if (window.scrollY > 50) { navbar.classList.add('scrolled'); }
            else { navbar.classList.remove('scrolled'); }
        });
    </script>
</body>
</html>
