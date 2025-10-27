# Laravel Development

## Installation Steps

### 1. Update System Packages

```bash
sudo apt update
```

### 2. Install Apache2 Web Server

```bash
sudo apt install apache2
```

### 3. Install PHP and Required Extensions

Install PHP along with all necessary extensions for Laravel:

```bash
sudo apt install php libapache2-mod-php php-cli php-mbstring php-xml php-bcmath php-json php-zip php-curl php-mysql php-xmlrpc php-gd
```

### 4. Install Composer (PHP Dependency Manager)

Download and install Composer:

```bash
sudo apt install curl unzip
curl -sS https://getcomposer.org/installer | php
sudo mv composer.phar /usr/local/bin/composer
```

### 5. Install Laravel Installer

Navigate to the web directory and install Laravel globally:

```bash
cd /var/www
composer global require laravel/installer
```

### 6. Configure Environment Path

Add Composer's global bin directory to your PATH:

```bash
nano ~/.bashrc
```

Add the following line at the end of the file:

```bash
export PATH="$HOME/.config/composer/vendor/bin:$PATH"
```

Save and exit, then reload the configuration:

```bash
source ~/.bashrc
```

### 7. Verify Installation

Check if Composer and Laravel are properly installed:

```bash
composer --version
laravel --version
```

## Creating a New Laravel Project

### 1. Create Project

```bash
laravel new example-project
```

### Project Setup

This project was initialized with the following configuration:

-   **Starter Kit**: None
-   **Testing Framework**: Pest
-   **Database**: MySQL
-   **Default Database Migration**: Applied
-   **npm Install**: Executed

### 2. Navigate to Project Directory

```bash
cd example-project
```

### 3. Run Development Server

For quick testing, use the built-in PHP server:

```bash
php artisan serve
```

## Apache Configuration

### 1. Create Apache Virtual Host Configuration

```bash
sudo nano /etc/apache2/sites-available/example-project.conf
```

Add the following configuration:

```apache
<VirtualHost *:80>
    ServerAdmin webmaster@localhost
    DocumentRoot /var/www/example-project/public
    ServerName example-project.local

    <Directory /var/www/example-project/public>
        AllowOverride All
        Require all granted
    </Directory>

    ErrorLog ${APACHE_LOG_DIR}/error.log
    CustomLog ${APACHE_LOG_DIR}/access.log combined
</VirtualHost>
```

### 2. Update Hosts File

```bash
sudo nano /etc/hosts
```

Add the following line:

```
127.0.0.1 example-project.local
```

Complete hosts file example:

```
127.0.0.1 localhost
127.0.0.1 example-project.local
127.0.1.1 ubuntu-H270M-D3H

# The following lines are desirable for IPv6 capable hosts
::1     ip6-localhost ip6-loopback
fe00::0 ip6-localnet
ff00::0 ip6-mcastprefix
ff02::1 ip6-allnodes
ff02::2 ip6-allrouters
```

### 3. Enable Site and Rewrite Module

```bash
sudo a2ensite example-project.conf
sudo a2enmod rewrite
sudo systemctl restart apache2
```

### 4. Set Proper Permissions

```bash
sudo chown -R www-data:www-data /var/www/example-project
sudo chmod -R 755 /var/www/example-project
sudo chmod -R 775 /var/www/example-project/storage
sudo chmod -R 775 /var/www/example-project/bootstrap/cache
```

## Moving Project to Custom Location

If you need to move your Laravel project to a custom directory (e.g., external drive or different partition):

### 1. Move the Project

```bash
sudo mv /var/www/example-project /media/ubuntu/_Projects/
```

### 2. Set Proper Ownership and Permissions

Update ownership to Apache user:

```bash
sudo chown -R www-data:www-data /media/ubuntu/_Projects/example-project
```

Set permissions on the parent directory to allow Apache access:

```bash
sudo chmod -R 755 /media/ubuntu/_Projects
```

Set specific permissions for Laravel directories:

