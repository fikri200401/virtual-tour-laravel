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
    <style>[x-cloak] { display: none !important; }</style>
    <script>
        window.adminImagePicker = function (initialValue, options, assetBaseUrl) {
            return {
                open: false,
                value: initialValue || '',
                options: options || [],
                previewFailed: false,

                get previewUrl() {
                    const path = (this.value || '').trim();
                    if (!path) return '';

                    if (/^(https?:)?\/\//i.test(path) || path.startsWith('data:')) {
                        return path;
                    }

                    return assetBaseUrl + '/' + path.replace(/^\/+/, '');
                },

                selectImage(path) {
                    this.value = path;
                    this.previewFailed = false;
                    this.open = false;
                }
            };
        };
    </script>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-100" x-data="{ activeTab: '{{ request('tab', 'dashboard') }}' }">
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
                        <i class="fas fa-upload mr-2"></i>Unggah Gambar
                    </button>
                </nav>
            </div>

            <div x-show="activeTab === 'dashboard'" class="p-6">
                <h2 class="text-xl font-bold mb-6">Dashboard - Statistik Virtual Tour UNPAM</h2>

                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
                    <div class="bg-blue-500 text-white p-6 rounded-lg shadow-lg">
                        <div class="flex items-center justify-between">
                            <div>
                                <h3 class="text-sm font-medium opacity-90">Total Pengunjung</h3>
                                <p class="text-2xl font-bold">{{ number_format($stats['total_visitors']) }}</p>
                                <p class="text-xs opacity-75 mt-1">Pengunjung unik selain admin</p>
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
                                <i class="fas fa-upload mb-1"></i><br>Unggah Gambar
                            </button>
                            <button @click="activeTab = 'content'" class="bg-orange-500 hover:bg-orange-600 text-white p-3 rounded-lg text-sm transition-colors">
                                <i class="fas fa-edit mb-1"></i><br>Edit Konten
                            </button>
                        </div>
                    </div>
                </div>

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

            <div x-show="activeTab === 'content'" class="p-6">
                <div class="mb-6 flex flex-col gap-3 md:flex-row md:items-center md:justify-between">
                    <div>
                        <h2 class="text-2xl font-bold text-gray-900">Kelola Konten Website</h2>
                        <p class="mt-1 text-sm text-gray-600">Kelola teks, gambar, dan URL berdasarkan bagian halaman. Buka bagian lalu simpan seluruh perubahannya sekaligus.</p>
                    </div>
                    <div class="flex gap-2">
                        <button type="button" @click="activeTab = 'upload'" class="rounded-md bg-slate-600 px-4 py-2 text-sm font-medium text-white hover:bg-slate-700">
                            <i class="fas fa-images mr-1"></i>Galeri Gambar
                        </button>
                        <a href="{{ route('home') }}" target="_blank" rel="noopener" class="rounded-md bg-blue-600 px-4 py-2 text-sm font-medium text-white hover:bg-blue-700">
                            <i class="fas fa-external-link-alt mr-1"></i>Preview Website
                        </a>
                    </div>
                </div>

                <div
                    class="space-y-4"
                    x-data="{ openSection: @js($contentGroups->keys()->first()) }">
                    @foreach($contentGroups as $sectionKey => $group)
                        <section class="overflow-visible rounded-xl border border-gray-200 bg-white shadow-sm">
                            <button
                                type="button"
                                class="flex w-full items-center justify-between gap-4 rounded-xl px-5 py-4 text-left hover:bg-gray-50"
                                @click="openSection = openSection === @js($sectionKey) ? null : @js($sectionKey)">
                                <span>
                                    <span class="block text-lg font-semibold text-gray-900">{{ $group['label'] }}</span>
                                    <span class="mt-1 block text-sm font-normal text-gray-500">{{ $group['description'] }}</span>
                                </span>
                                <span class="flex shrink-0 items-center gap-3">
                                    <span class="rounded-full bg-blue-100 px-2.5 py-1 text-xs font-semibold text-blue-700">{{ $group['items']->count() }} kolom</span>
                                    <i class="fas fa-chevron-down text-gray-400 transition-transform" :class="{ 'rotate-180': openSection === @js($sectionKey) }"></i>
                                </span>
                            </button>

                            <div x-show="openSection === @js($sectionKey)" x-transition x-cloak class="border-t border-gray-200">
                                <form action="{{ route('admin.content.section.update') }}" method="POST" class="p-5">
                                        @csrf
                                        <input type="hidden" name="section" value="{{ $sectionKey }}">

                                        <div class="grid grid-cols-1 gap-5 md:grid-cols-2">
                                            @foreach($group['items'] as $content)
                                                @php
                                                    $definition = $contentDefinitions[$content->content_key] ?? null;
                                                    $fieldLabel = $definition['label'] ?? ucfirst(str_replace('_', ' ', $content->content_key));
                                                    $isLongText = strlen($content->content_value) > 90
                                                        || str_contains($content->content_key, 'description')
                                                        || in_array($content->content_key, ['contact_address', 'about_commitment_text'], true);
                                                @endphp
                                                <div class="rounded-lg border border-gray-200 bg-gray-50 p-4">
                                                    @if($content->content_type === 'image')
                                                        <x-admin.image-picker
                                                            name="contents[{{ $content->id }}]"
                                                            :value="$content->content_value"
                                                            :images="$images"
                                                            input-id="content-image-{{ $content->id }}"
                                                            :label="$fieldLabel"
                                                            required />
                                                    @else
                                                        <div class="mb-2 flex items-center justify-between gap-2">
                                                            <label for="content-{{ $content->id }}" class="block text-sm font-semibold text-gray-800">{{ $fieldLabel }}</label>
                                                            <span class="rounded-full px-2 py-0.5 text-xs font-medium {{ $content->content_type === 'url' ? 'bg-purple-100 text-purple-700' : 'bg-gray-200 text-gray-600' }}">
                                                                {{ $content->content_type === 'url' ? 'URL' : 'Teks' }}
                                                            </span>
                                                        </div>

                                                        @if($isLongText)
                                                            <textarea id="content-{{ $content->id }}" name="contents[{{ $content->id }}]" rows="4" class="w-full rounded-md border border-gray-300 px-3 py-2 focus:border-blue-500 focus:ring-blue-500">{{ $content->content_value }}</textarea>
                                                        @else
                                                            <input id="content-{{ $content->id }}" type="{{ $content->content_type === 'url' ? 'url' : 'text' }}" name="contents[{{ $content->id }}]" value="{{ $content->content_value }}" class="w-full rounded-md border border-gray-300 px-3 py-2 focus:border-blue-500 focus:ring-blue-500">
                                                        @endif
                                                        <p class="mt-1 text-xs text-gray-400">Key: {{ $content->content_key }}</p>
                                                    @endif
                                                </div>
                                            @endforeach
                                        </div>

                                        <div class="mt-5 flex justify-end border-t border-gray-200 pt-4">
                                            <button type="submit" class="rounded-md bg-blue-600 px-5 py-2.5 font-medium text-white hover:bg-blue-700">
                                                <i class="fas fa-save mr-1"></i>Simpan Bagian {{ $group['label'] }}
                                            </button>
                                        </div>
                                </form>
                            </div>
                        </section>
                    @endforeach
                </div>
            </div>

            <div x-show="activeTab === 'facilities'" class="p-6">
                <h2 class="text-xl font-bold mb-4">Kelola Fasilitas</h2>

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
                            <x-admin.image-picker
                                name="image"
                                :value="old('image')"
                                :images="$images"
                                input-id="new-facility-image"
                                required />
                            <button type="submit" class="mt-2 bg-green-500 hover:bg-green-700 text-white px-4 py-2 rounded w-full">
                                <i class="fas fa-plus mr-1"></i>Tambah
                            </button>
                        </div>
                    </form>
                </div>

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
                                    <x-admin.image-picker
                                        name="image"
                                        :value="$facility->image"
                                        :images="$images"
                                        input-id="facility-image-{{ $facility->id }}"
                                        required />
                                </div>
                                <div class="flex space-x-2">
                                    <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white px-4 py-2 rounded flex-1">
                                        <i class="fas fa-save mr-1"></i>Perbarui
                                    </button>
                                </div>
                            </form>

                            <div class="mt-4 rounded-lg border border-indigo-200 bg-indigo-50 p-4">
                                <div class="flex flex-wrap items-center justify-between gap-2 mb-3">
                                    <h4 class="font-semibold text-indigo-900">
                                        <i class="fas fa-vr-cardboard mr-1"></i>Virtual Tour Fasilitas
                                    </h4>
                                    @if($facility->virtual_tour_url)
                                        <span class="rounded-full bg-green-100 px-2.5 py-1 text-xs font-semibold text-green-700">
                                            <i class="fas fa-check-circle mr-1"></i>Aktif
                                        </span>
                                    @else
                                        <span class="rounded-full bg-gray-200 px-2.5 py-1 text-xs font-semibold text-gray-600">
                                            Belum tersedia
                                        </span>
                                    @endif
                                </div>

                                <form action="{{ route('admin.facility.tour.upload') }}" method="POST" enctype="multipart/form-data" class="space-y-3">
                                    @csrf
                                    <input type="hidden" name="facility_id" value="{{ $facility->id }}">
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">
                                            {{ $facility->virtual_tour_url ? 'Ganti berkas virtual tour' : 'Unggah berkas virtual tour' }}
                                        </label>
                                        <input
                                            type="file"
                                            name="tour_file"
                                            accept=".zip,.rar,.png,.jpg,.jpeg,.webp"
                                            required
                                            class="w-full rounded-md border border-gray-300 bg-white px-3 py-2 text-sm">
                                        <p class="mt-1 text-xs text-gray-500">Format: ZIP, RAR, PNG, JPG, JPEG, atau WEBP. Maksimal 160 MB.</p>
                                    </div>
                                    <button type="submit" class="w-full rounded bg-indigo-600 px-4 py-2 text-white hover:bg-indigo-700">
                                        <i class="fas fa-upload mr-1"></i>{{ $facility->virtual_tour_url ? 'Ganti Virtual Tour' : 'Unggah Virtual Tour' }}
                                    </button>
                                </form>

                                @if($facility->virtual_tour_url)
                                    <div class="mt-3 grid grid-cols-1 sm:grid-cols-2 gap-2">
                                        <a href="{{ $facility->virtual_tour_url }}" target="_blank" rel="noopener" class="rounded bg-slate-700 px-4 py-2 text-center text-sm text-white hover:bg-slate-800">
                                            <i class="fas fa-eye mr-1"></i>Lihat Tour
                                        </a>
                                        <form action="{{ route('admin.facility.tour.delete') }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus virtual tour fasilitas ini?')">
                                            @csrf
                                            <input type="hidden" name="facility_id" value="{{ $facility->id }}">
                                            <button type="submit" class="w-full rounded bg-orange-500 px-4 py-2 text-sm text-white hover:bg-orange-600">
                                                <i class="fas fa-trash-alt mr-1"></i>Hapus Tour
                                            </button>
                                        </form>
                                    </div>
                                @endif
                            </div>

                            <form action="{{ route('admin.facility.delete') }}" method="POST" class="mt-3" onsubmit="return confirm('Yakin ingin menghapus fasilitas ini beserta virtual tournya?')">
                                @csrf
                                <input type="hidden" name="facility_id" value="{{ $facility->id }}">
                                <button type="submit" class="bg-red-500 hover:bg-red-700 text-white px-4 py-2 rounded w-full">
                                    <i class="fas fa-trash mr-1"></i>Hapus Fasilitas
                                </button>
                            </form>
                        </div>
                    @endforeach
                </div>
            </div>

            <div x-show="activeTab === 'users'" class="p-6">
                <h2 class="text-xl font-bold mb-4">Kelola User Admin</h2>

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
                                            <i class="fas fa-save mr-1"></i>Perbarui
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

            <div x-show="activeTab === 'virtual-tour'" class="p-6">
                <h2 class="text-xl font-bold mb-4">Kelola Virtual Tour</h2>

                <div class="bg-gradient-to-r from-indigo-50 to-blue-50 p-4 rounded-lg mb-6 border border-indigo-200">
                    <h3 class="text-lg font-semibold mb-3 text-indigo-800">
                        <i class="fas fa-street-view mr-2"></i>Virtual Tour Utama
                    </h3>
                    <p class="mb-4 text-sm text-indigo-700">Halaman Virtual Tour hanya menampilkan satu tur. Unggah file baru untuk memasang atau mengganti tur utama.</p>

                    <div class="bg-white p-4 rounded-lg mb-4 border">
                        <h4 class="font-medium mb-3 text-gray-800">
                            <i class="fas fa-upload mr-2 text-indigo-600"></i>{{ $deployedTour ? 'Ganti Virtual Tour' : 'Pasang Virtual Tour' }}
                        </h4>
                        <form action="{{ route('admin.tour.upload') }}" method="POST" enctype="multipart/form-data" class="space-y-4" @if($deployedTour) onsubmit="return confirm('Ganti virtual tour yang sedang terpasang?')" @endif>
                            @csrf
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">File Virtual Tour</label>
                                <input type="file" name="tour_file" accept=".zip,.rar,.png,.jpg,.jpeg,.webp" required class="w-full border border-gray-300 rounded-md px-3 py-2">
                                <p class="text-xs text-gray-500 mt-1">Format: ZIP/RAR hasil export virtual tour, atau gambar panorama PNG/JPG/JPEG/WEBP. Maksimal 160MB.</p>
                                <p class="text-xs text-amber-600 mt-1">Untuk hasil terbaik, gambar panorama menggunakan rasio equirectangular 2:1.</p>
                            </div>
                            <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded transition-colors">
                                <i class="fas fa-cloud-upload-alt mr-1"></i>{{ $deployedTour ? 'Ganti Virtual Tour' : 'Pasang Virtual Tour' }}
                            </button>
                        </form>
                    </div>

                    <h4 class="font-medium mb-2 text-gray-800"><i class="fas fa-info-circle mr-2 text-indigo-600"></i>Status Virtual Tour</h4>
                    @if($deployedTour)
                        <div class="bg-white p-3 rounded-lg border flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
                            <div>
                                <span class="font-medium text-gray-800">{{ $deployedTour['name'] }}</span>
                                <div class="text-xs text-gray-500 mt-0.5">
                                    {{ $deployedTour['file_count'] }} berkas &bull; {{ $deployedTour['size_mb'] }} MB
                                </div>
                            </div>
                            <div class="flex items-center space-x-2">
                                <span class="bg-green-100 text-green-800 px-2 py-1 rounded-full text-xs">
                                    <i class="fas fa-check-circle mr-1"></i>Aktif
                                </span>
                                <a href="{{ $deployedTour['url'] }}" target="_blank" rel="noopener" class="bg-slate-600 hover:bg-slate-700 text-white px-2 py-1 rounded text-xs transition-colors" aria-label="Lihat virtual tour">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <form action="{{ route('admin.tour.delete') }}" method="POST" class="inline" onsubmit="return confirm('Yakin ingin menghapus virtual tour utama? Semua file tour akan dihapus permanen.')">
                                    @csrf
                                    <button type="submit" class="bg-red-500 hover:bg-red-700 text-white px-2 py-1 rounded text-xs transition-colors">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </div>
                    @else
                        <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-3 text-center text-yellow-800 text-sm mb-3">
                            <i class="fas fa-inbox mr-1"></i>Belum ada virtual tour yang terpasang.
                        </div>
                    @endif
                </div>

            </div>

            <div x-show="activeTab === 'kritik-saran'" class="p-6">
                <h2 class="text-xl font-bold mb-4">Kelola Kritik & Saran</h2>

                <div class="bg-indigo-50 border border-indigo-200 p-4 rounded-lg mb-6">
                    <h3 class="text-lg font-semibold text-indigo-800 mb-3">Pengaturan Telegram Notifikasi</h3>
                    <p class="text-sm text-indigo-700 mb-4">Masukkan token bot dan chat ID untuk mengaktifkan notifikasi. Kosongkan salah satunya jika notifikasi tidak digunakan. Token bot tersedia melalui @BotFather, sedangkan chat ID dapat dilihat melalui @userinfobot di Telegram.</p>
                    <form action="{{ route('admin.kritik.telegram.update') }}" method="POST" class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        @csrf
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Bot Token</label>
                            <input type="text" name="telegram_bot_token" value="{{ $telegramSettings['bot_token'] ?? '' }}" class="w-full border border-gray-300 rounded-md px-3 py-2" placeholder="Contoh: 123456789:AA...">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Chat ID</label>
                            <input type="text" name="telegram_chat_id" value="{{ $telegramSettings['chat_id'] ?? '' }}" class="w-full border border-gray-300 rounded-md px-3 py-2" placeholder="Contoh: 1769041604">
                        </div>
                        <div class="md:col-span-2">
                            <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded">
                                <i class="fas fa-save mr-1"></i>Simpan Pengaturan Telegram
                            </button>
                        </div>
                    </form>
                </div>

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
                                    <button data-message="{{ $kritik->pesan }}" onclick="navigator.clipboard.writeText(this.dataset.message).then(() => alert('Pesan berhasil disalin.'))" class="bg-gray-500 hover:bg-gray-700 text-white px-4 py-2 rounded flex items-center transition-colors">
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

            <div x-show="activeTab === 'upload'" class="p-6">
                <h2 class="text-xl font-bold mb-4">Unggah dan Kelola Gambar</h2>

                <div class="bg-blue-50 p-4 rounded-lg mb-6">
                    <h3 class="text-lg font-semibold mb-3">Unggah Gambar Baru</h3>
                    <form action="{{ route('admin.upload') }}" method="POST" enctype="multipart/form-data" class="space-y-4">
                        @csrf
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Pilih Gambar</label>
                            <input type="file" name="image" accept="image/*" required class="w-full border border-gray-300 rounded-md px-3 py-2">
                            <p class="text-xs text-gray-500 mt-1">Format: JPG, JPEG, PNG, GIF, WEBP. Maksimal 5MB.</p>
                        </div>
                        <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white px-4 py-2 rounded">
                            <i class="fas fa-upload mr-1"></i>Unggah Gambar
                        </button>
                    </form>
                </div>

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

</body>
</html>
