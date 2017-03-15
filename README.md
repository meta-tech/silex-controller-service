
# MetaTech Silex ControllerServiceProvider

A service provider for [ Silex ](http://silex.sensiolabs.org) for managing DI and mounting on controllers.

### Requirements

PHP 7.0

### Install

The package can be installed using [ Composer ](https://getcomposer.org/).
```
composer require meta-tech/silex-controller-service
```

Or add the package to your `composer.json`.

```
"require": {
    "meta-tech/silex-controller-service" : "@dev"
}
```

## Usage

The provider will create a service relative to a Controller instance builded with its dependencies.  
If the controller implements the `Silex\Api\ControllerProviderInterface` the provider will also
mount the controller 's routes to the defined routing entry point

## Example

Admit you have a controller Test with dependencies on instanciation :

```php
class Test implements ControllerProviderInterface
{
    public function __construct(Application $app)
    {
        // do stuff
    }
```

You can use the ControllerServiceProvider to create a service to manage your
controller class instanciation :

```php

use MetaTech\Silex\Provider\ControllerServiceProvider;
use Acme\Ctrl\Test;
...

$app->register(new ControllerServiceProvider(Test::class, [$app], '/test', 'ctrl.'));

```
*first parameter* is your **controller class**   
*second parameter* is an array of your **controller depencies** (default [])  
*third parameter* define your controller **routing entry point** (default null : disabling mounting controller's routes)  
*fourth parameter* define your **service 's namespace** to access your controller (default 'ctrl.')  

The name of the registering service is the `given namespace` followed by your `controller class shortname`  

With the previous example `$app['ctrl.Test']` is now available and return your controller instance.

the `connect` method of your controller can now benefits of this service to define appropriate routes, like that :

```php
class Test implements ControllerProviderInterface
{
    ...

    public function connect(Application $app)
    {
        $collection = $app['controllers_factory'];
        $_          = 'ctrl.Test';

        $collection->match('/'    , "$_:index");
        $collection->match('/test', "$_:test");

        return $collection;
    }
}
```

Controller 's routes are mount on the defined routing entry point (third parameter);

see source code of `MetaTech\Silex\Ws\Controller` in [ meta-tech\silex-core ](https://github.com/meta-tech/silex-core)  for an advance controller architecture.


### License

The project is released under the MIT license, see the LICENSE file.
