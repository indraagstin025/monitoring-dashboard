# User Requirements - Monitoring Dashboard

Dokumen ini berisi daftar kebutuhan pengguna dalam format checklist agar mudah dipindahkan ke Trello.

## User Requirements Umum

- [ ] Aplikasi dapat diakses melalui browser.
- [ ] Aplikasi memiliki halaman landing atau welcome sebelum login.
- [ ] Sistem menggunakan autentikasi session Laravel.
- [ ] Semua data akun tersimpan di database MySQL.
- [ ] Aplikasi mendukung tampilan Dark Mode dan Light Mode.
- [ ] Dark Mode dapat aktif otomatis mengikuti jam lokal pengguna.
- [ ] Frontend menggunakan Blade, Tailwind CSS, Alpine.js, dan Vite.
- [ ] Backend menggunakan Laravel dan PHP.
- [ ] Sistem memiliki dashboard utama untuk monitoring target.
- [ ] Sistem memiliki halaman status publik yang bisa diakses tanpa login.
- [ ] Sistem dapat menjalankan pengecekan target secara otomatis melalui scheduler dan queue worker.
- [ ] Sistem dapat menampilkan update monitoring secara realtime melalui Laravel Reverb.

## Login Page

- [ ] Menampilkan identitas aplikasi Monitoring Dashboard.
- [ ] Terdapat input field Email.
- [ ] Terdapat input field Password dengan karakter tersembunyi.
- [ ] Terdapat checkbox Remember Me.
- [ ] Terdapat tombol Login untuk submit.
- [ ] Login berhasil masuk ke halaman Dashboard.
- [ ] Email tidak terdaftar menampilkan pesan error.
- [ ] Password salah menampilkan pesan error.
- [ ] Field kosong tidak bisa submit.
- [ ] Terdapat link untuk pindah ke halaman Register.
- [ ] Tampilan login mengikuti tema kuning dan hitam.
- [ ] Tampilan login responsif di desktop dan mobile.

## Register Page

- [ ] Terdapat input field Name.
- [ ] Terdapat input field Email.
- [ ] Terdapat input field Password.
- [ ] Terdapat input field Confirm Password.
- [ ] Terdapat tombol Register untuk submit.
- [ ] Register berhasil membuat akun baru.
- [ ] Email yang sudah digunakan menampilkan pesan error.
- [ ] Password dan Confirm Password harus sama.
- [ ] Field kosong tidak bisa submit.
- [ ] Terdapat link untuk pindah ke halaman Login.
- [ ] Tampilan register responsif di desktop dan mobile.

## Dashboard Monitoring

- [ ] Dashboard menampilkan judul Monitoring Dashboard.
- [ ] Dashboard menampilkan tombol menuju Halaman Status Publik.
- [ ] Dashboard menampilkan kartu ringkasan Total Target.
- [ ] Dashboard menampilkan kartu ringkasan Online.
- [ ] Dashboard menampilkan kartu ringkasan Down.
- [ ] Dashboard menampilkan kartu ringkasan Unstable.
- [ ] Dashboard menampilkan kartu ringkasan Dijeda atau Paused.
- [ ] Dashboard menampilkan daftar target yang dipantau.
- [ ] Setiap target menampilkan nama target.
- [ ] Setiap target menampilkan protocol, host, dan port.
- [ ] Setiap target menampilkan status terbaru.
- [ ] Setiap target menampilkan response time.
- [ ] Setiap target menampilkan uptime 7 hari.
- [ ] Setiap target HTTPS menampilkan sisa masa berlaku SSL certificate.
- [ ] Setiap target menampilkan waktu terakhir dicek.
- [ ] Dashboard dapat berubah tanpa refresh saat event realtime diterima.
- [ ] Tampilan dashboard cocok untuk sistem monitoring.
- [ ] Tampilan dashboard responsif di desktop dan mobile.

## Tambah Target

