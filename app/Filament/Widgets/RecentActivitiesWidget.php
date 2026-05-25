<?php

namespace App\Filament\Widgets;

use App\Models\Activity;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;

class RecentActivitiesWidget extends BaseWidget
{
    protected static ?int $sort = 2;

    protected int | string | array $columnSpan = 'full';

    public function table(Table $table): Table
    {
        return $table
            ->query(
                Activity::query()
                    ->with('batch')
                    ->latest('event_date')
                    ->limit(5)
            )
            ->columns([
                Tables\Columns\ImageColumn::make('cover_image')
                    ->label('')
                    ->disk('public')
                    ->square()
                    ->height(40),
                Tables\Columns\TextColumn::make('title')
                    ->label('Kegiatan')
                    ->limit(30)
                    ->weight('semibold'),
                Tables\Columns\TextColumn::make('batch.name')
                    ->label('Angkatan')
                    ->badge()
                    ->color('primary'),
                Tables\Columns\TextColumn::make('event_date')
                    ->label('Tanggal')
                    ->date('d M Y'),
                Tables\Columns\IconColumn::make('is_featured')
                    ->label('Unggulan')
                    ->boolean(),
                Tables\Columns\IconColumn::make('is_published')
                    ->label('Publikasi')
                    ->boolean(),
            ])
            ->heading('Kegiatan Terbaru')
            ->description('5 kegiatan terbaru diurutkan berdasarkan tanggal')
            ->paginated(false);
    }
}
