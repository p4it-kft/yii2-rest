<?php

namespace p4it\rest\server\traits;

use p4it\rest\server\models\DescribeContentSchema;

trait DescribeContentTrait
{

    /**
     * @var DescribeContentSchema
     */
    public $content;

    public $mediaTypes = [
        'application/json',
//        'application/xml'
    ];

    public function fields()
    {
        $fields = parent::fields();
        $fields['content'] = static function ($model) {
            return $model->getContentByMediaTypes();
        };

        return $fields;
    }

    public function getContentByMediaTypes()
    {
        if (!$this->content) {
            return null;
        }

        $content = [];

        foreach ($this->mediaTypes as $mediaType) {
            if(property_exists($this->content, 'type')) {
                $content[$mediaType] = ['schema' => $this->content];
            } else {
                $content[$mediaType] = ['schema' => ['type' => 'object', 'properties' => $this->content]];
            }
        }

        return $content;
    }

}
