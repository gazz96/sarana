# Product Requirements Document (PRD)
## Sistem Informasi Sarana dan Prasarana (SARPRAS)
### Untuk Sekolah/Kampus

---

## 1. Executive Summary

Sistem Informasi Sarana dan Prasarana (SARPRAS) adalah platform manajemen aset dan pemeliharaan fasilitas yang dirancang khusus untuk institusi pendidikan seperti sekolah dan kampus. Sistem ini memungkinkan manajemen inventaris, pelaporan kerusakan, dan alur kerja perbaikan yang terkoordinasi antara berbagai peran pengguna.

### Tujuan Utama
- Manajemen inventaris sarana dan prasarana secara efisien
- Pelaporan dan tracking kerusakan fasilitas
- Koordinasi perbaikan melalui alur kerja terstruktur
- Pengambilan keputusan berbasis data untuk perencanaan pengadaan

---

## 2. Current System Analysis

### Teknologi yang Digunakan
- **Backend Framework**: Laravel (PHP)
- **Frontend**: Blade Templates dengan CSS Bootstrap
- **Database**: MySQL
- **Libraries**: Chart.js untuk visualisasi data

### Fitur yang Sudah Ada
1. **Manajemen Pengguna dengan Role-based Access Control**
   - Guru/Staf
   - Teknisi
   - Admin
   - Lembaga/Manajemen
   - Keuangan

2. **Manajemen Inventaris (Goods)**
   - Katalog barang dengan kode otomatis
   - Informasi merk, detail, dan lokasi
   - Status barang (AKTIF/TIDAK AKTIF)
   - Pencarian dan filter

3. **Sistem Pelaporan Masalah (Problems)**
   - Workflow status: DRAFT → DIAJUKAN → PROSES → SELESAI → DIBATALKAN
   - Approval system multi-level
   - Tracking perbaikan
   - Cetak laporan

4. **Manajemen Lokasi**
   - Pengelompokan lokasi aset

---

## 3. User Personas

### 3.1 Guru/Staf Pengajar
**Karakteristik:**
- Pengguna utama fasilitas dan sarana
- Membutuhkan akses cepat untuk melaporkan masalah
- Tidak memiliki keahlian teknis

**Kebutuhan:**
- Melaporkan kerusakan sarana dengan mudah
- Melihat status laporan
- Mengakses daftar inventaris

### 3.2 Teknisi
**Karakteristik:**
- Bertanggung jawab atas perbaikan
- Memiliki keahlian teknis
- Perlu estimasi biaya dan material

**Kebutuhan:**
- Menerima dan menangani laporan kerusakan
- Mengestimasi biaya perbaikan
- Update status pekerjaan
- Request approval untuk pengadaan material

### 3.3 Admin
**Karakteristik:**
- Mengelola operasional harian
- Koordinator antara teknisi dan manajemen

**Kebutuhan:**
- Memantau semua laporan masuk
- Approve hasil kerja teknisi
- Koordinasi dengan departemen lain

### 3.4 Lembaga/Manajemen
**Karakteristik:**
- Decision maker
- Membutuhkan analisis untuk pengambilan keputusan

**Kebutuhan:**
- Approve pengadaan/perbaikan besar
- Melihat laporan dan statistik
- Analisis trend kerusakan

### 3.5 Keuangan
**Karakteristik:**
- Mengelola anggaran dan pembayaran

**Kebutuhan:**
- Approve biaya perbaikan
- Tracking pengeluaran maintenance
- Laporan keuangan

---

## 4. Functional Requirements

### 4.1 Modul Inventaris (Barang)

#### 4.1.1 Manajemen Barang
**Priority: High | Status: Already Implemented**

| Feature | Description | User Role |
|---------|-------------|-----------|
| Create Barang | Tambah barang baru dengan field: nama, merk, detail, lokasi, status | Admin |
| Read Barang | Lihat daftar barang dengan pencarian dan pagination | All Users |
| Update Barang | Edit informasi barang | Admin |
| Delete Barang | Hapus barang dari database | Admin |
| Auto-generate Kode | Generate kode barang otomatis (BRG-001, BRG-002, dll) | System |

**Data Structure:**
```
- id (PK)
- code (unique, auto-generated)
- name
- merk
- detail
- location_id (FK)
- status (AKTIF/TIDAK AKTIF)
- created_at
- updated_at
```

#### 4.1.2 Manajemen Lokasi
**Priority: High | Status: Already Implemented**

| Feature | Description |
|---------|-------------|
| Create Lokasi | Tambah lokasi baru |
| List Lokasi | Daftar semua lokasi untuk dropdown |
| Edit/Hapus Lokasi | Manajemen data lokasi |

### 4.2 Modul Pelaporan Masalah

#### 4.2.1 Workflow Status
**Priority: High | Status: Already Implemented**

```
0. DRAFT → Draft laporan oleh Guru
1. DIAJUKAN → Laporan dikirim ke Teknisi
2. PROSES → Teknisi mengerjakan
3. SELESAI DIKERJAKAN → Menunggu approval
4. DIBATALKAN → Laporan dibatalkan
5. MENUNGGU PERSETUJUAN MANAGEMENT → Approval manajemen
6. MENUNGGU PERSETUJUAN ADMIN → Approval admin
7. MENUNGGU PERSETUJUAN KEUANGAN → Approval keuangan
```

#### 4.2.2 Fitur Pelaporan
**Priority: High | Status: Already Implemented**

