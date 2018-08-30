<?php
class Trabajadores{
    // DB stuff
    private $conn;
    private $table='trabajadores';

    //Estudiante Properties
    public $NumCedula;
    public $nombre;
    public $apellido_1;
    public $apellido_2;
    public $usuario;
    public $NumTarjeta;
    
    //Constructor with BD
    public function __construct($db) {
        $this->conn = $db;
      }
    // Get Estudiantes
    public function read(){
        //Create query
        $query='SELECT NumCedula,nombre,apellido_1,apellido_2,usuario,NumTarjeta FROM '.$this->table.' ';
        // Prepare statemnt
        $stmt = $this->conn->prepare($query);
        //Execute query
        $stmt->execute();
        return $stmt;
    }
    // Get single estudiante
    public function read_single(){
        //Create query
        $query='SELECT NumCedula,nombre,apellido_1,apellido_2,usuario,NumTarjeta FROM '.$this->table.' WHERE NumCedula = ? LIMIT 0,1';
        // Prepare statemnt
        $stmt = $this->conn->prepare($query);
        //Bind ID
        $stmt->bindParam(1,$this->NumCedula);
        //Execute query
        $stmt->execute();

        $row= $stmt->fetch(PDO::FETCH_ASSOC);

        // Set properties
        $this->NumCedula= $row['NumCedula'];
        $this->nombre= $row['nombre'];
        $this->apellido_1= $row['apellido_1'];
        $this->apellido_2= $row['apellido_2'];
        $this->usuario= $row['usuario'];
        $this->NumTarjeta= $row['NumTarjeta'];

    }
    public function create(){
        // Create queary
        $query = 'INSERT INTO '.$this->table.'
            SET
                NumCedula = :NumCedula,
                nombre = :nombre,
                apellido_1 = :apellido_1,
                apellido_2 = :apellido_2,
                usuario = :usuario,
                NumTarjeta = :NumTarjeta';

        //Prepare statement
        $stmt = $this->conn->prepare($query);
        //Clean data
        $this->NumCedula = htmlspecialchars(strip_tags($this->NumCedula));
        $this->nombre = htmlspecialchars(strip_tags($this->nombre));
        $this->apellido_1 = htmlspecialchars(strip_tags($this->apellido_1));
        $this->apellido_2 = htmlspecialchars(strip_tags($this->apellido_2));
        $this->usuario = htmlspecialchars(strip_tags($this->usuario));
        $this->NumTarjeta = htmlspecialchars(strip_tags($this->NumTarjeta));

        //Bind data
        $stmt->bindParam(':NumCedula',$this->NumCedula);
        $stmt->bindParam(':nombre',$this->nombre);
        $stmt->bindParam(':apellido_1',$this->apellido_1);
        $stmt->bindParam(':apellido_2',$this->apellido_2);
        $stmt->bindParam(':usuario',$this->usuario);
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
                NumCedula = :NumCedula,
                nombre= :nombre,
                apellido_1= :apellido_1,
                apellido_2= :apellido_2,
                usuario= :usuario,
                NumTarjeta= :NumTarjeta
            WHERE NumCedula= :NumCedula';

        //Prepare statement
        $stmt = $this->conn->prepare($query);
        //Clean data
        $this->NumCedula = htmlspecialchars(strip_tags($this->NumCedula));
        $this->nombre = htmlspecialchars(strip_tags($this->nombre));
        $this->apellido_1 = htmlspecialchars(strip_tags($this->apellido_1));
        $this->apellido_2 = htmlspecialchars(strip_tags($this->apellido_2));
        $this->usuario = htmlspecialchars(strip_tags($this->usuario));
        $this->NumTarjeta = htmlspecialchars(strip_tags($this->NumTarjeta));


        //Bind data
        $stmt->bindParam(':NumCedula',$this->NumCedula);
        $stmt->bindParam(':nombre',$this->nombre);
        $stmt->bindParam(':apellido_1',$this->apellido_1);
        $stmt->bindParam(':apellido_2',$this->apellido_2);
        $stmt->bindParam(':usuario',$this->usuario);
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