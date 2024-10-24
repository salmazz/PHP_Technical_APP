## README.md

```markdown
# Todo List Application

A simple Todo List application developed using Laravel 11 and MySQL. This project is an API-based application with the following features:

- User authentication (token-based).
- CRUD operations for todos.
- Image attachment for todos.
- Database seeding with 1000 todos.
- Email notifications (simulated) when a todo is created or updated.
- Efficient search functionality.
- Unit tests for core functionalities.
- Swagger API documentation.
- Implemented using repository design pattern, service pattern, and observer for better code organization and maintainability.

## Table of Contents

1. [Installation and Setup](#installation-and-setup)
2. [Database Schema](#database-schema)
3. [API Endpoints](#api-endpoints)
4. [Postman Collection](#postman-collection)
5. [Testing](#testing)
6. [Technical Details](#technical-details)

## Installation and Setup

### Prerequisites
- PHP >= 8.2
- Composer
- MySQL
- Node.js (optional, if you choose to use the frontend layer)

### Installation

1. **Clone the Repository:**

   ```bash
   git clone https://github.com/salmazz/PHP_Technical_APP.git
   cd PHP_Technical_APP
   ```

2. **Install Dependencies:**

   ```bash
   composer install
   npm install  # If using the frontend layer
   ```

3. **Environment Setup:**

   Create a `.env` file by copying the example file and configure the environment variables, especially the database settings.

   ```bash
   cp .env.example .env
   ```

   Update the `.env` file with your database credentials:

   ```
   DB_CONNECTION=mysql
   DB_HOST=127.0.0.1
   DB_PORT=3306
   DB_DATABASE=todo_db
   DB_USERNAME=root
   DB_PASSWORD=your_password
   ```

4. **Generate Application Key:**

   ```bash
   php artisan key:generate
   ```

5. **Run Migrations and Seed the Database:**

   Run the migrations and seed the database with 1000 todos:

   ```bash
   php artisan migrate --seed
   ```

6. **Generate Swagger Documentation:**

   Generate the Swagger API documentation:

   ```bash
   php artisan l5-swagger:generate
   ```

7. **Run the Application:**

   Start the local development server:

   ```bash
   php artisan serve
   ```

   The application will be available at `http://localhost:8000`.

8. **Access Swagger Documentation:**

   The Swagger UI for API documentation is available at:

   ```
   http://localhost:8000/api/documentation
   ```

## Database Schema

The database schema for the application consists of the following tables:

### Users Table
| Column      | Type         | Description                      |
|-------------|--------------|----------------------------------|
| id          | INT          | Primary key                      |
| name        | VARCHAR(255) | Name of the user                 |
| email       | VARCHAR(255) | Email of the user (unique)       |
| password    | VARCHAR(255) | Hashed password                  |
| created_at  | TIMESTAMP    | Timestamp when the record was created |
| updated_at  | TIMESTAMP    | Timestamp when the record was updated |

### Todos Table
| Column      | Type         | Description                      |
|-------------|--------------|----------------------------------|
| id          | INT          | Primary key                      |
| title       | VARCHAR(255) | Title of the todo                |
| description | TEXT         | Description of the todo          |
| status      | ENUM         | Status of the todo (`pending`, `in_progress`, `completed`, `canceled`) |
| image       | VARCHAR(255) | Path to the attached image       |
| user_id     | INT          | Foreign key referencing `users.id` |
| created_at  | TIMESTAMP    | Timestamp when the record was created |
| updated_at  | TIMESTAMP    | Timestamp when the record was updated |

## API Endpoints

### Authentication

- **Register:**
  ```
  POST /api/auth/register
  ```
    - Request:
      ```json
      {
        "name": "John Doe",
        "email": "john@example.com",
        "password": "password"
      }
      ```

- **Login:**
  ```
  POST /api/auth/login
  ```
    - Request:
      ```json
      {
        "email": "john@example.com",
        "password": "password"
      }
      ```

### Todo Management

- **Get All Todos:**
  ```
  GET /api/todos
  ```

- **Create Todo:**
  ```
  POST /api/todos
  ```
    - Request (Multipart Form-Data):
        - `title`: string
        - `description`: string (optional)
        - `status`: enum (`pending`, `in_progress`, `completed`, `canceled`)
        - `image`: file (optional)

- **Update Todo:**
  ```
  PUT /api/todos/{id}
  ```
    - Request (Multipart Form-Data):
        - `title`: string
        - `description`: string (optional)
        - `status`: enum (`pending`, `in_progress`, `completed`, `canceled`)
        - `image`: file (optional)

- **Delete Todo:**
  ```
  DELETE /api/todos/{id}
  ```

- **Update Todo Status:**
  ```
  PATCH /api/todos/{id}/status
  ```
    - Request:
      ```json
      {
        "status": "completed"
      }
      ```

### Search Functionality

- **Search Todos:**
  ```
  GET /api/todos?title=example&status=pending&created_at=2024-09-20
  ```

## Postman Collection
A Postman collection is available with all the API endpoints for easy testing and integration. It includes example requests and expected responses.

**Link to Postman Collection:** [Postman Collection](https://api.postman.com/collections/6208228-8ffbe2d9-7beb-495d-b426-95f3f0cb7372?access_key=PMAT-01J88V3SEQ3DFNPDCQQ43BQKBY)
Please update the environment variables in Postman:
- `app_url`: Your app link
- `token`: Retrieved after login.


## Testing

### Unit Tests

The following unit tests are included:

1. **Creating a Todo**:
    - Test creating a todo and dispatching an email job.
    - Check if the todo is saved in the database.

2. **Updating a Todo**:
    - Test updating a todo and dispatching an email job.
    - Verify the updated values in the database.

3. **Email Notifications**:
    - Test email notifications are sent on creating or updating a todo.
    - Simulated using `Mail::fake()` during tests.

4. **Search Functionality**:
    - Test searching todos by title, status, and creation date.

5. **Pagination**:
    - Test pagination functionality for todos.

### Running Tests

To run the tests, use the following command:

```bash
php artisan test
```

## Technical Details

### Design Patterns Used

- **Repository Pattern**: Abstracts the data layer, providing a flexible API for data operations.
- **Service Pattern**: Encapsulates business logic, making the controller lean and focusing on request validation and response.
- **Observer Pattern**: Observes model events and dispatches jobs for email notifications when a todo is created or updated.

### Observer Implementation

The `TodoObserver` is used to listen for `created` and `updated` events on the `Todo` model. It dispatches a job `SendTodoNotification` to send an email notification to the user.

### Job and Mail Implementation

- **Job**: The `SendTodoNotification` job is used to handle the sending of email notifications asynchronously.
- **Mail**: The `TodoNotification` mailable class is used to format the email content.



### Explanation of Additional Sections

- **Database Schema**: Lists the structure of the `users` and `todos` tables, showing the columns and their types.
- **Postman Collection**: Instructions on how to use the provided Postman collection for testing the API endpoints.
- **Testing**: Lists the different test cases covered and how to run them.
- **Technical Details**: Explains the design patterns used (repository, service, observer) and how they were implemented.
- **Observer Implementation**: Describes how the observer is used for handling events.
- **Job and Mail Implementation**: Details the job and mail classes used for sending email notifications.

You can adjust the content based on the specifics of your implementation and add any additional details you find relevant. If you have any further modifications or additions, feel free to ask!
