<?php
// Koneksi ke database
$conn = mysqli_connect("localhost", "root", "", "monitoring_termal");

// Mengambil data dari parameter GET
$tanggal = $_GET['tanggal'];
$gambar = $_GET['gambar'];

// Menghapus data dan gambar dari database dan server
$sql = "DELETE FROM data_termal WHERE tanggal = '$tanggal' AND gambar = '$gambar'";
if (mysqli_query($conn, $sql)) {
  unlink($gambar); // Menghapus file gambar dari server
  echo "Data berhasil dihapus.";
} else {
  echo "Error: " . $sql . "<br>" . mysqli_error($conn);
}

// Menutup koneksi ke database
mysqli_close($conn);
?>