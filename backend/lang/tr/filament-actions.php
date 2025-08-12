<?php

return [
    'single' => [
        'label' => 'Yeni :label',
        'modal' => [
            'heading' => ':Label oluştur',
            'actions' => [
                'create' => [
                    'label' => 'Oluştur',
                ],
                'create_another' => [
                    'label' => 'Oluştur ve yeni ekle',
                ],
            ],
        ],
        'notifications' => [
            'created' => [
                'title' => 'Oluşturuldu',
            ],
        ],
    ],
    'multiple' => [
        'label' => 'Yeni :label',
        'modal' => [
            'heading' => ':Label oluştur',
            'actions' => [
                'create' => [
                    'label' => 'Oluştur',
                ],
                'create_another' => [
                    'label' => 'Oluştur ve yeni ekle',
                ],
            ],
        ],
        'notifications' => [
            'created' => [
                'title' => 'Oluşturuldu',
            ],
        ],
    ],
    'edit' => [
        'single' => [
            'label' => 'Düzenle',
            'modal' => [
                'heading' => ':Label düzenle',
                'actions' => [
                    'save' => [
                        'label' => 'Kaydet',
                    ],
                ],
            ],
            'notifications' => [
                'saved' => [
                    'title' => 'Kaydedildi',
                ],
            ],
        ],
    ],
    'delete' => [
        'single' => [
            'label' => 'Sil',
            'modal' => [
                'heading' => ':Label sil',
                'description' => 'Bu işlemi geri alamazsınız.',
                'actions' => [
                    'delete' => [
                        'label' => 'Sil',
                    ],
                ],
            ],
            'notifications' => [
                'deleted' => [
                    'title' => 'Silindi',
                ],
            ],
        ],
        'multiple' => [
            'label' => 'Seçilenleri sil',
            'modal' => [
                'heading' => ':label sil',
                'description' => 'Bu işlemi geri alamazsınız.',
                'actions' => [
                    'delete' => [
                        'label' => 'Sil',
                    ],
                ],
            ],
            'notifications' => [
                'deleted' => [
                    'title' => 'Silindi',
                ],
            ],
        ],
    ],
    'view' => [
        'single' => [
            'label' => 'Görüntüle',
            'modal' => [
                'heading' => ':Label görüntüle',
                'actions' => [
                    'close' => [
                        'label' => 'Kapat',
                    ],
                ],
            ],
        ],
    ],
];