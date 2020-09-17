<?php

namespace p4it\rest\server\resources;

trait ResourceAttributeMapperTrait
{
    public function load($params, $formName = null)
    {
        $params = $this->mapParams($params);

        return parent::load($params, $formName);
    }

    public static function attributeMap(): array
    {
        return [];
    }

    public function mapParams($params) {
        foreach (self::attributeMap() as $from => $to) {
            if (!isset($params[$from]) || isset($params[$to])) {
                continue;
            }

            $params[$to] = $params[$from];
            unset($params[$from]);
        }

        return $params;
    }

    public function getFirstErrors()
    {
        if (empty($this->getErrors())) {
            return [];
        }

        $errors = [];
        foreach ($this->getErrors() as $name => $es) {
            if (!empty($es)) {
                $mappedName = array_search($name, self::attributeMap(), true);
                $errors[$mappedName ?: $name] = reset($es);
            }
        }

        return $errors;
    }
}
