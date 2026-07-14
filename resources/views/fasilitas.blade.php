@extends('layouts.app')

@section('title', 'Fasilitas & Virtual Tour - Universitas Pamulang')

@section('content')
    @php
        $tourFacilities = $facilities
            ->filter(fn ($facility) => filled($facility->virtual_tour_url))
            ->values();
        $hasFacilityTours = $tourFacilities->isNotEmpty();
    @endphp

    @push('styles')
        <style>
            [x-cloak] { display: none !important; }

            .facility-tour-card {
                position: relative;
                min-height: 250px;
                overflow: hidden;
                border: 2px solid transparent;
                border-radius: 1rem;
                background: #0f172a;
                box-shadow: 0 12px 28px rgba(15, 23, 42, .14);
                color: #fff;
                text-align: left;
                transition: transform .35s ease, box-shadow .35s ease, border-color .35s ease;
            }

            .facility-tour-card:not(:disabled):hover {
                transform: translateY(-6px);
                border-color: rgba(96, 165, 250, .8);
                box-shadow: 0 22px 42px rgba(15, 23, 42, .25);
            }

            .facility-tour-card.active {
                border-color: #3b82f6;
                box-shadow: 0 0 0 4px rgba(59, 130, 246, .18), 0 22px 42px rgba(15, 23, 42, .25);
            }

            .facility-tour-card:disabled {
                cursor: not-allowed;
                filter: grayscale(.35);
            }

            .facility-tour-card img {
                position: absolute;
                inset: 0;
                width: 100%;
                height: 100%;
                object-fit: cover;
                transition: transform .5s ease;
            }

            .facility-tour-card:not(:disabled):hover img {
                transform: scale(1.06);
            }

            .facility-tour-card::after {
                position: absolute;
                inset: 0;
                content: '';
                background: linear-gradient(180deg, rgba(15, 23, 42, .12) 10%, rgba(15, 23, 42, .94) 100%);
            }

            .facility-tour-card:disabled::after {
                background: linear-gradient(180deg, rgba(15, 23, 42, .38) 10%, rgba(15, 23, 42, .96) 100%);
            }

            .facility-tour-content {
                position: relative;
                z-index: 1;
                display: flex;
                min-height: 250px;
                flex-direction: column;
                justify-content: flex-end;
                padding: 1.5rem;
            }

            .facility-tour-viewer {
                position: relative;
                width: 100%;
                height: 0;
                overflow: hidden;
                padding-bottom: 56.25%;
                border-radius: 1rem;
                background: #0f172a;
                box-shadow: 0 25px 50px rgba(15, 23, 42, .24);
            }

            .facility-tour-modal-panel {
                max-height: calc(100vh - 2rem);
                overflow-y: auto;
            }

            .facility-tour-viewer iframe {
                position: absolute;
                inset: 0;
                width: 100%;
                height: 100%;
                border: 0;
            }

            .facility-tour-viewer.fullscreen {
                position: fixed;
                inset: 0;
                z-index: 9999;
                width: 100vw;
                height: 100vh;
                padding-bottom: 0;
                border-radius: 0;
            }

            .facility-tour-loading {
                position: absolute;
                inset: 0;
                z-index: 5;
                display: flex;
                align-items: center;
                justify-content: center;
                background: linear-gradient(135deg, #1e293b 0%, #0f172a 100%);
                color: #cbd5e1;
                transition: opacity .35s ease;
            }

            .facility-tour-loading.hidden {
                opacity: 0;
                pointer-events: none;
            }

            .facility-tour-spinner {
                width: 48px;
                height: 48px;
                border: 4px solid rgba(96, 165, 250, .2);
                border-top-color: #60a5fa;
                border-radius: 50%;
                animation: facility-spin 1s linear infinite;
            }

            @keyframes facility-spin {
                to { transform: rotate(360deg); }
            }

            .facility-fullscreen-button {
                position: absolute;
                top: 1rem;
                right: 1rem;
                z-index: 10;
                padding: .65rem 1rem;
                border: 1px solid rgba(255, 255, 255, .15);
                border-radius: .6rem;
                background: rgba(15, 23, 42, .78);
                color: #fff;
                backdrop-filter: blur(8px);
                transition: background .25s ease;
            }

            .facility-fullscreen-button:hover {
                background: rgba(37, 99, 235, .88);
            }
        </style>
    @endpush

    <section
        id="facilities"
        class="min-h-screen bg-gradient-to-b from-gray-100 to-white pb-20"
        style="padding-top: 130px;"
        x-data="facilityTourApp()">
        <div class="container mx-auto px-6">
            <div class="mx-auto mb-12 max-w-3xl text-center">
                <span class="mb-3 inline-flex items-center rounded-full bg-blue-100 px-4 py-2 text-sm font-semibold text-blue-700">
                    <i class="fas fa-vr-cardboard mr-2"></i>{{ $content['facility_page_badge'] }}
                </span>
                <h2 class="mb-4 text-3xl font-bold text-gray-900 md:text-4xl">{{ $content['facility_page_title'] }}</h2>
                <p class="text-lg leading-relaxed text-gray-600">
                    {{ $content['facility_page_description'] }}
                </p>
            </div>

            <div class="mx-auto grid max-w-5xl grid-cols-1 gap-6 md:grid-cols-2">
                @forelse($facilities as $facility)
                    @php($tourUrl = $facility->virtual_tour_url)
                    <button
                        type="button"
                        class="facility-tour-card w-full"
                        @if($tourUrl)
                            :class="{ 'active': activeFacility === {{ $facility->id }} }"
                            :aria-pressed="activeFacility === {{ $facility->id }}"
                            @click="openTour({{ $facility->id }}, @js($tourUrl), @js($facility->name))"
                        @else
                            disabled
                            aria-disabled="true"
                        @endif>
                        <img src="{{ asset($facility->image ?? 'asset/default.jpg') }}" alt="{{ $facility->name }}">
                        <span class="facility-tour-content">
                            <span class="mb-3 inline-flex w-fit items-center rounded-full px-3 py-1 text-xs font-semibold {{ $tourUrl ? 'bg-blue-500/90 text-white' : 'bg-gray-700/90 text-gray-200' }}">
                                <i class="fas {{ $tourUrl ? 'fa-play-circle' : 'fa-clock' }} mr-2"></i>
                                {{ $tourUrl ? 'Buka Virtual Tour' : 'Tour belum tersedia' }}
                            </span>
                            <span class="mb-2 text-2xl font-bold">{{ $facility->name }}</span>
                            <span class="leading-relaxed text-slate-200">{{ $facility->description }}</span>
                        </span>
                    </button>
                @empty
                    <div class="col-span-full rounded-xl border border-yellow-200 bg-yellow-50 p-6 text-center text-yellow-700">
                        Belum ada data fasilitas. Tambahkan fasilitas dari dashboard admin.
                    </div>
                @endforelse
            </div>

            @if($hasFacilityTours)
                <div
                    x-show="isModalOpen"
                    x-transition.opacity
                    x-cloak
                    class="fixed inset-0 flex items-center justify-center bg-slate-950/80 p-4"
                    style="z-index: 100;"
                    role="dialog"
                    aria-modal="true"
                    :aria-label="'Virtual Tour ' + activeFacilityName"
                    @click.self="closeTour()">
                    <div x-show="isModalOpen" x-transition class="facility-tour-modal-panel w-full max-w-6xl rounded-2xl bg-white shadow-2xl">
                        <div class="flex items-center justify-between gap-4 border-b border-gray-200 px-5 py-4">
                            <div>
                                <p class="text-xs font-semibold uppercase tracking-wider text-blue-600">Virtual Tour Fasilitas</p>
                                <h3 class="text-xl font-bold text-gray-900" x-text="activeFacilityName"></h3>
                            </div>
                            <button x-ref="closeButton" type="button" class="flex h-10 w-10 items-center justify-center rounded-full bg-gray-100 text-gray-600 hover:bg-gray-200" aria-label="Tutup virtual tour" @click="closeTour()">
                                <i class="fas fa-times"></i>
                            </button>
                        </div>

                        <div class="p-4 sm:p-6">
                            <div class="facility-tour-viewer" :class="{ 'fullscreen': isFullscreen }">
                                <div class="facility-tour-loading" :class="{ 'hidden': !isLoading }">
                                    <div class="text-center">
                                        <div class="facility-tour-spinner mx-auto mb-4"></div>
                                        <p class="text-sm">Memuat virtual tour fasilitas...</p>
                                    </div>
                                </div>

                                <template x-if="currentTourUrl">
                                    <iframe
                                        :src="currentTourUrl"
                                        :title="'Virtual Tour ' + activeFacilityName"
                                        allowfullscreen
                                        allow="gyroscope; accelerometer; xr-spatial-tracking"
                                        @load="onIframeLoaded()">
                                    </iframe>
                                </template>

                                <button type="button" class="facility-fullscreen-button" @click="toggleFullscreen()">
                                    <i :class="isFullscreen ? 'fas fa-compress' : 'fas fa-expand'"></i>
                                    <span class="ml-1" x-text="isFullscreen ? 'Keluar Fullscreen' : 'Fullscreen'"></span>
                                </button>
                            </div>

                            <p class="mt-4 text-sm text-gray-500">
                                <i class="fas fa-mouse-pointer mr-2 text-blue-600"></i>Klik dan geser panorama untuk melihat ke segala arah.
                            </p>
                        </div>
                    </div>
                </div>
            @elseif($facilities->isNotEmpty())
                <div class="mx-auto mt-10 max-w-3xl rounded-xl border border-blue-200 bg-blue-50 p-6 text-center text-blue-700">
                    <i class="fas fa-info-circle mr-2"></i>
                    Virtual tour fasilitas belum tersedia. Admin dapat mengunggah tour secara terpisah pada menu Fasilitas.
                </div>
            @endif
        </div>
    </section>

    @include('partials.contact')

    @push('scripts')
        <script>
            function facilityTourApp() {
                return {
                    activeFacility: null,
                    activeFacilityName: '',
                    currentTourUrl: '',
                    isModalOpen: false,
                    isFullscreen: false,
                    isLoading: false,

                    openTour(facilityId, url, name) {
                        this.activeFacility = facilityId;
                        this.activeFacilityName = name;
                        this.isLoading = true;
                        this.currentTourUrl = url;
                        this.isModalOpen = true;
                        document.body.style.overflow = 'hidden';
                        this.$nextTick(() => this.$refs.closeButton?.focus());
                    },

                    closeTour() {
                        this.isModalOpen = false;
                        this.isFullscreen = false;
                        this.isLoading = false;
                        this.currentTourUrl = '';
                        this.activeFacility = null;
                        this.activeFacilityName = '';
                        document.body.style.overflow = '';
                    },

                    onIframeLoaded() {
                        this.isLoading = false;
                    },

                    toggleFullscreen() {
                        this.isFullscreen = !this.isFullscreen;
                    },

                    init() {
                        document.addEventListener('keydown', (event) => {
                            if (event.key !== 'Escape') return;

                            if (this.isFullscreen) {
                                this.isFullscreen = false;
                            } else if (this.isModalOpen) {
                                this.closeTour();
                            }
                        });
                    }
                };
            }
        </script>
    @endpush
@endsection
