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
        
        $this->manageController($controllerName, $controllerMethodName, $methodAruments);
    }
    
    private function manageControllerMethod($controllerMethodName, $methodAruments = array()) {
        if($controllerMethodName === '') {
//            If we call controller in URL cant't be passed arguments because one of them will be considered as method name
            return;
        }
        
        if(!$this->controllerMethodExtists($controllerMethodName)) {
            exit('Controller\'s method do not exist!');
        }
        
        call_user_func_array(array($this->controllerObj, $controllerMethodName), $methodAruments);
    }
    
    private function manageController($controllerName, $controllerMethodName = '', $methodAruments = array()) {
        $properControllerName = ucfirst($controllerName);
        $controllerFileExists = $this->controllerFileExists($properControllerName);
        
        if(!$controllerFileExists) {
//            exit('Controller\'s file do not exist!');
            $filePath = $controllerName;
            $filePath .= $controllerMethodName !== '' ? '/' . $controllerMethodName : '';
            foreach($methodAruments as $urlElement) {
                $filePath .= '/' . $urlElement;
            }
            $this->manageFileDownload($filePath);
            return;
        }
        
        $controllerClassExist = $this->manageControllerClass($properControllerName);
        
        if(!$controllerClassExist) {
            exit('Controller\'s class do not exist!');
        }
        
//        This suppose to not work 100% correctly!
        $this->controllerObj = new $properControllerName($controllerMethodName);
        
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
        return realpath(__DIR__ . '/' . '../' . Config::APPLICATION_PATH . '/' . Config::CONTROLLERS_PATH . '/' . $properControllerName . '.php');
    }
    
    private function manageFileDownload($filePath) {
        $relativeFilePath = __DIR__ . '/../' . $filePath;
        if(!file_exists($relativeFilePath)) {
            exit('Error, file to download doesn\'t exist!');
        }
        
        $this->sendFile($relativeFilePath);
    }
    
    private function sendFile($relativeFilePath) {
        $fileInfo = finfo_open(FILEINFO_MIME_TYPE);
        $fileExtension = pathinfo($relativeFilePath, PATHINFO_EXTENSION);
        
        $contentType = '';
//        hack for MIME types:
        switch($fileExtension) {
            case 'css':
                $contentType = 'text/css';
                break;
            default:
                $contentType = finfo_file($fileInfo, $relativeFilePath);
        }
        
        header('Content-Type: ' . $contentType);
        finfo_close($fileInfo);
        //Use Content-Disposition: attachment to specify the filename
        header('Content-Disposition: attachment; filename=' . basename($relativeFilePath));
        //No cache
        header('Expires: 0');
        header('Cache-Control: must-revalidate');
        header('Pragma: public');
        //Define file size
        header('Content-Length: ' . filesize($relativeFilePath));
        ob_clean();
        flush();
        readfile($relativeFilePath);
        exit;
    }
}