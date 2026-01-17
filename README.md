# Tuskari

A safari booking platform that connects travelers with safari operators, allowing them to browse, book, and pay for safari experiences across Africa.

## Tech Stack

- **Backend**: Laravel 12 (PHP 8.3+)
- **Frontend**: Vue 3 with Inertia.js
- **State Management**: Pinia
- **UI Components**: PrimeVue with Aura theme
- **Real-time**: Laravel Reverb (WebSockets) with Laravel Echo
- **Authentication**: Laravel Sanctum + Spatie Permissions
- **Payments**: Stripe
- **Build Tool**: Vite

## Prerequisites

**For Docker development (recommended):**
- Docker & Docker Compose
- Node.js & npm (for running Vite on host)

**For local development:**
- PHP 8.3+
- Composer
- Node.js & npm
- MySQL or PostgreSQL
- Redis (optional)

## Installation

### Option 1: Docker Development (Recommended)

1. Clone the repository:
   ```bash
   git clone https://github.com/your-username/tuskari-laravel.git
   cd tuskari-laravel
   ```

2. Install PHP dependencies:
   ```bash
   composer install
   ```

3. Install JavaScript dependencies:
   ```bash
   npm install
   ```

4. Copy the environment file:
   ```bash
   cp .env.example .env
   ```

5. Start Docker containers:
   ```bash
   ./vendor/bin/sail up -d
   ```

6. Generate application key and run migrations:
   ```bash
   ./vendor/bin/sail artisan key:generate
   ./vendor/bin/sail artisan migrate
   ```

7. Build frontend assets:
   ```bash
   npm run build
   ```

8. Access the application at http://localhost

### Option 2: Local Development

1. Clone the repository:
   ```bash
   git clone https://github.com/your-username/tuskari-laravel.git
   cd tuskari-laravel
   ```

2. Install PHP dependencies:
   ```bash
   composer install
   ```

3. Install JavaScript dependencies:
   ```bash
   npm install
   ```

4. Copy the environment file and configure:
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```

5. Configure your database in `.env` and run migrations:
   ```bash
   php artisan migrate
   ```

## Development

### With Docker (Laravel Sail)

```bash
# Start all containers (MySQL, Redis, Mailpit, Reverb, Queue, Scheduler)
./vendor/bin/sail up -d

# Start Vite dev server for hot reload (in a separate terminal)
npm run dev

# Run artisan commands
./vendor/bin/sail artisan migrate
./vendor/bin/sail artisan tinker

# View container logs
./vendor/bin/sail logs -f

# Stop all containers
./vendor/bin/sail down
```

#### Docker Services

| Service | Port | URL |
|---------|------|-----|
| Laravel App | 80 | http://localhost |
| Vite HMR | 5173 | http://localhost:5173 |
| MySQL 8 | 3306 | - |
| Redis | 6379 | - |
| Reverb WebSocket | 8080 | ws://localhost:8080 |
| Mailpit UI | 8025 | http://localhost:8025 |

### Without Docker

```bash
# Start Laravel dev server
php artisan serve

# Start Vite dev server (in a separate terminal)
npm run dev

# Start WebSocket server for real-time features (optional)
php artisan reverb:start
```

## Building for Production

```bash
npm run build
```

## Testing

```bash
# Run all tests
php artisan test

# Run feature tests only
php artisan test tests/Feature/

# Run unit tests only
php artisan test tests/Unit/

# Run a specific test file
php artisan test tests/Feature/GroupPricingTest.php
```

## Code Quality

```bash
# Run Laravel Pint (code style fixer)
./vendor/bin/pint
```

## Key Features

- **Safari Listings**: Browse and search safari experiences
- **Booking System**: Complete booking flow with payment integration
- **User Roles**: Support for travelers and safari operators
- **Real-time Messaging**: Chat between users using WebSockets
- **Admin Panel**: Manage platform content and users
- **Destination Guides**: Information about national parks and reserves

## License

All rights reserved.
