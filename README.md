# PPK-Pertemuan2

# Software Requirements Specification (SRS)
## Aplikasi Manajemen Tugas "JARA"
---

## 1. Pendahuluan

### 1.1 Tujuan
Dokumen ini menjelaskan kebutuhan perangkat lunak untuk aplikasi **JARA**, yaitu aplikasi manajemen tugas yang dapat digunakan secara individu maupun tim (kolaborasi).

### 1.2 Ruang Lingkup
JARA memungkinkan pengguna untuk:
- Membuat daftar tugas (list) sesuai kategori, misal "Tugas Kantor" atau "Tugas Kuliah"
- Menambahkan tugas satu per satu ke dalam daftar
- Mengelola tugas secara individu maupun berkolaborasi dengan orang lain
- Memantau perkembangan tugas dan tim

---

## 2. Deskripsi Umum

### 2.1 Aktor / Peran Pengguna

| Peran | Deskripsi |
|---|---|
| **Admin** | Memegang kendali penuh atas sistem dan manajemen user |
| **User** | Pemilik akun yang membuat daftar tugas dan tugas |
| **Kolaborator** | Orang yang ditambahkan oleh User ke dalam suatu tugas/daftar untuk dikerjakan bersama |

### 2.2 Karakteristik Pengguna
Pengguna umum (mahasiswa, karyawan) yang membutuhkan alat bantu pengelolaan tugas pribadi maupun tim, tanpa memerlukan keahlian teknis khusus.

---

## 3. Kebutuhan Fungsional

| ID | Kebutuhan |
|---|---|
| FR-01 | Sistem harus memungkinkan User membuat **daftar tugas** (kategori), contoh: "Tugas Kantor", "Tugas Kuliah" |
| FR-02 | Sistem harus memungkinkan User menambahkan **tugas** satu per satu ke dalam daftar tugas |
| FR-03 | Setiap tugas harus memiliki atribut minimal: **nama tugas**, **prioritas** (tinggi/sedang/rendah), dan **tenggat waktu** |
| FR-04 | Sistem harus memungkinkan User menandai tugas sebagai **selesai** |
| FR-05 | Sistem harus menyediakan fitur **kolaborasi**, di mana User dapat menambahkan orang lain (Kolaborator) ke suatu tugas |
| FR-06 | User dan Kolaborator yang terlibat dalam tugas kolaborasi dapat **mengubah status tugas** (misal: Belum Dikerjakan, Sedang Dikerjakan, Selesai) |
| FR-07 | Sistem harus menyediakan fitur **monitoring** agar User/Admin dapat memantau perkembangan tugas dan tim |
| FR-08 | Sistem harus memiliki **manajemen user** dengan 3 peran: Admin, User, dan Kolaborator |
| FR-09 | Admin dapat mengelola (menambah/menghapus/mengatur) akun User dalam sistem |

---

## 4. Kebutuhan Non-Fungsional (Ringkas)

| ID | Kebutuhan |
|---|---|
| NFR-01 | Sistem harus mudah digunakan (user-friendly) untuk pengguna non-teknis |
| NFR-02 | Sistem harus dapat diakses melalui web dan/atau mobile |
| NFR-03 | Data tugas harus tersimpan secara aman dan hanya dapat diakses oleh pihak yang berwenang (Admin/User/Kolaborator terkait) |
| NFR-04 | Sistem harus responsif saat mengelola banyak tugas/daftar tugas |
