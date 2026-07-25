<section id="contact" class="bg-white py-20">
    <div class="container mx-auto px-6">
        <div class="mb-16 text-center">
            <h2 class="text-3xl font-bold">{{ $content['contact_title'] }}</h2>
            @if($content['contact_description'])
                <p class="mx-auto mt-3 max-w-2xl text-gray-600">{{ $content['contact_description'] }}</p>
            @endif
        </div>
        <div class="grid grid-cols-1 gap-12 md:grid-cols-2">
            <div>
                <h3 class="mb-6 text-2xl font-semibold">{{ $content['contact_information_title'] }}</h3>
                <div class="space-y-6">
                    <div class="flex items-start">
                        <div class="mr-4 rounded-full bg-blue-200 p-3"><i class="fas fa-map-marker-alt"></i></div>
                        <div>
                            <h4 class="font-medium">Alamat</h4>
                            <p>{{ $content['contact_address'] }}</p>
                        </div>
                    </div>
                    <div class="flex items-start">
                        <div class="mr-4 rounded-full bg-blue-200 p-3"><i class="fas fa-phone-alt"></i></div>
                        <div>
                            <h4 class="font-medium">Telepon</h4>
                            <p>{{ $content['contact_phone'] }}</p>
                        </div>
                    </div>
                    <div class="flex items-start">
                        <div class="mr-4 rounded-full bg-blue-200 p-3"><i class="fas fa-envelope"></i></div>
                        <div>
                            <h4 class="font-medium">Email</h4>
                            <p>{{ $content['contact_email'] }}</p>
                        </div>
                    </div>
                </div>
                <div class="mt-8">
                    <h3 class="mb-4 text-xl font-semibold">{{ $content['contact_hours_title'] }}</h3>
                    <div class="rounded-lg bg-white p-4">
                        <table class="w-full">
                            <tbody>
                                <tr class="border-b border-gray-600">
                                    <td class="py-2 font-medium">Senin - Jumat</td>
                                    <td class="py-2 text-right">{{ $content['contact_weekday_hours'] }}</td>
                                </tr>
                                <tr class="border-b border-gray-600">
                                    <td class="py-2 font-medium">Sabtu</td>
                                    <td class="py-2 text-right">{{ $content['contact_saturday_hours'] }}</td>
                                </tr>
                                <tr>
                                    <td class="py-2 font-medium">Minggu</td>
                                    <td class="py-2 text-right">{{ $content['contact_sunday_hours'] }}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div>
                <h3 class="mb-6 text-2xl font-semibold">{{ $content['feedback_title'] }}</h3>
                <form method="POST" action="{{ route('kritik-saran.store') }}" class="space-y-4">
                    @csrf
                    <div>
                        <label for="nama" class="mb-1 block">Nama</label>
                        <input type="text" name="nama" id="nama" value="{{ old('nama') }}" required class="w-full rounded-md border bg-white px-4 py-2 {{ $errors->has('nama') ? 'border-red-500' : 'border-gray-700' }}">
                        @error('nama')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label for="kontak" class="mb-1 block">Email/No. HP</label>
                        <input type="text" name="kontak" id="kontak" value="{{ old('kontak') }}" required class="w-full rounded-md border bg-white px-4 py-2 {{ $errors->has('kontak') ? 'border-red-500' : 'border-gray-700' }}">
                        @error('kontak')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label for="pesan" class="mb-1 block">Pesan</label>
                        <textarea name="pesan" id="pesan" rows="4" required class="w-full rounded-md border bg-white px-4 py-2 {{ $errors->has('pesan') ? 'border-red-500' : 'border-gray-700' }}">{{ old('pesan') }}</textarea>
                        @error('pesan')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label for="captcha_answer" class="mb-2 block">Kode CAPTCHA</label>
                        <div class="mb-2 flex flex-wrap items-center gap-3">
                            <img id="feedback-captcha-image" src="{{ route('kritik-saran.captcha') }}" alt="Kode CAPTCHA" width="220" height="70" class="h-[70px] w-[220px] rounded-md border border-gray-300 bg-blue-50 object-cover">
                            <button type="button" id="refresh-feedback-captcha" class="rounded-md border border-blue-600 px-4 py-2 text-sm font-medium text-blue-600 hover:bg-blue-50" aria-label="Muat kode CAPTCHA baru">
                                <i class="fas fa-sync-alt mr-2"></i>Kode Baru
                            </button>
                        </div>
                        <input type="text" name="captcha_answer" id="captcha_answer" required maxlength="5" autocomplete="off" autocapitalize="characters" spellcheck="false" class="w-full rounded-md border bg-white px-4 py-2 uppercase tracking-[0.3em] {{ $errors->has('captcha_answer') ? 'border-red-500' : 'border-gray-700' }}" placeholder="Masukkan 5 karakter di atas">
                        @error('captcha_answer')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                    </div>
                    <button type="submit" class="rounded-md bg-blue-600 px-6 py-2 font-medium text-white hover:bg-blue-700">{{ $content['feedback_submit_text'] }}</button>
                </form>
            </div>
        </div>
    </div>
</section>

@push('scripts')
    <script>
        document.getElementById('refresh-feedback-captcha')?.addEventListener('click', () => {
            const image = document.getElementById('feedback-captcha-image');
            if (image) image.src = @js(route('kritik-saran.captcha')) + '?refresh=' + Date.now();
        });

        @if($errors->hasAny(['nama', 'kontak', 'pesan', 'captcha_answer']))
            document.getElementById('contact')?.scrollIntoView({ block: 'start' });
        @endif
    </script>
@endpush
