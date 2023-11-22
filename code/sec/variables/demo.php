<?php
//How to use it
//Include the file rtf.php somewhere in your project. Then do this:

$reader = new RtfReader();
$rtf = file_get_contents("test.rtf"); // or use a string
$reader->Parse($rtf);

//If you’d like to see what the parser read, then call this:
//$reader->root->dump();

//To convert the parser’s parse tree to HTML, call this:
$formatter = new RtfHtml();
echo $formatter->Format($reader->root);