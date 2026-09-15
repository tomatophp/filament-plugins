<?php

namespace TomatoPHP\FilamentPlugins\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Schema;
use Nwidart\Modules\Facades\Module;
use TomatoPHP\FilamentPlugins\Services\CRUDGenerator;

/**
 * @property int $id
 * @property string $module
 * @property string $name
 * @property string $comment
 * @property bool $timestamps
 * @property bool $soft_deletes
 * @property bool $migrated
 * @property bool $generated
 * @property Carbon|null $migrated_at
 * @property string $created_at
 * @property string $updated_at
 * @property TableCol[] $tableCols
 */
class Table extends Model
{
    /**
     * @var array
     */
    protected $fillable = ['module', 'name', 'comment', 'timestamps', 'soft_deletes', 'migrated', 'generated', 'created_at', 'updated_at'];

    protected $casts = [
        'timestamps' => 'boolean',
        'soft_deletes' => 'boolean',
        'migrated' => 'boolean',
        'generated' => 'boolean',
        'migrated_at' => 'datetime',
    ];

    public function tableCols(): HasMany
    {
        return $this->hasMany(TableCol::class);
    }

    /**
     * The `*_create_{name}_table.php` migrations the builder wrote into this module.
     *
     * @return array<int, string>
     */
    public function generatedMigrationFiles(): array
    {
        $module = Module::find((string) $this->module);

        if (! $module || blank($this->name)) {
            return [];
        }

        return File::glob($module->getPath().'/database/migrations/*_create_'.$this->name.'_table.php') ?: [];
    }

    /**
     * The builder owns the database table only when it migrated it itself and its migration file still exists.
     */
    public function ownsDatabaseTable(): bool
    {
        return $this->migrated_at !== null && count($this->generatedMigrationFiles()) > 0;
    }

    /**
     * Migrating drops the database table, so it is only allowed for new tables or tables the builder owns.
     */
    public function canMigrate(): bool
    {
        return (! Schema::hasTable($this->name)) || $this->ownsDatabaseTable();
    }

    /**
     * Drop the owned table (if any) and write a fresh migration for it.
     *
     * Returns false, without touching the database, when an existing table is not owned by the builder.
     */
    public function migrate(): bool
    {
        if (! $this->canMigrate()) {
            return false;
        }

        (new CRUDGenerator(table: $this, migration: true))->generate();

        $this->forceFill([
            'migrated' => true,
            'migrated_at' => now(),
        ])->save();

        return true;
    }

    public function getTable()
    {
        return config('filament-plugins.database_prefix') ? config('filament-plugins.database_prefix').'_tables' : 'tables';
    }
}
