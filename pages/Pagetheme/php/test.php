<?php 

$command = escapeshellcmd('python /PythonProject/takePhoto.py');
$output = shell_exec($command);
echo $output;

?>