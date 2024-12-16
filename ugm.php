<?php

ini_set('display_errors', 1);
error_reporting(E_ALL);

// Sesuaikan dengan setting MySQL
$servername = "localhost";
$username = "root";
$password = ""; // Ganti dengan password MySQL root jika ada
$dbname = "responsi_pgweb";

// Buat koneksi
$conn = new mysqli($servername, $username, $password, $dbname);

// Cek koneksi
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Query untuk mengambil semua data dari tabel penduduk
$sql = "SELECT * FROM kos"; // Ganti dengan tabel kos
$result = $conn->query($sql);

// Inisialisasi peta
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Peta dan Tabel Data Penduduk Wilayah Yogyakarta</title>
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        /* Layout Grid untuk membagi peta dan tabel */
       body {
    font-family: Arial, sans-serif;
    margin: 0;
    display: flex;
    flex-direction: column;
    height: 100vh;
    background-color: #1E2A47; /* Navy background */
    color: white; /* White text for contrast */
}

header {
    text-align: center;
    padding: 20px;
    height: 10%;
    width: 100%;
    background-color: #0b1957; /* Purple background for header */
    color: white; /* White text for contrast */
}

#map {
    height: 60%;
    width: 100%;
    margin-right: 20px;
    background-color: #e0e0e0;
}

#tabel-data {
    padding: 20px;
    max-height: 250px;
    overflow-y: auto;
    background-color: #3B4C75; /* Darker navy background for table container */
    border: 1px solid #e0e0e0;
    color: white; /* White text for table */
}

table {
    width: 100%;
    border-collapse: collapse;
}

th, td {
    padding: 10px;
    text-align: left;
    border: 1px solid #e0e0e0;
}

th {
    background-color: #0b1957; /* Purple header color */
    color: white;
}

tr:nth-child(even) {
    background-color: #CC8DB3; /* Lighter purple background for even rows */
}

tr:nth-child(odd) {
    background-color: #0b1957; /* Navy background for odd rows */
}

tr:hover {
    background-color: #0b1957; /* Purple on hover */
}

button {
    background-color: #ddd; /* Purple buttons */
    color: white;
    border: none;
    padding: 10px 15px;
    cursor: pointer;
    border-radius: 5px;
}

button:hover {
    background-color: #CC8DB3; /* Darker purple on hover */
}

.modal-content {
    background-color: #0b1957; /* Dark background for modal */
    color: white; /* White text in modal */
}

.modal-header {
    background-color: #CC8DB3; /* Purple header in modal */
    color: white;
}

.modal-footer button {
    background-color: #CC8DB3; /* Purple footer buttons */
    color: white;
}

.modal-footer button:hover {
    background-color: #3E0070; /* Darker purple on hover */
}

    </style>
</head>

<body>
    <header>
        <h1>Rekomendasi Kos</h1>
        <p>Halaman ini menampilkan data penduduk beserta lokasi geografis masing-masing kecamatan.</p>
    </header>

    <div id="map"></div>

    <div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editModalLabel">Edit Data Kos</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            
    <div class="modal-body">
        <form id="editForm">
            <input type="hidden" id="edit-id">
            <div class="mb-3">
                <label for="nama" class="form-label">Nama Kos</label>
                <input type="text" class="form-control" id="nama" name="nama" required>
            </div>
            <div class="mb-3">
                <label for="harga" class="form-label">Harga</label>
                <input type="number" class="form-control" id="harga" name="harga" required>
            </div>
            <div class="mb-3">
                <label for="kontak" class="form-label">Kontak</label>
                <input type="text" class="form-control" id="kontak" name="kontak" required>
            </div>
            <div class="mb-3">
                <label for="latitude" class="form-label">Latitude</label>
                <input type="text" class="form-control" id="latitude" name="latitude" required>
            </div>
            <div class="mb-3">
                <label for="longitude" class="form-label">Longitude</label>
                <input type="text" class="form-control" id="longitude" name="longitude" required>
            </div>
            <div class="mb-3">
                <label for="fasilitas" class="form-label">Fasilitas</label>
                <input type="text" class="form-control" id="fasilitas" name="fasilitas" required>
            </div>
        </form>
    </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" onclick="saveChanges()">Save changes</button>
            </div>
        </div>
    </div>