| Feature | Description | User Role |
|---------|-------------|-----------|
| Create Problem | Buat laporan kerusakan dengan item barang | Guru |
| Submit Problem | Ajukan laporan ke teknisi | Guru |
| Accept Problem | Teknisi menerima tugas | Teknisi |
| Update Problem | Update progress & tambah item/perkiraan biaya | Teknisi |
| Finish Problem | Tandai selesai & ajukan approval | Teknisi |
| Management Approval | Approval oleh manajemen | Lembaga |
| Admin Approval | Approval oleh admin | Admin |
| Finance Approval | Approval pembayaran | Keuangan |
| Print Problem | Cetak laporan PDF | All Users |

#### 4.2.3 Struktur Data Problem
```
Problem:
- id (PK)
- code (auto-generated)
- user_id (FK - pelapor)
- issue (deskripsi masalah)
- status (0-7)
- date (tanggal laporan)
- note (catatan tambahan)
- user_technician_id (FK)
- user_management_id (FK)
- user_finance_id (FK)
- admin_id (FK)

ProblemItem:
- id (PK)
- problem_id (FK)
- good_id (FK)
- description
- price (estimasi biaya)
- quantity
```

### 4.3 Modul Dashboard & Reporting

#### 4.3.1 Dashboard
**Priority: High | Status: Already Implemented**

| Feature | Description |
|---------|-------------|
| Statistics Overview | Total barang, total masalah, statistik per role |
| Chart Visualization | Grafik trend kerusakan, statistik barang |
| Recent Activity | Aktivitas terbaru dalam sistem |

#### 4.3.2 Laporan
**Priority: Medium | Status: Already Implemented**

| Feature | Description |
|---------|-------------|
| Generate Report | Buat laporan umum |
| Export Data | Export data ke berbagai format |
| Print Laporan | Cetak laporan untuk dokumentasi |

### 4.4 Modul User Management

#### 4.4.1 Autentikasi & Authorisasi
**Priority: High | Status: Already Implemented**

| Feature | Description |
|---------|-------------|
| Login | Autentikasi user |
| Role-based Access | Hak akses berdasarkan peran |
| Profile Management | Update profile user |

---

## 5. Non-Functional Requirements

### 5.1 Performance
- Response time < 2 detik untuk load halaman
- Support 100+ concurrent users
- Query optimization untuk data > 10,000 records

### 5.2 Security
- Password hashing (bcrypt)
- CSRF protection
- SQL injection prevention
- Role-based access control
- Session management

### 5.3 Usability
- Responsive design (mobile, tablet, desktop)
- Intuitive navigation
- Clear error messages
- Bahasa Indonesia

### 5.4 Scalability
- Modular architecture untuk penambahan fitur
- Database optimization untuk growth
- Code reusability

---

## 6. Technical Specifications

### 6.1 Stack Teknologi

**Backend:**
- Laravel 8.x
- PHP 7.4+
- MySQL 5.7+

**Frontend:**
- Bootstrap 5.x
- Blade Templates
- jQuery
- Chart.js

**Development Tools:**
- Composer
- NPM
- Git

### 6.2 Database Schema

**Main Tables:**
1. `users` - Data pengguna
2. `roles` - Role pengguna
3. `goods` - Data barang/inventaris
4. `locations` - Data lokasi
5. `problems` - Data laporan masalah
6. `problem_items` - Item dalam laporan
7. `problem_approvals` - Tracking approval
8. `options` - Settings sistem

### 6.3 API Endpoints (Future Enhancement)

```
POST   /api/login
GET    /api/goods
POST   /api/goods
GET    /api/problems
POST   /api/problems
GET    /api/problems/{id}
PATCH  /api/problems/{id}
DELETE /api/problems/{id}
```

---

## 7. Enhancement Opportunities

### 7.1 Priority Enhancements (Short-term)

#### 1. Notifikasi System
**Priority: High | Effort: Medium**

| Feature | Description |
|---------|-------------|
| Email Notifications | Notifikasi saat status problem berubah |
| In-app Notifications | Notifikasi real-time dalam dashboard |
| SMS Notifications | Notifikasi SMS untuk urgent cases |

**User Value:** Meningkatkan respons time dan komunikasi

#### 2. Mobile App / PWA
**Priority: High | Effort: High**

| Feature | Description |
|---------|-------------|
| Mobile-friendly Interface | UI optimised untuk mobile |
| Progressive Web App | Offline capability |
| Photo Upload | Upload foto kerusakan |

**User Value:** Akses mudah saat melaporkan masalah

#### 3. Advanced Reporting
**Priority: Medium | Effort: Medium**

| Feature | Description |
|---------|-------------|
| Custom Date Range | Filter laporan per periode |
| Export to Excel/PDF | Multiple export formats |
| Analytics Dashboard | Insight dan trend analysis |

**User Value:** Data-driven decision making

### 7.2 Future Enhancements (Long-term)

#### 1. QR Code System
**Priority: Medium | Effort: Medium**

| Feature | Description |
|---------|-------------|
| Generate QR Code | QR code untuk setiap barang |
| Scan QR Code | Scan untuk melihat detail barang |
| Quick Report | Scan & report langsung |

#### 2. Preventive Maintenance
**Priority: Medium | Effort: High**

| Feature | Description |
|---------|-------------|
| Maintenance Schedule | Jadwal pemeliharaan berkala |
| Reminders | Notifikasi jadwal maintenance |
- Asset Lifecycle Tracking | Tracking umur pakai aset

