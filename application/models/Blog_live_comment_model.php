<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require_once realpath(__DIR__ . '/../core/Basic_model.php');
class Blog_live_comment_model extends Basic_model {
    
    protected function areThereNewComments($properBlogName, $timestamp) {
        $liveCommentsFilesRegExp = $this->fakeDatabaseDir . '/' . $properBlogName . '/lk/*';
        $liveCommentsFiles = glob($liveCommentsFilesRegExp);
        
        foreach($liveCommentsFiles as $filePath) {
            $filePathArr = explode('/', $filePath);
            $fileName = $filePathArr[count($filePathArr) - 1];
            
//            Integer is too small for timestamp.
            if(doubleval($fileName) > doubleval($timestamp)) {
                return true;
            }
        }
        
        return false;
    }

    public function getComments($properBlogName, $lastSiteTimestamp) {
        $leftTime = 800000;
        $timeIncrement = 50;
        
        while($leftTime > 2 * $timeIncrement) {
            if($this->areThereNewComments($properBlogName, $lastSiteTimestamp)) {
                break;
            }
            usleep($timeIncrement);
            $leftTime -= $timeIncrement;
        }
        
        return $this->getCommentsData($properBlogName);
    }
    
    protected function getCommentsData($properBlogName) {
        $liveComments = array();
        
        $liveCommentsFilesRegExp = $this->fakeDatabaseDir . '/' . $properBlogName . '/lk/*';
        $liveCommentsFiles = glob($liveCommentsFilesRegExp);
        
        foreach($liveCommentsFiles as $filePath) {
            $filePathArr = explode('/', $filePath);
            $fileName = $filePathArr[count($filePathArr) - 1];
            $liveCommentData = $this->readDataFromFileFD($properBlogName . '/lk', $fileName);
            
            $liveComment = array();
            $liveComment['username'] = $liveCommentData[0];
            $liveComment['timestamp'] = $fileName;
            $liveComment['text'] = '';
            
            for($i = 1; $i < count($liveCommentData); $i++) {
                $liveComment['text'] .= $liveCommentData[$i] . '<br/>';
            }
            
            $liveComments[] = $liveComment;
        }
        
        asort($liveComments);
        
        return $liveComments;
    }
    
    public function manageNewCommentData($nickname, $content, $timestamp, $blogName) {
        if(!$this->checkIfBlogExist($blogName)) {
            return false;
        }
        
        $basicTimestamp = substr($timestamp, 0, -3);
        $blogNameProper = $this->getInternalBlogName($blogName);
        $liveCommentDirPath = $blogNameProper . '/lk';
        $liveCommentDirExist = $this->checkIfFileExistsFD('', $liveCommentDirPath);
        
//        $sem = sem_get(4);
//        ob_flush();
//        flush();
//        sem_acquire($sem);
        
        if(!$liveCommentDirExist) {
            $this->addDirFD($blogNameProper, 'lk');
        }
        
        $newLiveCommentNumber = $this->getNewLiveCommentUniqueNumber($liveCommentDirPath, $basicTimestamp);
        $newLiveCommentFileName = $basicTimestamp . $newLiveCommentNumber;
        
        $this->addEmptyFileFD($liveCommentDirPath, $newLiveCommentFileName);
        $this->saveDataToFileFD($liveCommentDirPath, $newLiveCommentFileName, array($nickname, $content));
        
//        ob_flush();
//        flush();
//        sem_release($sem);
        
        return $newLiveCommentFileName;
    }
    
    protected function getNewLiveCommentUniqueNumber($liveCommentDirPath, $basicTimestamp) {
        $liveCommentDirNameRegExp = $this->fakeDatabaseDir . '/' . $liveCommentDirPath . '/' . $basicTimestamp . '???';
        $sameTimeBlogLiveComments = glob($liveCommentDirNameRegExp);
        $sameTimeBlogLiveCommentsCount = count($sameTimeBlogLiveComments);

        if($sameTimeBlogLiveCommentsCount > 100) {
            $uniqunessNumber = $sameTimeBlogLiveCommentsCount;
        } elseif($sameTimeBlogLiveCommentsCount > 10) {
            $uniqunessNumber = '0' . $sameTimeBlogLiveCommentsCount;
        } else {
            $uniqunessNumber = '00' . $sameTimeBlogLiveCommentsCount;
        }
        
        return $uniqunessNumber;
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