<!-- Contact Section -->
<section id="contact" class="py-20 bg-white">
    <div class="container mx-auto px-6">
        <h2 class="text-3xl font-bold text-center mb-16">HUBUNGI KAMI</h2>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-12">
            <div>
                <h3 class="text-2xl font-semibold mb-6">INFORMASI</h3>
                <div class="space-y-6">
                    <div class="flex items-start">
                        <div class="bg-blue-200 p-3 rounded-full mr-4"><i class="fas fa-map-marker-alt"></i></div>
                        <div>
                            <h4 class="font-medium">Alamat</h4>
                            <p>Jl. Raya Puspitek, Buaran, Kec. Pamulang, Kota Tangerang Selatan, Banten 15310</p>
                        </div>
                    </div>
                    <div class="flex items-start">
                        <div class="bg-blue-200 p-3 rounded-full mr-4"><i class="fas fa-phone-alt"></i></div>
                        <div>
                            <h4 class="font-medium">Telepon</h4>
                            <p>021 7412 566<br>Ext. 123 (Prodi Sistem Informasi)</p>
                        </div>
                    </div>
                    <div class="flex items-start">
                        <div class="bg-blue-200 p-3 rounded-full mr-4"><i class="fas fa-envelope"></i></div>
                        <div>
                            <h4 class="font-medium">Email</h4>
                            <p>humas@unpam.ac.id</p>
                        </div>
                    </div>
                </div>
                <div class="mt-8">
                    <h3 class="text-xl font-semibold mb-4">Jam Operasional</h3>
                    <div class="bg-white rounded-lg p-4">
                        <table class="w-full">
                            <tbody>
                                <tr class="border-b border-gray-600">
                                    <td class="py-2 font-medium">Senin - Jumat</td>
                                    <td class="py-2 text-right">08:00 - 16:00</td>
                                </tr>
                                <tr class="border-b border-gray-600">
                                    <td class="py-2 font-medium">Sabtu</td>
                                    <td class="py-2 text-right">08:00 - 14:00</td>
                                </tr>
                                <tr>
                                    <td class="py-2 font-medium">Minggu</td>
                                    <td class="py-2 text-right">Tutup</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div>
                <h3 class="text-2xl font-semibold mb-6">KRITIK & SARAN</h3>
                <form method="POST" action="{{ route('kritik-saran.store') }}" class="space-y-4">
                    @csrf
                    <div>
                        <label for="nama" class="block mb-1">Nama</label>
                        <input type="text" name="nama" id="nama" required class="w-full bg-white border border-gray-700 rounded-md px-4 py-2">
                    </div>
                    <div>
                        <label for="kontak" class="block mb-1">Email/No. HP</label>
                        <input type="text" name="kontak" id="kontak" required class="w-full bg-white border border-gray-700 rounded-md px-4 py-2">
                    </div>
                    <div>
                        <label for="pesan" class="block mb-1">Pesan</label>
                        <textarea name="pesan" id="pesan" rows="4" required class="w-full bg-white border border-gray-700 rounded-md px-4 py-2"></textarea>
                    </div>
                    <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-6 rounded-md">Kirim Pesan</button>
                </form>
            </div>
        </div>
    </div>
</section>
