function updateDateTime() {
  var now = new Date();
  
  // Array untuk nama hari dalam bahasa Inggris
  var days = ['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'];
  
  // Array untuk nama bulan dalam bahasa Inggris
  var months = ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'];
  
  // Mendapatkan nama hari dengan mengakses indeks dari array 'days'
  var dayName = days[now.getDay()];
  
  // Mendapatkan tanggal dengan metode getDate()
  var date = now.getDate();
  
  // Mendapatkan nama bulan dengan mengakses indeks dari array 'months'
  var monthName = months[now.getMonth()];
  
  // Mendapatkan tahun dengan metode getFullYear()
  var year = now.getFullYear();
  
  // Format tanggal
  var dateString = dayName + ', ' + monthName + ' ' + date + ', ' + year;
  
  // Mendapatkan jam dan menambahkan leading zero jika nilai jam atau menit < 10
  var hour = now.getHours() < 10 ? '0' + now.getHours() : now.getHours();
  var minute = now.getMinutes() < 10 ? '0' + now.getMinutes() : now.getMinutes();
  
  // Format jam
  var timeString = hour + ':' + minute;
  
  // Gabungkan tanggal dan jam
  var dateTimeString = dateString + ' ' + timeString;
  
  var datetimeElement = document.getElementById('datetime');
  if (datetimeElement) {
    datetimeElement.textContent = dateTimeString;
  }
}

// Panggil fungsi updateDateTime setiap menit
setInterval(updateDateTime, 60000);

// Jalankan fungsi pertama kali untuk menginisialisasi
updateDateTime();
