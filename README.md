# Suara Bilal - News Portal

A PHP-based news portal system with user roles, article management, and commenting features.

## Features

- **User Management**
  - Multiple role levels (Admin, Writer, User)
  - User registration and authentication
  - Profile management

- **Article System**
  - Create and read articles
  - Category management
  - Comment system
  - Pagination

- **Admin Features**
  - User role management
  - Category management
  - Content moderation

## Requirements

- PHP 7.4 or higher
- MySQL/MariaDB
- Web server (Apache/Nginx)
- PDO PHP Extension

## Installation

1. Clone or download the repository
2. Import the database schema from `database.sql`
3. Configure database connection in `config/database.php`
4. Ensure proper permissions on directories
5. Set up your web server to point to the project directory

## Database Configuration

Update `config/database.php` with your database credentials:

```php
private $host = "localhost";
private $db_name = "SuaraBilal";
private $username = "your_username";
private $password = "your_password";
```

## User Roles

### Admin (role_id: 1)
- Full system access
- Manage users and their roles
- Create/edit/delete categories
- Manage all articles and comments
- Access to admin dashboard

### Writer (role_id: 2)
- Create new articles
- Edit own articles
- Delete own articles
- Comment on any article
- View basic analytics

### User (role_id: 3)
- Read all articles
- Post comments
- Edit own profile
- View public content

## Project Structure

```
suarabilal/
├── config/
│   └── database.php          # Database configuration
├── models/
│   ├── Article.php          # Article management
│   ├── Category.php         # Category management
│   └── User.php            # User management
├── includes/
│   ├── header.php          # Common header
│   └── footer.php          # Common footer
├── admin/
│   ├── manage-users.php    # User management interface
│   └── manage-categories.php # Category management
├── assets/
│   ├── css/               # Stylesheets
│   └── js/                # JavaScript files
└── [other PHP files]      # Main application files
```

## Security Implementation

### Authentication & Authorization
- Secure password hashing using `password_hash()`
- Session-based authentication
- Role-based access control (RBAC)

### Database Security
- PDO prepared statements to prevent SQL injection
- Parameterized queries for all database operations
- Input validation and sanitization

### General Security
- XSS prevention through output escaping
- CSRF protection on forms
- Secure session handling
- Input validation on all user inputs

## Contributing Guidelines

1. **Fork the Repository**
   - Create your own fork of the code

2. **Create a Branch**
   - Make a branch for your feature: `git checkout -b feature/YourFeature`

3. **Code Standards**
   - Follow PSR-12 coding standards
   - Include comments for complex logic
   - Maintain existing code style

4. **Testing**
   - Test your changes thoroughly
   - Ensure existing features still work

5. **Submit Pull Request**
   - Push to your fork
   - Submit a pull request with clear description

## License

This project is licensed under the MIT License. See the LICENSE file for details.