```bash
sudo chmod -R 755 /media/ubuntu/_Projects/example-project
sudo chmod -R 775 /media/ubuntu/_Projects/example-project/storage
sudo chmod -R 775 /media/ubuntu/_Projects/example-project/bootstrap/cache
```

### 3. Update Apache Virtual Host Configuration

Edit the Apache configuration file:

```bash
sudo nano /etc/apache2/sites-available/example-project.conf
```

Update the configuration to point to the new location:

```apache
<VirtualHost *:80>
    ServerAdmin webmaster@localhost
    DocumentRoot /media/ubuntu/_Projects/example-project/public
    ServerName example-project.local

    <Directory /media/ubuntu/_Projects/example-project/public>
        AllowOverride All
        Require all granted
    </Directory>

    ErrorLog ${APACHE_LOG_DIR}/error.log
    CustomLog ${APACHE_LOG_DIR}/access.log combined
</VirtualHost>
```

### 4. Restart Apache

Apply the changes by restarting Apache:

```bash
sudo systemctl restart apache2
```

### 5. Verify Access

Visit http://example-project.local in your browser to confirm the project is working.

## Accessing Your Application

-   **Development Server**: http://localhost:8000
-   **Apache Virtual Host**: http://example-project.local

## Troubleshooting

### Common Issues

#### 1. 403 Forbidden Error

If you encounter a "Forbidden" error after moving the project:

**Cause**: Insufficient permissions on the parent directory preventing Apache from accessing the project files.

**Solution**:

```bash
sudo chmod -R 755 /media/ubuntu/_Projects
sudo chmod -R 755 /media/ubuntu/_Projects/example-project
```

#### 2. Permission Denied Errors

Ensure proper ownership for all project files:

```bash
sudo chown -R www-data:www-data /media/ubuntu/_Projects/example-project
```

#### 3. Apache Not Starting

Check for port conflicts:

```bash
sudo netstat -tulpn | grep :80
```

#### 4. Composer Command Not Found

Verify PATH configuration in ~/.bashrc and reload:

```bash
source ~/.bashrc
```

#### 5. Storage/Cache Write Errors

Ensure Laravel's writable directories have correct permissions:

```bash
sudo chmod -R 775 /media/ubuntu/_Projects/example-project/storage
sudo chmod -R 775 /media/ubuntu/_Projects/example-project/bootstrap/cache
```

### Useful Commands

```bash
# Check Apache status
sudo systemctl status apache2

# Restart Apache
sudo systemctl restart apache2

# View Apache error logs
sudo tail -f /var/log/apache2/error.log

# View Laravel logs
tail -f /media/ubuntu/_Projects/example-project/storage/logs/laravel.log

# Clear Laravel cache
php artisan cache:clear
php artisan config:clear
php artisan route:clear
php artisan view:clear

# Check file permissions
ls -la /media/ubuntu/_Projects/example-project

# Test Apache configuration
sudo apache2ctl configtest
```

## Permission Management Best Practices

### Recommended Permission Structure

```
Project Root: 755 (rwxr-xr-x)
├── storage/: 775 (rwxrwxr-x)
│   ├── app/: 775
│   ├── framework/: 775
│   └── logs/: 775
├── bootstrap/cache/: 775 (rwxrwxr-x)
└── Other directories: 755 (rwxr-xr-x)
```

### Ownership

-   **Owner**: www-data (Apache user)
-   **Group**: www-data
-   This ensures Apache can read all files and write to necessary directories

## Next Steps

1. Configure your `.env` file for database connections
2. Run migrations: `php artisan migrate`
3. Install additional packages as needed via Composer
4. Start building your application!

## Resources

-   [Laravel Documentation](https://laravel.com/docs)
-   [Composer Documentation](https://getcomposer.org/doc/)
-   [Apache Documentation](https://httpd.apache.org/docs/)
-   [Linux File Permissions Guide](https://www.linux.com/training-tutorials/understanding-linux-file-permissions/)
