# Changelog

All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.0.0/),
and this project adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

## [Unreleased]

### Added
- **Admin Dashboard**
  - Review statistics and metrics widgets
  - Real-time review queue management
  - User activity monitoring
  - System health status overview
- **Role-Based Access Control**
  - Admin and moderator user roles
  - Granular permission system
  - Secure admin panel access control
  - Permission management interface
- **Review Moderation**
  - Approve/reject individual reviews
  - Bulk moderation actions
  - Custom rejection reasons
  - Email notifications for moderated reviews
  - Audit trail of all moderation actions
  - View page for detailed review inspection
- **Business Management**
  - Advanced filtering and search
  - Business verification system
  - Detailed business metrics
- **User Management**
  - Role assignment
  - Activity logging
  - Account status management
- Business listing advanced filters and sorting
  - `q`, `min_rating`, `max_rating`, `from`, `to`, `min_reviews`
  - Sorting by `reviews_count` (supports `-reviews_count`)
- Caching for business endpoints
  - Index: 60s per-URL caching with versioned invalidation
  - Show: 5m per-business caching with invalidation on update/delete
- Interactive API documentation with Swagger UI
  - Accessible at `/api/documentation`
  - Automatic API spec generation
  - Try-it-out functionality
  - Authentication support
  - Request/response examples
  - Model schemas
  - Documentation at `/api/documentation.json`
- Initial project setup with Laravel 12
- React.js/Next.js frontend scaffolding
- Filament 4 admin panel
- Authentication system
- Database migrations for core entities
- API endpoints for businesses and reviews
- Review question management
- Testing framework setup
- CI/CD pipeline configuration
- API routing registered in `backend/bootstrap/app.php`
- Versioned API routes in `backend/routes/api.php` with `GET /api/v1/health`
- Base API controllers: `HealthController`, `BusinessController`, `ReviewController`
- JSON resources: `UserResource`, `BusinessResource`, `ReviewResource`
- Feature test for health check: `tests/Feature/Api/HealthTest.php`

### Changed
- JSON resources configured to return unwrapped responses via `AppServiceProvider`
- Documentation updates in `README.md` (API section, correct clone URL)
- Default configuration for production readiness:
  - Cache store default switched to `database` (`config/cache.php`)
  - Queue connection default switched to `database` (`config/queue.php`)
  - Session driver default switched to `database` (`config/session.php`)
 - `BusinessController` updated to apply advanced filters and caching
 - `TESTS.md` updated with full Postman collection including advanced business filters

### Fixed
- 500 error on `GET /api/v1/businesses?min_reviews=5&sort=-reviews_count` by using `has('reviews', '>=', N)` in `withReviewsCountMin()` to avoid duplicate `withCount` conflicts when sorting by `reviews_count`

## [0.1.0] - 2024-03-15
### Added
- Project initialization
- Basic project structure
- Development environment setup
- Initial documentation

## [0.2.0] - 2024-03-22
### Added
- User authentication system
- Business management
- Review system
- Admin dashboard
- API documentation

### Changed
- Updated dependencies
- Improved error handling
- Enhanced security measures

## [0.3.0] - 2024-03-29
### Added
- Advanced search functionality
- Review moderation tools
- User management
- Role-based access control
- Email notifications

### Fixed
- Performance issues
- Security vulnerabilities
- UI/UX improvements

## [1.0.0] - 2024-04-05
### Added
- Production-ready features
- Comprehensive test coverage
- Deployment scripts
- Monitoring setup

### Changed
- Code optimization
- Documentation updates
- Performance improvements
