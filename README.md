# Laravel permission-generator

permission-generator scans your project `resources/view/` and `app/` folder to find `@can(...)`, `Gate::allow(...)`
functions, then it create keys based on first parameter value and insert into json permission file.

### Installation

You just have to require the package

```sh
$ composer require marcogeorge7/permission-generator
```

This package register the provider automatically,
[See laravel package discover](https://laravel.com/docs/5.5/packages#package-discovery).


### Usage
First you have to create json files in resource to save permission in it:
```
app/
  resources/
    role/
      permissions.json
```
second, publish the configuration file.

```
php artisan vendor:publish --provider="PermissionGenerator\Framework\GeneratorServiceProvider"
```

### Output
`generator:update` command will scan your code to identify new permission keys, then it'll update json file on `app/resources/role/` folder appending this keys.

```json
{
    "access_index": "can access all",
    "create_user": "can create user",
    "update_user": ""
}
```

if for any reason artisan can't find `generator:update` command, you can register the provider manually on your `config/app.php` file:

```php
return [
    ...
    'providers' => [
        ...
        PermissionGenerator\Framework\GeneratorServiceProvider::class,
        ...
    ]
]
```


### Customization
You can change the default path of views to scan and the output of the json permission file.

### Todo
- auto create value as a description of permission in json object
- insert permission in database table permission as defult and can replace it with config file
