# Laravel Development

## Table of Contents

1. [Installation Steps](#seedersInstallation-Steps)
2. [Creating a New Laravel Project](#Creating-a-New-Laravel-Project)
3. [Apache Configuration](#Apache-Configuration)
4. [Moving Project to Custom Location](#Moving-Project-to-Custom-Location)
5. [Permission Management](#Permission-Management-Best-Practices)
6. [Authentication Setup](#authentication-setup)
7. [Database Migrations](#database-migrations)
8. [Factories](#factories)
9. [Seeders](#seeders)

---

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

# Laravel Authentication & Database Management

## Authentication Setup

### Default Laravel Authentication (Deprecated)

**Note**: `php artisan make:auth` is deprecated and only works with Laravel 5.6 or earlier versions.

### Option 1: Laravel UI (Bootstrap Authentication)

Laravel UI provides Bootstrap-based authentication scaffolding.

#### Installation

```bash
composer require laravel/ui
```

#### Run Development Server

```bash
npm run dev
```

### Option 2: Laravel Breeze (Recommended)

Laravel Breeze provides a minimal and simple authentication scaffolding with Blade templates.

#### Installation

```bash
composer require laravel/breeze --dev
```

#### Install Breeze

```bash
php artisan breeze:install
```

**Interactive Prompts:**

1. Select: **Blade**
2. Dark mode support: **Yes**
3. Testing framework: **Pest**

#### Run Migrations

```bash
php artisan migrate
```

#### Install NPM Dependencies

```bash
npm install
```

#### Run Development Server

```bash
npm run dev
```

---

## Database Migrations

### Creating a Migration

To modify an existing table (e.g., adding columns to the users table):

```bash
php artisan make:migration UpdateUserTableMigration
```

### Example: Updating User Table Schema

Edit the generated migration file in `database/migrations/`:

```php
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->integer('age');
            $table->float('percentage');
            $table->string('profileImage')->nullable(true);
            $table->date('date_of_birth')->nullable();
            $table->enum('gender', ['male', 'female'])->default('male');
            $table->enum('userType', ['student', 'teacher'])->default('student');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'age',
                'percentage',
                'profileImage',
                'date_of_birth',
                'gender',
                'userType'
            ]);
        });
    }
};
```

### Run the Migration

```bash
php artisan migrate
```

### Common Migration Commands

```bash
# Run all pending migrations
php artisan migrate

# Rollback the last migration batch
php artisan migrate:rollback

# Rollback all migrations
php artisan migrate:reset

# Rollback and re-run all migrations
php artisan migrate:refresh

# Drop all tables and re-run migrations
php artisan migrate:fresh

# Check migration status
php artisan migrate:status
```

---

## Factories

Factories are used to generate fake data for testing purposes.

### Creating a Factory

```bash
php artisan make:factory UserFactory --model=User
```

### Example: User Factory Definition

Edit the factory file in `database/factories/UserFactory.php`:

```php
<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
 */
class UserFactory extends Factory
{
    /**
     * The current password being used by the factory.
     */
    protected static ?string $password;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => fake()->name(),
            'email' => fake()->unique()->safeEmail(),
            'age' => $this->faker->numberBetween(18, 65),
            'percentage' => $this->faker->randomFloat(2, 0, 100),
            'date_of_birth' => $this->faker->date(),
            'gender' => $this->faker->randomElement(['male', 'female']),
            'userType' => $this->faker->randomElement(['student', 'teacher']),
            'email_verified_at' => now(),
            'password' => static::$password ??= Hash::make('password'),
            'remember_token' => Str::random(10),
        ];
    }

    /**
     * Indicate that the model's email address should be unverified.
     */
    public function unverified(): static
    {
        return $this->state(fn (array $attributes) => [
            'email_verified_at' => null,
        ]);
    }
}
```

### Using Factories in Tinker

```bash
php artisan tinker

# Create a single user
User::factory()->create();

# Create multiple users
User::factory()->count(10)->create();
```

---

## Seeders

Seeders allow you to populate your database with test data.

### Creating a Seeder

```bash
php artisan make:seeder UserSeeder
```

### Example: User Seeder Definition

Edit the seeder file in `database/seeders/UserSeeder.php`:

```php
<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create 50 users using the factory
        User::factory()->count(50)->create();

        // Or create a specific user
        User::factory()->create([
            'name' => 'Admin User',
            'email' => 'admin@example.com',
            'userType' => 'teacher',
        ]);
    }
}
```

### Registering Seeder in DatabaseSeeder

Edit `database/seeders/DatabaseSeeder.php`:

```php
<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            UserSeeder::class,
            // Add other seeders here
        ]);
    }
}
```

### Running Seeders

```bash
# Run a specific seeder
php artisan db:seed --class=UserSeeder

# Run all seeders (defined in DatabaseSeeder)
php artisan db:seed

# Refresh database and run all seeders
php artisan migrate:fresh --seed
```

---

## Complete Workflow Example

Here's a complete workflow for setting up authentication with custom user fields:

```bash
# 1. Create Laravel project
laravel new example-project
cd example-project

# 2. Install Breeze
composer require laravel/breeze --dev
php artisan breeze:install
# Select: Blade, Yes, Pest

# 3. Create migration for user table updates
php artisan make:migration UpdateUserTableMigration

# 4. Edit migration file (add custom columns)
# database/migrations/YYYY_MM_DD_HHMMSS_update_user_table_migration.php

# 5. Run migrations
php artisan migrate

# 6. Create/Update User Factory
php artisan make:factory UserFactory --model=User
# Edit database/factories/UserFactory.php

# 7. Create User Seeder
php artisan make:seeder UserSeeder
# Edit database/seeders/UserSeeder.php

# 8. Run seeder
php artisan db:seed --class=UserSeeder

# 9. Install and run frontend assets
npm install
npm run dev

# 10. Start development server
php artisan serve
```

---

## Faker Methods Reference

Common Faker methods used in factories:

```php
// Text
fake()->name()                          // Full name
fake()->firstName()                     // First name
fake()->lastName()                      // Last name
fake()->email()                         // Email address
fake()->safeEmail()                     // Safe email (example.com domain)

// Numbers
fake()->numberBetween(1, 100)          // Random integer
fake()->randomFloat(2, 0, 100)         // Random float with 2 decimals

// Dates
fake()->date()                          // Random date (Y-m-d)
fake()->dateTime()                      // Random datetime
fake()->dateTimeBetween('-30 years', 'now')  // Date in range

// Random Selection
fake()->randomElement(['option1', 'option2'])  // Random from array
fake()->boolean()                       // Random true/false

// Lorem Ipsum
fake()->sentence()                      // Random sentence
fake()->paragraph()                     // Random paragraph
fake()->text(200)                       // Random text (200 chars)
```

---

## Best Practices

### Migrations

1. **Always include `down()` method** - Allows rollback of migrations
2. **Use descriptive migration names** - e.g., `AddStatusToUsersTable`
3. **One change per migration** - Easier to manage and rollback
4. **Use nullable() for optional fields** - Prevents database errors

### Factories

1. **Use realistic data** - Makes testing more reliable
2. **Set default password** - Use `Hash::make('password')` for consistency
3. **Create factory states** - For different user types or statuses
4. **Use unique() for unique fields** - Prevents database constraint errors

### Seeders

1. **Order matters** - Seed tables with foreign keys last
2. **Use factories in seeders** - Don't duplicate fake data logic
3. **Create admin users separately** - With known credentials
4. **Use transactions** - For better performance with large datasets

---

## Common Issues & Solutions

### Issue: Migration fails with "Column already exists"

**Solution**: Check if the column was added in a previous migration or rollback first:

```bash
php artisan migrate:rollback
php artisan migrate
```

### Issue: Seeder fails with "Class not found"

**Solution**: Run composer autoload:

```bash
composer dump-autoload
```

### Issue: Factory produces duplicate emails

**Solution**: Use `unique()` in factory:

```php
'email' => fake()->unique()->safeEmail()
```

### Issue: NPM run dev fails after Breeze installation

**Solution**: Clear cache and reinstall:

```bash
rm -rf node_modules package-lock.json
npm install
npm run dev
```

---

## Useful Commands Summary

```bash
# Authentication
composer require laravel/breeze --dev
php artisan breeze:install

# Migrations
php artisan make:migration MigrationName
php artisan migrate
php artisan migrate:rollback
php artisan migrate:fresh --seed

# Factories
php artisan make:factory ModelFactory --model=Model

# Seeders
php artisan make:seeder SeederName
php artisan db:seed --class=SeederName
php artisan db:seed

# Development
npm install
npm run dev
php artisan serve
```

## Resources

---

-   [Laravel Documentation](https://laravel.com/docs)
-   [Composer Documentation](https://getcomposer.org/doc/)
-   [Apache Documentation](https://httpd.apache.org/docs/)
-   [Linux File Permissions Guide](https://www.linux.com/training-tutorials/understanding-linux-file-permissions/)
-   [Laravel Authentication Documentation](https://laravel.com/docs/authentication)
-   [Laravel Breeze Documentation](https://laravel.com/docs/starter-kits#laravel-breeze)
-   [Laravel Migrations Documentation](https://laravel.com/docs/migrations)
-   [Laravel Factories Documentation](https://laravel.com/docs/eloquent-factories)
-   [Laravel Seeders Documentation](https://laravel.com/docs/seeding)
-   [Faker Documentation](https://fakerphp.github.io/)

---
