<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require_once realpath(__DIR__ . '/../core/Basic_controller.php');
class Blog extends Basic_controller {
    
    public function __construct($controllerMethodWhichWillBeCalled) {
        parent::__construct();
        $this->loadModel('Blog');
        
        $blogToDisplayName = $this->getGetData('nazwa');
        if($blogToDisplayName) {
            $this->displayWholeBlog($blogToDisplayName);
        } else {
//            if (...) then we display list of blogs
            if($controllerMethodWhichWillBeCalled === "") {
                $this->all_blogs();
            }
        }
    }
    
    public function all_blogs() {
        $blogsNames = $this->model->getAllBlogsNames();
        
        $this->loadView('blogs_list', $blogsNames);
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
    
}
