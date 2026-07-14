@props([
    'name' => 'image',
    'value' => '',
    'images' => [],
    'inputId' => 'image-picker',
    'label' => 'URL Gambar',
    'required' => false,
])

@php
    $imageOptions = collect($images)
        ->map(fn ($path) => [
            'path' => $path,
            'url' => asset($path),
            'name' => basename($path),
        ])
        ->values();
@endphp

<div
    class="relative"
    x-data="adminImagePicker(@js($value), @js($imageOptions), @js(rtrim(asset('/'), '/')))"
    @click.outside="open = false"
    @keydown.escape.window="open = false">
    <label for="{{ $inputId }}" class="mb-1 block text-sm font-medium text-gray-700">{{ $label }}</label>

    <div class="flex overflow-hidden rounded-md border border-gray-300 bg-white focus-within:border-blue-500 focus-within:ring-2 focus-within:ring-blue-200">
        <input
            id="{{ $inputId }}"
            type="text"
            name="{{ $name }}"
            value="{{ $value }}"
            x-model="value"
            @input="previewFailed = false"
            @required($required)
            class="min-w-0 flex-1 border-0 px-3 py-2 outline-none focus:ring-0"
            placeholder="asset/gambar.jpg atau https://..."
            autocomplete="off">
        <button
            type="button"
            class="shrink-0 border-l border-gray-300 bg-gray-50 px-3 py-2 text-sm font-medium text-blue-600 hover:bg-blue-50"
            @click="open = !open"
            :aria-expanded="open.toString()"
            aria-haspopup="listbox">
            <i class="fas fa-images mr-1"></i>
            <span class="hidden sm:inline">Pilih Gambar</span>
            <i class="fas fa-chevron-down ml-1 text-xs transition-transform" :class="{ 'rotate-180': open }"></i>
        </button>
    </div>

    <p class="mt-1 text-xs text-gray-500">Ketik URL/link gambar atau pilih gambar yang sudah diupload.</p>

    <div x-show="value" x-cloak class="mt-2 flex items-center gap-3 rounded-md border border-gray-200 bg-white p-2">
        <div class="flex h-16 w-24 shrink-0 items-center justify-center overflow-hidden rounded bg-gray-100">
            <img
                x-show="!previewFailed"
                :src="previewUrl"
                alt="Preview gambar terpilih"
                class="h-full w-full object-cover"
                x-on:load="previewFailed = false"
                x-on:error="previewFailed = true">
            <i x-show="previewFailed" class="fas fa-image text-xl text-gray-400"></i>
        </div>
        <div class="min-w-0">
            <p class="text-xs font-semibold text-gray-700">Gambar terpilih</p>
            <p class="break-all text-xs text-gray-500" x-text="value"></p>
        </div>
    </div>

    <div
        x-show="open"
        x-cloak
        x-transition.origin.top.right
        class="absolute right-0 z-40 mt-2 w-full min-w-[18rem] overflow-hidden rounded-lg border border-gray-200 bg-white shadow-2xl md:w-[36rem]"
        role="listbox">
        <div class="border-b border-gray-200 bg-gray-50 px-4 py-3">
            <p class="font-semibold text-gray-800">Pilih dari Upload Gambar</p>
            <p class="text-xs text-gray-500">Klik thumbnail untuk memasukkan URL gambar ke kolom.</p>
        </div>

        <div x-show="options.length > 0" class="grid max-h-80 grid-cols-2 gap-2 overflow-y-auto p-3 sm:grid-cols-3">
            <template x-for="option in options" :key="option.path">
                <button
                    type="button"
                    class="overflow-hidden rounded-md border bg-white text-left transition hover:border-blue-500 hover:shadow-md"
                    :class="value === option.path ? 'border-blue-500 ring-2 ring-blue-200' : 'border-gray-200'"
                    @click="selectImage(option.path)"
                    role="option"
                    :aria-selected="(value === option.path).toString()">
                    <img :src="option.url" :alt="option.name" class="h-24 w-full bg-gray-100 object-cover">
                    <span class="block truncate px-2 py-2 text-xs text-gray-700" x-text="option.path" :title="option.path"></span>
                </button>
            </template>
        </div>

        <div x-show="options.length === 0" class="p-6 text-center text-sm text-gray-500">
            Belum ada gambar yang diupload.
        </div>

        <div class="border-t border-gray-200 bg-gray-50 p-3">
            <button
                type="button"
                class="w-full rounded-md bg-blue-600 px-3 py-2 text-sm font-medium text-white hover:bg-blue-700"
                @click="activeTab = 'upload'; open = false">
                <i class="fas fa-upload mr-1"></i>Buka Menu Upload Gambar
            </button>
        </div>
    </div>
</div>
