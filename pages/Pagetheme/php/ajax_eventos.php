<?php
if (isset($_POST['key'])){
    
$user='root';
$pass='';
$db='proyectofinal';
$conn= new mysqli('localhost',$user, $pass, $db);

if($_POST['key'] == 'getCampusData'){
    $sqlcampus=$conn->query("SELECT CodCampus FROM campus ");
    if($sqlcampus->num_rows>0){
        $response ="";
        while($data=$sqlcampus->fetch_array()){
            $response .='
            <li>
            <a onclick="getedfcampus(\''.$data["CodCampus"].'\')" >'.$data["CodCampus"].'</a>
            </li>
            ';

            }   
        }
        exit ($response);

}

    if($_POST['key'] == 'getedfcampus')
    {
        $campus = $conn->real_escape_string($_POST['campus']);
        $sqledif=$conn->query("SELECT CodEdif FROM edificiodocencia WHERE CodCampus='$campus' ");
        if($sqledif->num_rows>0){
            $response ="";
            while($data=$sqledif->fetch_array()){
                $response .='
                <li>
                <a onclick="getaulaedf(\''.$data["CodEdif"].'\',\''.$campus.'\')" >'.$data["CodEdif"].'</a>
                </li>
                ';
    
                }   
            }
            exit ($response);
    }

    if($_POST['key'] == 'getaulaedf')
    {   $edf = $conn->real_escape_string($_POST['edf']);
        $campus = $conn->real_escape_string($_POST['campus']);
        
        $sqlaula=$conn->query("SELECT CodSalon FROM salondocencia WHERE CodCampus='$campus' AND CodEdif='$edf' ");
        $response ="";
        if($sqlaula->num_rows>0){
            
            while($data=$sqlaula->fetch_array()){
                $response .='
                <li>
                <a (\''.$data["CodSalon"].','.$campus.','.$edf.'\')" >'.$data["CodSalon"].'</a>
                </li>
                ';
    
                }   
            }
            exit ($response);
    }

    

  
}
?>