<?php

ini_set('display_errors', 1);
error_reporting(E_ALL);

// Sesuaikan dengan setting MySQL
$servername = "localhost";
$username = "root";
$password = ""; // Ganti dengan password MySQL root jika ada
$dbname = "pgweb_acara7b";

// Buat koneksi
$conn = new mysqli($servername, $username, $password, $dbname);

// Cek koneksi
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Query untuk mengambil semua data dari tabel penduduk
$sql = "SELECT * FROM penduduk";
$result = $conn->query($sql);

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rekomendasi Kos di Sleman</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"
        integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin="" />
    <link rel="stylesheet" href="mainstyle.css">
</head>

<body>
    <!-- Header -->
    <header>
        <div class="logo">
            <h1>KosFinder</h1>
        </div>
        <nav>
            <ul>
                <li><a href="#home">Home</a></li>
                <li><a href="#daerah">Daerah</a></li>
                <li><a href="#kontak" data-bs-toggle="modal" data-bs-target="#kontakModal">Kontak</a></li>
            </ul>
        </nav>
    </header>

    <!-- Main Section with Map and Introduction -->
    <section id="home" class="hero">
        <h2>Temukan Kos Affordable di Sekitar Kampusmu</h2>

        <!-- Peta atau fitur interaktif bisa ditambahkan di sini -->
        <div class="text-center my-4">
            <button class="btn btn-primary mx-2" onclick="window.location.href='ugm.php'">Rekomendasi Kos</button>
            <button class="btn btn-primary mx-2" onclick="window.location.href='form.php'">Pengisian Form</button>
        </div>

    </section>


    <!-- Pertimbangan Section -->
    <section id="daerah" class="daerah">



        <div class="container mt-5">
            <h4>Hal yang harus dipertimbangkan</h4>

            <!-- Collapse untuk Harga -->
            <button class="btn btn-primary mt-3" type="button" data-bs-toggle="collapse" data-bs-target="#price" aria-expanded="false" aria-controls="price">
                💰 Harga
            </button>
            <div class="collapse" id="price">
                <div class="card card-body mt-2">
                    <h5>Harga Kos</h5>
                    <p>Harga kos sangat bergantung pada berbagai faktor seperti lokasi, ukuran ruangan, fasilitas yang tersedia, dan kondisi bangunan. Biasanya, kos yang terletak di area pusat kota atau dekat dengan kampus atau pusat perbelanjaan cenderung lebih mahal. Selain itu, fasilitas tambahan seperti AC, Wi-Fi, atau keamanan 24 jam juga bisa mempengaruhi harga. Oleh karena itu, penting untuk mempertimbangkan anggaran pribadi dan membandingkan harga dari beberapa pilihan kos yang tersedia.</p>
                </div>
            </div>

            <!-- Collapse untuk Lokasi -->
            <button class="btn btn-primary mt-3" type="button" data-bs-toggle="collapse" data-bs-target="#location" aria-expanded="false" aria-controls="location">
                📍 Lokasi
            </button>
            <div class="collapse" id="location">
                <div class="card card-body mt-2">
                    <h5>Lokasi Kos</h5>
                    <p>Lokasi kos memegang peranan penting dalam menentukan kenyamanan dan aksesibilitas penghuninya. Kos yang terletak dekat dengan tempat-tempat strategis seperti kampus, pusat perbelanjaan, atau transportasi umum akan memberikan kemudahan akses yang lebih baik. Lokasi yang baik juga dapat meningkatkan kualitas hidup dengan lingkungan yang lebih aman dan dekat dengan fasilitas umum. Oleh karena itu, pemilihan lokasi kos sebaiknya mempertimbangkan faktor-faktor ini agar sesuai dengan kebutuhan penghuni..</p>
                </div>
            </div>

            <!-- Collapse untuk Kondisi -->
            <button class="btn btn-primary mt-3" type="button" data-bs-toggle="collapse" data-bs-target="#condition" aria-expanded="false" aria-controls="condition">
                🏠 Kondisi
            </button>
            <div class="collapse" id="condition">
                <div class="card card-body mt-2">
                    <h5>Kondisi Kos</h5>
                    <p>Kondisi fisik kos sangat berpengaruh pada kenyamanan penghuni. Kos yang terawat dengan baik, bersih, dan aman akan menciptakan lingkungan yang lebih nyaman untuk tinggal. Penting untuk memeriksa apakah bangunan kos dalam kondisi baik, apakah sudah ada renovasi atau ada kerusakan yang perlu diperbaiki. Kos yang dalam kondisi prima tidak hanya memberikan kenyamanan, tetapi juga menjamin keselamatan penghuninya selama tinggal di sana.</p>
                </div>
            </div>

            <!-- Collapse untuk Kendaraan -->
            <button class="btn btn-primary mt-3" type="button" data-bs-toggle="collapse" data-bs-target="#vehicle" aria-expanded="false" aria-controls="vehicle">
                🚗 Kendaraan
            </button>
            <div class="collapse" id="vehicle">
                <div class="card card-body mt-2">
                    <h5>Kendaraan dan Akses Transportasi</h5>
                    <p>asilitas kendaraan di kos dapat menjadi faktor penting, terutama bagi mereka yang menggunakan kendaraan pribadi atau mengandalkan transportasi umum. Beberapa kos menyediakan tempat parkir yang aman bagi kendaraan, baik motor maupun mobil, sehingga penghuni tidak perlu khawatir tentang tempat parkir. Selain itu, akses yang mudah ke transportasi umum seperti bus atau kereta api juga sangat membantu bagi penghuni yang tidak memiliki kendaraan pribadi.</p>
                </div>
            </div>
        </div>
    </section>


    <section id="daerah" class="daerah">
        <h4>Sebaran Universitas di Kabupaten Sleman</h4>
        <div id="map"></div>
    </section>


