![Screenshot](https://github.com/tomatophp/filament-plugins/blob/master/arts/3x1io-tomato-plugins.jpg)

# Filament plugins

[![Latest Stable Version](https://poser.pugx.org/tomatophp/filament-plugins/version.svg)](https://packagist.org/packages/tomatophp/filament-plugins)
[![PHP Version Require](http://poser.pugx.org/tomatophp/filament-plugins/require/php)](https://packagist.org/packages/tomatophp/filament-plugins)
[![License](https://poser.pugx.org/tomatophp/filament-plugins/license.svg)](https://packagist.org/packages/tomatophp/filament-plugins)
[![Downloads](https://poser.pugx.org/tomatophp/filament-plugins/d/total.svg)](https://packagist.org/packages/tomatophp/filament-plugins)

Manage your modules as a plugin system with plugin generator

## Screenshots

![Plugins](https://github.com/tomatophp/filament-plugins/blob/master/arts/plugins.png)
![Tables](https://github.com/tomatophp/filament-plugins/blob/master/arts/tables.png)
![Generate](https://github.com/tomatophp/filament-plugins/blob/master/arts/generate.png)
![Create Col](https://github.com/tomatophp/filament-plugins/blob/master/arts/create-col.png)
![Table cols](https://github.com/tomatophp/filament-plugins/blob/master/arts/table-cols.png)

## Installation

```bash
composer require tomatophp/filament-plugins
```
after install your package please run this command

```bash
php artisan filament-plugins:install
```

By default the module classes are not loaded automatically. You can autoload your modules by adding merge-plugin to the extra section:

```json
"extra": {
    "laravel": {
        "dont-discover": []
    },
    "merge-plugin": {
        "include": [
            "Modules/*/composer.json"
        ]
    }
},
```

now you need to run this command to autoload your modules

```bash
composer dump-autoload
```

finally reigster the plugin on `/app/Providers/Filament/AdminPanelProvider.php`

```php
->plugin(\TomatoPHP\FilamentPlugins\FilamentPluginsPlugin::make())
```

## Usage

you can create a new plugin using just a command

```bash
php artisan filament-plugins:generate
```

or you can use the GUI to create a new plugin, after create a plugin you need to make sure that it's loaded on composer by run this command

```bash
composer dump-autoload
```

after create the plugin you can create a new table inside it and than run the migration generator to convert it to a migration file then you can use the GUI to generate resources, pages, widget or model, or you can easy use this commands

```bash
php artisan filament-plugins:model
php artisan filament-plugins:resource
php artisan filament-plugins:page
php artisan filament-plugins:widget
```

it will generate the files for you and you can use it directly, please note that you need to generate the model first than use other commands


## Publish Assets

you can publish config file by use this command

```bash
php artisan vendor:publish --tag="filament-plugins-config"
```

you can publish views file by use this command

```bash
php artisan vendor:publish --tag="filament-plugins-views"
```

you can publish languages file by use this command

```bash
php artisan vendor:publish --tag="filament-plugins-lang"
```

you can publish migrations file by use this command

```bash
php artisan vendor:publish --tag="filament-plugins-migrations"
```

## Support

you can join our discord server to get support [TomatoPHP](https://discord.gg/Xqmt35Uh)

## Docs

you can check docs of this package on [Docs](https://docs.tomatophp.com/plugins/laravel-package-generator)

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## Security

Please see [SECURITY](SECURITY.md) for more information about security.

## Credits

- [Tomatophp](mailto:info@3x1.io)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
