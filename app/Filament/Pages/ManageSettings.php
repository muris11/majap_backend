<?php

namespace App\Filament\Pages;

use App\Models\Setting;
use Filament\Actions\Action;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\HtmlString;

class ManageSettings extends Page implements HasForms
{
    use InteractsWithForms;

    protected static ?int $navigationSort = 100;

    public ?array $data = [];

    public static function getNavigationIcon(): ?string
    {
        return 'heroicon-o-cog-6-tooth';
    }

    public static function getNavigationLabel(): string
    {
        return 'Pengaturan';
    }

    public function getTitle(): string
    {
        return 'Pengaturan Website';
    }

    public function getView(): string
    {
        return 'filament.pages.manage-settings';
    }

    public function mount(): void
    {
        $settings = Setting::all()->pluck('value', 'key')->toArray();

        // Don't load existing image to avoid loading issues
        // User will need to re-upload if they want to change
        if (isset($settings['about_image'])) {
            unset($settings['about_image']);
        }
        if (isset($settings['about_page_image'])) {
            unset($settings['about_page_image']);
        }

        $this->form->fill($settings);
    }

    public function getAboutImageUrl(): ?string
    {
        $setting = Setting::where('key', 'about_image')->first();
        if ($setting && $setting->value) {
            return asset('storage/'.$setting->value);
        }

        return null;
    }

    public function getAboutPageImageUrl(): ?string
    {
        $setting = Setting::where('key', 'about_page_image')->first();
        if ($setting && $setting->value) {
            return asset('storage/'.$setting->value);
        }

        return null;
    }

    public function form(Schema $schema): Schema
    {
        return $schema
            ->schema([
                Section::make('Gambar')
                    ->description('Gambar yang ditampilkan di website')
                    ->schema([
                        Placeholder::make('current_image')
                            ->label('Gambar Tentang (Beranda)')
                            ->content(function () {
                                $url = $this->getAboutImageUrl();
                                if ($url) {
                                    return new HtmlString('<img src="'.$url.'" alt="Current Image" style="max-width: 200px; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.1);">');
                                }

                                return 'Belum ada gambar';
                            }),
                        FileUpload::make('about_image')
                            ->label('Upload Gambar Tentang (Beranda)')
                            ->helperText('Gambar di section Tentang pada halaman utama')
                            ->image()
                            ->maxSize(2048)
                            ->acceptedFileTypes(['image/jpeg', 'image/png', 'image/webp'])
                            ->disk('public')
                            ->visibility('public')
                            ->directory('settings'),
                        Placeholder::make('current_about_page_image')
                            ->label('Gambar Halaman Tentang')
                            ->content(function () {
                                $url = $this->getAboutPageImageUrl();
                                if ($url) {
                                    return new HtmlString('<img src="'.$url.'" alt="Current Image" style="max-width: 200px; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.1);">');
                                }

                                return 'Belum ada gambar';
                            }),
                        FileUpload::make('about_page_image')
                            ->label('Upload Gambar Halaman Tentang')
                            ->helperText('Gambar di halaman Tentang Kami (Visi & Misi)')
                            ->image()
                            ->maxSize(2048)
                            ->acceptedFileTypes(['image/jpeg', 'image/png', 'image/webp'])
                            ->disk('public')
                            ->visibility('public')
                            ->directory('settings'),
                    ]),
                Section::make('Statistik')
                    ->description('Data statistik yang ditampilkan di website')
                    ->schema([
                        TextInput::make('active_members')
                            ->label('Jumlah Anggota Aktif')
                            ->placeholder('150+'),
                        TextInput::make('established_year')
                            ->label('Tahun Berdiri')
                            ->placeholder('2015'),
                    ]),
            ])
            ->statePath('data');
    }

    protected function getFormActions(): array
    {
        return [
            Action::make('save')
                ->label('Simpan Pengaturan')
                ->submit('save'),
        ];
    }

    public function save(): void
    {
        $data = $this->form->getState();

        $imageKeys = ['about_image', 'about_page_image'];

        foreach ($data as $key => $value) {
            if (in_array($key, $imageKeys)) {
                $newValue = null;

                if (is_array($value) && ! empty($value)) {
                    $newValue = reset($value);
                } elseif (is_string($value) && ! empty($value)) {
                    $newValue = $value;
                }

                if ($newValue) {
                    $oldSetting = Setting::where('key', $key)->first();
                    if ($oldSetting && $oldSetting->value && $oldSetting->value !== $newValue) {
                        if (Storage::disk('public')->exists($oldSetting->value)) {
                            Storage::disk('public')->delete($oldSetting->value);
                        }
                    }
                    Setting::setValue($key, $newValue, 'image', 'general');
                }

                continue;
            }

            $type = match ($key) {
                'active_members', 'established_year' => 'text',
                default => 'text',
            };

            Setting::setValue($key, $value ?? '', $type, 'general');
        }

        Notification::make()
            ->title('Pengaturan berhasil disimpan')
            ->success()
            ->send();
    }
}
