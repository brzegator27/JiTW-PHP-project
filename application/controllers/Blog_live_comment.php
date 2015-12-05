<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require_once realpath(__DIR__ . '/../core/Basic_controller.php');
class Blog_live_comment extends Basic_controller {
    
    public function __construct() {
        parent::__construct();
        $this->loadModel('Blog_live_comment');
    }
    
    public function get_live_comments() {
        $blogName = $this->getPostData('blog_name');
        $timestamp = $this->getPostData('timestamp');
        
        if($blogName) {
            $commentsArr = $this->model->getComments($blogName);
            $commentsJSON = json_encode(array('data' => $commentsArr));
            echo $commentsJSON;
        }

        return;
    }
    
    public function add_live_comment() {
        $blogName = $this->getPostData('blog_name');
        $username = $this->getPostData('username');
        $text = $this->getPostData('text');
        $timestamp = $this->getPostData('timestamp');
        
        if($blogName && $username && $blogName && $timestamp) {
            echo $this->model->manageNewCommentData($username, $text, $timestamp, $blogName);
        }

        return;
    }

}
