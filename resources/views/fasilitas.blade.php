@extends('layouts.app')

@section('content')
    <!-- Facilities Section -->
    <section id="facilities" class="py-20 bg-gray-100" style="padding-top: 120px;">
        <div class="container mx-auto px-6">
            <h2 class="text-3xl font-bold text-center mb-6">FASILITAS UNPAM VIKTOR</h2>
            <p class="text-center text-gray-700 mb-16 max-w-2xl mx-auto">Temukan berbagai fasilitas modern yang mendukung proses belajar mengajar di Program Studi Sistem Informasi</p>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                @forelse($facilities as $facility)
                    <div class="facility-card bg-white rounded-lg overflow-hidden shadow-md transition duration-300">
                        <div class="h-48 overflow-hidden">
                            <img src="{{ asset($facility->image ?? 'asset/default.jpg') }}" alt="{{ e($facility->name) }}" class="w-full h-full object-cover">
                        </div>
                        <div class="p-6">
                            <h3 class="text-xl font-semibold mb-2">{{ e($facility->name) }}</h3>
                            <p class="text-gray-600">{{ e($facility->description) }}</p>
                        </div>
                    </div>
                @empty
                    <div class="col-span-full bg-yellow-50 border border-yellow-200 rounded-lg p-6 text-center text-yellow-700">
                        Belum ada data fasilitas. Tambahkan fasilitas dari dashboard admin.
                    </div>
                @endforelse
            </div>
        </div>
    </section>

    @include('partials.contact')
@endsection