#### 3. Budget & Procurement
**Priority: Low | Effort: High**

| Feature | Description |
|---------|-------------|
| Budget Planning | Perencanaan anggaran |
| Procurement Request | Request pengadaan baru |
- Vendor Management | Manajemen supplier/vendor

---

## 8. Success Metrics

### 8.1 Key Performance Indicators (KPIs)

| Metric | Target | Measurement |
|--------|--------|-------------|
| Response Time | < 24 jam | Rata-rata waktu dari laporan ke penanganan |
| Resolution Time | < 3 hari | Rata-rata waktu penyelesaian masalah |
| User Adoption | 80%+ | Persentase user aktif |
| System Uptime | 99%+ | Ketersediaan sistem |
| User Satisfaction | 4+/5 | Rating kepuasan pengguna |

### 8.2 Business Impact

| Impact | Description |
|--------|-------------|
| Cost Reduction | Pengurangan biaya reaktif melalui preventive maintenance |
| Efficiency | Meningkatkan efisiensi penanganan masalah |
- Accountability | Jelas tanggung jawab setiap role |
| Data-driven | Keputusan berbasis data aktual |

---

## 9. Implementation Roadmap

### 9.1 Phase 1: Foundation (DONE ✅)
- [x] User authentication & role management
- [x] Inventory management (goods)
- [x] Problem reporting system
- [x] Basic workflow automation
- [x] Basic dashboard

### 9.2 Phase 2: Enhancement (Recommended)
- [ ] Notification system (email & in-app)
- [ ] Advanced reporting & analytics
- [ ] Mobile responsiveness improvement
- [ ] Photo upload for damage reports
- [ ] Barcode/QR code generation

### 9.3 Phase 3: Advanced Features (Future)
- [ ] Mobile app / PWA
- [ ] Preventive maintenance scheduling
- [ ] Budget & procurement module
- [ ] Vendor management
- [ ] Integration with other systems (HR, Finance)

---

## 10. Risk Assessment

| Risk | Impact | Probability | Mitigation |
|------|--------|-------------|------------|
| User Adoption | High | Medium | Training, UI simplification |
| Data Loss | High | Low | Regular backups, redundancy |
| Performance Issues | Medium | Medium | Optimization, caching |
| Security Breach | High | Low | Security best practices, audits |

---

## 11. Cost Estimate

### 11.1 Development Cost

| Item | Effort | Notes |
|------|--------|-------|
| Phase 1 (Done) | 4-6 weeks | Foundation system |
| Phase 2 | 6-8 weeks | Enhancement features |
| Phase 3 | 8-12 weeks | Advanced features |
| Maintenance | 20% pa | Ongoing support |

### 11.2 Infrastructure Cost

| Item | Monthly Cost |
|------|--------------|
| Hosting (VPS/Cloud) | Rp 500K - 2M |
| Domain | Rp 150K |
| Backup Service | Rp 100K - 500K |
| SSL Certificate | Free/Rp 200K |
| Email Service | Rp 100K - 500K |

---

## 12. Detailed Workflows per Role

### 12.1 Workflow Diagram Legend
```
→  = Flow to next step
⬆ = Approval/Action Required
⬇ = Status Change
□ = Start/End Point
◆ = Decision Point
```

---

### 12.2 Role: GURU/STAF PENGAJAR

#### 12.2.1 Guru Workflow Flowchart
```
┌─────────────────────────────────────────────────────────────┐
│                    GURU/STAF PENGAJAR                       │
│                    FLOW KERUSAKAN BARANG                    │
└─────────────────────────────────────────────────────────────┘

                    [LOGIN SISTEM]
                            ↓
        ┌─────────────────────────────────────────┐
        │  Dashboard: Lihat Menu & Statistik       │
        └─────────────────────────────────────────┘
                            ↓
                    [ADA KERUSAKAN?]
                    /              \
                  YES               NO
                  /                  \
            [BUAT LAPORAN]       [LIHAT INVENTARIS]
                  ↓                   ↓
        ┌───────────────────────┐   ┌─────────────────────┐
        │  1. Input Deskripsi   │   │  - Cari Barang      │
        │     Masalah           │   │  - Filter Lokasi    │
        │  2. Pilih Barang      │   │  - Lihat Detail     │
        │     yang Rusak        │   └─────────────────────┘
        │  3. Tambah Item       │
        │     (jika perlu)      │
        └───────────────────────┘
                  ↓
            [SIMPAN DRAFT]
                  ↓
            Status: DRAFT (0)
                  ↓
        ┌───────────────────────┐
        │  Review & Edit        │
        │  - Cek Kembali        │
        │  - Modifikasi jika    │
        │    perlu              │
        └───────────────────────┘
                  ↓
            [AJUKAN LAPORAN]
                  ↓
            Status: DIAJUKAN (1)
                  ↓
        ┌───────────────────────────────────┐
        │  Notifikasi: "Laporan telah       │
        │  dikirim ke teknisi"              │
        └───────────────────────────────────┘
                  ↓
            [MONITORING STATUS]
                  ↓
        ┌─────────────────────────────────────┐
        │  Track Progress:                    │
        │  - DIAJUKAN (1) → Menunggu teknisi  │
        │  - PROSES (2) → Sedang dikerjakan   │
        │  - SELESAI (3) → Selesai dikerjakan │
        │  - DIBATALKAN (4) → Dibatalkan      │
        └─────────────────────────────────────┘
                  ↓
              [SELESAI]
```

