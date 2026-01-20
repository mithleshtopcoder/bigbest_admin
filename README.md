Created an Artisan command to update product ratings:
   php artisan products:update-ratings
Or for a specific product:
   php artisan products:update-ratings --product-id=44



# Store-Wise Billing & Retail Management System

## Admin Panel - Laravel Application

This is the admin panel for the multi-city, multi-store retail management system built with Laravel and Tailwind CSS.

## Features

### ✅ Completed Admin Panel Modules

1. **Dashboard**
   - Analytics widgets with key metrics
   - Sales charts and trendsa
   - Top products and stores
   - Recent orders overview

2. **Store & Location Management**
   - Cities management
   - Zones management
   - Stores management with GPS coordinates

3. **Employee Management**
   - Employee listing and management
   - Role-based access control (RBAC)
   - Permission management

4. **Inventory & Purchase**
   - Stock overview
   - Product catalog management
   - Supplier management
   - Purchase Orders (PO)
   - Goods Received Note (GRN)

5. **Inter-store Transfer**
   - Stock transfer management
   - Transfer request approval workflow

6. **Ledger & Accounting**
   - Store-wise ledger
   - Supplier ledger
   - Customer ledger
   - Daily expense tracking

7. **Reports & Analytics**
   - Sales reports
   - Profit & Loss statements
   - GST reports
   - Inventory valuation

## Installation

1. Navigate to the backend directory:
```bash
cd backend
```

2. Install PHP dependencies:
```bash
composer install
```

3. Install Node dependencies:
```bash
npm install
```

4. Copy environment file:
```bash
cp .env.example .env
```

5. Generate application key:
```bash
php artisan key:generate
```

6. Build frontend assets:
```bash
npm run build
```

7. Start the development server:
```bash
php artisan serve
```

8. In another terminal, start Vite for hot reloading:
```bash
npm run dev
```

## Access the Admin Panel

Visit: `http://localhost:8000/admin/dashboard`

## Project Structure

```
backend/
├── app/
│   └── Http/
│       └── Controllers/
│           └── Admin/          # Admin controllers
├── resources/
│   ├── views/
│   │   └── admin/              # Admin Blade templates
│   │       ├── layout.blade.php
│   │       ├── dashboard.blade.php
│   │       ├── cities/
│   │       ├── zones/
│   │       ├── stores/
│   │       ├── employees/
│   │       ├── inventory/
│   │       ├── purchase/
│   │       ├── transfers/
│   │       ├── ledger/
│   │       └── reports/
│   ├── css/
│   │   └── app.css            # Tailwind CSS
│   └── js/
│       └── app.js
└── routes/
    └── web.php                 # Admin routes
```

## Routes

All admin routes are prefixed with `/admin`:

- `/admin/dashboard` - Dashboard
- `/admin/locations/cities` - Cities
- `/admin/locations/zones` - Zones
- `/admin/locations/stores` - Stores
- `/admin/employees` - Employees
- `/admin/employees/roles` - Roles & Permissions
- `/admin/inventory` - Inventory Overview
- `/admin/inventory/products` - Products
- `/admin/inventory/suppliers` - Suppliers
- `/admin/purchase/orders` - Purchase Orders
- `/admin/purchase/grn` - GRN
- `/admin/transfers` - Inter-store Transfers
- `/admin/transfers/requests` - Transfer Requests
- `/admin/ledger` - Ledger Overview
- `/admin/ledger/store` - Store Ledger
- `/admin/ledger/supplier` - Supplier Ledger
- `/admin/ledger/customer` - Customer Ledger
- `/admin/ledger/expenses` - Expenses
- `/admin/reports` - Reports Overview
- `/admin/reports/sales` - Sales Report
- `/admin/reports/profit-loss` - P&L Report
- `/admin/reports/gst` - GST Reports
- `/admin/reports/inventory-valuation` - Inventory Valuation

## Technologies Used

- **Backend:** Laravel 12
- **Frontend:** Blade Templates + Tailwind CSS 4
- **Charts:** Chart.js
- **Icons:** Heroicons (SVG)

## Design Features

- Modern, clean UI with Tailwind CSS
- Responsive design
- Beautiful gradient sidebar
- Interactive charts and graphs
- Status badges and indicators
- Card-based layouts
- Hover effects and transitions

## Next Steps

1. Implement authentication system
2. Connect to database
3. Implement CRUD operations
4. Add form validation
5. Implement search and filters
6. Add export functionality
7. Implement real-time updates

## Notes

- This is currently a static design implementation
- All data shown is sample/mock data
- Database models and migrations need to be created
- Authentication and authorization need to be implemented
