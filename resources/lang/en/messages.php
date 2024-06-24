<?php

return [
    'group' => 'Settings',
    'plugins' => [
       'title' => 'Plugins',
       'create' => 'Create Plugin',
       'import' => 'Import Plugin',
       'form' => [
            'name' => 'Name',
            'name-placeholder' => 'e.g. My Plugin',
            'description' => 'Description',
            'description-placeholder' => 'e.g. A simple plugin for Filament',
            'icon' => 'Icon',
            'color' => 'Color',
            'file' => 'Plugin ZIP file',
       ],
       'actions' => [
            'generate' => 'Generate',
            'active' => 'Active',
            'disable' => 'Disable',
            'delete' => 'Delete',
           'github' => 'Github',
           'docs' => 'Docs',
       ],
        'notifications' => [
            'autoload' => [
                'title' => 'Error',
                'body' => 'The plugin could not be activated because the class could not be found. please run composer dump-autoload on your terminal'
            ],
            'enabled' => [
                'title' => 'Success',
                'body' => 'The plugin has been activated successfully.'
            ],
            'deleted' => [
                'title' => 'Success',
                'body' => 'The plugin has been deleted successfully.'
            ],
            'disabled' => [
                'title' => 'Success',
                'body' => 'The plugin has been deactivated successfully.'
            ],
            'import' => [
                'title' => 'Success',
                'body' => 'The plugin has been imported successfully.'
            ],
            'created' => [
                'title' => 'Success',
                'body' => 'The plugin has been created successfully.'
            ],
        ]
    ],
    'tables' => [
        'title' => 'Tables',
        'create' => 'Create Table',
        'edit' => 'Edit Table',
        'columns' => 'Table Columns',
        'form' => [
            'name' => 'Name',
            'soft_deletes' => 'Allow Soft Delete',
            'timestamps' => 'Allow Timestamps',
            'type' => 'Type',
            'nullable' => 'Nullable',
            'foreign' => 'Foreign',
            'foreign_table' => 'Foreign Table',
            'foreign_col' => 'Foreign Column',
            'foreign_on_delete_cascade' => 'On Delete Cascade',
            'auto_increment' => 'Auto Increment',
            'primary' => 'Primary',
            'unsigned' => 'Unsigned',
            'default' => 'Default',
            'unique' => 'Unique',
            'index' => 'Index',
            'lenth' => 'Length',
            'migrated' => 'Migrated',
            'generated' => 'Generated',
            'created_at' => 'Created At',
            'updated_at' => 'Update At',
        ],
        'actions' => [
            'create' => 'Create Table',
            'migrate' => 'Create Migration',
            'generate' => 'Generate',
            'columns' => 'Add Column',
            'add-id' => 'Add ID Column',
            'add-timestamps' => 'Add Timestamps',
            'add-softdeletes' => 'Add Soft Deletes',
        ],
        'notifications' => [
            'migrated' => [
                'title' => 'Success',
                'body' => 'The table has been migrated successfully.'
            ],
            'not-migrated' => [
                'title' => 'Error',
                'body' => 'The table could not be migrated.'
            ],
            'generated' => [
                'title' => 'Success',
                'body' => 'The table has been generated successfully.'
            ],
            'model' => [
                'title' => 'Error',
                'body' => 'The model could not be found generate it first.'
            ]
        ]
    ]
];
