<?php

namespace p4it\rest\server\models;

use yii\base\Model;

/**
 * https://swagger.io/docs/specification/paths-and-operations/
 *
 * Class DescribePath
 * @package p4it\rest\server
 */
class Describe extends Model
{

    public $openapi = '3.0.0';
    /**
     * @var DescribePath[]
     */
    public $paths = [];
    /**
     * @var DescribeInfo
     */
    public $info;
    /**
     * @var DescribeServer[]
     */
    public $servers;

    public function attributes()
    {
        return [
            'openapi',
            'info',
            'servers',
            'paths',
        ];
    }

    public function fields()
    {
        $fields = parent::fields();
        $fields['paths'] = static function (Describe $model) {
            $content = [];
            foreach ($model->paths as $path) {
                if(!($path instanceof DescribePath)) {
                    return $model->paths;
                }
                foreach ($path->operations as $operation) {
                    $content[$path->url][$operation->getMethod()] = $operation;
                }

            }

            return $content;
        };

        return $fields;
    }

    /**
     * @param $url
     * @return DescribePath
     */
    public function getPath($url): DescribePath
    {
        $url = $this->normalizeUrl($url);
        if (isset($this->paths[$url])) {
            return $this->paths[$url];
        }

        $this->paths[$url] = new DescribePath([
            'url' => $url,
        ]);

        return $this->paths[$url];
    }

    private function normalizeUrl($url)
    {
        return '/' . preg_replace('/<([\w._-]+):?([^>]+)?>/', '{$1}', $url);
    }

}
