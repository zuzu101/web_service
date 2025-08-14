# Laravel Retail Service Management System

A comprehensive web-based service management system built with Laravel 11.x for managing retail service operations, customer orders, and employee workflows.

## 🚀 Features

### Admin Panel
- **Dashboard** with statistics (total orders, users, pending/completed orders)
- **Order Management** (CRUD operations)
- **Manual Order Input** for walk-in customers
- **Employee Assignment** for service orders
- **Service Type Management** (Hardware, Software, Maintenance & Cleaning, Printer & PC)
- **Location Management** (Ujung Berung, Getaway)
- **Real-time Order Status** tracking

### User Features
- **User Registration** and Authentication
- **Order Submission** with WhatsApp-style message interface
- **Order Tracking** with status updates
- **Profile Management**
- **Order History** with employee and pricing details

### Service Types & Pricing
1. **Service Hardware** - Rp 150,000
2. **Service Software** - Rp 100,000  
3. **Maintenance & Cleaning** - Rp 75,000
4. **Service Printer & PC** - Rp 125,000

## 🛠 Tech Stack

- **Backend**: Laravel 11.x (Stable LTS)
- **Database**: MySQL
- **Frontend**: Blade Templates, Vanilla JavaScript
- **Authentication**: Laravel Session-based Auth
- **Architecture**: MVC Pattern with Direct Database Operations

## 📋 Database Schema

### Core Tables
- `users` - Customer and admin accounts
- `employees` - Service technicians
- `transactions` - Main orders table
- `items` - Service items/devices
- `services` - Service types with pricing
- `locations` - Service locations (Ujung Berung, Getaway)
- `transaction_status` - Order status tracking

## 🚀 Installation

### Prerequisites
- PHP >= 8.2
- Composer
- MySQL
- Laravel 11.x

### Setup Steps

1. **Clone the repository**
```bash
git clone https://github.com/zuzu101/web_service.git
cd web_service
```

2. **Install dependencies**
```bash
composer install
```

3. **Environment setup**
```bash
cp .env.example .env
php artisan key:generate
```

4. **Database configuration**
Update `.env` file with your database credentials:
```
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=your_database_name
DB_USERNAME=your_username
DB_PASSWORD=your_password
```

5. **Run migrations**
```bash
php artisan migrate
```

6. **Setup initial data**
Visit: `http://your-domain/setup-admin` to create admin user and sample data

7. **Start the server**
```bash
php artisan serve
```

## 👤 Default Login Credentials

### Admin Access
- **URL**: `/admin/dashboard`
- **Username**: `admin`
- **Password**: `admin123`

### User Access
- Register new users at `/register`
- Login at `/login`

## 🗺 Key Routes

### Admin Routes
- `GET /admin/dashboard` - Admin dashboard with statistics
- `GET /admin/orders` - List all orders
- `GET /admin/orders/create` - Create order form
- `GET /admin/orders/{id}` - View order details
- `GET /admin/orders/{id}/edit` - Edit order

### User Routes  
- `GET /user/dashboard` - User dashboard
- `GET /user/order` - Order submission form
- `GET /user/profile` - Profile management

### Setup Route
- `GET /setup-admin` - Initialize admin user and sample data

## 💡 Key Features

### Direct Database Operations
- No API layer - direct Eloquent operations
- Optimized for speed and simplicity
- Complete CRUD functionality

### Role-based System
- **Admin (user_id = 999)**: Full system access
- **Regular Users**: Personal orders only
- Automatic role-based redirects

### Service Locations
- **Ujung Berung, Bandung**
- **Getaway, Bandung**

### Order Status Tracking
- Pending, Completed, Paid status
- Employee assignment tracking
- Real-time status updates

## 🤝 Contributing

1. Fork the repository
2. Create feature branch (`git checkout -b feature/AmazingFeature`)
3. Commit changes (`git commit -m 'Add AmazingFeature'`)
4. Push to branch (`git push origin feature/AmazingFeature`)
5. Open Pull Request

## 📝 License

This project is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).

## 👨‍💻 Author

**zuzu101** - [GitHub Profile](https://github.com/zuzu101)

---

Built with **Laravel 11.x** - The stable and widely-adopted LTS version of Laravel framework.

⭐ **Star this repository if you find it helpful!**
