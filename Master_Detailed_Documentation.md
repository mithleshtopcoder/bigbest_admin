# MASTER DETAILED DOCUMENTATION
## Store-Wise Billing & Retail Management System
**Prepared By:** RS Top Coder  
**Prepared For:** Client  
**Date:** 04 Dec 2025

---

## Table of Contents
1. Executive Summary
2. BRD — Business Requirement Document
3. SRS — Software Requirement Specification
4. Functional Requirements (Detailed)
5. Non-Functional Requirements
6. System Architecture & Technology Stack
7. Data Model — High Level
8. API Overview (Complete)
9. UI / Blade Structure (Laravel) — Complete
10. Sidebar Menu Structure (Admin & Store Panels)
11. Route Map (Complete)
12. Project Plan, Timeline & Milestones
13. Gantt Chart (Text)
14. Team Roles & Responsibilities
15. Risk Management & Mitigation
16. QA, UAT & Acceptance Criteria
17. Deployment & Handover
18. Deliverables
19. Next Steps / Action Items
20. Appendix — Useful Snippets

---

# 1. Executive Summary
This document consolidates business and system requirements, technical design, delivery plan and operational notes for the Store-Wise Billing & Retail Management System — a multi-city, store-wise retail management platform with a centralized admin panel, store portal, fast POS, inventory and purchase management, inter-store stock movement, ledger & reporting, HRM module, and a customer Android app for ordering.

**Key Highlights:**
- Multi-city, multi-store operations
- Centralized Admin Panel with comprehensive management
- Store-wise Billing & POS System
- Complete Inventory & Purchase Management
- Inter-Store Stock Movement
- Employee Sales Tracking
- Store-Wise Financial Reports (P&L, Ledger, Expenses)
- Location-Based Android Customer Application
- Order Auto-Routing to Nearest Store
- Full Analytics Dashboard & Advanced Reporting
- HRM Module (Employee, Attendance, Leave, Salary, Recruitment, Performance, Training)

---

# 2. BRD — Business Requirement Document
**Project Goal:** Provide a single platform to manage multi-city stores with centralized reporting, store-level autonomy, fast billing and a customer ordering app that routes orders to the nearest store.

**Key Business Objectives**
- Centralize multi-store operations
- Automate billing, inventory and accounting
- Improve order accuracy by nearest-store routing
- Provide real-time analytics for quick decisions
- Improve customer experience with mobile app ordering
- Streamline HR operations with comprehensive HRM module

**Primary Stakeholders**
- Business Owner
- Store Managers
- Cashiers
- Inventory Team
- Accountants
- HR Managers
- Customers
- RS Top Coder (Delivery)

**Scope**
- Central Admin Dashboard
- Store Management Portal
- POS/Billing
- Inventory / Purchase Management
- Inter-store Transfer
- Employee Management (RBAC)
- HRM Module (Complete)
- Accounting & Ledger
- Reports & Analytics
- Android Customer App (ordering & tracking)
- APIs & Documentation

---

# 3. SRS — Software Requirement Specification
## 3.1 Purpose
This SRS defines functional and non-functional requirements, constraints, interfaces and acceptance criteria to guide development, testing and deployment.

## 3.2 System Overview
Three main components:
- **Web App (Admin + Store Portal):** Laravel (Blade) server-rendered app for admins and store users.
- **API Layer:** RESTful endpoints served by Laravel for mobile & any integrations.
- **Mobile App (Android - Flutter):** Customer-facing app for store discovery, ordering and tracking.

## 3.3 User Roles
- Super Admin (Full system access)
- Admin (Administrative access)
- Store Manager (Store-specific operations)
- Cashier (Billing & POS)
- Inventory Manager (Stock & purchase)
- Accountant (Ledger & financial reports)
- HR Manager (HRM module access)
- Customer (Mobile app user)

## 3.4 Constraints & Assumptions
- Customer app target: Android (iOS optional in future)
- Internet required
- Third-party services (SMS, payment gateway) billed to client
- Client provides branding assets & GST details

---

# 4. Functional Requirements (Detailed)

## 4.1 Store & Location Management
**Features:**
- Multi-city, multi-store setup
- Store location mapping (latitude/longitude)
- Automatic distance calculation
- Zone-based store assignment (primary/secondary store)
- Store status management (Active/Inactive)
- Store contact information & timings
- Store delivery radius configuration

**Data Fields:**
- Store Name, Code, Address
- City, Zone, State
- GPS Coordinates (Latitude, Longitude)
- Contact Person, Phone, Email
- Opening/Closing Hours
- Delivery Radius (in KM)
- Store Type, Status

## 4.2 POS & Billing Module
**Features:**
- Fast POS billing interface
- Barcode & QR-code support
- Hold/Resume billing
- Discount, coupon, offer management
- Multiple payment modes (Cash, Card, UPI, Wallet, Payment Gateway)
- Invoice generation & printing
- Receipt customization
- Return/Exchange handling
- Customer selection for billing
- Tax calculation (GST)
- Round-off handling

**POS Interface Requirements:**
- Large touch targets for speed
- Keyboard shortcuts
- Quick product search
- Recent transactions
- Daily sales summary
- Payment split options

## 4.3 Customer Android and iOS Application
**Features:**
- App available on Google Play Store
- Customer can browse nearby stores
- Real-time catalog, pricing & availability
- Place order directly from app
- Auto-assign nearest store based on GPS
- Order tracking, status updates, and notifications
- Customer profile management
- Order history
- Wishlist/Favorites
- Push notifications
- OTP-based login/registration

**Order Flow:**
1. Customer opens app
2. GPS detects location
3. Shows nearby stores
4. Browse products with real-time availability
5. Add to cart
6. Checkout
7. Order auto-routed to nearest store
8. Store receives notification
9. Store accepts/rejects order
10. Order tracking with status updates
11. Delivery/Pickup completion

## 4.4 Order Management (Store Wise)
**Features:**
- Orders assigned to nearest store automatically
- Store manager can accept/reject order
- Delivery / pickup options
- Store-wise delivery radius
- Order status tracking (Pending, Accepted, Preparing, Ready, Out for Delivery, Delivered, Cancelled)
- Order history
- Order cancellation with reasons
- Delivery boy assignment
- Estimated delivery time

## 4.5 Purchase & Inventory Management
**Features:**
- Purchase orders (PO) creation & management
- GRN (Goods Received Note) processing
- Supplier/party management
- Item master with variations (size, weight, color, etc.)
- Stock in/out operations
- Store-wise stock tracking
- Dead stock identification
- Stock alerts (low stock, out of stock)
- FIFO & weighted average costing methods
- Batch/Lot number tracking
- Expiry date management
- Stock adjustment
- Stock audit & reconciliation

**Inventory Features:**
- Real-time stock levels
- Stock movement history
- Stock valuation
- Multi-warehouse support
- Stock transfer between stores
- Stock reports

## 4.6 Inter-Store Stock Transfer
**Features:**
- Request stock from another store
- Store-to-store movement
- Transfer approval workflow
- Movement history with audit trail
- Transfer status tracking (Requested, Approved, In Transit, Received, Rejected)
- Transfer document generation
- Stock reconciliation after transfer

## 4.7 Employee Management
**Features:**
- Employee master data
- Role-based permissions (RBAC)
- Employee-wise sales tracking
- Employee performance metrics
- Employee assignment to stores
- Employee login credentials
- Employee activity logs

## 4.8 HRM Module (Human Resource Management)
**Features:**

### 4.8.1 Employee Management
- Employee master with complete profile
- Employee hierarchy & reporting structure
- Employee documents management
- Employee ID card generation
- Employee directory

### 4.8.2 Attendance Management
- Check-in/Check-out (with GPS)
- Daily attendance tracking
- Attendance calendar view
- Attendance reports
- Late coming & early going tracking
- Overtime calculation
- Attendance regularization
- Biometric integration (optional)

### 4.8.3 Leave Management
- Leave types (Casual, Sick, Annual, etc.)
- Leave balance tracking
- Leave application & approval workflow
- Leave calendar
- Leave reports
- Leave encashment
- Leave carry forward

### 4.8.4 Salary Management
- Salary structure definition
- Payroll processing
- Salary components (Basic, HRA, Allowances, Deductions)
- Tax calculation (TDS)
- Salary slip generation
- Salary reports
- Bonus & incentives
- Loan & advances
- Reimbursements

