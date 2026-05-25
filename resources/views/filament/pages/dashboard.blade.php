<x-filament-panels::page class="fi-dashboard-page">
    {{-- Welcome Banner - Responsive & Professional --}}
    <div class="mb-6 p-4 md:p-5 lg:p-6 rounded-xl text-white bg-gradient-to-br from-[#606C38] via-[#4a5a2d] to-[#283618] shadow-lg dark:shadow-xl">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 sm:gap-6">
            {{-- User Info Section --}}
            <div class="flex items-center gap-3 md:gap-4">
                <div class="shrink-0">
                    <img 
                        src="{{ asset('logo-small.png') }}" 
                        alt="Logo" 
                        class="w-12 h-12 md:w-14 md:h-14 rounded-full bg-white/10 backdrop-blur-sm p-1.5 md:p-2 ring-2 ring-white/20"
                    >
                </div>
                <div class="min-w-0">
                    <h2 class="text-lg md:text-xl lg:text-2xl font-bold truncate">
                        Selamat {{ now()->format('H') < 12 ? 'Pagi' : (now()->format('H') < 15 ? 'Siang' : (now()->format('H') < 18 ? 'Sore' : 'Malam')) }}, {{ auth()->user()->name }}
                    </h2>
                    <p class="text-xs md:text-sm text-white/85 mt-0.5">
                        {{ now()->translatedFormat('l, d F Y') }}
                    </p>
                </div>
            </div>

            {{-- Quick Actions --}}
            <div class="flex flex-wrap gap-2 sm:gap-3 sm:shrink-0">
                <x-filament::button 
                    tag="a" 
                    href="{{ route('filament.admin.resources.activities.create') }}" 
                    size="sm" 
                    color="white"
                    outlined
                    class="border-white/30 backdrop-blur-sm transition-all duration-200"
                >
                    <span class="hidden sm:inline">Kegiatan Baru</span>
                    <span class="sm:hidden">Kegiatan</span>
                </x-filament::button>
                <x-filament::button 
                    tag="a" 
                    href="{{ route('filament.admin.resources.contact-messages.index') }}" 
                    size="sm" 
                    icon="heroicon-o-envelope" 
                    color="white"
                    outlined
                    class="border-white/30 backdrop-blur-sm transition-all duration-200"
                >
                    Pesan
                </x-filament::button>
            </div>
        </div>
    </div>

    {{-- Widgets --}}
    <x-filament-widgets::widgets
        :widgets="$this->getVisibleWidgets()"
        :columns="$this->getColumns()"
    />
</x-filament-panels::page>
