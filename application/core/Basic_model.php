<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * FD stands for FakeDirectory.
 */

class Basic_model {
    
    protected $fakeDatabaseDir = 'fakeDatabase';
    
    public function addDirFD($folderDirPath, $folderName) {
        if($this->checkIfFileExistsFD($folderDirPath, $folderName)) {
            return false;
        }
        $folderPath = $this->generatePath($folderDirPath, $folderName);
        
        return mkdir($folderPath);
    }
    
    public function addEmptyFileFD($fileDirPath, $fileName) {
        $filePath = $this->generatePath($fileDirPath, $fileName);
        $newFile = fopen($filePath, 'w');
        fclose($newFile);
    }
    
    public function saveDataToFileFD($fileDirPath, $fileName, $fileData) {
        $filePath = $this->generatePath($fileDirPath, $fileName);
        $file = fopen($filePath, 'w') or die("Unable to open file!");
        
        $textToSave = '';
        foreach($fileData as $singleDataRaw) {
            $singleData = $this->prepareTextToSave($singleDataRaw);
            $textToSave .= $singleData . "\n";
        }
        $textToSaveWithoutTrailingNewLine = rtrim($textToSave);
        
        fwrite($file, $textToSaveWithoutTrailingNewLine);
        fclose($file);
    }
    
    protected function prepareTextToSave($text) {
        return $text;
//        return trim(preg_replace('/\s+/', ' ', $text));
    }

    public function clearFileFD($fileDirPath, $fileName) {
        $filePath = $this->generatePath($fileDirPath, $fileName);
        $file = fopen($filePath, "w+") or die("Unable to open file!");
        fclose($file);
    }
    
    public function readDataFromFileFD($fileDirPath, $fileName) {
        $filePath = $this->generatePath($fileDirPath, $fileName);
        $file = fopen($filePath, "r") or die("Unable to open file!");
        
        $fileData = array();
        
        while(!feof($file)) {
          $fileData[] = rtrim(fgets($file));
        }
        fclose($file);
        
        return $fileData;
    }
    
    protected function generatePath($fileDirPath, $fileName) {
        return __DIR__ . '/../../' . Config::FAKEDATABASE_PATH . '/' .  $fileDirPath . '/' . $fileName;
    }
    
    protected function checkIfFileExistsFD($fileDirPath, $fileName) {
        $fileFullPath = $this->generatePath($fileDirPath, $fileName);
        return file_exists($fileFullPath);
    }
    
    public function getInternalBlogName($blogName) {
        return $blogName;
//        return str_replace(' ', '-', $blogName);
    }
}

