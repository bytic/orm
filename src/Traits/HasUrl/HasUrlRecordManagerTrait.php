<?php

namespace Nip\Records\Traits\HasUrl;

/**
 * Trait HasUrlRecordManagerTrait
 * @package Nip\Records\Traits\HasUrl
 */
trait HasUrlRecordManagerTrait
{

    /**
     * @param string $name
     * @param $arguments
     * @return bool
     */
    protected function isCallUrl($name, $arguments)
    {
        if (substr($name, 0, 3) == "get" && substr($name, -3) == "URL") {
            $action = substr($name, 3, -3);
            $params = isset($arguments[0]) ? $arguments[0] : [];
            $module = isset($arguments[1]) ? $arguments[1] : null;

            return $this->compileURL($action, $params, $module);
        }

        return false;
    }

    /**
     * @param string $action
     * @param array $params
     * @param null $module
     * @return string|null
     */
    public function compileURL($action, $params = [], $module = null)
    {
        $controller = $this->getController();

        if (substr($action, 0, 5) == 'Async') {
            $controller = 'async-' . $controller;
            $action = substr($action, 5);
        }

        if (substr($action, 0, 5) == 'Modal') {
            $controller = 'modal-' . $controller;
            $action = substr($action, 5);
        }

        $params = (array) $params;

        $params['action'] = (!empty($action)) ? $action : 'index';
        $params['controller'] = $controller;

        $params['action'] = inflector()->unclassify($params['action']);
        $params['action'] = ($params['action'] == 'index') ? null : $params['action'];

        $params['controller'] = $controller ? $controller : $this->getController();
        $params['module'] = $module ? $module : request()->getModuleName();

        $routeName = $params['module'] . '.' . $params['controller'] . '.' . $params['action'];
        if (app()->get('router')->hasRoute($routeName)) {
            unset($params['module'], $params['controller'], $params['action']);
        } else {
            $routeName = $params['module'] . '.default';
        }

        return app()->get('router')->assembleFull($routeName, $params);
    }
}
