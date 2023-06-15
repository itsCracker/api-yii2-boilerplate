<?php

namespace controllers;


use Yii;
use yii\web\Response;
use yii\web\Controller;

class SiteController extends Controller
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            [
                'class'    =>  'yii\filters\ContentNegotiator',
                'only'     =>  ['resource', 'index'],
                'formats'  =>  [
                    'application/json' => Response::FORMAT_HTML,
                ],
            ],
        ];
    }

    public function actionIndex()
    {
        return "<h2 style='text-align: center;'>
        Auth </h2>";
    }

    public function actionResource()
    {  

        $path = Yii::getAlias('@app/');
        $openapi = \OpenApi\Generator::scan([$path.'devOps', $path.'apps/routes', $path.'modules', $path.'apps/models']);
        header('Content-Type: application/json');
        echo $openapi->toJson();
        die();
    }

}
