<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require_once '/../core/Basic_model.php';
class Blog_model extends Basic_model {
    
    public function getBlogData($blogName) {
        $blogData = array();
        if(!$this->checkIfBlogExist($blogName)) {
            return $blogData;
        }
        
        $properBlogName = $this->getInternalBlogName($blogName);
        
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
            $blogEntryTitleAndContent = $this->getEntryTitleAndContent($properBlogName, $blogEntryId);
            
            $entries[$blogEntryId] = array(
                'title' => $blogEntryTitleAndContent['title'],
                'content' => $blogEntryTitleAndContent['content'],
                'comments' => $blogEntryComments
            );
        }
        
        return $entries;
    }
    
    public function getEntryComments($properBlogName, $entryId) {
        
        return array();
    }
    
    public function getEntryTitleAndContent($properBlogName, $entryId) {
        $blogEntryTitleAndContent = array();
        
        $basicEntryData = $this->readDataFromFileFD($properBlogName, $entryId);
        $entryTitle = $basicEntryData[0];
        $entryContent = '';
        for($i = 1; $i < count($basicEntryData); $i++) {
            $entryContent .= $basicEntryData[$i] . "\n";
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
    
    public function getUserBlogProperName($userName, $password) {
        $blogsDirRough = glob($this->fakeDatabaseDir . '/*', GLOB_ONLYDIR);
        
        foreach($blogsDirRough as $blogDirRough) {
            $blogPathDirs = explode('/', $blogDirRough);
            $blogDir = $blogPathDirs[count($blogPathDirs) - 1];
            
            if($this->checkSingleBlogUserData($blogDir, $userName, $password)) {
                return $blogDir;
            }
        }
    }
    
    protected function checkSingleBlogUserData($properBlogName, $userName, $password) {
        if(!$this->checkIfFileExistsFD($properBlogName, 'info')) {
            return false;
        }
        
        $blogUserData = $this->readDataFromFileFD($properBlogName, 'info');
        $blogDataUserName = $blogUserData[0];
        $blogDataPasswordMD5 = $blogUserData[1];
        
        $passwordMD5 = md5($password);
        if($userName === $blogDataUserName && $passwordMD5 === $blogDataPasswordMD5) {
            return true;
        }
        return false;
    }
    
    public function manageNewEntryData($properBlogName, $entryTitle, $entry, $date) {
        $seconds = '12';
        $basicNewEntryDirName = $date . $seconds;
        $uniqunessNumber = $this->getNewEntryUniqueNumber($properBlogName, $basicNewEntryDirName);
        $newEntryDirFullName = $basicNewEntryDirName . $uniqunessNumber;
        
        $this->addEmptyFileFD($properBlogName, $newEntryDirFullName);
        $this->saveDataToFileFD($properBlogName, $newEntryDirFullName, array($entryTitle, $entry));
    }
    
    protected function getNewEntryUniqueNumber($properBlogName, $basicNewEntryDirName) {
        $basicNewEntryDirNameRegExp = $this->fakeDatabaseDir . '/' . $properBlogName . '/' . $basicNewEntryDirName . '??';
        $blogSameTimeEntries = glob($basicNewEntryDirNameRegExp);
        $sameTimeEntriesCount = count($blogSameTimeEntries);

        if($sameTimeEntriesCount > 10) {
            $uniqunessNumber = $sameTimeEntriesCount;
        } else {
            $uniqunessNumber = '0' . $sameTimeEntriesCount;
        }
        
        return $uniqunessNumber;
    }
}