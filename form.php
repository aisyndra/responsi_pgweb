<?php

// Mengambil nilai dari POST, jika tidak ada set dengan string kosong
$nama = isset($_POST["nama"]) ? $_POST["nama"] : "";
$harga = isset($_POST["harga"]) ? $_POST["harga"] : "";
$kontak = isset($_POST["kontak"]) ? $_POST["kontak"] : "";
$latitude = isset($_POST["latitude"]) ? $_POST["latitude"] : "";
$longitude = isset($_POST["longitude"]) ? $_POST["longitude"] : "";
$fasilitas = isset($_POST["fasilitas"]) ? $_POST["fasilitas"] : "";

// Validasi sederhana: cek apakah semua field terisi
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    if (empty($nama) || empty($harga) || empty($kontak) || empty($latitude) || empty($longitude) || empty($fasilitas)) {
        die("Semua field harus diisi.");
    }

    // Sesuaikan dengan setting MySQL
    $servername = "localhost";
    $username = "root";
    $password = ""; // Pastikan password sesuai dengan pengaturan Anda
    $dbname = "responsi_pgweb";

    // Membuat koneksi
    $conn = new mysqli($servername, $username, $password, $dbname);

    // Cek koneksi
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Query menggunakan prepared statement untuk mencegah SQL Injection
    $stmt = $conn->prepare("INSERT INTO kos (nama, harga, kontak, latitude, longitude, fasilitas) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("sissss", $nama, $harga, $kontak, $latitude, $longitude, $fasilitas);

    // Mengeksekusi query dan cek apakah berhasil
    if ($stmt->execute()) {
        echo "Data berhasil ditambahkan. <a href='index.php'>Kembali ke halaman utama</a>";
    } else {
        echo "Error: " . $stmt->error;
    }

    // Tutup statement dan koneksi
    $stmt->close();
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Form Input Kos</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #0b1957;
            color: white;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        .container {
            background-color: #CC8DB3;
            padding: 20px 30px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            width: 100%;
            max-width: 400px;
        }

        h1 {
            text-align: center;
            color: #0b1957;
        }

        form {
            display: flex;
            flex-direction: column;
        }

        label {
            margin-top: 10px;
            margin-bottom: 5px;
            font-weight: bold;
        }

        input, textarea, button {
            padding: 10px;
            border: none;
            border-radius: 5px;
            font-size: 14px;
        }

        input, textarea {
            margin-bottom: 15px;
        }

        button {
            background-color: #0b1957;
            color: white;
            cursor: pointer;
            font-weight: bold;
        }

        button:hover {
            background-color: #09133e;
        }

        a {
            color: #0b1957;
            text-decoration: none;
            font-weight: bold;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Tambah Data Kos</h1>
        <form method="POST" action="">
            <label for="nama">Nama Kos:</label>
            <input type="text" id="nama" name="nama" required>

            <label for="harga">Harga:</label>
            <input type="number" id="harga" name="harga" required>

            <label for="kontak">Kontak:</label>
            <input type="text" id="kontak" name="kontak" required>

            <label for="latitude">Latitude:</label>
            <input type="text" id="latitude" name="latitude" required>

            <label for="longitude">Longitude:</label>
            <input type="text" id="longitude" name="longitude" required>

            <label for="fasilitas">Fasilitas:</label>
            <textarea id="fasilitas" name="fasilitas" required></textarea>

            <button type="submit">Simpan</button>
        </form>
    </div>
</body>
</html>
