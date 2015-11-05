<?php 

class Config {
    
    private $fullBaseUrl;
    
    public function __construct() {
        $this->fullBaseUrl = 'http://' . $_SERVER['HTTP_HOST'] . Config::URL_BASE . '/';
    }
    
    public function getFullBaseUrl() {
        return $this->fullBaseUrl;
    }
    
    const
        APPLICATION_PATH = "application",
        CONTROLLERS_PATH = "controllers",
        VIEWS_PATH = "views",
        MODELS_PATH = "models";
    
    const
        URL_BASE = "/jitw-php-agh",
        INDEX_PAGE = 'index.php';
    
    const
        FAKEDATABASE_PATH = "fakeDatabase";
}