# AGENT.md

## Product & Engineering Guidelines — Manufacturing ERP / MRP

Dokumen ini adalah pedoman utama untuk AI agent dan developer yang mengembangkan ERP modular, khususnya modul **MRP / Manufacturing**.

Tujuan utamanya adalah menjaga proyek tetap **generic, configurable, scalable, secure, maintainable, dan production-ready** tanpa mengorbankan kesederhanaan kode.

---

# 1. VISI PRODUK

Bangun ERP manufaktur yang dapat digunakan oleh banyak jenis perusahaan tanpa mengubah core business logic.

Contoh industri yang harus tetap dapat didukung:

- Footwear
- Garment
- Furniture
- Food Manufacturing
- Electronics
- Automotive
- Consumer Goods
- General Manufacturing

**Jangan membuat sistem khusus untuk satu perusahaan.**

Perusahaan seperti PT Adonia Footwear Indonesia hanya digunakan sebagai business reference / case study untuk memahami kebutuhan manufaktur.

Prinsip utama:

```text
Generic Core
    +
Configurable Metadata
    +
Company Configuration
    +
Master Data
    +
Business Rules
    =
Reusable ERP
```

---

# 2. TUJUAN SISTEM

Sistem harus membantu perusahaan:

1. merencanakan produksi;
2. menghitung kebutuhan material;
3. mengelola BOM dan Routing;
4. mengatur production order dan work order;
5. memantau penggunaan material;
6. mengintegrasikan produksi dengan inventory;
7. mengelola quality inspection;
8. menghubungkan produksi dengan maintenance;
9. menghitung biaya produksi;
10. menyediakan laporan dan KPI yang dapat dipercaya.

ERP harus mengurangi ketergantungan pada:

- Excel manual;
- dokumen kertas;
- input berulang;
- komunikasi yang tidak tercatat;
- data antar departemen yang tidak sinkron.

Nilai utama produk:

> **Semua data operasional terhubung dan dapat dipantau dari satu sumber data yang konsisten.**

---

# 3. PRINSIP PRODUK

## 3.1 Generic by Default

Jangan hardcode:

- nama perusahaan;
- plant;
- warehouse;
- production line;
- mesin;
- proses produksi;
- product type;
- status transaksi;
- reason code;
- numbering format;
- istilah industri;
- label bisnis yang dapat berbeda antar perusahaan.

Gunakan master data dan configuration.

---

## 3.2 Configuration over Hardcoding

Jika sebuah nilai dapat berbeda antar perusahaan, pertimbangkan untuk membuatnya configurable.

Contoh:

```text
Production Order
Manufacturing Order
Work Order
```

Label dapat dikonfigurasi sesuai kebutuhan tenant/company.

Contoh numbering:

```text
MO-2026-000001
PROD-JKT-000001
MFG-000001
```

Format bukan hardcoded di source code.

---

## 3.3 Business Domain over Menu

Database dan business logic harus mengikuti **domain bisnis**, bukan struktur menu UI.

Jangan membuat satu tabel hanya karena ada satu menu.

Contoh:

```text
BOM
Routing
Work Center
Production Order
Work Order
Material Consumption
Stock Movement
Production Result
```

harus dirancang berdasarkan lifecycle dan hubungan bisnisnya.

---

# 4. ARSITEKTUR TEKNOLOGI

Stack utama:

- PHP
- Laravel 12
- PostgreSQL
- Laravel Full-Stack

Gunakan kemampuan native Laravel terlebih dahulu.

Jangan menambahkan library pihak ketiga hanya karena populer atau nyaman.

Sebelum menambah dependency, pastikan:

1. Laravel tidak menyediakan solusi native;
2. dependency benar-benar menyelesaikan masalah penting;
3. maintenance project aktif dan stabil;
4. dependency tidak menciptakan coupling yang tidak perlu;
5. manfaat jangka panjang lebih besar daripada kompleksitas tambahan.

---

# 5. CLEAN CODE

## 5.1 Readability First

Kode harus mudah dibaca oleh developer lain.

Utamakan:

```text
Readable > Clever
Simple > Complex
Explicit > Magic
Maintainable > Short
```

Jangan membuat kode singkat tetapi sulit dipahami.

---

## 5.2 Single Responsibility

Satu class / method harus memiliki satu tanggung jawab utama.

Hindari service seperti:

```text
ProductionService
```

yang melakukan semuanya sekaligus:

- create order;
- issue material;
- calculate cost;
- quality inspection;
- stock update;
- notification.

Pecah berdasarkan domain/action yang jelas.

