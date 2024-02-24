// Fungsi untuk membaca nilai temperature dan gambar dari file
function updateTemperature() {
    // Baca nilai temperature
    var xhr = new XMLHttpRequest();
    xhr.open("GET", "temperature.txt?" + new Date().getTime(), true);
    xhr.onreadystatechange = function() {
        if (xhr.readyState === XMLHttpRequest.DONE && xhr.status === 200) {
            // Tampilkan nilai temperature
            document.getElementById("temperature").innerHTML = xhr.responseText;
        }
    };
    xhr.send();

    // Tampilkan gambar
    var img = new Image();
    img.src = "temperature.jpg?" + new Date().getTime();
    document.getElementById("image").src = img.src;
}



// Panggil fungsi updateTemperature setiap 1 detik
setInterval(updateTemperature, 1000);
