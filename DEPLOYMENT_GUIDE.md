# EduApp Deployment Guide

यह guide आपको production में EduApp को deploy करने में मदद करेगा।

---

## Pre-Deployment Checklist

### 1. Server Requirements

#### Minimum Requirements
- **OS:** Ubuntu 20.04 LTS / 22.04 LTS
- **RAM:** 2GB (4GB recommended)
- **Storage:** 20GB SSD
- **CPU:** 2 cores
- **PHP:** 8.2 or higher
- **MySQL/MariaDB:** 8.0+
- **Redis:** 6.0+
- **Nginx/Apache:** Latest stable

#### PHP Extensions Required
```bash
php-cli
php-fpm
php-mysql
php-mbstring
php-xml
php-curl
php-zip
php-gd
php-intl
php-bcmath
php-redis
php-imagick
```

---

## Step 1: Server Setup (Ubuntu)

### Update System
```bash
sudo apt update && sudo apt upgrade -y
```

### Install PHP 8.2
```bash
sudo apt install software-properties-common -y
sudo add-apt-repository ppa:ondrej/php -y
sudo apt update
sudo apt install php8.2 php8.2-fpm php8.2-mysql php8.2-mbstring \
  php8.2-xml php8.2-curl php8.2-zip php8.2-gd php8.2-intl \
  php8.2-bcmath php8.2-redis -y
```

### Install Composer
```bash
curl -sS https://getcomposer.org/installer | php
sudo mv composer.phar /usr/local/bin/composer
```

### Install Nginx
```bash
sudo apt install nginx -y
sudo systemctl enable nginx
sudo systemctl start nginx
```

### Install MySQL
```bash
sudo apt install mysql-server -y
sudo mysql_secure_installation
```

### Install Redis
```bash
sudo apt install redis-server -y
sudo systemctl enable redis
sudo systemctl start redis
```

### Install Git
```bash
sudo apt install git -y
```

---

## Step 2: Create Database

```bash
# Login to MySQL
sudo mysql -u root -p

# Create database and user
CREATE DATABASE eduapp CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
CREATE USER 'eduapp_user'@'localhost' IDENTIFIED BY 'strong_password_here';
GRANT ALL PRIVILEGES ON eduapp.* TO 'eduapp_user'@'localhost';
FLUSH PRIVILEGES;
EXIT;
```

---

## Step 3: Deploy Application

### Clone Repository
```bash
# Create directory
sudo mkdir -p /var/www/eduapp
sudo chown -R $USER:$USER /var/www/eduapp

# Clone (or upload files)
cd /var/www/eduapp
git clone https://github.com/yourusername/eduapp.git .

# OR if uploading via FTP/SCP, ensure all files are in /var/www/eduapp
```

### Install Dependencies
```bash
cd /var/www/eduapp
composer install --optimize-autoloader --no-dev
```

### Setup Environment
```bash
# Copy environment file
cp .env.example .env

# Edit .env file
nano .env
```

### Configure .env for Production
```env
APP_NAME=EduApp
APP_ENV=production
APP_DEBUG=false
APP_URL=https://yourdomain.com

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=eduapp
DB_USERNAME=eduapp_user
DB_PASSWORD=strong_password_here

QUEUE_CONNECTION=redis
CACHE_STORE=redis
SESSION_DRIVER=redis

REDIS_HOST=127.0.0.1
REDIS_PASSWORD=null
REDIS_PORT=6379

# Your actual credentials
RAZORPAY_KEY=rzp_live_your_key
RAZORPAY_SECRET=your_secret

VIMEO_ACCESS_TOKEN=your_token

AWS_ACCESS_KEY_ID=your_key
AWS_SECRET_ACCESS_KEY=your_secret
AWS_DEFAULT_REGION=ap-south-1
AWS_BUCKET=your_bucket
```

### Generate App Key
```bash
php artisan key:generate
```

### Run Migrations
```bash
php artisan migrate --force
```

### Optimize Application
```bash
php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan optimize
```

### Storage Permissions
```bash
sudo chown -R www-data:www-data /var/www/eduapp/storage
sudo chown -R www-data:www-data /var/www/eduapp/bootstrap/cache
chmod -R 775 /var/www/eduapp/storage
chmod -R 775 /var/www/eduapp/bootstrap/cache
```

