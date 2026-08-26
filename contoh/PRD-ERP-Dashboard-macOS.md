# PRD — ERP Dashboard dengan Desain macOS
**Versi:** 1.0
**Tanggal:** 26 Agustus 2026
**Status:** Draft

---

## 1. Latar Belakang & Tujuan

Membangun antarmuka dashboard ERP yang mengadopsi bahasa desain macOS (Big Sur/Sonoma-style): translucency/blur, jendela dengan traffic-light button, sidebar ala Finder, dan Dock. Tujuannya membuat ERP — yang biasanya terasa kaku dan padat data — terasa **ringan, familiar, dan menyenangkan digunakan** sehari-hari, tanpa mengorbankan fungsi inti ERP (data operasional bisnis).

**Target pengguna:** staf internal (admin, finance, gudang, HR) yang mengakses dashboard dari desktop/laptop setiap hari.

**Bukan tujuan:** meniru macOS 1:1 secara fungsional (tidak perlu Launchpad sungguhan, Mission Control, dsb). Elemen macOS dipakai sebagai *skin*/metafora visual, bukan tiruan sistem operasi.

---

## 2. Prinsip Desain

| Prinsip | Penjelasan |
|---|---|
| **Vibrancy over flat** | Gunakan blur/translucency pada layer navigasi (menu bar, sidebar, dock), bukan pada area kerja utama yang padat data. |
| **Konten tetap raja** | Ornamen macOS (traffic light, dock) tidak boleh mengurangi ruang baca data tabel/kartu. |
| **Konsisten dengan sistem asli** | Warna aksen, radius, dan spacing mengikuti nilai resmi Apple HIG (lihat token di bawah), bukan interpretasi bebas. |
| **Skeuomorphism secukupnya** | Depth dipakai untuk hierarki (kartu di atas latar, dock mengambang), bukan dekorasi berlebihan (hindari gradient/shadow berlebihan). |
| **Kepadatan data profesional** | ERP menampilkan banyak angka; jangan sampai gaya macOS yang "lega" membuat informasi penting jadi tenggelam — prioritaskan angka besar/jelas di kartu statistik. |

---

## 3. Design Token

### 3.1 Warna
| Token | Hex | Penggunaan |
|---|---|---|
| `--accent` (System Blue) | `#0A84FF` | Aksi utama, link, item aktif |
| `--accent-2` (System Indigo) | `#5E5CE6` | Aksen sekunder, gradient |
| `--green` (System Green) | `#30D158` | Status positif, delta naik |
| `--orange` (System Orange) | `#FF9F0A` | Peringatan ringan |
| `--red` (System Red) | `#FF453A` | Error, delta turun, jatuh tempo |
| `--teal` | `#64D2FF` | Aksen ikon |
| `--pink` | `#FF375F` | Aksen ikon |
| `--yellow` | `#FFD60A` | Aksen ikon |
| Latar jendela | `rgba(255,255,255,0.78)` + blur 40px | Body window |
| Latar sidebar | `rgba(246,246,248,0.72)` + blur 20px | Sidebar |
| Teks utama | `#1d1d1f` | Heading, isi utama |
| Teks sekunder | `#6e6e73` | Label, caption |
| Divider | `rgba(60,60,67,0.12)` | Garis pemisah |

> Mode gelap (dark mode) belum termasuk versi 1.0 — lihat *Future Work*.

### 3.2 Tipografi
- **Font stack:** `-apple-system, BlinkMacSystemFont, "SF Pro Text", "Helvetica Neue", Arial, sans-serif`
- **Skala:**
  - H1 (judul halaman): 22px / bold
  - H3 (judul panel): 14.5px / bold
  - Body: 12.5–13px / regular–medium
  - Caption/label: 11–11.5px / medium, uppercase untuk section label

### 3.3 Radius & Spacing
| Elemen | Radius |
|---|---|
| Jendela utama | 20px |
| Kartu/panel | 16px |
| Ikon modul (squircle) | 13px |
| Item navigasi/tombol | 8–9px |
| Dock | 18px |
| Item Dock | 12px |

- Padding jendela ke desktop: 26px
- Gap antar kartu: 14px
- Padding dalam panel: 16–18px

### 3.4 Elevasi (Shadow)
- Jendela utama: `0 30px 80px rgba(0,0,0,0.35)`
- Kartu: `0 1px 3px rgba(0,0,0,0.06), 0 8px 20px rgba(0,0,0,0.04)`
- Dock & item dock: shadow lebih tajam agar terasa "mengambang" di atas konten

---

## 4. Struktur Layout

```
┌─────────────────────────────────────────────────┐
│ Menu Bar (26px, blur gelap, ikon status + jam)   │
├─────────────────────────────────────────────────┤
│ ┌───────────────────────────────────────────┐   │
│ │ Title Bar (traffic light • judul • search) │   │
│ ├───────────┬─────────────────────────────────┤ │
│ │  Sidebar  │  Content Area                   │ │
│ │  (Finder- │  - Header + CTA                 │ │
│ │  style)   │  - Stat cards (grid 4 kolom)     │ │
│ │           │  - Chart + Donut (2 kolom)       │ │
│ │           │  - Tabel transaksi               │ │
│ │           │  - Grid modul (Launchpad-style)  │ │
│ └───────────┴─────────────────────────────────┘ │
└─────────────────────────────────────────────────┘
              [ Dock mengambang di bawah ]
```

### 4.1 Menu Bar
- Tinggi tetap 26px, selalu di posisi paling atas viewport (bukan bagian dari jendela)
- Kiri: logo Apple-style (opsional diganti logo perusahaan) + nama aplikasi + menu (File/Edit/View/Window/Help) — bersifat dekoratif, tidak wajib fungsional di v1
- Kanan: ikon status (jaringan, dsb.) + jam real-time