#### 12.2.2 Guru Data Flow
```
┌─────────────────────────────────────────────────────────────────┐
│                    DATA FLOW - GURU                             │
└─────────────────────────────────────────────────────────────────┘

INPUT DATA:
┌──────────────────┐    ┌──────────────────┐    ┌──────────────────┐
│  Form Laporan    │ -> │  Problem         │ -> │  Problem Items   │
│  - issue         │    │  - code (auto)   │    │  - good_id       │
│  - date          │    │  - user_id       │    │  - description   │
│  - note          │    │  - issue         │    │  - price         │
│  - items[]       │    │  - status        │    │  - quantity      │
└──────────────────┘    └──────────────────┘    └──────────────────┘
                                ↓
                        ┌──────────────────┐
                        │  Database:       │
                        │  - problems      │
                        │  - problem_items │
                        └──────────────────┘

READ DATA:
┌──────────────────┐    ┌──────────────────┐    ┌──────────────────┐
│  Dashboard       │ <- │  My Problems     │ <- │  problems Table  │
│  - Statistics    │    │  - List          │    │  WHERE user_id   │
│  - Recent        │    │  - Filter Status │    │  = current_user  │
└──────────────────┘    └──────────────────┘    └──────────────────┘

ACTIONS:
- Create: POST /problems
- Update: PATCH /problems/{id} (status 0->1)
- Read: GET /problems?user_id={me}
- Delete: DELETE /problems/{id} (status 0 only)
```

#### 12.2.3 Guru Access Matrix
| Module | Create | Read | Update | Delete |
|--------|--------|------|--------|--------|
| Inventaris (Barang) | ✗ | ✓ | ✗ | ✗ |
| Laporan Masalah | ✓ | ✓ (Own) | ✓ (Draft) | ✓ (Draft) |
| Laporan Orang Lain | ✗ | ✓ (Limited) | ✗ | ✗ |
| Dashboard | - | ✓ | - | - |

---

### 12.3 Role: TEKNISI

#### 12.3.1 Teknisi Workflow Flowchart
```
┌─────────────────────────────────────────────────────────────┐
│                         TEKNISI                              │
│              FLOW PENANGANAN KERUSAKAN                       │
└─────────────────────────────────────────────────────────────┘

                    [LOGIN SISTEM]
                            ↓
        ┌─────────────────────────────────────────┐
        │  Dashboard: Lihat Job Masuk & Aktif     │
        └─────────────────────────────────────────┘
                            ↓
            [ADA LAPORAN BARU? - Status DIAJUKAN (1)]
                    /              \
                  YES               NO
                  /                  \
        ┌────────────────┐      [LIHAT JOB AKTIF]
        │  Review Detail  │      Status: PROSES (2)
        │  - Masalah      │             ↓
        │  - Lokasi       │      [UPDATE PROGRESS]
        │  - Barang       │             ↓
        └────────────────┘      [KERJAKAN PERBAIKAN]
                  ↓
        [TERIMA TUGAS?]
              /    \
            YES      NO
           /          \
    [ACCEPT JOB]    [ABANDON]
          ↓              ↓
  Status: PROSES (2)  Status: DIAJUKAN (1)
          ↓
┌─────────────────────────────────────┐
│  Investigasi & Perbaikan:           │
│  1. Survey lokasi                   │
│  2. Identifikasi masalah            │
│  3. Tentukan solusi                 │
└─────────────────────────────────────┘
          ↓
[BUTUH MATERIAL/PART?]
       /          \
     YES            NO
    /                \
[TAMBAH ITEM]   [KERJAKAN]
     ↓              ↓
[Input Estimasi] [PERBAIKAN]
- good_id         ↓
- description     ↓
- price       ┌─────────────────┐
- quantity    │ Update Progress │
              │ & Catatan       │
              └─────────────────┘
                    ↓
              [SELESAI PEKERJAAN?]
                    /          \
                  YES            NO
                 /                \
        [FINISH & AJUKAN]      [CONTINUE WORK]
              ↓                     ↓
    Status: SELESAI (3)       Status: PROSES (2)
              ↓
┌─────────────────────────────────────┐
│  Ajukan ke Management               │
│  - Total biaya                      │
│  - Detail pekerjaan                 │
│  - Rekomendasi                      │
└─────────────────────────────────────┘
              ↓
    Status: WAITING MGMT (5)
              ↓
        [MONITORING APPROVAL]
              ↓
┌─────────────────────────────────────────┐
│  Track Approval Status:                 │
│  - Management Approved                  │
│  - Admin Approved                       │
│  - Finance Approved                     │
│  = COMPLETED                            │
└─────────────────────────────────────────┘
              ↓
          [SELESAI]
```

