<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Basic_controller {
    
    protected $headerViewName = "header";
    protected $footViewName = "foot";

    public function __construct() {
        
    }
    
    protected function loadView($viewName) {
        $this->loadSpecificView($this->headerViewName);
        
        $this->loadSpecificView($viewName);
        
        $this->loadSpecificView($this->footViewName);
    }
    
    protected function loadSpecificView($viewName) {
        $viewPath = $this->getSpecificViewPath($viewName);
        require_once($viewPath);
    }
    
    protected function getSpecificViewPath($viewName = '') {
        return Config::APPLICATION_PATH . '/' . Config::VIEWS_PATH . '/' . $viewName . '.php';
    }
}
