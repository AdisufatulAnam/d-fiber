# Panduan CI/CD: GitHub Actions → Server Home (Tailscale + GHCR)

Panduan lengkap menghubungkan repository ini dengan server home Anda supaya
**push ke `main` = otomatis ter-deploy**.

## Daftar Isi

1. [Arsitektur & Alur](#1-arsitektur--alur)
2. [Persiapan di GitHub](#2-persiapan-di-github)
3. [Persiapan di Server Home](#3-persiapan-di-server-home)
4. [Menjalankan Deploy Pertama](#4-menjalankan-deploy-pertama)
5. [Operasi Harian](#5-operasi-harian)
6. [Troubleshooting](#6-troubleshooting)

---

## 1. Arsitektur & Alur

```
Anda push ke main
      │
      ▼
┌─────────────────── GitHub Actions ───────────────────┐
│ Job 1: Test    → pint, phpstan, vue-tsc, pest        │
│ Job 2: Build   → docker build → push ke ghcr.io      │
│ Job 3: Deploy  → join Tailscale → SSH ke server home │
└──────────────────────────────────────────────────────┘
      │  (lewat jaringan Tailscale - TIDAK butuh IP publik)
      ▼
┌────────────────── Server Home ───────────────────────┐
│ docker compose pull  → tarik image dari GHCR         │
│ docker compose up    → ganti container dengan versi  │
│ php artisan migrate  → update database               │
│ php artisan optimize → cache config & route          │
└──────────────────────────────────────────────────────┘
```

Konsep kunci:

| Komponen | Fungsi |
|---|---|
| **GitHub Actions** | Mesin CI/CD yang menjalankan test, build, dan perintah deploy |
| **GHCR** (ghcr.io) | Gudang image Docker milik GitHub, menyimpan hasil build |
| **Tailscale** | VPN pribadi: server home "masuk" jaringan yang sama dengan runner GitHub, jadi bisa di-SSH tanpa IP publik / port forwarding |
| **`docker-compose.prod.yml`** | Compose versi produksi (image dari GHCR, bukan build lokal) |
| **`.env` di server** | Konfigurasi produksi (APP_KEY, DB, dll) — **tidak pernah di-commit** |

File yang terlibat di repository:

- `.github/workflows/ci-cd.yml` — pipeline CI/CD (3 job, sudah dikomentari per perintah)
- `docker-compose.prod.yml` — compose yang dipakai di server
- `docs/panduan-ci-cd.md` — panduan ini

---

## 2. Persiapan di GitHub

Buka: **repository → Settings → Secrets and variables → Actions → Secrets → New repository secret**

Buat **5 secret** berikut:

| Nama Secret | Isi | Cara mendapatkannya |
|---|---|---|
| `TS_AUTHKEY` | Auth key Tailscale | Langkah 2.1 |
| `DEPLOY_HOST` | IP Tailscale server home (`100.x.x.x`) | Langkah 3.2 |
| `DEPLOY_USER` | Username SSH di server | Langkah 3.3 (mis. `ho`) |
| `DEPLOY_PATH` | Direktori deploy di server | Langkah 3.4 (mis. `/home/ho/dfiber`) |
| `DEPLOY_SSH_KEY` | Private key SSH (multiline) | Langkah 3.3 |

### 2.1 Membuat Auth Key Tailscale

```bash
# 1. Buka https://login.tailscale.com/admin/settings/keys
# 2. Klik "Generate auth key"
# 3. Isi:
#    - Description       : github-actions-dfiber
#    - Tags (opsional)   : kosongkan (pakai default ACL)
#    - Expiration        : pilih "Reusable" ATAU jangka panjang
#      (WAJIB - kalau key kedaluwarsa, deploy berikutnya GAGAL)
# 4. Copy key (awalan tskey-auth-...) → simpan sebagai secret TS_AUTHKEY
```

> **Keamanan:** auth key hanya bisa *menambahkan* device ke tailnet Anda,
> tidak bisa membaca data. Simpan sebagai secret, jangan pernah di-commit.

### 2.2 Secret lainnya

- `DEPLOY_HOST`, `DEPLOY_USER`, `DEPLOY_PATH` diisi setelah server disiapkan (Bagian 3).
- `DEPLOY_SSH_KEY` diisi dari hasil `ssh-keygen` di Langkah 3.3.

---

## 3. Persiapan di Server Home

Semua perintah di bawah dijalankan **di server home** kecuali disebut lain.
Keterangan tiap perintah ditulis setelah tanda `#`.

### 3.1 Prasyarat: Docker

```bash
# Instal Docker Engine resmi (script Docker) - butuh sudo
curl -fsSL https://get.docker.com | sudo sh

# Tambahkan user Anda ke grup docker supaya tidak perlu sudo tiap kali
sudo usermod -aG docker $USER
# (logout-login lagi agar grup aktif)

# Verifikasi: harus muncul versi Docker & Compose v2.x
docker --version
docker compose version
```

### 3.2 Instal Tailscale

```bash
# Instal Tailscale dari script resmi
curl -fsSL https://tailscale.com/install.sh | sh

# Hubungkan server ke tailnet Anda (buka URL yang muncul untuk login sekali)
sudo tailscale up

# Tampilkan IP Tailscale server (format 100.x.x.x) - INI isi secret DEPLOY_HOST
tailscale ip -4

# (Opsional) beri nama server agar mudah dikenali via MagicDNS
sudo tailscale set --hostname=dfiber-server
```

Catatan:

- Jalankan `tailscale status` untuk memastikan server online.
- IP `100.x.x.x` bersifat privat di tailnet Anda — tidak bisa diakses dari internet.

### 3.3 Membuat SSH key untuk deploy

**Di Mac (lokal):**

```bash
# Buat pasangan key khusus deploy (tanpa passphrase agar bisa dipakai otomatis)
ssh-keygen -t ed25519 -C "github-actions-dfiber" -f ~/.ssh/dfiber_deploy
# -t ed25519 : tipe key modern & aman
# -C         : label/comment agar mudah dikenali
# -f         : nama file output (private key) + .pub (public key)
```

**Di server:**

```bash
# Buat direktori SSH dengan permission ketat (OpenSSH menolak jika terlalu longgar)
mkdir -p ~/.ssh && chmod 700 ~/.ssh

# Tambahkan public key ke daftar yang diizinkan
# (ganti ISIKAN_PUBLIC_KEY dengan isi file ~/.ssh/dfiber_deploy.pub dari Mac)
echo "ISIKAN_PUBLIC_KEY" >> ~/.ssh/authorized_keys

# Permission wajib 600, kalau salah SSH akan menolak key
chmod 600 ~/.ssh/authorized_keys
```

**Di Mac (lokal) — uji koneksi:**

```bash
# Ganti 100.x.x.x dengan hasil `tailscale ip -4`, user dengan DEPLOY_USER Anda
ssh -i ~/.ssh/dfiber_deploy -o StrictHostKeyChecking=accept-new ho@100.x.x.x 'echo koneksi-ok'
# Berhasil jika mencetak: koneksi-ok
```

**Di Mac (lokal) — simpan sebagai secret GitHub:**

```bash
# Salin isi PRIVATE key ke clipboard, lalu tempel di GitHub sebagai DEPLOY_SSH_KEY
pbcopy < ~/.ssh/dfiber_deploy
```

> **Penting:** private key (`dfiber_deploy`) hanya untuk GitHub Actions.
> Jangan commit, jangan share. Public key (`.pub`) boleh terbit di mana pun.

### 3.4 Direktori deploy + `.env` produksi

**Di server:**

```bash
# Buat direktori tempat aplikasi produksi "hidup" (berisi compose + .env)
mkdir -p ~/dfiber
```

> **Penting:** isi secret `DEPLOY_PATH` di GitHub **harus path absolut**
> (mis. `/home/ho/dfiber`), bukan `~/dfiber` — variabel `~` tidak ikut
> diekspansi oleh shell di dalam pipeline, sehingga deploy akan gagal.

**Di Mac (lokal):** salin contoh env ke server (file `.env.example` adalah template):

```bash
# Salin .env.example dari repo sebagai dasar .env produksi di server
# Ganti /home/ho dengan home directory user Anda di server (cek dengan `pwd` saat login)
scp .env.example ho@100.x.x.x:/home/ho/dfiber/.env
```

**Di server** — edit `~/dfiber/.env` (`nano ~/dfiber/.env`) dan ubah nilai ini:

```ini
APP_NAME="D-Fiber"
APP_ENV=production          # produksi, bukan local
APP_KEY=base64:ISI_KEY      # hasil perintah openssl di bawah
APP_DEBUG=false             # JANGAN true di produksi (error tidak bocor ke user)
APP_URL=http://100.x.x.x:8000   # atau domain Anda + port DOCKER_HTTP_PORT

LOG_LEVEL=warning           # kurangi volume log di produksi

# Database - sesuaikan dengan MySQL di host server
DB_CONNECTION=mysql
DB_HOST=host.docker.internal    # dari dalam container, MySQL ada di host
DB_PORT=3306
DB_DATABASE=dfiber
DB_USERNAME=dfiber              # user khusus aplikasi (lihat 3.5)
DB_PASSWORD=PASSWORD_KUAT
```

Generate `APP_KEY` (jalankan di server):

```bash
# Cetak APP_KEY acak siap pakai (prefix base64: wajib untuk Laravel)
echo "base64:$(openssl rand -base64 32)"
# Salin hasilnya ke APP_KEY di ~/dfiber/.env
```

Buat database + user MySQL (di server):

```bash
# Masuk ke MySQL sebagai root
sudo mysql

# Di prompt MySQL, jalankan:
# CREATE DATABASE            → buat database aplikasi (utf8mb4 = dukung emoji/UTF-8)
# CREATE USER + GRANT        → user aplikasi hanya boleh menyentuh database dfiber
# FLUSH PRIVILEGES           → terapkan perubahan hak akses segera
CREATE DATABASE IF NOT EXISTS dfiber CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
CREATE USER IF NOT EXISTS 'dfiber'@'%' IDENTIFIED BY 'PASSWORD_KUAT';
GRANT ALL PRIVILEGES ON dfiber.* TO 'dfiber'@'%';
FLUSH PRIVILEGES;
EXIT;
```

### 3.5 Login GHCR di server (agar bisa pull image private)

Image hasil build disimpan di `ghcr.io`. Kalau repository Anda **private**,
image juga private → server butuh Personal Access Token (PAT).

```bash
# Di Mac/browser: GitHub → Settings → Developer settings →
# Personal access tokens → Tokens (classic) → Generate new token (classic)
#
# Centang scope:
#   - repo         → izin baca repository private (dibutuhkan package ter-link repo)
#   - read:packages → izin menarik (pull) image dari GHCR
# Set expiry bebas (mis. 90 hari), lalu COPY token-nya
```

**Di server:**

```bash
# Login ke ghcr.io; token dimasukkan via stdin agar tidak masuk riwayat shell
echo "TOKEN_HASIL_COPAS" | docker login ghcr.io -u Adisufatulanam --password-stdin
# Berhasil jika mencetak: Login Succeed
# Kredensial tersimpan di ~/.docker/config.json (otomatis dipakai saat docker pull)
```

> Alternatif (lebih simpel tapi kurang aman): buat package image *public*
> di halaman Packages GitHub → Settings → Change visibility.
> **Tidak disarankan** karena image berisi source code lengkap.

---

## 4. Menjalankan Deploy Pertama

```bash
# Di Mac: pastikan branch main sudah berisi perubahan terbaru
git checkout main
git merge dev           # gabungkan kerjaan dev ke main (kalau perlu)
git push origin main    # push ke main → memicu pipeline CI/CD penuh
```

Pantau di browser: **repository → Actions tab → workflow "CI/CD"**.
Tiga job harus berjalan berurutan: `Test` → `Build image` → `Deploy`.

**Verifikasi setelah hijau semua:**

```bash
# Di server: status container (keduanya harus "Up" / "running")
cd ~/dfiber
docker compose -f docker-compose.prod.yml ps

# Di server: cek aplikasi merespons
curl -I http://localhost:8000      # harus HTTP/1.1 200 OK
                                    # (port sesuai DOCKER_HTTP_PORT di .env)

# Dari Mac (opsional): buka http://100.x.x.x:8000 di browser
```

---

## 5. Operasi Harian

Semua perintah dijalankan **di server** dari `~/dfiber`.

```bash
# Lihat log aplikasi realtime (berhenti dengan Ctrl+C)
docker compose -f docker-compose.prod.yml logs -f app

# Lihat log worker antrian (job WA, PDF, dll)
docker compose -f docker-compose.prod.yml logs -f queue

# Status semua container
docker compose -f docker-compose.prod.yml ps

# Restart worker tanpa menyentuh aplikasi
docker compose -f docker-compose.prod.yml restart queue

# Masuk ke dalam container app (tinker, artisan manual, dll)
docker compose -f docker-compose.prod.yml exec app bash

# Jalankan artisan manual
docker compose -f docker-compose.prod.yml exec app php artisan migrate:status
```

### Rollback ke versi sebelumnya

Setiap deploy juga men-tag image dengan `sha-<commit>` sehingga versi lama
selalu tersimpan di GHCR.

```bash
# 1. Lihat daftar image/tag yang tersedia: GHCR → Packages → <nama-image> → Tags
# 2. Pilih tag lama, misalnya sha-a1b2c3d
# 3. Di server, arahkan .env ke tag tersebut:
nano ~/dfiber/.env
#    ubah: DOCKER_IMAGE_TAG=sha-a1b2c3d
#    (kalau barisnya belum ada, tambahkan DOCKER_IMAGE & DOCKER_IMAGE_TAG)

# 4. Tarik & jalankan versi lama
docker compose -f docker-compose.prod.yml pull
docker compose -f docker-compose.prod.yml up -d

# 5. Migrasi TIDAK dijalankan otomatis saat rollback (migrasi bersifat maju).
#    Kalau versi lama butuh skema lama, restore backup database terlebih dulu.
```

> Catatan: deploy berikutnya dari GitHub akan menimpa `DOCKER_IMAGE_TAG`
> kembali ke `main` (env dari workflow lebih tinggi prioritasnya dari `.env`).

---

## 6. Troubleshooting

| Gejala | Penyebab | Solusi |
|---|---|---|
| Job Deploy gagal di step "Hubungkan runner ke Tailscale" | Auth key salah/kedaluwarsa, atau server offline | Regenerate key di admin Tailscale → update secret `TS_AUTHKEY`; cek `tailscale status` di server |
| `ping failed` / job deploy gagal sebelum SSH | Server home tidak terjangkau dari tailnet | Di server: `sudo systemctl status tailscaled`, `sudo tailscale up` |
| `pull access denied for ghcr.io/...` | Server belum login GHCR / PAT kedaluwarsa | Ulangi Langkah 3.5 (`docker login ghcr.io`) |
| `env file .env not found` | `~/dfiber/.env` belum dibuat | Buat sesuai Langkah 3.4 |
| Deploy gagal di `migrate` (`Connection refused`) | MySQL tidak jalan atau kredensial salah | Cek `sudo systemctl status mysql`, cocokkan `DB_*` di `.env` |
| Smoke test gagal (aplikasi tidak merespons) | Ada error saat boot aplikasi | Lihat log: `docker compose -f docker-compose.prod.yml logs --tail=100 app` |
| Job Test gagal di `composer setup` bagian migrate | Perubahan migrasi belum kompatibel / DB CI bermasalah | Baca log job Test di Actions; jalankan test lokal: `composer ci:check` |
| Job Test gagal di `npm run check` / `vue-tsc` | Error lint/type frontend | Perbaiki lokal: `npm run check:fix`, `npm run types:check` |
| Deploy jalan tapi tampilan lama | Browser cache aset | Hard refresh (Cmd+Shift+R); pastikan tag image berubah di GHCR |
| `Permission denied (publickey)` saat test SSH | Public key belum terpasang / permission salah | Cek `~/.ssh/authorized_keys` (harus `chmod 600`, `~/.ssh` harus `chmod 700`) |

### Menambah/mengubah secret

GitHub → repository → **Settings → Secrets and variables → Actions** → klik
secret → *Update* / *Delete*.

### Menambah environment variables baru untuk aplikasi

1. Tambahkan di `config/*.php` (Laravel) + `.env.example` (template untuk dev).
2. Tambahkan nilainya di `~/dfiber/.env` **di server**.
3. Jalankan ulang container: `docker compose -f docker-compose.prod.yml up -d`
   (nilai `.env` dibaca ulang setiap container dibuat ulang).

---

## Ringkasan Alur Setelah Setup Selesai

```
edit kode di dev → push dev    → test + build saja (image tag "dev")
merge ke main → push main      → test → build → deploy otomatis ke server home
rollback                     → ubah DOCKER_IMAGE_TAG di .env server
```
