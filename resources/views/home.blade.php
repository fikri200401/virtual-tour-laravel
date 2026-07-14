@extends('layouts.app')

@section('content')
    <section id="home" class="hero-section flex h-screen items-center justify-center text-white" style="background-image: linear-gradient(rgba(0, 0, 0, .7), rgba(0, 0, 0, .7)), url('{{ $content['hero_background_image_url'] }}');">
        <div class="px-4 text-center">
            <h1 class="mb-6 text-4xl font-bold md:text-6xl">{{ $content['hero_title'] }}</h1>
            <h2 class="mb-8 text-3xl font-bold md:text-5xl">{{ $content['hero_subtitle'] }}</h2>
            <p class="mx-auto mb-10 max-w-3xl text-xl">{{ $content['hero_description'] }}</p>
            <a href="{{ route('virtual-tour') }}" class="mt-8 inline-flex items-center rounded-full bg-yellow-500 px-8 py-3 font-medium text-white transition duration-300 hover:bg-yellow-600">
                <i class="fas fa-vr-cardboard mr-2"></i>{{ $content['home_tour_button_text'] }}
            </a>
        </div>
    </section>

    <section id="tour" class="bg-white py-20">
        <div class="container mx-auto px-6">
            <h2 class="mb-16 text-center text-3xl font-bold">{{ $content['home_tour_title'] }}</h2>
            <div class="grid grid-cols-1 items-center gap-12 lg:grid-cols-2">
                <div class="relative h-96 overflow-hidden rounded-lg bg-gray-200">
                    <img src="{{ $content['home_tour_image_url'] }}" alt="{{ $content['home_tour_heading'] }}" class="h-full w-full object-cover">
                </div>
                <div>
                    <h3 class="mb-4 text-2xl font-semibold">{{ $content['home_tour_heading'] }}</h3>
                    <p class="mb-6 leading-relaxed">{{ $content['home_tour_description'] }}</p>
                    <div class="space-y-4">
                        @foreach([$content['home_tour_feature_1'], $content['home_tour_feature_2'], $content['home_tour_feature_3']] as $feature)
                            <div class="flex items-start">
                                <div class="mr-4 rounded-full bg-blue-100 p-2"><i class="fas fa-check text-blue-600"></i></div>
                                <p>{{ $feature }}</p>
                            </div>
                        @endforeach
                    </div>
                    <a href="{{ route('virtual-tour') }}" class="mt-8 inline-flex items-center rounded-full bg-blue-600 px-8 py-3 font-medium text-white transition duration-300 hover:bg-blue-700">
                        <i class="fas fa-vr-cardboard mr-2"></i>{{ $content['home_tour_button_text'] }}
                    </a>
                </div>
            </div>
        </div>
    </section>

    <section id="fasilitas" class="bg-gray-100 py-20">
        <div class="container mx-auto px-6">
            <h2 class="mb-6 text-center text-3xl font-bold">{{ $content['facilities_title'] }}</h2>
            <p class="mx-auto mb-16 max-w-2xl text-center text-gray-700">{{ $content['facilities_description'] }}</p>
            <div class="grid grid-cols-1 gap-8 md:grid-cols-2 lg:grid-cols-3">
                @forelse($facilities as $facility)
                    <div class="facility-card overflow-hidden rounded-lg bg-white shadow-md transition duration-300">
                        <div class="h-48 overflow-hidden">
                            <img src="{{ asset($facility->image ?? 'asset/default.jpg') }}" alt="{{ $facility->name }}" class="h-full w-full object-cover">
                        </div>
                        <div class="p-6">
                            <h3 class="mb-2 text-xl font-semibold">{{ $facility->name }}</h3>
                            <p class="text-gray-600">{{ $facility->description }}</p>
                        </div>
                    </div>
                @empty
                    <div class="col-span-full rounded-lg border border-yellow-200 bg-yellow-50 p-6 text-center text-yellow-700">
                        Belum ada data fasilitas. Tambahkan fasilitas dari dashboard admin.
                    </div>
                @endforelse
            </div>
        </div>
    </section>

    <section id="lokasi-kampus" class="bg-white py-16">
        <div class="container mx-auto px-4">
            <h2 class="mb-12 text-center text-3xl font-bold text-gray-800">{{ $content['campus_location_title'] }}</h2>
            <div class="grid grid-cols-1 gap-12 md:grid-cols-2">
                <div>
                    <h3 class="mb-4 text-xl font-semibold">{{ $content['campus_map_title'] }}</h3>
                    <img src="{{ $content['campus_map_image_url'] }}" alt="{{ $content['campus_map_title'] }}" class="rounded-lg shadow-xl">
                    <p class="mt-4 text-gray-600">{{ $content['campus_map_description'] }}</p>
                </div>
                <div>
                    <h3 class="mb-4 text-xl font-semibold">{{ $content['google_maps_title'] }}</h3>
                    <div class="h-[400px] w-full">
                        <iframe src="{{ $content['google_maps_embed_url'] }}" title="{{ $content['google_maps_title'] }}" width="100%" height="100%" style="border:0;" allowfullscreen loading="lazy" referrerpolicy="no-referrer-when-downgrade" class="rounded-lg shadow-lg"></iframe>
                    </div>
                    <p class="mt-4 text-gray-600">{{ $content['google_maps_description'] }}</p>
                </div>
            </div>
        </div>
    </section>

    <section id="about" class="bg-white py-20">
        <div class="container mx-auto px-6">
            <h2 class="mb-16 text-center text-3xl font-bold">{{ $content['about_title'] }}</h2>
            <div class="flex flex-col items-center md:flex-row">
                <div class="mb-10 md:mb-0 md:w-1/2">
                    <img src="{{ $content['about_image_url'] }}" alt="{{ $content['about_title'] }}" class="rounded-lg shadow-xl">
                </div>
                <div class="md:w-1/2 md:pl-12">
                    <h3 class="mb-4 text-2xl font-semibold">{{ $content['about_subtitle'] }}</h3>
                    <p class="mb-6 leading-relaxed">{{ $content['about_description'] }}</p>
                    <p class="mb-6 leading-relaxed">{{ $content['about_commitment_text'] }}</p>
                    <ul class="mb-6 list-disc space-y-2 pl-6">
                        <li>{{ $content['about_point_1'] }}</li>
                        <li>{{ $content['about_point_2'] }}</li>
                        <li>{{ $content['about_point_3'] }}</li>
                        <li>{{ $content['about_point_4'] }}</li>
                    </ul>
                    <div class="mt-8">
                        <a href="{{ route('tentang') }}" class="inline-block rounded-md bg-blue-500 px-6 py-2 font-medium text-white transition duration-300 hover:bg-blue-700">{{ $content['about_button_text'] }}</a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    @include('partials.contact')
@endsection
