<?php

return [
    'group' => 'الإعدادات',
    'plugins' => [
        'title' => 'الاضافات',
        'create' => 'إنشاء اضافة جديدة',
        'import' => 'استيراد اضافة',
        'form' => [
            'name' => 'الاسم',
            'name-placeholder' => 'e.g. My Plugin',
            'description' => 'الوصف',
            'description-placeholder' => 'e.g. A simple plugin for Filament',
            'icon' => 'الايقونة',
            'color' => 'اللون',
            'file' => 'ملف الاضافة المضغوط',
        ],
        'actions' => [
            'generate' => 'توليد',
            'active' => 'تفعيل',
            'disable' => 'إبطال',
            'delete' => 'حذف',
            'github' => 'جيت هاب',
            'docs' => 'عن الاضافة',
        ],
        'notifications' => [
            'autoload' => [
                'title' => 'خطأ',
                'body' => 'عفواً لا يمكن تفعيل هذه الاضافة قبل تشغيل الامر composer dump-autoload في الطرفية'
            ],
            'enabled' => [
                'title' => 'عملية ناجحة',
                'body' => 'تم تفعيل الاضافة بنجاح'
            ],
            'deleted' => [
                'title' => 'عملية ناجحة',
                'body' => 'تم حذف الاضافة بنجاح'
            ],
            'disabled' => [
                'title' => 'عملية ناجحة',
                'body' => 'تم تبطيل الاضافة بنجاح'
            ],
            'import' => [
                'title' => 'عملية ناجحة',
                'body' => 'تم استيراد الاضافة بنجاح'
            ],
            'created' => [
                'title' => 'عملية ناجحة',
                'body' => 'تم إنشاء الاضافة بنجاح'
            ],
        ]
    ],
    'tables' => [
        'title' => 'الجداول',
        'create' => 'إنشاء جدول جديد',
        'edit' => 'تعديل الجدول',
        'columns' => 'أعمدة الجدول',
        'form' => [
            'name' => 'الاسم',
            'type' => 'النوع',
            'soft_deletes' => 'السماح بالحذف الناعم',
            'timestamps' => 'السماح بالوقت',
            'nullable' => 'يمكن أن يكون فارغاً',
            'foreign' => 'خارجي',
            'foreign_table' => 'جدول خارجي',
            'foreign_col' => 'عمود خارجي',
            'foreign_on_delete_cascade' => 'عند الحذف يتبع',
            'auto_increment' => 'تزايد تلقائياً',
            'primary' => 'رئيسي',
            'unsigned' => 'غير سالب',
            'default' => 'افتراضي',
            'unique' => 'فريد',
            'index' => 'فهرس',
            'lenth' => 'الطول',
            'migrated' => 'تم التهجير',
            'generated' => 'تم التوليد',
            'created_at' => 'تاريخ الإنشاء',
            'updated_at' => 'تاريخ التحديث',
        ],
        'actions' => [
            'create' => 'إنشاء جدول',
            'migrate' => 'تهجير',
            'generate' => 'توليد',
            'columns' => 'اضافة عمود',
            'add-id' => 'إضافة عمود ID',
            'add-timestamps' => 'إضافة الوقت',
            'add-softdeletes' => 'إضافة حذف ناعم',
        ],
        'notifications' => [
            'migrated' => [
                'title' => 'عملية ناجحة',
                'body' => 'تم تهجير الجدول بنجاح'
            ],
            'not-migrated' => [
                'title' => 'خطأ',
                'body' => 'لا يمكن تهجير الجدول'
            ],
            'generated' => [
                'title' => 'عملية ناجحة',
                'body' => 'تم توليد الجدول بنجاح'
            ],
            'model' => [
                'title' => 'خطأ',
                'body' => 'لا يمكن توليد النموذج'
            ]
        ]
    ]
];
