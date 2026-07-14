@extends('layouts.app')

@section('content')
    <section id="about" class="bg-white py-20" style="padding-top: 120px;">
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
                </div>
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

    @include('partials.contact')
@endsection
