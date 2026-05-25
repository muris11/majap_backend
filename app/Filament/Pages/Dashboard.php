<?php

namespace App\Filament\Pages;

use Filament\Pages\Dashboard as BaseDashboard;
use App\Filament\Widgets\StatsOverview;
use App\Filament\Widgets\RecentActivitiesWidget;
use App\Filament\Widgets\RecentMessagesWidget;

class Dashboard extends BaseDashboard
{
    protected static ?int $navigationSort = -2;

    public static function getNavigationLabel(): string
    {
        return 'Beranda';
    }

    public static function getNavigationIcon(): string|null
    {
        return 'heroicon-o-home';
    }

    public function getTitle(): string
    {
        return 'Dashboard';
    }

    public function getHeading(): string
    {
        return '';
    }

    public function getSubheading(): ?string
    {
        return null;
    }

    public function getView(): string
    {
        return 'filament.pages.dashboard';
    }

    public function getWidgets(): array
    {
        return [
            StatsOverview::class,
            RecentActivitiesWidget::class,
            RecentMessagesWidget::class,
        ];
    }

    public function getColumns(): int | array
    {
        return 2;
    }
}