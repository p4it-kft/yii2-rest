<?php

namespace p4it\rest\server\models;

/**
 * https://swagger.io/docs/specification/paths-and-operations/
 *
 * Class DescribePath
 * @package p4it\rest\server
 */
class DescribeResponseIndex extends DescribeResponse
{

    public function fields()
    {
        $fields = parent::fields();
        $fields['content'] = static function (DescribeResponse $model) {
            $contentSchema = [
                'type' => 'object',
                'properties' => [
                    'items' => [
                        'type' => 'array',
                        'items' => $model->content
                    ],
                    '_meta' => [
                        'type' => 'object',
                        'properties' => [
                            'totalCount' => [
                                'type' => 'integer',
                            ],
                            'pageCount' => [
                                'type' => 'integer',
                            ],
                            'currentPage' => [
                                'type' => 'integer',
                            ],
                            'perPage' => [
                                'type' => 'integer',
                            ],
                        ]
                    ],
                    '_links' => [
                        'type' => 'object',
                        'properties' => [
                            'self' => [
                                'type' => 'object',
                                'properties' => [
                                    'href' => [
                                        'type' => 'string'
                                    ]
                                ]
                            ],
                            'next' => [
                                'type' => 'object',
                                'properties' => [
                                    'href' => [
                                        'type' => 'string'
                                    ]
                                ]
                            ],
                            'last' => [
                                'type' => 'object',
                                'properties' => [
                                    'href' => [
                                        'type' => 'string'
                                    ]
                                ]
                            ],
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