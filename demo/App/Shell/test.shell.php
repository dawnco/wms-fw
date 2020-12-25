<?php
/**
 * @author Dawnc
 * @date   2020-12-24
 */


//$db = \Wms\Fw\Db::instance();
//
//$row = [
//    'appId'    => 100,
//    'deviceId' => 100,
//];
//$db->insert('risk_logs', $row);
//$db->update('risk_logs', ['deviceId' => 200], ['id' => 55797]);
//$db->delete('risk_logs', ['id' => 55796]);
//$db->insertBatch('risk_logs', [$row]);
//$r = $db->getLine('SELECT * FROM risk_logs WHERE id = ?', [55805]);
//shell_log("this is shell log");


$cls = new \App\Lib\HttpUtil("https://11.emj8.com/22");
$r   = $cls->setHeader("Content-Type:application/json")->setMethod("POST")->setData("xx11xx")->request();
dump($r);