### Create Storage Link
```bash
php artisan storage:link
```

---

## Step 4: Configure Nginx

### Create Nginx Configuration
```bash
sudo nano /etc/nginx/sites-available/eduapp
```

### Nginx Config File
```nginx
server {
    listen 80;
    server_name yourdomain.com www.yourdomain.com;
    root /var/www/eduapp/public;

    add_header X-Frame-Options "SAMEORIGIN";
    add_header X-Content-Type-Options "nosniff";

    index index.php;

    charset utf-8;

    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    location = /favicon.ico { access_log off; log_not_found off; }
    location = /robots.txt  { access_log off; log_not_found off; }

    error_page 404 /index.php;

    location ~ \.php$ {
        fastcgi_pass unix:/var/run/php/php8.2-fpm.sock;
        fastcgi_param SCRIPT_FILENAME $realpath_root$fastcgi_script_name;
        include fastcgi_params;
    }

    location ~ /\.(?!well-known).* {
        deny all;
    }
}
```

### Enable Site
```bash
sudo ln -s /etc/nginx/sites-available/eduapp /etc/nginx/sites-enabled/
sudo nginx -t
sudo systemctl reload nginx
```

---

## Step 5: SSL Certificate (Let's Encrypt)

### Install Certbot
```bash
sudo apt install certbot python3-certbot-nginx -y
```

### Obtain Certificate
```bash
sudo certbot --nginx -d yourdomain.com -d www.yourdomain.com
```

### Auto-renewal (already set up by certbot)
Test renewal:
```bash
sudo certbot renew --dry-run
```

---

## Step 6: Setup Queue Workers

### Create Supervisor Config
```bash
sudo nano /etc/supervisor/conf.d/eduapp-worker.conf
```

### Supervisor Configuration
```ini
[program:eduapp-worker]
process_name=%(program_name)s_%(process_num)02d
command=php /var/www/eduapp/artisan queue:work redis --sleep=3 --tries=3 --max-time=3600
autostart=true
autorestart=true
stopasgroup=true
killasgroup=true
user=www-data
numprocs=4
redirect_stderr=true
stdout_logfile=/var/www/eduapp/storage/logs/worker.log
stopwaitsecs=3600
```

### Install and Start Supervisor
```bash
sudo apt install supervisor -y
sudo supervisorctl reread
sudo supervisorctl update
sudo supervisorctl start eduapp-worker:*
```

### Check Status
```bash
sudo supervisorctl status
```

---

## Step 7: Setup Cron Jobs

### Edit Crontab
```bash
sudo crontab -e -u www-data
```

### Add Laravel Scheduler
```cron
* * * * * cd /var/www/eduapp && php artisan schedule:run >> /dev/null 2>&1
```

---

## Step 8: Configure Firewall

```bash
sudo ufw allow OpenSSH
sudo ufw allow 'Nginx Full'
sudo ufw enable
sudo ufw status
```

---

## Step 9: Performance Optimization

### PHP-FPM Optimization
```bash
sudo nano /etc/php/8.2/fpm/pool.d/www.conf
```

Update these values:
```ini
pm = dynamic
pm.max_children = 50
pm.start_servers = 10
pm.min_spare_servers = 5
pm.max_spare_servers = 20
pm.max_requests = 500
```

Restart PHP-FPM:
```bash
sudo systemctl restart php8.2-fpm
```

### MySQL Optimization
```bash
sudo nano /etc/mysql/mysql.conf.d/mysqld.cnf
```

Add/Update:
```ini
[mysqld]
max_connections = 200
innodb_buffer_pool_size = 1G
innodb_log_file_size = 256M
query_cache_size = 0
query_cache_type = 0
```

Restart MySQL:
```bash
sudo systemctl restart mysql
```

### Redis Optimization
```bash
sudo nano /etc/redis/redis.conf
```

Update:
```ini
maxmemory 256mb
maxmemory-policy allkeys-lru
```

Restart Redis:
```bash
sudo systemctl restart redis
```

---

## Step 10: Logging & Monitoring

### Setup Log Rotation
```bash
sudo nano /etc/logrotate.d/eduapp
```

```
/var/www/eduapp/storage/logs/*.log {
    daily
    missingok
    rotate 14
    compress
    delaycompress
    notifempty
    create 0640 www-data www-data
    sharedscripts
}
```

