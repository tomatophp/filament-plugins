<?php

namespace TomatoPHP\FilamentPlugins\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use TomatoPHP\FilamentPlugins\Models\Table;

/**
 * @extends Factory<Table>
 */
class TableFactory extends Factory
{
    protected $model = Table::class;

    public function definition(): array
    {
        return [
            'module' => 'Demo',
            // snake_case and never an existing application table.
            'name' => 'demo_'.$this->faker->unique()->lexify('????????'),
            'comment' => $this->faker->sentence(),
            'timestamps' => true,
            'soft_deletes' => false,
            'migrated' => false,
            'generated' => false,
        ];
    }

    public function forModule(string $module): static
    {
        return $this->state(fn (): array => ['module' => $module]);
    }

    /**
     * Every builder table starts with an `id` column, like the create page does.
     */
    public function configure(): static
    {
        return $this->afterCreating(function (Table $table): void {
            $table->tableCols()->create([
                'name' => 'id',
                'type' => 'bigint',
                'unsigned' => true,
                'auto_increment' => true,
                'primary' => true,
            ]);
        });
    }
}
