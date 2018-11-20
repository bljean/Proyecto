<?php
class salondocencia{
    // DB stuff
    private $conn;
    private $table='salondocencia';

    //Estudiante Properties
    public $CodCampus;
    public $CodEdif;
    public $CodSalon; 
    
    

    //Constructor with BD
    public function __construct($db) {
        $this->conn = $db;
      }
    // Get grupo activo
    public function read(){
        //Create query
        $query='SELECT CodCampus,CodEdif,CodSalon FROM '.$this->table.' ';
        // Prepare statemnt
        $stmt = $this->conn->prepare($query);
        //Execute query
        $stmt->execute();
        return $stmt;
    }

    public function create(){
        // Create queary
        $query = 'INSERT INTO '.$this->table.'
            SET
                CodCampus = :CodCampus,
                CodEdif = :CodEdif,
                CodSalon = :CodSalon';

        //Prepare statement
        $stmt = $this->conn->prepare($query);
        //Clean data
        $this->CodCampus = htmlspecialchars(strip_tags($this->CodCampus));
        $this->CodEdif = htmlspecialchars(strip_tags($this->CodEdif));
        $this->CodSalon = htmlspecialchars(strip_tags($this->CodSalon));

        //Bind data
        $stmt->bindParam(':CodCampus',$this->CodCampus);
        $stmt->bindParam(':CodEdif',$this->CodEdif);
        $stmt->bindParam(':CodSalon',$this->CodSalon);
        //Execute query
        if($stmt->execute()){
            return true;
        }
        //Print error if something goes wrong
        printf("Error: %s.\n",$stmt->error);
        return false;
    }


}

?>