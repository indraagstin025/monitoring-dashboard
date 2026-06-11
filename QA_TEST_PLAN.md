# QA Test Plan - Monitoring Dashboard

Dokumen ini dipakai untuk menguji aplikasi Monitoring Dashboard dari alur autentikasi sampai fitur monitoring, notifikasi, laporan, realtime update, dan status publik.

## Informasi Umum

| Item | Nilai |
| --- | --- |
| Project | Monitoring Dashboard |
| Framework | Laravel, Blade, Tailwind, Alpine, Vite |
| Database | MySQL Laragon |
| Queue | Database queue |
| Realtime | Laravel Reverb |
| Default akun seed | admin@monitoring.test / password |
| Tujuan QA | Memastikan fitur utama berjalan, validasi form jelas, dan monitoring realtime bekerja |

## Command Runtime Untuk QA

Jalankan command ini di terminal terpisah saat melakukan QA full flow.

| No | Command | Tujuan | Wajib |
| --- | --- | --- | --- |
| 1 | `php artisan serve` | Web server Laravel | Ya |
| 2 | `npm run dev` | Vite frontend dev server | Ya |
| 3 | `php artisan reverb:start --host=127.0.0.1 --port=8080` | Realtime dashboard | Ya, untuk realtime |
| 4 | `php artisan schedule:work` | Menjalankan scheduler monitoring | Ya, untuk auto monitoring |
| 5 | `php artisan queue:work --queue=monitoring,notifications,default --tries=3 --timeout=60` | Memproses ping target dan notifikasi | Ya, untuk monitoring/notifikasi |
| 6 | `php artisan pail` | Melihat log realtime | Opsional |

## Persiapan Data

| Checklist | Item | Catatan |
| --- | --- | --- |
| [ ] | `.env` mengarah ke MySQL Laragon | `DB_CONNECTION=mysql`, `DB_PORT=3306` |
| [ ] | Database `monitoring_db` tersedia | Cek di Laragon/HeidiSQL/phpMyAdmin |
| [ ] | Migration sudah jalan | `php artisan migrate:status` semua `Ran` |
| [ ] | Seeder akun sudah jalan | `php artisan db:seed` |
| [ ] | Akun admin bisa login | `admin@monitoring.test` / `password` |
| [ ] | Tidak ada config cache lama | `php artisan config:clear` bila `.env` berubah |
| [ ] | Vite asset tampil | Tidak ada error CSS/JS di browser console |

## Smoke Test

| ID | Area | Skenario | Langkah | Expected Result | Status |
| --- | --- | --- | --- | --- | --- |
| SMK-01 | App | Halaman awal terbuka | Buka `/` | Landing page tampil tanpa error | [ ] |
| SMK-02 | Auth | Login admin | Login dengan akun seed | Masuk ke dashboard | [ ] |
| SMK-03 | Dashboard | Dashboard tampil | Buka `/dashboard` | Kartu summary dan daftar target tampil | [ ] |
| SMK-04 | Asset | Frontend style aktif | Cek warna kuning/hitam dan layout | UI tampil sesuai desain | [ ] |
| SMK-05 | Console | Tidak ada error fatal | Buka log Laravel | Tidak ada error fatal baru | [ ] |

## Auth

| ID | Area | Skenario | Langkah | Expected Result | Status |
| --- | --- | --- | --- | --- | --- |
| AUTH-01 | Login | Login valid | Isi email dan password benar | Redirect ke dashboard | [ ] |
| AUTH-02 | Login | Login email salah | Isi email tidak terdaftar | Error validasi/login tampil | [ ] |
| AUTH-03 | Login | Password salah | Isi password salah | Error login tampil | [ ] |
| AUTH-04 | Login | Required fields | Kosongkan email/password | Browser/backend menolak submit | [ ] |
| AUTH-05 | Logout | Logout user | Klik menu user lalu logout | Redirect keluar, session hilang | [ ] |
| AUTH-06 | Protected route | Akses dashboard tanpa login | Buka `/dashboard` setelah logout | Redirect ke login | [ ] |
| AUTH-07 | Register | Register valid | Buat akun baru bila route aktif | Akun dibuat dan bisa login | [ ] |
| AUTH-08 | Register | Email duplikat | Pakai email yang sudah ada | Error validasi tampil | [ ] |

