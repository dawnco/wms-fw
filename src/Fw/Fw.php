<?php
/**
 * @author Dawnc
 * @date   2020-05-10
 */

namespace Wms\Fw;

class Fw
{
    public static $application = null;

    public $hook  = null;
    public $route = null;

    public static function instance()
    {
        return self::$application = new self();
    }


    public function __construct()
    {
        date_default_timezone_set('PRC');

    }


    public function run()
    {
        $response = new Response();
        try {
            $body = $this->exec();
            $response->sendJson(0, null, $body);
        } catch (Exception $e) {
            $response->sendJson($e->getCode(), $e->getMessage());
        } catch (\Exception $e) {
            $response->status(500)->send($e->getMessage());
        }
    }

    private function exec()
    {
        $this->route = new Route();
        $this->hook  = new Hook();
        $this->hook();

        $this->hook->handle('pre_control');

        $control = $this->route->getControl();
        $method  = $this->route->getMethod();
        $param   = $this->route->getParam();
        if (!class_exists($control)) {
            throw new Exception($control . " File Not Found");
        }

        $classInstance = new $control();

        if (!method_exists($classInstance, $method)) {
            throw new Exception($control . "->" . $method . "() Method Not Found");
        }

        $body = call_user_func_array(array($classInstance, $method), $param);

        $this->hook->handle('after_control');

        return $body;

    }

    public function shell()
    {
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

}