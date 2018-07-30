<?php

namespace app\modules\v1\common;

use app\componments\auth\QueryParamAuth;
use app\componments\filter\VerbFilter;
use app\models\Auth;
use app\models\AuthGroup;
use app\models\User;
use yii\filters\ContentNegotiator;
use yii\filters\Cors;
use yii\rest\Controller;
use yii\web\Response;

class BaseController extends Controller
{
    public function behaviors()
    {
        $behaviors = parent::behaviors();
        $behaviors['authenticator']=[
            'class'=>QueryParamAuth::className(),
        ];
        $behaviors['contentNegotiator'] = [
            'class' => ContentNegotiator::className(),
            'formats' => [
                'application/json' => Response::FORMAT_JSON,
               // 'application/xml' => Response::FORMAT_XML,
            ],
        ];
        $behaviors['verbFilter']=[
            'class' => VerbFilter::className(),
            'actions' => $this->verbs(),
        ];
        $behaviors['cors']=[
            'class' => Cors::className(),

        ];

        return $behaviors;
    }

}