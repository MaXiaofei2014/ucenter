<?php

namespace App\Http\Controllers\Api\V1;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Controllers\Api\V1\ApiController;

use Cache;
use Queue;
use Config;
use App\Services\Api;
use App\Model\User;
use PhpSms;
use App\Jobs\Sms;
use App\Exceptions\ApiException;

class SmsController extends ApiController
{
    /**
     * 发送验证码
     *
     * @param string $phone 手机号
     * @return apiReturn
     */
    public function postCode(Request $request)
    {
        $this->_beforeSend($request->phone);

        $code = rand(100000, 999999);
        $content = '您好，您的验证码是：' . $code;

        Cache::put(Config::get('cache.sms.code') . $request->phone, $code, 5);

        PhpSms::queue(false);
        $result = PhpSms::make()->to($request->phone)->content($content)->send();

        if (true === $result['success']) {

            Queue::push(new Sms(parent::getAppId(), parent::getUserId(), $request->phone, $content));

            return Api::apiReturn(SUCCESS, '发送成功');
        } else {
            return Api::apiReturn(ERROR, '发送失败', $result['logs']);
        }
    }

    /**
     * 发送内容短信
     *
     * @param string $phone 手机号
     * @return apiReturn
     */
    public function sendContent(Request $request)
    {
        $this->beforeSend($request);

        PhpSms::queue(false);
        $result = PhpSms::make()->to($request->phone)->content($request->content)->send();

        if (true === $result['success']) {
            return Api::apiReturn(SUCCESS, '发送成功');
        } else {
            return Api::apiReturn(ERROR, '发送失败', $result['logs']);
        }
    }

    /**
     * 验证短信
     *
     * @param string $phone 手机号
     * @param string $code 验证码
     * @return apiReturn
     */
    public function putCode(Request $request)
    {
        $this->apiValidate($request->only('phone', 'code'), ['phone' => 'required|size:11', 'code' => 'required|size:6']);

        if (Cache::get(Config::get('cache.sms.code') . $request->phone) != $request->code) {
            return Api::apiReturn(ERROR, '验证失败，验证码不匹配');
        } else {
            Cache::put(Config::get('cache.sms.validated') . $request->phone, 1, 1);
            return Api::apiReturn(SUCCESS, '验证成功');
        }
    }

    /**
     * 发送前的校验
     *
     * @param string $phone 手机号
     * @return true/ApiException
     */
    private function _beforeSend($phone)
    {
        $this->apiValidate(['phone' => $phone], ['phone' => 'required|size:11']);

        // 手机号和用户id两种方式计数
        $count = empty(parent::getUserId()) ? Cache::get(Config::get('cache.sms.count.phone') . $phone) :
            Cache::get(Config::get('cache.sms.count.user_id') . parent::getUserId());

        if (is_null($count)) {
            empty(parent::getUserId()) ? Cache::put(Config::get('cache.sms.count.phone') . $phone, 1, 60) :
                Cache::put(Config::get('cache.sms.count.user_id') . parent::getUserId(), 1, 60);
        } else {
            // if (!in_array($phone, Config::get('phpsms.whiteList')) && $count > 3) {
            if (false === strpos(env('SMS_WHITE_LIST'), $phone) && $count > 3) {
                throw new ApiException('超过每小时三次限制');
            }
            empty(parent::getUserId()) ? Cache::put(Config::get('cache.sms.count.phone') . $phone, ++$count, 60) :
                Cache::put(Config::get('cache.sms.count.user_id') . parent::getUserId(), ++$count, 60);
        }

        return true;
    }
}

