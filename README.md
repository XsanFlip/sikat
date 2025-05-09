
<img width="1026" alt="Screenshot 2025-05-09 at 14 41 08" src="https://github.com/user-attachments/assets/b723bca9-8da2-4bab-a698-99347997fce9" />



Versi: 1.0
Tanggal Rilis: 24 April 2025
Developer: IT T4mpan

-----------------------------------------------------------
DESKRIPSI
-----------------------------------------------------------
SIKAT adalah tools berbasis web (PHP) untuk menganalisis jaringan menggunakan traceroute, ping, dan MTR. Tools ini menyajikan output analisis dalam bentuk teks dan grafik, serta memberikan penjelasan awam-friendly.

-----------------------------------------------------------
FITUR UTAMA
-----------------------------------------------------------
✔ Traceroute analysis dengan grafik per-hop (ms)

✔ Ping latency test

✔ MTR (MyTraceroute) report (Linux only)

✔ Analisa otomatis hasil traceroute

✔ Desain UI cantik dan responsif (gradasi warna)

✔ Single file PHP (mudah deploy di shared hosting)

✔ Tidak membutuhkan database

✔ Friendly untuk user awam dan teknisi

-----------------------------------------------------------
KOMPONEN YANG DIBUTUHKAN
-----------------------------------------------------------
✅ PHP 7.4 atau lebih baru

✅ Web server (Apache/Nginx)

✅ Sistem Operasi:
   - Windows (ping dan tracert)
   - Linux (ping, traceroute, mtr*)

✅ Akses shell/command-line (`exec()` diaktifkan di PHP)

✅ Browser modern (untuk tampilan Chart.js)

✅ Opsional (Linux): `mtr` harus tersedia di path sistem

-----------------------------------------------------------
CARA INSTALASI
-----------------------------------------------------------
1. Upload file `sikat.php` ke folder public web server Anda (misal `htdocs/` atau `public_html/`).
2. Pastikan `exec()` tidak diblokir di konfigurasi `php.ini`.
3. (Opsional - Linux) Install `mtr` jika ingin menggunakan fitur MTR:
   - Debian/Ubuntu: `sudo apt install mtr`
   - CentOS/RHEL: `sudo yum install mtr`
4. Akses dari browser:  
   `http://localhost/sikat.php`  
   atau  
   `http://IP-server-anda/sikat.php`

-----------------------------------------------------------
PENGGUNAAN
-----------------------------------------------------------
1. Masukkan IP Address atau nama domain (misalnya `google.com`)
2. Pilih mode analisa:
   - PING
   - TRACEROUTE
   - MTR
3. Klik tombol "Jalankan"
4. Hasil akan langsung muncul di bawah beserta analisa (khusus Traceroute)
5. Untuk traceroute, grafik latensi per hop akan ditampilkan

-----------------------------------------------------------
PERINGATAN KEAMANAN
-----------------------------------------------------------
🔒 Tools ini menggunakan `exec()`, pastikan:
- File hanya dapat diakses oleh pengguna yang berwenang
- Jangan upload ke server publik tanpa otentikasi tambahan
- Validasi input sudah menggunakan `escapeshellarg`, namun batasi akses file ini hanya untuk debugging/monitoring internal

-----------------------------------------------------------
LISENSI
-----------------------------------------------------------
SIKAT dilisensikan di bawah MIT License.

Copyright (c) 2025

Izin diberikan secara gratis kepada siapa pun yang memperoleh salinan perangkat lunak ini dan file dokumentasi terkait ("Perangkat Lunak"), untuk menangani Perangkat Lunak tanpa batasan, termasuk hak untuk menggunakan, menyalin, mengubah, menggabungkan, menerbitkan, mendistribusikan, mensublisensikan, dan/atau menjual salinan Perangkat Lunak.

*Tidak ada jaminan keamanan dan ketersediaan. Gunakan sesuai risiko masing-masing.*

-----------------------------------------------------------
