<?php

class Bootstrap {
    private $controllerObj = null;
    
    public function __construct() {
        $urlParams = $this->getUrlParams();
        
        $controllerName = $urlParams['controller'];
        $controllerMethodName = $urlParams['method'];
        $methodAruments = $urlParams['arguments'];
        
        $this->runController($controllerName, $controllerMethodName, $methodAruments);
    }
    
    // Nazwa getUrlParams jest dwuznaczna - sugeruje pobieranie argumentów przesyłanych przez POST i GET
    private function getUrlParams() {
        $mainRawUrl = filter_var($_SERVER['PHP_SELF'], FILTER_SANITIZE_URL);
        $urlBaseAndIndex = Config::URL_BASE . '/' . Config::INDEX_PAGE;
        $urlBaseAndIndexLength = strlen($urlBaseAndIndex);
        $urlBaseAndIndexLengthPlusSlash = $urlBaseAndIndexLength + 1;
        $mainUrl = substr($mainRawUrl, $urlBaseAndIndexLengthPlusSlash);
        
        $urlElements = explode('/', $mainUrl);
        
        $urlParams = array();
        $urlParams['controller'] = array_key_exists(0, $urlElements) ? $urlElements[0] : '';
        $urlParams['method'] = array_key_exists(1, $urlElements) ? $urlElements[1] : '';
        
        unset($urlElements[0]);
        unset($urlElements[1]);
        
        $urlParams['arguments'] = $urlElements;
        
        return $urlParams;
    }
    
    private function runController($controllerName, $controllerMethodName = '', $methodAruments = array()) {
        if($controllerName === '') {
            exit('No controller specified!');
        }
        
        $properControllerName = ucfirst($controllerName);
        $this->manageController($properControllerName, $controllerMethodName, $methodAruments);
    }
    
    private function manageControllerMethod($controllerMethodName, $methodAruments = array()) {
        if($controllerMethodName === '') {
            $this->controllerObj = $this->controllerObj->newInstanceArgs($methodAruments);
            return;
        }
        
        if(!$this->controllerMethodExtists($controllerMethodName)) {
            exit('Controller\'s method do not exist!');
        }
        
        call_user_func_array(array($this->controllerObj, $controllerMethodName), $methodAruments);
    }
    
    private function manageController($properControllerName, $controllerMethodName = '', $methodAruments = array()) {
        $controllerFileExists = $this->controllerFileExists($properControllerName);
        
        if(!$controllerFileExists) {
            exit('Controller\'s file do not exist!');
        }
        
        $controllerClassExist = $this->manageControllerClass($properControllerName);
        
        if(!$controllerClassExist) {
            exit('Controller\'s class do not exist!');
        }
        
        $this->controllerObj = new $properControllerName();
        
        $this->manageControllerMethod($controllerMethodName, $methodAruments);
    }
    
    private function manageControllerClass($properControllerName) {
        $controllerPath = $this->getControllerPath($properControllerName);
        require_once($controllerPath);
        
        $controllerClassExist = class_exists($properControllerName);
        
        return $controllerClassExist;
    }
    
    private function controllerFileExists($properControllerName = '') {
        $controllerPath = $this->getControllerPath($properControllerName);
        return file_exists($controllerPath);
    }
    
    private function controllerMethodExtists($controllerMethodName = '') {
        return method_exists($this->controllerObj, $controllerMethodName);
    }
    
    private function getControllerPath($properControllerName = '') {
        return Config::APPLICATION_PATH . '/' . Config::CONTROLLERS_PATH . '/' . $properControllerName . '.php';
    }
    
}