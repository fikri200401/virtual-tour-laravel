@extends('layouts.app')

@section('content')
    @push('styles')
    <script src="https://aframe.io/releases/1.4.0/aframe.min.js"></script>
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

    <!-- Virtual Tour Section -->
    <section id="tour" class="py-20 bg-white">
        <div class="container mx-auto px-6">
            <h2 class="text-3xl font-bold text-center mb-16">VIRTUAL TOUR 360°</h2>

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

    <!-- Contact Section -->
    @include('partials.contact')

    @push('scripts')
    <script>
        const scenes = @json($scenes);
        const hotspots = @json($hotspots);
        let currentSceneId = {{ $scenes->isNotEmpty() ? $scenes->first()->id : 1 }};

        function changeScene(sceneKey, sceneId) {
            const panorama = document.getElementById('panorama');
            const hotspotsContainer = document.getElementById('hotspots');

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
