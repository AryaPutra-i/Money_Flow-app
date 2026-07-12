# ERD - Struktur Database MoneyFlow-app

Dokumen ini merinci struktur tabel, kolom, tipe, primary key, foreign key, dan relasi utama aplikasi.

## Tabel `users`
- Primary key: `id` (big integer, auto-increment)
- `name` : string
- `email` : string, unique
- `email_verified_at` : timestamp, nullable
- `password` : string
- `remember_token` : string
- timestamps: `created_at`, `updated_at`

## Tabel `user_accounts`
- Primary key: `id`
- `name`: string (non nullable)
- `email`: string, unique (non nullable)
- `password`: string (non nullable)
- `remember_token`
- timestamps

## Tabel `workspaces`
- Primary key: `id`
- `user_account_id`: foreign key -> `user_accounts.id` (on delete cascade)
- `name`: string, nullable
- `type`: enum('personal','organization') default 'personal'
- timestamps

Relasi: `user_accounts` 1 —* `workspaces`

## Tabel `wallets`
- Primary key: `id`
- `workspace_id`: foreign key -> `workspaces.id` (on delete cascade)
- `name`: string, nullable
- `balance`: decimal(15,2) default 0
- timestamps

Relasi: `workspaces` 1 —* `wallets`

## Tabel `categories`
- Primary key: `id`
- `workspace_id`: foreign key -> `workspaces.id` (on delete cascade)
- `name_category`: string, nullable
- `type_category`: enum('income','expense') default 'expense'
- timestamps

Relasi: `workspaces` 1 —* `categories`

## Tabel `transactions`
- Primary key: `id`
- `workspace_id`: foreign key -> `workspaces.id` (on delete cascade)
- `wallet_id`: foreign key -> `wallets.id` (on delete cascade)
- `category_id`: foreign key -> `categories.id` (on delete cascade)
- `amount`: decimal(15,2)
- `type`: enum('income','expense','transfer') default 'expense'
- `transaction_date`: date
- `proof_path`: string, nullable
- `is_recurring`: boolean default false
- timestamps

Relasi:
- `workspaces` 1 —* `transactions`
- `wallets` 1 —* `transactions`
- `categories` 1 —* `transactions`

## Tabel `split_bills`
- Primary key: `id`
- `transaction_id`: foreign key -> `transactions.id` (on delete cascade)
- `amount`: decimal(15,2)
- `status`: enum('pending','completed'), nullable
- timestamps

Relasi: `transactions` 1 —1 `split_bills` (1 transaksi bisa jadi split bill)

## Tabel `split_bills_participants`
- Primary key: `id`
- `split_bill_id`: foreign key -> `split_bills.id` (on delete cascade)
- `friend_name`: string, nullable
- `amount_due`: decimal(15,2)
- `is_paid`: boolean default false
- timestamps

Relasi: `split_bills` 1 —* `split_bills_participants`

## Tabel `budgets`
- Primary key: `id`
- `workspace_id`: foreign key -> `workspaces.id` (on delete cascade)
- `category_id`: foreign key -> `categories.id` (on delete cascade)
- `limit_amount`: decimal(15,2)
- `moonth_year`: date
- timestamps

Relasi: `workspaces` 1 —* `budgets`, `categories` 1 —* `budgets`

## Tabel `debts`
- Primary key: `id`
- `workspace_id`: foreign key -> `workspaces.id` (on delete cascade)
- `type`: enum('debt','receivable') default 'debt'
- `person_name`: string (non nullable)
- `amount`: decimal(15,2)
- `status`: enum('unpaid','paid') default 'unpaid'
- timestamps

Relasi: `workspaces` 1 —* `debts`

## Tabel `goals`
- Primary key: `id`
- `workspace_id`: foreign key -> `workspaces.id` (on delete cascade)
- `Deskripsi`: string, nullable
- `target_amount`: decimal(15,2)
- `current_amount`: decimal(15,2) default 0
- timestamps

Relasi: `workspaces` 1 —* `goals`

## Tabel `goal_savings`
- Primary key: `id`
- `goal_id`: foreign key -> `goals.id` (on delete cascade)
- `wallet_id`: foreign key -> `wallets.id` (on delete cascade)
- `amount`: decimal(15,2)
- `date`: date
- `notes`: text, nullable
- timestamps

Relasi: `goals` 1 —* `goal_savings`, `wallets` 1 —* `goal_savings`

## Tabel `saving_investasis`
- Primary key: `id`
- `workspace_id`: foreign key -> `workspaces.id` (on delete cascade)
- `wallet_id`: foreign key -> `wallets.id` (on delete cascade)
- `intrumen`: enum('saham','obligasi','reksa dana','emas','properti','lainnya') default 'saham'
- `nama_instrumen`: string
- `nominal_modal`: decimal(15,2)
- `estimasi_return`: decimal(5,2), nullable
- `tanggal_mulai`: date
- `tanggal_jatuh_tempo`: date, nullable
- `status`: enum('aktif','selesai','jual') default 'aktif'
- timestamps

Relasi: `workspaces` 1 —* `saving_investasis`, `wallets` 1 —* `saving_investasis`

## Tabel `subscription_transactions`
- Primary key: `id`
- `workspace_id`: foreign key -> `workspaces.id` (on delete cascade)
- `wallet_id`: foreign key -> `wallets.id` (on delete cascade)
- `category_id`: foreign key -> `categories.id` (on delete cascade)
- `nama_transaksi`: string
- `nominal`: decimal(15,2)
- `frekuensi`: enum('daily','weekly','monthly','yearly') default 'monthly'
- `tanggal_mulai`: date (default now)
- `tanggal_eksekusi_berikutnya`: date
- timestamps

Relasi: `workspaces`/`wallets`/`categories` ke `subscription_transactions` (1 —*)

## Tabel `financial_health_scores`
- Primary key: `id`
- `workspace_id`: foreign key -> `workspaces.id` (on delete cascade)
- `score`: integer
- `rincian_metrik`: json, nullable
- `created_at`: timestamp (useCurrent)

Relasi: `workspaces` 1 —* `financial_health_scores`

## Tabel `saved_reports`
- Primary key: `id`
- `workspace_id`: foreign key -> `workspaces.id` (on delete cascade)
- `nama_laporan`: string
- `tipe_grafik`: string
- `filter_data`: json
- timestamps

Relasi: `workspaces` 1 —* `saved_reports`

## Tabel sistem tambahan
- `password_reset_tokens` (email primary, token, created_at)
- `sessions` (id primary string, user_id nullable FK to `users`, payload, last_activity)
- Laravel default tables `jobs`, `failed_jobs`, `cache`, dsb. dideklarasikan di migrasi awal.

---
Catatan: Struktur diambil dari file migrasi pada folder `database/migrations`. Untuk melihat migrasi sumber, lihat file migrasi terkait di folder `database/migrations`.
