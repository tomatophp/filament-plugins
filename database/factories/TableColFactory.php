<?php

namespace TomatoPHP\FilamentPlugins\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use TomatoPHP\FilamentPlugins\Models\Table;
use TomatoPHP\FilamentPlugins\Models\TableCol;

/**
 * @extends Factory<TableCol>
 */
class TableColFactory extends Factory
{
    protected $model = TableCol::class;

    public function definition(): array
    {
        return [
            'table_id' => Table::factory(),
            'name' => $this->faker->unique()->lexify('column_????'),
            'type' => 'string',
            'length' => 255,
            'nullable' => true,
            'order' => $this->faker->numberBetween(1, 100),
        ];
    }
}
