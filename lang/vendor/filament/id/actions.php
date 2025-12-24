 <?php
 
 return [
     'modal' => [
         'confirmation' => 'Apakah Anda yakin ingin melakukan ini?',
         'requires_confirmation_subheading' => 'Apakah Anda yakin ingin melakukan ini?',
     ],
 
     'create' => [
         'label' => 'Buat',
         'modal' => [
             'heading' => 'Buat :label',
             'actions' => [
                 'create' => [
                     'label' => 'Buat',
                 ],
                 'create_another' => [
                     'label' => 'Buat & buat lagi',
                 ],
             ],
         ],
         'notifications' => [
             'created' => [
                 'title' => 'Berhasil dibuat',
             ],
         ],
     ],
 
     'delete' => [
         'label' => 'Hapus',
         'modal' => [
             'heading' => 'Hapus :label',
             'actions' => [
                 'delete' => [
                     'label' => 'Hapus',
                 ],
             ],
         ],
         'notifications' => [
             'deleted' => [
                 'title' => 'Berhasil dihapus',
             ],
         ],
     ],
 
     'edit' => [
         'label' => 'Ubah',
         'modal' => [
             'heading' => 'Ubah :label',
             'actions' => [
                 'save' => [
                     'label' => 'Simpan perubahan',
                 ],
             ],
         ],
         'notifications' => [
             'saved' => [
                 'title' => 'Berhasil disimpan',
             ],
         ],
     ],
 
     'view' => [
         'label' => 'Lihat',
         'modal' => [
             'heading' => 'Lihat :label',
         ],
     ],
 
     'replicate' => [
         'label' => 'Duplikat',
         'modal' => [
             'heading' => 'Duplikat :label',
             'actions' => [
                 'replicate' => [
                     'label' => 'Duplikat',
                 ],
             ],
         ],
         'notifications' => [
             'replicated' => [
                 'title' => 'Berhasil diduplikat',
             ],
         ],
     ],
 
     'restore' => [
         'label' => 'Pulihkan',
         'modal' => [
             'heading' => 'Pulihkan :label',
             'actions' => [
                 'restore' => [
                     'label' => 'Pulihkan',
                 ],
             ],
         ],
         'notifications' => [
             'restored' => [
                 'title' => 'Berhasil dipulihkan',
             ],
         ],
     ],
 
     'force_delete' => [
         'label' => 'Hapus permanen',
         'modal' => [
             'heading' => 'Hapus permanen :label',
             'actions' => [
                 'force_delete' => [
                     'label' => 'Hapus permanen',
                 ],
             ],
         ],
         'notifications' => [
             'deleted' => [
                 'title' => 'Berhasil dihapus permanen',
             ],
         ],
     ],
 ];
