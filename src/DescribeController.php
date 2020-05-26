<?php

namespace p4it\rest\server;

use p4it\rest\server\models\Describe;
use p4it\rest\server\models\DescribeInfo;
use p4it\rest\server\models\DescribeServer;
use pappco\yii2\helpers\ArrayHelper;
use Symfony\Component\Yaml\Yaml;
use Yii;
use yii\base\Controller;
use yii\base\InvalidRouteException;

class DescribeController extends Controller
{
    public $version = '1.0.0';

    public function actionIndex()
    {

        $describe = new Describe();
        $describe->info = new DescribeInfo(['title' => Yii::$app->name, 'version' => $this->version]);
        $describe->servers[] = new DescribeServer(['url' => Yii::$app->urlManager->getHostInfo()]);

        /** @var \yii\web\UrlRule $rule */
        foreach (\Yii::$app->urlManager->rules as $rule) {
            if (!($rule instanceof \yii\rest\UrlRule)) {
                continue;
            }

            try {
                \Yii::$app->requestedRoute = reset($rule->controller) . '/describe';
                \Yii::$app->request->headers->removeAll();
                \Yii::$app->request->headers->add('Content-Type', 'application/json');

                ArrayHelper::mergeInto($describe->paths,\Yii::$app->runAction(reset($rule->controller) . '/describe', ['onlyPath'=>true]));
            } catch (InvalidRouteException $exception) {

            } catch (\Exception $exception) {

            }

        }

        \Yii::$app->response->format = \yii\web\Response::FORMAT_RAW;

        $describeArray = $describe->toArray();
        ArrayHelper::removeValue($describeArray, null, true);
        return Yaml::dump($describeArray, 20, 2);
    }
}
