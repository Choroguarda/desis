<?php

class Database {
    private $servername = "localhost";
    private $username = "root";
    private $password = "";
    private $dbname = "desis";
    protected $conn; 

    public function __construct() {
        $this->conn = new mysqli($this->servername, $this->username, $this->password, $this->dbname);
       
        if ($this->conn->connect_error) {
            die("Conexión fallida: " . $this->conn->connect_error);
        } 
    }
    
    public function insertData($campo1, $campo2, $campo3, $combo1, $combo2, $combo3, $referencia, $redesSociales, $busquedaWeb) {
        $sql = "INSERT INTO tu_tabla (campo1, campo2, campo3, combo1, combo2, combo3, referencia, redesSociales, busquedaWeb) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
        
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("ssssssiii", $campo1, $campo2, $campo3, $combo1, $combo2, $combo3, $referencia, $redesSociales, $busquedaWeb);

        $result = $stmt->execute();

        $stmt->close();

        return $result; // Return the result directly

    }
    public function getConn() {
        return $this->conn;
    }
    public function closeConnection() {
        $this->conn->close();
    }
}

?>
