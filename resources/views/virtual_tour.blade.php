@extends('layouts.app')

@section('content')
    @push('styles')
    <script src="https://aframe.io/releases/1.4.0/aframe.min.js"></script>
    <style>
        /* 3DVista Tour Styles */
        .tour-card {
            position: relative;
            overflow: hidden;
            border-radius: 1rem;
            cursor: pointer;
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
            background: linear-gradient(135deg, #1e293b 0%, #0f172a 100%);
            border: 2px solid transparent;
        }
        .tour-card:hover {
            transform: translateY(-6px);
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.3);
            border-color: rgba(59, 130, 246, 0.5);
        }
        .tour-card.active {
            border-color: #3b82f6;
            box-shadow: 0 0 0 4px rgba(59, 130, 246, 0.2), 0 20px 40px rgba(0, 0, 0, 0.3);
        }
        .tour-card .tour-icon {
            font-size: 2.5rem;
            color: #60a5fa;
            transition: transform 0.3s ease, color 0.3s ease;
        }
        .tour-card:hover .tour-icon,
        .tour-card.active .tour-icon {
            transform: scale(1.15);
            color: #93c5fd;
        }
        .tour-card .tour-label {
            font-weight: 600;
            color: #e2e8f0;
            font-size: 1.05rem;
            transition: color 0.3s ease;
        }
        .tour-card:hover .tour-label,
        .tour-card.active .tour-label {
            color: #ffffff;
        }
        .tour-card::after {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255,255,255,0.05), transparent);
            transition: left 0.5s ease;
        }
        .tour-card:hover::after {
            left: 100%;
        }

        /* Iframe container */
        .tour-iframe-container {
            position: relative;
            width: 100%;
            height: 0;
            padding-bottom: 56.25%; /* 16:9 */
            border-radius: 1rem;
            overflow: hidden;
            background: #0f172a;
            box-shadow: 0 25px 50px rgba(0, 0, 0, 0.25);
        }
        .tour-iframe-container iframe {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            border: none;
        }
        .tour-iframe-container.fullscreen {
            position: fixed;
            top: 0;
            left: 0;
            width: 100vw;
            height: 100vh;
            padding-bottom: 0;
            border-radius: 0;
            z-index: 9999;
        }
        .tour-iframe-container.fullscreen iframe {
            width: 100vw;
            height: 100vh;
        }

        /* Placeholder when no tour selected */
        .tour-placeholder {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            color: #64748b;
            background: linear-gradient(135deg, #1e293b 0%, #0f172a 100%);
        }
        .tour-placeholder i {
            font-size: 4rem;
            margin-bottom: 1rem;
            animation: pulse-glow 2s ease-in-out infinite;
        }
        @keyframes pulse-glow {
            0%, 100% { opacity: 0.5; transform: scale(1); }
            50% { opacity: 1; transform: scale(1.05); }
        }

        /* Fullscreen button */
        .btn-fullscreen {
            position: absolute;
            top: 1rem;
            right: 1rem;
            z-index: 10;
            background: rgba(0, 0, 0, 0.6);
            backdrop-filter: blur(8px);
            color: white;
            border: 1px solid rgba(255,255,255,0.15);
            padding: 0.6rem 1rem;
            border-radius: 0.5rem;
            cursor: pointer;
            transition: all 0.3s ease;
            font-size: 0.85rem;
        }
        .btn-fullscreen:hover {
            background: rgba(59, 130, 246, 0.7);
            border-color: rgba(59, 130, 246, 0.5);
        }

        /* Section separator */
        .section-divider {
            position: relative;
            text-align: center;
            margin: 4rem 0;
        }
        .section-divider::before {
            content: '';
            position: absolute;
            top: 50%;
            left: 0;
            right: 0;
            height: 1px;
            background: linear-gradient(90deg, transparent, #cbd5e1, transparent);
        }
        .section-divider span {
            position: relative;
            background: #f8fafc;
            padding: 0 1.5rem;
            color: #64748b;
            font-size: 0.875rem;
            font-weight: 500;
            text-transform: uppercase;
            letter-spacing: 0.1em;
        }

        /* Loading spinner for iframe */
        .tour-loading {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            display: flex;
            align-items: center;
            justify-content: center;
            background: linear-gradient(135deg, #1e293b 0%, #0f172a 100%);
            z-index: 5;
            transition: opacity 0.5s ease;
        }
        .tour-loading.hidden {
            opacity: 0;
            pointer-events: none;
        }
        .spinner {
            width: 48px;
            height: 48px;
            border: 4px solid rgba(96, 165, 250, 0.2);
            border-top-color: #60a5fa;
            border-radius: 50%;
            animation: spin 1s linear infinite;
        }
        @keyframes spin {
            to { transform: rotate(360deg); }
        }
    </style>
    @endpush

    <!-- Hero Section -->
    <section id="home" class="hero-section h-screen flex items-center justify-center text-white">
        <div class="text-center px-4">
            <h1 class="text-4xl md:text-6xl font-bold mb-6">{{ e($content['vr_title']) }}</h1>
            <h2 class="text-3xl md:text-5xl font-bold mb-8">{{ e($content['vr_subtitle']) }}</h2>
            <p class="text-xl mb-10 max-w-3xl mx-auto">{{ e($content['vr_description']) }}</p>
            <button onclick="document.getElementById('tour').scrollIntoView({behavior:'smooth'})" class="mt-8 bg-yellow-500 hover:bg-yellow-600 text-white font-medium py-3 px-8 rounded-full transition duration-300 inline-flex items-center">
                <i class="fas fa-vr-cardboard mr-2"></i> Mulai Tour
            </button>
        </div>
    </section>

    <!-- 3DVista Virtual Tour Section -->
    @if(count($virtualTours) > 0)
    <section id="tour" class="py-20 bg-gradient-to-b from-gray-50 to-white" x-data="virtualTourApp()">
        <div class="container mx-auto px-6">
            <div class="text-center mb-12">
                <h2 class="text-3xl font-bold text-gray-800 mb-3">VIRTUAL TOUR 360°</h2>
                <p class="text-gray-600 max-w-2xl mx-auto">Pilih lokasi di bawah untuk memulai tur virtual interaktif. Gunakan mouse atau sentuh layar untuk melihat ke segala arah.</p>
            </div>

            <!-- Tour Location Selector -->
            <div class="grid grid-cols-2 md:grid-cols-{{ min(count($virtualTours), 4) }} gap-4 mb-8 max-w-3xl mx-auto">
                @foreach($virtualTours as $index => $tour)
                <div
                    class="tour-card p-6 text-center"
                    :class="{ 'active': activeTour === '{{ $tour['slug'] }}' }"
                    @click="loadTour('{{ $tour['slug'] }}', '{{ $tour['url'] }}')"
                    id="tour-card-{{ $tour['slug'] }}">
                    <i class="{{ $tour['icon'] }} tour-icon mb-3 block"></i>
                    <span class="tour-label">{{ $tour['name'] }}</span>
                </div>
                @endforeach
            </div>

            <!-- Tour Viewer -->
            <div class="tour-iframe-container" :class="{ 'fullscreen': isFullscreen }" id="tourViewer">
                <!-- Loading Spinner -->
                <div class="tour-loading" :class="{ 'hidden': !isLoading }" id="tourLoading">
                    <div class="text-center">
                        <div class="spinner mx-auto mb-4"></div>
                        <p class="text-slate-400 text-sm">Memuat Virtual Tour...</p>
                    </div>
                </div>

                <!-- Placeholder -->
                <div class="tour-placeholder" x-show="!currentTourUrl">
                    <i class="fas fa-street-view"></i>
                    <p class="text-lg font-medium">Pilih lokasi untuk memulai</p>
                    <p class="text-sm mt-1">Klik salah satu lokasi di atas</p>
                </div>

                <!-- Iframe -->
                <iframe
                    x-show="currentTourUrl"
                    :src="currentTourUrl"
                    allowfullscreen
                    allow="gyroscope; accelerometer; xr-spatial-tracking"
                    @load="onIframeLoaded()"
                    id="tourIframe">
                </iframe>

                <!-- Fullscreen Toggle -->
                <button
                    class="btn-fullscreen"
                    x-show="currentTourUrl"
                    @click="toggleFullscreen()">
                    <i :class="isFullscreen ? 'fas fa-compress' : 'fas fa-expand'"></i>
                    <span class="ml-1" x-text="isFullscreen ? 'Keluar Fullscreen' : 'Fullscreen'"></span>
                </button>
            </div>

            <!-- Tour Instructions -->
            <div class="mt-8 bg-blue-50 p-6 rounded-lg">
                <h3 class="text-lg font-semibold mb-2"><i class="fas fa-info-circle text-blue-600 mr-2"></i>Cara Menggunakan Virtual Tour</h3>
                <ul class="space-y-2 text-gray-700">
                    <li><i class="fas fa-mouse-pointer mr-2"></i>Klik & seret atau sentuh layar untuk melihat ke segala arah</li>
                    <li><i class="fas fa-hand-pointer mr-2"></i>Klik tombol navigasi untuk berpindah ruangan</li>
                    <li><i class="fas fa-expand mr-2"></i>Gunakan tombol fullscreen untuk pengalaman terbaik</li>
                    <li><i class="fas fa-mobile-alt mr-2"></i>Di mobile, miringkan perangkat untuk melihat sekeliling</li>
                </ul>
            </div>
        </div>
    </section>
    @endif

    <!-- A-Frame VR Section (existing scenes) -->
    @if($scenes->isNotEmpty())
    <section id="aframe-tour" class="py-20 bg-white">
        <div class="container mx-auto px-6">
            @if(count($virtualTours) > 0)
            <div class="section-divider">
                <span><i class="fas fa-cube mr-2"></i>Panorama 360° Lainnya</span>
            </div>
            @else
            <h2 class="text-3xl font-bold text-center mb-16" id="tour">VIRTUAL TOUR 360°</h2>
            @endif

            <!-- A-Frame VR Scene -->
            <div class="mb-8">
                <a-scene id="vrScene" embedded style="height: 600px; width: 100%;" vr-mode-ui="enabled: true" background="color: #212121">
                    <a-assets>
                        @foreach($scenes as $scene)
                        <img id="{{ $scene->scene_key }}" src="{{ str_starts_with($scene->image_360, 'http') ? $scene->image_360 : asset($scene->image_360) }}" />
                        @endforeach
                    </a-assets>

                    <a-sky id="panorama" src="#{{ $scenes->isNotEmpty() ? $scenes->first()->scene_key : 'entrance' }}" rotation="0 -130 0"></a-sky>

                    <a-entity id="hotspots">
                        @php $currentSceneId = $scenes->isNotEmpty() ? $scenes->first()->id : 1; @endphp
                        @if(isset($hotspots[$currentSceneId]))
                            @foreach($hotspots[$currentSceneId] as $hotspot)
                            <a-sphere
                                class="hotspot"
                                position="{{ $hotspot->position_x }} {{ $hotspot->position_y }} {{ $hotspot->position_z }}"
                                radius="0.3"
                                color="#FFD700"
                                opacity="0.8"
                                animation="property: scale; to: 1.2 1.2 1.2; dir: alternate; dur: 1000; loop: true"
                                data-target="{{ $hotspot->target_scene }}"
                                data-name="{{ e($hotspot->name) }}">
                                <a-text value="{{ e($hotspot->name) }}" position="0 0.5 0" align="center" color="#FFFFFF" scale="2 2 2"></a-text>
                            </a-sphere>
                            @endforeach
                        @endif
                    </a-entity>

                    <a-camera look-controls="enabled: true" wasd-controls="enabled: false" cursor="rayOrigin: mouse"></a-camera>
                </a-scene>
            </div>

            <!-- Scene Navigation -->
            <div class="bg-gray-100 p-6 rounded-lg" x-data="{ activeScene: '{{ $scenes->isNotEmpty() ? $scenes->first()->scene_key : '' }}' }">
                <h3 class="text-xl font-semibold mb-4">Pilih Lokasi:</h3>
                <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                    @foreach($scenes as $index => $scene)
                    <button
                        class="scene-btn p-4 bg-white rounded-lg shadow hover:shadow-lg transition-all duration-300"
                        :class="{ 'ring-2 ring-blue-500': activeScene === '{{ $scene->scene_key }}' }"
                        @click="activeScene = '{{ $scene->scene_key }}'; changeScene('{{ $scene->scene_key }}', {{ $scene->id }})"
                        data-scene-id="{{ $scene->id }}">
                        <i class="{{ e($scene->icon) }} text-2xl text-blue-600 mb-2"></i>
                        <div class="font-medium">{{ e($scene->name) }}</div>
                        <div class="text-sm text-gray-600">{{ e($scene->description) }}</div>
                    </button>
                    @endforeach
                </div>
            </div>

            <!-- Instructions -->
            <div class="mt-8 bg-blue-50 p-6 rounded-lg">
                <h3 class="text-lg font-semibold mb-2"><i class="fas fa-info-circle text-blue-600 mr-2"></i>Cara Menggunakan Virtual Tour</h3>
                <ul class="space-y-2 text-gray-700">
                    <li><i class="fas fa-mouse-pointer mr-2"></i>Gunakan mouse untuk melihat ke segala arah</li>
                    <li><i class="fas fa-hand-pointer mr-2"></i>Klik tombol kuning untuk berpindah lokasi</li>
                    <li><i class="fas fa-vr-cardboard mr-2"></i>Klik ikon VR untuk mode Virtual Reality</li>
                    <li><i class="fas fa-expand-arrows-alt mr-2"></i>Klik ikon fullscreen untuk tampilan penuh</li>
                </ul>
            </div>
        </div>
    </section>
    @endif

    <!-- Contact Section -->
    @include('partials.contact')

    @push('scripts')
    <script>
        // 3DVista Tour Alpine.js App
        function virtualTourApp() {
            return {
                activeTour: '',
                currentTourUrl: '',
                isFullscreen: false,
                isLoading: false,

                loadTour(slug, url) {
                    if (this.activeTour === slug) return;
                    this.activeTour = slug;
                    this.isLoading = true;
                    this.currentTourUrl = url;
                },

                onIframeLoaded() {
                    this.isLoading = false;
                },

                toggleFullscreen() {
                    this.isFullscreen = !this.isFullscreen;
                    if (this.isFullscreen) {
                        document.body.style.overflow = 'hidden';
                    } else {
                        document.body.style.overflow = '';
                    }
                },

                init() {
                    // Listen for ESC to exit fullscreen
                    document.addEventListener('keydown', (e) => {
                        if (e.key === 'Escape' && this.isFullscreen) {
                            this.isFullscreen = false;
                            document.body.style.overflow = '';
                        }
                    });

                    // Auto-load first tour
                    @if(count($virtualTours) > 0)
                    this.$nextTick(() => {
                        this.loadTour('{{ $virtualTours[0]['slug'] }}', '{{ $virtualTours[0]['url'] }}');
                    });
                    @endif
                }
            }
        }

        // A-Frame Scene Switcher (existing functionality)
        const scenes = @json($scenes);
        const hotspots = @json($hotspots);
        let currentSceneId = {{ $scenes->isNotEmpty() ? $scenes->first()->id : 1 }};

        function changeScene(sceneKey, sceneId) {
            const panorama = document.getElementById('panorama');
            const hotspotsContainer = document.getElementById('hotspots');

            if (!panorama || !hotspotsContainer) return;

            panorama.setAttribute('src', '#' + sceneKey);
            currentSceneId = sceneId;

            while (hotspotsContainer.firstChild) {
                hotspotsContainer.removeChild(hotspotsContainer.firstChild);
            }

            if (hotspots[sceneId]) {
                hotspots[sceneId].forEach(hotspot => {
                    const sphere = document.createElement('a-sphere');
                    sphere.setAttribute('class', 'hotspot');
                    sphere.setAttribute('position', `${hotspot.position_x} ${hotspot.position_y} ${hotspot.position_z}`);
                    sphere.setAttribute('radius', '0.3');
                    sphere.setAttribute('color', '#FFD700');
                    sphere.setAttribute('opacity', '0.8');
                    sphere.setAttribute('animation', 'property: scale; to: 1.2 1.2 1.2; dir: alternate; dur: 1000; loop: true');

                    const text = document.createElement('a-text');
                    text.setAttribute('value', hotspot.name);
                    text.setAttribute('position', '0 0.5 0');
                    text.setAttribute('align', 'center');
                    text.setAttribute('color', '#FFFFFF');
                    text.setAttribute('scale', '2 2 2');
                    sphere.appendChild(text);

                    sphere.addEventListener('click', function() {
                        const targetSceneData = scenes.find(s => s.scene_key === hotspot.target_scene);
                        if (targetSceneData) {
                            changeScene(hotspot.target_scene, targetSceneData.id);
                            // Update Alpine.js state
                            document.querySelector('[x-data]').__x.$data.activeScene = hotspot.target_scene;
                        }
                    });

                    hotspotsContainer.appendChild(sphere);
                });
            }
        }
    </script>
    @endpush
@endsection
