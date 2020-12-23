<?php
/**
 * @author Dawnc
 * @date   2020-12-08
 */

use Wms\Fw\Conf;
use Wms\Fw\Fw;


$dir = dirname(dirname(__DIR__));

require $dir . "/vendor/autoload.php";

define("APP_PATH", dirname(__DIR__));

Conf::set('App', include APP_PATH . "/Conf/app.conf.php");
Conf::set('route', include APP_PATH . "/Conf/route.conf.php");
$fw = new Fw();
$fw->run();

