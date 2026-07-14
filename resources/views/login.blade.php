@extends('layouts.app')

@section('content')
    <section id="home" class="hero-section relative flex h-screen items-center justify-center px-4 text-white" style="background-image: linear-gradient(rgba(0, 0, 0, .82), rgba(0, 0, 0, .82)), url('{{ $content['hero_background_image_url'] }}');">
        <div class="relative z-20 w-full max-w-lg rounded-lg bg-white p-8 shadow-lg">
            <div class="text-center mb-6">
                <img src="{{ $content['site_logo_url'] }}" alt="Logo Universitas Pamulang" class="mx-auto h-24 w-24">
            </div>
            <h2 class="text-2xl font-semibold text-center text-gray-800 mb-6">Login Admin</h2>

            @if(session('error'))
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                    {{ session('error') }}
                </div>
            @endif

            <form action="{{ route('login.process') }}" method="POST" class="space-y-6" x-data="{ showPassword: false }">
                @csrf
                <div>
                    <label for="username" class="block text-sm font-medium text-gray-700">Username</label>
                    <input type="text" name="username" id="username" class="w-full px-4 py-2 border border-blue-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 text-gray-800" placeholder="Masukkan username" required>
                </div>
                <div>
                    <label for="password" class="block text-sm font-medium text-gray-700">Password</label>
                    <div class="relative">
                        <input :type="showPassword ? 'text' : 'password'" name="password" id="password" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 text-gray-800" placeholder="Masukkan password" required>
                        <button type="button" @click="showPassword = !showPassword" class="absolute right-3 top-2.5 text-gray-500">
                            <i :class="showPassword ? 'fas fa-eye-slash' : 'fas fa-eye'"></i>
                        </button>
                    </div>
                </div>
                <button type="submit" class="w-full py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500">Login</button>
            </form>
        </div>
    </section>
@endsection