- [ ] Terdapat tombol Tambah Target pada dashboard.
- [ ] Form tambah target dapat dibuka dari dashboard.
- [ ] Terdapat input field Nama Target.
- [ ] Terdapat input field Host atau URL.
- [ ] Terdapat pilihan protocol HTTP, HTTPS, atau TCP.
- [ ] Terdapat input field Port.
- [ ] Terdapat input field Check Interval.
- [ ] Terdapat input field Timeout.
- [ ] Terdapat input field Alert Threshold.
- [ ] Terdapat input field Group atau kategori target.
- [ ] User dapat memasukkan URL lengkap dan sistem menormalisasi host, protocol, dan port.
- [ ] Field Nama Target wajib diisi.
- [ ] Field Host atau URL wajib diisi.
- [ ] Host atau URL tidak valid menampilkan pesan error.
- [ ] Port harus berupa angka valid.
- [ ] Check Interval harus berupa angka valid.
- [ ] Timeout harus berupa angka valid.
- [ ] Alert Threshold harus berupa angka valid.
- [ ] Target berhasil ditambahkan dan tampil di dashboard.
- [ ] Jika validasi gagal, form tetap terbuka dan input sebelumnya tidak hilang.

## Edit Target

- [ ] Terdapat tombol Edit pada setiap target.
- [ ] Halaman edit menampilkan data target yang dipilih.
- [ ] User dapat mengubah nama target.
- [ ] User dapat mengubah host atau URL target.
- [ ] User dapat mengubah protocol target.
- [ ] User dapat mengubah port target.
- [ ] User dapat mengubah check interval.
- [ ] User dapat mengubah timeout.
- [ ] User dapat mengubah alert threshold.
- [ ] User dapat mengubah group target.
- [ ] Data target berhasil diperbarui setelah submit.
- [ ] Input tidak valid menampilkan pesan error.
- [ ] User dapat kembali ke dashboard dari halaman edit.

## Hapus Target

- [ ] Terdapat tombol Hapus pada setiap target.
- [ ] Sistem menampilkan konfirmasi sebelum target dihapus.
- [ ] Target berhasil dihapus dari database.
- [ ] Target yang sudah dihapus tidak tampil lagi di dashboard.
- [ ] Status log milik target ikut ditangani sesuai relasi database.

## Pause dan Resume Target

- [ ] Terdapat tombol Pause pada target aktif.
- [ ] Target yang dipause tidak dicek otomatis.
- [ ] Status target yang dipause tampil sebagai Paused atau Dijeda.
- [ ] Terdapat tombol Resume pada target yang dipause.
- [ ] Target yang diresume kembali masuk proses monitoring.
- [ ] Kartu ringkasan Paused berubah sesuai jumlah target yang dijeda.

## Detail Target

- [ ] Terdapat tombol Detail pada setiap target.
- [ ] Halaman detail menampilkan informasi lengkap target.
- [ ] Halaman detail menampilkan status terbaru target.
- [ ] Halaman detail menampilkan response time terbaru.
- [ ] Halaman detail menampilkan uptime target.
- [ ] Halaman detail menampilkan riwayat status log.
- [ ] Halaman detail menampilkan informasi SSL jika target menggunakan HTTPS.
- [ ] User dapat kembali ke dashboard dari halaman detail.

## Proses Monitoring Otomatis

- [ ] Sistem memiliki command untuk mengecek target.
- [ ] Sistem dapat menjalankan pengecekan target melalui scheduler.
- [ ] Sistem dapat menjalankan job monitoring melalui queue worker.
- [ ] Sistem mengecek target sesuai check interval.
- [ ] Sistem menyimpan hasil pengecekan ke status log.
- [ ] Sistem mencatat response time target.
- [ ] Sistem menandai target Online jika pengecekan berhasil.
- [ ] Sistem menandai target Down jika pengecekan gagal.
- [ ] Sistem menandai target Unstable jika target melewati ambang alert.
- [ ] Sistem tidak langsung menganggap target Down hanya karena uptime atau SSL belum dihitung.
- [ ] Sistem dapat menghitung uptime setelah data status log tersedia.