Contoh:

```text
CreateProductionOrder
ReleaseProductionOrder
IssueMaterial
RecordProductionResult
CompleteWorkOrder
CalculateProductionCost
```

---

## 5.3 Avoid Fat Controllers

Controller bertugas sebagai orchestration layer tipis.

Jangan menaruh business logic kompleks di controller.

Controller sebaiknya:

```text
Receive Request
    ↓
Validate / Authorize
    ↓
Call Application Service / Action
    ↓
Return Response
```

---

## 5.4 Validation

Pisahkan validation dari business operation bila kompleks.

Gunakan Laravel Form Request untuk request validation.

Business invariant tetap harus dijaga di application/domain layer dan database bila relevan.

Jangan hanya mengandalkan frontend validation.

---

## 5.5 Naming

Gunakan nama yang jelas dan konsisten.

Hindari:

```text
$data
$temp
$x
$foo
$process
```

kecuali scope sangat kecil dan maknanya jelas.

Lebih baik:

```text
$productionOrder
$materialRequirement
$workCenter
$stockMovement
```

Gunakan istilah domain yang konsisten di seluruh codebase.

---

# 6. DATABASE PRINCIPLES

PostgreSQL adalah source of truth untuk data ERP.

## 6.1 Relational First

Gunakan relational schema untuk data yang terstruktur dan memiliki relationship jelas.

Gunakan JSONB hanya ketika:

- struktur benar-benar dinamis;
- data bukan core relational entity;
- custom metadata memang membutuhkan fleksibilitas;
- relational modeling akan menciptakan kompleksitas yang tidak diperlukan.

Jangan menggunakan JSONB sebagai pengganti desain database yang benar.

---

## 6.2 Data Integrity

Gunakan database constraint bila memungkinkan:

- foreign key;
- unique constraint;
- check constraint;
- not null;
- composite unique constraint.

Business-critical data jangan hanya dijaga oleh application code.

---

## 6.3 Transactions

Gunakan database transaction untuk operasi yang mengubah beberapa data yang harus konsisten.

Contoh:

```text
Material Issue
    ↓
Stock Movement
    ↓
Material Consumption
```

Ketiganya harus berhasil sebagai satu operasi bisnis atau gagal sebagai satu kesatuan.

---

## 6.4 Posted Transaction

Transaksi yang sudah posted tidak boleh dihapus secara sembarangan.

Gunakan:

```text
Post
  ↓
Reverse
  ↓
Correction / New Transaction
```

Bukan:

```text
Post
  ↓
DELETE
```

ERP membutuhkan historical integrity.

---

# 7. SOURCE OF TRUTH

Setiap jenis data harus memiliki source of truth yang jelas.

Contoh:

```text
Product Master
→ Product domain

Stock Balance
→ Inventory / stock ledger

Material Consumption
→ Production material transaction

Production Output
→ Production result

Quality Result
→ Quality inspection transaction
```

Jangan menyimpan data yang sama di banyak tempat tanpa alasan yang kuat.

Jika sebuah data harus diturunkan / dihitung ulang, jelaskan sumber dan mekanismenya.

---

# 8. TRANSACTION ARCHITECTURE

Bedakan dengan jelas:

## Master Data

Contoh:

```text
Product
BOM
Routing
Work Center
Machine
UOM
Calendar
```

## Planning Data

```text
Demand
MPS
MRP
Material Requirement
```

## Transaction Data

```text
Production Order
Work Order
Material Issue
Production Result
Quality Inspection
```

## Ledger / History

```text
Stock Movement
Cost Transaction
Status History
Audit Log
```

Master data dapat berubah sesuai aturan.

Posted transactional history harus diperlakukan jauh lebih ketat.

---

# 9. WORKFLOW

Gunakan state transition yang jelas.

Contoh:

```text
Draft
  ↓
Planned
  ↓
Released
  ↓
In Progress
  ↓
Completed
  ↓
Closed
```

Dengan kemungkinan:

```text
Draft → Cancelled
Planned → Cancelled
Released → Cancelled
```

Tidak semua status boleh berpindah ke semua status.

Setiap transition harus memiliki business rule.

Status yang dapat dikonfigurasi harus dipisahkan dari aturan inti yang memang membutuhkan state machine tetap.

---

# 10. AUDITABILITY

ERP harus dapat menjawab:

- siapa melakukan perubahan;
- kapan perubahan dilakukan;
- apa yang berubah;
- dari nilai apa menjadi nilai apa;
- siapa yang approve;
- siapa yang release;
- siapa yang cancel;
- dokumen sumbernya apa.