### 4.8.5 Recruitment & Onboarding
- Job posting
- Candidate application
- Interview scheduling
- Candidate evaluation
- Offer letter generation
- Onboarding checklist
- Document collection
- Employee induction

### 4.8.6 Performance Management
- Goal setting
- Performance reviews
- KPI tracking
- 360-degree feedback
- Performance ratings
- Performance reports
- Appraisal cycle management

### 4.8.7 Training & Development
- Training programs
- Training calendar
- Employee training enrollment
- Training attendance
- Training feedback
- Skill assessment
- Certification tracking

## 4.9 Accounting & Ledger
**Features:**
- Ledger store-wise & global
- Supplier ledger
- Customer ledger
- Purchase, and payment entries
- Multi-store consolidated view
- Expense entry & categorization
- Payment tracking
- Receipt & payment vouchers
- Bank reconciliation
- Trial balance
- Balance sheet
- Cash flow statement

## 4.10 Profit & Loss & Financial Reports
**Features:**
- Store-wise Profit & Loss
- Daily/Monthly sales summary
- Tax reports (GST-wise)
- Purchase vs Sales comparison
- Inventory valuation report
- Expense reports
- Revenue reports
- Margin analysis
- Top/bottom products
- Customer-wise sales
- Employee-wise sales
- Export reports (CSV, PDF, Excel)

## 4.11 Dashboard & Analytics
**Features:**
- Real-time analytics
- Store performance comparison
- Top/bottom products
- Expense tracking
- Sales trends (daily, weekly, monthly, yearly)
- Revenue charts & graphs
- Inventory alerts dashboard
- Order status dashboard
- Employee performance dashboard
- Custom date range filters
- Export capabilities

## 4.12 Additional Modules

### 4.12.1 User Access Control (RBAC)
- Role creation & management
- Permission assignment
- User role assignment
- Access control matrix
- Menu-level permissions
- Feature-level permissions

### 4.12.2 Notification System
- SMS notifications
- Email notifications
- Push notifications (FCM)
- In-app notifications
- Notification templates
- Notification logs

### 4.12.3 Logs & Audit Trails
- User activity logs
- System logs
- Error logs
- Audit trail for critical actions
- Login/logout logs
- Data change history

---

# 5. Non-Functional Requirements
- **Performance:** POS actions under 1s; APIs average <500ms; Dashboard load <2s
- **Security:** JWT for APIs, encrypted sensitive data, RBAC, SQL injection prevention, XSS protection
- **Scalability:** Multi-store scale (100+ stores); cloud-friendly architecture
- **Availability:** 99% uptime target; backup & recovery procedures
- **Usability:** Fast, minimal clicks for POS; clear UIs; responsive design
- **Maintainability:** Modular code, service & repository layers; comprehensive documentation
- **Compatibility:** Modern browsers (Chrome, Firefox, Safari, Edge); Android 6.0+ for mobile app

---

# 6. System Architecture & Technology Stack
- **Backend:** Laravel 10/11 (PHP 8.1+)
- **Frontend:** Blade templates (no SPA), HTML5, CSS3, JavaScript (jQuery/Vanilla JS)
- **Mobile:** Flutter (Android), Dart
- **Database:** MySQL 8.0+ / PostgreSQL 13+
- **Queue / Workers:** Redis + Laravel Queues
- **Caching:** Redis
- **File Storage:** Local / AWS S3 / DigitalOcean Spaces
- **Hosting:** AWS / DigitalOcean / Other Cloud
- **Third-party Integrations:**
  - Payment Gateway (Razorpay/Paytm/Stripe)
  - SMS Gateway (Twilio/TextLocal)
  - Push Notifications (FCM - Firebase Cloud Messaging)
  - Email (SMTP)
  - Maps API (Google Maps for distance calculation)

---

# 7. Data Model — High Level
**Primary Entities**

**User & Access:**
- User, Role, Permission, UserRole, RolePermission
- AuditLog, ActivityLog

**Store & Location:**
- Store, City, Zone, StoreLocation

**Product & Catalog:**
- Product, Category, ProductVariant, ProductImage, Barcode

**Inventory:**
- Inventory, StockLog, StockAdjustment, StockAlert

**Purchase:**
- Supplier, PurchaseOrder, PurchaseOrderItem, GRN, GRNItem

**Sales & Orders:**
- Order, OrderItem, PaymentTransaction, Invoice
- Customer (from mobile app)

**Stock Transfer:**
- StockTransfer, StockTransferItem, TransferApproval

**Accounting:**
- Ledger, LedgerEntry, Expense, ExpenseCategory, Payment, Receipt

**HRM:**
- Employee, EmployeeDocument, Attendance, Leave, LeaveApplication, LeaveBalance
- Salary, SalaryComponent, Payroll, PayrollItem
- Recruitment, JobPosting, Candidate, Interview
- Performance, Goal, Review, Feedback
- Training, TrainingProgram, TrainingEnrollment

**Notifications:**
- Notification, NotificationTemplate, NotificationLog

**Notes:**
- Inventory table stores per-store stock and available quantity
- StockLog stores transaction history (in/out/transfer/sale/adjustment)
- Ledger entries are generated from revenue, purchases & expenses
- All critical actions are logged in AuditLog

---

# 8. API Overview (Complete)

## 8.1 Authentication APIs
- POST /api/auth/login (Mobile app login)
- POST /api/auth/register (Customer registration)
- POST /api/auth/logout
- POST /api/auth/refresh
- POST /api/auth/forgot-password
- POST /api/auth/reset-password
- POST /api/auth/verify-otp

## 8.2 Store APIs
- GET /api/stores (List all stores with filters)
- GET /api/stores/nearby (Get nearby stores by coordinates)
- GET /api/stores/{id} (Store details)
- GET /api/stores/{id}/products (Products available at store)

## 8.3 Product APIs
- GET /api/products (List products with filters)
- GET /api/products/{id} (Product details)
- GET /api/products/search (Search products)
- GET /api/products/categories (List categories)
- GET /api/products/category/{id} (Products by category)

## 8.4 Order APIs
- POST /api/orders (Create order)
- GET /api/orders (List orders with filters)
- GET /api/orders/{id} (Order details)
- PUT /api/orders/{id}/status (Update order status)
- POST /api/orders/{id}/cancel (Cancel order)
- GET /api/orders/user/{user_id} (User orders)

## 8.5 POS APIs (Store)
- POST /api/store/{store_id}/pos/order (Create POS order)
- POST /api/store/{store_id}/pos/hold (Hold transaction)
- GET /api/store/{store_id}/pos/holds (List held transactions)
- POST /api/store/{store_id}/pos/resume/{hold_id} (Resume held transaction)
- POST /api/store/{store_id}/pos/payment (Process payment)
- GET /api/store/{store_id}/pos/invoice/{order_id} (Get invoice)

## 8.6 Inventory APIs
- GET /api/store/{store_id}/inventory (Store inventory list)
- GET /api/store/{store_id}/inventory/{product_id} (Product stock)
- POST /api/store/{store_id}/inventory/adjust (Stock adjustment)
- GET /api/store/{store_id}/inventory/logs (Stock movement history)
- GET /api/store/{store_id}/inventory/alerts (Stock alerts)

## 8.7 Transfer APIs
- POST /api/transfers (Create transfer request)
- GET /api/transfers (List transfers)
- GET /api/transfers/{id} (Transfer details)
- PUT /api/transfers/{id}/approve (Approve transfer)
- PUT /api/transfers/{id}/reject (Reject transfer)
- PUT /api/transfers/{id}/receive (Mark as received)

## 8.8 Reporting APIs
- GET /api/reports/sales?store_id=&from=&to= (Sales report)
- GET /api/reports/pnl?store_id=&month= (Profit & Loss)
- GET /api/reports/gst?store_id=&from=&to= (GST report)
- GET /api/reports/inventory-valuation?store_id= (Inventory valuation)
- GET /api/reports/expenses?store_id=&from=&to= (Expense report)
- GET /api/reports/employee-sales?employee_id=&from=&to= (Employee sales)

