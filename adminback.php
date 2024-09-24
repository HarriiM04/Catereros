<?php

class  adminback
{
    private $connection;
    function __construct()
    {
        $dbhost = "localhost";
        $dbuser = "root";
        $dbpass = "";
        $dbname = "ecommerce";

        $this->connection = mysqli_connect($dbhost, $dbuser, $dbpass, $dbname);

        if (!$this->connection) {
            die("Databse connection error!!!");
        }
    }

    public function display_package() {
        $query = "SELECT * FROM Package";
        $result = $this->connection->query($query);
    
        if ($result) {
            return $result;
        } else {
            return false; // Handle error accordingly
        }
    }

    public function getItemsByIds($ids) {
        $query = "SELECT Name, CategoryID FROM items WHERE Id IN ($ids)";
        return mysqli_query($this->connection, $query);
    }
    
    
    public function getCategoryById($categoryId) {
        $query = "SELECT name FROM catagory WHERE id = ?";
        $stmt = $this->connection->prepare($query);
        $stmt->bind_param('i', $categoryId);
        $stmt->execute();
        return $stmt->get_result();
    }
    
    
    public function getCategoriess() {
        $query = "SELECT * FROM catagory";
        return mysqli_query($this->connection, $query); // Return result set
    }

    public function display_packageByID($id) {
    $query = "SELECT * FROM Package WHERE id = ?";
    $stmt = $this->connection->prepare($query);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result) {
        return $result->fetch_assoc();  // Return the package data as an associative array
    } else {
        return false; // Handle error accordingly
    }
}

}