Gunakan audit trail untuk transaksi kritis.

Jangan mengorbankan auditability demi CRUD sederhana.

---

# 11. SECURITY

Security adalah requirement inti, bukan fitur tambahan.

## 11.1 Authentication

Gunakan mekanisme authentication Laravel yang sesuai dengan arsitektur aplikasi.

Password harus selalu disimpan menggunakan hashing yang aman.

Jangan pernah menyimpan:

- plaintext password;
- secret key di repository;
- token sensitif di source code.

---

## 11.2 Authorization

Authorization harus dilakukan pada backend.

Jangan mempercayai frontend.

Gunakan policy / authorization mechanism Laravel untuk memastikan user hanya dapat melakukan tindakan yang diizinkan.

Contoh action permission:

```text
view
create
update
delete
submit
approve
reject
release
post
reverse
cancel
close
```

---

## 11.3 Multi-Company Isolation

Data antar company harus terisolasi.

Jika sistem mendukung:

```text
Company
  ↓
Plant
  ↓
Warehouse
```

maka akses user harus menghormati scope tersebut.

Jangan bergantung pada filter frontend.

---

## 11.4 Input Security

Semua input user harus divalidasi dan dinormalisasi sesuai kebutuhan.

Lindungi dari:

- SQL injection;
- XSS;
- CSRF;
- mass assignment;
- insecure direct object reference;
- privilege escalation;
- broken access control;
- unsafe file upload.

Gunakan fitur keamanan native Laravel dan PostgreSQL terlebih dahulu.

---

## 11.5 File Upload

Untuk file dan attachment:

- validasi MIME/type;
- validasi ukuran;
- generate safe filename;
- jangan mempercayai extension dari user;
- simpan di lokasi yang sesuai;
- batasi executable content;
- gunakan authorization ketika file diakses.

---

## 11.6 Secrets

Jangan commit:

```text
.env
API keys
password
private keys
production credentials
```

Gunakan environment variables / secret management yang sesuai deployment.

---

# 12. SECURE ERP TRANSACTIONS

Transaksi penting harus melewati sequence yang aman.

Contoh Material Issue:

```text
Validate Permission
    ↓
Validate Document State
    ↓
Validate Stock
    ↓
Open DB Transaction
    ↓
Create Material Consumption
    ↓
Create Stock Movement
    ↓
Update Stock Ledger / Balance
    ↓
Commit
```

Jika salah satu bagian gagal, transaction harus rollback.

---

# 13. CONCURRENCY

Sistem ERP digunakan banyak user secara bersamaan.

Pertimbangkan race condition untuk:

- stock issue;
- stock receipt;
- production result;
- numbering;
- approval;
- reservation;
- material allocation.

Gunakan PostgreSQL transaction dan locking strategy bila dibutuhkan.

Jangan mengasumsikan dua request selalu berjalan satu per satu.

---

# 14. NUMBERING

Numbering dokumen harus configurable.

Dukungan dapat mencakup:

```text
Prefix
Company
Plant
Year
Month
Sequence
Padding
```

Contoh:

```text
MO-JKT-202608-000001
```

Format di atas hanya contoh.

Jangan hardcode format dalam code.

Numbering harus aman terhadap concurrent requests dan tidak menghasilkan duplicate number.

---

# 15. METADATA & CUSTOMIZATION

ERP harus dapat dikustomisasi tanpa mengubah core code untuk kebutuhan sederhana.

Contoh:

```text
Label
Status
Reason Code
Product Type
Production Process
Numbering
Custom Field
```

Tetapi customization tidak boleh membuat semua data menjadi dynamic tanpa struktur.

Prioritas:

```text
Core relational model
    ↓
Configuration tables
    ↓
Custom metadata
    ↓
JSONB only when justified
```

---

# 16. PERFORMANCE

Jangan melakukan optimasi sebelum ada masalah nyata, tetapi desain harus menghindari pola yang jelas buruk.

Perhatikan:

- N+1 query;
- missing indexes;
- unnecessary queries;
- loading data berlebihan;
- large reports tanpa pagination/filter;
- unbounded relationship loading;
- expensive calculations pada setiap request.

Gunakan:

- eager loading secara tepat;
- indexes;
- pagination;
- query optimization;
- caching hanya jika diperlukan;
- queue untuk pekerjaan asynchronous yang sesuai.

---

# 17. OBSERVABILITY

Sistem production harus mudah dipantau.

Perhatikan:

