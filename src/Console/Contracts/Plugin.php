<?php

namespace TomatoPHP\FilamentPlugins\Console\Contracts;

use TomatoPHP\ConsoleHelpers\Traits\RunCommand;

use function Laravel\Prompts\info;
use function Laravel\Prompts\warning;

class Plugin
{
    use RunCommand;

    public ?string $label = null;

    public ?string $key = null;

    public ?string $module = null;

    public ?string $url = null;

    public ?string $description = null;

    public ?string $instractions = null;

    public ?string $command = null;

    public ?string $group = null;

    public static function make(string $key): static
    {
        return app(static::class)->key($key);
    }

    public function group(string $group): static
    {
        $this->group = $group;

        return $this;
    }

    public function key(string $key): static
    {
        $this->key = $key;

        return $this;
    }

    public function module(string $module): static
    {
        $this->module = $module;

        return $this;
    }

    public function label(string $label): static
    {
        $this->label = $label;

        return $this;
    }

    public function description(string $description): static
    {
        $this->description = $description;

        return $this;
    }

    public function instractions(string $instractions): static
    {
        $this->instractions = $instractions;

        return $this;
    }

    public function url(string $url): static
    {
        $this->url = $url;

        return $this;
    }

    public function command(string $command): static
    {
        $this->command = $command;

        return $this;
    }

    public function install(): void
    {
        info('About Plugin: '.$this->description);
        info('Docs: '.$this->url);
        info('Installing '.$this->label.' ...');
        $this->requireComposerPackages($this->command);
        $this->artisanCommand([$this->key.':install']);
        $this->artisanCommand(['filament:optimize-clear']);
        if (! empty($this->instractions)) {
            warning('finally reigster the plugin on "/app/Providers/Filament/AdminPanelProvider.php" using this code');
            warning($this->instractions);
            warning('then run "php artisan filament:optimize-clear" and check your plugins in /admin/plugins and enable it');
        }
        info($this->label.' installed successfully');
        info('====================================');
    }
}
