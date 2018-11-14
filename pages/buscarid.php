<?php 
//require '../vendor/autoload.php';
//require '/xampp/htdocs/Proyecto/vendor/autoload.php';

echo $argv[1];






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
                $index=1;       
                swipeRecord($cardN,$sqlStudentName,$sqlWorkersName,$data,$time,$index);
                return("Card with this number exist :$cardN ,$data,$time \n");
            }
            if( $sqlWorkersName->num_rows > 0 AND $sqlDataTime->num_rows == 0)
            {
                $index=1; 
                swipeRecord($cardN,$sqlStudentName,$sqlWorkersName,$data,$time,$index);
                return("Card with this number exist :$cardN ,$data,$time \n");
            }
        }else{
                if($sqlDataTime->num_rows == 0)
                {
                    $index=0;
                    swipeRecord($cardN,$sqlStudentName,$sqlWorkersName,$data,$time,$index);
                    return("This card number do not exist: $cardN, $data,$time\n");
                }
            }
        return("This report exist: $cardN, $data $time\n");
    }
function swipeRecord($cardN,$sqlStudentName,$sqlWorkersName,$date,$time,$index){
    
        if($index== 1){
            
            if($sqlStudentName->num_rows > 0 ){
                while($data= $sqlStudentName->fetch_array()){
                    $name=$data["nombre"];
                    $apellido=$data["apellido"];
                    $personid=$data["Matricula"];
                    
                }
                echo "\n$personid\n";
                //getStudentGroup($personid,$cardN,$name,$apellido);
            }
            if($sqlWorkersName->num_rows > 0){
                while($data= $sqlWorkersName->fetch_array()){
                    $name=$data["nombre"];
                    $apellido=$data["apellido_1"];
                    $personid=$data["NumCedula"];
                }
                echo "\n$personid\n";
                //getProfesorGroup($personid,$cardN,$name,$apellido);
                
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
            echo "fuciona \n";
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
            echo"no fuciona";
            insertSwipeRecord($cardN,$matricula,$name,$apellido,'Denegado');
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
            echo "no abrir puerta";
            insertSwipeRecord($cardN,$numCedula,$name,$apellido,'Denegado');
        }else{
            echo"abrir puerta al trabajador";
            insertSwipeRecord($cardN,$numCedula,$name,$apellido,'Permitido');
            openDoor();
        }
        return  $sqlProfessorGrupo;
        }
?>
 
