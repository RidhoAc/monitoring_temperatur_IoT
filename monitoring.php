<?php
// Koneksi ke database
$conn = mysqli_connect("localhost", "root", "", "monitoring_termal");

// Inisialisasi tanggal default jika belum ada input
$tanggal = date("Y-m-d");
if (isset($_POST['date'])) {
  $tanggal = $_POST['date'];
}

// Query untuk mengambil data dari database
$sql = "SELECT tanggal, waktu, gambar, temperatur FROM data_termal WHERE tanggal = '$tanggal' ORDER BY tanggal DESC, waktu DESC";
$result = mysqli_query($conn, $sql);

// Fungsi untuk membuat link download gambar
function createDownloadLink($gambar) {
  return "<a href='$gambar' download><button style='background-color: blue; color: white;'>Download</button></a>";
}

// Fungsi untuk membuat tabel
function createTable($result) {
  $table = "<table style='border-collapse: collapse; width: 100%;'>
              <thead style='background-color: blue; color: white'>
                <tr>
                  <th style='border: 1px solid black; text-align: center; padding: 5px; width: 15%'>Tanggal</th>
                  <th style='border: 1px solid black; text-align: center; padding: 5px; width: 15%'>Waktu</th>
                  <th style='border: 1px solid black; text-align: center; padding: 5px;'>Output Gambar Termal</th>
                  <th style='border: 1px solid black; text-align: center; padding: 5px; width: 20%'>Output Nilai Temperatur (°C)</th>
                </tr>
              </thead>
              <tbody>";
  while ($row = mysqli_fetch_assoc($result)) {
    $tanggal = $row["tanggal"];
    $waktu = date("H:i:s", strtotime($row["waktu"]));
    $gambar = $row["gambar"];
    $temperatur = $row["temperatur"];
    $tanggal_folder = $tanggal; // tambahkan ini untuk membuat format nama folder
    $nama_file = $gambar;
    $table .= "<tr>
                  <td style='border: 1px solid black; text-align: center; padding: 5px;'>$tanggal</td>
                  <td style='border: 1px solid black; text-align: center; padding: 5px;'>$waktu</td>
                  <td style='border: 1px solid black; text-align: center; padding: 5px;'><img src='$gambar' width='100px'><br>" . createDownloadLink($gambar) . "<br><button style='background-color: red; color: white;' onclick='hapusData(\"$tanggal_folder\", \"$nama_file\")'>Hapus</button></td>
                  <td style='border: 1px solid black; text-align: center; padding: 5px; font-size: 18px;'>$temperatur</td>
                </tr>";
  }
  $table .= "</tbody>
            </table>";
  return $table;
}

// Form untuk memilih tanggal
echo "<form method='post' style='text-align: center; margin-top: 20px;'>
        <label>Pilih Tanggal:</label>
        <input type='date' name='date' value='$tanggal' required>
        <button style='background-color: blue; color: white;' type='submit'>Tampilkan</button>
      </form>";

// Tombol download PDF
echo "<div style='text-align: center; margin-top: 20px;'><button style='background-color: blue; color: white;' onclick='window.print()'>Unduh PDF</button></div>";
?>

<!DOCTYPE html>
<html>
<meta charset="UTF-8">
<head>

  <title>WEBSITE MONITORING TEMPERATUR MESIN</title>
</head>
<body style="font-family: Arial, sans-serif;">
  <a href="../index.php"><button style='background-color: blue; color: white'>Kembali</button></a>
  <h1 style="text-align: center;">WEBSITE MONITORING TEMPERATUR MESIN</h1>
  <?php
  // Memeriksa apakah query berhasil dijalankan
  if (mysqli_num_rows($result) > 0) {
    echo "<h2 style='text-align: center;'>Data untuk tanggal $tanggal</h2>";
    echo createTable($result);
  } else {
    echo "<h2 style='text-align: center;'>Tidak ada data untuk tanggal $tanggal</h2>";
  }
  ?>
  
  <script>
function hapusData(tanggal, gambar) {
  var konfirmasi = confirm("Apakah Anda yakin ingin menghapus data ini?");
  if (konfirmasi == true) {
    // Membuat objek AJAX
    var xhttp = new XMLHttpRequest();
    xhttp.onreadystatechange = function() {
      if (this.readyState == 4 && this.status == 200) {
        // Menampilkan pesan sukses atau error
        alert(this.responseText);
        // Reload halaman setelah menghapus data
        location.reload();
      }
    };
    // Mengirim permintaan AJAX ke server
    xhttp.open("GET", "hapus_data.php?tanggal=" + tanggal + "&gambar=" + gambar, true);
    xhttp.send();
  }
}
</script>
</body>

</html>