## 8.9 HRM APIs
- GET /api/hrm/employees (List employees)
- POST /api/hrm/employees (Create employee)
- GET /api/hrm/employees/{id} (Employee details)
- PUT /api/hrm/employees/{id} (Update employee)
- POST /api/hrm/attendance/check-in (Check-in)
- POST /api/hrm/attendance/check-out (Check-out)
- GET /api/hrm/attendance?employee_id=&date= (Attendance records)
- POST /api/hrm/leave/apply (Apply for leave)
- GET /api/hrm/leave/applications (Leave applications)
- PUT /api/hrm/leave/{id}/approve (Approve leave)
- GET /api/hrm/salary/employee/{id} (Employee salary)
- POST /api/hrm/payroll/process (Process payroll)
- GET /api/hrm/performance/reviews (Performance reviews)

## 8.10 Customer APIs
- GET /api/customer/profile (Customer profile)
- PUT /api/customer/profile (Update profile)
- GET /api/customer/orders (Customer orders)
- GET /api/customer/addresses (Saved addresses)
- POST /api/customer/addresses (Add address)

---

# 9. UI / Blade Structure (Laravel) — Complete

## 9.1 Layout Structure
All Blade templates use a unified layout scheme:

```
resources/views/layouts/
  - app.blade.php        # master layout
  - header.blade.php     # top header nav
  - sidebar.blade.php    # left navigation container
  - menu.blade.php       # reusable menu snippets
  - footer.blade.php     # footer (optional)
```

## 9.2 Admin Views Structure

```
resources/views/admin/
  ├── dashboard.blade.php
  │
  ├── manage-store/
  │   ├── index.blade.php
  │   ├── create.blade.php
  │   └── edit.blade.php
  │
  ├── manage-employee/
  │   ├── index.blade.php
  │   ├── create.blade.php
  │   ├── edit.blade.php
  │   └── view.blade.php
  │
  ├── manage-product/
  │   ├── index.blade.php
  │   ├── create.blade.php
  │   ├── edit.blade.php
  │   └── view.blade.php
  │
  ├── manage-category/
  │   ├── index.blade.php
  │   ├── create.blade.php
  │   └── edit.blade.php
  │
  ├── manage-supplier/
  │   ├── index.blade.php
  │   ├── create.blade.php
  │   ├── edit.blade.php
  │   └── view.blade.php
  │
  ├── inventory/
  │   ├── index.blade.php
  │   ├── stock-in.blade.php
  │   ├── stock-out.blade.php
  │   ├── stock-adjustment.blade.php
  │   └── stock-logs.blade.php
  │
  ├── purchase/
  │   ├── index.blade.php
  │   ├── create-po.blade.php
  │   ├── grn.blade.php
  │   ├── view.blade.php
  │   └── suppliers.blade.php
  │
  ├── stock-transfer/
  │   ├── request.blade.php
  │   ├── approve.blade.php
  │   ├── track.blade.php
  │   └── history.blade.php
  │
  ├── ledger/
  │   ├── store-ledger.blade.php
  │   ├── supplier-ledger.blade.php
  │   ├── customer-ledger.blade.php
  │   ├── expenses.blade.php
  │   └── payments.blade.php
  │
  ├── reports/
  │   ├── sales-report.blade.php
  │   ├── pnl.blade.php
  │   ├── gst-report.blade.php
  │   ├── inventory-valuation.blade.php
  │   ├── expense-report.blade.php
  │   └── employee-sales.blade.php
  │
  └── hrm/
      ├── employees/
      │   ├── index.blade.php
      │   ├── create.blade.php
      │   ├── edit.blade.php
      │   └── view.blade.php
      ├── attendance/
      │   ├── index.blade.php
      │   ├── calendar.blade.php
      │   └── reports.blade.php
      ├── leave/
      │   ├── index.blade.php
      │   ├── apply.blade.php
      │   └── applications.blade.php
      ├── salary/
      │   ├── structure.blade.php
      │   ├── payroll.blade.php
      │   └── payslips.blade.php
      ├── recruitment/
      │   ├── job-postings.blade.php
      │   ├── candidates.blade.php
      │   └── interviews.blade.php
      ├── performance/
      │   ├── goals.blade.php
      │   ├── reviews.blade.php
      │   └── reports.blade.php
      └── training/
          ├── programs.blade.php
          ├── calendar.blade.php
          └── enrollments.blade.php
```

## 9.3 Store Views Structure

```
resources/views/store/
  ├── dashboard.blade.php
  │
  ├── pos/
  │   ├── index.blade.php
  │   ├── cart.blade.php
  │   ├── payment.blade.php
  │   ├── invoice.blade.php
  │   └── holds.blade.php
  │
  ├── inventory/
  │   ├── index.blade.php
  │   ├── stock-log.blade.php
  │   ├── alerts.blade.php
  │   └── adjustment.blade.php
  │
  ├── orders/
  │   ├── index.blade.php
  │   ├── view.blade.php
  │   ├── track.blade.php
  │   └── history.blade.php
  │
  ├── expenses/
  │   ├── index.blade.php
  │   ├── create.blade.php
  │   └── reports.blade.php
  │
  └── reports/
      ├── sales.blade.php
      ├── daily-summary.blade.php
      └── employee-sales.blade.php
```

## 9.4 Auth Views Structure

```
resources/views/auth/
  ├── login.blade.php
  ├── forgot-password.blade.php
  ├── reset-password.blade.php
  └── register.blade.php (if needed)
```

## 9.5 Public Assets

```
public/
  ├── css/
  │   ├── app.css
  │   ├── pos.css
  │   └── admin.css
  ├── js/
  │   ├── app.js
  │   ├── pos.js
  │   └── admin.js
  ├── images/
  │   └── logo.png
  └── uploads/
      ├── products/
      ├── employees/
      └── documents/
```

---

# 10. Sidebar Menu Structure (Admin & Store Panels)

## 10.1 Admin Panel Sidebar Menu

**Total Menu Sections: 11**
**Total Menu Items: 35+**

### Menu Structure:

1. **Dashboard** (1 item)
   - Dashboard

2. **Store Management** (1 item)
   - Manage Stores

3. **Employee Management** (1 item)
   - Manage Employees

4. **Products** (2 items)
   - Manage Products
   - Manage Categories

5. **Suppliers** (1 item)
   - Manage Suppliers

6. **Inventory** (4 items)
   - Stock Overview
   - Stock In
   - Stock Out
   - Stock Adjustment

7. **Purchase** (3 items)
   - Purchase Orders
   - Create PO
   - GRN

8. **Transfers** (3 items)
   - Stock Transfer Request
   - Approve Transfers
   - Transfer History

9. **Accounting** (5 items)
   - Store Ledger
   - Supplier Ledger
   - Customer Ledger
   - Expenses
   - Payments

10. **Reports** (6 items)
    - Sales Report
    - Profit & Loss
    - GST Report
    - Inventory Valuation
    - Expense Report
    - Employee Sales Report

11. **HRM** (7 sub-modules, 20+ items)
    - **Employee Management** (3 items)
      - Employees List
      - Add Employee
      - Employee Directory
    - **Attendance** (3 items)
      - Attendance Dashboard
      - Attendance Calendar
      - Attendance Reports
    - **Leave Management** (3 items)
      - Leave Applications
      - Apply Leave
      - Leave Balance
    - **Salary Management** (3 items)
      - Salary Structure
      - Process Payroll
      - Payslips
    - **Recruitment** (3 items)
      - Job Postings
      - Candidates
      - Interviews
    - **Performance** (3 items)
      - Goals
      - Performance Reviews
      - Performance Reports
    - **Training** (3 items)
      - Training Programs
      - Training Calendar
      - Enrollments

**Admin Panel Total: 35+ menu items**

## 10.2 Store Panel Sidebar Menu

**Total Menu Sections: 6**
**Total Menu Items: 15+**

### Menu Structure:

1. **Dashboard** (1 item)
   - Dashboard

2. **POS/Billing** (4 items)
   - POS Terminal
   - Hold Transactions
   - Today's Sales
   - Sales History

3. **Inventory** (3 items)
   - Stock Overview
   - Stock Logs
   - Stock Alerts

4. **Orders** (3 items)
   - New Orders
   - Order History
   - Order Tracking

5. **Expenses** (2 items)
   - Add Expense
   - Expense Reports

6. **Reports** (3 items)
   - Daily Sales Summary
   - Employee Sales
   - Store Performance

**Store Panel Total: 16 menu items**

---

# 11. Route Map (Complete)

## 11.1 Web Routes (web.php)

