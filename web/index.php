<?php
// error_reporting(E_ALL ^ E_DEPRECATED);
// ini_set('display_errors', 1);
// ini_set('display_startup_errors', 1);
// error_reporting(E_ALL);
require(__DIR__ . '/../vendor/autoload.php');

// load .env
$env = __DIR__ . '/../';
(new Dotenv\Dotenv($env))->load();

if(getenv('APP_ENV') !== 'production') {
    defined('YII_DEBUG') or define('YII_DEBUG', true);
    defined('YII_ENV') or define('YII_ENV', 'dev');
}
define("API_HOST", getenv('API_HOST'));

require(__DIR__ . '/../vendor/yiisoft/yii2/Yii.php');

$config = require(__DIR__ . '/../config/web.php');

(new yii\web\Application($config))->run();
