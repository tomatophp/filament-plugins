# Changelog

## v5.0.0

- Support Filament v5 and Laravel 12 / 13 (PHP 8.2+).
- Require `nwidart/laravel-modules` ^12.0|^13.0 and `tomatophp/filament-icons` ^5.0.
- `filament-plugins:resource`, `filament-plugins:page` and `filament-plugins:widget` now reuse Filament v5's own generators, so the generated classes use schemas, `Filament\Actions`, `recordActions()` / `toolbarActions()` and typed properties.
- The generated plugin page stub uses the Filament v5 property types.
- New plugin options to lock down file-writing features: `allowCreate()`, `allowImport()`, `allowToggle()`, `allowDestroy()`, `allowGenerator()`.
- `filament-plugins:model` works on every database driver (it no longer runs `SHOW TABLES`).
- Generated plugins are written to the configured `modules.paths.modules` directory.
- Pest test suite with Testbench.
