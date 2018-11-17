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

    public function read_single(){
        //Create query
        $query='SELECT *  FROM '.$this->table.' WHERE Matricula = ? and CodTema = ? and CodTP = ? and Numgrupo = ? and CodCampus = ? and AnoAcad = ? and NumPer = ? and NumAusencias = ? LIMIT 0,1';
        // Prepare statemnt
        $stmt = $this->conn->prepare($query);
        //Bind ID
        $stmt->bindParam(1,$this->Matricula);
        $stmt->bindParam(2,$this->CodTema);
        $stmt->bindParam(3,$this->CodTP);
        $stmt->bindParam(3,$this->Numgrupo);
        $stmt->bindParam(4,$this->CodCampus);
        $stmt->bindParam(5,$this->AnoAcad);
        $stmt->bindParam(6,$this->NumPer);
        $stmt->bindParam(7,$this->NumAusencias);

        //Execute query
        $stmt->execute();

        $row= $stmt->fetch(PDO::FETCH_ASSOC);

        // Set properties
        $this->Matricula= $row['Matricula'];
        $this->CodTema= $row['CodTema'];
        $this->CodTP= $row['CodTP'];
        $this->Numgrupo= $row['Numgrupo'];
        $this->CodCampus= $row['CodCampus'];
        $this->AnoAcad= $row['AnoAcad'];
        $this->NumPer= $row['NumPer'];
        $this->NumAusencias= $row['NumAusencias'];

    }



}
?>