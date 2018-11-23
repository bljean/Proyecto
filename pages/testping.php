<?php
require '/xampp/htdocs/Proyecto/vendor/autoload.php';
date_default_timezone_set('America/Santo_Domingo');


$activate=True;
$options = array(
    'cluster' => 'mt1',
    'encrypted' => true
);
$pusher = new Pusher\Pusher(
    '8b7b30cb5814aead90c6',
    '487f91e47b4bbf226e84',
    '583885',
    $options
);
while ($activate){
    getAulaip();
    sleep(5);
    
}

function getAulaip(){
    $timeout = 2;
    $port=22;
    $sqlSaula=connectBd()->query("SELECT CodCampus,CodEdif,CodSalon,ip,up_down FROM salondocencia WHERE ip is not null");
    if($sqlSaula->num_rows >0 ){
        while($data=$sqlSaula->fetch_array()){
            $CodCampus=$data['CodCampus'];
            $CodEdif=$data['CodEdif'];
            $CodSalon=$data['CodSalon'];
            $ip=$data['ip'];
            $up_down=$data['up_down'];
            
            $latency=pingFsockopen($ip,$port,$timeout);
            print("Aula:".$CodCampus."-".$CodEdif."-".$CodSalon." ip:".$ip." latency: ".$latency."\n");
            if($latency=="timeout" AND $up_down =="up" ){
                connectBd()->query("UPDATE salondocencia SET up_down = 'down' WHERE CodCampus = '$CodCampus' AND CodEdif = '$CodEdif' AND CodSalon = $CodSalon");                       
                notificarping('admin',$CodCampus,$CodEdif,$CodSalon,$ip,'down');
            }elseif($latency!="timeout" AND $up_down =="down"){
                connectBd()->query("UPDATE salondocencia SET up_down = 'up' WHERE CodCampus = '$CodCampus' AND CodEdif = '$CodEdif' AND CodSalon = $CodSalon");                       
                notificarping('admin',$CodCampus,$CodEdif,$CodSalon,$ip,'up');
            }
        }
     
    }
       
}
function pingFsockopen($host,$port,$timeout) {
    $start = microtime(true);
    // fsockopen prints a bunch of errors if a host is unreachable. Hide those
    // irrelevant errors and deal with the results instead.
    $fp = @fsockopen($host, $port, $errno, $errstr, $timeout);
    if (!$fp) {
      $latency ="timeout";
    }
    else {
      $latency = microtime(true) - $start;
      $latency = round($latency * 1000);
    }
    //echo $latency."\n";
    return $latency;
}
function connectBd(){
    $user='root';
    $pass='';
    $db='proyectofinal';
    $conn= new mysqli('localhost',$user, $pass, $db);
    return $conn;
}
function notificarping($ID,$CodCampus,$CodEdif,$CodSalon,$ip,$latency){
    $date = date('Y-m-d');
    $time= date('H:i:s');
    $pusher=$GLOBALS['pusher'];
    $message['message'] = $CodCampus."-".$CodEdif."-".$CodSalon."-".$ip."-".$latency;
    //print($message['message']);
    $pusher->trigger(''.$ID.'', 'my-event', $message);
    //connectBd()->query("INSERT INTO notificacionesadmin (mensaje,CodCampus,CodEdif,CodSalon,fecha,hora) VALUES ('$mensaje','$codcampus','$codedif','$codsalon','$date','$time')");
}

?>