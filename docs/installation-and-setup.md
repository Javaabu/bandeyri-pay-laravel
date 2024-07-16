---
title: Installation & Setup
sidebar_position: 1.2
---

You can install the package via composer:

```bash
composer require javaabu/bandeyri-pay-laravel
```

# Publishing the config file

Publishing the config file is optional:

```bash
php artisan vendor:publish --provider="Javaabu\BandeyriPayLaravel\BandeyriPayLaravelServiceProvider" --tag="bandeyri-pay-laravel-config"
```

This is the default content of the config file:

```php
// TODO
```
