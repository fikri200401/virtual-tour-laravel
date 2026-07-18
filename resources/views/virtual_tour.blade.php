@extends('layouts.app')

@section('content')
    @push('styles')
        <style>
            .tour-modal-panel {
                max-height: calc(100vh - 2rem);
                overflow-y: auto;
            }

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

    <div x-data="virtualTourModal()" @keydown.escape.window="handleEscape()">
        <section id="home" class="hero-section flex h-screen items-center justify-center text-white" style="background-image: linear-gradient(rgba(0, 0, 0, .7), rgba(0, 0, 0, .7)), url('{{ $content['vr_background_image_url'] }}');">
            <div class="px-4 text-center">
                <h1 class="mb-6 text-4xl font-bold md:text-6xl">{{ $content['vr_title'] }}</h1>
                <h2 class="mb-8 text-3xl font-bold md:text-5xl">{{ $content['vr_subtitle'] }}</h2>
                <p class="mx-auto mb-10 max-w-3xl text-xl">{{ $content['vr_description'] }}</p>

                @if($virtualTour)
                    <button x-ref="openButton" type="button" @click="openTour(@js($virtualTour['url']))" class="mt-8 inline-flex items-center rounded-full bg-yellow-500 px-8 py-3 font-medium text-white transition duration-300 hover:bg-yellow-600">
                        <i class="fas fa-vr-cardboard mr-2"></i>Mulai Tour
                    </button>
                @else
                    <button type="button" disabled class="mt-8 inline-flex cursor-not-allowed items-center rounded-full bg-gray-500 px-8 py-3 font-medium text-white opacity-75">
                        <i class="fas fa-exclamation-circle mr-2"></i>Tour Belum Tersedia
                    </button>
                @endif
            </div>
        </section>

        @if($virtualTour)
            <div
                x-show="isModalOpen"
                x-transition.opacity
                x-cloak
                class="fixed inset-0 flex items-center justify-center bg-slate-950/80 p-4"
                style="z-index: 100;"
                role="dialog"
                aria-modal="true"
                aria-label="Virtual Tour Utama"
                @click.self="closeTour()">
                <div x-show="isModalOpen" x-transition class="tour-modal-panel w-full max-w-6xl rounded-2xl bg-white shadow-2xl">
                    <div class="flex items-center justify-between gap-4 border-b border-gray-200 px-5 py-4">
                        <div>
                            <p class="text-xs font-semibold uppercase tracking-wider text-blue-600">Virtual Tour Utama</p>
                            <h2 class="text-xl font-bold text-gray-900">{{ $content['vr_section_title'] }}</h2>
                        </div>
                        <button x-ref="closeButton" type="button" class="flex h-10 w-10 items-center justify-center rounded-full bg-gray-100 text-gray-600 hover:bg-gray-200" aria-label="Tutup virtual tour" @click="closeTour()">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>

                    <div class="p-4 sm:p-6">
                        <div class="tour-iframe-container" :class="{ 'fullscreen': isFullscreen }">
                            <div class="tour-loading" :class="{ 'hidden': !isLoading }">
                                <div class="text-center">
                                    <div class="spinner mx-auto mb-4"></div>
                                    <p class="text-sm text-slate-400">Memuat Virtual Tour...</p>
                                </div>
                            </div>

                            <template x-if="currentTourUrl">
                                <iframe
                                    :src="currentTourUrl"
                                    title="Virtual Tour Utama"
                                    allowfullscreen
                                    allow="gyroscope; accelerometer; xr-spatial-tracking"
                                    @load="isLoading = false">
                                </iframe>
                            </template>

                            <button type="button" class="btn-fullscreen" @click="toggleFullscreen()">
                                <i :class="isFullscreen ? 'fas fa-compress' : 'fas fa-expand'"></i>
                                <span class="ml-1" x-text="isFullscreen ? 'Keluar Fullscreen' : 'Fullscreen'"></span>
                            </button>
                        </div>

                        <p class="mt-4 text-sm text-gray-500">
                            <i class="fas fa-mouse-pointer mr-2 text-blue-600"></i>{{ $content['vr_section_description'] }}
                        </p>
                    </div>
                </div>
            </div>
        @endif
    </div>

    @include('partials.contact')

    @push('scripts')
        <script>
            function virtualTourModal() {
                return {
                    currentTourUrl: '',
                    isModalOpen: false,
                    isFullscreen: false,
                    isLoading: false,

                    openTour(url) {
                        this.currentTourUrl = url;
                        this.isLoading = true;
                        this.isModalOpen = true;
                        document.body.style.overflow = 'hidden';
                        this.$nextTick(() => this.$refs.closeButton?.focus());
                    },

                    closeTour() {
                        this.isModalOpen = false;
                        this.isFullscreen = false;
                        this.isLoading = false;
                        this.currentTourUrl = '';
                        document.body.style.overflow = '';
                        this.$nextTick(() => this.$refs.openButton?.focus());
                    },

                    toggleFullscreen() {
                        this.isFullscreen = !this.isFullscreen;
                    },

                    handleEscape() {
                        if (this.isFullscreen) {
                            this.isFullscreen = false;
                        } else if (this.isModalOpen) {
                            this.closeTour();
                        }
                    }
                };
            }
        </script>
    @endpush
@endsection
