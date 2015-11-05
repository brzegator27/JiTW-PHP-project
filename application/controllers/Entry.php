<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require_once '/../core/Basic_controller.php';
class Entry extends Basic_controller {
    
    public function __construct() {
        parent::__construct();
        $this->loadModel('Entry');
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