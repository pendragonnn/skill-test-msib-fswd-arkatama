<?php
function createConnection() {
    $host = 'localhost'; 
    $username = 'root'; 
    $password = ''; 
    $dbname = 'arkatama'; 

    $conn = new mysqli($host, $username, $password, $dbname);

    if ($conn->connect_error) {
        die("Koneksi gagal: " . $conn->connect_error);
    }

    return $conn;
}

function createTable($conn) {
    $sql = "CREATE TABLE IF NOT EXISTS data_pengguna (
                ID INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
                NAME VARCHAR(100) NOT NULL,
                AGE INT(3) UNSIGNED,
                CITY VARCHAR(100),
                CREATED_AT TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
            )";

    if ($conn->query($sql) === TRUE) {
        echo "Tabel berhasil dibuat atau sudah ada.";
    } else {
        echo "Error: " . $conn->error;
    }
}

function processAndSaveData($conn, $data) {
  $temp = 0;
  for ($i = 0; $i < strlen($data); $i++) {
    if(is_numeric($data[$i])){
      $temp = $i;
    }
  }

  $age = (int) substr($data, $temp,strlen($data) - $temp +2);


  $name =  strtoupper(substr($data, 0, $temp-1));
  $city = strtoupper(substr($data, $temp+2));

  $sql = "INSERT INTO data_pengguna (NAME, AGE, CITY) VALUES ('$name', '$age', '$city')";
    if ($conn->query($sql) === TRUE) {
        echo "Data berhasil disimpan.";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $input_data = $_POST["data"];
    
    $conn = createConnection();
    createTable($conn);
    processAndSaveData($conn, $input_data);
    
    $conn->close();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Form Input Data Pengguna</title>
</head>
<body>
    <h2>Form Input Data Pengguna</h2>
    <form method="post" action="<?php echo $_SERVER["PHP_SELF"]; ?>">
        <label for="data">Masukkan data pengguna:</label>
        <input type="text" name="data" id="data">
        <input type="submit" value="Simpan">
    </form>
</body>
</html>