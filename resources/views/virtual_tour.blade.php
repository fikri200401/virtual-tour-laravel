@extends('layouts.app')

@section('content')
    @push('styles')
        <style>
            .tour-iframe-container {
                position: relative;
                width: 100%;
                height: 0;
                padding-bottom: 56.25%;
                overflow: hidden;
                border-radius: 1rem;
                background: #0f172a;
                box-shadow: 0 25px 50px rgba(0, 0, 0, .25);
            }

            .tour-iframe-container iframe {
                position: absolute;
                inset: 0;
                width: 100%;
                height: 100%;
                border: 0;
            }

            .tour-iframe-container.fullscreen {
                position: fixed;
                inset: 0;
                z-index: 9999;
                width: 100vw;
                height: 100vh;
                padding-bottom: 0;
                border-radius: 0;
            }

            .btn-fullscreen {
                position: absolute;
                top: 1rem;
                right: 1rem;
                z-index: 10;
                padding: .6rem 1rem;
                border: 1px solid rgba(255, 255, 255, .15);
                border-radius: .5rem;
                color: white;
                background: rgba(0, 0, 0, .6);
                backdrop-filter: blur(8px);
                transition: background .2s ease, border-color .2s ease;
            }

            .btn-fullscreen:hover {
                border-color: rgba(59, 130, 246, .5);
                background: rgba(59, 130, 246, .75);
            }

            .tour-loading {
                position: absolute;
                inset: 0;
                z-index: 5;
                display: flex;
                align-items: center;
                justify-content: center;
                background: #0f172a;
                transition: opacity .3s ease;
            }

            .tour-loading.hidden {
                opacity: 0;
                pointer-events: none;
            }

            .spinner {
                width: 48px;
                height: 48px;
                border: 4px solid rgba(96, 165, 250, .2);
                border-top-color: #60a5fa;
                border-radius: 50%;
                animation: spin 1s linear infinite;
            }

            @keyframes spin {
                to { transform: rotate(360deg); }
            }
        </style>
    @endpush

    <section id="home" class="hero-section flex h-screen items-center justify-center text-white" style="background-image: linear-gradient(rgba(0, 0, 0, .7), rgba(0, 0, 0, .7)), url('{{ $content['vr_background_image_url'] }}');">
        <div class="px-4 text-center">
            <h1 class="mb-6 text-4xl font-bold md:text-6xl">{{ $content['vr_title'] }}</h1>
            <h2 class="mb-8 text-3xl font-bold md:text-5xl">{{ $content['vr_subtitle'] }}</h2>
            <p class="mx-auto mb-10 max-w-3xl text-xl">{{ $content['vr_description'] }}</p>
            <button type="button" onclick="document.getElementById('{{ $virtualTour ? 'tourViewer' : 'tour' }}')?.scrollIntoView({ behavior: 'smooth', block: 'start' })" class="mt-8 inline-flex items-center rounded-full bg-yellow-500 px-8 py-3 font-medium text-white transition duration-300 hover:bg-yellow-600">
                <i class="fas fa-vr-cardboard mr-2"></i>Mulai Tour
            </button>
        </div>
    </section>

    <section
        id="tour"
        class="bg-gradient-to-b from-gray-50 to-white py-16"
        x-data="{
            isFullscreen: false,
            isLoading: true,
            toggleFullscreen() {
                this.isFullscreen = !this.isFullscreen;
                document.body.style.overflow = this.isFullscreen ? 'hidden' : '';
            },
            exitFullscreen() {
                this.isFullscreen = false;
                document.body.style.overflow = '';
            }
        }"
        @keydown.escape.window="if (isFullscreen) exitFullscreen()">
        <div class="container mx-auto px-6">
            <div class="mx-auto mb-8 max-w-3xl text-center">
                <h2 class="mb-3 text-3xl font-bold text-gray-800">{{ $content['vr_section_title'] }}</h2>
                <p class="text-gray-600">{{ $content['vr_section_description'] }}</p>
            </div>

            @if($virtualTour)
                <div id="tourViewer" class="tour-iframe-container mx-auto max-w-6xl scroll-mt-4" :class="{ 'fullscreen': isFullscreen }">
                    <div class="tour-loading" :class="{ 'hidden': !isLoading }">
                        <div class="text-center">
                            <div class="spinner mx-auto mb-4"></div>
                            <p class="text-sm text-slate-400">Memuat Virtual Tour...</p>
                        </div>
                    </div>

                    <iframe
                        src="{{ $virtualTour['url'] }}"
                        title="Virtual Tour Utama"
                        allowfullscreen
                        allow="gyroscope; accelerometer; xr-spatial-tracking"
                        @load="isLoading = false">
                    </iframe>

                    <button type="button" class="btn-fullscreen" @click="toggleFullscreen()">
                        <i :class="isFullscreen ? 'fas fa-compress' : 'fas fa-expand'"></i>
                        <span class="ml-1" x-text="isFullscreen ? 'Keluar Fullscreen' : 'Fullscreen'"></span>
                    </button>
                </div>

                <div class="mx-auto mt-8 max-w-6xl rounded-lg bg-blue-50 p-6">
                    <h3 class="mb-2 text-lg font-semibold"><i class="fas fa-info-circle mr-2 text-blue-600"></i>Cara Menggunakan Virtual Tour</h3>
                    <ul class="space-y-2 text-gray-700">
                        <li><i class="fas fa-mouse-pointer mr-2"></i>Klik dan geser atau sentuh layar untuk melihat ke segala arah</li>
                        <li><i class="fas fa-hand-pointer mr-2"></i>Gunakan tombol navigasi di dalam tur untuk berpindah lokasi</li>
                        <li><i class="fas fa-expand mr-2"></i>Gunakan tombol fullscreen untuk tampilan yang lebih luas</li>
                    </ul>
                </div>
            @else
                <div class="mx-auto max-w-3xl rounded-lg border border-yellow-200 bg-yellow-50 p-6 text-center text-yellow-800">
                    <i class="fas fa-exclamation-circle mr-2"></i>Virtual tour belum tersedia. Admin dapat memasangnya melalui panel Virtual Tour.
                </div>
            @endif
        </div>
    </section>

    @include('partials.contact')
@endsection
