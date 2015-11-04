<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require_once '/../core/Basic_controller.php';
class Blog extends Basic_controller {
    
    public function __construct() {
        parent::__construct();
        $this->loadModel('Blog');
        
        $blogToDisplayName = $this->getGetData('nazwa');
        if($blogToDisplayName) {
            $this->displayWholeBlog($blogToDisplayName);
        }
    }
    
    public function displayWholeBlog($blogName) {
        $blogData = $this->model->getBlogData($blogName);
        $this->loadView('blog_page', $blogData);
    }
    
    public function add_blog() {
        $blogName = isset($_POST['blog_name']) ? $_POST['blog_name'] : '';
        $userName = isset($_POST['user_name']) ? $_POST['user_name'] : '';
        $password = isset($_POST['password']) ? $_POST['password'] : '';
        $blogDescription = isset($_POST['blog_description']) ? $_POST['blog_description'] : '';

//        echo str_replace(PHP_EOL, '<br />', $blogDescription);
        
        if($blogName && $userName && $password && $blogDescription) {
            return $this->registerNewBlog($blogName, $userName, $password, $blogDescription);
        }
        
        $this->loadView('new_blog');
    }
    
    private function registerNewBlog($blogName, $userName, $password, $blogDescription) {
        $blogAlreadyExist = $this->model->checkIfBlogExist($blogName);
        if($blogAlreadyExist) {
            $this->loadView('blog_added', array('message' => 'Blog o takiej nazwie już istnieje! Musisz wymyśleć inną.'));
            return;
        }
        
        $this->model->manageNewBlogData($blogName, $userName, $password, $blogDescription);
        $this->loadView('blog_added', array('message' => 'Blog założony poprawnie. ;)'));
    }
    
    public function add_entry() {
        $entryTitle = $this->getPostData('entry_title');
        $entry = $this->getPostData('entry');
        $userName = $this->getPostData('user_name');
        $password = $this->getPostData('password');
        $date = $this->getPostData('date');

        if($entryTitle && $entry && $userName && $password) {
            return $this->addNewEntry($entryTitle, $entry, $userName, $password, $date);
        }
        
        $this->loadView('new_entry');
    }
    
    private function addNewEntry($entryTitle, $entry, $userName, $password, $date) {
        $blogName = $this->model->getUserBlogProperName($userName, $password);
        if(!$blogName) {
            $this->loadView('blog_added', array('message' => 'Podana nazwa użytkownika, lub hasło są niepoprawne.'));
            return;
        }
        
        $this->model->manageNewEntryData($blogName, $entryTitle, $entry, $date);
        $this->loadView('entry_added');
    }
}
