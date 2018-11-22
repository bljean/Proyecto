<?php 
//require '../vendor/autoload.php';
require '/xampp/htdocs/Proyecto/vendor/autoload.php';
date_default_timezone_set('America/Santo_Domingo');
$CodCampus = "";
$CodEdif = "";
$CodSalon =0;

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
if($argv[1]=="presente"){
    if (connectBd()->connect_error) {
        exit("-1");
    }else{
        $ip=$argv[3];
        getsalon_ip();
        getpPerson($argv[2]);
    }
    
    
    //echo $argv[1]." ".$argv[2];
}elseif($argv[1]=="test"){
    $ip=$argv[2];
    getsalon_ip();
    exit($CodCampus."-".$CodEdif."-".$CodSalon); 
}
else{
    if (connectBd()->connect_error) {
        exit("-1");
    }else{
    compareInfo($argv[1]);
    }
}






function connectBd(){
    $user='root';
    $pass='';
    $db='proyectofinal';
    $conn= new mysqli('localhost',$user, $pass, $db);
    return $conn;
}
function compareInfo($cardN){
    $date = date('Y-m-d');
    $time= date('H:i:s');

        $sqlStudentName = connectBd()->query( "SELECT nombre, apellido, Matricula FROM estudiante WHERE NumTarjeta='$cardN'");
        $sqlWorkersName = connectBd()->query( "SELECT nombre, apellido_1, NumCedula FROM trabajadores WHERE NumTarjeta='$cardN'");
        $sqlDataTime = connectBd()->query( "SELECT * FROM swipe WHERE Fecha='$date' AND Tiempo='$time'");
    
        if($sqlStudentName->num_rows > 0 OR $sqlWorkersName->num_rows > 0)
        {
            if($sqlStudentName->num_rows > 0 AND $sqlDataTime->num_rows == 0)
            {
                while($data= $sqlStudentName->fetch_array()){
                    $name=$data["nombre"];
                    $apellido=$data["apellido"];
                    $personid=$data["Matricula"];
                }
                //$index=1;       
                //swipeRecord($cardN,$sqlStudentName,$sqlWorkersName,$data,$time,$index);
                exit($personid);
            }
            if( $sqlWorkersName->num_rows > 0 AND $sqlDataTime->num_rows == 0)
            {
                while($data= $sqlWorkersName->fetch_array()){
                    $name=$data["nombre"];
                    $apellido=$data["apellido_1"];
                    $personid=$data["NumCedula"];
                }
                //$index=1; 
                //swipeRecord($cardN,$sqlStudentName,$sqlWorkersName,$data,$time,$index);
                exit($personid);
            }
        }else{
            exit("-1");
        }
        
}
function getpPerson($cardN){
    $date = date('Y-m-d');
    $time= date('H:i:s');

    $sqlStudentName = connectBd()->query( "SELECT nombre, apellido, Matricula FROM estudiante WHERE NumTarjeta='$cardN'");
    $sqlWorkersName = connectBd()->query( "SELECT nombre, apellido_1, NumCedula FROM trabajadores WHERE NumTarjeta='$cardN'");
    $sqlDataTime = connectBd()->query( "SELECT * FROM swipe WHERE Fecha='$date' AND Tiempo='$time'");
    
        if($sqlStudentName->num_rows > 0 OR $sqlWorkersName->num_rows > 0)
        {
            if($sqlStudentName->num_rows > 0)
            {
                $index=1;       
                swipeRecord($cardN,$sqlStudentName,$sqlWorkersName,$index);
                //return($personid);
                exit("presente");
            }
            if( $sqlWorkersName->num_rows > 0)
            {
                $index=1; 
                swipeRecord($cardN,$sqlStudentName,$sqlWorkersName,$index);
                //return($personid);
                exit("presente");
            }
        }else{
            $index=0; 
            swipeRecord($cardN,$sqlStudentName,$sqlWorkersName,$index);
            $id="-1";
            exit($id) ;
            //return($id);
        }
        
}

