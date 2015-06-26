<?php

require_once('Text/LanguageDetect.php');

function checkLanguage($text) {

  try {
      $l = new Text_LanguageDetect();
      $l->setNameMode(2); //return 2-letter language codes only
      $result = $l->detect($text, 4);

      if(array_key_exists('en', $result)) {
        return true;
      }
      else {
        return false;
      }

  }
  catch (Text_LanguageDetect_Exception $e) {

  }


}

?>
