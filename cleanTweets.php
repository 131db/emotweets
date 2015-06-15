<?php

  //Tokenize and clean retrieved tweets

  //Regular Expressions
  $mention = "/(@\w+)/";
  $hashtags = "/(#\w+)/";
  $links = "/(?:https?:\/\/)?(?:[\w]+\.)([a-zA-Z\.]{2,6})([\/\w\.-]*)*\/?/";
  $smile = "#(^|\W)(\>\:\]|\:-\)|\:\)|\:o\)|\:\]|\:3|\:c\)|\:\>|\=\]|8\)|\=\)|\:\}|\:\^\))($|\W)#";
  $laugh = "#(^|\W)(\>\:D|\:-D|\:D|8-D|x-D|X-D|\=-D|\=D|\=-3|8-\)|xD|XD|8D|\=3)($|\W)#";
  $sad = "#(^|\W)(\>\:\[|\:-\(|\:\(|\:-c|\:c|\:-\<|\:-\[|\:\[|\:\{|\>\.\>|\<\.\<|\>\.\<)($|\W)#";
  $wink = "#(^|\W)(\>;\]|;-\)|;\)|\*-\)|\*\)|;-\]|;\]|;D|;\^\))($|\W)#";
  $tongue = "#(^|\W)(\>\:P|\:-P|\:P|X-P|x-p|\:-p|\:p|\=p|\:-Þ|\:Þ|\:-b|\:b|\=p|\=P|xp|XP|xP|Xp)($|\W)#";
  $surprise = "#(^|\W)(\>\:o|\>\:O|\:-O|\:O|°o°|°O°|\:O|o_O|o\.O|8-0)($|\W)#";
  $annoyed = "#(^|\W)(\>\:\\|\>\:/|\:-/|\:-\.|\:\\|\=/|\=\\|\:S|\:\/)($|\W)#";
  $cry = "#(^|\W)(\:'\(|;'\()($|\W)#";

  $string = "I love you more @NikoValerio! See you again next week?";
  // $emoticon = "I amdasdasdsadasd  =D";
  // if(preg_match($mention, $string)){
  //   echo "Match";
  // }else {
  //   echo "Not!";
  // }
  echo $string . "<br />";
  $string = preg_replace($mention, "", $string);
  $string = preg_replace($links, "", $string);
  $string = preg_replace($laugh, "", $string);
  $string = preg_replace($smile, "", $string);
  $string = preg_replace($sad, "", $string);
  $string = preg_replace($wink, "", $string);
  $string = preg_replace($tongue, "", $string);
  $string = preg_replace($surprise, "", $string);
  $string = preg_replace($annoyed, "", $string);
  $string = preg_replace($cry, "", $string);
  $string = preg_replace($hashtags, "", $string);
  echo $string;


?>
