<?php
class Periodoacademico{
    // DB stuff
    private $conn;
    private $table='periodoacademico';

    //Estudiante Properties
    public $AnoAcad;
    public $NumPer;
   
    
    //Constructor with BD
    public function __construct($db) {
        $this->conn = $db;
      }
    // Get grupo activo
    public function read(){
        //Create query
        $query='SELECT AnoAcad, NumPer FROM '.$this->table.' ';
        // Prepare statemnt
        $stmt = $this->conn->prepare($query);
        //Execute query
        $stmt->execute();
        return $stmt;
    }

    public function read_single(){
        //Create query
        $query='SELECT *  FROM '.$this->table.' WHERE AnoAcad = ? and NumPer = ?  LIMIT 0,1';
        // Prepare statemnt
        $stmt = $this->conn->prepare($query);
        //Bind ID
        $stmt->bindParam(1,$this->AnoAcad);
        $stmt->bindParam(2,$this->NumPer);

        //Execute query
        $stmt->execute();

        $row= $stmt->fetch(PDO::FETCH_ASSOC);

        // Set properties
        $this->AnoAcad= $row['AnoAcad'];
        $this->NumPer= $row['NumPer'];
       
    }

    public function create(){
        // Create queary
        $query = 'INSERT INTO '.$this->table.'
            SET
                AnoAcad = :AnoAcad,
                NumPer = :NumPer';

        //Prepare statement
        $stmt = $this->conn->prepare($query);
        //Clean data
        $this->AnoAcad = htmlspecialchars(strip_tags($this->AnoAcad));
        $this->NumPer = htmlspecialchars(strip_tags($this->NumPer));
        
        //Bind data
        $stmt->bindParam(':AnoAcad',$this->AnoAcad);
        $stmt->bindParam(':NumPer',$this->NumPer);
        
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