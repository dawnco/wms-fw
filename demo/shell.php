<?php
/**
 * @author Dawnc
 * @date   2020-12-08
 */

use Wms\Fw\Conf;
use Wms\Fw\Fw;


$dir = __DIR__;

require $dir . "/vendor/autoload.php";
require dirname($dir) . "/vendor/autoload.php";

define("APP_PATH", $dir . "/App");

Conf::set('app', include APP_PATH . "/Conf/app.conf.php");
$fw = new Fw();
$fw->shell($argv);