## Realtime Update

- [ ] Sistem menggunakan Laravel Reverb untuk broadcast event.
- [ ] Dashboard menerima event saat target selesai dicek.
- [ ] Status target berubah tanpa refresh halaman.
- [ ] Response time target berubah tanpa refresh halaman.
- [ ] Uptime 7D berubah tanpa refresh halaman.
- [ ] Waktu terakhir cek berubah tanpa refresh halaman.
- [ ] Kartu ringkasan berubah tanpa refresh halaman.
- [ ] Jika realtime tidak aktif, data tetap bisa diperbarui dengan refresh halaman.

## Uptime Monitoring

- [ ] Sistem menghitung uptime 1 hari.
- [ ] Sistem menghitung uptime 7 hari.
- [ ] Sistem menghitung uptime 30 hari.
- [ ] Dashboard menampilkan uptime 7 hari.
- [ ] Uptime 7 hari dihitung dari data yang tersedia, tidak harus menunggu 7 hari penuh.
- [ ] Jika belum ada data status log, uptime menampilkan nilai kosong atau 0 sesuai kondisi.
- [ ] Command uptime:calculate dapat menghitung ulang uptime target.
- [ ] Hasil perhitungan uptime tersimpan di database.

## SSL Certificate Monitoring

- [ ] Sistem mengecek SSL certificate untuk target HTTPS.
- [ ] Sistem menyimpan tanggal expired SSL certificate.
- [ ] Sistem menampilkan sisa hari SSL certificate.
- [ ] Sistem tidak menampilkan SSL certificate untuk target HTTP atau TCP.
- [ ] SSL valid menampilkan informasi sisa hari.
- [ ] SSL hampir expired dapat dikategorikan perlu perhatian.
- [ ] SSL invalid atau gagal dicek menampilkan kondisi error yang jelas.

## Notification Settings

- [ ] Terdapat halaman pengaturan notifikasi.
- [ ] User dapat menambah channel notifikasi.
- [ ] User dapat memilih tipe channel Telegram.
- [ ] User dapat memilih tipe channel Discord.
- [ ] User dapat mengaktifkan atau menonaktifkan channel notifikasi.
- [ ] User dapat mengirim test notification.
- [ ] Test notification berhasil menampilkan pesan sukses.
- [ ] Test notification gagal menampilkan pesan error.
- [ ] Field kosong tidak bisa submit.
- [ ] Token atau webhook tidak valid menampilkan pesan error.
- [ ] Daftar channel notifikasi tampil di halaman settings.
- [ ] User dapat menghapus channel notifikasi.

## Telegram Bot Setup

- [ ] Form Telegram memiliki input Bot Token.
- [ ] Form Telegram memiliki input Chat ID.
- [ ] User dapat memasukkan token Telegram mentah.
- [ ] User dapat memasukkan URL getUpdates Telegram dan sistem menormalisasi token.
- [ ] Terdapat tombol untuk mengambil Chat ID dari Telegram.
- [ ] Sistem menampilkan daftar chat yang ditemukan dari getUpdates.
- [ ] Jika hanya ada satu chat, Chat ID dapat terisi otomatis.
- [ ] Jika tidak ada chat, sistem menampilkan instruksi agar user mengirim pesan ke bot terlebih dahulu.
- [ ] Token Telegram tidak valid menampilkan pesan error.
- [ ] Chat ID Telegram mendukung ID personal dan group.
- [ ] Test Telegram berhasil mengirim pesan ke chat tujuan.

## Discord Webhook Setup

- [ ] Form Discord memiliki input Webhook URL.
- [ ] Webhook URL wajib valid.
- [ ] Webhook URL kosong tidak bisa submit.
- [ ] Test Discord berhasil mengirim pesan ke channel tujuan.
- [ ] Webhook salah atau expired menampilkan pesan error.