- application logs;
- failed jobs;
- database errors;
- transaction failures;
- security events;
- critical business events.

Log harus membantu debugging tetapi jangan membocorkan:

- password;
- token;
- secret;
- data sensitif yang tidak diperlukan.

---

# 18. TESTING

Business-critical logic harus dapat diuji.

Prioritaskan testing untuk:

- MRP calculation;
- BOM explosion;
- stock validation;
- production order lifecycle;
- material consumption;
- production result;
- costing;
- approval;
- authorization;
- reversal;
- concurrency-sensitive transaction.

Utamakan test untuk business behavior daripada hanya coverage angka.

---

# 19. UI / UX DESIGN PRINCIPLE

Desain aplikasi terinspirasi dari prinsip **Apple / macOS** dalam hal kualitas pengalaman, bukan menyalin tampilan Apple.

Prinsip:

```text
Clean
Calm
Clear
Consistent
Focused
Minimal
Fast
```

## 19.1 Minimalism

Jangan menampilkan semua data sekaligus.

Tampilkan informasi yang paling penting terlebih dahulu.

---

## 19.2 Hierarchy

Pengguna harus dapat memahami:

```text
What is happening?
What needs attention?
What can I do?
What happened before?
```

dengan cepat.

---

## 19.3 Consistency

Komponen dan interaction pattern harus konsisten antar modul.

Contoh:

```text
Create
Edit
Submit
Approve
Reject
Post
Reverse
Cancel
```

harus memiliki behavior yang konsisten.

---

## 19.4 Subtle Interaction

Gunakan animation secara minimal.

Animation harus membantu:

- feedback;
- orientation;
- state transition;
- loading;
- confirmation.

Jangan menggunakan animasi hanya untuk dekorasi.

---

## 19.5 Information Density

ERP membutuhkan data density yang cukup tinggi, tetapi jangan membuat interface terasa sesak.

Gunakan hierarchy, whitespace, grouping, filtering, dan progressive disclosure.

---

# 20. ACCESSIBILITY

UI harus mempertimbangkan:

- keyboard navigation;
- readable contrast;
- focus state;
- clear error message;
- semantic controls;
- usable form validation.

Jangan mengandalkan warna sebagai satu-satunya indikator status.

---

# 21. ERROR HANDLING

Error message harus:

- jelas;
- actionable;
- tidak membocorkan detail internal;
- tetap membantu user memahami apa yang gagal.

Jangan menampilkan raw exception kepada user production.

Contoh buruk:

```text
SQLSTATE[23505] duplicate key...
```

Contoh baik:

```text
Nomor Production Order sudah digunakan. Silakan coba lagi.
```

Detail teknis tetap dicatat pada server log.

---

# 22. DEVELOPMENT WORKFLOW

Sebelum membuat fitur baru:

```text
Understand Domain
    ↓
Define Requirement
    ↓
Define Business Rules
    ↓
Define Data Model
    ↓
Define Workflow
    ↓
Implement
    ↓
Test
    ↓
Review
```

Jangan langsung membuat CRUD hanya karena ada nama menu.

---

# 23. FEATURE DEVELOPMENT RULE

Setiap fitur harus memiliki minimal:

```text
Purpose
Actor
Input
Business Rules
Validation
State
Output
Permission
Audit Requirement
Data Relation
Failure Case
```

Untuk transaksi penting tambahkan:

```text
Source Document
Posting Rule
Reversal Rule
Concurrency Consideration
```

---

# 24. API / BACKEND CONTRACT

Jika API digunakan antar modul atau client:

- gunakan naming konsisten;
- response structure konsisten;
- validation error konsisten;
- authorization wajib;
- pagination konsisten;
- filtering/sorting harus terkontrol;
- jangan expose database structure secara mentah jika tidak diperlukan.

Jangan membuat endpoint yang hanya memindahkan logic dari controller ke route tetapi tetap mencampur domain logic.

---

# 25. MODULAR ERP PRINCIPLE

ERP terdiri dari banyak domain.

Contoh:

```text
Core
HRIS
CRM
Purchasing
Inventory
MRP
Quality
Maintenance
Finance
```

Module boleh saling terhubung, tetapi jangan membuat semua module menjadi tightly coupled.

Gunakan contract dan domain boundary yang jelas.

Contoh:

```text
Sales
   ↓ Demand
MRP
   ↓ Material Requirement
Purchasing

MRP
   ↓ Material Movement
Inventory

MRP
   ↓ Employee Reference
HR

MRP
   ↓ Inspection Request
Quality

MRP
   ↓ Equipment Availability
Maintenance
```

