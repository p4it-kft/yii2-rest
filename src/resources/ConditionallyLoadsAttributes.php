<?php

namespace p4it\rest\server\resources;

use yii\helpers\ArrayHelper;

trait ConditionallyLoadsAttributes
{
    /**
     * Filter the given data, removing any optional values.
     *
     * @param  array  $data
     * @return array
     */
    protected function filter($data)
    {
        return $this->removeMissingValues($data);
    }

    /**
     * Remove the missing values from the filtered data.
     *
     * @param  array  $data
     * @return array
     */
    protected function removeMissingValues($data)
    {
        $numericKeys = true;

        foreach ($data as $key => $value) {
            if (($value instanceof PotentiallyMissing && $value->isMissing()) ||
                ($value instanceof self &&
                $value->resource instanceof PotentiallyMissing &&
                $value->isMissing())) {
                unset($data[$key]);
            } else {
                $numericKeys = $numericKeys && is_numeric($key);
            }
        }

        if (property_exists($this, 'preserveKeys') && $this->preserveKeys === true) {
            return $data;
        }

        return $numericKeys ? array_values($data) : $data;
    }

    /**
     * Retrieve a value based on a given condition.
     *
     * @param  bool  $condition
     * @param  mixed  $value
     * @param  mixed  $default
     * @return MissingValue|mixed
     */
    protected function when($condition, $value, $default = null)
    {
        if ($condition) {
            return $value;
        }

        return func_num_args() === 3 ? $default : new MissingValue;
    }

    /**
     * Merge a value based on a given condition.
     *
     * @param $fields
     * @param bool $condition
     * @param array $value
     */
    protected function mergeIntoWhen(&$fields, $condition, $value)
    {
        if(!$condition) {
            foreach ($value as $key => $item) {
                if(is_int($key)) {
                    $key = $item;
                }
                $fields[$key] = new MissingValue();
            }

            return;
        }

        $fields = ArrayHelper::merge($fields, $value);
    }

    public function toArray(array $fields = [], array $expand = [], $recursive = true) {
        $fields = $this->resolveFields($fields,[]);
        $expand = array_diff_key($this->resolveFields([], $expand), $fields);

        $fields = $this->filter($fields);
        $expand = $this->filter($expand);

        if(!$fields && !$expand) {
            return [];
        }
        return parent::toArray(array_keys($fields), array_keys($expand), $recursive);
    }

}
