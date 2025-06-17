"# Vendor Data Layer Documentation" 
# Vendor Data Layer Implementation

## Overview
Added the data layer for **Vendor management**, including:
-The Vendor model (app/Models/Vendor.php) uses the vendors table created by the migration.
-Eloquent automatically links the model to the table (convention: Vendor → vendors).
- A `Vendor` model to represent vendor entities.
- A database migration to create the `vendors` table.

## Files Changed/Added

### 1. `app/Models/Vendor.php`
- **Purpose**: Defines the `Vendor` model for interacting with the `vendors` table.
- **Key Features**:
  - Uses Laravel’s Eloquent ORM.
  - Includes fillable fields (e.g., `name`, `email`, `address`).
  - Relationships (e.g., `hasMany(Product::class)` if applicable).

### 2. `database/migrations/2025_06_12_144618_create_vendors_table.php`
- **Purpose**: Creates the `vendors` table schema.
- **Schema**:
  ```php
  Schema::create('vendors', function (Blueprint $table) {
      $table->id();
      $table->string('name');
      $table->string('email')->unique();
      $table->text('address')->nullable();
      $table->timestamps();
  });
