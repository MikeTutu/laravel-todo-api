# Todo App Documentation by Micheal Tutu

## Table of Contents
- [Overview](#overview)
- [Environment Setup](#environment-setup)
- [Running the Application](#running-the-application)
- [API Endpoints](#api-endpoints)
- [Authentication Endpoints](#authentication-endpoints)
- [Interaction with JSONPlaceholder API](#interaction-with-jsonplaceholder-api)
- [Testing](#testing)

## Overview
This documentation provides an overview of the Todo application built using [PHP](https://www.php.net/) Laravel framework. The application allows users to manage their tasks effectively through a RESTful API. It includes functionalities for CRUD operations on Todo items and `implements custom authentication middleware`. Additionally, it interacts with the JSONPlaceholder API for mock data and `includes PHPunit tests` for endpoint validation.

- **Environment Configuration**:
  - The application can be configured to run in two modes:
    - **Live Mode**: Interacts with the database.
    - **Mock Mode**: Utilizes the JSONPlaceholder API for mock data.
  - Set the environment variable **APP_MODE** in the `.env` file to control the mode.

## Environment Setup
1. **Requirements**:
   - [PHP](https://www.php.net/) >= 8.1
   - [Composer](https://getcomposer.org/)
   - [Laravel Framework](https://laravel.com/) v10.x

2. **Configuration**:
   - Extract the zipped application files to your desired directory.
   - **Environment Variables**:
     - The `.env` file is included in the zipped app.
     - Update the `.env` file with appropriate configurations.
     - **`APP_MODE`**: Set to either `'live'` or `'mock'` to control interaction with the database or JSONPlaceholder API, respectively.

3. **Database Setup** (if `APP_MODE` is set to `'live'`):
   - Configure your database connection in the `.env` file.
   - Run database migrations: `php artisan migrate`

## Running the Application
- To run the application, execute: `php artisan serve`
- The application will be accessible at `http://localhost:8000`

## API Endpoints Overview

| Method | Endpoint             | Description                           |
|--------|----------------------|---------------------------------------|
| GET    | /api/todos           | Retrieve all Todo items.              |
| GET    | /api/todos/{id}      | Retrieve a single Todo item by its ID.|
| POST   | /api/todos           | Create a new Todo item.               |
| PUT    | /api/todos/{id}      | Update an existing Todo item by its ID.|
| DELETE | /api/todos/{id}      | Delete a Todo item by its ID.         |

## Authentication Endpoints Overview
| Method | Endpoint             | Description                           |
|--------|----------------------|---------------------------------------|
| POST   | /api/signin          | User sign-in.                         |
| POST   | /api/signup          | User sign-up.                         |
| POST   | /api/signout         | User sign-out.                         |

## Authentication Endpoints
- The application includes a custom authentication middleware, validated on every request.
- To access endpoints that require authentication, include the appropriate authentication token in the request headers.

## Interaction with JSONPlaceholder API
- The application interacts with the JSONPlaceholder API for mock data when `APP_MODE` is set to 'mock'.
- It utilizes Laravel's HTTP client to make requests to the JSONPlaceholder API.
- Proper handling and parsing of responses from the JSONPlaceholder API are ensured.

## Testing
- The application includes unit tests for all endpoints.
- To run the tests, execute: `php artisan test`