### 4.2 Jendela Aplikasi (Window)
- Traffic light (merah/kuning/hijau) — dekoratif, tidak perlu fungsi close/minimize sungguhan kecuali diintegrasikan ke Electron/desktop app
- Search box di title bar untuk pencarian global (modul, invoice, produk)
- Judul jendela center-aligned, deskriptif sesuai halaman aktif

### 4.3 Sidebar
- Profil singkat perusahaan/pengguna di atas
- Navigasi dikelompokkan per section: **Utama** (modul transaksional), **Laporan**, **Sistem**
- Item aktif diberi warna solid `--accent` dengan teks putih
- Badge angka (contoh: jumlah pesanan pending) di sisi kanan item

### 4.4 Content Area
1. **Header** — sapaan + tanggal + 1 CTA utama
2. **Stat cards** — maksimal 4 kartu, tiap kartu: ikon badge, indikator delta (naik/turun), angka besar, label
3. **Chart row** — 2 kolom: chart tren (bar/line) di kiri (lebih lebar), distribusi kategori (donut) di kanan
4. **Tabel** — transaksi/aktivitas terbaru, maksimal 5–7 baris terlihat, sisanya via scroll/paginasi
5. **Grid modul** — akses cepat ke semua modul ERP, ikon squircle bergradasi warna berbeda per modul

### 4.5 Dock
- Berisi 6–10 modul yang paling sering diakses (bukan semua modul — itu tugas grid Launchpad)
- Efek hover: `translateY(-10px) scale(1.15)` agar terasa "mengambang"
- Separator visual untuk memisahkan grup modul inti vs. sistem/pengaturan

---

## 5. Sistem Ikon
- Semua ikon berbentuk **squircle** (radius 13px untuk kartu modul, 12px untuk dock), bukan lingkaran atau kotak tajam
- Setiap kategori modul punya warna gradient unik & konsisten di semua tempat (sidebar, stat card, grid modul, dock):

| Modul | Warna |
|---|---|
| Penjualan | Hijau (`#34C759 → #28A745`) |
| Inventori | Oranye (`#FF9F0A → #FF7A00`) |
| Pembelian | Pink/merah (`#FF375F → #D6194B`) |
| Keuangan | Biru muda (`#64D2FF → #0A84FF`) |
| HR/SDM | Ungu (`#5E5CE6 → #8E5CE6`) |
| CRM | Merah (`#FF453A → #C81E1E`) |
| Analitik | Abu-abu (`#8E8E93 → #636366`) |
| Pengaturan | Abu gelap (`#636366 → #3A3A3C`) |

- Glyph di dalam ikon: garis (stroke), bukan solid fill, ketebalan 2px, `stroke-linecap: round` — konsisten dengan gaya SF Symbols

---

## 6. Interaksi & Motion
- Hover kartu/tombol: transform halus (translateY -3px hingga -10px tergantung elemen), durasi 150ms, easing `ease`
- Tidak ada animasi masuk halaman yang berlebihan (page-load animation) — cukup transisi hover agar terasa responsif, bukan "AI-generated flashy"
- Fokus keyboard harus tetap terlihat (accessibility) meski tema translucent — gunakan outline kontras saat elemen di-*tab*

---

## 7. Responsivitas
| Breakpoint | Perilaku |
|---|---|
| Desktop (≥1200px) | Layout penuh seperti mockup: sidebar + 4 kolom stat card |
| Tablet (768–1199px) | Sidebar collapsible jadi ikon saja; stat card jadi 2 kolom |
| Mobile (<768px) | Sidebar disembunyikan (hamburger/drawer), stat card 1 kolom, dock disembunyikan atau diganti tab bar bawah |

> Catatan: mockup awal dioptimalkan untuk desktop. Versi mobile perlu iterasi desain terpisah, bukan sekadar menyusutkan elemen (efek blur & dock kurang cocok untuk layar sempit).

---

## 8. Aksesibilitas
- Kontras teks minimal WCAG AA terhadap latar translucent (uji ulang saat blur diterapkan di atas wallpaper gelap/terang)
- Semua ikon fungsional harus punya label teks yang menyertainya (bukan icon-only tanpa keterangan)
- `prefers-reduced-motion` dihormati — matikan efek scale/translate saat pengguna mengaktifkannya di OS

---

## 9. Batasan & Risiko
- **Performa blur**: `backdrop-filter` cukup berat di perangkat rendah/browser lama — sediakan fallback warna solid jika tidak didukung
- **Konsistensi lintas OS**: pengguna Windows/Linux tidak familiar dengan metafora macOS (traffic light, dock) — pastikan elemen tetap fungsional secara universal, bukan hanya estetika
- **Skalabilitas data**: mockup pakai data statis; saat dihubungkan ke data nyata, pastikan tabel & chart tetap terbaca saat volume data besar (perlu paginasi/virtualisasi)

---

## 10. Future Work (v1.1+)
- Dark mode mengikuti palet macOS gelap (`#1c1c1e` dsb.)
- Widget yang bisa di-drag/susun ulang di area konten (mirip Notification Center macOS)
- Command palette (⌘K) untuk pencarian & navigasi cepat
- Versi tablet/mobile dengan pendekatan desain terpisah

---

## 11. Referensi
- Apple Human Interface Guidelines — Materials, Color, SF Symbols
- Struktur file mockup: `erp-dashboard-macos.html` (single-file HTML/CSS/JS, ikon SVG inline, tanpa framework/library eksternal)
