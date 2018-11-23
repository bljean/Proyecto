<?php
if (isset($_POST['key'])){
    
$user='root';
$pass='';
$db='proyectofinal';
$conn= new mysqli('localhost',$user, $pass, $db);


if($_POST['key'] == 'getExistingData'){
    
    $sqlnotidata=$conn->query("SELECT CodCampus,CodEdif,CodSalon,ip,up_down FROM salondocencia WHERE ip is not null");
    $response ="";
     if($sqlnotidata->num_rows>0){
        while($data=$sqlnotidata->fetch_array()){
            $CodCampus=$data["CodCampus"];
            $CodEdif=$data["CodEdif"];
            $CodSalon=$data["CodSalon"];
            $ip=$data["ip"];
            $up_down=$data["up_down"];
            if($up_down=="up"){
                $response.='
                <tr>
                <td>'.$CodCampus.'-'.$CodEdif.'-'.$CodSalon.'</td>
                <td>'.$ip.'</td>
                <td>
                <div class="col-md-4">
                <button type="button" class="btn btn-success btn-lg">'.$up_down.'</button>
                </div>
                </td>
                </tr>
                ';
            }else{
                $response.='
                <tr>
                <td>'.$CodCampus.'-'.$CodEdif.'-'.$CodSalon.'</td>
                <td>'.$ip.'</td>
                <td>
                <div class="col-md-4">
                <button type="button" class="btn btn-danger btn-lg">'.$up_down.'</button>
                </div>
                </td>
                </tr>
                ';
            }
            
            
        }
        $jsonArray = array(
            'body'=> $response,
        );
        exit(json_encode($jsonArray));
    } else{
        exit("error");
    }
   
    
}
}








?>