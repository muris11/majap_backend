<?php

namespace App\Filament\Widgets;

use App\Models\Activity;
use App\Models\Album;
use App\Models\ContactMessage;
use App\Models\Batch;
use App\Models\OrganizationStructure;
use App\Models\TimelineEvent;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class StatsOverview extends BaseWidget
{
    protected static ?int $sort = 1;

    protected function getColumns(): int
    {
        return 4;
    }

    protected function getStats(): array
    {
        return [
            Stat::make('Angkatan', Batch::count())
                ->description('Total angkatan terdaftar')
                ->descriptionIcon('heroicon-o-academic-cap')
                ->color('success')
                ->chart([1, 2, 3, 5, 4, 6, Batch::count()]),

            Stat::make('Kegiatan', Activity::count())
                ->description(Activity::where('is_published', true)->count() . ' dipublikasikan')
                ->descriptionIcon('heroicon-o-calendar-days')
                ->color('primary')
                ->chart([3, 5, 7, 8, 6, 9, Activity::count()]),

            Stat::make('Album', Album::count())
                ->description(Album::where('is_published', true)->count() . ' dipublikasikan')
                ->descriptionIcon('heroicon-o-photo')
                ->color('warning')
                ->chart([1, 2, 4, 3, 5, 4, Album::count()]),

            Stat::make('Pesan Masuk', ContactMessage::count())
                ->description(ContactMessage::where('is_read', false)->count() . ' belum dibaca')
                ->descriptionIcon('heroicon-o-envelope')
                ->color(ContactMessage::where('is_read', false)->count() > 0 ? 'danger' : 'gray')
                ->chart([7, 5, 8, 6, 9, 4, ContactMessage::count()]),

            Stat::make('Linimasa', TimelineEvent::count())
                ->description(TimelineEvent::where('is_published', true)->count() . ' dipublikasikan')
                ->descriptionIcon('heroicon-o-clock')
                ->color('info')
                ->chart([2, 3, 1, 4, 3, 5, TimelineEvent::count()]),

            Stat::make('Struktur Organisasi', OrganizationStructure::count())
                ->description(OrganizationStructure::where('is_active', true)->count() . ' aktif')
                ->descriptionIcon('heroicon-o-square-3-stack-3d')
                ->color('gray'),
        ];
    }
}
