<?php 
require '/xampp/htdocs/Proyecto/vendor/autoload.php';
date_default_timezone_set('America/Santo_Domingo');
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


    checkGroupTime();


function connectBd(){
    $user='root';
    $pass='';
    $db='proyectofinal';
    $conn= new mysqli('localhost',$user, $pass, $db);
    return $conn;
}
function checkGroupTime(){
    
    $date = date('Y/m/d');
    $time= date('H:i:s');
    $day= getWeekday($date);
    $sqlhorarioRecoverytime=connectBd()->query("SELECT Codtema, CodTP,HoraInicio , Horafin, NumGrupo, CodCampus, AnoAcad,NumPer,Sal_CodCampus,Sal_CodEdif,Sal_CodSalon FROM gruporecuperarhoras WHERE HoraInicio<='$time' AND Horafin >= '$time' AND Fecha='$date'");
    $sqlHorariogrupotime= connectBd()->query("SELECT Codtema, CodTP,HoraInicio , Horafin, NumGrupo, CodCampus, AnoAcad,NumPer,Sal_CodCampus,Sal_CodEdif,Sal_CodSalon FROM horariogrupoactivo WHERE horariogrupoactivo.HoraInicio<='$time' AND horariogrupoactivo.Horafin >= '$time' AND horariogrupoactivo.DiaSem='$day'");
    if($sqlhorarioRecoverytime->num_rows >0){
        while($data= $sqlhorarioRecoverytime->fetch_array()){
            $HoraInicio=$data['HoraInicio'];
            $Horafin=$data['Horafin'];
            $NumGrupo=$data["NumGrupo"];
            $Codtema=$data["Codtema"];
            $CodTP=$data["CodTP"];
            $CodCampus=$data["CodCampus"];
            $AnoAcad=$data["AnoAcad"];
            $NumPer=$data["NumPer"];
            $codcampus=$data["Sal_CodCampus"];
            $codedif=$data["Sal_CodEdif"];
            $codsalon=$data["Sal_CodSalon"];
            ausencia($HoraInicio,$Horafin,$Codtema,$CodTP,$CodCampus,$NumGrupo,$AnoAcad,$NumPer,$codcampus,$codedif,$codsalon);
        }
        
       }elseif($sqlHorariogrupotime->num_rows >0){
        while($data= $sqlHorariogrupotime->fetch_array()){
            $HoraInicio=$data['HoraInicio'];
            $Horafin=$data['Horafin'];
            $NumGrupo=$data["NumGrupo"];
            $Codtema=$data["Codtema"];
            $CodTP=$data["CodTP"];
            $CodCampus=$data["CodCampus"];
            $AnoAcad=$data["AnoAcad"];
            $NumPer=$data["NumPer"];
            $codcampus=$data["Sal_CodCampus"];
            $codedif=$data["Sal_CodEdif"];
            $codsalon=$data["Sal_CodSalon"];
            ausencia($HoraInicio,$Horafin,$Codtema,$CodTP,$CodCampus,$NumGrupo,$AnoAcad,$NumPer,$codcampus,$codedif,$codsalon);
        }
       
       }else echo "No grupo a esta hora\n";
}
function ausencia($horaini,$horafin,$Codtema,$CodTP,$CodCampus,$NumGrupo,$AnoAcad,$NumPer,$codcampus,$codedif,$codsalon){
    $date = date('Y-m-d');
    $time= date('H:i:s');
    $day= getWeekday($date);
    $horadeAusencia = getHorapresencia($horaini,$horafin,$Codtema,$CodTP,$CodCampus,$NumGrupo,$AnoAcad,$NumPer);
    $horas=totalhorasgrupo($horaini,$horafin);
    echo"tiempo: $time\n";
    echo"hora de ausencia:$horadeAusencia\n";
    if($time > $horadeAusencia){
        $sqlhorarioRecoverytime=connectBd()->query("SELECT Codtema, CodTP,HoraInicio , Horafin, NumGrupo, CodCampus, AnoAcad, NumPer FROM gruporecuperarhoras WHERE Sal_CodCampus='$codcampus' AND Sal_CodEdif='$codedif' AND Sal_CodSalon='$codsalon' AND HoraInicio<='$time' AND Horafin >= '$time' AND Fecha='$date'");
        $sqlAusentesEst=connectBd()->query("SELECT grupoinsest.Matricula as Matricula FROM grupoinsest LEFT JOIN asistencia ON asistencia.ID=grupoinsest.Matricula AND asistencia.CodTema=grupoinsest.CodTema AND asistencia.CodTP= grupoinsest.CodTP AND asistencia.CodCampus= grupoinsest.CodCampus AND asistencia.NumGrupo= grupoinsest.Numgrupo AND asistencia.AnoAcad=grupoinsest.AnoAcad AND asistencia.NumPer= grupoinsest.NumPer AND asistencia.Fecha= '$date' WHERE grupoinsest.CodTema='$Codtema' AND grupoinsest.CodTP='$CodTP' AND grupoinsest.CodCampus='$CodCampus' AND grupoinsest.Numgrupo='$NumGrupo' AND grupoinsest.AnoAcad='$AnoAcad' AND grupoinsest.NumPer='$NumPer' AND asistencia.ID is NULL");
        $sqlAusenteProf=connectBd()->query("SELECT contratodocencia.NumCedula as NumCedula FROM contratodocencia LEFT JOIN asistencia ON asistencia.ID=contratodocencia.NumCedula AND asistencia.CodTema=contratodocencia.CodTema AND asistencia.CodTP= contratodocencia.CodTp AND asistencia.CodCampus= contratodocencia.CodCampus AND asistencia.NumGrupo= contratodocencia.Numgrupo AND asistencia.AnoAcad=contratodocencia.AnoAcad AND asistencia.NumPer= contratodocencia.NumPer AND asistencia.Fecha= '$date' WHERE contratodocencia.CodTema='$Codtema' AND contratodocencia.CodTP='$CodTP' AND contratodocencia.CodCampus='$CodCampus' AND contratodocencia.Numgrupo='$NumGrupo' AND contratodocencia.AnoAcad='$AnoAcad' AND contratodocencia.NumPer='$NumPer' AND asistencia.ID is NULL");
        
        if($sqlhorarioRecoverytime->num_rows>0){
            if($sqlAusenteProf->num_rows>0 && $time >= getHorausencia($horafin) ){
                $sqlPresentesEst=connectBd()->query("SELECT asistencia.ID as Matricula FROM grupoinsest LEFT JOIN asistencia ON asistencia.ID=grupoinsest.Matricula AND asistencia.CodTema=grupoinsest.CodTema AND asistencia.CodTP= grupoinsest.CodTP AND asistencia.CodCampus= grupoinsest.CodCampus AND asistencia.NumGrupo= grupoinsest.Numgrupo AND asistencia.AnoAcad=grupoinsest.AnoAcad AND asistencia.NumPer= grupoinsest.NumPer AND asistencia.Fecha= '$date' WHERE grupoinsest.CodTema='$Codtema' AND grupoinsest.CodTP='$CodTP' AND grupoinsest.CodCampus='$CodCampus' AND grupoinsest.Numgrupo='$NumGrupo' AND grupoinsest.AnoAcad='$AnoAcad' AND grupoinsest.NumPer='$NumPer' AND asistencia.Presencia='R' ");
                
                while($data=$sqlAusenteProf->fetch_array()){
                    $NumCedula=$data['NumCedula'];
                    attendEstRecord($data['NumCedula'],$date,$horaini,$time,$horafin,getWeekday($date),$Codtema,$CodTP,$CodCampus,$NumGrupo,$AnoAcad,$NumPer,'FR','1111','nada','nada','nada','nada',$codcampus,$codedif,$codsalon);
                    $sqlNombreprof=connectBd()->query("SELECT nombre,apellido_1 FROM trabajadores WHERE NumCedula='$NumCedula'");
                    while($data=$sqlNombreprof->fetch_array()){
                        $name=$data['nombre'];
                        $apellido=$data['apellido_1'];
                    }
                    $mensaje='Se ha generado la Falta de Recuperacion de '.$name.' '.$apellido.' en el grupo '.$CodCampus.'-'.$Codtema.'-'.$CodTP.'-'.$NumGrupo.' en la fecha '.$date.' a la hora'.$time.' ';
                    notificargrupo($CodCampus,$Codtema,$CodTP,$NumGrupo,$AnoAcad,$NumPer,$mensaje,$Codtema);
                }
                if($sqlPresentesEst->num_rows>0){
                    while($data=$sqlPresentesEst->fetch_array()){
                        $matricula=$data['Matricula'];
                        $sqlnombreEstudiante=connectBd()->query("SELECT nombre,apellido FROM estudiante WHERE Matricula='$Matricula'");
                        while($data=$sqlnombreEstudiante->fetch_array()){
                            $name=$data['nombre'];
                            $apellido=$data['apellido'];
                        }
                        $mensaje='Se ha generado la Falta de Recuperacion de '.$name.' '.$apellido.' en el grupo '.$CodCampus.'-'.$Codtema.'-'.$CodTP.'-'.$NumGrupo.' en la fecha '.$date.' a la hora'.$time.' ';
                        notificargrupo($CodCampus,$Codtema,$CodTP,$NumGrupo,$AnoAcad,$NumPer,$mensaje,$matricula);
                        connectBd()->query("UPDATE asistencia SET Presencia = 'FR', HorasPresente='0' WHERE asistencia.ID = '$matricula' AND asistencia.Fecha = '$date' AND asistencia.Horaini = '$horaini' AND asistencia.NumGrupo =$NumGrupo AND asistencia.CodTema = '$Codtema' AND asistencia.CodTP = $CodTP AND asistencia.CodCampus = '$CodCampus' AND asistencia.AnoAcad = $AnoAcad AND asistencia.NumPer = $NumPer");
                    }
                    
                }
                if($sqlAusentesEst->num_rows>0){
                    while($data=$sqlAusentesEst->fetch_array()){
                        attendEstRecord($data['Matricula'],$date,$horaini,$time,$horafin,getWeekday($date),$Codtema,$CodTP,$CodCampus,$NumGrupo,$AnoAcad,$NumPer,'FR','1111','nada','nada','nada','nada',$codcampus,$codedif,$codsalon);
                    }
                }    
            }else{
                connectBd()->query("UPDATE gruporecuperar SET PR_o_R='R' WHERE Codtema='$Codtema' AND CodTP='$CodTP' AND CodCampus='$CodCampus' AND NumGrupo='$NumGrupo' AND AnoAcad='$AnoAcad' AND NumPer='$NumPer' AND Fecha_Recuperar='$date' ");
               
                if($sqlAusentesEst->num_rows>0){
                    while($data=$sqlAusentesEst->fetch_array()){
                        attendEstRecord($data['Matricula'],$date,$horaini,$time,$horafin,getWeekday($date),$Codtema,$CodTP,$CodCampus,$NumGrupo,$AnoAcad,$NumPer,'A','1111','nada','nada','nada','nada',$codcampus,$codedif,$codsalon);
                    }
                }
            }
        }else{
            if($sqlAusenteProf->num_rows>0 && $time >= getHorausencia($horafin)  ){
                $sqlPresentesEst=connectBd()->query("SELECT asistencia.ID as Matricula FROM grupoinsest LEFT JOIN asistencia ON asistencia.ID=grupoinsest.Matricula AND asistencia.CodTema=grupoinsest.CodTema AND asistencia.CodTP= grupoinsest.CodTP AND asistencia.CodCampus= grupoinsest.CodCampus AND asistencia.NumGrupo= grupoinsest.Numgrupo AND asistencia.AnoAcad=grupoinsest.AnoAcad AND asistencia.NumPer= grupoinsest.NumPer AND asistencia.Fecha= '$date' WHERE grupoinsest.CodTema='$Codtema' AND grupoinsest.CodTP='$CodTP' AND grupoinsest.CodCampus='$CodCampus' AND grupoinsest.Numgrupo='$NumGrupo' AND grupoinsest.AnoAcad='$AnoAcad' AND grupoinsest.NumPer='$NumPer' AND asistencia.Presencia='P' ");
                connectBd()->query("INSERT INTO gruporecuperar (CodTema, CodTp, NumGrupo, CodCampus, AnoAcad, NumPer, PR_o_R, Fecha_Recuperar,Horas) VALUES ('$Codtema', '$CodTP', '$NumGrupo', '$CodCampus', '$AnoAcad', '$NumPer', 'PR', '$date','$horas')");
                while($data=$sqlAusenteProf->fetch_array()){
                    $NumCedula=$data['NumCedula'];
                    attendEstRecord($NumCedula,$date,$horaini,$time,$horafin,getWeekday($date),$Codtema,$CodTP,$CodCampus,$NumGrupo,$AnoAcad,$NumPer,'PR','1111','nada','nada','nada','nada','nada','nada',$codcampus,$codedif,$codsalon);
                    $sqlNombreprof=connectBd()->query("SELECT nombre,apellido_1 FROM trabajadores WHERE NumCedula='$NumCedula'");
                    while($data=$sqlNombreprof->fetch_array()){
                        $name=$data['nombre'];
                        $apellido=$data['apellido_1'];
                    }
                    $mensaje='Se ha generado la ausencia de '.$name.' '.$apellido.' en el grupo '.$CodCampus.'-'.$Codtema.'-'.$CodTP.'-'.$NumGrupo.' en la fecha '.$date.' a la hora'.$time.' ';
                    notificargrupo($CodCampus,$Codtema,$CodTP,$NumGrupo,$AnoAcad,$NumPer,$mensaje,$Codtema);
                }
                if($sqlPresentesEst->num_rows>0){
                    while($data=$sqlPresentesEst->fetch_array()){
                        $matricula=$data['Matricula'];
                        $sqlnombreEstudiante=connectBd()->query("SELECT nombre,apellido FROM estudiante WHERE Matricula='$Matricula'");
                        while($data=$sqlnombreEstudiante->fetch_array()){
                            $name=$data['nombre'];
                            $apellido=$data['apellido'];
                        }
                        $mensaje='Se ha generado Por Recuperar de '.$name.' '.$apellido.' en el grupo '.$CodCampus.'-'.$Codtema.'-'.$CodTP.'-'.$NumGrupo.' en la fecha '.$date.' a la hora'.$time.' ';
                        notificargrupo($CodCampus,$Codtema,$CodTP,$NumGrupo,$AnoAcad,$NumPer,$mensaje,$matricula);
                        connectBd()->query("UPDATE asistencia SET Presencia = 'PR', HorasPresente='0' WHERE asistencia.ID = '$matricula' AND asistencia.Fecha = '$date' AND asistencia.Horaini = '$horaini' AND asistencia.NumGrupo =$NumGrupo AND asistencia.CodTema = '$Codtema' AND asistencia.CodTP = $CodTP AND asistencia.CodCampus = '$CodCampus' AND asistencia.AnoAcad = $AnoAcad AND asistencia.NumPer = $NumPer");
                    }
                    
                }
                if($sqlAusentesEst->num_rows>0){
                    while($data=$sqlAusentesEst->fetch_array()){
                        attendEstRecord($data['Matricula'],$date,$horaini,$time,$horafin,getWeekday($date),$Codtema,$CodTP,$CodCampus,$NumGrupo,$AnoAcad,$NumPer,'PR','1111','nada','nada','nada','nada','nada','nada',$codcampus,$codedif,$codsalon);
                    }
                }    
            }else{
                /*$sqlRecoveryGrupo=connectBd()->query("SELECT PR_o_R FROM gruporecuperar WHERE Codtema='$Codtema' AND CodTP='$CodTP' AND CodCampus='$CodCampus' AND NumGrupo='$NumGrupo' AND AnoAcad='$AnoAcad' AND NumPer='$NumPer' AND Fecha_Recuperar='$date' ");
                if($sqlRecoveryGrupo->num_rows>0){
                    while($data=$sqlRecoveryGrupo->fetch_array()){
                        $control=$data['PR_o_R'];
                    }
                }*/
                if($sqlAusentesEst->num_rows>0){
                    while($data=$sqlAusentesEst->fetch_array()){
                        attendEstRecord($data['Matricula'],$date,$horaini,$time,$horafin,getWeekday($date),$Codtema,$CodTP,$CodCampus,$NumGrupo,$AnoAcad,$NumPer,'A','1111','nada','nada','nada','nada','nada','nada',$codcampus,$codedif,$codsalon);
                    }
                }
            }
        }
    
       //else echo "ya estan ausentes";
            
    }else echo" todavia es tiempo de entrar \n";
}
function attendEstRecord($matricula,$date,$horaini,$time,$horafin,$day,$Codtema,$CodTP,$CodCampus,$NumGrupo,$AnoAcad,$NumPer,$Precencia,$cardN,$name,$apellido,$estado,$numCedula,$nombreprofe,$apellidoprofe,$codcampus,$codedif,$codsalon){
    $horasPresente=totalHorasAsistencia($horaini,$horafin,$time,$Precencia);
    $sqlStudentattend = connectBd()->query( "SELECT * FROM asistencia WHERE ID='$matricula' AND Fecha='$date' AND Horaini='$horaini'");
    if($Precencia=='R'){
        if($sqlStudentattend->num_rows > 0){
            //echo "ya esta precente";
            if($estado=='S'){
                insertSwipeRecord($cardN,$matricula,$name,$apellido,'Permitido',$codcampus,$codedif,$codsalon);
            }else{
                insertSwipeRecord($cardN,$matricula,$name,$apellido,'Permitido',$codcampus,$codedif,$codsalon);
            }
        }else {
            if($estado=='S'){
                connectBd()->query("INSERT INTO asistencia (ID,Fecha,Horaini,Horaentrada,Horafin,HorasPresente,Sal_CodCampus,Sal_CodEdif,Sal_CodSalon,Diasemana,Presencia,NumGrupo,CodTema,CodTP,CodCampus,AnoAcad,NumPer) VALUES('$matricula','$date','$horaini','$time','$horafin','$horasPresente','$codcampus','$codedif','$codsalon','$day','$Precencia','$NumGrupo','$Codtema','$CodTP','$CodCampus','$AnoAcad','$NumPer')");
                $mensaje='Se ha generado la presencia de '.$nombreprofe.' '.$apellidoprofe.' en el grupo '.$CodCampus.'-'.$Codtema.'-'.$CodTP.'-'.$NumGrupo.' en la fecha '.$date.' a la hora'.$time.' ';
                notificargrupo($CodCampus,$Codtema,$CodTP,$NumGrupo,$AnoAcad,$NumPer,$mensaje,$numCedula);
                notificargrupo($CodCampus,$Codtema,$CodTP,$NumGrupo,$AnoAcad,$NumPer,$mensaje,$matricula);
                insertSwipeRecord($cardN,$matricula,$name,$apellido,'Permitido',$codcampus,$codedif,$codsalon);
              
            }else{
                connectBd()->query("INSERT INTO asistencia (ID,Fecha,Horaini,Horaentrada,Horafin,HorasPresente,Sal_CodCampus,Sal_CodEdif,Sal_CodSalon,Diasemana,Presencia,NumGrupo,CodTema,CodTP,CodCampus,AnoAcad,NumPer) VALUES('$matricula','$date','$horaini','$time','$horafin','$horasPresente','$codcampus','$codedif','$codsalon','$day','$Precencia','$NumGrupo','$Codtema','$CodTP','$CodCampus','$AnoAcad','$NumPer')");
                $mensaje='Se ha generado la recuperacion de '.$name.' '.$apellido.' en el grupo '.$CodCampus.'-'.$Codtema.'-'.$CodTP.'-'.$NumGrupo.' en la fecha '.$date.' a la hora'.$time.' ';
                notificargrupo($CodCampus,$Codtema,$CodTP,$NumGrupo,$AnoAcad,$NumPer,$mensaje,$matricula);
                notificargrupo($CodCampus,$Codtema,$CodTP,$NumGrupo,$AnoAcad,$NumPer,$mensaje,$Codtema);
                insertSwipeRecord($cardN,$matricula,$name,$apellido,'Permitido',$codcampus,$codedif,$codsalon);
            }
        } 
    }else{
        if($sqlStudentattend->num_rows > 0){
            //echo "ya esta precente";
            if($estado=='S'){
                insertSwipeRecord($cardN,$matricula,$name,$apellido,'Permitido',$codcampus,$codedif,$codsalon);
            }else{
                insertSwipeRecord($cardN,$matricula,$name,$apellido,'Permitido',$codcampus,$codedif,$codsalon);
            }
        }else{
            if($Precencia=='A' OR $Precencia=='PR' OR $Precencia=='FR'){
                //Ausensia, PorRecuperar,FalloRecuperacion.
                if($Precencia=='A'){
                    $mensaje='Se ha generado la ausencia de '.$name.' '.$apellido.' en el grupo '.$CodCampus.'-'.$Codtema.'-'.$CodTP.'-'.$NumGrupo.' en la fecha '.$date.' a la hora'.$time.' ';
                    incrementarA($CodCampus,$Codtema,$CodTP,$NumGrupo,$matricula,$horaini,$horafin);
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
                    insertSwipeRecord($cardN,$matricula,$name,$apellido,'Permitido',$codcampus,$codedif,$codsalon);
                }else{
                    connectBd()->query("INSERT INTO asistencia (ID,Fecha,Horaini,Horaentrada,Horafin,HorasPresente,Sal_CodCampus,Sal_CodEdif,Sal_CodSalon,Diasemana,Presencia,NumGrupo,CodTema,CodTP,CodCampus,AnoAcad,NumPer) VALUES('$matricula','$date','$horaini','$time','$horafin','$horasPresente','$codcampus','$codedif','$codsalon','$day','$Precencia','$NumGrupo','$Codtema','$CodTP','$CodCampus','$AnoAcad','$NumPer')");
                    $mensaje='Se ha generado la presencia de '.$name.' '.$apellido.' en el grupo '.$CodCampus.'-'.$Codtema.'-'.$CodTP.'-'.$NumGrupo.' en la fecha '.$date.' a la hora'.$time.' ';
                    insertSwipeRecord($cardN,$matricula,$name,$apellido,'Permitido',$codcampus,$codedif,$codsalon);
                    //notificacion
                    notificargrupo($CodCampus,$Codtema,$CodTP,$NumGrupo,$AnoAcad,$NumPer,$mensaje,$matricula);
                }
               
            } 
        }  
    }
          
}
function incrementarA($CodCampus,$Codtema,$CodTP,$NumGrupo,$AnoAcad,$NumPer,$mensaje,$matricula,$horaini,$horafin){
    $time1 = strtotime($horaini);
    $time2 = strtotime($horafin);
    $totalHoras = round(abs($time2 - $time1) / 3600,2);
    $whole = floor($totalHoras);
    $sqlStudent=connectBd()->query("SELECT NumAusencias FROM grupoinsest WHERE grupoinsest.Matricula = $matricula AND grupoinsest.CodTema = '$Codtema' AND grupoinsest.CodTP ='$CodTP' AND grupoinsest.Numgrupo = $NumGrupo AND grupoinsest.CodCampus = '$CodCampus' AND grupoinsest.AnoAcad = $AnoAcad AND grupoinsest.NumPer = $NumPer")
    if($sqlStudent->num_rows>0){
        while($data=$sqlStudent->fetch_array()){
            $NumAusencias=$data['NumAusencias'];
        }
    }
    $NumAusencias+=$whole
    connectBd()->query("UPDATE grupoinsest SET NumAusencias = '$NumAusencias' WHERE grupoinsest.Matricula = $matricula AND grupoinsest.CodTema = '$Codtema' AND grupoinsest.CodTP ='$CodTP' AND grupoinsest.Numgrupo = $NumGrupo AND grupoinsest.CodCampus = '$CodCampus' AND grupoinsest.AnoAcad = $AnoAcad AND grupoinsest.NumPer = $NumPer");
}
function getHorausencia($Horafin){
    $Horafin = strtotime($Horafin);
    $horadeAusencia = date('H:i:s', strtotime('-10 minutes', $Horafin));
    return $horadeAusencia;
}
function getHorapresencia($horaini,$horafin,$Codtema,$CodTP,$CodCampus,$NumGrupo,$AnoAcad,$NumPer){
    $time1 = strtotime($horaini);
    $time2 = strtotime($horafin);
    $sqltiempolimite= connectBd()->query("SELECT PTLimiteH FROM configuraciongrupo WHERE CodTema='$Codtema'AND CodTp='$CodTP' AND NumGrupo='$NumGrupo' AND CodCampus='$CodCampus' AND AnoAcad='$AnoAcad' AND NumPer='$NumPer' ");
    if($sqltiempolimite->num_rows >0){
        while($data=$sqltiempolimite->fetch_array()){
            $tiempolimite=$data["PTLimiteH"];
        }

    }
    $totalHoras = round(abs($time2 - $time1) / 3600,2);
    $whole = floor($totalHoras);
    //echo $whole,"\n";
    //echo $totalHoras,"\n";
    //echo "tiempo limite: $tiempolimite\n";
    $tiempolimite*=$whole;
    $fraction = $totalHoras - $whole;
    $horadeAusencia = date('H:i:s', strtotime('+'.$tiempolimite.' minutes', $time1));
    return $horadeAusencia;
    
}
function getWeekday($date) {
    return date('w', strtotime($date));
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
function notificaradmin($ID,$mensaje,$codcampus,$codedif,$codsalon){
    $date = date('Y-m-d');
    $time= date('H:i:s');
    $pusher=$GLOBALS['pusher'];
    $message['message'] = $mensaje;
    $pusher->trigger(''.$ID.'', 'my-event', $message);
    connectBd()->query("INSERT INTO notificacionesadmin (mensaje,CodCampus,CodEdif,CodSalon,fecha,hora) VALUES ('$mensaje','$codcampus','$codedif','$codsalon','$date','$time')");
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
function insertSwipeRecord($NumTarjeta,$ID,$Nombre,$apellido,$Acceso,$codcampus,$codedif,$codsalon){
    $date = date('Y-m-d');
    $time= date('H:i:s');
    $day= getWeekday($date);
    //echo "\n$NumTarjeta,$Nombre,$apellido,$Acceso, $codcampus,$codedif,$codsalon,$date,$time\n";
    connectBd()->query("INSERT INTO swipe (NumTarjeta,ID,Nombre,Acceso,Sal_CodCampus,Sal_CodEdif,Sal_CodSalon,Fecha,Tiempo) VALUES('$NumTarjeta','$ID','$Nombre $apellido','$Acceso','$codcampus','$codedif','$codsalon','$date','$time')");
}

?>