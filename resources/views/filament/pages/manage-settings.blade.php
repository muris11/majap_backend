<x-filament-panels::page class="fi-settings-page">
    <form wire:submit="save" class="space-y-6">
        <div class="space-y-6">
            {{ $this->form }}
        </div>

        <div class="flex flex-col sm:flex-row gap-3 pt-6 border-t border-gray-200 dark:border-gray-700">
            <x-filament::button
                type="submit"
                class="w-full sm:w-auto"
            >
                <span class="flex items-center justify-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                    </svg>
                    Simpan Pengaturan
                </span>
            </x-filament::button>

            <x-filament::button
                type="button"
                color="gray"
                tag="a"
                href="{{ route('filament.admin.pages.dashboard') }}"
                class="w-full sm:w-auto"
            >
                Batal
            </x-filament::button>
        </div>
    </form>

    <div class="mt-6 p-4 rounded-lg border border-blue-200 bg-blue-50 dark:border-blue-900 dark:bg-blue-950/20">
        <div class="flex gap-3">
            <svg class="w-5 h-5 shrink-0 mt-0.5 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
            <div class="text-sm text-blue-800 dark:text-blue-300">
                <p class="font-semibold mb-1">Informasi</p>
                <p class="text-blue-700 dark:text-blue-400">
                    Pengaturan yang diubah akan langsung terlihat di website setelah disimpan.
                </p>
            </div>
        </div>
    </div>
</x-filament-panels::page>