#### 12.3.2 Teknisi Data Flow
```
┌─────────────────────────────────────────────────────────────────┐
│                   DATA FLOW - TEKNISI                           │
└─────────────────────────────────────────────────────────────────┘

READ DATA:
┌──────────────────┐    ┌──────────────────┐    ┌──────────────────┐
│  Dashboard       │ <- │  Assigned Jobs   │ <- │  problems Table  │
│  - Job Masuk     │    │  - Status 1,2    │    │  WHERE status    │
│  - Job Aktif     │    │  - By Me         │    │  IN (1,2,3,5)    │
└──────────────────┘    └──────────────────┘    └──────────────────┘
                                ↓
                        ┌──────────────────┐
                        │  Problem Detail  │
                        │  - Items         │
                        │  - Goods Info    │
                        └──────────────────┘

WRITE DATA:
┌──────────────────┐    ┌──────────────────┐
│  Accept Job      │ -> │  Update Problem  │
│  - technician_id │    │  - status: 1->2  │
└──────────────────┘    └──────────────────┘
                                ↓
┌──────────────────┐    ┌──────────────────┐    ┌──────────────────┐
│  Update Work     │ -> │  Problem Items   │ -> │  Total Cost      │
│  - Add items     │    │  - good_id       │    │  Calculation     │
│  - Update notes  │    │  - price         │    └──────────────────┘
└──────────────────┘    │  - quantity      │
                        └──────────────────┘
                                ↓
┌──────────────────┐    ┌──────────────────┐
│  Finish Job      │ -> │  Update Problem  │
│  - status: 2->3  │    │  - status: 3->5  │
└──────────────────┘    └──────────────────┘

ACTIONS:
- Accept: PATCH /problems/{id}/accept (status 1->2)
- Update: PATCH /problems/{id} (add items)
- Finish: PATCH /problems/{id}/finish (status 2->3)
- Mgmt Approval: PATCH /problems/{id}/management (status 3->5)
- Cancel: PATCH /problems/{id}/cancel (status 2->1)
```

#### 12.3.3 Teknisi Access Matrix
| Module | Create | Read | Update | Delete |
|--------|--------|------|--------|--------|
| Inventaris (Barang) | ✗ | ✓ | ✗ | ✗ |
| Laporan Masalah | ✗ | ✓ (Assigned) | ✓ (Assigned) | ✗ |
| Problem Items | ✓ | ✓ | ✓ | ✓ |
| Dashboard | - | ✓ | - | - |

---

### 12.4 Role: ADMIN

#### 12.4.1 Admin Workflow Flowchart
```
┌─────────────────────────────────────────────────────────────┐
│                          ADMIN                               │
│               FLOW KOORDINASI & APPROVAL                    │
└─────────────────────────────────────────────────────────────┘

                    [LOGIN SISTEM]
                            ↓
        ┌─────────────────────────────────────────┐
        │  Dashboard: Overview Semua Aktivitas    │
        └─────────────────────────────────────────┘
                            ↓
        ┌─────────────────────────────────────────────────┐
        │  Monitor:                                       │
        │  - Semua Laporan (All Status)                  │
        │  - Job Teknisi Aktif                           │
        │  - Pending Approvals                           │
        └─────────────────────────────────────────────────┘
                            ↓
                [ADA LAPORAN BARU?]
                        /    \
                      YES      NO
                     /          ┌──────────────────────┐
                    /            │  Monitoring:        │
        ┌─────────────────┐     │  - Track Progress    │
        │  Review Laporan │     │  - Koordinasi        │
        │  - Validasi     │     └──────────────────────┘
        │  - Prioritas    │              ↓
        └─────────────────┘         [JOB SELESAI?]
                    ↓                    /    \
            [KOORDINASI TEKNISI]       YES      NO
                    ↓                  /        ┌────────────┐
        ┌─────────────────────┐   [REVIEW]      │ Monitoring │
        │  Assign/Rekomendasikan│       ↓        │ Continue   │
        │  teknisi jika        │  [CEK HASIL]    └────────────┘
        │  perlu              │       ↓
        └─────────────────────┘  ┌───────────────────┐
                    ↓          │  Verifikasi:      │
        ┌─────────────────────┐ │  - Pekerjaan      │
        │  Track Progress     │ │  - Kualitas       │
        │  - Follow up        │ │  - Biaya          │
        │  - Update status    │ └───────────────────┘
        └─────────────────────┘           ↓
                    ↓            [APPROVE/REVIEW]
        ┌─────────────────────┐             ↓
        │  Koordinasi Dept    │    [APPROVE HASIL?]
        │  - Management       │          /    \
        │  - Keuangan         │        YES      NO
        └─────────────────────┘        /        ┌──────────────┐
                                 [APPROVE]    │ Request      |
                                     ↓         │ Revision     |
        ┌─────────────────────────────┐       └──────────────┘
        │  Update Problem             │              ↓
        │  - admin_id                 │        [NOTIFIKASI]
        │  - status: 3->6             │        Teknisi untuk
        └─────────────────────────────┘        revisi
                                      ↓
                            [MONITORING LANJUTAN]
                                      ↓
                        [LAPORAN & STATISTIK]
                                      ↓
                                  [SELESAI]
```

#### 12.4.2 Admin Data Flow
```
┌─────────────────────────────────────────────────────────────────┐
│                    DATA FLOW - ADMIN                            │
└─────────────────────────────────────────────────────────────────┘

READ DATA:
┌──────────────────┐    ┌──────────────────┐    ┌──────────────────┐
│  Dashboard       │ <- │  All Problems    │ <- │  problems Table  │
│  - All Stats     │    │  - All Status    │    │  No filters      │
│  - Overview      │    │  - By Role       │    │                  │
└──────────────────┘    └──────────────────┘    └──────────────────┘
                                ↓
                        ┌──────────────────┐
                        │  Users Activity  │
                        │  - Tech progress │
                        │  - Approval queue│
                        └──────────────────┘

WRITE DATA:
┌──────────────────┐    ┌──────────────────┐
│  Approve Work    │ -> │  Update Problem  │
│  - admin_id      │    │  - admin_id      │
└──────────────────┘    │  - status: 3->6  │
                        └──────────────────┘
                                ↓
                        ┌──────────────────┐
                        │  Notify Next     │
                        │  - Finance       │
                        └──────────────────┘

ACTIONS:
- Read All: GET /problems (no filter)
- Approve: PATCH /problems/{id}/approve (status 3->6)
- Monitor: GET /problems?status=3 (finished jobs)
- Coordinate: Manual communication with tech/mgmt/finance
```

