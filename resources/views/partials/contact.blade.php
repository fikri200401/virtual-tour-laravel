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
                        <input type="text" name="nama" id="nama" required class="w-full rounded-md border border-gray-700 bg-white px-4 py-2">
                    </div>
                    <div>
                        <label for="kontak" class="mb-1 block">Email/No. HP</label>
                        <input type="text" name="kontak" id="kontak" required class="w-full rounded-md border border-gray-700 bg-white px-4 py-2">
                    </div>
                    <div>
                        <label for="pesan" class="mb-1 block">Pesan</label>
                        <textarea name="pesan" id="pesan" rows="4" required class="w-full rounded-md border border-gray-700 bg-white px-4 py-2"></textarea>
                    </div>
                    <button type="submit" class="rounded-md bg-blue-600 px-6 py-2 font-medium text-white hover:bg-blue-700">{{ $content['feedback_submit_text'] }}</button>
                </form>
            </div>
        </div>
    </div>
</section>
