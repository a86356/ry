<?php

return [



    'components' => [
        'response'=>[
            'class' => 'yii\web\Response',
            'format'=>'json',
            'formatters'=>[
                'json'=>'app\componments\format\ApiJsonFormatResponse',
            ],
        ],
    ],
        //oss:hypay/test-dir/*
    //oss-cn-hangzhou.aliyuncs.com
    'params'=>[

        'pagesize'=>50,
        //APP直传阿里云OSS
        'oss'=>[
            "AccessKeyID"=> "",
            "AccessKeySecret"=> "",
            "RoleArn"=> "",
            "BucketName"=> "",
            "Endpoint"=> "",
            "TokenExpireTime"=> "",
            'PolicyConfig'=>'
                {
                  "Statement": [
                    {
                      "Action": [
                        "oss:*"
                      ],
                      "Effect": "Allow",
                      "Resource": ["acs:oss:*:*:hypay/verify-dir/*"]
                    }
                  ],
                  "Version": "1"
                }',
        ],
    ]
];