#### 12.4.3 Admin Access Matrix
| Module | Create | Read | Update | Delete |
|--------|--------|------|--------|--------|
| Inventaris (Barang) | ✓ | ✓ | ✓ | ✓ |
| Laporan Masalah | ✓ | ✓ | ✓ | ✓ |
| Problem Items | ✓ | ✓ | ✓ | ✓ |
| Users | ✓ | ✓ | ✓ | ✓ |
| Locations | ✓ | ✓ | ✓ | ✓ |
| Dashboard | - | ✓ | - | - |
| Reports | - | ✓ | - | - |

---

### 12.5 Role: LEMBAGA/MANAJEMEN

#### 12.5.1 Management Workflow Flowchart
```
┌─────────────────────────────────────────────────────────────┐
│                    LEMBAGA/MANAJEMEN                         │
│               FLOW APPROVAL STRATEGIS                        │
└─────────────────────────────────────────────────────────────┘

                    [LOGIN SISTEM]
                            ↓
        ┌─────────────────────────────────────────┐
        │  Dashboard: Overview Strategis          │
        └─────────────────────────────────────────┘
                            ↓
        ┌─────────────────────────────────────────────────┐
        │  Review:                                         │
        │  - Laporan yang selesai dikerjakan (Status 3)   │
        │  - Approval dari Admin                          │
        │  - Total biaya & impact                         │
        └─────────────────────────────────────────────────┘
                            ↓
            [ADA PERSETUJUAN DIPERLUKAN?]
                        /    \
                      YES      NO
                     /          ┌──────────────────┐
                    /            │  Monitoring:     │
        ┌─────────────────┐     │  - Statistics    │
        │  Review Detail  │     │  - Trends        │
        │  - Pekerjaan    │     │  - Reports       │
        │  - Biaya        │     └──────────────────┘
        │  - Impact       │              ↓
        │  - Urgensi      │        [GENERATE LAPORAN]
        └─────────────────┘              ↓
                    ↓            ┌──────────────────┐
        ┌─────────────────────┐   │  Export Data    │
        │  Analisis & Putusan │   │  - PDF          │
        │  - Bandingkan       │   │  - Excel        │
        │    dengan budget    │   └──────────────────┘
        │  - Prioritas       │              ↓
        └─────────────────────┘        [DECISION MAKING]
                    ↓                      ↓
        ┌─────────────────────┐    ┌─────────────────────┐
        │  Approve / Reject   │    │  Lihat Statistik    │
        └─────────────────────┘    │  - Barang rusak     │
                    ↓              │  - Total biaya      │
        [APPROVE?]            └─────────────────────┘
          /    \                          ↓
        YES      NO                      ↓
         /        ┌──────────────┐   [MONITORING]
    [APPROVE]    │ Request      │       ↓
        ↓         │ Info tambahan│  [ANALISIS TREND]
    ┌───────────────┐  └──────────────┘        ↓
    │ Update Problem│          ↓          [PERENCANAAN]
    │ - mgmt_id     │   [NOTIFIKASI]          ↓
    │ - status 5->5 │   Teknisi/Admin      [BUDGETING]
    │ (lock)        │   untuk revisi         ↓
    └───────────────┘            ↓          [SELESAI]
            ↓              [MONITORING]
    [NOTIFIKASI KE]             ↓
    - Admin                    ↓
    - Keuangan            [LANJUTAN KE]
    - Teknisi             APPROVAL BERIKUTNYA]
                            ↓
                        ┌───────────────────┐
                        │ Finance Approval  │
                        │ (Next in line)    │
                        └───────────────────┘
```

#### 12.5.2 Management Data Flow
```
┌─────────────────────────────────────────────────────────────────┐
│                 DATA FLOW - MANAGEMENT                          │
└─────────────────────────────────────────────────────────────────┘

READ DATA:
┌──────────────────┐    ┌──────────────────┐    ┌──────────────────┐
│  Dashboard       │ <- │  Finished Jobs   │ <- │  problems Table  │
│  - High-level    │    │  - Status: 3     │    │  WHERE status    │
│  - Statistics    │    │  - Admin done    │    │  = 3             │
└──────────────────┘    └──────────────────┘    └──────────────────┘
                                ↓
                        ┌──────────────────┐
                        │  Cost Analysis   │
                        │  - Total per job │
                        │  - Monthly trend │
                        └──────────────────┘

WRITE DATA:
┌──────────────────┐    ┌──────────────────┐
│  Approve Budget  │ -> │  Update Problem  │
│  - management_id │    │  - management_id │
└──────────────────┘    │  - status: 5     │
                        └──────────────────┘
                                ↓
                        ┌──────────────────┐
                        │  Notify Finance  │
                        │  for final apprv │
                        └──────────────────┘

ACTIONS:
- Read: GET /problems?status=3
- Approve: PATCH /problems/{id}/approve (status 5->5, lock mgmt_id)
- Print: GET /problems/{id}/print
- Reports: GET /reports
```