## Dashboard Summary

| ID | Area | Skenario | Langkah | Expected Result | Status |
| --- | --- | --- | --- | --- | --- |
| DASH-01 | Summary | Total target | Tambah 1 target | Kartu Total bertambah | [ ] |
| DASH-02 | Summary | Online count | Target berhasil ping `up` | Kartu Online bertambah | [ ] |
| DASH-03 | Summary | Down count | Target gagal ping sampai threshold | Kartu Down bertambah | [ ] |
| DASH-04 | Summary | Unstable count | Target gagal sebelum threshold | Kartu Unstable bertambah | [ ] |
| DASH-05 | Summary | Paused count | Pause target | Kartu Dijeda bertambah | [ ] |
| DASH-06 | Realtime | Summary update tanpa refresh | Reverb, scheduler, queue aktif | Angka summary berubah tanpa reload | [ ] |

## Target - Tambah Target

| ID | Area | Skenario | Langkah | Expected Result | Status |
| --- | --- | --- | --- | --- | --- |
| TGT-01 | Add Target | Tambah HTTPS domain | Isi nama, protocol HTTPS, host `example.com` | Target tersimpan | [ ] |
| TGT-02 | Add Target | Paste full URL | Isi host `https://example.com:443/path` | Sistem simpan host `example.com`, protocol HTTPS, port 443 | [ ] |
| TGT-03 | Add Target | HTTP target | Protocol HTTP, host valid | Target tersimpan | [ ] |
| TGT-04 | Add Target | TCP target | Protocol TCP, host valid, port wajib | Target tersimpan dan bisa diping via socket | [ ] |
| TGT-05 | Add Target | Nama kosong | Kosongkan nama | Error `Nama target wajib diisi` tampil | [ ] |
| TGT-06 | Add Target | Host kosong | Kosongkan host | Error host tampil | [ ] |
| TGT-07 | Add Target | Host tidak valid | Isi host dengan spasi atau format kacau | Error host tidak valid tampil | [ ] |
| TGT-08 | Add Target | Port bukan angka | Isi port `abc` | Error port tampil | [ ] |
| TGT-09 | Add Target | Port di luar batas | Isi port `70000` | Error port maksimal tampil | [ ] |
| TGT-10 | Add Target | Interval terlalu kecil | Isi interval `5` | Error minimal 10 detik tampil | [ ] |
| TGT-11 | Add Target | Timeout terlalu besar | Isi timeout `60` | Error timeout maksimal 30 detik tampil | [ ] |
| TGT-12 | Add Target | Alert threshold invalid | Isi threshold `0` atau `11` | Error threshold tampil | [ ] |
| TGT-13 | Add Target | Form gagal tetap terbuka | Submit invalid | Form tambah target tetap terbuka, input lama tidak hilang | [ ] |
| TGT-14 | Add Target | Keyword Check tidak ada | Buka form tambah target | Field Keyword Check tidak tampil | [ ] |

## Target - Daftar Target

