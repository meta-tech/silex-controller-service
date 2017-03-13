<?php
namespace MetaTech\Silex\Provider;

use Pimple\Container;
use Pimple\ServiceProviderInterface;

/*!
 * @package     MetaTech\Silex\Provider
 * @class       ControllerServiceProvider
 * @implements  Pimple\ServiceProviderInterface
 * @author      a-Sansara
 * @date        2017-03-13 16:40:56 CET
 */
class ControllerServiceProvider implements ServiceProviderInterface
{
    /*! @private @var str $name */
    private $name;
    /*! @private @var str $ns */
    private $ns;
    /*! @private @var str $route */
    private $route;
    /*! @private @var [mixed] $args */
    private $args;

    /*
     * ControllerServiceProvider constructor
     *
     * @constructor
     * @public
     * @param       str     $class      a controller class
     * @param       [mixed] $args       controller arguments
     * @param       str     $route      controller entry point
     * @param       str     $namespace  controller service namespace in application
     */
    public function __construct($class, $args=[], $route=null, $namespace='ctrl.')
    {
        if (class_exists($class)) {
            $this->name  = $class;
            $this->ns    = $namespace . (new \ReflectionClass($class))->getShortName();
            $this->route = $route;
            $this->args  = $args;
        }
    }

    /*!
     * create a service dedicated to the controller and mount the controller's routes
     * 
     * @method      register
     * @public
     * @param       Pimple\Container    $app
     */
    public function register(Container $app)
    {
        if (!is_null($this->name)) {
            $class     = $this->name;
            $args      = $this->args;
            $app[$this->ns] = function() use ($class, $args) {
                return new $class(...$args);
            };
            
            if (!is_null($this->route)) {
                $imp = class_implements($class);
                if (isset($imp['Silex\\Api\\ControllerProviderInterface'])) {
                    $app->mount($this->route, $app[$this->ns]->connect($app));
                }
            }
        }
    }
}
