# Employee Management API

## Description
The **Employee Management API** is a RESTful service that allows users to manage employees in a system. It provides endpoints to create, read, update, and delete employee records while ensuring validation and data integrity.

## Features
- Create an employee with required details
- Retrieve employee details by ID
- Update employee information
- Delete an employee record
- Validations for required fields and data constraints
- Functional and unit tests for reliability

## Technologies Used
- **Symfony** (PHP framework)
- **PostgreSQL** (Database)
- **JMS Serializer** (Serialization/Deserialization)
- **OpenAPI (nelmio/api-doc-bundle)** (API documentation)
- **PHPUnit** (Testing framework)
- **Docker & Docker Compose** (Containerized environment)

---

## Installation and Setup
### Prerequisites
Make sure you have the following installed:
- **Docker** & **Docker Compose**
- **PHP 8.3**
- **Composer**

### Setup Steps

1. Clone the repository:
   - git clone <repository_url>
   - cd football-manager

2. Configure the environment: Ensure your `.env` file is set correctly, for example:

        ###> symfony/framework-bundle ###
        APP_ENV=dev
        APP_SECRET=secret
        APP_RUNTIME_ENV=dev
        APP_RUNTIME_MODE=web
        ###< symfony/framework-bundle ###
        
        ###> doctrine/doctrine-bundle ###
        DATABASE_URL="pgsql://root:root@db:5432/company"
        ###< doctrine/doctrine-bundle ###
        
        ###> symfony/messenger ###
        MESSENGER_TRANSPORT_DSN=doctrine://default
        ###< symfony/messenger ###

3. Install dependencies:
   - composer install

4. Run Database Migrations:
   - bin/console doctrine:database:create
   - bin/console doctrine:migrations:migrate

## API Usage
The API endpoints follow RESTful conventions:

### 1. Create an Employee
**Request:**
```http
POST /api/employees
Content-Type: application/json

{
  "firstName": "John",
  "lastName": "Doe",
  "email": "john@example.com",
  "hiredAt": "2025-04-01T00:00:00+00:00",
  "salary": 150
}
```

**Response:**
```json
{
  "id": 1,
  "firstName": "John",
  "lastName": "Doe",
  "email": "john@example.com",
  "hiredAt": "2025-04-01T00:00:00+00:00",
  "salary": 150
}
```

### 2. Retrieve an Employee
```http
GET /api/employees/{id}
```

### 3. Update an Employee
```http
PUT /api/employees/{id}
Content-Type: application/json

{
  "id": 1,
  "firstName": "Den",
  "lastName": "Joe",
  "salary": 200
}
```

### 4. Delete an Employee
```http
DELETE /api/employees/{id}
```

**API Documentation available at `/api/docs`** 

---

## Running Tests

### Execute Unit & Functional Tests
To run tests inside the Docker container:
```sh
docker-compose exec php bin/phpunit
```

To see detailed output, use:
```sh
docker-compose exec php bin/phpunit --debug
```

---

