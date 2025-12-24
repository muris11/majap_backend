<?php

namespace App\Services;

use App\Enums\ActivityStatus;
 use App\Enums\AlbumStatus;
 use App\Models\Batch;
 use App\Models\User;
 
 class SchemaService
 {
     public function getFormSchema(string $name): ?array
     {
         return match ($name) {
             'contact' => $this->contactFormSchema(),
             'activity' => $this->activityFormSchema(),
             'album' => $this->albumFormSchema(),
             default => null,
         };
     }
 
     public function getTableSchema(string $name): ?array
     {
         return match ($name) {
             'activities' => $this->activitiesTableSchema(),
             'albums' => $this->albumsTableSchema(),
             default => null,
         };
     }
 
     public function getNavigation(?User $user): array
     {
         $publicNav = [
             ['key' => 'home', 'label' => 'Beranda', 'href' => '/', 'icon' => 'home'],
             ['key' => 'about', 'label' => 'Tentang', 'href' => '/tentang', 'icon' => 'info'],
             ['key' => 'activities', 'label' => 'Kegiatan', 'href' => '/kegiatan', 'icon' => 'calendar'],
             ['key' => 'gallery', 'label' => 'Galeri', 'href' => '/galeri', 'icon' => 'image'],
             ['key' => 'contact', 'label' => 'Kontak', 'href' => '/kontak', 'icon' => 'mail'],
         ];
 
         if (!$user) {
             return ['items' => $publicNav];
         }
 
         $userNav = [];
 
         if ($user->hasRole('admin') || $user->hasRole('super_admin')) {
             $userNav[] = [
                 'key' => 'admin',
                 'label' => 'Admin Panel',
                 'href' => '/admin',
                 'icon' => 'settings',
             ];
         }
 
         return [
             'items' => $publicNav,
             'user_items' => $userNav,
         ];
     }
 
     protected function contactFormSchema(): array
     {
         return [
             'name' => 'contact',
             'title' => 'Hubungi Kami',
             'description' => 'Kirim pesan kepada kami',
             'fields' => [
                 [
                     'name' => 'name',
                     'type' => 'text',
                     'label' => 'Nama Lengkap',
                     'placeholder' => 'Masukkan nama lengkap',
                     'validation' => [
                         'required' => true,
                         'max' => 255,
                     ],
                 ],
                 [
                     'name' => 'email',
                     'type' => 'email',
                     'label' => 'Email',
                     'placeholder' => 'email@contoh.com',
                     'validation' => [
                         'required' => true,
                         'email' => true,
                         'max' => 255,
                     ],
                 ],
                 [
                     'name' => 'subject',
                     'type' => 'text',
                     'label' => 'Subjek',
                     'placeholder' => 'Subjek pesan',
                     'validation' => [
                         'required' => true,
                         'max' => 255,
                     ],
                 ],
                 [
                     'name' => 'message',
                     'type' => 'textarea',
                     'label' => 'Pesan',
                     'placeholder' => 'Tulis pesan Anda...',
                     'rows' => 5,
                     'validation' => [
                         'required' => true,
                         'max' => 5000,
                     ],
                 ],
             ],
             'actions' => [
                 [
                     'type' => 'submit',
                     'label' => 'Kirim Pesan',
                     'variant' => 'primary',
                 ],
             ],
         ];
     }
 
     protected function activityFormSchema(): array
     {
         $batches = Batch::active()
             ->orderBy('year', 'desc')
             ->get()
             ->map(fn ($b) => ['value' => $b->id, 'label' => $b->name])
             ->toArray();
 
         return [
             'name' => 'activity',
             'title' => 'Form Kegiatan',
             'fields' => [
                 [
                     'name' => 'title',
                     'type' => 'text',
                     'label' => 'Judul Kegiatan',
                     'validation' => ['required' => true, 'max' => 255],
                 ],
                 [
                     'name' => 'slug',
                     'type' => 'text',
                     'label' => 'Slug',
                     'validation' => ['required' => true, 'max' => 255],
                     'hint' => 'URL-friendly identifier',
                 ],
                 [
                     'name' => 'short_description',
                     'type' => 'textarea',
                     'label' => 'Deskripsi Singkat',
                     'rows' => 3,
                     'validation' => ['required' => true, 'max' => 500],
                 ],
                 [
                     'name' => 'content',
                     'type' => 'richtext',
                     'label' => 'Konten',
                     'validation' => ['required' => true],
                 ],
                 [
                     'name' => 'batch_id',
                     'type' => 'select',
                     'label' => 'Angkatan',
                     'options' => $batches,
                     'validation' => ['required' => true],
                 ],
                 [
                     'name' => 'status',
                     'type' => 'select',
                     'label' => 'Status',
                     'options' => ActivityStatus::toArray(),
                     'default' => 'draft',
                     'validation' => ['required' => true],
                 ],
                 [
                     'name' => 'event_date',
                     'type' => 'date',
                     'label' => 'Tanggal Kegiatan',
                     'validation' => ['required' => true],
                 ],
                 [
                     'name' => 'location',
                     'type' => 'text',
                     'label' => 'Lokasi',
                     'validation' => ['required' => true, 'max' => 255],
                 ],
                 [
                     'name' => 'cover_image',
                     'type' => 'image',
                     'label' => 'Cover Image',
                     'accept' => 'image/*',
                     'maxSize' => 2048,
                     'validation' => ['required' => false],
                 ],
                 [
                     'name' => 'is_featured',
                     'type' => 'checkbox',
                     'label' => 'Tampilkan di Beranda',
                     'default' => false,
                 ],
             ],
             'actions' => [
                 ['type' => 'submit', 'label' => 'Simpan', 'variant' => 'primary'],
                 ['type' => 'cancel', 'label' => 'Batal', 'variant' => 'secondary'],
             ],
         ];
     }
 
     protected function albumFormSchema(): array
     {
         $batches = Batch::active()
             ->orderBy('year', 'desc')
             ->get()
             ->map(fn ($b) => ['value' => $b->id, 'label' => $b->name])
             ->toArray();
 
         return [
             'name' => 'album',
             'title' => 'Form Album',
             'fields' => [
                 [
                     'name' => 'title',
                     'type' => 'text',
                     'label' => 'Judul Album',
                     'validation' => ['required' => true, 'max' => 255],
                 ],
                 [
                     'name' => 'description',
                     'type' => 'textarea',
                     'label' => 'Deskripsi',
                     'rows' => 3,
                 ],
                 [
                     'name' => 'batch_id',
                     'type' => 'select',
                     'label' => 'Angkatan',
                     'options' => $batches,
                     'validation' => ['required' => true],
                 ],
                 [
                     'name' => 'status',
                     'type' => 'select',
                     'label' => 'Status',
                     'options' => AlbumStatus::toArray(),
                     'default' => 'draft',
                     'validation' => ['required' => true],
                 ],
             ],
             'actions' => [
                 ['type' => 'submit', 'label' => 'Simpan', 'variant' => 'primary'],
             ],
         ];
     }
 
     protected function activitiesTableSchema(): array
     {
         return [
             'name' => 'activities',
             'title' => 'Daftar Kegiatan',
             'columns' => [
                 ['key' => 'title', 'label' => 'Judul', 'sortable' => true, 'searchable' => true],
                 ['key' => 'batch.name', 'label' => 'Angkatan', 'sortable' => true],
                 ['key' => 'event_date_formatted', 'label' => 'Tanggal', 'sortable' => true],
                 ['key' => 'location', 'label' => 'Lokasi', 'searchable' => true],
                 ['key' => 'is_featured', 'label' => 'Featured', 'type' => 'badge'],
             ],
             'filters' => [
                 [
                     'key' => 'batch_id',
                     'label' => 'Filter Angkatan',
                     'type' => 'select',
                     'endpoint' => '/api/v1/batches',
                 ],
                 [
                     'key' => 'featured',
                     'label' => 'Hanya Featured',
                     'type' => 'checkbox',
                 ],
             ],
             'actions' => ['view', 'edit', 'delete'],
             'pagination' => [
                 'per_page_options' => [12, 24, 48],
                 'default_per_page' => 12,
             ],
         ];
     }
 
     protected function albumsTableSchema(): array
     {
         return [
             'name' => 'albums',
             'title' => 'Daftar Album',
             'columns' => [
                 ['key' => 'title', 'label' => 'Judul', 'sortable' => true, 'searchable' => true],
                 ['key' => 'batch.name', 'label' => 'Angkatan', 'sortable' => true],
                 ['key' => 'photos_count', 'label' => 'Jumlah Foto'],
                 ['key' => 'activity.title', 'label' => 'Kegiatan Terkait'],
             ],
             'filters' => [
                 [
                     'key' => 'batch_id',
                     'label' => 'Filter Angkatan',
                     'type' => 'select',
                     'endpoint' => '/api/v1/batches',
                 ],
             ],
             'actions' => ['view', 'edit', 'delete'],
             'pagination' => [
                 'per_page_options' => [12, 24, 48],
                 'default_per_page' => 12,
             ],
         ];
     }
 }
