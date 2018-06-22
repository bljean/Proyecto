<?php 
// Script start
$rustart = getrusage();
function connectBd(){
    $user='root';
    $pass='';
    $db='proyectofinal';
    $conn= new mysqli('localhost',$user, $pass, $db);
    return $conn;
}
function reconigtion($personid){
    exec("python /PythonProject/PythonCode/takePhoto.py $personid",$output);
    if($output[0]=="1"){
        echo "FUNCIONAAAAAAA";
    }else if($output[0]=="0"){
        echo "no funciona D:";
    }
}
$cardN=5852349;
$sqlStudentName = connectBd()->query( "SELECT nombre, apellido, matricula FROM estudiante WHERE CardNumber='$cardN'");
if($sqlStudentName->num_rows > 0 ){
    while($data= $sqlStudentName->fetch_array()){
        $name=$data["nombre"];
        $apellido=$data["apellido"];
        $personid=$data["matricula"];
       
    }
    reconigtion($personid);
}
function rutime($ru, $rus, $index) {
    return ($ru["ru_$index.tv_sec"]*1000 + intval($ru["ru_$index.tv_usec"]/1000))
     -  ($rus["ru_$index.tv_sec"]*1000 + intval($rus["ru_$index.tv_usec"]/1000));
}

$ru = getrusage();
echo "This process used " . rutime($ru, $rustart, "utime") .
    " ms for its computations\n";
echo "It spent " . rutime($ru, $rustart, "stime") .
    " ms in system calls\n";
 
?>