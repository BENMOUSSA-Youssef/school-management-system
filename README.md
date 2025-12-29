# 🎓 School Management System

A comprehensive web-based school management system built with Laravel, designed to streamline student information management, grade tracking, and academic record keeping. This system provides separate dashboards for teachers and students with role-based access control.

![Laravel](https://img.shields.io/badge/Laravel-12.0-red.svg)
![PHP](https://img.shields.io/badge/PHP-8.2-blue.svg)
![License](https://img.shields.io/badge/license-MIT-green.svg)

## 📋 Table of Contents

- [Features](#-features)
- [Tech Stack](#-tech-stack)
- [Screenshots](#-screenshots)
- [Installation](#-installation)
- [Configuration](#-configuration)
- [Usage](#-usage)
- [Project Structure](#-project-structure)
- [Database Schema](#-database-schema)
- [API Routes](#-api-routes)
- [Contributing](#-contributing)
- [License](#-license)

## ✨ Features

### For Teachers
- **Student Management**: Create, view, edit, and delete student records
- **Module Management**: Manage academic modules with codes, coefficients, and exam dates
- **Grade Management**: Record and update student grades with bulk update capabilities
- **Results Dashboard**: View comprehensive results and statistics
- **Absence Tracking**: Monitor student attendance and absences
- **Weighted Average Calculation**: Automatic calculation of weighted averages based on module coefficients

### For Students
- **Personal Dashboard**: View personal academic information
- **Grade Viewing**: Access grades for all enrolled modules
- **Results Overview**: View calculated averages and academic status
- **Profile Management**: Update personal profile information

### System Features
- **Role-Based Access Control**: Separate access levels for teachers and students
- **Authentication System**: Secure login and registration
- **Weighted Grade Calculation**: Automatic average calculation using module coefficients
- **Status Determination**: Automatic pass/fail status based on averages
- **Mention System**: Academic mentions (Très Bien, Bien, Assez Bien, Passable, Ajourné)
- **SQLite Database**: Lightweight database solution for easy deployment

## 🛠 Tech Stack

- **Backend Framework**: Laravel 12.0
- **Programming Language**: PHP 8.2+
- **Database**: SQLite
- **Frontend**: Blade Templates, JavaScript, CSS
- **Authentication**: Laravel Authentication
- **Architecture**: MVC (Model-View-Controller)

## 📸 Screenshots

> _Screenshots will be added here_

## 🚀 Installation

### Prerequisites

- PHP 8.2 or higher
- Composer
- Node.js and npm (for asset compilation)
- SQLite (or any database supported by Laravel)

### Step 1: Clone the Repository

```bash
git clone https://github.com/yourusername/school-management-system.git
cd school-management-system
```

### Step 2: Install Dependencies

```bash
# Install PHP dependencies
composer install

# Install Node.js dependencies
npm install
```

### Step 3: Environment Configuration

```bash
# Copy the environment file
cp .env.example .env

# Generate application key
php artisan key:generate
```

### Step 4: Database Setup

```bash
# Create SQLite database (if using SQLite)
touch database/database.sqlite

# Run migrations
php artisan migrate
```

### Step 5: Build Assets

```bash
# Build frontend assets
npm run build

# Or for development with hot reload
npm run dev
```

### Step 6: Start the Server

```bash
# Start Laravel development server
php artisan serve
```

Visit `http://localhost:8000` in your browser.

### Quick Start Script

Alternatively, you can use the provided quick start script:

```bash
chmod +x QUICK_START.sh
./QUICK_START.sh
```

## ⚙️ Configuration

### Database Configuration

Edit the `.env` file to configure your database:

```env
DB_CONNECTION=sqlite
DB_DATABASE=/absolute/path/to/database/database.sqlite
```

For other databases (MySQL, PostgreSQL), update the connection settings accordingly.

### User Roles

The system supports two roles:
- `teacher`: Full access to all management features
- `student`: Access to personal dashboard and grades

## 📖 Usage

### Creating a Teacher Account

1. Navigate to `/register`
2. Fill in your details
3. Select "teacher" as your role
4. Complete registration

### Creating a Student Account

1. Navigate to `/register`
2. Fill in your details
3. Select "student" as your role
4. Complete registration
5. A teacher must link your user account to a student record

### Teacher Workflow

1. **Login** at `/login`
2. **Add Students**: Navigate to Students section and add new students
3. **Add Modules**: Create academic modules with codes and coefficients
4. **Record Grades**: Enter grades for students in each module
5. **View Results**: Check the Results dashboard for comprehensive statistics

### Student Workflow

1. **Login** at `/login`
2. **View Dashboard**: Access your personal dashboard at `/student/dashboard`
3. **Check Grades**: View all your grades and calculated averages
4. **View Profile**: Update your profile information

## 📁 Project Structure

```
school-management-system/
├── app/
│   ├── Http/
│   │   ├── Controllers/      # Application controllers
│   │   └── Middleware/       # Custom middleware
│   ├── Models/               # Eloquent models
│   │   ├── Student.php
│   │   ├── Module.php
│   │   ├── Grade.php
│   │   └── User.php
│   ├── Traits/               # Reusable traits
│   │   └── CalculatesAverage.php
│   └── View/
│       └── Components/        # Blade components
├── database/
│   ├── migrations/            # Database migrations
│   └── seeders/              # Database seeders
├── resources/
│   └── views/                # Blade templates
│       ├── index.blade.php
│       ├── students.blade.php
│       ├── modules.blade.php
│       ├── grades.blade.php
│       ├── results.blade.php
│       └── student-dashboard.blade.php
├── routes/
│   └── web.php               # Web routes
└── public/                   # Public assets
```

## 🗄 Database Schema

### Users Table
- `id`: Primary key
- `name`: User full name
- `email`: Unique email address
- `password`: Hashed password
- `role`: User role (teacher/student)
- `timestamps`: Created/updated timestamps

### Students Table
- `id`: Primary key
- `full_name`: Student full name
- `registration_number`: Unique registration number
- `class`: Student class
- `user_id`: Foreign key to users table
- `timestamps`: Created/updated timestamps

### Modules Table
- `id`: Primary key
- `name`: Module name
- `code`: Module code
- `coefficient`: Weight for grade calculation
- `exam_date`: Date of examination
- `timestamps`: Created/updated timestamps

### Grades Table
- `id`: Primary key
- `student_id`: Foreign key to students table
- `module_id`: Foreign key to modules table
- `score`: Grade score
- `absences`: Number of absences
- `timestamps`: Created/updated timestamps

## 🛣 API Routes

### Authentication Routes
- `GET/POST /login` - User login
- `POST /logout` - User logout
- `GET/POST /register` - User registration

### Teacher Routes (Protected)
- `GET /dashboard` - Teacher dashboard
- `GET /students` - List all students
- `POST /students` - Create new student
- `GET /students/{id}/edit` - Edit student
- `PUT /students/{id}` - Update student
- `DELETE /students/{id}` - Delete student
- `GET /modules` - List all modules
- `POST /modules` - Create new module
- `GET /modules/{id}/edit` - Edit module
- `PUT /modules/{id}` - Update module
- `DELETE /modules/{id}` - Delete module
- `GET /grades` - List all grades
- `POST /grades` - Create new grade
- `POST /grades/bulk-update` - Bulk update grades
- `GET /results` - View results dashboard

### Student Routes (Protected)
- `GET /student/dashboard` - Student dashboard

### Common Routes (Protected)
- `GET /profile` - User profile

## 🧪 Testing

```bash
# Run tests
php artisan test
```

## 🤝 Contributing

Contributions are welcome! Please feel free to submit a Pull Request. For major changes, please open an issue first to discuss what you would like to change.

1. Fork the repository
2. Create your feature branch (`git checkout -b feature/AmazingFeature`)
3. Commit your changes (`git commit -m 'Add some AmazingFeature'`)
4. Push to the branch (`git push origin feature/AmazingFeature`)
5. Open a Pull Request

## 📝 License

This project is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).

## 👤 Author

**Your Name**

- GitHub: [@yourusername](https://github.com/yourusername)
- Email: your.email@example.com

## 🙏 Acknowledgments

- Built with [Laravel](https://laravel.com)
- Thanks to all contributors who have helped improve this project

---

⭐ If you find this project helpful, please consider giving it a star!
