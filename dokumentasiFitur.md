# Dokumentasi Fitur MoneyFlow

Dokumentasi ini menjelaskan semua fitur aplikasi MoneyFlow berdasarkan model dan hubungan data yang sudah dibuat.

## 1. Workspace
- Fitur: manajemen ruang kerja utama.
- Deskripsi: setiap workspace adalah konteks yang memisahkan data keuangan antara satu akun dan akun lain atau antar tujuan penggunaan.
- Field utama: `user_account_id`, `name`, `type`.
- Relasi:
  - `user_account`: workspace dimiliki oleh satu akun pengguna.
  - `wallets`: bisa memiliki banyak dompet.
  - `categories`: bisa memiliki banyak kategori transaksi.
  - `transactions`: bisa memiliki banyak transaksi.
  - `budgets`: bisa memiliki anggaran.
  - `debts`: bisa memiliki pencatatan hutang/piutang.
  - `goals`: bisa memiliki tujuan menabung.
  - `savingInvestasis`: bisa memiliki catatan investasi.

## 2. Wallet
- Fitur: dompet atau sumber dana untuk transaksi.
- Deskripsi: wallet menyimpan saldo dan menghubungkan transaksi, tabungan tujuan, serta investasi.
- Field utama: `workspace_id`, `name`, `balance`.
- Relasi:
  - `workspace`: wallet berada pada satu workspace.
  - `transactions`: dompet dapat memiliki banyak transaksi.
  - `goalSavings`: dapat digunakan untuk menyimpan dana tujuan.
  - `savingInvestasis`: dapat digunakan sebagai sumber modal investasi.

## 3. Transaction
- Fitur: pencatatan transaksi pemasukan dan pengeluaran.
- Deskripsi: transaksi mencatat nilai, tanggal, kategori, dompet, serta status pengulangan.
- Field utama: `workspace_id`, `wallet_id`, `category_id`, `amount`, `type`, `transaction_date`, `proof_path`, `is_recurring`.
- Relasi:
  - `workspace`: transaksi terkait dengan satu workspace.
  - `wallet`: transaksi terkait dengan satu dompet.
  - `category`: transaksi terkait dengan satu kategori.
  - `splitBills`: transaksi dapat dijadikan dasar split bill.

## 4. Category
- Fitur: klasifikasi transaksi.
- Deskripsi: kategori membantu mengelompokkan transaksi dan mengaitkannya dengan anggaran.
- Field utama: `workspace_id`, `name_category`, `type_category`.
- Relasi:
  - `workspace`: kategori berada pada satu workspace.
  - `transactions`: kategori dapat memiliki banyak transaksi.
  - `budgets`: kategori dapat memiliki anggaran terkait.

## 5. Budget
- Fitur: batas pengeluaran berdasarkan kategori.
- Deskripsi: budget membantu mengelola batas keuangan untuk periode tertentu.
- Field utama: `workspace_id`, `category_id`, `limit_amount`, `moonth_year`.
- Relasi:
  - `workspace`: anggaran berada pada satu workspace.
  - `category`: anggaran terkait dengan satu kategori.

## 6. Debt
- Fitur: pencatatan hutang dan piutang.
- Deskripsi: debt menyimpan informasi jumlah, tipe, siapa pihak terkait, dan status pelunasan.
- Field utama: `workspace_id`, `type`, `person_name`, `amount`, `status`.
- Relasi:
  - `workspace`: catatan hutang berada pada satu workspace.

## 7. Goal
- Fitur: tujuan menabung.
- Deskripsi: goal merepresentasikan target keuangan dengan jumlah yang ingin dicapai dan akumulasi saat ini.
- Field utama: `workspace_id`, `Deskripsi`, `target_amount`, `current_amount`.
- Relasi:
  - `workspace`: goal berada pada satu workspace.
  - `goalSavings`: goal dapat menerima banyak tabungan tujuan.

## 8. GoalSaving
- Fitur: pencatatan setoran ke goal.
- Deskripsi: GoalSaving mencatat kontribusi nyata ke tujuan menabung menggunakan wallet tertentu.
- Field utama: `goal_id`, `wallet_id`, `amount`, `date`, `notes`.
- Relasi:
  - `goal`: setoran terkait dengan satu goal.
  - `wallet`: setoran menggunakan satu wallet.

## 9. SavingInvestasi
- Fitur: pencatatan investasi.
- Deskripsi: SavingInvestasi menyimpan detail modal investasi, jenis instrumen, estimasi return, tanggal mulai, dan tanggal jatuh tempo.
- Field utama: `workspace_id`, `wallet_id`, `intrumen`, `nama_instrumen`, `nominal_modal`, `estimasi_return`, `tanggal_mulai`, `tanggal_jatuh_tempo`, `status`.
- Relasi:
  - `workspace`: investasi berada pada satu workspace.
  - `wallet`: investasi terkait dengan satu wallet.