| ID | Area | Skenario | Langkah | Expected Result | Status |
| --- | --- | --- | --- | --- | --- |
| LIST-01 | Layout | Baris target rapi | Tambah host panjang | Nama dan host tidak merusak layout | [ ] |
| LIST-02 | Layout | Host panjang truncate | Tambah domain panjang | Host tampil sebagai chip dan dipotong rapi | [ ] |
| LIST-03 | Status | Indicator up | Target status up | Dot hijau tampil | [ ] |
| LIST-04 | Status | Indicator down | Target status down | Dot merah tampil | [ ] |
| LIST-05 | Status | Indicator unstable | Target status unstable | Dot kuning tampil | [ ] |
| LIST-06 | Data | Response time | Ping berhasil | Response time tampil dalam ms | [ ] |
| LIST-07 | Data | Uptime 7D | Ada status logs | Uptime tampil persen | [ ] |
| LIST-08 | Data | SSL cert | Target HTTPS | Sisa hari SSL tampil jika cert terbaca | [ ] |
| LIST-09 | Data | Last checked | Target sudah dicek | Waktu terakhir cek tampil | [ ] |
| LIST-10 | Actions | Tombol detail | Klik ikon grafik/detail | Masuk ke halaman detail target | [ ] |
| LIST-11 | Actions | Tombol edit | Klik ikon edit | Masuk ke form edit target | [ ] |
| LIST-12 | Actions | Pause/resume | Klik pause lalu resume | Status pause berubah dan summary update | [ ] |
| LIST-13 | Actions | Delete target | Klik delete dan confirm | Target terhapus dari daftar | [ ] |

## Target - Edit Target

| ID | Area | Skenario | Langkah | Expected Result | Status |
| --- | --- | --- | --- | --- | --- |
| EDIT-01 | Edit | Edit nama target | Ubah nama lalu simpan | Nama berubah | [ ] |
| EDIT-02 | Edit | Edit host | Ubah host valid | Host berubah | [ ] |
| EDIT-03 | Edit | Edit full URL | Isi host full URL | Data dinormalisasi seperti tambah target | [ ] |
| EDIT-04 | Edit | Edit invalid port | Isi port salah | Error validasi tampil | [ ] |
| EDIT-05 | Edit | Toggle recovery notification | Ubah checkbox recovery | Nilai tersimpan | [ ] |
| EDIT-06 | Edit | Keyword Check tidak ada | Buka form edit | Field Keyword Check tidak tampil | [ ] |

## Monitoring Job

| ID | Area | Skenario | Langkah | Expected Result | Status |
| --- | --- | --- | --- | --- | --- |
| MON-01 | Scheduler | Scheduler aktif | Jalankan `php artisan schedule:work` | `monitor:run` berjalan sesuai jadwal | [ ] |
| MON-02 | Queue | Queue aktif | Jalankan `queue:work` | Job `monitoring` diproses | [ ] |
| MON-03 | Ping HTTP | Target HTTP/HTTPS up | Target valid merespons 2xx | Status menjadi `up` | [ ] |
| MON-04 | Ping HTTP | Target HTTP/HTTPS gagal | Host invalid atau timeout | Status menjadi `unstable`, lalu `down` setelah threshold | [ ] |
| MON-05 | Ping TCP | TCP port terbuka | Protocol TCP dan port valid | Status menjadi `up` | [ ] |
| MON-06 | Ping TCP | TCP port tertutup | Protocol TCP dan port tertutup | Status gagal sesuai threshold | [ ] |
| MON-07 | Logs | Status log tercatat | Ping target | Row baru masuk `status_logs` | [ ] |
| MON-08 | Incident | Incident terbuka | Target berubah dari up ke unstable/down | Incident baru dibuat | [ ] |
| MON-09 | Incident | Incident tertutup | Target kembali up | Incident ongoing ditutup | [ ] |
| MON-10 | Uptime | Uptime auto update | Ping target selesai | `uptime_1d/7d/30d` dihitung ulang | [ ] |
| MON-11 | SSL | SSL auto update | Target HTTPS valid | `ssl_expires_at` tersimpan jika cert terbaca | [ ] |
| MON-12 | Keyword | Keyword tidak mempengaruhi status | Target lama punya data keyword lama | Status tidak lagi diproses dari keyword | [ ] |

## Realtime