</div>

    <div id="tabel-data">
        <h2>Data Kos</h2>
        <?php
        if ($result->num_rows > 0) {
            echo "<table>
                <tr>
                    <th>id</th>
                    <th>Nama</th>
                    <th>Harga</th>
                    <th>Kontak</th>
                    <th>Latitude</th>
                    <th>Longitude</th>
                    <th>Fasilitas</th>
                    <th>Aksi</th>
                </tr>";
            while ($row = $result->fetch_assoc()) {
                echo "<tr>
                    <td>" . $row["id"] . "</td>
                    <td>" . $row["nama"] . "</td>
                    <td>" . $row["harga"] . "</td>
                    <td>" . $row["kontak"] . "</td>
                    <td>" . $row["latitude"] . "</td>
                    <td>" . $row["longitude"] . "</td>
                    <td>" . $row["fasilitas"] . "</td>
                    <td class='action-buttons'>
                        <button onclick=\"editData(" . $row['id'] . ")\">Edit</button>
                        <button onclick=\"deleteData(" . $row['id'] . ")\">Hapus</button>
                    </td>
                  </tr>";
            }
            echo "</table>";
        } else {
            echo "Tidak ada data yang tersedia.";
        }
        ?>
    </div>


    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.min.js"></script>
    <script>
        // Inisialisasi peta
        var map = L.map("map").setView([-7.7706217711188295, 110.35620626294589], 11);

        // Tile Layer Base Map
        var osm = L.tileLayer("https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png", {
            attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors',
        });
        osm.addTo(map);

         // Buat pane untuk layer UNIV_FIX
         map.createPane('paneUNIV_FIX');
                map.getPane("paneUNIV_FIX").style.zIndex = 250;

                // Menentukan ikon untuk setiap titik
                var universityIcon = L.icon({
                    iconUrl: 'pict/pin (1).png', // Ganti dengan URL ikon yang sesuai
                    iconSize: [32, 32], // Ukuran ikon
                    iconAnchor: [16, 32], // Titik tumpu ikon di tengah bawah
                    popupAnchor: [0, -32], // Posisi popup terhadap ikon
                    tooltipAnchor: [16, -32] // Posisi tooltip
                });

                // Membuat layer GeoJSON untuk data universitas
                var univFixLayer = L.geoJSON(null, {
                    pane: 'paneUNIV_FIX',
                    pointToLayer: function(feature, latlng) {
                        // Menambahkan marker dengan ikon khusus untuk setiap titik
                        return L.marker(latlng, {
                            icon: universityIcon
                        });
                    },

                    // onEachFeature untuk menambahkan interaksi dengan setiap fitur
                    onEachFeature: function(feature, layer) {
                        // Konten popup yang akan ditampilkan
                        var popup_content = feature.properties.Nama ? feature.properties.Nama : "Universitas Tidak Dikenal";

                        // Menambahkan event pada setiap marker
                        layer.on({
                            click: function(e) {
                                layer.bindPopup(popup_content).openPopup(); // Menampilkan popup saat marker diklik
                            },
                            mouseover: function(e) {
                                layer.bindTooltip(popup_content, {
                                    direction: "top",
                                    sticky: true
                                }).openTooltip();
                            },
                            mouseout: function(e) {
                                layer.closeTooltip(); // Menutup tooltip saat mouse keluar
                            }
                        });
                    }
                });

                // Memuat data GeoJSON dari file
                fetch('vektor/UNIV_OKE.geojson') // Ganti dengan path file yang sesuai
                    .then(response => response.json())
                    .then(data => {
                        univFixLayer.addData(data).addTo(map); // Menambahkan data GeoJSON ke layer dan peta
                    })
                    .catch(error => console.error('Error loading GeoJSON:', error));

         // Buat pane untuk layer MRB_univ
         map.createPane('paneMRB_univ');
                map.getPane("paneMRB_univ").style.zIndex = 301;

                // Fungsi untuk menentukan warna berdasarkan jarak
                function getColorByDistance(distance) {
                    if (distance <= 1000) {
                        return 'cyan'; // Warna untuk jarak 1000 meter
                    } else if (distance <= 2000) {
                        return 'red'; // Warna untuk jarak 2000 meter
                    } else {
                        return 'orange'; // Warna untuk jarak lebih dari 2000 meter
                    }
                }

                // Memuat data GeoJSON
                var MRB_univ = L.geoJSON(null, {
                    pane: 'paneMRB_univ',
                    style: function(feature) {
                        return {
                            color: 'blue', // Warna garis batas polygon
                            weight: 2, // Ketebalan garis batas
                            opacity: 0.6, // Transparansi garis batas
                            fillColor: getColorByDistance(feature.properties.DISTANCE), // Warna pengisi berdasarkan distance
                            fillOpacity: 0.4 // Transparansi pengisi polygon
                        };
                    },

                    // Event handler onEachFeature
                    onEachFeature: function(feature, layer) {
                        var popup_content = "Distance: " + feature.properties.DISTANCE + " meters";

                        layer.on({
                            click: function(e) {
                                layer.bindPopup(popup_content).openPopup();
                            },
                            mouseover: function(e) {
                                // Tidak ada perubahan pada warna saat mouseover
                                // Tidak ada perubahan warna pada kursor di area tersebut
                            },
                            mouseout: function(e) {
                                // Tidak ada perubahan warna saat mouseout
                            }
                        });
                    }
                });

                // Memuat file GeoJSON dari folder "vektor/MRB_NEW.geojson"
                fetch('vektor/MRB_NEW.geojson')
                    .then(response => response.json())
                    .then(data => {
                        MRB_univ.addData(data).addTo(map); // Menambahkan data GeoJSON ke layer dan peta
                    })
                    .catch(error => console.error('Error loading GeoJSON:', error));

                // Fungsi untuk mendapatkan warna berdasarkan nama kecamatan
                function getColorByKecamatan(kecamatan) {
                    if (kecamatan === "Kecamatan A") {
                        return "red";
                    } else if (kecamatan === "Kecamatan B") {
                        return "blue";
                    } else {
                        return "green"; // Warna default
                    }
                }

        <?php
        // Loop untuk menambahkan marker ke peta
        if ($result->num_rows > 0) {
            $result->data_seek(0);
            while ($row = $result->fetch_assoc()) {
                $nama = $row['nama'];
                $harga = $row['harga'];
                $latitude = $row['latitude'];
                $longitude = $row['longitude'];
                $fasilitas = $row['fasilitas'];

                echo "L.marker([$latitude, $longitude]).addTo(map)
                   .bindPopup('<b>Nama: $nama</b><br>Harga: $harga IDR<br>Fasilitas: $fasilitas');";
            }
        }
        ?>


        function editData(id) {
            fetch(`http://localhost/pgweb/acara9/api.php?id=${id}`)
                .then(response => response.json())
                .then(data => {
                    document.getElementById('edit-id').value = data.id;
                    document.getElementById('nama').value = data.nama;
                    document.getElementById('harga').value = data.harga;
                    document.getElementById('kontak').value = data.kontak;
                    document.getElementById('latitude').value = data.latitude;
                    document.getElementById('longitude').value = data.longitude;
                    document.getElementById('fasilitas').value = data.fasilitas;

                    const editModal = new bootstrap.Modal(document.getElementById('editModal'));
                    editModal.show();
                })
                .catch(error => console.error('Error fetching data:', error));
        }

        function saveChanges() {
            const id = document.getElementById('edit-id').value;
            const updatedData = {
                'edit-id': id,
                nama: document.getElementById('nama').value,
                harga: document.getElementById('harga').value,
                kontak: document.getElementById('kontak').value,
                latitude: document.getElementById('latitude').value,
                longitude: document.getElementById('longitude').value,
                fasilitas: document.getElementById('fasilitas').value,
            };

            fetch(`http://localhost/pgweb/acara9/api.php`, {
                    method: 'PUT',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded',
                    },
                    body: new URLSearchParams(updatedData),
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        alert(data.success);
                        const editModal = bootstrap.Modal.getInstance(document.getElementById('editModal'));
                        editModal.hide();
                        location.reload();
                    } else if (data.error) {
                        alert(data.error);
                    }
                })
                .catch(error => console.error('Error updating data:', error));
        }

        function deleteData(id) {
            if (confirm("Apakah Anda yakin ingin menghapus data ini?")) {
                fetch(`http://localhost/pgweb/acara9/api.php?id=${id}`, {
                        method: 'DELETE',
                    })
                    .then(response => response.json())
                    .then(data => {
                        location.reload();
                    })
                    .catch(error => console.error('Error deleting data:', error));
            }
        }
    </script>
</body>

</html>