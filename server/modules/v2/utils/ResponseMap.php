<?php

namespace app\modules\v1\utils;

use yii\base\UserException;

class ResponseMap
{
    protected static $map = [
        //登录类
        '200001'=>'商户不存在',
        '200002'=>'商户登录密码错误',
        '200003'=>'该微信已经绑定了一个帐号',
        '200004'=>'该用户已经绑定了微信帐号',
        '200005'=>'该帐号已封停,请联系客服人员',
        '200006'=>'该帐号已被暂停使用,请联系客服人员',
        //API接口类
        '300001'=>'access-token错误',
        '300002'=>'该接口只能用:#verb请求',
        //资源请求大类
        //设备类
        '410001'=>'设备不存在',
        //门店类
        '420001'=>'门店不存在',
        //商户类
        '430001'=>'商户不存在',
        //流水类
        '440001'=>'订单不存在',
        //推送类
        '500001'=>'设备令牌不能为空',
        //审核类
        '600001'=>'非驳回状态,无法修改',
        '600002'=>'审核单不存在',
        //900001为系统错误
    ];

    public static function Map($code)
    {
        if(!array_key_exists($code,self::$map))
        {
            throw new UserException("业务码映射不存在","900001");
        }

        return self::$map[$code];
    }
}