[![SWEP](https://github.com/drochette/swep/actions/workflows/symfony.yml/badge.svg)](https://github.com/drochette/swep/actions/workflows/symfony.yml)

# SWEP - Vehicle Booking System

A Symfony-based vehicle booking application that allows users to browse and book vehicles. It also integrates with external APIs to fetch vehicle brand information.

## 🛠 Tech Stack

- **Language:** PHP 8.4+
- **Framework:** Symfony 8.0.*
- **Database:** MariaDB (via Doctrine ORM)
- **Containerization:** Docker & Docker Compose
- **Package Manager:** Composer
- **Web Server:** Nginx
- **Asset Management:** Symfony AssetMapper

## 📋 Requirements

- Docker and Docker Compose
- PHP 8.4 or higher (for local development)
- Composer

## 🚀 Getting Started

### 1. Clone the repository
```bash
git clone <repository-url>
cd swep
```

### 2. Setup Environment
Copy the default environment file and adjust values if necessary:
```bash
cp .env .env.local
```

### 3. Start the project
The project is containerized using Docker. You can use the provided `Makefile` for convenience:
```bash
make start
```
This will start the PHP, Nginx, MariaDB, and Maildev containers.

### 4. Install Dependencies
```bash
docker compose exec php composer install
```

### 5. Database Setup
Run migrations to create the database schema and load fixtures:
```bash
make db-migration
make purge-fixtures
```

## 🖥 Usage

- **Web Interface:** Access the application at [http://localhost:8000](http://localhost:8000)
- **Maildev:** View sent emails at [http://localhost:1080](http://localhost:1080)
- **API Endpoints:**
    - `GET /api/vehicles`: List all vehicles in JSON format.

### CLI Commands
- `php bin/console app:vehicle-brands`: Lists vehicle brands (fetched from NHTSA API).

## 📜 Available Scripts

| Command | Description |
|---------|-------------|
| `make start` | Start the Docker containers in detached mode. |
| `make stop` | Stop the Docker containers. |
| `make fix-cs` | Run PHP CS Fixer to fix code style issues. |
| `make db-migration` | Run database migrations. |
| `make purge-fixtures` | Reload database fixtures (Warning: truncates data). |

## ⚙️ Environment Variables

Key environment variables defined in `.env`:

- `APP_ENV`: Application environment (dev, prod, test).
- `DATABASE_URL`: Connection string for the MariaDB database.
- `MAILER_DSN`: DSN for the mailer service (configured for Maildev by default).
- `HTTP_CARS_CLIENT_URI`: Base URI for the external cars API.

## 📂 Project Structure

- `assets/`: Frontend assets (CSS, JS).
- `bin/`: Symfony executable scripts (e.g., `console`).
- `config/`: Application configuration files.
- `docker/`: Docker configuration (Dockerfile, Nginx config).
- `migrations/`: Database migration files.
- `public/`: Publicly accessible files (entry point `index.php`, images).
- `src/`: PHP source code.
    - `Command/`: Console commands.
    - `Controller/`: Web and API controllers.
    - `Entity/`: Doctrine entities.
    - `HttpClient/`: External API clients.
    - `Repository/`: Doctrine repositories.
    - `Service/`: Business logic services.
- `templates/`: Twig templates.
- `tests/`: Automated tests.

## 🧪 Testing

The project uses PHPUnit for testing.

To run tests:
```bash
docker compose exec php bin/phpunit
```
*Note: Currently, the `tests/` directory only contains a bootstrap file. TODO: Add functional and unit tests.*

## 📄 License

This project is licensed under the terms specified in `composer.json` (**proprietary**).
