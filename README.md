# Task Management System

A comprehensive RESTful API for managing projects and tasks, built with Laravel v13. This system allows users to create and manage projects, organize tasks with priorities and statuses, track deadlines, and receive notifications for overdue tasks.

## Table of Contents

- [Installation Steps](#installation-steps)
- [Environment Setup](#environment-setup)
- [API Documentation](#api-documentation)
- [Features](#features)

## Installation Steps

### Prerequisites

- PHP 8.3 or higher
- Composer
- MySQL or compatible database
- Git

### Step 1: Clone the Repository

```bash
git clone <repository-url>
cd task-management-system
```

### Step 2: Install PHP Dependencies

```bash
composer install
```

### Step 3: Generate Application Key

```bash
php artisan key:generate
```

### Step 4: Configure Database

Update your `.env` file with your database credentials (see [Environment Setup](#environment-setup) below).

### Step 5: Run Migrations

```bash
php artisan migrate
```

### Step 6: Seed the Database

```bash
php artisan db:seed
```

This will create sample users, projects, and tasks with realistic data.

### Step 7: Generate API Documentation

```bash
php artisan l5-swagger:generate
```

### Step 8: Start the Development Server

```bash
php artisan serve
```

The application will be available at `http://localhost:8000`

## Environment Setup

### Create and Configure .env File

1. Copy the example environment file:

```bash
cp .env.example .env
```

2. Update the following variables in your `.env` file:

```env
APP_NAME="Task Management System"
APP_ENV=local
APP_DEBUG=true
APP_URL=http://localhost:8000

# Database Configuration
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=task_management
DB_USERNAME=root
DB_PASSWORD=

# Mail Configuration (for notifications)
MAIL_MAILER=log
MAIL_FROM_ADDRESS=noreply@taskmanagement.local
MAIL_FROM_NAME="${APP_NAME}"

# Queue Configuration (for background jobs)
QUEUE_CONNECTION=database
```

### Optional: Queue Configuration

To enable background task processing (for overdue task notifications):

```bash
# Run queue worker
php artisan queue:work
```

## API Documentation

### Swagger UI Documentation

Access interactive API documentation through Swagger UI:

1. Start the development server:
   ```bash
   php artisan serve
   ```

2. Navigate to:
   ```
   http://localhost:8000/api/documentation
   ```

The Swagger UI provides:
- Interactive API endpoint testing
- Request/response examples
- Authentication details
- Parameter descriptions
- HTTP status codes and error handling

### Postman Collection

A complete Postman collection is included in the project:

**Location:** `docs/postman/task-management.postman.json`

#### Importing into Postman:

1. Open Postman
2. Click **Import** (top left)
3. Select **File** tab
4. Choose `docs/postman/task-management.postman.json`
5. Click **Import**

The collection includes:
- All API endpoints organized by resource
- Pre-configured authentication
- Example requests and responses
- Environment variables for easy switching between environments

#### Creating a Postman Environment:

1. Create a new Environment in Postman
2. Set these variables:
   - `base_url`: `http://localhost:8000`
   - `token`: (obtained from login endpoint)

### Regenerating API Documentation

If you modify API endpoints, regenerate the Swagger documentation:

```bash
php artisan l5-swagger:generate
```

## Features

- **Project Management**: Organize work into projects with status tracking (Active, Completed, Archived)
- **Task Management**: Create tasks with priorities (Low, Medium, High) and statuses (Todo, In Progress, Completed)
- **Task Scheduling**: Set due dates and receive overdue notifications
- **API Authentication**: Secure endpoints with Sanctum token-based authentication
- **Pagination**: Cursor-based pagination for efficient data retrieval
- **Error Handling**: Comprehensive error responses with meaningful messages
- **Testing**: Complete test suite with PHPUnit

## Development

### Running Tests

```bash
php artisan test
```

### Database Seeding

Seed the database with sample data:

```bash
php artisan db:seed
```

To seed specific tables:

```bash
php artisan db:seed --class=UserSeeder
php artisan db:seed --class=ProjectSeeder
php artisan db:seed --class=TaskSeeder
```