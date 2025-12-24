<?php

namespace App\Filament\Widgets;

use App\Models\Activity;
use App\Models\Album;
use App\Models\Batch;
use App\Models\ContactMessage;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class StatsOverview extends BaseWidget
{
    protected static ?int $sort = 1;
    
    protected int | string | array $columnSpan = 'full';

    protected function getStats(): array
    {
        $unreadMessages = ContactMessage::where('is_read', false)->count();
        
        return [
            Stat::make('Angkatan', Batch::count())
                ->description('Terdaftar')
                ->descriptionIcon('heroicon-m-academic-cap')
                ->color('success'),

            Stat::make('Kegiatan', Activity::count())
                ->description('Total kegiatan')
                ->descriptionIcon('heroicon-m-calendar-days')
                ->color('primary'),

            Stat::make('Album', Album::count())
                ->description('Galeri foto')
                ->descriptionIcon('heroicon-m-photo')
                ->color('warning'),

            Stat::make('Pesan', ContactMessage::count())
                ->description($unreadMessages > 0 ? $unreadMessages . ' baru' : 'Semua terbaca')
                ->descriptionIcon('heroicon-m-envelope')
                ->color($unreadMessages > 0 ? 'danger' : 'success'),
        ];
    }
}