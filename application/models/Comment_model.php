<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require_once realpath(__DIR__ . '/../core/Basic_model.php');
class Comment_model extends Basic_model {
    
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