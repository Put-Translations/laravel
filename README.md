# Put Translations for Laravel

Put Translations is a Laravel package that simplifies the process of managing and synchronizing translations for your multi-language applications. It integrates seamlessly with the Put.sh translation service to automate the translation process.

## Features

- Automatic extraction of translatable strings from your Laravel project
- Easy synchronization with the Put.sh translation service
- Support for multiple target languages
- Tracking of translation usage in your application
- Command-line interface for easy management

## Installation

1. Install the package via Composer:

   ```bash
   composer require put-translations/laravel
   ```

2. (Optionally) Publish the configuration file:

   ```bash
   php artisan vendor:publish --provider="PutTranslations\Laravel\TranslationSyncServiceProvider" --tag="config"
   ```

3. Add your Put.sh API key to your `.env` file:

   ```ini
   PUT_TRANSLATIONS_API_KEY=your_api_key_here
   ```

## Configuration

The package uses environment variables for configuration. Add the following to your `.env` file:

```ini
PUT_API_KEY=your_api_key_here
PUT_API_URL=
```

Here's what each variable does:

- `PUT_API_KEY`: Your API key from Put.sh
- `PUT_API_URL`: (Optional) If you use a self hosted instance then update this to your URL

You can adjust these values according to your project's needs.

## Usage

### Extracting and Syncing Translations

To extract translatable strings from your project and sync them with Put.sh, run:

```bash
php artisan put:sync
```

This command will:

1. Scan your project for translatable strings
2. Send these strings to Put.sh for translation
3. Save the translated strings back to your project

### Using Translations in Your Code

You can use the standard Laravel translation functions in your code:

```php
__('Hello, World!');
trans('Welcome to our app');
@lang('Thank you for your purchase');
```

Or use the custom `t()` helper function:

```php
t('This string will be translated');
```

In Blade templates:

```blade
{{ t('Translate me') }}
```

## Getting an API Key

To use this package, you need an API key from Put.sh. Follow these steps:

1. Go to [put.sh](https://put.sh)
2. Sign up for an account or log in
3. Navigate to your projector create a new project
4. Go to the Configuration tab
5. Copy the API key and add it to your `.env` file

## Contributing

Contributions are welcome! Please feel free to submit a Pull Request.

## License

This package is open-sourced software licensed under the MIT license.

