<?php
class Grupoactivo{
    // DB stuff
    private $conn;
    private $table='grupoactivo';

    //Estudiante Properties
    public $CodTema;
    public $CodTp;
    public $NumGrupo;
    public $CodCampus;
    public $AnoAcad;
    public $NumPer;
    public $NumCredito;
    
    //Constructor with BD
    public function __construct($db) {
        $this->conn = $db;
      }
    // Get grupo activo
    public function read(){
        //Create query
        $query='SELECT CodTema,CodTp,NumGrupo,CodCampus,AnoAcad,NumPer,NumCredito FROM '.$this->table.' ';
        // Prepare statemnt
        $stmt = $this->conn->prepare($query);
        //Execute query
        $stmt->execute();
        return $stmt;
    }
    
    // Get single estudiante
    public function read_single(){
        //Create query
        $query='SELECT *  FROM '.$this->table.' WHERE CodTema = ? and CodTp = ? and NumGrupo = ? and CodCampus = ? and AnoAcad = ?and NumPer = ?and NumCredito = ? LIMIT 0,1';
        // Prepare statemnt
        $stmt = $this->conn->prepare($query);
        //Bind ID
        $stmt->bindParam(1,$this->CodTema);
        $stmt->bindParam(2,$this->CodTp);
        $stmt->bindParam(3,$this->NumGrupo);
        $stmt->bindParam(4,$this->CodCampus);
        $stmt->bindParam(5,$this->AnoAcad);
        $stmt->bindParam(6,$this->NumPer);
        $stmt->bindParam(7,$this->NumCredito);

        //Execute query
        $stmt->execute();

        $row= $stmt->fetch(PDO::FETCH_ASSOC);

        // Set properties
        $this->CodTema= $row['CodTema'];
        $this->CodTp= $row['CodTp'];
        $this->NumGrupo= $row['NumGrupo'];
        $this->CodCampus= $row['CodCampus'];
        $this->AnoAcad= $row['AnoAcad'];
        $this->NumPer= $row['NumPer'];
        $this->NumCredito= $row['NumCredito'];

    }

    public function create(){
        // Create queary
        $query = 'INSERT INTO '.$this->table.'
            SET
                CodTema = :CodTema,
                CodTp = :CodTp,
                NumGrupo= :NumGrupo,
                CodCampus= :CodCampus,
                AnoAcad= :AnoAcad,
                NumPer= :NumPer,
                NumCredito= :NumCredito';

        //Prepare statement
        $stmt = $this->conn->prepare($query);
        //Clean data
        $this->CodTema = htmlspecialchars(strip_tags($this->CodTema));
        $this->CodTp = htmlspecialchars(strip_tags($this->CodTp));
        $this->NumGrupo = htmlspecialchars(strip_tags($this->NumGrupo));
        $this->CodCampus = htmlspecialchars(strip_tags($this->CodCampus));
        $this->AnoAcad = htmlspecialchars(strip_tags($this->AnoAcad));
        $this->NumPer = htmlspecialchars(strip_tags($this->NumPer));
        $this->NumCredito = htmlspecialchars(strip_tags($this->NumCredito));

        //Bind data
        $stmt->bindParam(':CodTema',$this->CodTema);
        $stmt->bindParam(':CodTp',$this->CodTp);
        $stmt->bindParam(':NumGrupo',$this->NumGrupo);
        $stmt->bindParam(':CodCampus',$this->CodCampus);
        $stmt->bindParam(':AnoAcad',$this->AnoAcad);
        $stmt->bindParam(':NumPer',$this->NumPer);
        $stmt->bindParam(':NumCredito',$this->NumCredito);
        
        //Execute query
        if($stmt->execute()){
            return true;
        }
        //Print error if something goes wrong
        printf("Error: %s.\n",$stmt->error);
        return false;
    }

    /*
    public function update(){
        // Create queary
        $query = 'UPDATE '.$this->table.'
        SET
            CodTema = :CodTema,
            NumGrupo = :NumGrupo,
            CodTp = :CodTp,
            CodCampus= :CodCampus,
            AnoAcad= :AnoAcad,
            NumPer= :NumPer,
            NumCredito= :NumCredito
        WHERE NumGrupo= :NumGrupo ';

        //Prepare statement
        $stmt = $this->conn->prepare($query);
        //Clean data
        $this->CodTema = htmlspecialchars(strip_tags($this->CodTema));
        $this->CodTp = htmlspecialchars(strip_tags($this->CodTp));
        $this->NumGrupo = htmlspecialchars(strip_tags($this->NumGrupo));
        $this->CodCampus = htmlspecialchars(strip_tags($this->CodCampus));
        $this->AnoAcad = htmlspecialchars(strip_tags($this->AnoAcad));
        $this->NumPer = htmlspecialchars(strip_tags($this->NumPer));
        $this->NumCredito = htmlspecialchars(strip_tags($this->NumCredito));


        //Bind data
        $stmt->bindParam(':CodTema',$this->CodTema);
        $stmt->bindParam(':CodTp',$this->CodTp);
        $stmt->bindParam(':NumGrupo',$this->NumGrupo);
        $stmt->bindParam(':CodCampus',$this->CodCampus);
        $stmt->bindParam(':AnoAcad',$this->AnoAcad);
        $stmt->bindParam(':NumPer',$this->NumPer);
        $stmt->bindParam(':NumCredito',$this->NumCredito);

        //Execute query
        if($stmt->execute()){
            return true;
        }
        //Print error if something goes wrong
        printf("Error: %s.\n",$stmt->error);
        return false;
    }
    */


}
    ?>