## 10. SplitBill
- Fitur: pembagian tagihan bersama.
- Deskripsi: SplitBill mencatat jumlah tagihan yang dibagi dari satu transaksi dan status pembayarannya.
- Field utama: `transaction_id`, `amount`, `status`.
- Relasi:
  - `transaction`: split bill berasal dari satu transaksi.
  - `participants`: split bill memiliki daftar partisipan.

## 11. SplitBillsParticipant
- Fitur: detail partisipan split bill.
- Deskripsi: setiap partisipan mencatat nama, jumlah yang harus dibayar, dan status pembayaran.
- Field utama: `split_bill_id`, `friend_name`, `amount_due`, `is_paid`.
- Relasi:
  - `splitBill`: partisipan terkait dengan satu split bill.

## 12. User Account
- Fitur: autentikasi pengguna.
- Deskripsi: user_account menyimpan data login pengguna dan mengelola workspace yang dimiliki.
- Field utama: `name`, `email`, `password`.
- Relasi:
  - `workspaces`: satu akun dapat memiliki banyak workspace.

## 13. Subscription Transaction
- Fitur: pencatatan transaksi berlangganan/recurring.
- Deskripsi: subscription transaction mencatat transaksi yang terjadi secara berkala dan otomatis (harian, mingguan, bulanan, atau tahunan). Fitur ini membantu pengguna melacak pembayaran berulang seperti langganan streaming, tagihan utilitas, iuran fitness, dll.
- Field utama: `workspace_id`, `wallet_id`, `category_id`, `nama_transaksi`, `nominal`, `frekuensi`, `tanggal_mulai`, `tanggal_eksekusi_berikutnya`.
- Relasi:
  - `workspace`: transaksi berlangganan berada pada satu workspace.
  - `wallet`: transaksi berlangganan terkait dengan satu dompet/sumber dana.
  - `category`: transaksi berlangganan diklasifikasikan dalam satu kategori.
- Frekuensi yang didukung: `daily` (harian), `weekly` (mingguan), `monthly` (bulanan), `yearly` (tahunan).
- Kegunaan: memudahkan pengguna untuk mengatur transaksi otomatis, mencegah lupa pembayaran, dan memberikan visibilitas penuh terhadap pengeluaran rutin.

## 14. Financial Health Score
- Fitur: penilaian kesehatan keuangan workspace.
- Deskripsi: financial health score menganalisis dan memberikan skor kesehatan keuangan berdasarkan berbagai metrik keuangan pengguna. Skor ini membantu pengguna memahami kondisi keuangan mereka secara keseluruhan dan mengidentifikasi area yang perlu diperbaiki.
- Field utama: `workspace_id`, `score`, `rincian_metrik`.
- Relasi:
  - `workspace`: skor kesehatan berada pada satu workspace.
- Struktur data: `rincian_metrik` adalah array yang menyimpan detail metrik-metrik yang digunakan dalam perhitungan skor (contoh: rasio tabungan, rasio hutang, keseimbangan arus kas, dll).
- Kegunaan: memberikan insights mengenai kesehatan finansial, membantu pengguna membuat keputusan keuangan yang lebih baik, dan melacak perkembangan kesehatan keuangan dari waktu ke waktu.

## 15. Saved Report
- Fitur: penyimpanan laporan/visualisasi custom.
- Deskripsi: saved report memungkinkan pengguna menyimpan laporan atau visualisasi keuangan yang telah dikustomisasi sesuai kebutuhan mereka. Pengguna dapat membuat berbagai jenis grafik dan laporan dengan filter data tertentu untuk analisis mendalam.
- Field utama: `workspace_id`, `nama_laporan`, `tipe_grafik`, `filter_data`.
- Relasi:
  - `workspace`: laporan tersimpan berada pada satu workspace.
- Tipe grafik yang didukung: berbagai jenis visualisasi data (contoh: bar chart, pie chart, line chart, dsb).
- Filter data: array yang menyimpan parameter filter yang digunakan dalam laporan (contoh: rentang tanggal, kategori, tipe transaksi, dll).
- Kegunaan: memudahkan pengguna untuk membuat dan menyimpan laporan khusus yang sering digunakan, mempercepat akses ke informasi analitik, dan membantu dalam pelaporan dan auditing keuangan.

## Kesimpulan
Aplikasi MoneyFlow memiliki fitur utama:
- manajemen workspace
- manajemen wallet
- pencatatan transaksi
- pencatatan transaksi berlangganan/recurring (BARU)
- kategori dan anggaran
- pencatatan hutang/piutang
- tujuan menabung dan penyetoran goal
- pencatatan investasi
- pembagian tagihan bersama
- penilaian kesehatan keuangan (Financial Health Score) (BARU)
- penyimpanan laporan/visualisasi custom (Saved Report) (BARU)
- autentikasi akun pengguna

Semua fitur di atas saling terhubung melalui relasi model Eloquent untuk menjaga konsistensi data dan mendukung alur kerja keuangan lengkap.