---

# 26. DEPENDENCY RULE

Ketergantungan harus mengarah ke domain yang stabil.

Hindari circular dependency seperti:

```text
MRP → Inventory → MRP → Inventory
```

Gunakan transaction/event/contract yang jelas bila diperlukan.

---

# 27. DOMAIN LANGUAGE

Gunakan istilah domain yang konsisten.

Contoh:

```text
Production Order
Work Order
Material Requirement
Material Consumption
Production Result
Stock Movement
Quality Inspection
```

Jangan menggunakan beberapa istilah untuk hal yang sama tanpa alasan.

Jika perusahaan membutuhkan label berbeda, ubah melalui metadata/configuration, bukan mengganti nama entity core.

---

# 28. DATA LIFECYCLE

Setiap entity penting harus memiliki lifecycle yang jelas.

Contoh:

```text
Draft
→ Submitted
→ Approved
→ Released
→ Completed
→ Closed
```

Untuk master data:

```text
Draft
→ Active
→ Inactive
```

Jangan menggunakan DELETE untuk semua skenario.

---

# 29. REPORTING PRINCIPLE

Report harus memiliki sumber data yang jelas.

Jangan membuat angka laporan berdasarkan field yang tidak memiliki definisi bisnis yang jelas.

Setiap KPI harus memiliki:

```text
Definition
Formula
Source Data
Period
Filter
Aggregation
```

Contoh:

```text
Production Efficiency
=
Actual Good Output / Planned Output × 100%
```

Formula harus didokumentasikan jika digunakan sebagai KPI resmi.

---

# 30. AI AGENT BEHAVIOR

Ketika AI agent mengembangkan project ini, AI harus:

1. memahami existing architecture sebelum membuat perubahan;
2. mengikuti pola kode yang sudah ada jika pola tersebut sehat;
3. tidak membuat file atau abstraction yang tidak diperlukan;
4. tidak menambahkan package baru tanpa alasan;
5. tidak mengubah business behavior secara diam-diam;
6. menjaga backward compatibility bila memungkinkan;
7. mempertahankan data integrity;
8. mempertimbangkan authorization untuk setiap fitur baru;
9. menambahkan validation pada input;
10. mempertimbangkan transaction untuk operasi multi-table;
11. menambahkan test untuk business-critical behavior;
12. menjaga code tetap readable;
13. menghindari premature optimization;
14. tidak menghapus data production tanpa mekanisme aman;
15. tidak meng-hardcode kebutuhan perusahaan tertentu.

---

# 31. DEFINITION OF DONE

Sebuah fitur belum dianggap selesai hanya karena halaman dan CRUD sudah bekerja.

Minimal harus terpenuhi:

```text
Requirement
✓

Business Logic
✓

Validation
✓

Authorization
✓

Database Integrity
✓

Error Handling
✓

Audit Requirement
✓

Transaction Safety
✓

Tests for Critical Logic
✓

Documentation
✓
```

---

# 32. PRIORITAS ENGINEERING

Jika terdapat trade-off, gunakan urutan prioritas:

```text
1. Security
2. Data Integrity
3. Correct Business Logic
4. Maintainability
5. Reliability
6. Performance
7. Simplicity
8. Visual Polish
```

ERP yang terlihat bagus tetapi menghasilkan data produksi yang salah adalah produk yang gagal.

---

# 33. GOLDEN RULES

Selalu ingat prinsip berikut:

```text
Do not hardcode the company.
Do not duplicate the source of truth.
Do not delete posted transactions.
Do not trust the frontend for authorization.
Do not put complex business logic in controllers.
Do not use JSONB as a substitute for relational design.
Do not add dependencies without justification.
Do not sacrifice data integrity for convenience.
Do not over-engineer simple problems.
Do not make UI decisions drive the database model.
```

Dan yang paling penting:

> **Build a generic manufacturing ERP core that can adapt to companies, industries, plants, and processes through configuration and master data rather than custom code.**

---

# 34. FINAL PRODUCT PRINCIPLE

Produk ini harus terasa seperti sistem enterprise modern yang:

```text
Simple to use
        ↓
Powerful underneath
        ↓
Strict with data
        ↓
Flexible in configuration
        ↓
Secure by default
        ↓
Easy to maintain
        ↓
Ready to scale
```

Desain boleh minimal dan elegan seperti prinsip pengalaman macOS, tetapi **arsitektur, database, workflow, security, dan business logic harus mengikuti kebutuhan ERP enterprise yang sebenarnya**.
