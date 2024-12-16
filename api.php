<?php

// Set content type to JSON
header("Content-Type: application/json");
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE");

$servername = "localhost";
$username = "root";
$password = ""; // Ganti dengan password MySQL root jika ada
$dbname = "responsi_pgweb"; // Ganti dengan nama database yang sesuai

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die(json_encode(["error" => "Connection failed: " . $conn->connect_error]));
}

// Handle GET request
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    if (isset($_GET['id'])) {
        $id = intval($_GET['id']); // Pastikan ID adalah integer
        if ($id > 0) { // Validasi ID
            $sql = "SELECT * FROM kos WHERE id = $id";
            $result = $conn->query($sql);

            if ($result->num_rows > 0) {
                $row = $result->fetch_assoc();
                echo json_encode($row);
            } else {
                echo json_encode(["error" => "No record found."]);
            }
        } else {
            echo json_encode(["error" => "Invalid ID. It must be a positive number."]);
        }
    } else {
        // Fetch all records if no ID is provided
        $sql = "SELECT * FROM kos";
        $result = $conn->query($sql);
        $data = [];

        while ($row = $result->fetch_assoc()) {
            $data[] = $row;
        }
        echo json_encode($data);
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'PUT') {
    parse_str(file_get_contents("php://input"), $put_vars);
    $id = $put_vars['edit-id'];

    // Validasi ID
    if (!is_numeric($id)) {
        echo json_encode(["error" => "Invalid ID. It must be a number."]);
        exit;
    }

    // Cek apakah ID ada di database
    $checkSql = "SELECT * FROM kos WHERE id = $id";
    $checkResult = $conn->query($checkSql);
    if ($checkResult->num_rows === 0) {
        echo json_encode(["error" => "No record found for ID: $id."]);
        exit;
    }

    // Ambil data dari request PUT
    $nama = $put_vars['nama'];
    $harga = $put_vars['harga'];
    $kontak = $put_vars['kontak'];
    $latitude = $put_vars['latitude'];
    $longitude = $put_vars['longitude'];
    $fasilitas = $put_vars['fasilitas'];

    $sql = "UPDATE kos SET 
            nama='$nama', 
            harga='$harga', 
            kontak='$kontak', 
            latitude='$latitude', 
            longitude='$longitude', 
            fasilitas='$fasilitas' 
            WHERE id=$id";

    if ($conn->query($sql) === TRUE) {
        echo json_encode(["success" => "Data updated successfully."]);
    } else {
        echo json_encode(["error" => "Error updating record: " . $conn->error]);
    }
}


// Handle DELETE request
if ($_SERVER['REQUEST_METHOD'] === 'DELETE') {
    $id = intval($_GET['id']);
    if ($id > 0) { // Validasi ID
        $sql = "DELETE FROM kos WHERE id = $id";

        if ($conn->query($sql) === TRUE) {
            echo json_encode(["success" => "Data deleted successfully."]);
        } else {
            echo json_encode(["error" => "Error deleting record: " . $conn->error]);
        }
    } else {
        echo json_encode(["error" => "Invalid ID for delete."]);
    }
}

$conn->close();
?>