```php
// Public Routes
Route::get('/', function () {
    return redirect('/admin/dashboard');
});
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
Route::get('/forgot-password', [AuthController::class, 'showForgotPassword'])->name('forgot-password');
Route::post('/forgot-password', [AuthController::class, 'forgotPassword']);
Route::get('/reset-password/{token}', [AuthController::class, 'showResetPassword'])->name('reset-password');
Route::post('/reset-password', [AuthController::class, 'resetPassword']);
```

## 11.2 Admin Routes (admin.php - prefix /admin)

```php
Route::prefix('admin')->name('admin.')->middleware(['auth', 'role:admin|super_admin'])->group(function () {
    
    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    
    // Store Management
    Route::prefix('manage-store')->name('manage-store.')->group(function () {
        Route::get('/', [StoreController::class, 'index'])->name('index');
        Route::get('/create', [StoreController::class, 'create'])->name('create');
        Route::post('/', [StoreController::class, 'store'])->name('store');
        Route::get('/{id}/edit', [StoreController::class, 'edit'])->name('edit');
        Route::put('/{id}', [StoreController::class, 'update'])->name('update');
        Route::delete('/{id}', [StoreController::class, 'destroy'])->name('destroy');
    });
    
    // Employee Management
    Route::prefix('manage-employee')->name('manage-employee.')->group(function () {
        Route::get('/', [EmployeeController::class, 'index'])->name('index');
        Route::get('/create', [EmployeeController::class, 'create'])->name('create');
        Route::post('/', [EmployeeController::class, 'store'])->name('store');
        Route::get('/{id}/edit', [EmployeeController::class, 'edit'])->name('edit');
        Route::put('/{id}', [EmployeeController::class, 'update'])->name('update');
        Route::delete('/{id}', [EmployeeController::class, 'destroy'])->name('destroy');
    });
    
    // Product Management
    Route::prefix('manage-product')->name('manage-product.')->group(function () {
        Route::get('/', [ProductController::class, 'index'])->name('index');
        Route::get('/create', [ProductController::class, 'create'])->name('create');
        Route::post('/', [ProductController::class, 'store'])->name('store');
        Route::get('/{id}/edit', [ProductController::class, 'edit'])->name('edit');
        Route::put('/{id}', [ProductController::class, 'update'])->name('update');
        Route::delete('/{id}', [ProductController::class, 'destroy'])->name('destroy');
    });
    
    // Category Management
    Route::prefix('manage-category')->name('manage-category.')->group(function () {
        Route::get('/', [CategoryController::class, 'index'])->name('index');
        Route::get('/create', [CategoryController::class, 'create'])->name('create');
        Route::post('/', [CategoryController::class, 'store'])->name('store');
        Route::get('/{id}/edit', [CategoryController::class, 'edit'])->name('edit');
        Route::put('/{id}', [CategoryController::class, 'update'])->name('update');
        Route::delete('/{id}', [CategoryController::class, 'destroy'])->name('destroy');
    });
    
    // Supplier Management
    Route::prefix('manage-supplier')->name('manage-supplier.')->group(function () {
        Route::get('/', [SupplierController::class, 'index'])->name('index');
        Route::get('/create', [SupplierController::class, 'create'])->name('create');
        Route::post('/', [SupplierController::class, 'store'])->name('store');
        Route::get('/{id}/edit', [SupplierController::class, 'edit'])->name('edit');
        Route::put('/{id}', [SupplierController::class, 'update'])->name('update');
        Route::delete('/{id}', [SupplierController::class, 'destroy'])->name('destroy');
    });
    
    // Inventory
    Route::prefix('inventory')->name('inventory.')->group(function () {
        Route::get('/', [InventoryController::class, 'index'])->name('index');
        Route::get('/stock-in', [InventoryController::class, 'stockIn'])->name('stock-in');
        Route::post('/stock-in', [InventoryController::class, 'processStockIn'])->name('stock-in.process');
        Route::get('/stock-out', [InventoryController::class, 'stockOut'])->name('stock-out');
        Route::post('/stock-out', [InventoryController::class, 'processStockOut'])->name('stock-out.process');
        Route::get('/stock-adjustment', [InventoryController::class, 'stockAdjustment'])->name('stock-adjustment');
        Route::post('/stock-adjustment', [InventoryController::class, 'processStockAdjustment'])->name('stock-adjustment.process');
        Route::get('/stock-logs', [InventoryController::class, 'stockLogs'])->name('stock-logs');
    });
    
    // Purchase
    Route::prefix('purchase')->name('purchase.')->group(function () {
        Route::get('/', [PurchaseController::class, 'index'])->name('index');
        Route::get('/create-po', [PurchaseController::class, 'createPo'])->name('create-po');
        Route::post('/create-po', [PurchaseController::class, 'storePo'])->name('store-po');
        Route::get('/grn', [PurchaseController::class, 'grn'])->name('grn');
        Route::post('/grn', [PurchaseController::class, 'processGrn'])->name('process-grn');
        Route::get('/{id}/view', [PurchaseController::class, 'view'])->name('view');
    });
    
    // Stock Transfer
    Route::prefix('stock-transfer')->name('stock-transfer.')->group(function () {
        Route::get('/request', [TransferController::class, 'request'])->name('request');
        Route::post('/request', [TransferController::class, 'storeRequest'])->name('store-request');
        Route::get('/approve', [TransferController::class, 'approve'])->name('approve');
        Route::post('/approve/{id}', [TransferController::class, 'processApproval'])->name('process-approval');
        Route::get('/track', [TransferController::class, 'track'])->name('track');
        Route::get('/history', [TransferController::class, 'history'])->name('history');
    });
    
    // Ledger
    Route::prefix('ledger')->name('ledger.')->group(function () {
        Route::get('/store-ledger', [LedgerController::class, 'storeLedger'])->name('store-ledger');
        Route::get('/supplier-ledger', [LedgerController::class, 'supplierLedger'])->name('supplier-ledger');
        Route::get('/customer-ledger', [LedgerController::class, 'customerLedger'])->name('customer-ledger');
        Route::get('/expenses', [LedgerController::class, 'expenses'])->name('expenses');
        Route::post('/expenses', [LedgerController::class, 'storeExpense'])->name('store-expense');
        Route::get('/payments', [LedgerController::class, 'payments'])->name('payments');
    });
    
    // Reports
    Route::prefix('reports')->name('reports.')->group(function () {
        Route::get('/sales-report', [ReportController::class, 'salesReport'])->name('sales-report');
        Route::get('/pnl', [ReportController::class, 'pnl'])->name('pnl');
        Route::get('/gst-report', [ReportController::class, 'gstReport'])->name('gst-report');
        Route::get('/inventory-valuation', [ReportController::class, 'inventoryValuation'])->name('inventory-valuation');
        Route::get('/expense-report', [ReportController::class, 'expenseReport'])->name('expense-report');
        Route::get('/employee-sales', [ReportController::class, 'employeeSales'])->name('employee-sales');
    });
    
    // HRM Module
    Route::prefix('hrm')->name('hrm.')->group(function () {
        // Employees
        Route::prefix('employees')->name('employees.')->group(function () {
            Route::get('/', [HRM\EmployeeController::class, 'index'])->name('index');
            Route::get('/create', [HRM\EmployeeController::class, 'create'])->name('create');
            Route::post('/', [HRM\EmployeeController::class, 'store'])->name('store');
            Route::get('/{id}', [HRM\EmployeeController::class, 'show'])->name('show');
            Route::get('/{id}/edit', [HRM\EmployeeController::class, 'edit'])->name('edit');
            Route::put('/{id}', [HRM\EmployeeController::class, 'update'])->name('update');
        });
        
        // Attendance
        Route::prefix('attendance')->name('attendance.')->group(function () {
            Route::get('/', [HRM\AttendanceController::class, 'index'])->name('index');
            Route::get('/calendar', [HRM\AttendanceController::class, 'calendar'])->name('calendar');
            Route::get('/reports', [HRM\AttendanceController::class, 'reports'])->name('reports');
            Route::post('/check-in', [HRM\AttendanceController::class, 'checkIn'])->name('check-in');
            Route::post('/check-out', [HRM\AttendanceController::class, 'checkOut'])->name('check-out');
        });
        
        // Leave
        Route::prefix('leave')->name('leave.')->group(function () {
            Route::get('/', [HRM\LeaveController::class, 'index'])->name('index');
            Route::get('/apply', [HRM\LeaveController::class, 'apply'])->name('apply');
            Route::post('/apply', [HRM\LeaveController::class, 'storeApplication'])->name('store-application');
            Route::get('/applications', [HRM\LeaveController::class, 'applications'])->name('applications');
            Route::post('/{id}/approve', [HRM\LeaveController::class, 'approve'])->name('approve');
            Route::post('/{id}/reject', [HRM\LeaveController::class, 'reject'])->name('reject');
        });
        
        // Salary
        Route::prefix('salary')->name('salary.')->group(function () {
            Route::get('/structure', [HRM\SalaryController::class, 'structure'])->name('structure');
            Route::get('/payroll', [HRM\SalaryController::class, 'payroll'])->name('payroll');
            Route::post('/payroll/process', [HRM\SalaryController::class, 'processPayroll'])->name('process-payroll');
            Route::get('/payslips', [HRM\SalaryController::class, 'payslips'])->name('payslips');
        });
        
        // Recruitment
        Route::prefix('recruitment')->name('recruitment.')->group(function () {
            Route::get('/job-postings', [HRM\RecruitmentController::class, 'jobPostings'])->name('job-postings');
            Route::get('/candidates', [HRM\RecruitmentController::class, 'candidates'])->name('candidates');
            Route::get('/interviews', [HRM\RecruitmentController::class, 'interviews'])->name('interviews');
        });
        
        // Performance
        Route::prefix('performance')->name('performance.')->group(function () {
            Route::get('/goals', [HRM\PerformanceController::class, 'goals'])->name('goals');
            Route::get('/reviews', [HRM\PerformanceController::class, 'reviews'])->name('reviews');
            Route::get('/reports', [HRM\PerformanceController::class, 'reports'])->name('reports');
        });
        
        // Training
        Route::prefix('training')->name('training.')->group(function () {
            Route::get('/programs', [HRM\TrainingController::class, 'programs'])->name('programs');
            Route::get('/calendar', [HRM\TrainingController::class, 'calendar'])->name('calendar');
            Route::get('/enrollments', [HRM\TrainingController::class, 'enrollments'])->name('enrollments');
        });
    });
});
```