## Reports

- [ ] Terdapat halaman Reports.
- [ ] Reports menampilkan ringkasan data monitoring.
- [ ] Reports menampilkan total target.
- [ ] Reports menampilkan status target.
- [ ] Reports menampilkan rata-rata uptime jika data tersedia.
- [ ] Reports menampilkan rata-rata response time jika data tersedia.
- [ ] Reports tidak error saat data monitoring masih kosong.
- [ ] Reports dapat digunakan untuk mengevaluasi performa target.

## Public Status Page

- [ ] Halaman status publik dapat diakses tanpa login.
- [ ] Halaman status publik menampilkan daftar target yang dipublikasikan.
- [ ] Halaman status publik menampilkan status setiap target.
- [ ] Halaman status publik menampilkan response time setiap target.
- [ ] Halaman status publik menampilkan uptime setiap target.
- [ ] Halaman status publik menampilkan pesan kosong jika belum ada target.
- [ ] Tampilan halaman status publik responsif di desktop dan mobile.
- [ ] Halaman status publik menggunakan desain yang konsisten dengan dashboard.

## Profile Page

- [ ] User dapat membuka halaman profile.
- [ ] User dapat mengubah nama akun.
- [ ] User dapat mengubah email akun.
- [ ] User dapat mengubah password akun.
- [ ] Password lama wajib benar sebelum password diganti.
- [ ] Password baru dan konfirmasi password harus sama.
- [ ] User dapat menghapus akun jika fitur tersedia.
- [ ] Validasi gagal menampilkan pesan error.
- [ ] Perubahan berhasil menampilkan pesan sukses.

## Navigation

- [ ] Navbar menampilkan nama atau identitas aplikasi.
- [ ] Navbar memiliki link Dashboard.
- [ ] Navbar memiliki link Reports.
- [ ] Navbar memiliki link Notification Settings.
- [ ] Navbar memiliki menu Profile.
- [ ] Navbar memiliki tombol Logout.
- [ ] Menu aktif terlihat jelas.
- [ ] Navigasi responsif pada ukuran mobile.

## Logout

- [ ] Terdapat tombol Logout.
- [ ] Logout berhasil mengakhiri session user.
- [ ] Setelah logout, user diarahkan ke halaman login atau welcome.
- [ ] User yang sudah logout tidak bisa membuka dashboard tanpa login.
- [ ] Session yang tidak valid otomatis diarahkan ke halaman login.

## UI dan Theme

- [ ] Desain menggunakan warna utama kuning dan hitam.
- [ ] Desain mengikuti gaya Material Design.
- [ ] Komponen button terlihat konsisten.
- [ ] Komponen card terlihat konsisten.
- [ ] Komponen form terlihat konsisten.
- [ ] Status Online memiliki indikator hijau.
- [ ] Status Down memiliki indikator merah.
- [ ] Status Unstable memiliki indikator kuning.
- [ ] Status Paused memiliki indikator yang mudah dibedakan.
- [ ] Teks mudah dibaca pada Light Mode.
- [ ] Teks mudah dibaca pada Dark Mode.
- [ ] Layout tidak saling tumpang tindih pada desktop.
- [ ] Layout tidak saling tumpang tindih pada mobile.

## Validasi dan Error Handling

- [ ] Setiap form menampilkan pesan error jika input tidak valid.
- [ ] Pesan error menggunakan bahasa yang mudah dipahami user.
- [ ] Data input user tidak hilang saat validasi gagal.
- [ ] Sistem menolak host atau URL yang tidak valid.
- [ ] Sistem menolak port di luar batas valid.
- [ ] Sistem menolak timeout yang tidak valid.
- [ ] Sistem menolak interval pengecekan yang tidak valid.
- [ ] Sistem menampilkan feedback sukses setelah aksi berhasil.
- [ ] Sistem tidak menampilkan stack trace kepada user.

## Database

