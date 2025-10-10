# Business Review System

A modern, full-featured business review platform built with Laravel 12, React.js, and Filament 4.

## Features

### Public Features
- Browse and search businesses
- Submit and read reviews
- User profiles and review history
- Business ratings and statistics
- Responsive design for all devices

### Admin Features
- Business management
- Review moderation
- User management
- Custom review questions
- Analytics and reporting
- System configuration

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
   git clone https://github.com/yourusername/business-review-system.git
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