## 11.3 Store Routes (store.php - prefix /store)

```php
Route::prefix('store')->name('store.')->middleware(['auth', 'role:store_manager|cashier'])->group(function () {
    
    // Dashboard
    Route::get('/dashboard', [Store\DashboardController::class, 'index'])->name('dashboard');
    
    // POS
    Route::prefix('pos')->name('pos.')->group(function () {
        Route::get('/', [Store\POSController::class, 'index'])->name('index');
        Route::post('/order', [Store\POSController::class, 'createOrder'])->name('order');
        Route::post('/hold', [Store\POSController::class, 'hold'])->name('hold');
        Route::get('/holds', [Store\POSController::class, 'holds'])->name('holds');
        Route::post('/resume/{id}', [Store\POSController::class, 'resume'])->name('resume');
        Route::post('/payment', [Store\POSController::class, 'payment'])->name('payment');
        Route::get('/invoice/{id}', [Store\POSController::class, 'invoice'])->name('invoice');
    });
    
    // Inventory
    Route::prefix('inventory')->name('inventory.')->group(function () {
        Route::get('/', [Store\InventoryController::class, 'index'])->name('index');
        Route::get('/stock-log', [Store\InventoryController::class, 'stockLog'])->name('stock-log');
        Route::get('/alerts', [Store\InventoryController::class, 'alerts'])->name('alerts');
    });
    
    // Orders
    Route::prefix('orders')->name('orders.')->group(function () {
        Route::get('/', [Store\OrderController::class, 'index'])->name('index');
        Route::get('/{id}/view', [Store\OrderController::class, 'view'])->name('view');
        Route::post('/{id}/accept', [Store\OrderController::class, 'accept'])->name('accept');
        Route::post('/{id}/reject', [Store\OrderController::class, 'reject'])->name('reject');
        Route::get('/track', [Store\OrderController::class, 'track'])->name('track');
        Route::get('/history', [Store\OrderController::class, 'history'])->name('history');
    });
    
    // Expenses
    Route::prefix('expenses')->name('expenses.')->group(function () {
        Route::get('/', [Store\ExpenseController::class, 'index'])->name('index');
        Route::get('/create', [Store\ExpenseController::class, 'create'])->name('create');
        Route::post('/', [Store\ExpenseController::class, 'store'])->name('store');
        Route::get('/reports', [Store\ExpenseController::class, 'reports'])->name('reports');
    });
    
    // Reports
    Route::prefix('reports')->name('reports.')->group(function () {
        Route::get('/sales', [Store\ReportController::class, 'sales'])->name('sales');
        Route::get('/daily-summary', [Store\ReportController::class, 'dailySummary'])->name('daily-summary');
        Route::get('/employee-sales', [Store\ReportController::class, 'employeeSales'])->name('employee-sales');
    });
});
```

## 11.4 API Routes (api.php)

```php
Route::prefix('api')->group(function () {
    
    // Public APIs
    Route::post('/auth/login', [Api\AuthController::class, 'login']);
    Route::post('/auth/register', [Api\AuthController::class, 'register']);
    Route::post('/auth/verify-otp', [Api\AuthController::class, 'verifyOtp']);
    
    // Protected APIs
    Route::middleware('auth:sanctum')->group(function () {
        // Auth
        Route::post('/auth/logout', [Api\AuthController::class, 'logout']);
        Route::post('/auth/refresh', [Api\AuthController::class, 'refresh']);
        
        // Stores
        Route::get('/stores', [Api\StoreController::class, 'index']);
        Route::get('/stores/nearby', [Api\StoreController::class, 'nearby']);
        Route::get('/stores/{id}', [Api\StoreController::class, 'show']);
        Route::get('/stores/{id}/products', [Api\StoreController::class, 'products']);
        
        // Products
        Route::get('/products', [Api\ProductController::class, 'index']);
        Route::get('/products/{id}', [Api\ProductController::class, 'show']);
        Route::get('/products/search', [Api\ProductController::class, 'search']);
        Route::get('/products/categories', [Api\ProductController::class, 'categories']);
        
        // Orders
        Route::post('/orders', [Api\OrderController::class, 'store']);
        Route::get('/orders', [Api\OrderController::class, 'index']);
        Route::get('/orders/{id}', [Api\OrderController::class, 'show']);
        Route::put('/orders/{id}/status', [Api\OrderController::class, 'updateStatus']);
        Route::post('/orders/{id}/cancel', [Api\OrderController::class, 'cancel']);
        Route::get('/orders/user/{user_id}', [Api\OrderController::class, 'userOrders']);
        
        // Customer
        Route::get('/customer/profile', [Api\CustomerController::class, 'profile']);
        Route::put('/customer/profile', [Api\CustomerController::class, 'updateProfile']);
        Route::get('/customer/orders', [Api\CustomerController::class, 'orders']);
        Route::get('/customer/addresses', [Api\CustomerController::class, 'addresses']);
        Route::post('/customer/addresses', [Api\CustomerController::class, 'storeAddress']);
    });
});
```

---

# 12. Project Plan, Timeline & Milestones
**Total Duration: 6 Months** (as per quotation)

**Milestones / Payment**
- M1 — Advance payment at project start & requirement finalization (Week 1) — 25%
- M2 — After completion of UI/UX design & store flow approval (Week 8) — 25%
- M3 — After completion of development & testing (Week 22) — 25%
- M4 — After UAT & before final delivery (Week 24) — 25%

**High-Level Phases**
- **Month 1:** Requirements finalization, planning, UI/UX design
- **Month 2:** Backend architecture, database design, authentication, core modules
- **Month 3:** Admin panel development (Store, Employee, Product, Category, Supplier)
- **Month 4:** Inventory, Purchase, Stock Transfer, POS development
- **Month 5:** HRM module, Reports, Ledger, Android app development
- **Month 6:** Integration, testing, UAT, deployment, training

---

