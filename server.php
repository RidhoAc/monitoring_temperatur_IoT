<?php
date_default_timezone_set('Asia/Jakarta');

// Koneksi ke database
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "monitoring_termal";

$conn = mysqli_connect($servername, $username, $password, $dbname);

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Menerima data dari client
$temperature = $_POST["temperature"];
$image = $_POST["image"];

// Simpan data ke file
//ubah angka dua untuk mengatur digit koma di belakang
file_put_contents("temperature.txt", number_format($temperature, 2));
file_put_contents("temperature.jpg", $image);

// Simpan data ke database satu kali dalam satu jam

    // Buat folder jika belum ada
    $folderName = date('Ymd');
    if (!file_exists($folderName)) {
        mkdir($folderName);
    }

    // Simpan gambar ke folder
    $hour = date('H');
    $fileName = $hour . '.jpg';
    $filePath = $folderName . '/' . $fileName;
    

    // Cek apakah sudah ada data pada jam yang sama
    $date = date('Y-m-d');
    $time = date('H:00:00');
    $sql = "SELECT * FROM data_termal WHERE tanggal = '$date' AND waktu = '$time'";
    $result = mysqli_query($conn, $sql);

    // Simpan data ke database jika belum ada data pada jam yang sama
    if (mysqli_num_rows($result) == 0) {
        $sql = "INSERT INTO data_termal (tanggal, waktu, gambar, temperatur) VALUES ('$date', '$time', '$filePath', '$temperature')";
        mysqli_query($conn, $sql);
		//simpan gambar ke folder sesuai dengan tanggal folder
		file_put_contents($filePath, $image);
    }

    // Update last saved time


mysqli_close($conn);

?>
