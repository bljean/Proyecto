<?php
class contratodocencia{
    // DB stuff
    private $conn;
    private $table='contratodocencia';

    //Estudiante Properties
    public $CodTema;
    public $CodTp;
    public $Numgrupo; 
    public $CodCampus;
    public $AnoAcad;
    public $NumPer;
    public $NumCedula;
    
    
    //Constructor with BD
    public function __construct($db) {
        $this->conn = $db;
      }
    // Get grupo activo
    public function read(){
        //Create query
        $query='SELECT CodTema,CodTp,Numgrupo,CodCampus,AnoAcad,NumPer,NumCedula FROM '.$this->table.' ';
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
                CodTema = :CodTema,
                CodTp = :CodTp,
                Numgrupo= :Numgrupo,
                CodCampus= :CodCampus,
                AnoAcad= :AnoAcad,
                NumPer= :NumPer,
                NumCedula= :NumCedula';

        //Prepare statement
        $stmt = $this->conn->prepare($query);
        //Clean data
        $this->CodTema = htmlspecialchars(strip_tags($this->CodTema));
        $this->CodTp = htmlspecialchars(strip_tags($this->CodTp));
        $this->Numgrupo = htmlspecialchars(strip_tags($this->Numgrupo));
        $this->CodCampus = htmlspecialchars(strip_tags($this->CodCampus));
        $this->AnoAcad = htmlspecialchars(strip_tags($this->AnoAcad));
        $this->NumPer = htmlspecialchars(strip_tags($this->NumPer));
        $this->NumCedula = htmlspecialchars(strip_tags($this->NumCedula));
        

        //Bind data
        $stmt->bindParam(':CodTema',$this->CodTema);
        $stmt->bindParam(':CodTp',$this->CodTp);
        $stmt->bindParam(':Numgrupo',$this->Numgrupo);
        $stmt->bindParam(':CodCampus',$this->CodCampus);
        $stmt->bindParam(':AnoAcad',$this->AnoAcad);
        $stmt->bindParam(':NumPer',$this->NumPer);
        $stmt->bindParam(':NumCedula',$this->NumCedula);
        
        
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