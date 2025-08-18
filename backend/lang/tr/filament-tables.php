<?php

return [
    'columns' => [
        'text' => [
            'more_list_items' => 've :count tane daha',
        ],
    ],
    'fields' => [
        'bulk_select_page' => [
            'label' => 'Toplu işlemler için tüm öğeleri seç/seçimi kaldır.',
        ],
        'bulk_select_record' => [
            'label' => ':key öğesi için toplu işlemleri seç/seçimi kaldır.',
        ],
        'search' => [
            'label' => 'Ara',
            'placeholder' => 'Ara',
            'indicator' => 'Ara',
        ],
    ],
    'summary' => [
        'heading' => 'Özet',
        'subheadings' => [
            'all' => 'Tüm :label',
            'group' => ':group özeti',
            'page' => 'Bu sayfa',
        ],
        'summarizers' => [
            'average' => [
                'label' => 'Ortalama',
            ],
            'count' => [
                'label' => 'Sayı',
            ],
            'sum' => [
                'label' => 'Toplam',
            ],
        ],
    ],
    'actions' => [
        'disable_reordering' => [
            'label' => 'Kayıtları yeniden sıralamayı bitir',
        ],
        'enable_reordering' => [
            'label' => 'Kayıtları yeniden sırala',
        ],
        'filter' => [
            'label' => 'Filtrele',
        ],
        'group' => [
            'label' => 'Grupla',
        ],
        'open_bulk_actions' => [
            'label' => 'Toplu işlemler',
        ],
        'toggle_columns' => [
            'label' => 'Sütunları değiştir',
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
    'grouping' => [
        'fields' => [
            'group' => [
                'label' => 'Grupla',
                'placeholder' => 'Grupla',
            ],
            'direction' => [
                'label' => 'Gruplama yönü',
                'options' => [
                    'asc' => 'Artan',
                    'desc' => 'Azalan',
                ],
            ],
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
];