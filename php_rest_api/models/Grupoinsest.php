<?php
class Grupoinsest{
    // DB stuff
    private $conn;
    private $table='grupoinsest';

    //Estudiante Properties
    public $Matricula;
    public $CodTema;
    public $CodTP; 
    public $Numgrupo;
    public $CodCampus;
    public $AnoAcad;
    public $NumPer;
    public $NumAusencias;
    
    //Constructor with BD
    public function __construct($db) {
        $this->conn = $db;
      }
    // Get grupo activo
    public function read(){
        //Create query
        $query='SELECT Matricula,CodTema,CodTP,Numgrupo,CodCampus,AnoAcad,NumPer,NumAusencias FROM '.$this->table.' ';
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
                Matricula = :Matricula,
                CodTema = :CodTema,
                CodTP= :CodTP,
                Numgrupo= :Numgrupo,
                CodCampus= :CodCampus,
                AnoAcad= :AnoAcad,
                NumPer= :NumPer,
                NumAusencias=:NumAusencias';

        //Prepare statement
        $stmt = $this->conn->prepare($query);
        //Clean data
        $this->Matricula = htmlspecialchars(strip_tags($this->Matricula));
        $this->CodTema = htmlspecialchars(strip_tags($this->CodTema));
        $this->CodTP = htmlspecialchars(strip_tags($this->CodTP));
        $this->Numgrupo = htmlspecialchars(strip_tags($this->Numgrupo));
        $this->CodCampus = htmlspecialchars(strip_tags($this->CodCampus));
        $this->AnoAcad = htmlspecialchars(strip_tags($this->AnoAcad));
        $this->NumPer = htmlspecialchars(strip_tags($this->NumPer));
        $this->NumAusencias = htmlspecialchars(strip_tags($this->NumAusencias));

        //Bind data
        $stmt->bindParam(':Matricula',$this->Matricula);
        $stmt->bindParam(':CodTema',$this->CodTema);
        $stmt->bindParam(':CodTP',$this->CodTP);
        $stmt->bindParam(':Numgrupo',$this->Numgrupo);
        $stmt->bindParam(':CodCampus',$this->CodCampus);
        $stmt->bindParam(':AnoAcad',$this->AnoAcad);
        $stmt->bindParam(':NumPer',$this->NumPer);
        $stmt->bindParam(':NumAusencias',$this->NumAusencias);
        
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