| ID | Area | Skenario | Langkah | Expected Result | Status |
| --- | --- | --- | --- | --- | --- |
| RT-01 | Reverb | Reverb aktif | Jalankan `reverb:start` | Browser websocket connect | [ ] |
| RT-02 | Dashboard | Status realtime | Ping target berubah status | Dot status berubah tanpa refresh | [ ] |
| RT-03 | Dashboard | Response realtime | Ping target selesai | Response time berubah tanpa refresh | [ ] |
| RT-04 | Dashboard | Last checked realtime | Ping target selesai | Terakhir cek berubah tanpa refresh | [ ] |
| RT-05 | Dashboard | Uptime realtime | Uptime dihitung | Uptime 7D berubah tanpa refresh | [ ] |
| RT-06 | Dashboard | Summary realtime | Status target berubah | Kartu summary berubah tanpa refresh | [ ] |
| RT-07 | Fallback | Reverb mati | Stop Reverb lalu ping target | Data berubah setelah refresh manual, tidak crash | [ ] |

## Uptime

| ID | Area | Skenario | Langkah | Expected Result | Status |
| --- | --- | --- | --- | --- | --- |
| UPT-01 | Calculation | Belum ada log | Target baru tanpa log | Uptime default 100 atau `Menghitung` sesuai view | [ ] |
| UPT-02 | Calculation | Semua log up | Buat beberapa log up | Uptime 100% | [ ] |
| UPT-03 | Calculation | Campuran up/down | Ada log up dan down | Persen sesuai rasio log | [ ] |
| UPT-04 | Command | Manual calculate | Jalankan `php artisan uptime:calculate` | Kolom uptime terupdate | [ ] |
| UPT-05 | Realtime | Manual calculate broadcast | Reverb aktif lalu calculate | Dashboard update tanpa refresh | [ ] |

## SSL

| ID | Area | Skenario | Langkah | Expected Result | Status |
| --- | --- | --- | --- | --- | --- |
| SSL-01 | HTTPS | Cert valid | Target HTTPS valid | Sisa hari SSL tampil | [ ] |
| SSL-02 | HTTP | Non HTTPS | Target HTTP | SSL tampil `-` atau N/A | [ ] |
| SSL-03 | Expiry alert | SSL <= 14 hari | Simulasikan target mendekati expired | Notifikasi SSL dibuat sesuai logic | [ ] |
| SSL-04 | Command | Manual SSL check | Jalankan `php artisan ssl:check` | Command selesai tanpa error fatal | [ ] |
| SSL-05 | Failure | SSL gagal dibaca | Host HTTPS bermasalah | Status ping tidak otomatis down hanya karena SSL kosong | [ ] |

## Notification Channel - Telegram

| ID | Area | Skenario | Langkah | Expected Result | Status |
| --- | --- | --- | --- | --- | --- |
| TEL-01 | Setup | Token valid | Isi token valid | Token diterima | [ ] |
| TEL-02 | Setup | Paste full getUpdates URL | Paste URL `https://api.telegram.org/bot.../getUpdates` | Token dinormalisasi dan bisa disimpan | [ ] |
| TEL-03 | Setup | Ambil Chat ID | Token valid, bot sudah menerima `/start`, klik Ambil | Chat ID otomatis terisi | [ ] |
| TEL-04 | Setup | Banyak chat | Bot punya beberapa update chat | Dropdown pilihan chat muncul | [ ] |
| TEL-05 | Setup | Bot belum di-start | Klik Ambil sebelum `/start` | Pesan minta kirim `/start` tampil | [ ] |
| TEL-06 | Validation | Token kosong | Submit Telegram tanpa token | Error token wajib tampil | [ ] |
| TEL-07 | Validation | Token salah | Isi token invalid | Error token tidak valid tampil | [ ] |
| TEL-08 | Validation | Chat ID kosong | Submit tanpa chat ID | Error Chat ID wajib tampil | [ ] |
| TEL-09 | Validation | Chat ID bukan angka | Isi `abc` | Error Chat ID harus angka tampil | [ ] |
| TEL-10 | Test | Test sukses | Channel valid, klik Test | Pesan masuk ke Telegram dan status test berhasil | [ ] |
| TEL-11 | Test | Test gagal | Chat ID salah | Flash error gagal kirim tampil | [ ] |
| TEL-12 | Toggle | Nonaktifkan channel | Klik Nonaktifkan | Channel tidak dipakai notifikasi | [ ] |
| TEL-13 | Toggle | Aktifkan channel | Klik Aktifkan | Channel aktif kembali | [ ] |
| TEL-14 | Delete | Hapus channel | Klik Hapus dan confirm | Channel hilang dari daftar | [ ] |

