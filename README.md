# Laravel API Connector

Package to consume external API in your Laravel projects.

## Prerequisite
1. This package requires a basic understanding of [Guzzle http](https://packagist.org/packages/guzzlehttp/guzzle)
2. PHP >= 7.3

## Installation
The recommended way to install this package through Composer.

```
composer require coloredcow/laravel-api-connector
```

## Usage

1. Run database migration
    ```
    php artisan migrate
    ```
1. Add following information in newly created table `a_p_i_connectors`
    ```
    1. client_id
    2. client_secret
    3. grant_type
    4. base_url (API base URL, Ex: https://example.com)
    5. auth_url (Ex: https://example.com/oauth/token)
    ```
1. Use the trait `APIConnector`
1. Make API calls
    1. Get Call (No need to add API base URL)
    ```
    $this->makeApiCall('your-end-point'); (Ex: $this->makeApiCall('get-user'))
    ```
    1. Post Call (No need to add API base URL)
    ```
    $data = array(...); // API payload
    $this->makeApiCall('your-end-point', 'post', $data);
    ```