 <?php
 
 return [
     'columns' => [
         'text' => [
             'actions' => [
                 'collapse_list' => 'Tampilkan :count lebih sedikit',
                 'expand_list' => 'Tampilkan :count lagi',
             ],
             'more_list_items' => 'dan :count lagi',
         ],
     ],
 
     'actions' => [
         'modal' => [
             'confirmation' => 'Apakah Anda yakin ingin melakukan ini?',
             'requires_confirmation_subheading' => 'Apakah Anda yakin ingin melakukan ini?',
         ],
     ],
 
     'empty' => [
         'heading' => 'Tidak ada data',
         'description' => 'Buat :label untuk memulai.',
     ],
 
     'filters' => [
         'actions' => [
             'reset' => 'Reset filter',
             'apply' => 'Terapkan',
         ],
         'heading' => 'Filter',
         'indicator' => 'Filter aktif',
         'multi_select' => [
             'placeholder' => 'Semua',
         ],
         'select' => [
             'placeholder' => 'Semua',
         ],
         'trinary' => [
             'placeholder' => 'Semua',
             'true' => 'Ya',
             'false' => 'Tidak',
         ],
     ],
 
     'grouping' => [
         'fields' => [
             'group' => [
                 'label' => 'Kelompokkan berdasarkan',
                 'placeholder' => 'Kelompokkan berdasarkan',
             ],
             'direction' => [
                 'label' => 'Arah pengelompokan',
                 'options' => [
                     'asc' => 'Naik',
                     'desc' => 'Turun',
                 ],
             ],
         ],
     ],
 
     'reorder_indicator' => 'Seret dan lepas data ke urutan yang diinginkan.',
 
     'selection_indicator' => [
         'selected_count' => ':count dipilih',
         'actions' => [
             'select_all' => [
                 'label' => 'Pilih semua :count',
             ],
             'deselect_all' => [
                 'label' => 'Batalkan pilihan semua',
             ],
         ],
     ],
 
     'sorting' => [
         'fields' => [
             'column' => [
                 'label' => 'Urutkan berdasarkan',
             ],
             'direction' => [
                 'label' => 'Arah pengurutan',
                 'options' => [
                     'asc' => 'Naik',
                     'desc' => 'Turun',
                 ],
             ],
         ],
     ],
 
     'search' => [
         'label' => 'Cari',
         'placeholder' => 'Cari',
         'indicator' => 'Cari',
     ],
 
     'summary' => [
         'heading' => 'Ringkasan',
         'subheadings' => [
             'all' => 'Semua :label',
             'group' => 'Ringkasan :group',
             'page' => 'Halaman ini',
         ],
         'summarizers' => [
             'average' => [
                 'label' => 'Rata-rata',
             ],
             'count' => [
                 'label' => 'Jumlah',
             ],
             'sum' => [
                 'label' => 'Total',
             ],
         ],
     ],
 ];
