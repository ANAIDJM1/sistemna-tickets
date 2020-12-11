<?php
$servername = "localhost";
$username = "ugelcusc_tickete"; 
$password = "TuWYSnShXdcKQBdC"; 
$dbname = "ugelcusc_tickete"; 

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);
// Check connection
if ($conn->connect_error) {
  die("Fallo conexion de BD: " . $conn->connect_error);
}
echo "<script>console.log('conexion BD con Exito!');</script>";
?>