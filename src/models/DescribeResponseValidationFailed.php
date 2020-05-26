<?php

namespace p4it\rest\server\models;

/**
 * https://swagger.io/docs/specification/paths-and-operations/
 *
 * Class DescribePath
 * @package p4it\rest\server
 */
class DescribeResponseValidationFailed extends DescribeResponse
{

    public function fields()
    {
        $fields = parent::fields();
        $fields['content'] = static function (DescribeResponse $model) {
            $contentSchema = [
                'type' => 'array',
                'items' => [
                    'type' => 'object',
                    'properties' => [
                        'field' => [
                            'type' => 'string',
                        ],
                        'message' => [
                            'type' => 'string',
                        ]
                    ]
                ]
            ];

            $content = [];

            foreach ($model->mediaTypes as $mediaType) {
                $content[$mediaType] = ['schema' => $contentSchema];
            }

            return $content;
        };

        return $fields;
    }
}