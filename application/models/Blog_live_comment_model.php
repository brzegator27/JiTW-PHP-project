<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require_once '/../core/Basic_model.php';
class Blog_live_comment_model extends Basic_model {
    
    public function newComment($author, $content) {
        
    }
    
    public function getComments() {
        
    }
    
    public function manageCommentsFile() {
        
    }
    
    public function manageNewCommentData($commentType, $nickname, $content, $blogName, $entryId) {
        $blogNameProper = $this->getInternalBlogName($blogName);
        $commentDirPath = $blogNameProper . '/' . $entryId . '.k';
        $commentDirExist = $this->checkIfFileExistsFD('', $commentDirPath);
        
        if(!$commentDirExist) {
            $this->addDirFD($blogNameProper, $entryId . '.k');
        }
        
        $newCommentNumber = $this->getFilesInDirCount($commentDirPath);
        $this->addEmptyFileFD($commentDirPath, $newCommentNumber);
        
        $commentDate = gmdate('Y-m-d h:i:s \G\M\T');
        $this->saveDataToFileFD($commentDirPath, $newCommentNumber, array($commentType, $commentDate, $nickname, $content));
    }
    
    private function getFilesInDirCount($dirPath) {
        $dirPathFD = $this->fakeDatabaseDir . '/' . $dirPath;
        $filesystemIterator = new FilesystemIterator($dirPathFD, FilesystemIterator::SKIP_DOTS);
        return iterator_count($filesystemIterator);
    }
}