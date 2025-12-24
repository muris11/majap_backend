 <?php
 
 return [
     'components' => [
         'builder' => [
             'actions' => [
                 'add' => [
                     'label' => 'Tambah ke :label',
                 ],
                 'clone' => [
                     'label' => 'Duplikat',
                 ],
                 'delete' => [
                     'label' => 'Hapus',
                 ],
                 'add_between' => [
                     'label' => 'Sisipkan antar blok',
                 ],
                 'collapse_all' => [
                     'label' => 'Ciutkan semua',
                 ],
                 'expand_all' => [
                     'label' => 'Perluas semua',
                 ],
                 'move_down' => [
                     'label' => 'Pindah ke bawah',
                 ],
                 'move_up' => [
                     'label' => 'Pindah ke atas',
                 ],
                 'reorder' => [
                     'label' => 'Pindahkan',
                 ],
             ],
         ],
 
         'checkbox_list' => [
             'actions' => [
                 'deselect_all' => [
                     'label' => 'Batalkan pilihan semua',
                 ],
                 'select_all' => [
                     'label' => 'Pilih semua',
                 ],
             ],
         ],
 
         'file_upload' => [
             'editor' => [
                 'actions' => [
                     'cancel' => [
                         'label' => 'Batal',
                     ],
                     'drag_crop' => [
                         'label' => 'Mode seret "potong"',
                     ],
                     'drag_move' => [
                         'label' => 'Mode seret "pindah"',
                     ],
                     'flip_horizontal' => [
                         'label' => 'Balik horizontal',
                     ],
                     'flip_vertical' => [
                         'label' => 'Balik vertikal',
                     ],
                     'move_down' => [
                         'label' => 'Pindah ke bawah',
                     ],
                     'move_left' => [
                         'label' => 'Pindah ke kiri',
                     ],
                     'move_right' => [
                         'label' => 'Pindah ke kanan',
                     ],
                     'move_up' => [
                         'label' => 'Pindah ke atas',
                     ],
                     'rotate_left' => [
                         'label' => 'Putar ke kiri',
                     ],
                     'rotate_right' => [
                         'label' => 'Putar ke kanan',
                     ],
                     'save' => [
                         'label' => 'Simpan',
                     ],
                     'set_aspect_ratio' => [
                         'label' => 'Atur rasio aspek ke :ratio',
                     ],
                     'zoom_100' => [
                         'label' => 'Zoom ke 100%',
                     ],
                     'zoom_in' => [
                         'label' => 'Perbesar',
                     ],
                     'zoom_out' => [
                         'label' => 'Perkecil',
                     ],
                 ],
                 'fields' => [
                     'height' => [
                         'label' => 'Tinggi',
                         'unit' => 'px',
                     ],
                     'rotation' => [
                         'label' => 'Rotasi',
                         'unit' => 'derajat',
                     ],
                     'width' => [
                         'label' => 'Lebar',
                         'unit' => 'px',
                     ],
                     'x_position' => [
                         'label' => 'X',
                         'unit' => 'px',
                     ],
                     'y_position' => [
                         'label' => 'Y',
                         'unit' => 'px',
                     ],
                 ],
             ],
         ],
 
         'key_value' => [
             'actions' => [
                 'add' => [
                     'label' => 'Tambah baris',
                 ],
                 'delete' => [
                     'label' => 'Hapus baris',
                 ],
                 'reorder' => [
                     'label' => 'Urutkan ulang baris',
                 ],
             ],
             'fields' => [
                 'key' => [
                     'label' => 'Kunci',
                 ],
                 'value' => [
                     'label' => 'Nilai',
                 ],
             ],
         ],
 
         'markdown_editor' => [
             'toolbar_buttons' => [
                 'attach_files' => 'Lampirkan file',
                 'blockquote' => 'Kutipan',
                 'bold' => 'Tebal',
                 'bullet_list' => 'Daftar tidak berurut',
                 'code_block' => 'Blok kode',
                 'heading' => 'Judul',
                 'italic' => 'Miring',
                 'link' => 'Tautan',
                 'ordered_list' => 'Daftar berurut',
                 'preview' => 'Pratinjau',
                 'edit' => 'Edit',
                 'strike' => 'Coret',
                 'table' => 'Tabel',
             ],
         ],
 
         'repeater' => [
             'actions' => [
                 'add' => [
                     'label' => 'Tambah ke :label',
                 ],
                 'clone' => [
                     'label' => 'Duplikat',
                 ],
                 'delete' => [
                     'label' => 'Hapus',
                 ],
                 'collapse_all' => [
                     'label' => 'Ciutkan semua',
                 ],
                 'expand_all' => [
                     'label' => 'Perluas semua',
                 ],
                 'move_down' => [
                     'label' => 'Pindah ke bawah',
                 ],
                 'move_up' => [
                     'label' => 'Pindah ke atas',
                 ],
                 'reorder' => [
                     'label' => 'Pindahkan',
                 ],
             ],
         ],
 
         'rich_editor' => [
             'toolbar_buttons' => [
                 'attach_files' => 'Lampirkan file',
                 'blockquote' => 'Kutipan',
                 'bold' => 'Tebal',
                 'bullet_list' => 'Daftar tidak berurut',
                 'code_block' => 'Blok kode',
                 'heading' => 'Judul',
                 'italic' => 'Miring',
                 'link' => 'Tautan',
                 'ordered_list' => 'Daftar berurut',
                 'redo' => 'Ulangi',
                 'strike' => 'Coret',
                 'underline' => 'Garis bawah',
                 'undo' => 'Batalkan',
             ],
         ],
 
         'select' => [
             'actions' => [
                 'create_option' => [
                     'label' => 'Buat',
                     'modal' => [
                         'heading' => 'Buat',
                         'actions' => [
                             'create' => [
                                 'label' => 'Buat',
                             ],
                             'create_another' => [
                                 'label' => 'Buat & buat lagi',
                             ],
                         ],
                     ],
                 ],
                 'edit_option' => [
                     'label' => 'Ubah',
                     'modal' => [
                         'heading' => 'Ubah',
                         'actions' => [
                             'save' => [
                                 'label' => 'Simpan',
                             ],
                         ],
                     ],
                 ],
             ],
             'boolean' => [
                 'true' => 'Ya',
                 'false' => 'Tidak',
             ],
             'loading_message' => 'Memuat...',
             'max_items_message' => 'Hanya :count yang dapat dipilih.',
             'no_search_results_message' => 'Tidak ada hasil yang cocok.',
             'placeholder' => 'Pilih opsi',
             'searching_message' => 'Mencari...',
             'search_prompt' => 'Ketik untuk mencari...',
         ],
 
         'tags_input' => [
             'placeholder' => 'Tag baru',
         ],
 
         'wizard' => [
             'actions' => [
                 'previous_step' => [
                     'label' => 'Sebelumnya',
                 ],
                 'next_step' => [
                     'label' => 'Selanjutnya',
                 ],
             ],
         ],
     ],
 ];
