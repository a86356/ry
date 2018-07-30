<?php

namespace app\componments\format;

use yii\helpers\Json;
use yii\web\JsonResponseFormatter;
use yii\web\Response;

class ApiJsonFormatResponse extends JsonResponseFormatter
{
    //格式化响应
    protected function formatJson($response)
    {
        $response->getHeaders()->set('Content-Type', 'application/json; charset=UTF-8');
        if ($response->data !== null) {
            $options = $this->encodeOptions;
            if ($this->prettyPrint) {
                $options |= JSON_PRETTY_PRINT;
            }

            if($response->getIsSuccessful())
            {
                $response->data=[
                    'status'=>"success",
                    'code'=>'000000',
                    'data'=>$response->data
                ];
            }
            else
            {
                $response->setStatusCode(200);
                $response->data = [
                    'status'=>'fail',
                    'code'=>"{$response->data['code']}",
                    'msg'=>$response->data['message'],
                ];
            }
            $response->content = Json::encode($response->data, $options);
        }
    }
}