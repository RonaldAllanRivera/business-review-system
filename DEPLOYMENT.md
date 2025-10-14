# Laravel Deployment Guide for SiteGround

This guide provides step-by-step instructions for deploying a Laravel application to SiteGround hosting using Git.

## Prerequisites

- SiteGround hosting account with SSH access
- Git repository (GitHub)
- Composer installed locally
- PHP 8.2+ on both local and server
- Node.js and NPM (for asset compilation)

## Server Configuration

### 1. Connect to SiteGround via SSH

```bash
ssh username@yourdomain.com -p 18765  # Replace with your SiteGround SSH details
```

### 2. Verify PHP Version

```bash
php -v  # Should be 8.2 or higher
```

If needed, set PHP version via SiteGround's cPanel:
1. Go to cPanel → MultiPHP Manager
2. Select your domain and set PHP version to 8.2 or higher

### 3. Required PHP Extensions

Ensure these extensions are enabled:
- BCMath
- Ctype
- cURL
- DOM
- Fileinfo
- JSON
- Mbstring
- OpenSSL
- PDO
- Tokenizer
- XML
- ZIP

## Deployment Setup

### 1. Create Deployment Directory

```bash
cd ~/www/
mkdir -p yourdomain.com
cd yourdomain.com
```

### 2. Clone Your Repository

```bash
git clone https://github.com/yourusername/your-repo.git .
```

### 3. Install Dependencies

```bash
composer install --optimize-autoloader --no-dev
```

### 4. Environment Setup

```bash
cp .env.example .env
nano .env  # Edit with your production settings
```

### 5. Generate Application Key

```bash
php artisan key:generate
```

### 6. Set Permissions

```bash
chmod -R 755 storage bootstrap/cache
chmod -R 775 storage/
chmod -R 775 bootstrap/cache/
```

### 7. Database Setup

1. Create database in SiteGround cPanel
2. Update `.env` with database credentials
3. Run migrations:

```bash
php artisan migrate --force
```

### 8. Storage Link

```bash
php artisan storage:link
```

### 9. Optimize Application

```bash
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

## Web Server Configuration

### 1. Update .htaccess

Create/update `public/.htaccess`:

```apache
<IfModule mod_rewrite.c>
    <IfModule mod_negotiation.c>
        Options -MultiViews -Indexes
    </IfModule>

    RewriteEngine On

    # Handle Authorization Header
    RewriteCond %{HTTP:Authorization} .
    RewriteRule .* - [E=HTTP_AUTHORIZATION:%{HTTP:Authorization}]

    # Redirect Trailing Slashes If Not A Folder...
    RewriteCond %{REQUEST_FILENAME} !-d
    RewriteCond %{REQUEST_URI} (.+)/$
    RewriteRule ^ %1 [L,R=301]

    # Send Requests To Front Controller...
    RewriteCond %{REQUEST_FILENAME} !-d
    RewriteCond %{REQUEST_FILENAME} !-f
    RewriteRule ^ index.php [L]
</IfModule>
```

### 2. Set Document Root

In SiteGround cPanel:
1. Go to Websites → Manage
2. Find your domain and click "Site Tools"
3. Go to Site → File Manager
4. Navigate to `public_html`
5. Create or modify `.htaccess` with the following content:

```apache
<IfModule mod_rewrite.c>
    RewriteEngine on
    RewriteRule ^(.*)$ public/$1 [L]
</IfModule>
```

## Automated Deployment

### 1. Create Deployment Script

Create `deploy.sh` in your project root:

```bash
#!/bin/bash

# Exit on error
set -e

echo "Deploying application..."

# Change to the project directory
cd ~/www/yourdomain.com

# Pull the latest changes from the repository
git fetch --all
git reset --hard origin/main  # or your branch name

# Install/update Composer dependencies
composer install --no-interaction --prefer-dist --optimize-autoloader --no-dev

# Install NPM dependencies and build assets (if needed)
npm install
npm run build

# Run database migrations
php artisan migrate --force

# Clear caches
php artisan config:clear
php artisan cache:clear
php artisan view:clear

# Cache configuration
php artisan config:cache
php artisan route:cache
php artisan view:cache

echo "Deployment completed successfully!"
```

### 2. Make Script Executable

```bash
chmod +x deploy.sh
```

### 3. Set Up GitHub Webhook (Optional)

For automatic deployments on push:

1. In your GitHub repository, go to Settings → Webhooks → Add webhook
2. Set Payload URL to: `https://yourdomain.com/deploy`
3. Set Content type to `application/json`
4. Add a secret token
5. Create a route in your Laravel app to handle the webhook

## Post-Deployment Tasks

### 1. Set Up Queue Worker

For queue processing, set up a cron job:

```bash
* * * * * cd /home/username/yourdomain.com && php artisan queue:work --tries=3 >/dev/null 2>&1
```

### 2. Schedule Tasks

Add this to your server's crontab:

```bash
* * * * * cd /home/username/yourdomain.com && php artisan schedule:run >> /dev/null 2>&1
```

## Troubleshooting

### Common Issues

1. **500 Error After Deployment**
   - Check Laravel logs: `tail -f storage/logs/laravel.log`
   - Verify file permissions
   - Ensure `.env` is properly configured

2. **Asset Loading Issues**
   - Run `php artisan storage:link`
   - Check file permissions
   - Clear view cache: `php artisan view:clear`

3. **Database Connection Issues**
   - Verify database credentials in `.env`
   - Check if database server is running
   - Ensure database user has proper permissions

## Security Considerations

1. **Environment Variables**
   - Never commit `.env` to version control
   - Keep sensitive information in `.env`

2. **File Permissions**
   - Set strict permissions on sensitive files
   - Storage and bootstrap/cache should be writable by web server

3. **HTTPS**
   - Enable SSL in SiteGround cPanel
   - Force HTTPS in `.htaccess`

## Maintenance Mode

To put your site in maintenance mode:
```bash
php artisan down
```

To bring it back up:
```bash
php artisan up
```

## Backups

Set up regular backups through SiteGround cPanel:
1. Go to Backups
2. Schedule automatic backups
3. Consider off-site backup solutions

---

This guide covers the essential steps for deploying a Laravel application to SiteGround. Adjust paths and settings according to your specific setup.
