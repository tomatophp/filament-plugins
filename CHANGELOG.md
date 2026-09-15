# Changelog

## v5.0.0

- Support Filament v5 and Laravel 12 / 13 (PHP 8.2+).
- Require `nwidart/laravel-modules` ^12.0|^13.0 and `tomatophp/filament-icons` ^5.0.
- `filament-plugins:resource`, `filament-plugins:page` and `filament-plugins:widget` now reuse Filament v5's own generators, so the generated classes use schemas, `Filament\Actions`, `recordActions()` / `toolbarActions()` and typed properties.
- The generated plugin page stub uses the Filament v5 property types.
- New plugin options to lock down file-writing features: `allowCreate()`, `allowImport()`, `allowToggle()`, `allowDestroy()`, `allowGenerator()`.
- The table builder's migrate action only drops tables it created itself (tracked with a new `migrated_at` column plus the module's own migration file); an existing table it does not own is left untouched and a notification explains why. Table names must be snake_case, unique and must not match an existing database table.
- Registering the plugin no longer removes other plugins from the panel: only plugins inside a disabled module from the modules folder are disabled, and only their own classes are removed. Vendor packages that ship a `module.json` (all TomatoPHP packages) are never touched.
- Ship `Table` and `TableCol` factories (`TomatoPHP\FilamentPlugins\Database\Factories`).
- Ship `resources/dist/filament-plugins.css` with the utilities the plugin cards use; run `php artisan filament:assets` after updating.
- `filament-plugins:model` works on every database driver (it no longer runs `SHOW TABLES`).
- Generated plugins are written to the configured `modules.paths.modules` directory.
- Pest test suite with Testbench.
