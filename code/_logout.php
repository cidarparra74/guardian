<?php

	session_start();
	$_SESSION = array();
	session_destroy();

	//header("Location: ../index.html");
?>
<html>
<head>
<SCRIPT LANGUAGE="JavaScript">
window.parent.frames[1].location="../templates/_cabecera.html";
window.parent.frames[2].location="../templates/_cabecera.html";
setTimeout ("breakOut()", 1000);
function breakOut() {
	if (self.parent.frames.length != 0){
		self.parent.location="../index.html";
	}
} 
</SCRIPT>
</head>
<body>
Finalizando...
</body>
</html>
