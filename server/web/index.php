<?php



//跨域

if(isset($_SERVER["HTTP_ORIGIN"])) {
    header('Access-Control-Allow-Origin:'.$_SERVER["HTTP_ORIGIN"]);
}

header('Access-Control-Allow-Methods:OPTIONS, GET, POST'); // 允许option，get，post请求
header('Access-Control-Allow-Headers:x-requested-with'); // 允许x-requested-with请求头
header('Access-Control-Max-Age:86400'); //
header('Access-Control-Allow-Credentials:true');
header('Access-Control-Allow-Credentials:true');
header("Access-Control-Allow-Headers:Content-Type");





// comment out the following two lines when deployed to production
defined('YII_DEBUG') or define('YII_DEBUG', true);
defined('YII_ENV') or define('YII_ENV', 'dev');


require(__DIR__ . '/../functions.php');

require __DIR__ . '/../vendor/autoload.php';
require __DIR__ . '/../vendor/yiisoft/yii2/Yii.php';

$config = require __DIR__ . '/../config/web.php';

(new yii\web\Application($config))->run();
