<?php

namespace Nip\Records\Relations\Traits;

use Nip\Utility\Str;

/**
 * Trait HasParamsTrait
 * @package Nip\Records\Relations\Traits
 */
trait HasParamsTrait
{
    /**
     * @var array
     */
    protected $params = [];

    /**
     * @param $key
     * @return mixed
     */
    public function getParam($key)
    {
        return $this->hasParam($key) ? $this->params[$key] : null;
    }

    /**
     * @param $key
     * @return boolean
     */
    public function hasParam($key)
    {
        return isset($this->params[$key]);
    }

    /**
     * @param $params
     */
    public function addParams($params)
    {
//        $this->checkParamClass($params);
//        $this->checkParamWith($params);
//        $this->checkParamWithPK($params);
//        $this->checkParamTable($params);
//        $this->checkParamFk($params);
//        $this->checkParamPrimaryKey($params);
        $this->setParams($params);
    }

    /**
     * @param $key
     * @param $value
     */
    public function setParam($key, $value)
    {
        $method = 'set' . Str::studly($key);
        if (method_exists($this, $method)) {
            $this->$method($value);
            return;
        }

        $method = 'set' . Str::upper($key);
        if (method_exists($this, $method)) {
            $this->$method($value);
            return;
        }

        if (property_exists($this, $key)) {
            $this->{$key} = $value;
            return;
        }

        $this->params[$key] = $value;
    }

    /**
     * @return array
     */
    public function getParams(): array
    {
        return $this->params;
    }

    /**
     * @param $params
     */
    public function setParams($params)
    {
        foreach ($params as $key => $value) {
            $this->setParam($key, $value);
        }
    }
}