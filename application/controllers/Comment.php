<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require_once '/../core/Basic_controller.php';
require_once 'Blog.php';
class Comment extends Basic_controller {
    
    public function __construct() {
        parent::__construct();
        $this->loadModel('Comment');
    }
    
    public function add_comment() {
        $blogNameGet = $this->getGetData('blog_name');
        $entryIdGet = $this->getGetData('entry_id');
        
        $blogName = $this->getPostData('blog_name');
        $entryId = $this->getPostData('entry_id');
        $commentType = $this->getPostData('type');
        $nickname = $this->getPostData('nickname');
        $content = $this->getPostData('content');
        
        if($commentType !== '' && $nickname && $content && $blogName && $entryId) {
            $this->model->manageNewCommentData($commentType, $nickname, $content, $blogName, $entryId);
            
            $newLocationUrl = 'http://' . $_SERVER['HTTP_HOST'] . Config::URL_BASE . '/' . 'blog?nazwa=' . rawurlencode($blogName);
            header('Location: ' . $newLocationUrl);
            die();
        }
        
        if($blogNameGet && $entryIdGet) {
            $viewData = array('blog_name' => $blogNameGet, 'entry_id' => $entryIdGet);
            $this->loadView('new_comment', $viewData);
            return; 
       }
        
        $this->displayError404();
    }

}
