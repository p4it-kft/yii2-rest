<?php

namespace p4it\rest\server\models;

use yii\base\Model;

/**
 * https://swagger.io/docs/specification/paths-and-operations/
 *
 * Class DescribePath
 * @package p4it\rest\server
 */
class DescribeOperation extends Model
{
    public function attributes()
    {
        return [
            'summary',
            'description',
            'operationId',
            'parameters',
            'responses',
            'requestBody',
        ];
    }

    protected $method;

    public $summary;
    public $description;
    public $operationId;
    public $requestBody;

    /**
     * @var DescribeParameter[]
     */
    public $parameters;

    /**
     * @var DescribeResponse[]
     */
    public $responses;

    /**
     * @return mixed
     */
    public function getMethod()
    {
        return $this->method;
    }

    /**
     * @param mixed $method
     * @return DescribeOperation
     */
    public function setMethod($method)
    {
        $this->method = strtolower($method);
        return $this;
    }

    /**
     * @param DescribeResponse $response
     * @return DescribeOperation
     */
    public function addResponse($response)
    {
        $this->responses[$response->statusCode] = $response;
        return $this;
    }

    public function addResponses($responses) {
        foreach ($responses as $response) {
            $this->addResponse($response);
        }

        return $this;
    }

    /**
     * @param DescribeParameter $parameter
     * @return DescribeOperation
     */
    public function addParameter($parameter)
    {
        $this->parameters[] = $parameter;
        return $this;
    }

    /**
     * @param DescribeParameter[] $parameters
     * @return DescribeOperation
     */
    public function addParameters($parameters)
    {
        foreach ($parameters as $parameter) {
            $this->addParameter($parameter);
        }

        return $this;
    }

}