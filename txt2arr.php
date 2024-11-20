<?php
$host = 'localhost';
$username = 'root'; 
$password = '';     
$dbname = 'orszagok_db';
 
$conn = new mysqli($host, $username, $password);
 
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
 
$sql = "CREATE DATABASE IF NOT EXISTS $dbname";
if (!$conn->query($sql)) {
    die("Error creating database: " . $conn->error);
}
 
$conn->select_db($dbname);
 
$sql = "
CREATE TABLE IF NOT EXISTS orszagok (
    id INT AUTO_INCREMENT PRIMARY KEY,
    orszag VARCHAR(255) NOT NULL
)";
if (!$conn->query($sql)) {
    die("Error creating table: " . $conn->error);
}
 
$bemenet_tomb = array();
$file = fopen("orszagok.txt", "r");
if (!$file) {
    die("Error: Unable to open orszagok.txt. Make sure the file exists.");
}
while (!feof($file)) {
    $sor = fgets($file);
    $bemenet_tomb[] = trim($sor); 
}
fclose($file);
 
$hossz = sizeof($bemenet_tomb);
$i = 0;
$orszagok_tomb = array();
while ($i < $hossz) {
    $orszagok_tomb[] = $bemenet_tomb[$i];
    $i += 3;
}
 
$inserted = 0;
foreach ($orszagok_tomb as $orszag) {
    $orszag = $conn->real_escape_string($orszag); 
    $sql = "INSERT INTO orszagok (orszag) VALUES ('$orszag')";
    if ($conn->query($sql)) {
        $inserted++;
    } else {
        echo "Error inserting data: " . $conn->error . "<br>";
    }
}
 
echo "<h1>Data Inserted Successfully</h1>";
echo "<p>Inserted $inserted records into the 'orszagok' table.</p>";
 
$conn->close();
?>