## Notification Channel - Discord

| ID | Area | Skenario | Langkah | Expected Result | Status |
| --- | --- | --- | --- | --- | --- |
| DIS-01 | Setup | Webhook valid | Isi Discord webhook valid | Channel tersimpan | [ ] |
| DIS-02 | Validation | Webhook kosong | Submit Discord tanpa webhook | Error wajib tampil | [ ] |
| DIS-03 | Validation | Webhook bukan URL | Isi `abc` | Error URL valid tampil | [ ] |
| DIS-04 | Test | Test sukses | Klik Test channel Discord valid | Pesan masuk ke Discord | [ ] |
| DIS-05 | Test | Test gagal | Webhook invalid/revoked | Error gagal kirim tampil | [ ] |

## Reports

| ID | Area | Skenario | Langkah | Expected Result | Status |
| --- | --- | --- | --- | --- | --- |
| REP-01 | Index | Buka laporan | Akses `/reports` | Summary target tampil | [ ] |
| REP-02 | Summary | Avg uptime | Ada data uptime | Avg uptime tampil benar | [ ] |
| REP-03 | Summary | Avg response | Ada status logs | Avg response tampil benar | [ ] |
| REP-04 | Table | Detail target | Lihat tabel laporan | Status, uptime, response, incident tampil | [ ] |
| REP-05 | Export | Export CSV | Klik Export CSV | File CSV terdownload | [ ] |
| REP-06 | CSV | Isi CSV | Buka file CSV | Data target sesuai database | [ ] |

## Status Public Page

| ID | Area | Skenario | Langkah | Expected Result | Status |
| --- | --- | --- | --- | --- | --- |
| PUB-01 | Public | Buka status page | Klik Halaman Status Publik | Halaman publik terbuka tanpa login | [ ] |
| PUB-02 | Slug | Slug valid | Buka `/status/{slug}` user | Status page tampil | [ ] |
| PUB-03 | Slug | Slug invalid | Buka slug tidak ada | 404 tampil | [ ] |
| PUB-04 | Stats | Semua up | Semua target up | Pesan semua sistem normal | [ ] |
| PUB-05 | Stats | Ada down | Salah satu target down | Pesan gangguan tampil | [ ] |
| PUB-06 | Empty | Tidak ada target | User tanpa target | Pesan belum ada layanan dipantau | [ ] |
| PUB-07 | Group | Target punya grup | Isi group_name | Target dikelompokkan sesuai grup | [ ] |

## Profile

| ID | Area | Skenario | Langkah | Expected Result | Status |
| --- | --- | --- | --- | --- | --- |
| PRF-01 | Profile | Update nama | Ubah nama profile | Nama berubah | [ ] |
| PRF-02 | Profile | Email invalid | Isi email tidak valid | Error validasi tampil | [ ] |
| PRF-03 | Password | Update password valid | Isi current dan new password benar | Password berubah | [ ] |
| PRF-04 | Password | Current password salah | Isi current salah | Error tampil | [ ] |
| PRF-05 | Delete | Delete account guarded | Klik delete tanpa password | Error password tampil | [ ] |

## UI, Responsive, Dark Mode

| ID | Area | Skenario | Langkah | Expected Result | Status |
| --- | --- | --- | --- | --- | --- |
| UI-01 | Theme | Warna utama | Buka dashboard | Tema kuning/hitam konsisten | [ ] |
| UI-02 | Dark mode | Malam hari | Jam browser 18:00-05:59 | Dark mode aktif otomatis | [ ] |
| UI-03 | Light mode | Siang hari | Jam browser 06:00-17:59 | Light mode aktif otomatis | [ ] |
| UI-04 | Mobile | Dashboard mobile | Buka viewport mobile | Layout tidak overlap | [ ] |
| UI-05 | Mobile | Navigation mobile | Klik hamburger | Menu tampil dan bisa dipakai | [ ] |
| UI-06 | Form | Error state | Submit form invalid | Error mudah dibaca | [ ] |
| UI-07 | Text | Host panjang | Target dengan host panjang | Teks truncate dan layout tetap rapi | [ ] |

