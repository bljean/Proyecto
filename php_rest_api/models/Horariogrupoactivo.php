<?php
class horariogrupoactivo{

    // DB stuff
    private $conn;
    private $table='horariogrupoactivo';

    //Estudiante Properties
    public $CodTema;
    public $CodTP;
    public $NumGrupo;
    public $CodCampus;
    public $AnoAcad;
    public $NumPer;
    public $DiaSem;
    public $HoraInicio;
    public $Horafin;
    public $Sal_CodCampus;
    public $Sal_CodEdif;
    public $Sal_CodSalon;
    
    
    //Constructor with BD
    public function __construct($db) {
        $this->conn = $db;
      }
    // Get grupo activo
    public function read(){
        //Create query
        $query='SELECT CodTema, CodTP,NumGrupo,CodCampus,AnoAcad,NumPer,DiaSem,HoraInicio,Horafin,Sal_CodCampus,Sal_CodEdif,Sal_CodSalon FROM '.$this->table.' ';
        // Prepare statemnt
        $stmt = $this->conn->prepare($query);
        //Execute query
        $stmt->execute();
        return $stmt;
    }

    public function read_single(){
        //Create query
        $query='SELECT *  FROM '.$this->table.' WHERE CodTema = ? and CodTP = ? and NumGrupo = ? and CodCampus = ? and AnoAcad = ? and NumPer = ? and DiaSem = ? HoraInicio = ? and Horafin = ? and Sal_CodCampus = ? and Sal_CodEdif = ? and Sal_CodSalon = ? LIMIT 0,1';
        // Prepare statemnt
        $stmt = $this->conn->prepare($query);
        //Bind ID
        $stmt->bindParam(1,$this->CodTema);
        $stmt->bindParam(2,$this->CodTP);
        $stmt->bindParam(3,$this->NumGrupo);
        $stmt->bindParam(4,$this->CodCampus);
        $stmt->bindParam(5,$this->AnoAcad);
        $stmt->bindParam(6,$this->NumPer);
        $stmt->bindParam(7,$this->DiaSem);
        $stmt->bindParam(8,$this->HoraInicio);
        $stmt->bindParam(9,$this->Horafin);
        $stmt->bindParam(10,$this->Sal_CodCampus);
        $stmt->bindParam(11,$this->Sal_CodEdif);
        $stmt->bindParam(12,$this->Sal_CodSalon);

        //Execute query
        $stmt->execute();

        $row= $stmt->fetch(PDO::FETCH_ASSOC);

        // Set properties
        $this->CodTema= $row['CodTema'];
        $this->CodTP= $row['CodTP'];
        $this->NumGrupo= $row['NumGrupo'];
        $this->CodCampus= $row['CodCampus'];
        $this->AnoAcad= $row['AnoAcad'];
        $this->NumPer= $row['NumPer'];
        $this->DiaSem= $row['DiaSem'];
        $this->HoraInicio= $row['HoraInicio'];
        $this->Horafin= $row['Horafin'];
        $this->Sal_CodCampus= $row['Sal_CodCampus'];
        $this->Sal_CodEdif= $row['Sal_CodEdif'];
        $this->Sal_CodSalon= $row['Sal_CodSalon'];

    }

    public function create(){
        // Create queary
        $query = 'INSERT INTO '.$this->table.'
            SET
                CodTema = :CodTema,
                CodTP = :CodTP,
                NumGrupo= :NumGrupo,
                CodCampus= :CodCampus,
                AnoAcad= :AnoAcad,
                NumPer= :NumPer,
                DiaSem= :DiaSem,
                HoraInicio= :HoraInicio,
                Horafin= :Horafin,
                Sal_CodCampus= :Sal_CodCampus,
                Sal_CodEdif= :Sal_CodEdif,
                Sal_CodSalon= :Sal_CodSalon';

        //Prepare statement
        $stmt = $this->conn->prepare($query);
        //Clean data
        $this->CodTema = htmlspecialchars(strip_tags($this->CodTema));
        $this->CodTP = htmlspecialchars(strip_tags($this->CodTP));
        $this->NumGrupo = htmlspecialchars(strip_tags($this->NumGrupo));
        $this->CodCampus = htmlspecialchars(strip_tags($this->CodCampus));
        $this->AnoAcad = htmlspecialchars(strip_tags($this->AnoAcad));
        $this->NumPer = htmlspecialchars(strip_tags($this->NumPer));
        $this->DiaSem = htmlspecialchars(strip_tags($this->DiaSem));
        $this->HoraInicio = htmlspecialchars(strip_tags($this->HoraInicio));
        $this->Horafin = htmlspecialchars(strip_tags($this->Horafin));
        $this->Sal_CodCampus = htmlspecialchars(strip_tags($this->Sal_CodCampus));
        $this->Sal_CodEdif = htmlspecialchars(strip_tags($this->Sal_CodEdif));
        $this->Sal_CodSalon = htmlspecialchars(strip_tags($this->Sal_CodSalon));

        //Bind data
        $stmt->bindParam(':CodTema',$this->CodTema);
        $stmt->bindParam(':CodTP',$this->CodTP);
        $stmt->bindParam(':NumGrupo',$this->NumGrupo);
        $stmt->bindParam(':CodCampus',$this->CodCampus);
        $stmt->bindParam(':AnoAcad',$this->AnoAcad);
        $stmt->bindParam(':NumPer',$this->NumPer);
        $stmt->bindParam(':DiaSem',$this->DiaSem);
        $stmt->bindParam(':HoraInicio',$this->HoraInicio);
        $stmt->bindParam(':Horafin',$this->Horafin);
        $stmt->bindParam(':Sal_CodCampus',$this->Sal_CodCampus);
        $stmt->bindParam(':Sal_CodEdif',$this->Sal_CodEdif);
        $stmt->bindParam(':Sal_CodSalon',$this->Sal_CodSalon);

        
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