<?php
$servername = "localhost";
$username = "ticketera";
$password = "TuWYSnShXdcKQBdC";
$dbname = "ticketera";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);
// Check connection
if ($conn->connect_error) {
  die("Fallo conexion de BD: " . $conn->connect_error);
}
echo "<script>console.log('conexion BD con Exito!');</script>";
?>