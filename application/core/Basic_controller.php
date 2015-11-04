<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Basic_controller {
    
    protected $headerViewName = "header";
    protected $footViewName = "foot";
    
    protected $model = null;

    public function __construct() {
        
    }
    
    protected function loadView($viewName, $viewData = array()) {
        $this->loadSpecificView($this->headerViewName, $viewData);
        $this->loadSpecificView($viewName, $viewData);
        $this->loadSpecificView($this->footViewName, $viewData);
    }
    
    protected function loadSpecificView($viewName, $viewData) {
        $viewPath = $this->getSpecificViewPath($viewName);
        require $viewPath;
    }
    
    protected function getSpecificViewPath($viewName = '') {
        return Config::APPLICATION_PATH . '/' . Config::VIEWS_PATH . '/' . $viewName . '.php';
    }
    
    protected function loadModel($basicModelName) {
        $fullModelName = $basicModelName . '_model';
        require_once '/../models/' . $fullModelName . '.php';
        $this->model = new $fullModelName();
    }
    
    protected function getAllPostData($dataMap) {
        $postData = array();
        
        foreach($dataMap as $dataName) {
            $postData[$dataName] = getPostData($dataName);
        }
        
        return $postData;
    }
    
    protected function getPostData($dataName) {
        return isset($_POST[$dataName]) ? $_POST[$dataName] : '';
    }
    
    protected function getGetData($dataName) {
        return isset($_GET[$dataName]) ? $_GET[$dataName] : '';
    }
}
