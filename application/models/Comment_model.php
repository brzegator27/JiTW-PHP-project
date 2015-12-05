<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require_once realpath(__DIR__ . '/../core/Basic_model.php');
class Comment_model extends Basic_model {
    
    public function manageNewCommentData($commentType, $nickname, $content, $blogName, $entryId) {
        if(!$this->checkIfBlogExist($blogName) || !$this->checkIfEntryExists($blogName, $entryId)) {
            return false;
        }
        
        $blogNameProper = $this->getInternalBlogName($blogName);
        $commentDirPath = $blogNameProper . '/' . $entryId . '.k';
        $commentDirExist = $this->checkIfFileExistsFD('', $commentDirPath);
        
//        $sem = sem_get(2);
//        ob_flush();
//        flush();
//        sem_acquire($sem);
        
        if(!$commentDirExist) {
            $this->addDirFD($blogNameProper, $entryId . '.k');
        }
        
        $newCommentNumber = $this->getFilesInDirCount($commentDirPath);
        $this->addEmptyFileFD($commentDirPath, $newCommentNumber);
        
        $commentDate = gmdate('Y-m-d h:i:s \G\M\T');
        $this->saveDataToFileFD($commentDirPath, $newCommentNumber, array($commentType, $commentDate, $nickname, $content));
        
//        ob_flush();
//        flush();
//        sem_release($sem);
    }
    
    private function getFilesInDirCount($dirPath) {
        $dirPathFD = $this->fakeDatabaseDir . '/' . $dirPath;
        $filesystemIterator = new FilesystemIterator($dirPathFD, FilesystemIterator::SKIP_DOTS);
        return iterator_count($filesystemIterator);
    }
    
//    Bad practice, code repetition!!!
    public function checkIfBlogExist($blogName) {
        $properBlogName = $this->getInternalBlogName($blogName);
        return $this->checkIfFileExistsFD('', $properBlogName);
    }
    
//    Bad practice, method in not proper model!!!
    public function checkIfEntryExists($blogName, $entryId) {
        $properBlogName = $this->getInternalBlogName($blogName);
        return $this->checkIfFileExistsFD($properBlogName, $entryId);
    }
}