<?php
$word = new COM("word.application") or die("Can’t start Word!");
echo "Loading Word, v. {$word->Version}";
$word->Visible = 0;
$word->Documents->open(dirname(__FILE__)."/ztest.doc");
$test= $word->ActiveDocument->content->Text;
echo $test;
//echo phpinfo();
?>