# 13. Gantt Chart (Text)
```
MONTH →   1    2    3    4    5    6
WEEK →   1-4  5-8  9-12 13-16 17-20 21-24
-----------------------------------------------------------------------------------------
Requirement Finalization      ████
Planning & Documentation      ████
UI/UX Design                       ████████
Backend Architecture                    ████████
Admin Panel Development                           ████████████
Store Portal Development                             ████████████
POS Billing Module                                          ████████
Inventory & Purchase Module                                     ████████
Inter-Store Transfer Module                                        ████████
HRM Module                                                              ████████
Reports & Financial Module                                                ████████
Android App (Flutter)                                                          ████████
Integration & QA Testing                                                           ████████
User Acceptance Testing (UAT)                                                          ████
Deployment & Training                                                                   ████
-----------------------------------------------------------------------------------------
```

---

# 14. Team Roles & Responsibilities
- **Project Manager:** Project planning, client communication, milestone sign-offs, risk management
- **Backend Developers (Laravel):** API development, business logic, database design, integrations
- **Frontend Developers (Blade):** Blade templates, CSS, JavaScript for POS and admin panels
- **Mobile Developer (Flutter):** Android app development, API integration, push notifications
- **UI/UX Designer:** Wireframes, UI design, user experience optimization
- **QA Engineer:** Test planning, test case creation, testing execution, bug reporting
- **DevOps Engineer:** Server setup, deployment, monitoring, backup strategies
- **Business Analyst:** Requirements gathering, documentation, client liaison

---

# 15. Risk Management & Mitigation
| Risk | Impact | Mitigation |
|------|--------|------------|
| Delayed feedback | Timeline slip | Weekly client demos and sign-offs, clear communication channels |
| API downtime | Blocked features | Staging environment & mock data, fallback mechanisms |
| Scope creep | Cost/time increase | Formal Change Request Process, clear scope definition |
| Payment/Gateway integration | Delay | Start integration early, use sandbox environment |
| Third-party service issues | Feature delays | Multiple vendor options, fallback plans |
| Mobile app approval delays | Launch delay | Early submission, compliance check, Play Store guidelines |

---

# 16. QA, UAT & Acceptance Criteria
**QA Phases**
- Unit Testing (Code level)
- Integration Testing (Module interactions)
- System Testing (End-to-end flows)
- Performance Testing (Load, stress)
- Security Testing (Vulnerability assessment)
- User Acceptance Testing (Client validation)

**UAT**
- Client executes predefined scenarios
- Real-world data testing
- UAT sign-off required for Go-Live
- Bug fixes based on UAT feedback

**Acceptance Criteria**
- All functional requirements implemented and tested
- No critical defects (P0, P1)
- POS performance meets NFRs (<1s response)
- Mobile app approved (Play Store readiness)
- All reports accurate and validated
- Security audit passed
- Documentation complete

---

# 17. Deployment & Handover
**Deployment Steps**
1. Provision servers (Web, Database, Cache)
2. Configure environment variables
3. Setup database and run migrations
4. Configure backups (automated)
5. Setup cronjobs, queues, scheduled tasks
6. SSL certificate installation
7. Domain & DNS configuration
8. CDN setup (if required)
9. Monitoring tools setup
10. Post-deploy smoke tests
11. Performance optimization
12. Security hardening

**Handover**
- Source code (post full payment)
- Server credentials (if client-side hosting)
- Database backup
- Documentation:
  - BRD, SRS, Master Documentation
  - API Documentation
  - User Manual
  - Admin Manual
  - Technical Documentation
- Training sessions (Admin, Store Manager, Cashier)
- 6 months free support included (bug fixes only)

---

# 18. Deliverables
**Web Applications:**
- Admin Panel (Complete)
- Store Management Portal (Complete)
- POS/Billing System (Complete)

**Modules:**
- Store & Location Management
- Employee Management
- Product & Category Management
- Supplier Management
- Inventory Management
- Purchase & GRN Management
- Inter-Store Stock Transfer
- POS/Billing System
- Order Management
- Accounting & Ledger
- HRM Module (Complete)
- Reports & Analytics  
- Dashboard & Analytics

**Mobile Application:**
- Android Customer App (Google Play Store ready)

**Documentation:**
- Master Documentation (This document)
- BRD (Business Requirement Document)
- SRS (Software Requirement Specification)
- API Documentation  
- User Manual
- Admin Manual
- Technical Documentation
- Test Reports

**Deployment:**
- Production deployment
- Staging environment
- Database setup
- Backup configuration

**Support:**
- 6 months free support (bug fixes only)
- Training sessions

---

# 19. Next Steps / Action Items
1. ✅ Accept BRD & SRS and confirm sign-off (M1 payment - 25%)
2. Provide branding materials (logo, theme colors, store details)
3. Provide finalized requirements (if any changes)
4. Confirm hosting preference & provide server access (if client-side)
5. Provide necessary legal/technical documents (GST details, etc.)
6. Start UI/UX design approval (Weeks 2-8)
7. Weekly standup schedule and point of contact establishment
8. Third-party service accounts setup (SMS, Payment Gateway, etc.)

---

# 20. Appendix — Useful Snippets

## 20.1 Blade Master Layout Example

```blade
<!-- resources/views/layouts/app.blade.php -->
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'StoreWise - Retail Management System')</title>
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
  @stack('styles')
</head>
<body>
    <div class="min-h-screen bg-gray-100">
  @include('layouts.header')
        <div class="flex">
    @include('layouts.sidebar')
            <main class="flex-1 p-6">
                @if(session('success'))
                    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                        {{ session('success') }}
                    </div>
                @endif
                @if(session('error'))
                    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                        {{ session('error') }}
                    </div>
                @endif
      @yield('content')
            </main>
    </div>
  </div>
  <script src="{{ asset('js/app.js') }}"></script>
  @stack('scripts')
</body>
</html>
```

## 20.2 Example Controller Structure

```php
<?php
// app/Http/Controllers/Admin/StoreController.php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Store;
use Illuminate\Http\Request;

class StoreController extends Controller
{
    public function index()
    {
        $stores = Store::with('city', 'zone')->paginate(15);
        return view('admin.manage-store.index', compact('stores'));
    }

    public function create()
    {
        $cities = City::all();
        $zones = Zone::all();
        return view('admin.manage-store.create', compact('cities', 'zones'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|unique:stores,code',
            'address' => 'required|string',
            'city_id' => 'required|exists:cities,id',
            'latitude' => 'required|numeric',
            'longitude' => 'required|numeric',
            // ... more fields
        ]);

        $store = Store::create($validated);
        
        return redirect()->route('admin.manage-store.index')
            ->with('success', 'Store created successfully');
    }

    public function edit($id)
    {
        $store = Store::findOrFail($id);
        $cities = City::all();
        $zones = Zone::all();
        return view('admin.manage-store.edit', compact('store', 'cities', 'zones'));
    }

    public function update(Request $request, $id)
    {
        $store = Store::findOrFail($id);
        
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|unique:stores,code,' . $id,
            // ... more fields
        ]);

        $store->update($validated);
        
        return redirect()->route('admin.manage-store.index')
            ->with('success', 'Store updated successfully');
    }

    public function destroy($id)
    {
        $store = Store::findOrFail($id);
        $store->delete();
        
        return redirect()->route('admin.manage-store.index')
            ->with('success', 'Store deleted successfully');
    }
}
```

## 20.3 Example Model with Relationships

```php
<?php
// app/Models/Store.php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Store extends Model
{
    protected $fillable = [
        'name',
        'code',
        'address',
        'city_id',
        'zone_id',
        'latitude',
        'longitude',
        'contact_person',
        'phone',
        'email',
        'opening_hours',
        'closing_hours',
        'delivery_radius',
        'status'
    ];

    protected $casts = [
        'latitude' => 'decimal:8',
        'longitude' => 'decimal:8',
        'delivery_radius' => 'decimal:2',
        'status' => 'boolean'
    ];

    // Relationships
    public function city(): BelongsTo
    {
        return $this->belongsTo(City::class);
    }

    public function zone(): BelongsTo
    {
        return $this->belongsTo(Zone::class);
    }

    public function inventory(): HasMany
    {
        return $this->hasMany(Inventory::class);
    }

    public function orders(): HasMany
    {
        return $this->hasMany(Order::class);
    }

    public function employees(): HasMany
    {
        return $this->hasMany(Employee::class);
    }

    // Helper Methods
    public function calculateDistance($lat, $lng)
    {
        // Haversine formula for distance calculation
        $earthRadius = 6371; // km
        
        $dLat = deg2rad($lat - $this->latitude);
        $dLng = deg2rad($lng - $this->longitude);
        
        $a = sin($dLat/2) * sin($dLat/2) +
             cos(deg2rad($this->latitude)) * cos(deg2rad($lat)) *
             sin($dLng/2) * sin($dLng/2);
        
        $c = 2 * atan2(sqrt($a), sqrt(1-$a));
        
        return $earthRadius * $c;
    }
}
```

