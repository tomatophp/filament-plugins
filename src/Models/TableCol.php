<?php

namespace TomatoPHP\FilamentPlugins\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use TomatoPHP\FilamentPlugins\Database\Factories\TableColFactory;

/**
 * @property int $id
 * @property int $table_id
 * @property string $name
 * @property string $type
 * @property int $length
 * @property string $default
 * @property string $comment
 * @property string $foreign_table
 * @property string $foreign_col
 * @property string $foreign_model
 * @property bool $nullable
 * @property bool $index
 * @property bool $auto_increment
 * @property bool $primary
 * @property bool $unique
 * @property bool $unsigned
 * @property bool $foreign
 * @property bool $foreign_on_delete_cascade
 * @property string $created_at
 * @property string $updated_at
 * @property Table $table
 */
class TableCol extends Model
{
    use HasFactory;

    protected static function newFactory(): TableColFactory
    {
        return TableColFactory::new();
    }

    /**
     * @var array
     */
    protected $fillable = ['order', 'table_id', 'name', 'type', 'length', 'default', 'comment', 'foreign_table', 'foreign_col', 'foreign_model', 'nullable', 'index', 'auto_increment', 'primary', 'unique', 'unsigned', 'foreign', 'foreign_on_delete_cascade', 'created_at', 'updated_at'];

    protected $casts = [
        'nullable' => 'boolean',
        'index' => 'boolean',
        'auto_increment' => 'boolean',
        'primary' => 'boolean',
        'unique' => 'boolean',
        'unsigned' => 'boolean',
        'foreign' => 'boolean',
        'foreign_on_delete_cascade' => 'boolean',
    ];

    /**
     * @return BelongsTo
     */
    public function table()
    {
        return $this->belongsTo('TomatoPHP\FilamentPlugins\Models\Table');
    }

    public function getTable()
    {
        return config('filament-plugins.database_prefix') ? config('filament-plugins.database_prefix').'_table_cols' : 'table_cols';
    }
}
