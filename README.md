# Business Review System

[![License: MIT](https://img.shields.io/badge/License-MIT-yellow.svg)](https://opensource.org/licenses/MIT)
[![Laravel](https://img.shields.io/badge/Laravel-12.x-FF2D20?logo=laravel)](https://laravel.com/)
[![React](https://img.shields.io/badge/React-18.x-61DAFB?logo=react)](https://reactjs.org/)
[![PHP](https://img.shields.io/badge/PHP-8.2+-777BB4?logo=php)](https://www.php.net/)
[![API Documentation](https://img.shields.io/badge/API-Documentation-4FC08D?logo=swagger)](https://admin.artworkwebsite.com/api/documentation)

## Overview

A high-performance, scalable business review platform that enables users to discover, rate, and review local businesses. Built with modern web technologies following industry best practices for security, performance, and maintainability.

### Key Achievements
- **Robust Backend**: Implemented a secure, RESTful API with Laravel 12, featuring comprehensive test coverage and API documentation
- **Modern Frontend**: Developed an intuitive user interface using React 18 and Next.js 14 with responsive design principles
- **Admin Dashboard**: Created a powerful management interface using Filament 4 for efficient content and user management
- **Performance**: Optimized database queries and implemented caching strategies for sub-second response times
- **Security**: Implemented industry-standard authentication, authorization, and data validation
- **CI/CD**: Established automated testing and deployment pipelines for reliable releases

## 📋 Table of Contents

- [✨ Features](#-features)
  - [Public Features](#public-features)
  - [Admin Features](#admin-features)
- [🚀 Tech Stack](#-tech-stack)
  - [Backend](#backend)
  - [Frontend](#frontend)
  - [Admin Panel](#admin-panel)
- [📚 API Documentation](#api-documentation)
  - [Features](#features)
  - [Access](#access)
- [🚀 Getting Started](#-getting-started)
  - [Prerequisites](#prerequisites)
  - [Installation](#installation)
  - [Environment Setup](#environment-setup)
  - [Running the Application](#running-the-application)
- [🧪 Testing](#-testing)
- [🤝 Contributing](#-contributing)
- [📄 License](#-license)

---

## Features

### Public Features
- Browse and search businesses
- Submit and read reviews
- User profiles and review history
- Business ratings and statistics
- Responsive design for all devices

### Admin Features
- **Role-Based Access Control**
  - Granular permissions for admin and moderator roles
  - Secure access control to admin features
  - Permission management interface
- **Review Moderation**
  - Approve/reject reviews with custom rejection reasons
  - Bulk moderation actions
  - Email notifications for moderated reviews
  - Audit trail of moderation actions
- **Admin Dashboard**
  - Review statistics and metrics
  - Pending reviews queue
  - User activity monitoring
  - System health status
- **Business Management**
  - Full CRUD operations
  - Advanced filtering and search
  - Business verification system
- **User Management**
  - User roles and permissions
  - Activity logs
  - Account management
- **Analytics & Reporting**
  - Review trends
  - User engagement metrics
  - Exportable reports
- **System Configuration**
  - Email templates
  - Review guidelines
  - System settings

## Tech Stack

### Backend
- PHP 8.2+
- Laravel 12.x
- MySQL 8.0+
- Redis

### Frontend
- React 18
- Next.js 14
- TypeScript
- Tailwind CSS
- Shadcn UI

### Admin Panel
- Filament 4
- Livewire 3
- Alpine.js

## API Documentation

## 📚 API Documentation

The API is fully documented using OpenAPI 3.0 specification with Swagger UI.

### Features
- Interactive API explorer
- Try-it-out functionality for all endpoints
- Detailed request/response schemas
- Authentication examples
- Error response documentation
- Request validation rules
- Response status codes
- Example values

### Access
- **Swagger UI**: [https://admin.artworkwebsite.com/api/documentation](https://admin.artworkwebsite.com/api/documentation)
- **OpenAPI JSON**: [https://admin.artworkwebsite.com/docs?api-docs.json](https://admin.artworkwebsite.com/docs?api-docs.json)
- **Admin Panel Access**: Available in the sidebar under "API Documentation" and "OpenAPI JSON"

### Authentication
The API uses Bearer token authentication. Include your token in the `Authorization` header:

### Features
- Interactive API documentation
- Try-it-out functionality
- Authentication details
- Request/response examples
- Model schemas

### Access
- Public endpoints are accessible without authentication
- Protected endpoints require a valid API token
- Use the "Authorize" button to set your API token

## Getting Started

### Prerequisites
- PHP 8.2+
- Composer
- Node.js 18+
- MySQL 8.0+
- Redis

### Installation

1. Clone the repository:
   ```bash
   git clone https://github.com/RonaldAllanRivera/business-review-system.git
   cd business-review-system
   ```

2. Install PHP dependencies:
   ```bash
   composer install
   ```

3. Install JavaScript dependencies:
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

6. Configure your `.env` file with database and other settings.

7. Run migrations and seeders:
   ```bash
   php artisan migrate --seed
   ```

8. Build assets:
   ```bash
   npm run build
   ```

9. Start the development server:
   ```bash
   php artisan serve
   ```

## API

All API routes are versioned and served under the `/api/v1` prefix.

- Base path: `/api/v1`
- Health check: `GET /api/v1/health`

Example:

curl -s http://localhost:8000/api/v1/health | jq
```
Routing is registered in `backend/bootstrap/app.php` and routes are defined in `backend/routes/api.php`.

Currently available stub endpoints:

- `GET /api/v1/businesses`
- `GET /api/v1/reviews`

### Auth (Sanctum)

- `POST /api/v1/auth/register` (name, email, password, password_confirmation)
- `POST /api/v1/auth/login` (email, password)
- `GET /api/v1/auth/me` (Bearer token)
- `POST /api/v1/auth/logout` (Bearer token)

Example login + authenticated request:

```bash
TOKEN=$(curl -s -X POST http://localhost:8000/api/v1/auth/login \
  -H 'Content-Type: application/json' \
  -d '{"email":"test@example.com","password":"password"}' | jq -r .token)
  
curl -s http://localhost:8000/api/v1/auth/me -H "Authorization: Bearer $TOKEN" | jq
```
### Business endpoints

- `GET /api/v1/businesses` supports advanced filters and sorting:
  - `q` (search name/description)
  - `min_rating`, `max_rating`
  - `from`, `to` (YYYY-MM-DD for `created_at`)
  - `min_reviews` (ensures at least N reviews)
  - `sort` one of `id`, `name`, `rating`, `created_at`, `reviews_count` (prefix with `-` for desc)
  - `per_page`
- `GET /api/v1/businesses/{id}`
- `POST /api/v1/businesses` (Bearer token)
- `PUT /api/v1/businesses/{id}` (Bearer token)
- `DELETE /api/v1/businesses/{id}` (Bearer token)

Caching behavior:
- List responses are cached per unique URL for ~60 seconds and invalidated on create/update/delete
- Show responses are cached for 5 minutes and invalidated on update/delete of that business

### Review endpoints

- `GET /api/v1/reviews` supports `business_id`, `user_id`, `sort`, `per_page`
- `GET /api/v1/reviews/{id}`
- `POST /api/v1/reviews` (Bearer token)
- `PUT /api/v1/reviews/{id}` (Bearer token; owner only)
- `DELETE /api/v1/reviews/{id}` (Bearer token; owner only)

## Development

### Running Tests
```bash
php artisan test
```

### Code Style
This project follows PSR-12 coding standards. To fix code style issues:

```bash
composer fix
```

## Contributing

1. Fork the repository
2. Create your feature branch (`git checkout -b feature/AmazingFeature`)
3. Commit your changes (`git commit -m 'Add some AmazingFeature'`)
4. Push to the branch (`git push origin feature/AmazingFeature`)
5. Open a Pull Request

## License

This project is licensed under the MIT License - see the [LICENSE](LICENSE) file for details.

## Acknowledgments

- Laravel Community
- React Team
- Filament Team
- All contributors
