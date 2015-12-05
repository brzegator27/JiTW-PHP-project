<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
   "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" lang="en" xml:lang="en">
<head>
 <title>Jakiś tytuł strony tutaj jest :P</title>
 <meta http-equiv="Content-Type" content="text/html;charset=utf-8"></meta>
 <link rel="stylesheet" 
       type="text/css" 
       href="<?= Config::URL_BASE . '/' . Config::INDEX_PAGE ?>/application/views/css/styles.css" 
       title="Default">
 </link>
 <link rel="alternate stylesheet" 
       type="text/css" 
       href="<?= Config::URL_BASE . '/' . Config::INDEX_PAGE ?>/application/views/css/alternative.css" 
       title="Other">
 </link>
 <script src="<?= Config::URL_BASE . '/' . Config::INDEX_PAGE ?>/application/views/js/CSSManager.js"></script>
 <script src="<?= Config::URL_BASE . '/' . Config::INDEX_PAGE ?>/application/views/js/liveComment.js"></script>
</head>
    <body onload="inicializeCSSList()">
        <?php require 'menu.php';