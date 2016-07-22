# Yandex Translate Provider for Laravel 5

[![Latest Stable Version](https://poser.pugx.org/bluora/laravel-yandex-translate/v/stable.svg)](https://packagist.org/packages/bluora/laravel-yandex-translate) [![Total Downloads](https://poser.pugx.org/bluora/laravel-yandex-translate/downloads.svg)](https://packagist.org/packages/bluora/laravel-yandex-translate) [![Latest Unstable Version](https://poser.pugx.org/bluora/laravel-yandex-translate/v/unstable.svg)](https://packagist.org/packages/bluora/laravel-yandex-translate) [![License](https://poser.pugx.org/bluora/laravel-yandex-translate/license.svg)](https://packagist.org/packages/bluora/laravel-yandex-translate)

### This package is compatible with Laravel 5.*

## Installation

Install using composer:

```
composer require bluora/laravel-yandex-translate dev-master
```

Add class to the service provider list.

In `config/app.php`:

Update the providers section with:

```php
'providers' => [
    ...
    Bluora\Yandex\Providers\YandexTranslateServiceProvider::class,
)
```

Update the aliases section with:

```php
'aliases' => [
    ...
    'YandexTranslate'      => 'Bluora\Yandex\Facades\YandexTranslateFacade',
]

```

In `config/services.php`:

Add a new third party entry:

```php
return [
    ...
    'yandex-translate' => [
        'key' => env('YANDEX_TRANSLATE_KEY', ''),
    ]
];
```
You can then add YANDEX_TRANSLATE_KEY=myapihere to your .env file.

## Usage

```php
echo YandexTranslate::translate('Hello world', 'en', 'ru');
```

Would output:
```
Привет мир
```

##Yandex API Key

You can get your API key (here)[http://api.yandex.com/key/form.xml?service=trnsl].