## Security And Access Control

| ID | Area | Skenario | Langkah | Expected Result | Status |
| --- | --- | --- | --- | --- | --- |
| SEC-01 | Auth | Route auth protected | Akses `/dashboard` tanpa login | Redirect login | [ ] |
| SEC-02 | Ownership | Target user lain | Coba akses ID target milik user lain | 404 atau forbidden | [ ] |
| SEC-03 | Ownership | Channel user lain | Coba test/toggle/delete channel user lain | 404 atau forbidden | [ ] |
| SEC-04 | CSRF | Submit tanpa CSRF | Coba request POST tanpa token | Ditolak | [ ] |
| SEC-05 | Secret | Token bot tidak tampil di list | Lihat daftar channel | Token tidak ditampilkan mentah | [ ] |
| SEC-06 | Public | Status page tanpa data sensitif | Buka public status | Tidak ada token/config sensitif | [ ] |

## Negative And Edge Cases

| ID | Area | Skenario | Langkah | Expected Result | Status |
| --- | --- | --- | --- | --- | --- |
| NEG-01 | Network | Host tidak resolve | Tambah domain asal | Status menjadi unstable/down, log warning | [ ] |
| NEG-02 | Network | Timeout | Target lambat lebih dari timeout | Status gagal sesuai threshold | [ ] |
| NEG-03 | Queue | Queue worker mati | Stop queue worker | Job menumpuk di tabel `jobs`, UI tidak crash | [ ] |
| NEG-04 | Scheduler | Scheduler mati | Stop schedule worker | Monitoring otomatis berhenti, manual command masih bisa | [ ] |
| NEG-05 | Reverb | Reverb mati | Stop Reverb | Realtime berhenti, refresh manual tetap menampilkan data terbaru | [ ] |
| NEG-06 | Telegram | Token revoked | Test channel token lama | Error test tampil | [ ] |
| NEG-07 | Telegram | Chat ID group negatif | Pakai Chat ID `-100...` | Validasi menerima angka negatif | [ ] |
| NEG-08 | Delete | Hapus target dengan logs | Delete target | Logs ikut terhapus via cascade | [ ] |

## Regression Checklist Setelah Perubahan

| Checklist | Item |
| --- | --- |
| [ ] | Login masih bisa |
| [ ] | Dashboard terbuka |
| [ ] | Tambah target valid berhasil |
| [ ] | Form tambah target invalid menampilkan error |
| [ ] | Target bisa diping oleh queue |
| [ ] | Realtime dashboard masih berjalan |
| [ ] | Edit target berhasil |
| [ ] | Pause/resume target berhasil |
| [ ] | Delete target berhasil |
| [ ] | Uptime 7D update |
| [ ] | SSL Cert tampil untuk HTTPS |
| [ ] | Telegram channel bisa lookup Chat ID |
| [ ] | Telegram test berhasil |
| [ ] | Discord validation tetap aman |
| [ ] | Reports terbuka |
| [ ] | Export CSV berhasil |
| [ ] | Public status page terbuka |
| [ ] | Mobile layout tidak rusak |

## Bug Report Template

Gunakan format ini saat menemukan masalah.

| Field | Isi |
| --- | --- |
| Bug ID |  |
| Tanggal |  |
| Tester |  |
| Environment | Local / Staging / Production |
| Browser |  |
| Akun |  |
| Area |  |
| Severity | Low / Medium / High / Critical |
| Steps To Reproduce |  |
| Expected Result |  |
| Actual Result |  |
| Screenshot/Log |  |
| Catatan |  |

## Sign Off

| Role | Nama | Status | Tanggal | Catatan |
| --- | --- | --- | --- | --- |
| QA |  | Pending |  |  |
| Developer |  | Pending |  |  |
| Owner |  | Pending |  |  |