function swipeRecord($cardN,$sqlStudentName,$sqlWorkersName,$index){
    
        if($index== 1){

            if($sqlStudentName->num_rows > 0 ){
                while($data= $sqlStudentName->fetch_array()){
                    $name=$data["nombre"];
                    $apellido=$data["apellido"];
                    $personid=$data["Matricula"]; 
                }
                //echo "\n$personid\n";
                getStudentGroup($personid,$cardN,$name,$apellido);
               
            }
            if($sqlWorkersName->num_rows > 0){
                while($data= $sqlWorkersName->fetch_array()){
                    $name=$data["nombre"];
                    $apellido=$data["apellido_1"];
                    $personid=$data["NumCedula"];
                }
                //echo "\n$personid\n";
                getProfesorGroup($personid,$cardN,$name,$apellido);
            }
            //reconigtion($personid);
            //connectBd()->query("INSERT INTO swipe (cardnumber,name, status, dataTime) VALUES('$cardN','$name $apellido','Permitido','$date $time')");
        } else if($index == 0){
            insertSwipeRecord($cardN,'1111111','N/A','N/A','Denegado');
            
        }
}
function getStudentGroup($matricula,$cardN,$name,$apellido){
        $codcampus = $GLOBALS['CodCampus'];
        $codedif =$GLOBALS['CodEdif'];
        $codsalon =$GLOBALS['CodSalon'];
        $date = date('Y-m-d');
        $time= date('H:i:s');
        $day= getWeekday($date);
        $sqlRecoveryGrupo=connectBd()->query( "SELECT gruporecuperarhoras.CodTema as Codtema,gruporecuperarhoras.CodTP as CodTP,gruporecuperarhoras.HoraInicio as HoraInicio ,gruporecuperarhoras.Horafin as Horafin,gruporecuperarhoras.NumGrupo as NumGrupo,gruporecuperarhoras.CodCampus as CodCampus,gruporecuperarhoras.AnoAcad as AnoAcad,gruporecuperarhoras.NumPer as NumPer,Fecha_Recuperar FROM gruporecuperarhoras INNER JOIN grupoinsest on gruporecuperarhoras.Codtema=grupoinsest.Codtema AND gruporecuperarhoras.CodTP=grupoinsest.CodTP AND gruporecuperarhoras.NumGrupo=grupoinsest.NumGrupo AND gruporecuperarhoras.CodCampus=grupoinsest.CodCampus AND gruporecuperarhoras.AnoAcad=grupoinsest.AnoAcad AND gruporecuperarhoras.NumPer=grupoinsest.NumPer AND grupoinsest.Matricula=$matricula AND gruporecuperarhoras.Sal_CodCampus='$codcampus' AND gruporecuperarhoras.Sal_CodEdif='$codedif' AND gruporecuperarhoras.Sal_CodSalon=$codsalon AND gruporecuperarhoras.HoraInicio<='$time' AND gruporecuperarhoras.Horafin >= '$time' AND gruporecuperarhoras.Fecha='$date'");
        $sqlStudentGrupo=connectBd()->query( "SELECT horariogrupoactivo.CodTema as Codtema,horariogrupoactivo.CodTP as CodTP,horariogrupoactivo.HoraInicio as HoraInicio ,horariogrupoactivo.Horafin as Horafin,horariogrupoactivo.NumGrupo as NumGrupo,horariogrupoactivo.CodCampus as CodCampus,horariogrupoactivo.AnoAcad as AnoAcad,horariogrupoactivo.NumPer as NumPer FROM horariogrupoactivo INNER JOIN grupoinsest on horariogrupoactivo.Codtema=grupoinsest.Codtema AND horariogrupoactivo.CodTP=grupoinsest.CodTP AND horariogrupoactivo.NumGrupo=grupoinsest.NumGrupo AND horariogrupoactivo.CodCampus=grupoinsest.CodCampus AND horariogrupoactivo.AnoAcad=grupoinsest.AnoAcad AND horariogrupoactivo.NumPer=grupoinsest.NumPer AND grupoinsest.Matricula= $matricula AND horariogrupoactivo.Sal_CodCampus='$codcampus' AND horariogrupoactivo.Sal_CodEdif='$codedif' AND horariogrupoactivo.Sal_CodSalon=$codsalon AND horariogrupoactivo.HoraInicio<='$time' AND horariogrupoactivo.Horafin >= '$time' AND horariogrupoactivo.DiaSem='$day'");
        if($sqlRecoveryGrupo->num_rows >0){
            while($data= $sqlRecoveryGrupo->fetch_array()){
                $horaini=$data["HoraInicio"];
                $horafin=$data["Horafin"];
                $NumGrupo=$data["NumGrupo"];
                $Codtema=$data["Codtema"];
                $CodTP=$data["CodTP"];
                $CodCampus=$data["CodCampus"];
                $AnoAcad=$data["AnoAcad"];
                $NumPer=$data["NumPer"];
                attendEstRecord($matricula,$date,$horaini,$time,$horafin,$day,$Codtema,$CodTP,$CodCampus,$NumGrupo,$AnoAcad,$NumPer,'R',$cardN,$name,$apellido,'E','nada','nada','nada');
            }
        }elseif($sqlStudentGrupo->num_rows >0){
            while($data= $sqlStudentGrupo->fetch_array()){
                $horaini=$data["HoraInicio"];
                $horafin=$data["Horafin"];
                $NumGrupo=$data["NumGrupo"];
                $Codtema=$data["Codtema"];
                $CodTP=$data["CodTP"];
                $CodCampus=$data["CodCampus"];
                $AnoAcad=$data["AnoAcad"];
                $NumPer=$data["NumPer"];
            }
            attendEstRecord($matricula,$date,$horaini,$time,$horafin,$day,$Codtema,$CodTP,$CodCampus,$NumGrupo,$AnoAcad,$NumPer,'P',$cardN,$name,$apellido,'E','nada','nada','nada');
        }else {
            insertSwipeRecord($cardN,$matricula,$name,$apellido,'Denegado');
            //exit("no-clases-".$codcampus."-".$codcampus."-".$codedif."-".$codsalon."-".$date."-".$time."-".$day."-".$matricula);
        }
}
function getProfesorGroup($numCedula,$cardN,$name,$apellido){
        $codcampus = $GLOBALS['CodCampus'];
        $codedif =$GLOBALS['CodEdif'];
        $codsalon =$GLOBALS['CodSalon'];
        $date = date('Y/m/d');
        $time= date('H:i:s');
        $day= getWeekday($date);
        $sqlRecoveryGrupo=connectBd()->query( "SELECT gruporecuperarhoras.CodTema as Codtema,gruporecuperarhoras.CodTP as CodTP,gruporecuperarhoras.HoraInicio as HoraInicio ,gruporecuperarhoras.Horafin as Horafin,gruporecuperarhoras.NumGrupo as NumGrupo,gruporecuperarhoras.CodCampus as CodCampus,gruporecuperarhoras.AnoAcad as AnoAcad,gruporecuperarhoras.NumPer as NumPer,Fecha_Recuperar FROM gruporecuperarhoras INNER JOIN contratodocencia on gruporecuperarhoras.Codtema=contratodocencia.Codtema AND gruporecuperarhoras.CodTP=contratodocencia.CodTP AND gruporecuperarhoras.NumGrupo=contratodocencia.NumGrupo AND gruporecuperarhoras.CodCampus=contratodocencia.CodCampus AND gruporecuperarhoras.AnoAcad=contratodocencia.AnoAcad AND gruporecuperarhoras.NumPer=contratodocencia.NumPer AND contratodocencia.NumCedula=$numCedula AND gruporecuperarhoras.Sal_CodCampus='$codcampus' AND gruporecuperarhoras.Sal_CodEdif='$codedif' AND gruporecuperarhoras.Sal_CodSalon=$codsalon AND gruporecuperarhoras.HoraInicio<='$time' AND gruporecuperarhoras.Horafin >= '$time' AND gruporecuperarhoras.Fecha='$date'");
        $sqlProfessorGrupo=connectBd()->query( "SELECT horariogrupoactivo.CodTema as Codtema,horariogrupoactivo.CodTP as CodTP,horariogrupoactivo.HoraInicio as HoraInicio ,horariogrupoactivo.Horafin as Horafin,horariogrupoactivo.NumGrupo as NumGrupo,horariogrupoactivo.CodCampus as CodCampus,horariogrupoactivo.AnoAcad as AnoAcad,horariogrupoactivo.NumPer as NumPer FROM horariogrupoactivo INNER JOIN contratodocencia on horariogrupoactivo.Codtema=contratodocencia.Codtema AND horariogrupoactivo.CodTP=contratodocencia.CodTP AND horariogrupoactivo.NumGrupo=contratodocencia.NumGrupo AND horariogrupoactivo.CodCampus=contratodocencia.CodCampus AND horariogrupoactivo.AnoAcad=contratodocencia.AnoAcad AND horariogrupoactivo.NumPer=contratodocencia.NumPer AND contratodocencia.NumCedula=$numCedula AND horariogrupoactivo.Sal_CodCampus='$codcampus' AND horariogrupoactivo.Sal_CodEdif='$codedif' AND horariogrupoactivo.Sal_CodSalon=$codsalon AND horariogrupoactivo.HoraInicio<='$time' AND horariogrupoactivo.Horafin >= '$time' AND horariogrupoactivo.DiaSem='$day'");
        $sqlProfessorSustiGrupo= connectBd()->query("SELECT horariogrupoactivo.CodTema as Codtema,horariogrupoactivo.CodTP as CodTP,horariogrupoactivo.HoraInicio as HoraInicio ,horariogrupoactivo.Horafin as Horafin,horariogrupoactivo.NumGrupo as NumGrupo,horariogrupoactivo.CodCampus as CodCampus,horariogrupoactivo.AnoAcad as AnoAcad,horariogrupoactivo.NumPer as NumPer,sustituto.NumCedula as NumCedula, trabajadores.nombre as nombreprofe, trabajadores.apellido_1 as apellidoprofe  FROM horariogrupoactivo INNER JOIN sustituto on horariogrupoactivo.Codtema=sustituto.Codtema AND horariogrupoactivo.CodTP=sustituto.CodTP AND horariogrupoactivo.NumGrupo=sustituto.NumGrupo AND horariogrupoactivo.CodCampus=sustituto.CodCampus AND horariogrupoactivo.AnoAcad=sustituto.AnoAcad AND horariogrupoactivo.NumPer=sustituto.NumPer AND sustituto.NumCedulaSusti=$numCedula AND horariogrupoactivo.Sal_CodCampus='$codcampus' AND horariogrupoactivo.Sal_CodEdif='$codedif' AND horariogrupoactivo.Sal_CodSalon='$codsalon' AND horariogrupoactivo.HoraInicio<='$time' AND horariogrupoactivo.Horafin >= '$time' AND horariogrupoactivo.DiaSem='$day' AND sustituto.Fecha='$date'INNER JOIN trabajadores on trabajadores.NumCedula= sustituto.NumCedula");
        
        $sqlProfessor=connectBd()->query("SELECT Codtema FROM contratodocencia WHERE NumCedula=$numCedula");
        if($sqlRecoveryGrupo->num_rows >0){
            while($data= $sqlRecoveryGrupo->fetch_array()){
                $horaini=$data["HoraInicio"];
                $horafin=$data["Horafin"];
                $NumGrupo=$data["NumGrupo"];
                $Codtema=$data["Codtema"];
                $CodTP=$data["CodTP"];
                $CodCampus=$data["CodCampus"];
                $AnoAcad=$data["AnoAcad"];
                $NumPer=$data["NumPer"];
                attendEstRecord($numCedula,$date,$horaini,$time,$horafin,$day,$Codtema,$CodTP,$CodCampus,$NumGrupo,$AnoAcad,$NumPer,'R',$cardN,$name,$apellido,'P',$numCedula,'nada','nada');
            }
        }elseif($sqlProfessorGrupo->num_rows >0){
            while($data= $sqlProfessorGrupo->fetch_array()){
                $horaini=$data["HoraInicio"];
                $horafin=$data["Horafin"];
                $NumGrupo=$data["NumGrupo"];
                $Codtema=$data["Codtema"];
                $CodTP=$data["CodTP"];
                $CodCampus=$data["CodCampus"];
                $AnoAcad=$data["AnoAcad"];
                $NumPer=$data["NumPer"];
            }
            attendEstRecord($numCedula,$date,$horaini,$time,$horafin,$day,$Codtema,$CodTP,$CodCampus,$NumGrupo,$AnoAcad,$NumPer,'P',$cardN,$name,$apellido,'P',$numCedula,'nada','nada');
        }elseif($sqlProfessorSustiGrupo->num_rows >0){
            while($data= $sqlProfessorSustiGrupo->fetch_array()){
                $horaini=$data["HoraInicio"];
                $horafin=$data["Horafin"];
                $NumGrupo=$data["NumGrupo"];
                $Codtema=$data["Codtema"];
                $CodTP=$data["CodTP"];
                $CodCampus=$data["CodCampus"];
                $AnoAcad=$data["AnoAcad"];
                $NumPer=$data["NumPer"];
                $NumCedula=$data["NumCedula"];
                $nombreprofe=$data["nombreprofe"];
                $apellidoprofe=$data["apellidoprofe"];
            }
            attendEstRecord($NumCedula,$date,$horaini,$time,$horafin,$day,$Codtema,$CodTP,$CodCampus,$NumGrupo,$AnoAcad,$NumPer,'P',$cardN,$name,$apellido,'S',$numCedula,$nombreprofe,$apellidoprofe);
        }elseif($sqlProfessor->num_rows >0){
            //echo "no abrir puerta";
            insertSwipeRecord($cardN,$numCedula,$name,$apellido,'Denegado');
        }else{
            //echo"abrir puerta al trabajador";
            insertSwipeRecord($cardN,$numCedula,$name,$apellido,'Permitido');
            openDoor();
        }
        return  $sqlProfessorGrupo;
}
function attendEstRecord($matricula,$date,$horaini,$time,$horafin,$day,$Codtema,$CodTP,$CodCampus,$NumGrupo,$AnoAcad,$NumPer,$Precencia,$cardN,$name,$apellido,$estado,$numCedula,$nombreprofe,$apellidoprofe){
            $codcampus = $GLOBALS['CodCampus'];
            $codedif =$GLOBALS['CodEdif'];
            $codsalon =$GLOBALS['CodSalon'];
            $horasPresente=totalHorasAsistencia($horaini,$horafin,$time,$Precencia);
            $sqlStudentattend = connectBd()->query( "SELECT * FROM asistencia WHERE ID='$matricula' AND Fecha='$date' AND Horaini='$horaini'");
            if($Precencia=='R'){
                if($sqlStudentattend->num_rows > 0){
                    //echo "ya esta precente";
                    if($estado=='S'){
                        insertSwipeRecord($cardN,$matricula,$name,$apellido,'Permitido');
                    }else{
                        insertSwipeRecord($cardN,$matricula,$name,$apellido,'Permitido');
                    }
                }else {
                    if($estado=='S'){
                        connectBd()->query("INSERT INTO asistencia (ID,Fecha,Horaini,Horaentrada,Horafin,HorasPresente,Sal_CodCampus,Sal_CodEdif,Sal_CodSalon,Diasemana,Presencia,NumGrupo,CodTema,CodTP,CodCampus,AnoAcad,NumPer) VALUES('$matricula','$date','$horaini','$time','$horafin','$horasPresente','$codcampus','$codedif','$codsalon','$day','$Precencia','$NumGrupo','$Codtema','$CodTP','$CodCampus','$AnoAcad','$NumPer')");
                        $mensaje='Se ha generado la presencia de '.$nombreprofe.' '.$apellidoprofe.' en el grupo '.$CodCampus.'-'.$Codtema.'-'.$CodTP.'-'.$NumGrupo.' en la fecha '.$date.' a la hora'.$time.' ';
                        notificargrupo($CodCampus,$Codtema,$CodTP,$NumGrupo,$AnoAcad,$NumPer,$mensaje,$numCedula);
                        notificargrupo($CodCampus,$Codtema,$CodTP,$NumGrupo,$AnoAcad,$NumPer,$mensaje,$matricula);
                        insertSwipeRecord($cardN,$matricula,$name,$apellido,'Permitido');
                      
                    }else{
                        connectBd()->query("INSERT INTO asistencia (ID,Fecha,Horaini,Horaentrada,Horafin,HorasPresente,Sal_CodCampus,Sal_CodEdif,Sal_CodSalon,Diasemana,Presencia,NumGrupo,CodTema,CodTP,CodCampus,AnoAcad,NumPer) VALUES('$matricula','$date','$horaini','$time','$horafin','$horasPresente','$codcampus','$codedif','$codsalon','$day','$Precencia','$NumGrupo','$Codtema','$CodTP','$CodCampus','$AnoAcad','$NumPer')");
                        $mensaje='Se ha generado la recuperacion de '.$name.' '.$apellido.' en el grupo '.$CodCampus.'-'.$Codtema.'-'.$CodTP.'-'.$NumGrupo.' en la fecha '.$date.' a la hora'.$time.' ';
                        notificargrupo($CodCampus,$Codtema,$CodTP,$NumGrupo,$AnoAcad,$NumPer,$mensaje,$matricula);
                        notificargrupo($CodCampus,$Codtema,$CodTP,$NumGrupo,$AnoAcad,$NumPer,$mensaje,$Codtema);
                        insertSwipeRecord($cardN,$matricula,$name,$apellido,'Permitido');
                    }
                } 
            }else{
                if($sqlStudentattend->num_rows > 0){
                    //echo "ya esta precente";
                    if($estado=='S'){
                        insertSwipeRecord($cardN,$matricula,$name,$apellido,'Permitido');
                    }else{
                        insertSwipeRecord($cardN,$matricula,$name,$apellido,'Permitido');
                    }
                }else{
                    if($Precencia=='A' OR $Precencia=='PR' OR $Precencia=='FR'){
                        //Ausensia, PorRecuperar,FalloRecuperacion.
                        if($Precencia=='A'){
                            $mensaje='Se ha generado la ausencia de '.$name.' '.$apellido.' en el grupo '.$CodCampus.'-'.$Codtema.'-'.$CodTP.'-'.$NumGrupo.' en la fecha '.$date.' a la hora'.$time.' ';
                        }
                        if($Precencia=='PR'){
                            $mensaje='Se ha generado la Por Recuperar de '.$name.' '.$apellido.' en el grupo '.$CodCampus.'-'.$Codtema.'-'.$CodTP.'-'.$NumGrupo.' en la fecha '.$date.' a la hora'.$time.' ';
                        }
                        if($Precencia=='FR'){
                            $mensaje='a Fallado la Recuperarcion de grupo '.$CodCampus.'-'.$Codtema.'-'.$CodTP.'-'.$NumGrupo.' en la fecha '.$date.' a la hora'.$time.' ';
                        }
                        notificargrupo($CodCampus,$Codtema,$CodTP,$NumGrupo,$AnoAcad,$NumPer,$mensaje,$matricula);
                        connectBd()->query("INSERT INTO asistencia (ID,Fecha,Horaini,Horafin,HorasPresente,Sal_CodCampus,Sal_CodEdif,Sal_CodSalon,Diasemana,Presencia,NumGrupo,CodTema,CodTP,CodCampus,AnoAcad,NumPer) VALUES('$matricula','$date','$horaini','$horafin','0','$codcampus','$codedif','$codsalon','$day','$Precencia','$NumGrupo','$Codtema','$CodTP','$CodCampus','$AnoAcad','$NumPer')");
                    }else {
                        //sustituto
                        if($estado=='S'){
                            connectBd()->query("INSERT INTO asistencia (ID,Fecha,Horaini,Horaentrada,Horafin,HorasPresente,Sal_CodCampus,Sal_CodEdif,Sal_CodSalon,Diasemana,Presencia,NumGrupo,CodTema,CodTP,CodCampus,AnoAcad,NumPer) VALUES('$matricula','$date','$horaini','$time','$horafin','$horasPresente','$codcampus','$codedif','$codsalon','$day','$Precencia','$NumGrupo','$Codtema','$CodTP','$CodCampus','$AnoAcad','$NumPer')");
                            $mensaje='Se ha generado la presencia de '.$nombreprofe.' '.$apellidoprofe.' en el grupo '.$CodCampus.'-'.$Codtema.'-'.$CodTP.'-'.$NumGrupo.' en la fecha '.$date.' a la hora'.$time.' ';
                            notificargrupo($CodCampus,$Codtema,$CodTP,$NumGrupo,$AnoAcad,$NumPer,$mensaje,$numCedula);
                            notificargrupo($CodCampus,$Codtema,$CodTP,$NumGrupo,$AnoAcad,$NumPer,$mensaje,$matricula);
                            insertSwipeRecord($cardN,$matricula,$name,$apellido,'Permitido');
                        }else{
                            connectBd()->query("INSERT INTO asistencia (ID,Fecha,Horaini,Horaentrada,Horafin,HorasPresente,Sal_CodCampus,Sal_CodEdif,Sal_CodSalon,Diasemana,Presencia,NumGrupo,CodTema,CodTP,CodCampus,AnoAcad,NumPer) VALUES('$matricula','$date','$horaini','$time','$horafin','$horasPresente','$codcampus','$codedif','$codsalon','$day','$Precencia','$NumGrupo','$Codtema','$CodTP','$CodCampus','$AnoAcad','$NumPer')");
                            $mensaje='Se ha generado la presencia de '.$name.' '.$apellido.' en el grupo '.$CodCampus.'-'.$Codtema.'-'.$CodTP.'-'.$NumGrupo.' en la fecha '.$date.' a la hora'.$time.' ';
                            insertSwipeRecord($cardN,$matricula,$name,$apellido,'Permitido');
                            //notificacion
                            notificargrupo($CodCampus,$Codtema,$CodTP,$NumGrupo,$AnoAcad,$NumPer,$mensaje,$matricula);
                        }
                       
                    } 
                }  
            }
                  
}
function totalHorasAsistencia($horaIni,$horaFin,$horaEntrada,$precencia){
    $time1 = strtotime($horaIni);
    $time2 = strtotime($horaFin);
    $time3 = strtotime($horaEntrada);
    $totalHoras = round(abs($time2 - $time1) / 3600,2);
    if($precencia=="P" || $precencia=="R"){
        $totalHorasPresente = round(abs($time2 - $time3) / 3600,2);
        $whole = floor($totalHorasPresente);      
        $fraction = $totalHorasPresente - $whole;
       if($fraction < 0.7){
        $totalHorasPresente= intval($totalHorasPresente);
       }else if($fraction >= 0.7){
        $totalHorasPresente += 0.5;
        $totalHorasPresente= intval($totalHorasPresente);
       } 
       return $totalHorasPresente;
    }
    return totalhorasgrupo($time1,$time2);
   
} 
function totalhorasgrupo($time1,$time2){
    $time1 = strtotime($time1);
    $time2 = strtotime($time2);
    $totalHoras = round(abs($time2 - $time1) / 3600,2);
    $t = $totalHoras;
    $whole = floor($t);      
    $fraction = $t - $whole;
    $minute = ($fraction * 0.6)*100;
    //echo intval($t),"h", $minute,"\n";
    $thorastime=mktime(intval($t),$minute ); 
    $horas=date("h:i", $thorastime);
    
    return $horas;
}
function notificargrupo($CodCampus,$CodTema,$CodTP,$Numgrupo,$AnoAcad,$Numper,$mensaje,$ID){
    $pusher=$GLOBALS['pusher'];
    $message['message'] = $mensaje;
    $date = date('Y-m-d');
    $time= date('H:i:s');
    /*$sqlemail=connectBd()->query("SELECT usuario FROM trabajadores WHERE NumCedula='$ID'");
    if($sqlemail->num_rows > 0){
        while($data= $sqlemail->fetch_array()){
        // send email
        $usuario=$data["usuario"];
        //mail(''.$usuario.'@ce.pucmm.edu.do',"Sistema",$mensaje);
        }
     }*/
 
    
    $pusher->trigger(''.$ID.'', 'my-event', $message);
    connectBd()->query("INSERT INTO notificaciones (ID,mensaje,estado,autor,fecha,Hora,CodTema,CodTp,NumGrupo,CodCampus,AnoAcad,NumPer) VALUES ('$ID', '$mensaje', '0','Sistema', '$date', '$time','$CodTema','$CodTP','$Numgrupo','$CodCampus','$AnoAcad','$Numper')");
    
}
function notificaradmin($ID,$mensaje){
    $codcampus = $GLOBALS['CodCampus'];
    $codedif =$GLOBALS['CodEdif'];
    $codsalon =$GLOBALS['CodSalon'];
    $date = date('Y-m-d');
    $time= date('H:i:s');
    $pusher=$GLOBALS['pusher'];
    $message['message'] = $mensaje;
    $pusher->trigger(''.$ID.'', 'my-event', $message);
    connectBd()->query("INSERT INTO notificacionesadmin (mensaje,CodCampus,CodEdif,CodSalon,fecha,hora) VALUES ('$mensaje','$codcampus','$codedif','$codsalon','$date','$time')");
}
function insertSwipeRecord($NumTarjeta,$ID,$Nombre,$apellido,$Acceso){
    
    $codcampus = $GLOBALS['CodCampus'];
    $codedif =$GLOBALS['CodEdif'];
    $codsalon =$GLOBALS['CodSalon'];
    $date = date('Y-m-d');
    $time= date('H:i:s');
    $day= getWeekday($date);
    //echo "\n$NumTarjeta,$Nombre,$apellido,$Acceso, $codcampus,$codedif,$codsalon,$date,$time\n";
    connectBd()->query("INSERT INTO swipe (NumTarjeta,ID,Nombre,Acceso,Sal_CodCampus,Sal_CodEdif,Sal_CodSalon,Fecha,Tiempo) VALUES('$NumTarjeta','$ID','$Nombre $apellido','$Acceso','$codcampus','$codedif','$codsalon','$date','$time')");
}
function getWeekday($date) {
    return date('w', strtotime($date));
}
function getsalon_ip(){
    $Ip = $GLOBALS['ip'];
    $sqlAula_ip = connectBd()->query( "SELECT CodCampus, CodEdif,CodSalon FROM salondocencia WHERE ip='$Ip'");
    if($sqlAula_ip->num_rows >0){
        while($data= $sqlAula_ip->fetch_array()){
            $CodCampus=$data["CodCampus"];
            $CodEdif = $data["CodEdif"];
            $CodSalon = $data["CodSalon"];
        }
    }
    $GLOBALS['CodCampus']=$CodCampus;
    $GLOBALS['CodEdif']= $CodEdif;
    $GLOBALS['CodSalon']=$CodSalon;
}
?>
 
