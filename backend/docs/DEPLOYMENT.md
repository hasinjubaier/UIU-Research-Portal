# UIU Research Portal - Backend Deployment Guide

This document details deployment strategies for the UIU Research Portal backend, including Docker Compose and production Linux (Ubuntu/Nginx/PHP-FPM/MySQL) environments.

---

## 1. System Requirements

- **PHP**: 8.0 or higher (8.2 recommended)
- **Extensions**: `pdo_mysql`, `mbstring`, `json`, `curl`, `zip`, `fileinfo`
- **Database**: MySQL 8.0+ or MariaDB 10.5+
- **Web Server**: Apache 2.4+ (with `mod_rewrite`) or Nginx 1.18+
- **Dependency Manager**: Composer 2.x
- **Memory**: 1 GB RAM minimum (2 GB recommended)

---

## 2. Docker Deployment (Recommended for Dev & Containerized Production)

### 2.1 Quick Start with Docker Compose

1. Clone repository and navigate to backend directory:
   ```bash
   cd backend
   ```

2. Copy environment variables:
   ```bash
   cp .env.example .env
   ```

3. Launch services (API, MySQL, WebSocket server):
   ```bash
   docker compose up -d
   ```

4. Run database migrations and seed demo data:
   ```bash
   docker compose exec api php bin/migrate.php
   docker compose exec api php bin/seed.php
   ```

5. Verify services:
   - **REST API**: `http://localhost:8000/api/health`
   - **MySQL**: `localhost:3306` (user: `root`, password: `rootpassword`)
   - **WebSocket Server**: `ws://localhost:8080`

---

## 3. Ubuntu VPS Production Deployment (Nginx + PHP-FPM)

### 3.1 Install System Packages

```bash
sudo apt update && sudo apt upgrade -y
sudo apt install -y nginx mysql-server composer git unzip \
    php8.2 php8.2-fpm php8.2-mysql php8.2-mbstring php8.2-xml \
    php8.2-curl php8.2-zip php8.2-cli
```

### 3.2 Secure MySQL & Provision Database

```bash
sudo mysql_secure_installation

sudo mysql -u root -p
```

```sql
CREATE DATABASE uiu_research_portal CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
CREATE USER 'uiu_user'@'localhost' IDENTIFIED BY 'StrongSecurePassword123!';
GRANT ALL PRIVILEGES ON uiu_research_portal.* TO 'uiu_user'@'localhost';
FLUSH PRIVILEGES;
EXIT;
```

### 3.3 Clone & Configure Backend

```bash
cd /var/www/uiu-research-portal
git clone https://github.com/hasinjubaier/UIU-Research-Portal.git .
cd backend

# Install production dependencies
composer install --no-dev --optimize-autoloader

# Set up environment
cp .env.example .env
nano .env
```

Ensure `.env` contains:
```ini
APP_ENV=production
APP_DEBUG=false
APP_URL=https://research.uiu.ac.bd

DB_HOST=127.0.0.1
DB_DATABASE=uiu_research_portal
DB_USERNAME=uiu_user
DB_PASSWORD=StrongSecurePassword123!

JWT_SECRET=generate_a_64_character_random_secret_key_here
```

### 3.4 Set Permissions

```bash
sudo chown -R www-data:www-data /var/www/uiu-research-portal
sudo chmod -R 755 /var/www/uiu-research-portal
sudo chmod -R 775 /var/www/uiu-research-portal/backend/storage
```

### 3.5 Run Migrations and Seeders

```bash
php bin/migrate.php
php bin/seed.php
```

### 3.6 Configure Nginx Virtual Host

Create `/etc/nginx/sites-available/uiu-portal`:

```nginx
server {
    listen 80;
    server_name research.uiu.ac.bd;

    root /var/www/uiu-research-portal;
    index index.html index.php;

    # Static frontend assets
    location / {
        try_files $uri $uri/ /index.html;
    }

    # Backend API routing
    location /api {
        root /var/www/uiu-research-portal/backend/public;
        try_files $uri /index.php$is_args$args;

        location ~ \.php$ {
            include snippets/fastcgi-php.conf;
            fastcgi_pass unix:/var/run/php/php8.2-fpm.sock;
            fastcgi_param SCRIPT_FILENAME /var/www/uiu-research-portal/backend/public/index.php;
        }
    }

    # WebSocket proxy
    location /ws/ {
        proxy_pass http://127.0.0.1:8080;
        proxy_http_version 1.1;
        proxy_set_header Upgrade $http_upgrade;
        proxy_set_header Connection "Upgrade";
        proxy_set_header Host $host;
    }

    # Deny access to hidden files and sensitive directories
    location ~ /\.(?!well-known).* {
        deny all;
    }
    location ^~ /backend/config/ {
        deny all;
    }
    location ^~ /backend/storage/logs/ {
        deny all;
    }
}
```

Enable the site and reload Nginx:
```bash
sudo ln -s /etc/nginx/sites-available/uiu-portal /etc/nginx/sites-enabled/
sudo nginx -t
sudo systemctl reload nginx
```

### 3.7 SSL Certificate via Let's Encrypt

```bash
sudo apt install -y certbot python3-certbot-nginx
sudo certbot --nginx -d research.uiu.ac.bd
```

### 3.8 Supervisor / Systemd Daemon for WebSockets

Create `/etc/systemd/system/uiu-websocket.service`:

```ini
[Unit]
Description=UIU Research Portal WebSocket Server
After=network.target

[Service]
Type=simple
User=www-data
Group=www-data
WorkingDirectory=/var/www/uiu-research-portal/backend
ExecStart=/usr/bin/php /var/www/uiu-research-portal/backend/bin/websocket.php
Restart=always
RestartSec=3

[Install]
WantedBy=multi-user.target
```

Enable and start the daemon:
```bash
sudo systemctl daemon-reload
sudo systemctl enable uiu-websocket
sudo systemctl start uiu-websocket
sudo systemctl status uiu-websocket
```

---

## 4. Maintenance & Backups

### Automated Database Backup (Daily Cron)

Add to `/etc/cron.daily/uiu-db-backup`:
```bash
#!/bin/bash
BACKUP_DIR="/var/backups/uiu_portal"
mkdir -p $BACKUP_DIR
mysqldump -u uiu_user -p'StrongSecurePassword123!' uiu_research_portal | gzip > "$BACKUP_DIR/uiu_db_$(date +\%F).sql.gz"
find $BACKUP_DIR -type f -mtime +14 -name "*.sql.gz" -exec rm {} +
```
```bash
chmod +x /etc/cron.daily/uiu-db-backup
```
