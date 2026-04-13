<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="Cache-Control" content="no-cache, no-store, must-revalidate">
    <meta http-equiv="Pragma" content="no-cache">
    <meta http-equiv="Expires" content="0">
    <title>Admin Dashboard - Virtual Tour UNPAM</title>
    <link rel="icon" type="image/png" href="{{ asset('asset/logo-unpam-300x291.png') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-100" x-data="{ activeTab: '{{ request('tab', 'dashboard') }}', editSceneModal: false, editSceneData: {} }">
    <!-- Header -->
    <header class="bg-blue-600 text-white p-4">
        <div class="container mx-auto flex justify-between items-center">
            <div class="flex items-center space-x-4">
                <img src="{{ asset('asset/logo-unpam-300x291.png') }}" alt="Logo UNPAM" class="h-12 w-auto">
                <h1 class="text-2xl font-bold">Admin Dashboard - Virtual Tour UNPAM</h1>
            </div>
            <div class="flex items-center space-x-4">
                <span>Selamat datang, Admin!</span>
                <a href="{{ route('home') }}" class="bg-blue-500 hover:bg-blue-700 px-4 py-2 rounded" target="_blank">
                    <i class="fas fa-external-link-alt mr-2"></i>Lihat Website
                </a>
                <a href="{{ route('logout') }}" class="bg-red-500 hover:bg-red-700 px-4 py-2 rounded">
                    <i class="fas fa-sign-out-alt mr-2"></i>Logout
                </a>
            </div>
        </div>
    </header>

    <div class="container mx-auto p-6">
        @if(session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                {{ session('error') }}
            </div>
        @endif

        @if($errors->any())
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                @foreach($errors->all() as $error)
                    <p>{{ $error }}</p>
                @endforeach
            </div>
        @endif

        <!-- Tabs -->
        <div class="bg-white rounded-lg shadow-lg">
            <div class="border-b">
                <nav class="flex space-x-8 overflow-x-auto">
                    <button @click="activeTab = 'dashboard'" :class="activeTab === 'dashboard' ? 'text-blue-600 border-b-2 border-blue-600 font-medium' : 'text-gray-500 hover:text-gray-700'" class="py-4 px-6 whitespace-nowrap">
                        <i class="fas fa-tachometer-alt mr-2"></i>Dashboard
                    </button>
                    <button @click="activeTab = 'content'" :class="activeTab === 'content' ? 'text-blue-600 border-b-2 border-blue-600 font-medium' : 'text-gray-500 hover:text-gray-700'" class="py-4 px-6 whitespace-nowrap">
                        <i class="fas fa-edit mr-2"></i>Kelola Konten
                    </button>
                    <button @click="activeTab = 'facilities'" :class="activeTab === 'facilities' ? 'text-blue-600 border-b-2 border-blue-600 font-medium' : 'text-gray-500 hover:text-gray-700'" class="py-4 px-6 whitespace-nowrap">
                        <i class="fas fa-building mr-2"></i>Kelola Fasilitas
                    </button>
                    <button @click="activeTab = 'users'" :class="activeTab === 'users' ? 'text-blue-600 border-b-2 border-blue-600 font-medium' : 'text-gray-500 hover:text-gray-700'" class="py-4 px-6 whitespace-nowrap">
                        <i class="fas fa-users mr-2"></i>Kelola User
                    </button>
                    <button @click="activeTab = 'virtual-tour'" :class="activeTab === 'virtual-tour' ? 'text-blue-600 border-b-2 border-blue-600 font-medium' : 'text-gray-500 hover:text-gray-700'" class="py-4 px-6 whitespace-nowrap">
                        <i class="fas fa-vr-cardboard mr-2"></i>Virtual Tour
                    </button>
                    <button @click="activeTab = 'kritik-saran'" :class="activeTab === 'kritik-saran' ? 'text-blue-600 border-b-2 border-blue-600 font-medium' : 'text-gray-500 hover:text-gray-700'" class="py-4 px-6 whitespace-nowrap">
                        <i class="fas fa-comments mr-2"></i>Kritik & Saran
                    </button>
                    <button @click="activeTab = 'upload'" :class="activeTab === 'upload' ? 'text-blue-600 border-b-2 border-blue-600 font-medium' : 'text-gray-500 hover:text-gray-700'" class="py-4 px-6 whitespace-nowrap">
                        <i class="fas fa-upload mr-2"></i>Upload Gambar
                    </button>
                </nav>
            </div>

            <!-- ==================== Dashboard Tab ==================== -->
            <div x-show="activeTab === 'dashboard'" class="p-6">
                <h2 class="text-xl font-bold mb-6">Dashboard - Statistik Virtual Tour UNPAM</h2>

                <!-- Statistics Cards -->
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
                    <div class="bg-blue-500 text-white p-6 rounded-lg shadow-lg">
                        <div class="flex items-center justify-between">
                            <div>
                                <h3 class="text-sm font-medium opacity-90">Total Pengunjung</h3>
                                <p class="text-2xl font-bold">{{ number_format($stats['total_visitors']) }}</p>
                                <p class="text-xs opacity-75 mt-1">Unique visitors (non-admin)</p>
                            </div>
                            <div class="text-3xl opacity-75"><i class="fas fa-users"></i></div>
                        </div>
                    </div>

                    <div class="bg-green-500 text-white p-6 rounded-lg shadow-lg">
                        <div class="flex items-center justify-between">
                            <div>
                                <h3 class="text-sm font-medium opacity-90">Akses Virtual Tour</h3>
                                <p class="text-2xl font-bold">{{ number_format($stats['vr_visitors']) }}</p>
                                <p class="text-xs opacity-75 mt-1">Pengunjung VR</p>
                            </div>
                            <div class="text-3xl opacity-75"><i class="fas fa-vr-cardboard"></i></div>
                        </div>
                    </div>

                    <div class="bg-purple-500 text-white p-6 rounded-lg shadow-lg">
                        <div class="flex items-center justify-between">
                            <div>
                                <h3 class="text-sm font-medium opacity-90">Kritik & Saran</h3>
                                <p class="text-2xl font-bold">{{ number_format($stats['kritik_count']) }}</p>
                                <p class="text-xs opacity-75 mt-1">Total feedback</p>
                            </div>
                            <div class="text-3xl opacity-75"><i class="fas fa-comments"></i></div>
                        </div>
                    </div>

                    <div class="bg-orange-500 text-white p-6 rounded-lg shadow-lg">
                        <div class="flex items-center justify-between">
                            <div>
                                <h3 class="text-sm font-medium opacity-90">Hari Ini</h3>
                                <p class="text-2xl font-bold">{{ number_format($stats['today_visitors']) }}</p>
                                <p class="text-xs opacity-75 mt-1">Pengunjung hari ini</p>
                            </div>
                            <div class="text-3xl opacity-75"><i class="fas fa-calendar-day"></i></div>
                        </div>
                    </div>
                </div>

                <!-- Conversion Rate & Quick Actions -->
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
                    <div class="bg-white p-6 rounded-lg shadow-lg border">
                        <h3 class="text-lg font-semibold mb-4 text-gray-800">
                            <i class="fas fa-chart-pie mr-2 text-blue-600"></i>Tingkat Konversi
                        </h3>
                        <div class="space-y-4">
                            @php
                                $vrRate = $stats['total_visitors'] > 0 ? ($stats['vr_visitors'] / $stats['total_visitors']) * 100 : 0;
                                $feedbackRate = $stats['vr_visitors'] > 0 ? ($stats['kritik_count'] / $stats['vr_visitors']) * 100 : 0;
                            @endphp
                            <div>
                                <div class="flex justify-between items-center mb-1">
                                    <span class="text-sm text-gray-600">Akses Virtual Tour</span>
                                    <span class="text-sm font-semibold text-green-600">{{ number_format($vrRate, 1) }}%</span>
                                </div>
                                <div class="w-full bg-gray-200 rounded-full h-2">
                                    <div class="bg-green-500 h-2 rounded-full" style="width: {{ min($vrRate, 100) }}%"></div>
                                </div>
                                <p class="text-xs text-gray-500 mt-1">Dari total pengunjung yang mengakses VR</p>
                            </div>
                            <div>
                                <div class="flex justify-between items-center mb-1">
                                    <span class="text-sm text-gray-600">Memberikan Feedback</span>
                                    <span class="text-sm font-semibold text-purple-600">{{ number_format($feedbackRate, 1) }}%</span>
                                </div>
                                <div class="w-full bg-gray-200 rounded-full h-2">
                                    <div class="bg-purple-500 h-2 rounded-full" style="width: {{ min($feedbackRate, 100) }}%"></div>
                                </div>
                                <p class="text-xs text-gray-500 mt-1">Dari pengunjung VR yang memberikan kritik/saran</p>
                            </div>
                        </div>
                    </div>

                    <div class="bg-white p-6 rounded-lg shadow-lg border">
                        <h3 class="text-lg font-semibold mb-4 text-gray-800">
                            <i class="fas fa-bolt mr-2 text-yellow-600"></i>Quick Actions
                        </h3>
                        <div class="grid grid-cols-2 gap-3">
                            <button @click="activeTab = 'kritik-saran'" class="bg-purple-500 hover:bg-purple-600 text-white p-3 rounded-lg text-sm transition-colors">
                                <i class="fas fa-comments mb-1"></i><br>Lihat Feedback
                            </button>
                            <button @click="activeTab = 'virtual-tour'" class="bg-green-500 hover:bg-green-600 text-white p-3 rounded-lg text-sm transition-colors">
                                <i class="fas fa-vr-cardboard mb-1"></i><br>Kelola VR
                            </button>
                            <button @click="activeTab = 'upload'" class="bg-blue-500 hover:bg-blue-600 text-white p-3 rounded-lg text-sm transition-colors">
                                <i class="fas fa-upload mb-1"></i><br>Upload Gambar
                            </button>
                            <button @click="activeTab = 'content'" class="bg-orange-500 hover:bg-orange-600 text-white p-3 rounded-lg text-sm transition-colors">
                                <i class="fas fa-edit mb-1"></i><br>Edit Konten
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Recent Visitors -->
                <div class="bg-white p-6 rounded-lg shadow-lg border">
                    <h3 class="text-lg font-semibold mb-4 text-gray-800">
                        <i class="fas fa-history mr-2 text-gray-600"></i>Aktivitas Pengunjung Terbaru
                    </h3>
                    <div class="overflow-x-auto">
                        <table class="w-full text-sm">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-4 py-2 text-left">Halaman</th>
                                    <th class="px-4 py-2 text-left">Waktu Akses</th>
                                    <th class="px-4 py-2 text-left">Status</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200">
                                @forelse($recentVisitors as $visitor)
                                    @php
                                        $pageName = str_replace(['.php', '/'], '', $visitor->page_visited);
                                        $pageIcon = 'fas fa-file';
                                        $pageColor = 'text-gray-600';
                                        switch($pageName) {
                                            case 'virtual-tour':
                                                $pageIcon = 'fas fa-vr-cardboard'; $pageColor = 'text-green-600'; $pageName = 'Virtual Tour'; break;
                                            case '':
                                            case 'index':
                                                $pageIcon = 'fas fa-home'; $pageColor = 'text-blue-600'; $pageName = 'Homepage'; break;
                                            case 'fasilitas':
                                                $pageIcon = 'fas fa-building'; $pageColor = 'text-purple-600'; $pageName = 'Fasilitas'; break;
                                            case 'tentang':
                                                $pageIcon = 'fas fa-info-circle'; $pageColor = 'text-orange-600'; $pageName = 'Tentang'; break;
                                        }
                                    @endphp
                                    <tr class="hover:bg-gray-50">
                                        <td class="px-4 py-3">
                                            <span class="{{ $pageColor }}">
                                                <i class="{{ $pageIcon }} mr-1"></i>{{ $pageName }}
                                            </span>
                                        </td>
                                        <td class="px-4 py-3 text-gray-600">
                                            {{ \Carbon\Carbon::parse($visitor->visit_time)->format('d/m/Y H:i') }}
                                        </td>
                                        <td class="px-4 py-3">
                                            <span class="bg-green-100 text-green-800 px-2 py-1 rounded-full text-xs">
                                                <i class="fas fa-check-circle mr-1"></i>Visitor
                                            </span>
                                        </td>
                                    </tr>
                                @empty
                                    <tr><td colspan="3" class="px-4 py-8 text-center text-gray-500">Belum ada data pengunjung</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- ==================== Content Management Tab ==================== -->
            <div x-show="activeTab === 'content'" class="p-6">
                <h2 class="text-xl font-bold mb-4">Kelola Konten Website</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    @foreach($contents as $content)
                        <div class="bg-gray-50 p-4 rounded-lg">
                            <form action="{{ route('admin.content.update') }}" method="POST" class="space-y-3">
                                @csrf
                                <input type="hidden" name="content_id" value="{{ $content->id }}">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">
                                        {{ ucfirst($content->section) }} - {{ ucfirst(str_replace('_', ' ', $content->content_key)) }}
                                    </label>
                                    @if($content->content_type == 'text' && strlen($content->content_value) > 100)
                                        <textarea name="content_value" rows="3" class="w-full border border-gray-300 rounded-md px-3 py-2">{{ $content->content_value }}</textarea>
                                    @else
                                        <input type="text" name="content_value" value="{{ $content->content_value }}" class="w-full border border-gray-300 rounded-md px-3 py-2">
                                    @endif
                                    <small class="text-gray-500">Tipe: {{ $content->content_type }}</small>
                                </div>
                                <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white px-4 py-2 rounded">
                                    <i class="fas fa-save mr-1"></i>Update
                                </button>
                            </form>
                        </div>
                    @endforeach
                </div>
            </div>

            <!-- ==================== Facilities Management Tab ==================== -->
            <div x-show="activeTab === 'facilities'" class="p-6">
                <h2 class="text-xl font-bold mb-4">Kelola Fasilitas</h2>

                <!-- Add New Facility -->
                <div class="bg-green-50 p-4 rounded-lg mb-6">
                    <h3 class="text-lg font-semibold mb-3">Tambah Fasilitas Baru</h3>
                    <form action="{{ route('admin.facility.add') }}" method="POST" class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        @csrf
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Nama Fasilitas</label>
                            <input type="text" name="name" required class="w-full border border-gray-300 rounded-md px-3 py-2" placeholder="Contoh: Laboratorium Komputer">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Deskripsi</label>
                            <textarea name="description" required class="w-full border border-gray-300 rounded-md px-3 py-2" placeholder="Deskripsi fasilitas..."></textarea>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">URL Gambar</label>
                            <input type="text" name="image" required class="w-full border border-gray-300 rounded-md px-3 py-2" placeholder="asset/gambar.jpg atau http://...">
                            <button type="submit" class="mt-2 bg-green-500 hover:bg-green-700 text-white px-4 py-2 rounded w-full">
                                <i class="fas fa-plus mr-1"></i>Tambah
                            </button>
                        </div>
                    </form>
                </div>

                <!-- Existing Facilities -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    @foreach($facilities as $facility)
                        <div class="bg-gray-50 p-4 rounded-lg">
                            <div class="mb-3">
                                <img src="{{ asset($facility->image ?? 'asset/default.jpg') }}" alt="{{ $facility->name }}" class="w-full h-32 object-cover rounded">
                            </div>
                            <form action="{{ route('admin.facility.update') }}" method="POST" class="space-y-3">
                                @csrf
                                <input type="hidden" name="facility_id" value="{{ $facility->id }}">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Nama Fasilitas</label>
                                    <input type="text" name="name" value="{{ $facility->name }}" class="w-full border border-gray-300 rounded-md px-3 py-2">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Deskripsi</label>
                                    <textarea name="description" rows="2" class="w-full border border-gray-300 rounded-md px-3 py-2">{{ $facility->description }}</textarea>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">URL Gambar</label>
                                    <input type="text" name="image" value="{{ $facility->image }}" class="w-full border border-gray-300 rounded-md px-3 py-2">
                                </div>
                                <div class="flex space-x-2">
                                    <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white px-4 py-2 rounded flex-1">
                                        <i class="fas fa-save mr-1"></i>Update
                                    </button>
                                </div>
                            </form>
                            <form action="{{ route('admin.facility.delete') }}" method="POST" class="mt-2" onsubmit="return confirm('Yakin ingin menghapus fasilitas ini?')">
                                @csrf
                                <input type="hidden" name="facility_id" value="{{ $facility->id }}">
                                <button type="submit" class="bg-red-500 hover:bg-red-700 text-white px-4 py-2 rounded w-full">
                                    <i class="fas fa-trash mr-1"></i>Hapus
                                </button>
                            </form>
                        </div>
                    @endforeach
                </div>
            </div>

            <!-- ==================== Users Management Tab ==================== -->
            <div x-show="activeTab === 'users'" class="p-6">
                <h2 class="text-xl font-bold mb-4">Kelola User Admin</h2>

                <!-- Add New User -->
                <div class="bg-green-50 p-4 rounded-lg mb-6">
                    <h3 class="text-lg font-semibold mb-3">Tambah User Admin Baru</h3>
                    <form action="{{ route('admin.user.add') }}" method="POST" class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        @csrf
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Username</label>
                            <input type="text" name="username" required class="w-full border border-gray-300 rounded-md px-3 py-2" placeholder="Masukkan username">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Password</label>
                            <input type="password" name="password" required class="w-full border border-gray-300 rounded-md px-3 py-2" placeholder="Masukkan password">
                        </div>
                        <div class="flex items-end">
                            <button type="submit" class="w-full bg-green-500 hover:bg-green-700 text-white px-4 py-2 rounded">
                                <i class="fas fa-user-plus mr-1"></i>Tambah User
                            </button>
                        </div>
                    </form>
                </div>

                <!-- Existing Users -->
                <div class="bg-white rounded-lg">
                    <div class="px-4 py-3 border-b border-gray-200">
                        <h3 class="text-lg font-semibold text-gray-800">Daftar User Admin</h3>
                    </div>
                    <div class="divide-y divide-gray-200">
                        @foreach($users as $user)
                            <div class="p-4">
                                <form action="{{ route('admin.user.update') }}" method="POST" class="flex flex-col md:flex-row md:items-end gap-4">
                                    @csrf
                                    <input type="hidden" name="user_id" value="{{ $user->id }}">
                                    <div class="flex-1">
                                        <label class="block text-sm font-medium text-gray-700 mb-1">Username</label>
                                        <input type="text" name="username" value="{{ $user->username }}" class="w-full border border-gray-300 rounded-md px-3 py-2">
                                    </div>
                                    <div class="flex-1">
                                        <label class="block text-sm font-medium text-gray-700 mb-1">Password Baru (kosongkan jika tidak diubah)</label>
                                        <input type="password" name="password" class="w-full border border-gray-300 rounded-md px-3 py-2" placeholder="Password baru (opsional)">
                                    </div>
                                    <div class="flex space-x-2">
                                        <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white px-4 py-2 rounded">
                                            <i class="fas fa-save mr-1"></i>Update
                                        </button>
                                    </div>
                                </form>
                                <div class="flex items-center mt-2">
                                    <form action="{{ route('admin.user.delete') }}" method="POST" class="inline" onsubmit="return confirm('Yakin ingin menghapus user ini?')">
                                        @csrf
                                        <input type="hidden" name="user_id" value="{{ $user->id }}">
                                        <button type="submit" class="bg-red-500 hover:bg-red-700 text-white px-3 py-1 rounded text-sm">
                                            <i class="fas fa-trash mr-1"></i>Hapus
                                        </button>
                                    </form>
                                    <div class="ml-4 text-sm text-gray-500">
                                        @if($user->created_at)
                                            <i class="fas fa-calendar mr-1"></i>Dibuat: {{ \Carbon\Carbon::parse($user->created_at)->format('d/m/Y H:i') }}
                                        @else
                                            <i class="fas fa-user mr-1"></i>ID: {{ $user->id }}
                                        @endif
                                        @if($user->id == session('admin_id'))
                                            <span class="ml-2 bg-blue-100 text-blue-800 px-2 py-1 rounded-full text-xs">
                                                <i class="fas fa-user mr-1"></i>Anda
                                            </span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>

            <!-- ==================== Virtual Tour Management Tab ==================== -->
            <div x-show="activeTab === 'virtual-tour'" class="p-6">
                <h2 class="text-xl font-bold mb-4">Kelola Virtual Tour</h2>

                <!-- VR Scenes Management -->
                <div class="bg-gray-50 p-4 rounded-lg mb-6">
                    <h3 class="text-lg font-semibold mb-3">Kelola Scene VR</h3>

                    <!-- Add Scene Form -->
                    <div class="bg-white p-4 rounded-lg mb-4">
                        <h4 class="font-medium mb-3">Tambah Scene Baru</h4>
                        <form action="{{ route('admin.scene.add') }}" method="POST" class="space-y-4">
                            @csrf
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Nama Scene</label>
                                    <input type="text" name="name" required class="w-full border border-gray-300 rounded-md px-3 py-2">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Key Scene</label>
                                    <input type="text" name="scene_key" required placeholder="contoh: entrance" class="w-full border border-gray-300 rounded-md px-3 py-2">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">URL Gambar 360°</label>
                                    <input type="url" name="image_360" required placeholder="https://..." class="w-full border border-gray-300 rounded-md px-3 py-2">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Icon (Font Awesome)</label>
                                    <input type="text" name="icon" required placeholder="fas fa-door-open" class="w-full border border-gray-300 rounded-md px-3 py-2">
                                </div>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Deskripsi</label>
                                <textarea name="description" required rows="2" class="w-full border border-gray-300 rounded-md px-3 py-2"></textarea>
                            </div>
                            <button type="submit" class="bg-green-500 hover:bg-green-700 text-white px-4 py-2 rounded">
                                <i class="fas fa-plus mr-1"></i>Tambah Scene
                            </button>
                        </form>
                    </div>

                    <!-- Existing Scenes -->
                    <div class="space-y-4">
                        @foreach($vrScenes as $scene)
                            <div class="bg-white p-4 rounded-lg border">
                                <div class="flex justify-between items-start">
                                    <div class="flex-1">
                                        <h4 class="font-medium text-lg">{{ $scene->name }}</h4>
                                        <p class="text-gray-600 mb-2">{{ $scene->description }}</p>
                                        <div class="text-sm text-gray-500">
                                            <span class="mr-4">Key: {{ $scene->scene_key }}</span>
                                            <span class="mr-4">Icon: <i class="{{ $scene->icon }}"></i></span>
                                            <span>ID: {{ $scene->id }}</span>
                                        </div>
                                    </div>
                                    <div class="flex space-x-2">
                                        <button @click="editSceneData = { id: {{ $scene->id }}, name: '{{ addslashes($scene->name) }}', description: '{{ addslashes($scene->description) }}', scene_key: '{{ addslashes($scene->scene_key) }}', image_360: '{{ addslashes($scene->image_360) }}', icon: '{{ addslashes($scene->icon) }}' }; editSceneModal = true" class="bg-blue-500 hover:bg-blue-700 text-white px-3 py-1 rounded text-sm">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                        <form action="{{ route('admin.scene.delete') }}" method="POST" class="inline" onsubmit="return confirm('Yakin ingin menghapus scene ini?')">
                                            @csrf
                                            <input type="hidden" name="scene_id" value="{{ $scene->id }}">
                                            <button type="submit" class="bg-red-500 hover:bg-red-700 text-white px-3 py-1 rounded text-sm">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>

                <!-- VR Hotspots Management -->
                <div class="bg-gray-50 p-4 rounded-lg">
                    <h3 class="text-lg font-semibold mb-3">Kelola Hotspots</h3>

                    <!-- Add Hotspot Form -->
                    <div class="bg-white p-4 rounded-lg mb-4">
                        <h4 class="font-medium mb-3">Tambah Hotspot Baru</h4>
                        <form action="{{ route('admin.hotspot.add') }}" method="POST" class="space-y-4">
                            @csrf
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Scene</label>
                                    <select name="scene_id" required class="w-full border border-gray-300 rounded-md px-3 py-2">
                                        <option value="">Pilih Scene</option>
                                        @foreach($vrScenes as $scene)
                                            <option value="{{ $scene->id }}">{{ $scene->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Nama Hotspot</label>
                                    <input type="text" name="name" required class="w-full border border-gray-300 rounded-md px-3 py-2">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Target Scene</label>
                                    <select name="target_scene" required class="w-full border border-gray-300 rounded-md px-3 py-2">
                                        <option value="">Pilih Target Scene</option>
                                        @foreach($vrScenes as $scene)
                                            <option value="{{ $scene->scene_key }}">{{ $scene->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="grid grid-cols-3 gap-2">
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">Posisi X</label>
                                        <input type="number" step="0.1" name="position_x" required class="w-full border border-gray-300 rounded-md px-3 py-2">
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">Posisi Y</label>
                                        <input type="number" step="0.1" name="position_y" required class="w-full border border-gray-300 rounded-md px-3 py-2">
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">Posisi Z</label>
                                        <input type="number" step="0.1" name="position_z" required class="w-full border border-gray-300 rounded-md px-3 py-2">
                                    </div>
                                </div>
                            </div>
                            <button type="submit" class="bg-green-500 hover:bg-green-700 text-white px-4 py-2 rounded">
                                <i class="fas fa-plus mr-1"></i>Tambah Hotspot
                            </button>
                        </form>
                    </div>

                    <!-- Existing Hotspots -->
                    <div class="space-y-4">
                        @foreach($vrHotspots as $hotspot)
                            <div class="bg-white p-4 rounded-lg border">
                                <div class="flex justify-between items-start">
                                    <div class="flex-1">
                                        <h4 class="font-medium">{{ $hotspot->name }}</h4>
                                        <p class="text-gray-600 text-sm">Scene: {{ $hotspot->scene->name ?? '-' }}</p>
                                        <p class="text-gray-600 text-sm">Target: {{ $hotspot->target_scene }}</p>
                                        <p class="text-gray-500 text-sm">
                                            Posisi: ({{ $hotspot->position_x }}, {{ $hotspot->position_y }}, {{ $hotspot->position_z }})
                                        </p>
                                    </div>
                                    <div class="flex space-x-2">
                                        <form action="{{ route('admin.hotspot.delete') }}" method="POST" class="inline" onsubmit="return confirm('Yakin ingin menghapus hotspot ini?')">
                                            @csrf
                                            <input type="hidden" name="hotspot_id" value="{{ $hotspot->id }}">
                                            <button type="submit" class="bg-red-500 hover:bg-red-700 text-white px-3 py-1 rounded text-sm">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>

            <!-- ==================== Kritik & Saran Tab ==================== -->
            <div x-show="activeTab === 'kritik-saran'" class="p-6">
                <h2 class="text-xl font-bold mb-4">Kelola Kritik & Saran</h2>

                <div class="bg-blue-50 p-4 rounded-lg mb-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <h3 class="text-lg font-semibold text-blue-800">Daftar Kritik & Saran dari Pengunjung</h3>
                            <p class="text-blue-600 text-sm">Data kritik dan saran yang dikirim melalui halaman virtual tour</p>
                        </div>
                        <div class="text-blue-800"><i class="fas fa-comments text-2xl"></i></div>
                    </div>
                </div>

                <div class="space-y-4">
                    @forelse($kritikSaran as $kritik)
                        <div class="bg-white p-6 rounded-lg border hover:shadow-md transition-shadow">
                            <div class="flex justify-between items-start">
                                <div class="flex-1">
                                    <div class="flex items-center mb-3">
                                        <div class="bg-blue-100 p-2 rounded-full mr-3">
                                            <i class="fas fa-user text-blue-600"></i>
                                        </div>
                                        <div>
                                            <h4 class="font-semibold text-lg text-gray-800">{{ $kritik->nama }}</h4>
                                            <p class="text-gray-600 text-sm">
                                                <i class="fas fa-envelope mr-1"></i>{{ $kritik->kontak }}
                                            </p>
                                        </div>
                                    </div>
                                    <div class="bg-gray-50 p-4 rounded-lg mb-3">
                                        <p class="text-gray-800 leading-relaxed">{!! nl2br(e($kritik->pesan)) !!}</p>
                                    </div>
                                    <div class="flex items-center text-sm text-gray-500">
                                        <i class="fas fa-calendar mr-1"></i>
                                        <span>Dikirim pada: {{ \Carbon\Carbon::parse($kritik->created_at)->format('d F Y, H:i') }} WIB</span>
                                        <span class="mx-2">&bull;</span>
                                        <i class="fas fa-tag mr-1"></i>
                                        <span>ID: #{{ $kritik->id }}</span>
                                    </div>
                                </div>
                                <div class="ml-4 flex flex-col space-y-2">
                                    <form action="{{ route('admin.kritik.delete') }}" method="POST" class="inline" onsubmit="return confirm('Yakin ingin menghapus kritik & saran ini?')">
                                        @csrf
                                        <input type="hidden" name="kritik_id" value="{{ $kritik->id }}">
                                        <button type="submit" class="bg-red-500 hover:bg-red-700 text-white px-4 py-2 rounded flex items-center transition-colors">
                                            <i class="fas fa-trash mr-2"></i>Hapus
                                        </button>
                                    </form>
                                    <button onclick="navigator.clipboard.writeText('{{ addslashes($kritik->pesan) }}').then(() => alert('Pesan berhasil disalin!'))" class="bg-gray-500 hover:bg-gray-700 text-white px-4 py-2 rounded flex items-center transition-colors">
                                        <i class="fas fa-copy mr-2"></i>Copy
                                    </button>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-6 text-center">
                            <div class="text-yellow-600 mb-2"><i class="fas fa-inbox text-3xl"></i></div>
                            <h3 class="text-lg font-semibold text-yellow-800 mb-2">Belum Ada Kritik & Saran</h3>
                            <p class="text-yellow-700">Belum ada kritik dan saran yang dikirim dari pengunjung virtual tour.</p>
                        </div>
                    @endforelse
                </div>

                @if($kritikSaran->count() > 0)
                    <div class="mt-6 bg-gray-50 p-4 rounded-lg">
                        <div class="flex items-center justify-between text-sm text-gray-600">
                            <span><i class="fas fa-info-circle mr-1"></i>Total: {{ $kritikSaran->count() }} kritik & saran</span>
                            <span><i class="fas fa-clock mr-1"></i>Data diurutkan berdasarkan tanggal terbaru</span>
                        </div>
                    </div>
                @endif
            </div>

            <!-- ==================== Upload Tab ==================== -->
            <div x-show="activeTab === 'upload'" class="p-6">
                <h2 class="text-xl font-bold mb-4">Upload dan Kelola Gambar</h2>

                <!-- Upload Form -->
                <div class="bg-blue-50 p-4 rounded-lg mb-6">
                    <h3 class="text-lg font-semibold mb-3">Upload Gambar Baru</h3>
                    <form action="{{ route('admin.upload') }}" method="POST" enctype="multipart/form-data" class="space-y-4">
                        @csrf
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Pilih Gambar</label>
                            <input type="file" name="image" accept="image/*" required class="w-full border border-gray-300 rounded-md px-3 py-2">
                            <p class="text-xs text-gray-500 mt-1">Format: JPG, JPEG, PNG, GIF, WEBP. Maksimal 5MB.</p>
                        </div>
                        <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white px-4 py-2 rounded">
                            <i class="fas fa-upload mr-1"></i>Upload Gambar
                        </button>
                    </form>
                </div>

                <!-- Image Gallery -->
                <div class="bg-yellow-50 p-4 rounded-lg">
                    <h3 class="text-lg font-semibold mb-3">Gambar yang Tersedia</h3>
                    <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-6 gap-4">
                        @foreach($images as $image)
                            <div class="bg-white p-2 rounded border hover:shadow-md transition-shadow" x-data="{ preview: false }">
                                <img src="{{ asset($image) }}" alt="{{ basename($image) }}" class="w-full h-20 object-cover rounded mb-2 cursor-pointer" @click="preview = true">
                                <p class="text-xs text-center mb-2"><code>{{ basename($image) }}</code></p>
                                <div class="flex space-x-1">
                                    <button @click="navigator.clipboard.writeText('{{ $image }}').then(() => alert('URL berhasil disalin: {{ $image }}'))" class="text-xs bg-green-500 hover:bg-green-600 text-white px-2 py-1 rounded flex-1" title="Copy URL">Copy</button>
                                    <form action="{{ route('admin.image.delete') }}" method="POST" class="flex-1">
                                        @csrf
                                        <input type="hidden" name="image_path" value="{{ $image }}">
                                        <button type="submit" class="text-xs bg-red-500 hover:bg-red-600 text-white px-2 py-1 rounded w-full" onclick="return confirm('Hapus gambar ini?')" title="Hapus">Del</button>
                                    </form>
                                </div>

                                <!-- Image Preview Modal -->
                                <div x-show="preview" x-transition class="fixed inset-0 bg-black bg-opacity-75 z-50 flex items-center justify-center" @click.self="preview = false" @keydown.escape.window="preview = false">
                                    <div class="max-w-4xl p-4">
                                        <img src="{{ asset($image) }}" alt="{{ basename($image) }}" class="max-w-full max-h-screen object-contain">
                                        <button @click="preview = false" class="absolute top-4 right-4 text-white text-2xl hover:text-gray-300">&times;</button>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Edit Scene Modal (Alpine.js) -->
    <div x-show="editSceneModal" x-transition class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50" @keydown.escape.window="editSceneModal = false">
        <div class="bg-white p-6 rounded-lg max-w-md w-full mx-4" @click.outside="editSceneModal = false">
            <h3 class="text-lg font-bold mb-4">Edit Scene</h3>
            <form :action="'{{ route('admin.scene.update') }}'" method="POST" class="space-y-4">
                @csrf
                <input type="hidden" name="scene_id" :value="editSceneData.id">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Nama Scene</label>
                    <input type="text" name="name" x-model="editSceneData.name" required class="w-full border border-gray-300 rounded-md px-3 py-2">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Key Scene</label>
                    <input type="text" name="scene_key" x-model="editSceneData.scene_key" required class="w-full border border-gray-300 rounded-md px-3 py-2">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">URL Gambar 360°</label>
                    <input type="url" name="image_360" x-model="editSceneData.image_360" required class="w-full border border-gray-300 rounded-md px-3 py-2">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Icon (Font Awesome)</label>
                    <input type="text" name="icon" x-model="editSceneData.icon" required class="w-full border border-gray-300 rounded-md px-3 py-2">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Deskripsi</label>
                    <textarea name="description" x-model="editSceneData.description" required rows="2" class="w-full border border-gray-300 rounded-md px-3 py-2"></textarea>
                </div>
                <div class="flex space-x-2">
                    <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white px-4 py-2 rounded">
                        <i class="fas fa-save mr-1"></i>Update
                    </button>
                    <button type="button" @click="editSceneModal = false" class="bg-gray-500 hover:bg-gray-700 text-white px-4 py-2 rounded">
                        Batal
                    </button>
                </div>
            </form>
        </div>
    </div>
</body>
</html>