### Install Monitoring (Optional)
```bash
# Install Sentry (for error tracking)
composer require sentry/sentry-laravel

# Publish config
php artisan vendor:publish --provider="Sentry\Laravel\ServiceProvider"
```

Add to `.env`:
```env
SENTRY_LARAVEL_DSN=your_sentry_dsn
```

---

## Step 11: Backup Strategy

### Database Backup Script
```bash
sudo nano /usr/local/bin/backup-eduapp-db.sh
```

```bash
#!/bin/bash
BACKUP_DIR="/var/backups/eduapp"
DATE=$(date +%Y%m%d_%H%M%S)
mkdir -p $BACKUP_DIR

mysqldump -u eduapp_user -p'password' eduapp > $BACKUP_DIR/eduapp_$DATE.sql
gzip $BACKUP_DIR/eduapp_$DATE.sql

# Keep only last 7 days
find $BACKUP_DIR -name "eduapp_*.sql.gz" -mtime +7 -delete
```

Make executable:
```bash
sudo chmod +x /usr/local/bin/backup-eduapp-db.sh
```

### Schedule Backup
Add to crontab:
```bash
sudo crontab -e
```

```cron
0 2 * * * /usr/local/bin/backup-eduapp-db.sh
```

---

## Step 12: Security Hardening

### Disable Directory Listing
Already handled in Nginx config

### Hide PHP Version
```bash
sudo nano /etc/php/8.2/fpm/php.ini
```

```ini
expose_php = Off
```

### Secure MySQL
```bash
sudo mysql_secure_installation
```

### Setup Fail2Ban
```bash
sudo apt install fail2ban -y
sudo systemctl enable fail2ban
sudo systemctl start fail2ban
```

---

## Maintenance Commands

### Update Application
```bash
cd /var/www/eduapp
git pull origin main
composer install --optimize-autoloader --no-dev
php artisan migrate --force
php artisan config:cache
php artisan route:cache
php artisan view:cache
sudo supervisorctl restart eduapp-worker:*
```

### Clear Cache
```bash
php artisan cache:clear
php artisan config:clear
php artisan route:clear
php artisan view:clear
```

### Check Queue Status
```bash
sudo supervisorctl status eduapp-worker:*
```

### View Logs
```bash
tail -f /var/www/eduapp/storage/logs/laravel.log
tail -f /var/www/eduapp/storage/logs/worker.log
```

---

## Troubleshooting

### 500 Error
```bash
# Check Laravel logs
tail -f /var/www/eduapp/storage/logs/laravel.log

# Check Nginx logs
sudo tail -f /var/log/nginx/error.log

# Check PHP-FPM logs
sudo tail -f /var/log/php8.2-fpm.log
```

### Permission Issues
```bash
sudo chown -R www-data:www-data /var/www/eduapp
chmod -R 775 /var/www/eduapp/storage
chmod -R 775 /var/www/eduapp/bootstrap/cache
```

### Queue Not Processing
```bash
# Restart workers
sudo supervisorctl restart eduapp-worker:*

# Check status
sudo supervisorctl status
```

### Database Connection Failed
```bash
# Test connection
php artisan tinker
>>> DB::connection()->getPdo();
```

---

## Rollback Strategy

### Quick Rollback
```bash
cd /var/www/eduapp
git checkout previous_working_commit
composer install --optimize-autoloader --no-dev
php artisan migrate:rollback --step=1
php artisan config:cache
sudo supervisorctl restart eduapp-worker:*
```

---

## Production Checklist

- [ ] `.env` में `APP_ENV=production` set है
- [ ] `.env` में `APP_DEBUG=false` set है
- [ ] Database credentials सही हैं
- [ ] SSL certificate install है
- [ ] Queue workers चल रहे हैं
- [ ] Cron jobs setup हैं
- [ ] File permissions सही हैं
- [ ] Backup system काम कर रहा है
- [ ] Monitoring setup है (Sentry/Log)
- [ ] Rate limiting enable है
- [ ] Firewall configured है
- [ ] Redis चल रहा है
- [ ] Payment gateway के live credentials हैं

---

## Support

Production deployment में किसी भी समस्या के लिए:
- Email: devops@eduapp.com
- Slack: #deployment-support

---

**Happy Deploying! 🚀**
