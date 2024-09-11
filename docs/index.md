# Laravel-Synapse
## AI agents for all!
Laravel Synapse gives you the ability to create AI agents. Inspired by Lanchain 0.1 this package aims to simplify intgrating AI agents in to your laravel aplication and allowing you to run them at scale.

## Installation
> **Requires [PHP 8.1+](https://php.net/releases/)**

First, via the [Composer](https://getcomposer.org/) package manager:

```bash
composer require use-the-fork/laravel-synapse
```

Next, execute the install command:

```bash
php artisan synapse:install
```


## Get Started
### 1. Set up your `.env` with the following Elasticsearch settings:
```dotenv
ES_AUTH_TYPE=http
ES_HOSTS="http://localhost:9200"
ES_USERNAME=

ES_PASSWORD=
ES_CLOUD_ID=
ES_API_ID=
ES_API_KEY=
ES_SSL_CA=
ES_INDEX_PREFIX=my_app
# prefix will be added to all indexes created by the package with an underscore
# ex: my_app_user_logs for UserLog.php model
ES_SSL_CERT=
ES_SSL_CERT_PASSWORD=
ES_SSL_KEY=
ES_SSL_KEY_PASSWORD=
# Options
ES_OPT_ID_SORTABLE=false
ES_OPT_VERIFY_SSL=true
ES_OPT_RETRIES=
ES_OPT_META_HEADERS=true
ES_ERROR_INDEX=
```

For multiple nodes, pass in as comma-separated:
```dotenv
ES_HOSTS="http://es01:9200,http://es02:9200,http://es03:9200"
```

::: details Cloud config .env example
1.5: In config/database.php, add the elasticsearch connection:
```php
'elasticsearch' => [
    'driver'       => 'elasticsearch',
    'auth_type'    => env('ES_AUTH_TYPE', 'http'), //http or cloud
    'hosts'        => explode(',', env('ES_HOSTS', 'http://localhost:9200')),
    'username'     => env('ES_USERNAME', ''),
    'password'     => env('ES_PASSWORD', ''),
    'cloud_id'     => env('ES_CLOUD_ID', ''),
    'api_id'       => env('ES_API_ID', ''),
    'api_key'      => env('ES_API_KEY', ''),
    'ssl_cert'     => env('ES_SSL_CA', ''),
    'ssl'          => [
        'cert'          => env('ES_SSL_CERT', ''),
        'cert_password' => env('ES_SSL_CERT_PASSWORD', ''),
        'key'           => env('ES_SSL_KEY', ''),
        'key_password'  => env('ES_SSL_KEY_PASSWORD', ''),
    ],
    'index_prefix' => env('ES_INDEX_PREFIX', false),
    'options'      => [
        'allow_id_sort'    => env('ES_OPT_ID_SORTABLE', false),
        'ssl_verification' => env('ES_OPT_VERIFY_SSL', true),
        'retires'          => env('ES_OPT_RETRIES', null),
        'meta_header'      => env('ES_OPT_META_HEADERS', true),
    ],
    'error_log_index' => env('ES_ERROR_INDEX', false),
],
```
:::

### 2. If packages are not autoloaded, add the service provider:
For **Laravel 10 and below**:

```php
//config/app.php
'providers' => [
    ...
    ...
    PDPhilip\Elasticsearch\ElasticServiceProvider::class, // [!code highlight]
    ...
```
For **Laravel 11**:
```php
//bootstrap/providers.php
<?php
return [
    App\Providers\AppServiceProvider::class,
    PDPhilip\Elasticsearch\ElasticServiceProvider::class, // [!code highlight]
];
```
Now, you're all set to use Elasticsearch with Laravel as if it were native to the framework.
