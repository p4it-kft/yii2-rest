<?php

namespace p4it\rest\server\resources;

trait ResourceAttributeMapperTrait
{
    public function load($params)
    {
        foreach ($this->attributeMap() as $from => $to) {
            if (!isset($params[$from]) || isset($params[$to])) {
                continue;
            }

            $params[$to] = $params[$from];
            unset($params[$from]);
        }

        return parent::load($params); // TODO: Change the autogenerated stub
    }

    public function attributeMap(): array
    {
        return [];
    }

    public function getFirstErrors()
    {
        if (empty($this->getErrors())) {
            return [];
        }

        $errors = [];
        foreach ($this->getErrors() as $name => $es) {
            if (!empty($es)) {
                $mappedName = array_search($name, $this->attributeMap(), true);
                $errors[$mappedName ?: $name] = reset($es);
            }
        }

        return $errors;
    }
}