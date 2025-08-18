<?php

return [
    'actions' => [
        'logout' => [
            'label' => 'Çıkış Yap',
        ],
    ],
    'avatar' => [
        'alt' => ':name avatarı',
    ],
    'breadcrumbs' => [
        'actions' => [
            'toggle_sidebar' => 'Kenar Çubuğunu Aç/Kapat',
        ],
    ],
    'global_search' => [
        'field' => [
            'label' => 'Genel Arama',
            'placeholder' => 'Ara...',
        ],
        'no_results_message' => 'Sonuç bulunamadı.',
    ],
    'layout' => [
        'direction' => 'ltr',
    ],
    'pages' => [
        'dashboard' => [
            'title' => 'Kontrol Paneli',
        ],
    ],
    'resources' => [
        'label' => 'Kaynak',
        'plural_label' => 'Kaynaklar',
        'navigation_label' => 'Kaynaklar',
        'table' => [
            'actions' => [
                'create' => [
                    'label' => 'Yeni :label',
                ],
                'edit' => [
                    'label' => 'Düzenle',
                ],
                'delete' => [
                    'label' => 'Sil',
                ],
                'view' => [
                    'label' => 'Görüntüle',
                ],
            ],
            'columns' => [
                'text' => [
                    'more_list_items' => 've :count tane daha',
                ],
            ],
            'empty' => [
                'heading' => 'Kayıt bulunamadı',
                'description' => 'Başlamak için bir :model oluşturun.',
            ],
            'filters' => [
                'actions' => [
                    'remove' => [
                        'label' => 'Filtreyi kaldır',
                    ],
                    'remove_all' => [
                        'label' => 'Tüm filtreleri kaldır',
                    ],
                    'reset' => [
                        'label' => 'Sıfırla',
                    ],
                ],
                'heading' => 'Filtreler',
                'indicator' => 'Aktif filtreler',
                'multi_select' => [
                    'placeholder' => 'Tümü',
                ],
                'select' => [
                    'placeholder' => 'Tümü',
                ],
                'trashed' => [
                    'label' => 'Silinmiş kayıtlar',
                    'only_trashed' => 'Sadece silinmiş kayıtlar',
                    'with_trashed' => 'Silinmiş kayıtlarla birlikte',
                    'without_trashed' => 'Silinmiş kayıtlar hariç',
                ],
            ],
            'reorder_indicator' => 'Kayıtları sürükleyip bırakarak yeniden sıralayın.',
            'selection_indicator' => [
                'selected_count' => '1 kayıt seçildi|:count kayıt seçildi',
                'actions' => [
                    'select_all' => [
                        'label' => 'Tüm :count kaydı seç',
                    ],
                    'deselect_all' => [
                        'label' => 'Tümünün seçimini kaldır',
                    ],
                ],
            ],
            'sorting' => [
                'fields' => [
                    'column' => [
                        'label' => 'Sırala',
                    ],
                    'direction' => [
                        'label' => 'Sıralama yönü',
                        'options' => [
                            'asc' => 'Artan',
                            'desc' => 'Azalan',
                        ],
                    ],
                ],
            ],
        ],
    ],
    'widgets' => [
        'account' => [
            'heading' => 'Hesap',
        ],
        'filament_info' => [
            'heading' => 'Filament',
        ],
    ],
];