#### 12.5.3 Management Access Matrix
| Module | Create | Read | Update | Delete |
|--------|--------|------|--------|--------|
| Inventaris (Barang) | ✗ | ✓ | ✗ | ✗ |
| Laporan Masalah | ✗ | ✓ | ✗ | ✗ |
| Problem Items | ✗ | ✓ | ✗ | ✗ |
| Dashboard | - | ✓ | - | - |
| Reports | - | ✓ | - | - |
| Approvals | ✗ | ✓ | ✓ | ✗ |

---

### 12.6 Role: KEUANGAN

#### 12.6.1 Keuangan Workflow Flowchart
```
┌─────────────────────────────────────────────────────────────┐
│                       KEUANGAN                               │
│                FLOW APPROVAL PEMBAYARAN                     │
└─────────────────────────────────────────────────────────────┘

                    [LOGIN SISTEM]
                            ↓
        ┌─────────────────────────────────────────┐
        │  Dashboard: Overview Keuangan           │
        └─────────────────────────────────────────┘
                            ↓
        ┌─────────────────────────────────────────────────┐
        │  Review:                                         │
        │  - Jobs yang sudah approved by Management (5)   │
        │  - Jobs yang sudah approved by Admin (6)        │
        │  - Total biaya pembayaran                       │
        └─────────────────────────────────────────────────┘
                            ↓
            [ADA PEMBAYARAN DIPERLUKAN?]
                        /    \
                      YES      NO
                     /          ┌──────────────────┐
                    /            │  Monitoring:     │
        ┌─────────────────┐     │  - Cash flow     │
        │  Review Detail  │     │  - Budget        │
        │  - Total biaya  │     │  - Reports       │
        │  - Bukti kerja  │     └──────────────────┘
        │  - Invoice      │              ↓
        │  - Budget check│        [GENERATE LAPORAN]
        └─────────────────┘              ↓
                    ↓            ┌──────────────────┐
        ┌─────────────────────┐   │  Export Data    │
        │  Verifikasi:        │   │  - Excel        │
        │  - Cek budget       │   │  - PDF          │
        │  - Validasi amount  │   └──────────────────┘
        │  - Approval chain   │              ↓
        └─────────────────────┘        [RECORD KEUANGAN]
                    ↓                      ↓
        ┌─────────────────────┐    ┌─────────────────────┐
        │  Process Payment    │    │  Tracking:          │
        │  - Approve amount    │    │  - Monthly expenses │
        │  - Record payment    │    │  - Per category     │
        └─────────────────────┘    └─────────────────────┘
                    ↓                      ↓
        ┌─────────────────────┐    ┌─────────────────────┐
        │  Approve / Reject   │    │  Financial Reports  │
        └─────────────────────┘    └─────────────────────┘
                    ↓
            [APPROVE?]
              /    \
            YES      NO
           /        ┌──────────────┐
      [APPROVE]    │ Reject       │
          ↓         │ Reason       │
  ┌───────────────┐  └──────────────┘
  │ Update        │          ↓
  │ - finance_id  │   [NOTIFIKASI]
  │ - status 3->3 │   Admin/Mgmt
  └───────────────┘   untuk revisi
          ↓
┌─────────────────────┐
│ PROCESS PEMBAYARAN  │
│ - Transfer          │
│ - Cash              │
│ - Record            │
└─────────────────────┘
          ↓
    [MARK AS PAID]
          ↓
    Status: COMPLETED
          ↓
    [NOTIFIKASI SELESAI]
    - Teknisi
    - Admin
    - Pelapor
          ↓
      [SELESAI]
```

#### 12.6.2 Keuangan Data Flow
```
┌─────────────────────────────────────────────────────────────────┐
│                   DATA FLOW - KEUANGAN                          │
└─────────────────────────────────────────────────────────────────┘

READ DATA:
┌──────────────────┐    ┌──────────────────┐    ┌──────────────────┐
│  Dashboard       │ <- │  Approved Jobs   │ <- │  problems Table  │
│  - Financial     │    │  - Status: 3     │    │  WHERE status    │
│  - Budget        │    │  - Mgmt approved │    │  = 3             │
└──────────────────┘    └──────────────────┘    └──────────────────┘
                                ↓
                        ┌──────────────────┐
                        │  Payment Due     │
                        │  - Total amount  │
                        │  - Per month     │
                        └──────────────────┘

WRITE DATA:
┌──────────────────┐    ┌──────────────────┐
│  Approve Payment │ -> │  Update Problem  │
│  - finance_id    │    │  - finance_id    │
└──────────────────┘    │  - status: 3     │
                        └──────────────────┘
                                ↓
                        ┌──────────────────┐
                        │  Record Payment  │
                        │  - Transaction   │
                        │  - Update budget │
                        └──────────────────┘

ACTIONS:
- Read: GET /problems?status=3&mgmt_approved=true
- Approve: PATCH /problems/{id}/approve (add finance_id)
- Reports: GET /reports/financial
- Export: GET /reports/financial/export
```