- [ ] Sistem menggunakan database MySQL.
- [ ] Terdapat migration untuk tabel users.
- [ ] Terdapat migration untuk tabel targets.
- [ ] Terdapat migration untuk tabel status_logs.
- [ ] Terdapat migration untuk tabel incidents.
- [ ] Terdapat migration untuk tabel notification_channels.
- [ ] Terdapat migration untuk queue jobs.
- [ ] Terdapat seeder akun admin default.
- [ ] Migration dapat dijalankan dari awal tanpa error.
- [ ] Seeder dapat membuat akun admin untuk testing.

## Deployment Railway

- [ ] Project dapat dipush ke GitHub.
- [ ] Railway dapat connect ke repository GitHub.
- [ ] Railway memiliki service aplikasi Laravel.
- [ ] Railway memiliki database MySQL.
- [ ] Environment variable Laravel diisi di Railway.
- [ ] APP_KEY tersedia di production.
- [ ] APP_ENV diset production.
- [ ] APP_DEBUG diset false.
- [ ] APP_URL menggunakan domain Railway.
- [ ] Build command menginstall dependency PHP dan Node.
- [ ] Build command menjalankan build asset frontend.
- [ ] Start command menjalankan aplikasi Laravel.
- [ ] Migration dapat dijalankan di Railway.
- [ ] Queue worker dapat dijalankan sebagai service terpisah jika dibutuhkan.
- [ ] Scheduler dapat dijalankan sebagai service atau cron sesuai kebutuhan deployment.

## Tabel Ringkasan User Requirements

| ID | Modul | Requirement Utama | Prioritas | Status |
| --- | --- | --- | --- | --- |
| UR-001 | Umum | Aplikasi dapat diakses melalui browser | High | Todo |
| UR-002 | Auth | User dapat login menggunakan email dan password | High | Todo |
| UR-003 | Auth | User dapat register akun baru | Medium | Todo |
| UR-004 | Auth | User dapat logout dari aplikasi | High | Todo |
| UR-005 | Dashboard | User dapat melihat ringkasan status target | High | Todo |
| UR-006 | Dashboard | User dapat melihat daftar target monitoring | High | Todo |
| UR-007 | Target | User dapat menambah target monitoring | High | Todo |
| UR-008 | Target | User dapat mengedit target monitoring | High | Todo |
| UR-009 | Target | User dapat menghapus target monitoring | Medium | Todo |
| UR-010 | Target | User dapat pause dan resume target | Medium | Todo |
| UR-011 | Monitoring | Sistem dapat mengecek target otomatis | High | Todo |
| UR-012 | Monitoring | Sistem dapat menyimpan status log hasil pengecekan | High | Todo |
| UR-013 | Monitoring | Sistem dapat menghitung response time | High | Todo |
| UR-014 | Uptime | Sistem dapat menghitung uptime 1D, 7D, dan 30D | High | Todo |
| UR-015 | SSL | Sistem dapat mengecek SSL certificate target HTTPS | Medium | Todo |
| UR-016 | Realtime | Dashboard dapat update tanpa refresh | Medium | Todo |
| UR-017 | Notification | User dapat setup notifikasi Telegram | High | Todo |
| UR-018 | Notification | User dapat setup notifikasi Discord | Medium | Todo |
| UR-019 | Report | User dapat melihat laporan monitoring | Medium | Todo |
| UR-020 | Public Status | Publik dapat melihat status target tanpa login | Medium | Todo |
| UR-021 | Profile | User dapat mengubah data profile | Low | Todo |
| UR-022 | UI Theme | Aplikasi memiliki tema kuning hitam dan dark mode otomatis | Medium | Todo |
| UR-023 | Validation | Form menampilkan validasi input yang jelas | High | Todo |
| UR-024 | Database | Sistem menggunakan MySQL dan migration berjalan | High | Todo |
| UR-025 | Deployment | Project dapat dideploy ke Railway untuk demo tugas | Medium | Todo |
