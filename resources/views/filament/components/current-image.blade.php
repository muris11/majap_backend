<div class="mb-4">
    <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">
        Gambar Saat Ini
    </label>

    @if($imageUrl)
        <div class="inline-block border border-gray-200 dark:border-gray-700 rounded-xl p-2 bg-white/70 dark:bg-gray-800/70 backdrop-blur-sm shadow-sm hover:shadow-md transition-shadow duration-200">
            <img
                src="{{ $imageUrl }}"
                alt="Current Image"
                class="max-w-full sm:max-w-xs md:max-w-sm lg:max-w-md xl:max-w-lg h-auto block rounded-lg object-cover"
                loading="lazy"
            >
        </div>
        <p class="mt-2 text-xs text-gray-500 dark:text-gray-400">
            Klik pada field upload di atas untuk mengganti gambar
        </p>
    @else
        <div class="flex items-center gap-2 p-4 bg-gray-50 dark:bg-gray-900 rounded-lg border border-dashed border-gray-300 dark:border-gray-700">
            <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
            </svg>
            <span class="text-sm text-gray-500 dark:text-gray-400">
                Belum ada gambar
            </span>
        </div>
    @endif
</div>
