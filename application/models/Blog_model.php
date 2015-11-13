<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require_once realpath(__DIR__ . '/../core/Basic_model.php');
class Blog_model extends Basic_model {
    
    public function getBlogData($blogName) {
        $blogData = array();
        if(!$this->checkIfBlogExist($blogName)) {
            return $blogData;
        }
        
        $properBlogName = $this->getInternalBlogName($blogName);
        
        $blogData['blog_name'] = $blogName;
        $blogData['description'] = $this->getBlogDescription($properBlogName);
        $blogData['entries'] = $this->getBlogEntries($properBlogName);
        
        return $blogData;
    }
    
    public function getBlogEntries($properBlogName) {
        $entries = array();
        
        $blogEntriesFilesRegExp = $this->fakeDatabaseDir . '/' . $properBlogName . '/' . '????????????????';
        $blogsEntriesIdsRough = glob($blogEntriesFilesRegExp);
        
        foreach($blogsEntriesIdsRough as $blogEntryIdRough) {
            $blogEntryIdWithPath = explode('/', $blogEntryIdRough);
            $blogEntryId = $blogEntryIdWithPath[count($blogEntryIdWithPath) - 1];
            $blogEntryComments = $this->getEntryComments($properBlogName, $blogEntryId);
            $blogEntryFiles = $this->getEntryFilesPaths($properBlogName, $blogEntryId);
            $blogEntryTitleAndContent = $this->getEntryTitleAndContent($properBlogName, $blogEntryId);
            
            $entries[$blogEntryId] = array(
                'title' => $blogEntryTitleAndContent['title'],
                'content' => $blogEntryTitleAndContent['content'],
                'comments' => $blogEntryComments,
                'files' => $blogEntryFiles
            );
        }
        
        return $entries;
    }
    
    public function getEntryComments($properBlogName, $entryId) {
        $comments = array();
        
        $entryCommentsFilesRegExp = $this->fakeDatabaseDir . '/' . $properBlogName . '/' . $entryId . '.k/*';
        $entryCommentsFilesRough = glob($entryCommentsFilesRegExp);
        
        for($i = 0; $i < count($entryCommentsFilesRough); $i++) {
            $commentData = $this->readDataFromFileFD($properBlogName . '/' . $entryId . '.k', $i);
            
            $comment = array();
            $comment['type'] = $commentData[0];
            $comment['date'] = $commentData[1];
            $comment['content'] = '';
            
            for($j = 2; $j < count($commentData); $j++) {
                $comment['content'] .= $commentData[$j] . '<br/>';
            }
            
            $comments[] = $comment;
        }
        
        return $comments;
    }
    
    public function getEntryFilesPaths($properBlogName, $entryId) {
        $entryFilesRegExp = $this->fakeDatabaseDir . '/' . $properBlogName . '/' . $entryId . '?.*';
        $entryFilesPaths = glob($entryFilesRegExp);
        
        return $entryFilesPaths;
    }
    
    public function getEntryTitleAndContent($properBlogName, $entryId) {
        $blogEntryTitleAndContent = array();
        
        $basicEntryData = $this->readDataFromFileFD($properBlogName, $entryId);
        $entryTitle = $basicEntryData[0];
        $entryContent = '';
        for($i = 1; $i < count($basicEntryData); $i++) {
            $entryContent .= $basicEntryData[$i] . "<br/>";
        }
        
        $blogEntryTitleAndContent['title'] = $entryTitle;
        $blogEntryTitleAndContent['content'] = $entryContent;
        
        return $blogEntryTitleAndContent;
    }
    
    public function getBlogDescription($properBlogName) {
        $basicBlogData = $this->readDataFromFileFD($properBlogName, 'info');
        $blogDescription = '';
        for($i = 2; $i < count($basicBlogData); $i++) {
            $blogDescription .= $basicBlogData[$i] . "\n";
        }
        
        return $blogDescription;
    }
    
    public function manageNewBlogData($blogName, $userName, $password, $blogDescription) {
        $properBlogName = $this->getInternalBlogName($blogName);
        $passwordMD5 = md5($password);
        
        $this->addDirFD('', $properBlogName);
        $this->addEmptyFileFD($properBlogName, 'info');
        $this->saveDataToFileFD($properBlogName, 'info', array($userName, $passwordMD5, $blogDescription));
    }
    
    public function checkIfBlogExist($blogName) {
        $properBlogName = $this->getInternalBlogName($blogName);
        return $this->checkIfFileExistsFD('', $properBlogName);
    }
    
    public function getAllBlogsNames() {
        $blogsNames = array();
        
        $blogsDirsRegExp = $this->fakeDatabaseDir . '/*';
        $blogsDirsPaths = glob($blogsDirsRegExp, GLOB_ONLYDIR);
        
        foreach($blogsDirsPaths as $blogDirPath) {
            $blogDirPathElements = explode('/', $blogDirPath);
            $blogsNames[] = $blogDirPathElements[count($blogDirPathElements) - 1];
        }
        
        return $blogsNames;
    }
    
}