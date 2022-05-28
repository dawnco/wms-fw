<?php
/**
 * @author Dawnc
 * @date   2020-05-10
 */

namespace Wms\Fw;

use Throwable;
use Wms\Database\DatabaseException;
use Wms\Lib\Log;

class Fw
{
    public static $application = null;

    public $hook = null;
    public $route = null;

    public static function instance()
    {
        return self::$application = new self();
    }


    public function __construct()
    {
        date_default_timezone_set(Conf::get('app.timezone', 'PRC'));
    }


    public function run()
    {

        set_error_handler([$this, 'errorHandler']);

        $request = new Request();

        $responseCls = Conf::get('app.response.handler', Response::class);
        $response = new $responseCls();

        try {
            $body = $this->exec($request);
            $response->sendJson(0, null, $body);
        } catch (DatabaseException $e) {
            $response->sendJson($e->getCode(),
                Conf::get('app.env') == 'dev' ? $e->getMessage() : "DATABASE OPERATE ERROR", $e->getData());
            Log::error("%s %s", "DATABASE OPERATE ERROR", $e);
        } catch (WmsException $e) {
            $response->sendJson($e->getCode(), $e->getMessage(), $e->getData());
            Log::info("%s", $e);
        } catch (Throwable $e) {
            $response->status(500)->send($e->getMessage());
            Log::info("Exception %s", $e);
        }
    }

    private function exec($request)
    {

        $this->route = new Route();
        $this->hook = new Hook();
        $this->hook();

        $this->hook->handle('pre_control');

        $control = $this->route->getControl();
        $method = $this->route->getMethod();
        $param = $this->route->getParam();
        if (!class_exists($control)) {
            throw new WmsException($control . " File Not Found");
        }

        $classInstance = new $control($request);

        if (!method_exists($classInstance, $method)) {
            throw new WmsException($control . "->" . $method . "() Method Not Found");
        }

        $body = call_user_func_array(array($classInstance, $method), $param);

        $this->hook->handle('after_control');

        return $body;

    }

    public function shell($argv)
    {
        $name = $argv[1];

        if (class_exists($name)) {
            $clsName = $name;
        } else {
            $clsName = "\\App\\Shell\\" . $name;
        }


        if (!class_exists($clsName)) {
            throw new WmsException("$clsName SHELL 不存在");
        }
        $cls = new $clsName();
        return $cls->start(array_slice($argv, 2));
    }

    public function hook()
    {
        $hooks = Conf::get("hook");
        foreach ((array)$hooks as $preg => $hook) {
            if (preg_match("#^$preg$#i", $this->route->getUri())) {
                $this->hook->add($hook['weld'], [
                    new $hook['h'](),
                    isset($hook['m']) ? $hook['m'] : "hook"
                ], $hook['seq'], isset($hook['p']) ? $hook['p'] : []);
            }
        }
    }

    public function errorHandler($errno, $errstr, $errfile, $errline)
    {
        Log::error("PHP ERROR: %s in %s:%s",
            $errstr, $errfile, $errline);
    }

}
