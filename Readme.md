# Laravel Database Backup

A Laravel 5 and Laravel 6 library that creates backup of your database with a single command

## Installation
```
composer require salmanzafar/laravel-db-backup
```
## Features

* Easy configuration db backup in a single command
* Storage Customization (e.g s3(s3 support is in development), local etc)
* Email about DbBackup completion (in development).

## Enable the package (Optional)
This package implements Laravel's auto-discovery feature. After you install it the package provider and facade are added automatically for laravel >= 5.5.

## Configuration
Publish the configuration file

This step is required only if you want to make changes in config file

```
php artisan vendor:publish --provider="Salman\DbBackup\DbBackupServiceProvider"
```

#### Config File located at ``config/dbbackup.php``

```php
<?php

/**
 * Db Backup configuration file to setup disk and folder name for db backup
 */

return [
    'disk' => 'public', // disk e.g local, public, s3 etc
    'visibility' => 'public', // leave it null for private
    'folder' => 'dbbackup' // folder name for backup
];
```
## Usage

After publishing the configuration file just run the below command

```
php artisan db:backup
```

Just it, Now in ```storage/app/yourbackupfoldername``` you should have your ```db dump```.

#### This package currently support ``Local Storage backup``
Tested on ```Laravel 6.1.0``` and ```Php 7.3```
