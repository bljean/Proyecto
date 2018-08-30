<?php
class Estudiante{
    // DB stuff
    private $conn;
    private $table='estudiante';

    //Estudiante Properties
    public $Matricula;
    public $nombre;
    public $apellido;
    public $NumTarjeta;
    
    //Constructor with BD
    public function __construct($db) {
        $this->conn = $db;
      }
    // Get Estudiantes
    public function read(){
        //Create query
        $query='SELECT Matricula,nombre,apellido,NumTarjeta FROM '.$this->table.' ';
        // Prepare statemnt
        $stmt = $this->conn->prepare($query);
        //Execute query
        $stmt->execute();
        return $stmt;
    }
    // Get single estudiante
    public function read_single(){
        //Create query
        $query='SELECT Matricula,nombre,apellido,NumTarjeta FROM '.$this->table.' WHERE Matricula = ? LIMIT 0,1';
        // Prepare statemnt
        $stmt = $this->conn->prepare($query);
        //Bind ID
        $stmt->bindParam(1,$this->Matricula);
        //Execute query
        $stmt->execute();

        $row= $stmt->fetch(PDO::FETCH_ASSOC);

        // Set properties
        $this->Matricula= $row['Matricula'];
        $this->nombre= $row['nombre'];
        $this->apellido= $row['apellido'];
        $this->NumTarjeta= $row['NumTarjeta'];

    }
    public function create(){
        // Create queary
        $query = 'INSERT INTO '.$this->table.'
            SET
                Matricula = :Matricula,
                nombre = :nombre,
                apellido= :apellido,
                NumTarjeta= :NumTarjeta';

        //Prepare statement
        $stmt = $this->conn->prepare($query);
        //Clean data
        $this->Matricula = htmlspecialchars(strip_tags($this->Matricula));
        $this->nombre = htmlspecialchars(strip_tags($this->nombre));
        $this->apellido = htmlspecialchars(strip_tags($this->apellido));
        $this->NumTarjeta = htmlspecialchars(strip_tags($this->NumTarjeta));

        //Bind data
        $stmt->bindParam(':Matricula',$this->Matricula);
        $stmt->bindParam(':nombre',$this->nombre);
        $stmt->bindParam(':apellido',$this->apellido);
        $stmt->bindParam(':NumTarjeta',$this->NumTarjeta);

        //Execute query
        if($stmt->execute()){
            return true;
        }
        //Print error if something goes wrong
        printf("Error: %s.\n",$stmt->error);
        return false;
    }
    //Update  estudiante
    public function update(){
        // Create queary
        $query = 'UPDATE '.$this->table.'
            SET
                nombre = :nombre,
                apellido= :apellido,
                NumTarjeta= :NumTarjeta
            WHERE Matricula= :Matricula ';

        //Prepare statement
        $stmt = $this->conn->prepare($query);
        //Clean data
        $this->Matricula = htmlspecialchars(strip_tags($this->Matricula));
        $this->nombre = htmlspecialchars(strip_tags($this->nombre));
        $this->apellido = htmlspecialchars(strip_tags($this->apellido));
        $this->NumTarjeta = htmlspecialchars(strip_tags($this->NumTarjeta));


        //Bind data
        $stmt->bindParam(':Matricula',$this->Matricula);
        $stmt->bindParam(':nombre',$this->nombre);
        $stmt->bindParam(':apellido',$this->apellido);
        $stmt->bindParam(':NumTarjeta',$this->NumTarjeta);

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