## 20.4 Example API Controller

```php
<?php
// app/Http/Controllers/Api/StoreController.php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Store;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class StoreController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $query = Store::with('city', 'zone')->where('status', true);
        
        if ($request->has('city_id')) {
            $query->where('city_id', $request->city_id);
        }
        
        $stores = $query->get();
        
        return response()->json([
            'success' => true,
            'data' => $stores
        ]);
    }

    public function nearby(Request $request): JsonResponse
    {
        $lat = $request->input('latitude');
        $lng = $request->input('longitude');
        $radius = $request->input('radius', 10); // default 10 km
        
        $stores = Store::where('status', true)
            ->get()
            ->map(function ($store) use ($lat, $lng) {
                $store->distance = $store->calculateDistance($lat, $lng);
                return $store;
            })
            ->filter(function ($store) use ($radius) {
                return $store->distance <= $radius;
            })
            ->sortBy('distance')
            ->values();
        
        return response()->json([
            'success' => true,
            'data' => $stores
        ]);
    }

    public function show($id): JsonResponse
    {
        $store = Store::with('city', 'zone', 'inventory')->findOrFail($id);
        
        return response()->json([
            'success' => true,
            'data' => $store
        ]);
    }
}
```

## 20.5 Example Database Migration

```php
<?php
// database/migrations/2024_01_01_000001_create_stores_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('stores', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('code')->unique();
            $table->text('address');
            $table->foreignId('city_id')->constrained('cities');
            $table->foreignId('zone_id')->nullable()->constrained('zones');
            $table->decimal('latitude', 10, 8);
            $table->decimal('longitude', 11, 8);
            $table->string('contact_person')->nullable();
            $table->string('phone')->nullable();
            $table->string('email')->nullable();
            $table->time('opening_hours')->nullable();
            $table->time('closing_hours')->nullable();
            $table->decimal('delivery_radius', 8, 2)->default(5.00);
            $table->boolean('status')->default(true);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('stores');
    }
};
```

## 20.6 Example POS JavaScript (Vanilla JS)

```javascript
// public/js/pos.js

class POSSystem {
    constructor() {
        this.cart = [];
        this.total = 0;
        this.init();
    }

    init() {
        this.bindEvents();
        this.loadHeldTransactions();
    }

    bindEvents() {
        // Barcode scanner
        document.getElementById('barcode-input').addEventListener('keypress', (e) => {
            if (e.key === 'Enter') {
                this.addProductByBarcode(e.target.value);
                e.target.value = '';
            }
        });

        // Add to cart button
        document.querySelectorAll('.add-to-cart').forEach(btn => {
            btn.addEventListener('click', (e) => {
                const productId = e.target.dataset.productId;
                this.addToCart(productId);
            });
        });

        // Payment button
        document.getElementById('process-payment').addEventListener('click', () => {
            this.processPayment();
        });

        // Hold transaction
        document.getElementById('hold-transaction').addEventListener('click', () => {
            this.holdTransaction();
        });
    }

    addToCart(productId) {
        // Fetch product details and add to cart
        fetch(`/api/products/${productId}`)
            .then(res => res.json())
            .then(data => {
                const item = {
                    product_id: data.id,
                    name: data.name,
                    price: data.price,
                    quantity: 1
                };
                this.cart.push(item);
                this.updateCart();
            });
    }

    updateCart() {
        const cartContainer = document.getElementById('cart-items');
        const totalElement = document.getElementById('cart-total');
        
        cartContainer.innerHTML = '';
        this.total = 0;

        this.cart.forEach((item, index) => {
            this.total += item.price * item.quantity;
            const itemHtml = `
                <div class="cart-item">
                    <span>${item.name}</span>
                    <span>Qty: ${item.quantity}</span>
                    <span>₹${item.price * item.quantity}</span>
                    <button onclick="pos.removeFromCart(${index})">Remove</button>
                </div>
            `;
            cartContainer.innerHTML += itemHtml;
        });

        totalElement.textContent = `Total: ₹${this.total.toFixed(2)}`;
    }

    processPayment() {
        if (this.cart.length === 0) {
            alert('Cart is empty');
            return;
        }

        const paymentData = {
            items: this.cart,
            total: this.total,
            payment_method: document.getElementById('payment-method').value
        };

        fetch('/api/store/pos/payment', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            },
            body: JSON.stringify(paymentData)
        })
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                window.location.href = `/store/pos/invoice/${data.order_id}`;
            }
        });
    }

    holdTransaction() {
        // Implementation for holding transaction
    }
}

// Initialize POS when DOM is ready
document.addEventListener('DOMContentLoaded', () => {
    window.pos = new POSSystem();
});
```

## 20.7 Example Service Class

```php
<?php
// app/Services/OrderService.php

namespace App\Services;

use App\Models\Order;
use App\Models\Store;
use App\Models\Product;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class OrderService
{
    public function assignNearestStore($customerLat, $customerLng): ?Store
    {
        $stores = Store::where('status', true)->get();
        
        $nearestStore = null;
        $minDistance = PHP_INT_MAX;

        foreach ($stores as $store) {
            $distance = $store->calculateDistance($customerLat, $customerLng);
            
            if ($distance < $minDistance && $distance <= $store->delivery_radius) {
                $minDistance = $distance;
                $nearestStore = $store;
            }
        }

        return $nearestStore;
    }

    public function createOrder(array $orderData): Order
    {
        DB::beginTransaction();
        
        try {
            // Assign nearest store
            $store = $this->assignNearestStore(
                $orderData['latitude'],
                $orderData['longitude']
            );

            if (!$store) {
                throw new \Exception('No store available in delivery radius');
            }

            // Create order
            $order = Order::create([
                'customer_id' => $orderData['customer_id'],
                'store_id' => $store->id,
                'total_amount' => $orderData['total'],
                'status' => 'pending',
                'delivery_address' => $orderData['address'],
                'latitude' => $orderData['latitude'],
                'longitude' => $orderData['longitude']
            ]);

            // Create order items
            foreach ($orderData['items'] as $item) {
                $order->items()->create([
                    'product_id' => $item['product_id'],
                    'quantity' => $item['quantity'],
                    'price' => $item['price'],
                    'total' => $item['quantity'] * $item['price']
                ]);

                // Update inventory
                $inventory = Inventory::where('store_id', $store->id)
                    ->where('product_id', $item['product_id'])
                    ->first();
                
                if ($inventory && $inventory->quantity >= $item['quantity']) {
                    $inventory->decrement('quantity', $item['quantity']);
                } else {
                    throw new \Exception('Insufficient stock');
                }
            }

            DB::commit();
            
            // Send notification
            $this->sendOrderNotification($order);
            
            return $order;
            
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Order creation failed: ' . $e->getMessage());
            throw $e;
        }
    }

    private function sendOrderNotification(Order $order): void
    {
        // Send push notification to store
        // Send SMS/Email to customer
    }
}
```

## 20.8 Example Middleware for Role-Based Access

```php
<?php
// app/Http/Middleware/CheckRole.php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckRole
{
    public function handle(Request $request, Closure $next, ...$roles): Response
    {
        if (!auth()->check()) {
            return redirect()->route('login');
        }

        $user = auth()->user();
        
        foreach ($roles as $role) {
            if ($user->hasRole($role)) {
                return $next($request);
            }
        }

        abort(403, 'Unauthorized access');
    }
}
```

## 20.9 Example Form Validation Rules

