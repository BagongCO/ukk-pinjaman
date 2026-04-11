# 💡 Sistem Peminjaman Lampu

[![PHP Version](https://img.shields.io/badge/PHP-8.0%2B-blue.svg)](https://php.net)
[![MySQL Version](https://img.shields.io/badge/MySQL-5.7%2B-orange.svg)](https://mysql.com)
[![License](https://img.shields.io/badge/License-MIT-green.svg)](LICENSE)
[![Status](https://img.shields.io/badge/Status-Production-success.svg)]()

> Sistem manajemen peminjaman lampu berbasis web untuk event, dekorasi, dan kebutuhan penerangan.

## 📋 Daftar Isi

- [Tentang Project](#-tentang-project)
- [Fitur Utama](#-fitur-utama)
- [Jenis Lampu](#-jenis-lampu)
- [Teknologi yang Digunakan](#-teknologi-yang-digunakan)
- [Struktur Database](#-struktur-database)
- [Instalasi](#-instalasi)
- [Cara Penggunaan](#-cara-penggunaan)
- [Screenshot](#-screenshot)
- [Lisensi](#-lisensi)

## 🎯 Tentang Project

Sistem Peminjaman Lampu adalah aplikasi web yang digunakan untuk mengelola proses peminjaman berbagai jenis lampu untuk keperluan event, pesta, dekorasi pernikahan, konser, dan kebutuhan penerangan lainnya.

### 👥 Role Pengguna

| Role | Hak Akses |
|------|-----------|
| **Admin** | Mengelola semua data (lampu, kategori, user, peminjaman) |
| **Petugas** | Memproses peminjaman dan pengembalian lampu |
| **Peminjam** | Melihat lampu, melakukan peminjaman, melihat riwayat |

## ✨ Fitur Utama

### 🔐 Autentikasi
- Login multi-role (Admin, Petugas, Peminjam)
- Registrasi untuk peminjam baru
- Logout system

### 💡 Manajemen Lampu (Admin & Petugas)
- CRUD data lampu
- Upload gambar lampu
- Kategori lampu (LED, Dekorasi, Sorot, Neon, dll)
- Filter berdasarkan kategori
- Pencarian lampu
- Tracking stok lampu

### 📝 Manajemen Peminjaman
- Peminjam dapat menyewa lampu
- Admin/petugas dapat menyetujui peminjaman
- Proses pengembalian dengan pengecekan kondisi lampu
- Hitung denda keterlambatan dan kerusakan
- Riwayat peminjaman per user
- Status peminjaman (Pending, Disetujui, Dipinjam, Selesai, Ditolak)

### 📊 Dashboard
- Statistik peminjaman
- Grafik peminjaman bulanan
- Lampu terbaru
- Peminjaman terbaru
- Stok lampu menipis (alert)

### 📧 Contact Management
- Form kontak untuk pengunjung
- Admin dapat membaca dan membalas pesan
- Status pesan (Unread, Read, Replied)

### 📜 Log Activity
- Mencatat semua aktivitas user
- Filter berdasarkan role
- Pencarian log

## 💡 Jenis Lampu

| Kategori | Jenis Lampu | Kegunaan |
|----------|-------------|----------|
| **LED** | Lampu LED, Lampu RGB, Lampu Strip LED | Penerangan umum, dekorasi |
| **Dekorasi** | Lampu Hias, Lampu Taman, Lampu Gantung | Dekorasi event, pesta, pernikahan |
| **Sorot** | Lampu Sorot, Spot Light, Moving Head | Konser, panggung, acara besar |
| **Neon** | Lampu Neon Flex, Lampu Neon Box | Reklame, signage, dekorasi |
| **Emergency** | Lampu Darurat, Lampu Tanda | Keamanan, evakuasi |
| **Lainnya** | Lampu Meja, Lampu Belajar, Lampu Kerja | Kebutuhan khusus |

## 🛠 Teknologi yang Digunakan

### Backend
- **PHP 8.0+** - Bahasa pemrograman utama
- **MySQL 5.7+** - Database management system
- **MySQLi** - Database driver

### Frontend
- **HTML5** - Struktur halaman
- **CSS3** - Styling
- **JavaScript** - Interaktivitas
- **Bootstrap 5** - Framework CSS
- **Font Awesome 6** - Icon library
- **jQuery** - JavaScript library
- **DataTables** - Tabel dinamis
- **AOS** - Scroll animation

### Tools
- **XAMPP / Laragon** - Local server
- **Git** - Version control
- **GitHub** - Repository hosting

## 📊 Struktur Database

### Tabel-tabel utama:

```sql
-- users - Data pengguna (admin, petugas, peminjam)
-- lampu - Data lampu
-- kategori_lampu - Kategori lampu
-- peminjaman - Data peminjaman
-- contacts - Pesan kontak
-- log_aktivitas - Log aktivitas