<!-- Modal Kontak -->
<div class="modal fade" id="kontakModal" tabindex="-1" aria-labelledby="kontakModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="kontakModalLabel">Kontak Kami</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p><strong>Name:</strong> Aisyah Girindra</p>
                <p><strong>NIM:</strong> 23/518650/SV/23026</p>
                <p><strong>Tahun:</strong> 2nd Year of GIS Student</p>
                <p><strong>Email:</strong> aisyahgirindra@mail.ugm.ac.id</p>
                <p><strong>Telepon:</strong> 087794106811</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>

    </section>
    <footer>
        <p>&copy; 2024 KosFinder. All rights reserved.</p>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"
        integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>
    <script>
        // Inisialisasi peta
        var map = L.map("map").setView([-7.733651373396419, 110.3725897788355], 13);

        // Tile Layer Base Map
        var basemap = L.tileLayer("https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png", {
            attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors',
        });



        // Menambahkan basemap ke dalam peta
        basemap.addTo(map);


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

        
        // Membuat Pane untuk GeoJSON Jalan
map.createPane('panejalan'); 
map.getPane('panejalan').style.zIndex = 401;  // Menentukan zIndex agar lapisan jalan berada di atas lapisan lainnya

// Membuat GeoJSON Polyline Jalan
var jalan = L.geoJSON(null, {
    pane: 'panejalan',  // Menambahkan pane ke GeoJSON
    style: function (feature) {
        return {
            color: "red",   // Warna polyline jalan
            opacity: 1,     // Transparansi
            weight: 2       // Ketebalan garis
        };
    }
});

// Memuat data GeoJSON dari file eksternal
$.getJSON("vektor/jalan.geojson", function (data) {
    jalan.addData(data);  // Menambahkan data GeoJSON ke dalam layer jalan
    map.addLayer(jalan);   // Menambahkan layer GeoJSON ke peta
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

        // Buat pane untuk layer Sleman kecamatan
        map.createPane('paneSlemanKec');
        map.getPane("paneSlemanKec").style.zIndex = 200;

        // Memuat file GeoJSON untuk kecamatan Sleman
        var slemanKec = L.geoJSON(null, {
            pane: 'paneSlemanKec',
            style: function(feature) {
                var kecamatan = feature.properties.KECAMATAN; // Mengambil nama kecamatan
                return {
                    color: getColorByKecamatan(kecamatan), // Mengatur warna berdasarkan nama kecamatan
                    weight: 2, // Ketebalan garis
                    opacity: 0.6, // Transparansi garis
                    fillColor: getColorByKecamatan(kecamatan), // Warna pengisi berdasarkan kecamatan
                    fillOpacity: 0.4 // Transparansi pengisi polygon
                };
            },

            // Event handler onEachFeature
            onEachFeature: function(feature, layer) {
                var popup_content = "Kecamatan: " + feature.properties.KECAMATAN;

                layer.on({
                    click: function(e) {
                        layer.bindPopup(popup_content).openPopup(); // Menampilkan popup saat diklik
                    },
                    mouseover: function(e) {
                        // Mengubah gaya saat mouseover
                        layer.setStyle({
                            weight: 4,
                            color: 'black',
                            fillOpacity: 0.7
                        });
                    },
                    mouseout: function(e) {
                        // Mengembalikan gaya ke default saat mouseout
                        layer.setStyle({
                            weight: 2,
                            color: getColorByKecamatan(feature.properties.KECAMATAN),
                            fillOpacity: 0.4
                        });
                    }
                });
            }
        });

        // Memuat file GeoJSON dari folder "geojson/sleman_kec.geojson"
        fetch('vektor/SLEMAN_KEC.geojson')
            .then(response => response.json())
            .then(data => {
                slemanKec.addData(data).addTo(map);
                console.log('GeoJSON data loaded successfully'); // Log untuk memastikan data dimuat
            })
            .catch(error => console.error('Error loading GeoJSON:', error));
    </script>
</body>

</html>