```php
// Example validation rules for different modules

// Store Creation
$rules = [
    'name' => 'required|string|max:255',
    'code' => 'required|string|unique:stores,code',
    'address' => 'required|string',
    'city_id' => 'required|exists:cities,id',
    'latitude' => 'required|numeric|between:-90,90',
    'longitude' => 'required|numeric|between:-180,180',
    'phone' => 'nullable|string|max:20',
    'email' => 'nullable|email|max:255',
    'delivery_radius' => 'nullable|numeric|min:0|max:100'
];

// Product Creation
$rules = [
    'name' => 'required|string|max:255',
    'sku' => 'required|string|unique:products,sku',
    'category_id' => 'required|exists:categories,id',
    'price' => 'required|numeric|min:0',
    'cost_price' => 'nullable|numeric|min:0',
    'stock_quantity' => 'nullable|integer|min:0',
    'description' => 'nullable|string',
    'barcode' => 'nullable|string|unique:products,barcode'
];

// Order Creation
$rules = [
    'customer_id' => 'required|exists:customers,id',
    'items' => 'required|array|min:1',
    'items.*.product_id' => 'required|exists:products,id',
    'items.*.quantity' => 'required|integer|min:1',
    'delivery_address' => 'required|string',
    'latitude' => 'required|numeric',
    'longitude' => 'required|numeric'
];
```

## 20.10 Example Notification Service

```php
<?php
// app/Services/NotificationService.php

namespace App\Services;

use App\Models\Notification;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;

class NotificationService
{
    public function sendSMS($phone, $message): bool
    {
        // Integrate with SMS gateway (Twilio, TextLocal, etc.)
        try {
            // SMS API call
            return true;
        } catch (\Exception $e) {
            Log::error('SMS sending failed: ' . $e->getMessage());
            return false;
        }
    }

    public function sendEmail($email, $subject, $template, $data): bool
    {
        try {
            Mail::send($template, $data, function ($message) use ($email, $subject) {
                $message->to($email)->subject($subject);
            });
            return true;
        } catch (\Exception $e) {
            Log::error('Email sending failed: ' . $e->getMessage());
            return false;
        }
    }

    public function sendPushNotification($deviceToken, $title, $body, $data = []): bool
    {
        // FCM integration
        try {
            // FCM API call
            return true;
        } catch (\Exception $e) {
            Log::error('Push notification failed: ' . $e->getMessage());
            return false;
        }
    }

    public function createNotification($userId, $type, $message, $data = []): Notification
    {
        return Notification::create([
            'user_id' => $userId,
            'type' => $type,
            'message' => $message,
            'data' => json_encode($data),
            'read_at' => null
        ]);
    }
}
```

## 20.11 Example Report Generation

```php
<?php
// app/Services/ReportService.php

namespace App\Services;

use App\Models\Order;
use App\Models\Store;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class ReportService
{
    public function generateSalesReport($storeId = null, $fromDate = null, $toDate = null)
    {
        $query = Order::where('status', 'completed');

        if ($storeId) {
            $query->where('store_id', $storeId);
        }

        if ($fromDate) {
            $query->whereDate('created_at', '>=', $fromDate);
        }

        if ($toDate) {
            $query->whereDate('created_at', '<=', $toDate);
        }

        return $query->select(
            DB::raw('DATE(created_at) as date'),
            DB::raw('COUNT(*) as total_orders'),
            DB::raw('SUM(total_amount) as total_sales'),
            DB::raw('AVG(total_amount) as average_order_value')
        )
        ->groupBy('date')
        ->orderBy('date')
        ->get();
    }

    public function generatePNLReport($storeId, $month, $year)
    {
        $startDate = Carbon::create($year, $month, 1)->startOfMonth();
        $endDate = Carbon::create($year, $month, 1)->endOfMonth();

        // Revenue
        $revenue = Order::where('store_id', $storeId)
            ->whereBetween('created_at', [$startDate, $endDate])
            ->where('status', 'completed')
            ->sum('total_amount');

        // Cost of Goods Sold
        $cogs = DB::table('order_items')
            ->join('orders', 'order_items.order_id', '=', 'orders.id')
            ->join('inventory', function($join) use ($storeId) {
                $join->on('order_items.product_id', '=', 'inventory.product_id')
                     ->where('inventory.store_id', '=', $storeId);
            })
            ->whereBetween('orders.created_at', [$startDate, $endDate])
            ->where('orders.status', 'completed')
            ->sum(DB::raw('order_items.quantity * inventory.cost_price'));

        // Expenses
        $expenses = DB::table('expenses')
            ->where('store_id', $storeId)
            ->whereBetween('date', [$startDate, $endDate])
            ->sum('amount');

        $grossProfit = $revenue - $cogs;
        $netProfit = $grossProfit - $expenses;

        return [
            'revenue' => $revenue,
            'cogs' => $cogs,
            'gross_profit' => $grossProfit,
            'expenses' => $expenses,
            'net_profit' => $netProfit,
            'profit_margin' => $revenue > 0 ? ($netProfit / $revenue) * 100 : 0
        ];
    }
}
```

## 20.12 Environment Configuration Example

```env
# .env.example

APP_NAME="StoreWise"
APP_ENV=production
APP_KEY=
APP_DEBUG=false
APP_URL=https://yourdomain.com

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=storewise
DB_USERNAME=root
DB_PASSWORD=

CACHE_DRIVER=redis
QUEUE_CONNECTION=redis
SESSION_DRIVER=redis

REDIS_HOST=127.0.0.1
REDIS_PASSWORD=null
REDIS_PORT=6379

# SMS Gateway
SMS_PROVIDER=twilio
TWILIO_SID=
TWILIO_TOKEN=
TWILIO_FROM=

# Payment Gateway
PAYMENT_GATEWAY=razorpay
RAZORPAY_KEY=
RAZORPAY_SECRET=

# Email
MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=
MAIL_PASSWORD=
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=noreply@storewise.com
MAIL_FROM_NAME="${APP_NAME}"

# FCM Push Notifications
FCM_SERVER_KEY=
FCM_SENDER_ID=

# Google Maps
GOOGLE_MAPS_API_KEY=
```

## 20.13 Useful Laravel Commands

```bash
# Create migration
php artisan make:migration create_stores_table

# Create model with migration
php artisan make:model Store -m

# Create controller
php artisan make:controller Admin/StoreController

# Create service
php artisan make:service OrderService

# Run migrations
php artisan migrate

# Rollback migration
php artisan migrate:rollback

# Seed database
php artisan db:seed

# Clear cache
php artisan cache:clear
php artisan config:clear
php artisan route:clear
php artisan view:clear

# Generate application key
php artisan key:generate

# Create storage link
php artisan storage:link

# Queue worker
php artisan queue:work

# Schedule tasks (add to crontab)
* * * * * cd /path-to-your-project && php artisan schedule:run >> /dev/null 2>&1
```

## 20.14 Database Indexes Recommendations

```php
// Important indexes for performance

// Stores table
$table->index('city_id');
$table->index('status');
$table->index(['latitude', 'longitude']); // For geospatial queries

// Products table
$table->index('category_id');
$table->index('sku');
$table->index('barcode');

// Orders table
$table->index('store_id');
$table->index('customer_id');
$table->index('status');
$table->index('created_at');

// Inventory table
$table->index(['store_id', 'product_id']);
$table->index('quantity'); // For low stock alerts

// Stock logs
$table->index(['store_id', 'product_id', 'created_at']);
```

## 20.15 Security Best Practices

1. **Authentication & Authorization**
   - Use Laravel's built-in authentication
   - Implement RBAC properly
   - Use middleware for route protection
   - Hash passwords (bcrypt)

2. **SQL Injection Prevention**
   - Use Eloquent ORM or parameterized queries
   - Never use raw queries with user input

3. **XSS Prevention**
   - Use Blade's `{{ }}` syntax (auto-escaped)
   - Sanitize user inputs
   - Use `{!! !!}` only when necessary

4. **CSRF Protection**
   - Include CSRF token in all forms
   - Use `@csrf` directive in Blade

5. **File Upload Security**
   - Validate file types
   - Store files outside public directory
   - Rename uploaded files
   - Scan for malware

6. **API Security**
   - Use JWT or Sanctum for API authentication
   - Implement rate limiting
   - Validate all inputs
   - Use HTTPS only

7. **Data Encryption**
   - Encrypt sensitive data at rest
   - Use HTTPS for data in transit
   - Encrypt database backups

---

# End of Master Detailed Documentation

**Document Version:** 1.0  
**Last Updated:** 04 Dec 2025  
**Prepared By:** RS Top Coder  
**Contact:** iqararahemad@rstopcoder.com | +91 8586087792

---

**Note:** This document is a living document and should be updated as the project evolves. All stakeholders should refer to this document for the latest system specifications and requirements.