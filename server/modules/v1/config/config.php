<?php

return [



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