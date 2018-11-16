<?php
class Asignatura{
    // DB stuff
    private $conn;
    private $table='asignatura';

    //Estudiante Properties
    public $CodTema;
    public $CodTp;
    public $Nombre;
    public $NumCreditos;
    
    //Constructor with BD
    public function __construct($db) {
        $this->conn = $db;
      }
    // Get grupo activo
    public function read(){
        //Create query
        $query='SELECT CodTema,CodTp,Nombre,NumCreditos FROM '.$this->table.' ';
        // Prepare statemnt
        $stmt = $this->conn->prepare($query);
        //Execute query
        $stmt->execute();
        return $stmt;
    }

    public function read_single(){
        //Create query
        $query='SELECT *  FROM '.$this->table.' WHERE CodTema = ? and CodTp = ? and Nombre = ? and NumCreditos = ?  LIMIT 0,1';
        // Prepare statemnt
        $stmt = $this->conn->prepare($query);
        //Bind ID
        $stmt->bindParam(1,$this->CodTema);
        $stmt->bindParam(2,$this->CodTp);
        $stmt->bindParam(3,$this->Nombre);
        $stmt->bindParam(4,$this->NumCreditos);

        //Execute query
        $stmt->execute();

        $row= $stmt->fetch(PDO::FETCH_ASSOC);

        // Set properties
        $this->CodTema= $row['CodTema'];
        $this->CodTp= $row['CodTp'];
        $this->Nombre= $row['Nombre'];
        $this->NumCreditos= $row['NumCreditos'];
        

    }

    public function create(){
        // Create queary
        $query = 'INSERT INTO '.$this->table.'
            SET
                CodTema = :CodTema,
                CodTp = :CodTp,
                Nombre= :Nombre,
                NumCreditos= :NumCreditos';

        //Prepare statement
        $stmt = $this->conn->prepare($query);
        //Clean data
        $this->CodTema = htmlspecialchars(strip_tags($this->CodTema));
        $this->CodTp = htmlspecialchars(strip_tags($this->CodTp));
        $this->Nombre = htmlspecialchars(strip_tags($this->Nombre));
        $this->NumCreditos = htmlspecialchars(strip_tags($this->NumCreditos));
        

        //Bind data
        $stmt->bindParam(':CodTema',$this->CodTema);
        $stmt->bindParam(':CodTp',$this->CodTp);
        $stmt->bindParam(':Nombre',$this->Nombre);
        $stmt->bindParam(':NumCreditos',$this->NumCreditos);
        
        
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