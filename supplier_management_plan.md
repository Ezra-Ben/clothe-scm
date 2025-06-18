# Supplier Management Module Design (Based on Existing Vendor Data)

Shared Understanding
The supplier is a validated and stored vendor. No duplication of data should occur. Supplier-specific data will be extended from the `vendors` table using relationships.



## 1.Fields & Data Schema

### Vendors Table (Already Exists)
| Field                 | Type        | Notes                         |
|----------------------|-------------|-------------------------------|
| id                   | BIGINT      | Primary Key                   |
| name                 | VARCHAR     | Vendor full name              |
| business_name        | VARCHAR     | Business entity name          |
| registration_number  | VARCHAR     | Official registration ID      |
| contact              | VARCHAR     | Email or phone                |
| product_category     | VARCHAR     | Type of products              |
| business_license_url | TEXT        | Document URL                  |
| created_at           | TIMESTAMP   | Laravel default               |
| updated_at           | TIMESTAMP   | Laravel default               |

### Suppliers Table (Extends Vendor)
| Field         | Type      | Notes                              |
|----------------|-----------|------------------------------------|
| id             | BIGINT    | Primary Key                        |
| vendor_id      | BIGINT    | FK → vendors.id                    |
| address        | TEXT      | Supplier business address          |
| added_by       | BIGINT    | FK → users.id (admin who created)  |
| created_at     | TIMESTAMP | Laravel default                    |
| updated_at     | TIMESTAMP | Laravel default                    |

### Contracts Table
| Field         | Type      | Notes                        |
|---------------|-----------|------------------------------|
| id            | BIGINT    | Primary Key                  |
| supplier_id   | BIGINT    | FK → suppliers.id            |
| file_url      | TEXT      | Contract document            |
| uploaded_by   | BIGINT    | FK → users.id (admin only)   |
| status        | ENUM      | ['active', 'expired']        |
| uploaded_at   | TIMESTAMP | Time of upload               |

### Performance Table
| Field             | Type      | Notes                           |
|------------------|-----------|---------------------------------|
| id               | BIGINT    | Primary Key                     |
| supplier_id      | BIGINT    | FK → suppliers.id               |
| performance_note | TEXT      | Admin observations              |
| rating           | INT       | 1 to 5                          |
| created_by       | BIGINT    | FK → users.id (admin only)      |
| created_at       | TIMESTAMP | Laravel default                 |

---

## 2.  Folder & File Structure (Divided by Feature)

### Views (`resources/views/supplier/`)
| Path                                           | Purpose                          |
|------------------------------------------------|----------------------------------|
| `profile.blade.php`                            | Supplier read-only profile       |
| `dashboard.blade.php`                          | Supplier dashboard               |
| `contracts/index.blade.php`                    | List supplier contracts          |
| `contracts/show.blade.php`                     | View specific contract           |
| `performance/index.blade.php`                  | Admin overview of performance    |

> *Same views reused by supplier and admin, but rendered with conditional logic to allow read-only or editable mode.*

### Controllers (`app/Http/Controllers/`)
| Controller                      | Purpose                              |
|--------------------------------|--------------------------------------|
| `SupplierController`           | View profile, dashboard              |
| `ContractController`           | Admin uploads, supplier views        |
| `PerformanceController`        | Admin notes & rating control         |

### Services (`app/Services/`)
| Service                        | Responsibility                        |
|-------------------------------|---------------------------------------|
| `SupplierService`             | Logic for profile & dashboard         |
| `ContractService`             | Handle uploads, storage, and fetch    |
| `PerformanceService`          | Record and evaluate supplier ratings  |

### Models (`app/Models/`)
| Model               | Notes                       |
|--------------------|-----------------------------|
| `Vendor`           | Already exists              |
| `Supplier`         | BelongsTo Vendor            |
| `Contract`         | BelongsTo Supplier          |
| `Performance`      | BelongsTo Supplier          |

---

## 3. Division of Work (Fullstack Development Per Each One of Us)

### Gihozo: Supplier Profile & Dashboard
- Model: `Supplier`
- Controller: `SupplierController`
- View: `profile.blade.php`, `dashboard.blade.php`
- Service: `SupplierService`

### Dilis: Contract Upload & Listing
- Model: `Contract`
- Controller: `ContractController`
- View: `contracts/index.blade.php`, `show.blade.php`
- Service: `ContractService`

###  Kristiana: Performance Review System
- Model: `Performance`
- Controller: `PerformanceController`
- View: `performance/index.blade.php`
- Service: `PerformanceService`

### Abigaba: Admin Dashboard Views
- View composition: Admin version of supplier dashboard
- Use existing blade views with conditionals
- Coordinate with Gihozo, Dilis, Kristiana for permission gates

### Mukisa: Database & Migrations
- Tables: `suppliers`, `contracts`, `performance`
- Write migrations with relationships
- Ensure seeding and foreign key constraints work

---

## Access Control (via Middleware/Gates)
| Role     | Permissions                                      |
|----------|--------------------------------------------------|
| Admin    | CRUD on everything, upload contracts             |
| Supplier | View profile, dashboard, contracts, performance  |

---