#### 12.6.3 Keuangan Access Matrix
| Module | Create | Read | Update | Delete |
|--------|--------|------|--------|--------|
| Inventaris (Barang) | ✗ | ✓ | ✗ | ✗ |
| Laporan Masalah | ✗ | ✓ | ✗ | ✗ |
| Problem Items | ✗ | ✓ | ✗ | ✗ |
| Dashboard | - | ✓ | - | - |
| Financial Reports | - | ✓ | - | - |
| Approvals | ✗ | ✓ | ✓ | ✗ |

---

### 12.7 Complete System Data Flow

#### 12.7.1 Overall Data Flow Diagram
```
┌─────────────────────────────────────────────────────────────────────┐
│                   COMPLETE DATA FLOW SYSTEM                        │
└─────────────────────────────────────────────────────────────────────┘

                    DATABASE SCHEMA
                    ==============

┌──────────────┐   ┌──────────────┐   ┌──────────────┐
│   USERS      │   │   ROLES      │   │   GOODS      │
│ - id         │   │ - id         │   │ - id         │
│ - name       │   │ - name       │   │ - code       │
│ - email      │   │ - permissions│   │ - name       │
│ - role_id    │   └──────────────┘   │ - location_id│
└──────────────┘                       │ - merk       │
       │                                │ - status     │
       │                                └──────────────┘
       │                                       │
       │                               ┌───────┴───────┐
       │                               │               │
       ▼                               ▼               ▼
┌──────────────┐   ┌──────────────┐   ┌──────────────┐
│   PROBLEMS   │   │ PROBLEM_     │   │  LOCATIONS   │
│ - id         │◄──┤   ITEMS      │   │ - id         │
│ - code       │   │ - id         │   │ - name       │
│ - user_id    │   │ - problem_id │   └──────────────┘
│ - status     │   │ - good_id    │
│ - issue      │   │ - price      │
│ - date       │   │ - quantity   │
│ - tech_id    │   └──────────────┘
│ - mgmt_id    │           │
│ - admin_id   │           │
│ - finance_id │           │
└──────────────┘           │
       │                   │
       └───────────────────┘
               │
               ▼
      ┌──────────────────┐
      │ PROBLEM_         │
      │ APPROVALS        │
      │ - problem_id     │
      │ - user_id        │
      │ - status         │
      └──────────────────┘

WORKFLOW TRANSITIONS:
====================

Status 0 (DRAFT)
    ↓ [GURU submits]
Status 1 (DIAJUKAN)
    ↓ [TEKNISI accepts]
Status 2 (PROSES)
    ↓ [TEKNISI finishes]
Status 3 (SELESAI DIKERJAKAN)
    ├─→ [ADMIN approves] → Status 6 (WAITING ADMIN)
    ├─→ [MGMT approves] → Status 5 (WAITING MGMT)
    └─→ [FINANCE approves] → Status 7 (WAITING FINANCE)
Status 4 (DIBATALKAN) [Anytime]
Status 5,6,7 → COMPLETED [All approved]
```

#### 12.7.2 API Request Flow Matrix
```
┌─────────────────────────────────────────────────────────────────────┐
│                    API REQUEST FLOW MATRIX                         │
└─────────────────────────────────────────────────────────────────────┘

┌──────────┬─────────────┬──────────────────────────────────────────┐
│  ROLE    │  ACTION     │  REQUEST/RESPONSE FLOW                   │
├──────────┼─────────────┼──────────────────────────────────────────┤
│          │  Login      │  POST /login → {token, user}             │
│  GURU    │  Create     │  POST /problems → {problem}              │
│          │  Submit     │  PATCH /problems/{id}/submit → {status}  │
│          │  View       │  GET /problems?me → {problems[]}         │
├──────────┼─────────────┼──────────────────────────────────────────┤
│          │  Accept     │  PATCH /problems/{id}/accept → {status}  │
│  TEKNSI  │  Update     │  PATCH /problems/{id} → {problem}       │
│          │  Finish     │  PATCH /problems/{id}/finish → {status}  │
│          │  Cancel     │  PATCH /problems/{id}/cancel → {status}  │
├──────────┼─────────────┼──────────────────────────────────────────┤
│  ADMIN   │  Approve    │  PATCH /problems/{id}/approve → {status} │
│          │  Manage     │  CRUD /goods, /locations, /users        │
├──────────┼─────────────┼──────────────────────────────────────────┤
│  MGMT    │  Approve    │  PATCH /problems/{id}/approve → {status} │
│          │  Reports    │  GET /reports → {data}                  │
├──────────┼─────────────┼──────────────────────────────────────────┤
│  KEUANGAN│  Approve    │  PATCH /problems/{id}/approve → {status} │
│          │  Financial  │  GET /reports/financial → {data}        │
└──────────┴─────────────┴──────────────────────────────────────────┘
```

---

## 13. Conclusion

Sistem SARPRAS yang sudah ada telah memiliki fondasi yang kuat untuk manajemen sarana prasarana di institusi pendidikan. Dengan enhancement yang direkomendasikan, sistem dapat menjadi lebih powerful dan memberikan value yang lebih besar kepada institusi.

### Key Strengths (Current System)
- ✅ Workflow automation yang terstruktur
- ✅ Role-based access control yang komprehensif
- ✅ Manajemen inventaris yang efisien
- ✅ Tracking system yang transparan

### Recommended Next Steps
1. Implement notification system
2. Improve mobile experience
3. Add advanced reporting
4. Consider preventive maintenance module

---

**Document Version:** 1.0
**Last Updated:** 2025
**Status:** Active
**Prepared by:** System Analysis based on existing codebase