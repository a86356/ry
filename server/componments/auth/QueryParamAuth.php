<?php
/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

namespace app\componments\auth;

use app\models\Auth;
use app\models\AuthGroup;
use app\models\GroupAuth;
use app\models\User;
use app\utils\ResponseMap;
use yii\base\UserException;
use yii\filters\auth\AuthMethod;

/**
 * QueryParamAuth is an action filter that supports the authentication based on the access token passed through a query parameter.
 *
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @since 2.0
 */
class QueryParamAuth extends AuthMethod
{
    /**
     * @var string the parameter name for passing the access token
     */
    public $tokenParam = 'access-token';

    /**
     * @inheritdoc
     */
    public function authenticate($user, $request, $response)
    {

        $accessToken = $request->post($this->tokenParam);

        if(empty($accessToken)){
            $accessToken = $request->get($this->tokenParam);
        }

        if (is_string($accessToken) && !empty($accessToken)) {

            $identity = $user->loginByAccessToken($accessToken, get_class($this));

            if ($identity !== null) {

                //rbac 处理
             //   $this->handleRbac();


                return $identity;
            }
        }
        if ($accessToken !== null) {
            $this->handleFailure($response);
        }

        return null;
    }

    public function handleFailure($response)
    {
        ApiException(ResponseMap::Map('300001'),'300001');
    }


    public function handleRbac(){
        $re=\Yii::$app->getRequest()->pathInfo;
        $arr=explode('/',$re);
        $module=$arr[0];
        $controller=$arr[1];
        $action=$arr[2];
        if($action=='login'){
            return;
        }

        $auth=Auth::findOne(['module_name'=>$module,'auth_c'=>$controller,'auth_a'=>$action]);
        if(!$auth){
            ApiException("没有找到该权限","900000");
        }
        $auth_id=$auth->auth_id;

        $group_id=User::findOne(\Yii::$app->getUser()->getId())->userGroups->group_id;

        $authgroups=GroupAuth::findOne(['auth_id'=>$auth_id,'group_id'=>$group_id]);
        if(!$authgroups){
            ApiException("该用户所属组没有权限","900000");
        }
    }

}
