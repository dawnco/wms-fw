<?php
/**
 * @author Dawnc
 * @date   2020-04-22
 */

namespace Wms\Lib;

use app\dict\ErrorCode;
use Wms\Fw\Exception;

class Sms
{
    /** 单个号码短信最小间隔时间 限制 60秒 */
    const SEND_NEXT_TIME = 60;

    /** 一天单个手机号最多发多少条 */
    const SEND_MAX_DAY = 10;

    /**
     * 短信有效期
     */
    const CODE_EXPIRE = 1800;

    public static function send($phone)
    {

        if (!$phone) {
            throw new Exception("mobile number is need", ErrorCode::SMS_SEND_ERROR);
        }

        $redis = Redis::getInstance();
        $last  = $redis->get('sms:last:' . $phone);
        $now   = time();
        if ($last && $now - $last < self::SEND_NEXT_TIME) {
            throw new Exception(sprintf("Sending too frequently, retrying after %d seconds!", self::SEND_NEXT_TIME), ErrorCode::SMS_SEND_ERROR);
        }

        // 同一个手机号每天发送限制发送10条短信
        $times = $redis->get('sms:stat:' . date("d", $now) . ":" . $phone);
        if ($times > self::SEND_MAX_DAY) {
            throw new Exception("The number of SMS messages for this mobile phone number has reached the upper limit today!", ErrorCode::SMS_SEND_ERROR);
        }

        //生成验证码
        $code = "";
        for ($i = 0; $i < 6; $i++) {
            $code .= rand(0, 9);
        }
        $redis->setex('sms:last:' . $phone, self::SEND_NEXT_TIME + 60, $now);
        $redis->incr('sms:stat:' . date("d", $now) . ":" . $phone);
        $redis->expire('sms:stat:' . date("d", $now) . ":" . $phone, 3600 * 24 + 3600);
        $redis->setex('sms:code:' . $phone, self::CODE_EXPIRE, $code);
        self::sendSms($phone, $code);

        return $code;
    }

    public static function verify($phone, $code)
    {

        $redis = Redis::getInstance();
        $saved = $redis->get('sms:code:' . $phone);

        if (!$phone || !$saved || !$code) {
            return false;
        }

        return $saved == $code;
    }

    /**
     * 发短信
     * @param $mobile
     * @param $code
     */
    private static function sendSms($mobile, $code)
    {

        $redis = Redis::getInstance();

        $provider = $redis->get('config:smsProvider');

        $cls = "\\vendor\\smsProvider\\$provider";

        if (!class_exists($cls)) {
            $cls = "\\vendor\\smsProvider\\TianYiSMSProvider";
        }

        $sms = new $cls();

        return $sms->send($mobile, $code);
    }

}
