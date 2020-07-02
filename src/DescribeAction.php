<?php

namespace p4it\rest\server;

use Closure;
use Illuminate\Support\Str;
use p4it\rest\server\models\Describe;
use p4it\rest\server\models\DescribeInfo;
use p4it\rest\server\models\DescribeOperation;
use p4it\rest\server\models\DescribeParameter;
use p4it\rest\server\models\DescribeRequestBody;
use p4it\rest\server\models\DescribeResource;
use p4it\rest\server\models\DescribeResponse;
use p4it\rest\server\models\DescribeSchema;
use p4it\rest\server\models\DescribeSearchResource;
use p4it\rest\server\models\DescribeServer;
use p4it\rest\server\models\parameter\IndexParameter;
use p4it\rest\server\models\responses\CreateResponses;
use p4it\rest\server\models\responses\DeleteResponses;
use p4it\rest\server\models\responses\DescribeResponses;
use p4it\rest\server\models\responses\IndexResponses;
use p4it\rest\server\models\responses\UpdateResponses;
use p4it\rest\server\models\responses\ValuesResponses;
use p4it\rest\server\models\responses\ViewResponses;
use pappco\yii2\helpers\ArrayHelper;
use Symfony\Component\Yaml\Yaml;
use Yii;
use yii\base\Action;
use yii\helpers\Inflector;
use yii\web\UrlRuleInterface;

class DescribeAction extends Action
{
    public $version = '1.0.0';

    /**
     * @var Closure|DescribeParameter[]
     */
    public $parameters;
    /**
     * @var Closure|DescribeResponse[]
     */
    public $responses;
    /**
     * @var Closure|DescribeRequestBody[]
     */
    public $requestBodies;

    public function run($onlyPath = false)
    {
        $actions = $this->getActions();

        $describe = new Describe();
        $describe->info = new DescribeInfo(['title' => Yii::$app->name, 'version' => $this->version]);
        $describe->servers[] = new DescribeServer(['url' => Yii::$app->urlManager->getHostInfo()]);

        foreach ($this->getUrlRules() as $urlRule) {

            $path = $describe->getPath($urlRule->name);
            $actionId = last(explode('/', $urlRule->route));

            if (!in_array($actionId, $actions, true)) {
                continue;
            }

            foreach ($urlRule->verb as $method) {

                $operation = new DescribeOperation([
                    'method' => $method,
                ]);

                $operation->addResponses($this->getResponses($actionId));
                preg_match_all('/<([\w._-]+):?([^>]+)?>/', $urlRule->name,$matches);
                $operation->addParameters($this->getParameters($actionId, $matches[1]??[]));
                if (strtolower($method) !== 'get') {
                    $operation->requestBody = $this->getRequestBody($actionId);
                }

                $path->addOperation($operation);
            }
        }

        $describeArray = $describe->toArray();
        if($onlyPath) {
            $describeArray = $describeArray['paths'];

            return $describeArray;
        }

        ArrayHelper::removeValue($describeArray, null, true);
        return Yaml::dump($describeArray, 20, 2);
    }

    private function getActions(): array
    {
        /** @var ActiveController $controller */
        $controller = $this->controller;

        $actions = array_filter(get_class_methods($controller), static function ($method) {
            return strpos($method, 'action') === 0 && strpos($method, 'actions') !== 0;
        });

        $actions = array_map(static function ($action) {
            return Inflector::camel2id(Str::replaceFirst('action', '', $action));
        }, $actions);

        return ArrayHelper::merge($actions, array_keys($controller->actions()));
    }

    /**
     * @return \yii\web\UrlRule[]
     */
    private function getUrlRules()
    {
        $baseUrl = Str::replaceLast('/describe', '', Yii::$app->requestedRoute);

        $urlRule = array_filter(Yii::$app->urlManager->rules, static function (UrlRuleInterface $urlRule) use ($baseUrl) {
            if (!($urlRule instanceof \yii\rest\UrlRule)) {
                return false;
            }
            return in_array($baseUrl, $urlRule->controller, true);
        });

        if (!$urlRule) {
            return [];
        }

        /** @var UrlRule $urlRule */
        $urlRule = reset($urlRule);

        $ruleKey = array_search($baseUrl, $urlRule->controller, true);

        return $urlRule->getRules()[$ruleKey];
    }

    /**
     * @param $actionId
     * @return DescribeResponse[]
     */
    private function getResponses($actionId): array
    {
        return $this->responses()[$actionId] ?? [new DescribeResponse([
                'statusCode' => 200,
                'description' => 'Default response'
            ])];
    }

    private function responses():array
    {
        if (is_array($this->responses)) {
            return $this->responses;
        }

        /** @var ActiveController $controller */
        $controller = $this->controller;
        $modelClass = $controller->modelClass;
        $searchModelClass = $controller->searchModelClass ?? $modelClass;

        $responses = [
            'index' => IndexResponses::get($modelClass, $searchModelClass),
            'create' => CreateResponses::get($modelClass),
            'update' => UpdateResponses::get($modelClass),
            'delete' => DeleteResponses::get($modelClass),
            'view' => ViewResponses::get($modelClass),
            'values' => ValuesResponses::get($modelClass),
            'describe' => DescribeResponses::get($controller),
        ];

        if ($this->responses instanceof Closure) {
            $responses = call_user_func($this->responses, $responses, $controller);
        }

        return $responses;
    }

    private function getParameters($actionId, $placeholders): array
    {
        $parameters = [];
        foreach ($placeholders as $placeholder) {
            $parameters[] = new DescribeParameter([
                'in' => 'path',
                'name' => $placeholder,
                'required' => true,
                'schema' => new DescribeSchema(['type' => 'string'])
            ]);
        }

        return ArrayHelper::merge($this->parameters()[$actionId] ?? [], $parameters);
    }

    private function parameters():array
    {
        if (is_array($this->parameters)) {
            return $this->parameters;
        }
        /** @var ActiveController $controller */
        $controller = $this->controller;
        $modelClass = $controller->modelClass;
        $parameters = [
            'index' => IndexParameter::getParameters($modelClass),
        ];


        if ($this->parameters instanceof Closure) {
            $parameters = call_user_func($this->parameters, $parameters, $controller);
        }

        return $parameters;
    }

    private function getRequestBody($actionId)
    {
        return $this->requestBodies()[$actionId] ?? null;
    }

    private function requestBodies():array
    {
        if (is_array($this->requestBodies)) {
            return $this->requestBodies;
        }

        /** @var ActiveController $controller */
        $controller = $this->controller;
        $modelClass = $controller->modelClass;
        $searchModelClass = $controller->searchModelClass ?? $modelClass;
        $requestBodies = [
            'update' =>
                new DescribeRequestBody([
                    'required' => true,
                    'content' => new DescribeResource(['modelClass' => $modelClass])
                ]),
            'index' =>
                new DescribeRequestBody([
                    'content' => new DescribeSearchResource(['modelClass' => $searchModelClass])
                ]),
        ];

        if ($this->requestBodies instanceof Closure) {
            $requestBodies = call_user_func($this->requestBodies, $requestBodies, $controller);
        }

        return $requestBodies;
    }
}
