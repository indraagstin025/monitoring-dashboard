# User Requirements - Monitoring Dashboard

| ID | Modul | Fitur | User Requirement | Prioritas |
| --- | --- | --- | --- | --- |
| UR-001 | Umum | Akses Aplikasi | Aplikasi dapat diakses melalui browser. | High |
| UR-002 | Umum | Landing Page | Aplikasi memiliki halaman welcome sebelum user login. | Medium |
| UR-003 | Umum | Autentikasi | Sistem menggunakan autentikasi session Laravel. | High |
| UR-004 | Umum | Database | Semua data aplikasi tersimpan di database MySQL. | High |
| UR-005 | Umum | Frontend | Frontend menggunakan Blade, Tailwind CSS, Alpine.js, dan Vite. | Medium |
| UR-006 | Umum | Backend | Backend menggunakan Laravel dan PHP. | High |
| UR-007 | Umum | Theme | Aplikasi mendukung tampilan Light Mode dan Dark Mode. | Medium |
| UR-008 | Umum | Dark Mode Otomatis | Dark Mode dapat aktif otomatis mengikuti jam lokal pengguna. | Medium |
| UR-009 | Login | Form Email | Halaman login memiliki input email. | High |
| UR-010 | Login | Form Password | Halaman login memiliki input password dengan karakter tersembunyi. | High |
| UR-011 | Login | Remember Me | Halaman login memiliki pilihan Remember Me. | Low |
| UR-012 | Login | Submit Login | User dapat menekan tombol Login untuk masuk ke aplikasi. | High |
| UR-013 | Login | Login Berhasil | Jika email dan password benar, user masuk ke halaman dashboard. | High |
| UR-014 | Login | Login Gagal | Jika email atau password salah, sistem menampilkan pesan error. | High |
| UR-015 | Login | Validasi Login | Field login yang kosong tidak dapat disubmit. | High |
| UR-016 | Register | Form Register | Halaman register memiliki input nama, email, password, dan konfirmasi password. | Medium |
| UR-017 | Register | Submit Register | User dapat membuat akun baru melalui halaman register. | Medium |
| UR-018 | Register | Validasi Email | Email yang sudah terdaftar tidak dapat digunakan kembali. | Medium |
| UR-019 | Register | Validasi Password | Password dan konfirmasi password harus sama. | Medium |
| UR-020 | Logout | Logout | User dapat logout dari aplikasi. | High |
| UR-021 | Logout | Session | User yang sudah logout tidak dapat membuka dashboard tanpa login ulang. | High |
| UR-022 | Navigation | Navbar | Navbar menampilkan identitas aplikasi dan menu utama. | Medium |
| UR-023 | Navigation | Menu Dashboard | Navbar memiliki link menuju halaman dashboard. | High |
| UR-024 | Navigation | Menu Reports | Navbar memiliki link menuju halaman reports. | Medium |
| UR-025 | Navigation | Menu Notification | Navbar memiliki link menuju halaman pengaturan notifikasi. | Medium |
| UR-026 | Navigation | Menu Profile | Navbar memiliki menu profile user. | Low |
| UR-027 | Navigation | Responsive Menu | Navigasi dapat digunakan pada tampilan desktop dan mobile. | Medium |
| UR-028 | Dashboard | Judul Dashboard | Dashboard menampilkan judul Monitoring Dashboard. | Medium |
| UR-029 | Dashboard | Status Publik | Dashboard memiliki tombol menuju halaman status publik. | Medium |
| UR-030 | Dashboard | Ringkasan Total | Dashboard menampilkan total target yang dipantau. | High |
| UR-031 | Dashboard | Ringkasan Online | Dashboard menampilkan jumlah target Online. | High |
| UR-032 | Dashboard | Ringkasan Down | Dashboard menampilkan jumlah target Down. | High |
| UR-033 | Dashboard | Ringkasan Unstable | Dashboard menampilkan jumlah target Unstable. | Medium |
| UR-034 | Dashboard | Ringkasan Paused | Dashboard menampilkan jumlah target yang dijeda. | Medium |
| UR-035 | Dashboard | Daftar Target | Dashboard menampilkan daftar target monitoring. | High |
| UR-036 | Dashboard | Informasi Target | Setiap target menampilkan nama, protocol, host, dan port. | High |
| UR-037 | Dashboard | Status Target | Setiap target menampilkan status terbaru. | High |
| UR-038 | Dashboard | Response Time | Setiap target menampilkan response time terbaru. | High |
| UR-039 | Dashboard | Uptime 7D | Setiap target menampilkan uptime 7 hari. | High |
| UR-040 | Dashboard | SSL Certificate | Target HTTPS menampilkan sisa masa berlaku SSL certificate. | Medium |
| UR-041 | Dashboard | Terakhir Cek | Setiap target menampilkan waktu terakhir dicek. | High |
| UR-042 | Target | Tambah Target | User dapat membuka form tambah target dari dashboard. | High |
| UR-043 | Target | Form Target | Form target memiliki field nama, host atau URL, protocol, port, interval, timeout, threshold, dan group. | High |
| UR-044 | Target | Normalisasi URL | User dapat memasukkan URL lengkap dan sistem menormalisasi protocol, host, dan port. | High |
| UR-045 | Target | Validasi Nama | Nama target wajib diisi. | High |
| UR-046 | Target | Validasi Host | Host atau URL wajib diisi dan harus valid. | High |
| UR-047 | Target | Validasi Protocol | Protocol hanya dapat menggunakan HTTP, HTTPS, atau TCP. | High |
| UR-048 | Target | Validasi Port | Port harus berupa angka valid. | High |
| UR-049 | Target | Validasi Interval | Check interval harus berupa angka valid. | Medium |
| UR-050 | Target | Validasi Timeout | Timeout harus berupa angka valid. | Medium |
| UR-051 | Target | Validasi Threshold | Alert threshold harus berupa angka valid. | Medium |
| UR-052 | Target | Simpan Target | Target berhasil ditambahkan dan tampil di dashboard. | High |
| UR-053 | Target | Edit Target | User dapat mengubah data target yang sudah dibuat. | High |
| UR-054 | Target | Update Target | Perubahan data target tersimpan ke database. | High |
| UR-055 | Target | Hapus Target | User dapat menghapus target monitoring. | Medium |
| UR-056 | Target | Konfirmasi Hapus | Sistem menampilkan konfirmasi sebelum target dihapus. | Medium |
| UR-057 | Target | Pause Target | User dapat menjeda target agar tidak dicek otomatis. | Medium |
| UR-058 | Target | Resume Target | User dapat mengaktifkan kembali target yang dijeda. | Medium |
| UR-059 | Target Detail | Detail Target | User dapat membuka halaman detail target. | Medium |
| UR-060 | Target Detail | Informasi Detail | Halaman detail menampilkan informasi status, response time, uptime, SSL, dan riwayat log. | Medium |
| UR-061 | Monitoring | Scheduler | Sistem dapat menjalankan pengecekan target melalui scheduler. | High |
| UR-062 | Monitoring | Queue Worker | Sistem dapat menjalankan job monitoring melalui queue worker. | High |
| UR-063 | Monitoring | Check Interval | Sistem mengecek target sesuai check interval masing-masing target. | High |
| UR-064 | Monitoring | Status Log | Sistem menyimpan hasil pengecekan target ke status log. | High |
| UR-065 | Monitoring | Online | Sistem menandai target Online jika pengecekan berhasil. | High |
| UR-066 | Monitoring | Down | Sistem menandai target Down jika pengecekan gagal. | High |
| UR-067 | Monitoring | Unstable | Sistem menandai target Unstable jika target melewati ambang alert. | Medium |
| UR-068 | Monitoring | Paused | Sistem tidak mengecek target yang sedang dijeda. | Medium |
| UR-069 | Monitoring | Status Independen | Sistem tidak langsung menganggap target Down hanya karena uptime atau SSL belum dihitung. | High |
| UR-070 | Realtime | Broadcast Event | Sistem menggunakan Laravel Reverb untuk broadcast hasil monitoring. | Medium |
| UR-071 | Realtime | Update Dashboard | Dashboard dapat berubah tanpa refresh saat event realtime diterima. | Medium |
| UR-072 | Realtime | Update Status | Status target dapat berubah tanpa refresh halaman. | Medium |
| UR-073 | Realtime | Update Response Time | Response time target dapat berubah tanpa refresh halaman. | Medium |
| UR-074 | Realtime | Update Summary | Kartu ringkasan dashboard dapat berubah tanpa refresh halaman. | Medium |
| UR-075 | Uptime | Uptime 1D | Sistem dapat menghitung uptime 1 hari. | Medium |
| UR-076 | Uptime | Uptime 7D | Sistem dapat menghitung uptime 7 hari. | High |
| UR-077 | Uptime | Uptime 30D | Sistem dapat menghitung uptime 30 hari. | Medium |
| UR-078 | Uptime | Data Tersedia | Uptime dihitung dari data yang tersedia dan tidak harus menunggu periode penuh. | High |
| UR-079 | Uptime | Recalculate | Command uptime:calculate dapat menghitung ulang uptime target. | Medium |
| UR-080 | SSL | Check SSL | Sistem mengecek SSL certificate untuk target HTTPS. | Medium |
| UR-081 | SSL | Expired Date | Sistem menyimpan tanggal expired SSL certificate. | Medium |
| UR-082 | SSL | Sisa Hari SSL | Sistem menampilkan sisa hari SSL certificate. | Medium |
| UR-083 | SSL | Non HTTPS | Sistem tidak menampilkan SSL certificate untuk target HTTP atau TCP. | Low |
| UR-084 | SSL | SSL Error | SSL invalid atau gagal dicek menampilkan kondisi error yang jelas. | Medium |
| UR-085 | Notification | Notification Settings | User dapat membuka halaman pengaturan notifikasi. | Medium |
| UR-086 | Notification | Tambah Channel | User dapat menambah channel notifikasi. | High |
| UR-087 | Notification | Telegram | User dapat memilih tipe channel Telegram. | High |
| UR-088 | Notification | Discord | User dapat memilih tipe channel Discord. | Medium |
| UR-089 | Notification | Aktif Nonaktif | User dapat mengaktifkan atau menonaktifkan channel notifikasi. | Medium |
| UR-090 | Notification | Test Notification | User dapat mengirim test notification. | High |
| UR-091 | Notification | Error Notification | Test notification gagal menampilkan pesan error yang jelas. | High |
| UR-092 | Notification | Hapus Channel | User dapat menghapus channel notifikasi. | Medium |
| UR-093 | Telegram | Bot Token | Form Telegram memiliki input Bot Token. | High |
| UR-094 | Telegram | Chat ID | Form Telegram memiliki input Chat ID. | High |
| UR-095 | Telegram | URL getUpdates | User dapat memasukkan URL getUpdates dan sistem menormalisasi token. | Medium |
| UR-096 | Telegram | Ambil Chat ID | Sistem dapat mengambil Chat ID dari Telegram getUpdates. | Medium |
| UR-097 | Telegram | Daftar Chat | Sistem menampilkan daftar chat yang ditemukan dari getUpdates. | Medium |
| UR-098 | Telegram | Token Invalid | Token Telegram tidak valid menampilkan pesan error. | High |
| UR-099 | Telegram | Test Telegram | Test Telegram berhasil mengirim pesan ke chat tujuan. | High |
| UR-100 | Discord | Webhook URL | Form Discord memiliki input Webhook URL. | Medium |
| UR-101 | Discord | Validasi Webhook | Webhook URL wajib valid. | Medium |
| UR-102 | Discord | Test Discord | Test Discord berhasil mengirim pesan ke channel tujuan. | Medium |
| UR-103 | Reports | Halaman Reports | User dapat membuka halaman reports. | Medium |
| UR-104 | Reports | Ringkasan Reports | Reports menampilkan ringkasan data monitoring. | Medium |
| UR-105 | Reports | Total Target | Reports menampilkan total target. | Medium |
| UR-106 | Reports | Status Target | Reports menampilkan status target. | Medium |
| UR-107 | Reports | Rata-rata Uptime | Reports menampilkan rata-rata uptime jika data tersedia. | Medium |
| UR-108 | Reports | Rata-rata Response Time | Reports menampilkan rata-rata response time jika data tersedia. | Medium |
| UR-109 | Reports | Data Kosong | Reports tidak error saat data monitoring masih kosong. | High |
| UR-110 | Public Status | Akses Publik | Halaman status publik dapat diakses tanpa login. | Medium |
| UR-111 | Public Status | Daftar Publik | Halaman status publik menampilkan daftar target yang dipublikasikan. | Medium |
| UR-112 | Public Status | Status Publik | Halaman status publik menampilkan status setiap target. | Medium |
| UR-113 | Public Status | Response Publik | Halaman status publik menampilkan response time setiap target. | Medium |
| UR-114 | Public Status | Uptime Publik | Halaman status publik menampilkan uptime setiap target. | Medium |
| UR-115 | Public Status | Empty State | Halaman status publik menampilkan pesan kosong jika belum ada target. | Medium |
| UR-116 | Profile | Edit Profile | User dapat mengubah nama dan email akun. | Low |
| UR-117 | Profile | Change Password | User dapat mengubah password akun. | Low |
| UR-118 | Profile | Validasi Password | Password lama wajib benar sebelum password diganti. | Low |
| UR-119 | Profile | Delete Account | User dapat menghapus akun jika fitur tersedia. | Low |
| UR-120 | UI Theme | Warna Utama | Desain menggunakan warna utama kuning dan hitam. | Medium |
| UR-121 | UI Theme | Material Design | Desain mengikuti gaya Material Design. | Medium |
| UR-122 | UI Theme | Konsistensi Komponen | Button, card, dan form memiliki tampilan yang konsisten. | Medium |
| UR-123 | UI Theme | Status Color | Status Online, Down, Unstable, dan Paused memiliki indikator warna berbeda. | Medium |
| UR-124 | UI Theme | Responsif | Layout tidak tumpang tindih pada desktop dan mobile. | High |
| UR-125 | Validation | Error Form | Setiap form menampilkan pesan error jika input tidak valid. | High |
| UR-126 | Validation | Bahasa Error | Pesan error menggunakan bahasa yang mudah dipahami user. | Medium |
| UR-127 | Validation | Old Input | Data input user tidak hilang saat validasi gagal. | Medium |
| UR-128 | Validation | Success Feedback | Sistem menampilkan feedback sukses setelah aksi berhasil. | Medium |
| UR-129 | Validation | Hide Stack Trace | Sistem tidak menampilkan stack trace kepada user. | High |
| UR-130 | Database | Migration Users | Terdapat migration untuk tabel users. | High |
| UR-131 | Database | Migration Targets | Terdapat migration untuk tabel targets. | High |
| UR-132 | Database | Migration Status Logs | Terdapat migration untuk tabel status_logs. | High |
| UR-133 | Database | Migration Incidents | Terdapat migration untuk tabel incidents. | Medium |
| UR-134 | Database | Migration Notifications | Terdapat migration untuk tabel notification_channels. | Medium |
| UR-135 | Database | Migration Jobs | Terdapat migration untuk tabel queue jobs. | High |
| UR-136 | Database | Seeder Admin | Terdapat seeder akun admin default untuk testing. | Medium |
| UR-137 | Deployment | GitHub | Project dapat dipush ke GitHub. | High |
| UR-138 | Deployment | Railway Service | Railway dapat connect ke repository GitHub dan menjalankan service Laravel. | Medium |
| UR-139 | Deployment | Railway MySQL | Railway memiliki database MySQL untuk aplikasi. | Medium |
| UR-140 | Deployment | Environment Variables | Environment variable Laravel diisi di Railway. | High |
| UR-141 | Deployment | Production Config | APP_ENV diset production, APP_DEBUG false, APP_KEY tersedia, dan APP_URL sesuai domain Railway. | High |
| UR-142 | Deployment | Build | Build command menginstall dependency PHP dan Node serta menjalankan build asset frontend. | Medium |
| UR-143 | Deployment | Migration Production | Migration dapat dijalankan di Railway. | High |
| UR-144 | Deployment | Worker Production | Queue worker dapat dijalankan sebagai service terpisah jika dibutuhkan. | Medium |
| UR-145 | Deployment | Scheduler Production | Scheduler dapat dijalankan sebagai service atau cron sesuai kebutuhan deployment. | Medium |
