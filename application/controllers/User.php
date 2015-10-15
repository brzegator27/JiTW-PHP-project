<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class User {
    public function asd($param1, $param2, $param3) {
//        echo $param1;
//        echo $param2;
//        echo $param3;
        
        require_once '/../core/Basic_model.php';
        
        $basicModel = new Basic_model();
//        $basicModel->addEmptyFileFD('', 'file');
//        $basicModel->clearFileFD('', 'file');
        $basicModel->saveDataToFileFD('', 'file', array('safdasdf', "asasdf\n\nasdfsad\n"));
        var_dump($basicModel->readDataFromFileFD('', 'file'));
        $basicModel->addDirFD('', 'myFolder.01');
    }
}