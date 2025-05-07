# Library Management API Documentation

This document describes the user, admin, profile, and book download endpoints for the Library Management System. These endpoints manage books, borrowings, reservations, categories, authors, and user profiles.

## Base URL
All endpoints are relative to the base URL: `/`

## Content Type
All requests and responses use `application/json` unless otherwise specified (e.g., file downloads or HTML views).

## Authentication
All endpoints require authentication via the `auth` middleware and email verification via the `verified` middleware. Specific endpoints under `user` and `admin` prefixes require `user` or `admin` role middleware, respectively.

---

## User Endpoints
These endpoints are accessible to authenticated users with the `user` role, under the `/user` prefix, and are protected by `auth`, `verified`, and `user` middleware. The route names are prefixed with `user.`.

### 1. User Dashboard
- **Method**: GET
- **URI**: `/user/dashboard`
- **Route Name**: `user.dashboard`
- **Description**: Displays the user's dashboard.
- **Authentication**: Required (user role).
- **Parameters**: None
- **Response**:
  - **200 OK**: HTML view of the user dashboard.
    ```html
    <!-- User dashboard HTML -->
    <div>Welcome, {user_name}!</div>
    ```
- **Notes**: Primarily for web usage; returns an HTML view.

### 2. List Books
- **Method**: GET
- **URI**: `/user/books`
- **Route Name**: `user.books.index`
- **Description**: Retrieves a paginated list of books with optional search.
- **Authentication**: Required (user role).
- **Parameters** (Query):
  - `search` (string, optional): Search term for book title, ISBN, description, author, or category.
  - `page` (integer, optional): Page number for pagination (default: 1).
- **Response**:
  - **200 OK**: Paginated list of books.
    ```json
    {
      "books": [
        {
          "id": 1,
          "title": "Sample Book",
          "isbn": "1234567890123",
          "author": {
            "id": 1,
            "first_name": "John",
            "last_name": "Doe"
          },
          "category": {
            "id": 1,
            "name": "Fiction"
          }
        }
      ],
      "current_page": 1,
      "total": 1,
      "per_page": 10
    }
    ```
- **Notes**: Returns an HTML view (`user.books.index`) for web usage with paginated data.

### 3. Search Books
- **Method**: GET
- **URI**: `/user/books/search-book`
- **Route Name**: `user.books.search-book`
- **Description**: Searches books by title, ISBN, description, author, or category.
- **Authentication**: Required (user role).
- **Parameters** (Query):
  - `search` (string, required): Search term.
- **Response**:
  - **200 OK**: HTML view with search results.
    ```html
    <!-- Book search results HTML -->
    <div>Search results for "{search_term}"</div>
    ```
- **Notes**: Primarily for web usage; similar to the books index but focused on search.

### 4. Show Book
- **Method**: GET
- **URI**: `/user/books/{book}`
- **Route Name**: `user.books.show`
- **Description**: Retrieves details of a specific book.
- **Authentication**: Required (user role).
- **Parameters**:
  - Path:
    - `book` (integer, required): The book ID.
- **Response**:
  - **200 OK**: Book details.
    ```json
    {
      "book": {
        "id": 1,
        "title": "Sample Book",
        "isbn": "1234567890123",
        "description": "A sample book description",
        "author": {
          "id": 1,
          "first_name": "John",
          "last_name": "Doe"
        },
        "category": {
          "id": 1,
          "name": "Fiction"
        }
      }
    }
    ```
  - **404 Not Found**: Book not found.
    ```json
    {
      "message": "Book not found."
    }
    ```
- **Notes**: Returns an HTML view (`user.books.show`) for web usage.

### 5. List Borrowings
- **Method**: GET
- **URI**: `/user/borrowings`
- **Route Name**: `user.borrowings.index`
- **Description**: Retrieves a paginated list of the user's borrowings with optional search.
- **Authentication**: Required (user role).
- **Parameters** (Query):
  - `search` (string, optional): Search term for book title, ISBN, author, category, status, or description.
  - `page` (integer, optional): Page number for pagination (default: 1).
- **Response**:
  - **200 OK**: Paginated list of borrowings.
    ```json
    {
      "borrowings": [
        {
          "id": 1,
          "book": {
            "id": 1,
            "title": "Sample Book"
          },
          "status": "active",
          "borrowed_at": "2025-05-01T10:00:00Z",
          "due_at": "2025-05-15T10:00:00Z"
        }
      ],
      "current_page": 1,
      "total": 1,
      "per_page": 10
    }
    ```
- **Notes**: Returns an HTML view (`user.borrowings.index`) for web usage.

### 6. Create Borrowing Form
- **Method**: GET
- **URI**: `/user/borrowings/create`
- **Route Name**: `user.borrowings.create`
- **Description**: Displays the form to create a new borrowing.
- **Authentication**: Required (user role).
- **Parameters**: None
- **Response**:
  - **200 OK**: HTML view of the borrowing creation form.
    ```html
    <!-- Borrowing creation form HTML -->
    <form method="POST" action="/user/borrowings">
        <select name="book_id">
            <option value="1">Sample Book</option>
        </select>
        <input type="date" name="due_at">
        <textarea name="description"></textarea>
        <button type="submit">Borrow</button>
    </form>
    ```
- **Notes**: Lists available books for borrowing.

### 7. Store Borrowing
- **Method**: POST
- **URI**: `/user/borrowings`
- **Route Name**: `user.borrowings.store`
- **Description**: Creates a new borrowing for the user.
- **Authentication**: Required (user role).
- **Parameters** (Body, `multipart/form-data` or `application/json`):
  - `book_id` (integer, required): The ID of the book to borrow.
  - `due_at` (string, required): Due date (format: `YYYY-MM-DD`, must be after current date).
  - `description` (string, optional): Additional notes (max 500 characters).
- **Response**:
  - **201 Created**: Borrowing created, redirects to borrowings index.
    ```json
    {
      "message": "Book borrowed successfully.",
      "borrowing": {
        "id": 1,
        "book_id": 1,
        "status": "active"
      }
    }
    ```
  - **422 Unprocessable Entity**: Validation errors or book unavailable.
    ```json
    {
      "message": "The given data was invalid.",
      "errors": {
        "book_id": ["No available copies of this book."]
      }
    }
    ```
- **Notes**: Decrements available book copies; checks for existing borrowings.

### 8. Show Borrowing
- **Method**: GET
- **URI**: `/user/borrowings/{borrowing}`
- **Route Name**: `user.borrowings.show`
- **Description**: Retrieves details of a specific borrowing.
- **Authentication**: Required (user role).
- **Parameters**:
  - Path:
    - `borrowing` (integer, required): The borrowing ID.
- **Response**:
  - **200 OK**: Borrowing details.
    ```json
    {
      "borrowing": {
        "id": 1,
        "book": {
          "id": 1,
          "title": "Sample Book"
        },
        "status": "active",
        "borrowed_at": "2025-05-01T10:00:00Z",
        "due_at": "2025-05-15T10:00:00Z"
      }
    }
    ```
  - **403 Forbidden**: Borrowing does not belong to the user.
    ```json
    {
      "message": "Unauthorized."
    }
    ```
  - **404 Not Found**: Borrowing not found.
    ```json
    {
      "message": "Borrowing not found."
    }
    ```
- **Notes**: Returns an HTML view (`user.borrowings.show`) for web usage.

### 9. Return Borrowing
- **Method**: POST
- **URI**: `/user/borrowings/{borrowing}/return`
- **Route Name**: `user.borrowings.return`
- **Description**: Marks a borrowing as returned.
- **Authentication**: Required (user role).
- **Parameters**:
  - Path:
    - `borrowing` (integer, required): The borrowing ID.
- **Response**:
  - **200 OK**: Borrowing returned, redirects to borrowings index.
    ```json
    {
      "message": "Book returned successfully."
    }
    ```
  - **403 Forbidden**: Borrowing does not belong to the user.
    ```json
    {
      "message": "Unauthorized."
    }
    ```
  - **404 Not Found**: Borrowing not found.
    ```json
    {
      "message": "Borrowing not found."
    }
    ```
- **Notes**: Updates borrowing status and increments book copies.

### 10. List Reservations
- **Method**: GET
- **URI**: `/user/reservations`
- **Route Name**: `user.reservations.index`
- **Description**: Retrieves a paginated list of the user's reservations with optional search.
- **Authentication**: Required (user role).
- **Parameters** (Query):
  - `search` (string, optional): Search term for book title or status.
  - `page` (integer, optional): Page number for pagination (default: 1).
- **Response**:
  - **200 OK**: Paginated list of reservations.
    ```json
    {
      "reservations": [
        {
          "id": 1,
          "book": {
            "id": 1,
            "title": "Sample Book"
          },
          "status": "active",
          "reserved_at": "2025-05-01T10:00:00Z",
          "expires_at": "2025-05-15T10:00:00Z"
        }
      ],
      "current_page": 1,
      "total": 1,
      "per_page": 10
    }
    ```
- **Notes**: Returns an HTML view (`user.reservations.index`) for web usage.

### 11. Create Reservation Form
- **Method**: GET
- **URI**: `/user/reservations/create`
- **Route Name**: `user.reservations.create`
- **Description**: Displays the form to create a new reservation.
- **Authentication**: Required (user role).
- **Parameters**: None
- **Response**:
  - **200 OK**: HTML view of the reservation creation form.
    ```html
    <!-- Reservation creation form HTML -->
    <form method="POST" action="/user/reservations">
        <select name="book_id">
            <option value="1">Sample Book</option>
        </select>
        <input type="date" name="expires_at">
        <textarea name="description"></textarea>
        <button type="submit">Reserve</button>
    </form>
    ```
- **Notes**: Lists books available for reservation.

### 12. Store Reservation
- **Method**: POST
- **URI**: `/user/reservations`
- **Route Name**: `user.reservations.store`
- **Description**: Creates a new reservation for the user.
- **Authentication**: Required (user role).
- **Parameters** (Body, `multipart/form-data` or `application/json`):
  - `book_id` (integer, required): The ID of the book to reserve.
  - `expires_at` (string, required): Expiration date (format: `YYYY-MM-DD`, must be after current date).
  - `description` (string, optional): Additional notes (max 500 characters).
- **Response**:
  - **201 Created**: Reservation created, redirects to reservations index.
    ```json
    {
      "message": "Book successfully reserved.",
      "reservation": {
        "id": 1,
        "book_id": 1,
        "status": "active"
      }
    }
    ```
  - **422 Unprocessable Entity**: Validation errors or book unavailable.
    ```json
    {
      "message": "The given data was invalid.",
      "errors": {
        "book_id": ["This book cannot be reserved."]
      }
    }
    ```
- **Notes**: Sets status to `active` or `pending` based on book availability.

### 13. Show Reservation
- **Method**: GET
- **URI**: `/user/reservations/{reservation}`
- **Route Name**: `user.reservations.show`
- **Description**: Retrieves details of a specific reservation.
- **Authentication**: Required (user role).
- **Parameters**:
  - Path:
    - `reservation` (integer, required): The reservation ID.
- **Response**:
  - **200 OK**: Reservation details.
    ```json
    {
      "reservation": {
        "id": 1,
        "book": {
          "id": 1,
          "title": "Sample Book"
        },
        "status": "active",
        "reserved_at": "2025-05-01T10:00:00Z",
        "expires_at": "2025-05-15T10:00:00Z"
      }
    }
    ```
  - **403 Forbidden**: Reservation does not belong to the user.
    ```json
    {
      "message": "Unauthorized."
    }
    ```
  - **404 Not Found**: Reservation not found.
    ```json
    {
      "message": "Reservation not found."
    }
    ```
- **Notes**: Returns an HTML view (`user.reservations.show`) for web usage.

### 14. Cancel Reservation
- **Method**: POST
- **URI**: `/user/reservations/{reservation}/cancel`
- **Route Name**: `user.reservations.cancel`
- **Description**: Cancels a user's reservation.
- **Authentication**: Required (user role).
- **Parameters**:
  - Path:
    - `reservation` (integer, required): The reservation ID.
- **Response**:
  - **200 OK**: Reservation canceled, redirects to reservations index.
    ```json
    {
      "message": "Reservation canceled successfully."
    }
    ```
  - **403 Forbidden**: Reservation does not belong to the user.
    ```json
    {
      "message": "Unauthorized."
    }
    ```
  - **404 Not Found**: Reservation not found.
    ```json
    {
      "message": "Reservation not found."
    }
    ```
- **Notes**: Updates reservation status to `canceled`.

### 15. Create Borrowing from Reservation
- **Method**: POST
- **URI**: `/user/reservations/{reservation}/create-borrowing`
- **Route Name**: `user.reservations.create-borrowing`
- **Description**: Creates a borrowing from an active reservation.
- **Authentication**: Required (user role).
- **Parameters**:
  - Path:
    - `reservation` (integer, required): The reservation ID.
- **Response**:
  - **201 Created**: Borrowing created, redirects to borrowings index.
    ```json
    {
      "message": "Book borrowed successfully.",
      "borrowing": {
        "id": 1,
        "book_id": 1,
        "status": "active"
      }
    }
    ```
  - **403 Forbidden**: Reservation does not belong to the user or is not active.
    ```json
    {
      "message": "Unauthorized."
    }
    ```
  - **404 Not Found**: Reservation not found.
    ```json
    {
      "message": "Reservation not found."
    }
    ```
- **Notes**: Converts an active reservation to a borrowing; updates reservation status.

### 16. Search Users
- **Method**: GET
- **URI**: `/user/users/search`
- **Route Name**: `user.users.search`
- **Description**: Searches for users (likely for collaboration or information).
- **Authentication**: Required (user role).
- **Parameters** (Query):
  - `search` (string, optional): Search term for user name or email.
- **Response**:
  - **200 OK**: HTML view with user search results.
    ```html
    <!-- User search results HTML -->
    <div>Search results for "{search_term}"</div>
    ```
- **Notes**: Primarily for web usage.

### 17. List Categories
- **Method**: GET
- **URI**: `/user/categories`
- **Route Name**: `user.categories.index`
- **Description**: Retrieves a list of categories.
- **Authentication**: Required (user role).
- **Parameters**: None
- **Response**:
  - **200 OK**: List of categories.
    ```json
    {
      "categories": [
        {
          "id": 1,
          "name": "Fiction"
        }
      ]
    }
    ```
- **Notes**: Returns an HTML view (`user.categories.index`) for web usage.

### 18. Show Category
- **Method**: GET
- **URI**: `/user/categories/{category}`
- **Route Name**: `user.categories.show`
- **Description**: Retrieves details of a specific category.
- **Authentication**: Required (user role).
- **Parameters**:
  - Path:
    - `category` (integer, required): The category ID.
- **Response**:
  - **200 OK**: Category details.
    ```json
    {
      "category": {
        "id": 1,
        "name": "Fiction"
      }
    }
    ```
  - **404 Not Found**: Category not found.
    ```json
    {
      "message": "Category not found."
    }
    ```
- **Notes**: Returns an HTML view (`user.categories.show`) for web usage.

### 19. List Authors
- **Method**: GET
- **URI**: `/user/authors`
- **Route Name**: `user.authors.index`
- **Description**: Retrieves a list of authors.
- **Authentication**: Required (user role).
- **Parameters**: None
- **Response**:
  - **200 OK**: List of authors.
    ```json
    {
      "authors": [
        {
          "id": 1,
          "first_name": "John",
          "last_name": "Doe"
        }
      ]
    }
    ```
- **Notes**: Returns an HTML view (`user.authors.index`) for web usage.

### 20. Show Author
- **Method**: GET
- **URI**: `/user/authors/{author}`
- **Route Name**: `user.authors.show`
- **Description**: Retrieves details of a specific author.
- **Authentication**: Required (user role).
- **Parameters**:
  - Path:
    - `author` (integer, required): The author ID.
- **Response**:
  - **200 OK**: Author details.
    ```json
    {
      "author": {
        "id": 1,
        "first_name": "John",
        "last_name": "Doe",
        "bio": "Sample biography"
      }
    }
    ```
  - **404 Not Found**: Author not found.
    ```json
    {
      "message": "Author not found."
    }
    ```
- **Notes**: Returns an HTML view (`user.authors.show`) for web usage.

---

## Admin Endpoints
These endpoints are accessible to authenticated users with the `admin` role, under the `/admin` prefix, and are protected by `auth`, `verified`, and `admin` middleware. The route names are prefixed with `admin.`.

### 1. Admin Dashboard
- **Method**: GET
- **URI**: `/admin/dashboard`
- **Route Name**: `admin.dashboard`
- **Description**: Displays the admin dashboard.
- **Authentication**: Required (admin role).
- **Parameters**: None
- **Response**:
  - **200 OK**: HTML view of the admin dashboard.
    ```html
    <!-- Admin dashboard HTML -->
    <div>Welcome, Admin!</div>
    ```
- **Notes**: Primarily for web usage; returns an HTML view.

### 2. Search Users (Admin)
- **Method**: GET
- **URI**: `/admin/users/search`
- **Route Name**: `admin.users.search`
- **Description**: Searches for users (for admin management).
- **Authentication**: Required (admin role).
- **Parameters** (Query):
  - `search` (string, optional): Search term for user name or email.
- **Response**:
  - **200 OK**: HTML view with user search results.
    ```html
    <!-- User search results HTML -->
    <div>Search results for "{search_term}"</div>
    ```
- **Notes**: Primarily for web usage.

### 3. List Users
- **Method**: GET
- **URI**: `/admin/users`
- **Route Name**: `admin.users.index`
- **Description**: Retrieves a paginated list of users.
- **Authentication**: Required (admin role).
- **Parameters** (Query):
  - `page` (integer, optional): Page number for pagination (default: 1).
- **Response**:
  - **200 OK**: Paginated list of users.
    ```json
    {
      "users": [
        {
          "id": 1,
          "name": "John Doe",
          "email": "john@example.com",
          "role": "user"
        }
      ],
      "current_page": 1,
      "total": 1,
      "per_page": 15
    }
    ```
- **Notes**: Returns an HTML view for web usage.

### 4. Create User Form
- **Method**: GET
- **URI**: `/admin/users/create`
- **Route Name**: `admin.users.create`
- **Description**: Displays the form to create a new user.
- **Authentication**: Required (admin role).
- **Parameters**: None
- **Response**:
  - **200 OK**: HTML view of the user creation form.
    ```html
    <!-- User creation form HTML -->
    <form method="POST" action="/admin/users">
        <input type="text" name="name">
        <input type="email" name="email">
        <input type="password" name="password">
        <select name="role">
            <option value="user">User</option>
            <option value="admin">Admin</option>
        </select>
        <button type="submit">Create</button>
    </form>
    ```
- **Notes**: For web usage.

### 5. Store User
- **Method**: POST
- **URI**: `/admin/users`
- **Route Name**: `admin.users.store`
- **Description**: Creates a new user.
- **Authentication**: Required (admin role).
- **Parameters** (Body, `multipart/form-data` or `application/json`):
  - `name` (string, required): The user's name.
  - `email` (string, required): The user's email.
  - `password` (string, required): The user's password.
  - `role` (string, required): The user's role (`user` or `admin`).
- **Response**:
  - **201 Created**: User created, redirects to users index.
    ```json
    {
      "message": "User created successfully.",
      "user": {
        "id": 1,
        "name": "John Doe",
        "email": "john@example.com",
        "role": "user"
      }
    }
    ```
  - **422 Unprocessable Entity**: Validation errors.
    ```json
    {
      "message": "The given data was invalid.",
      "errors": {
        "email": ["The email has already been taken."]
      }
    }
    ```
- **Notes**: Admin-only action.

### 6. Show User
- **Method**: GET
- **URI**: `/admin/users/{user}`
- **Route Name**: `admin.users.show`
- **Description**: Retrieves details of a specific user.
- **Authentication**: Required (admin role).
- **Parameters**:
  - Path:
    - `user` (integer, required): The user ID.
- **Response**:
  - **200 OK**: User details.
    ```json
    {
      "user": {
        "id": 1,
        "name": "John Doe",
        "email": "john@example.com",
        "role": "user"
      }
    }
    ```
  - **404 Not Found**: User not found.
    ```json
    {
      "message": "User not found."
    }
    ```
- **Notes**: Returns an HTML view for web usage.

### 7. Edit User Form
- **Method**: GET
- **URI**: `/admin/users/{user}/edit`
- **Route Name**: `admin.users.edit`
- **Description**: Displays the form to edit a user.
- **Authentication**: Required (admin role).
- **Parameters**:
  - Path:
    - `user` (integer, required): The user ID.
- **Response**:
  - **200 OK**: HTML view of the user edit form.
    ```html
    <!-- User edit form HTML -->
    <form method="POST" action="/admin/users/{user}">
        <input type="hidden" name="_method" value="PUT">
        <input type="text" name="name" value="{user_name}">
        <input type="email" name="email" value="{user_email}">
        <select name="role">
            <option value="user">User</option>
            <option value="admin">Admin</option>
        </select>
        <button type="submit">Update</button>
    </form>
    ```
- **Notes**: For web usage.

### 8. Update User
- **Method**: PUT
- **URI**: `/admin/users/{user}`
- **Route Name**: `admin.users.update`
- **Description**: Updates a user's details.
- **Authentication**: Required (admin role).
- **Parameters**:
  - Path:
    - `user` (integer, required): The user ID.
  - Body (`multipart/form-data` or `application/json`):
    - `name` (string, optional): The user's name.
    - `email` (string, optional): The user's email.
    - `role` (string, optional): The user's role (`user` or `admin`).
- **Response**:
  - **200 OK**: User updated, redirects to users index.
    ```json
    {
      "message": "User updated successfully.",
      "user": {
        "id": 1,
        "name": "John Doe",
        "email": "john@example.com",
        "role": "user"
      }
    }
    ```
  - **422 Unprocessable Entity**: Validation errors.
    ```json
    {
      "message": "The given data was invalid.",
      "errors": {
        "email": ["The email has already been taken."]
      }
    }
    ```
- **Notes**: Admin-only action.

### 9. Delete User
- **Method**: DELETE
- **URI**: `/admin/users/{user}`
- **Route Name**: `admin.users.destroy`
- **Description**: Deletes a user.
- **Authentication**: Required (admin role).
- **Parameters**:
  - Path:
    - `user` (integer, required): The user ID.
- **Response**:
  - **200 OK**: User deleted, redirects to users index.
    ```json
    {
      "message": "User deleted successfully."
    }
    ```
  - **404 Not Found**: User not found.
    ```json
    {
      "message": "User not found."
    }
    ```
- **Notes**: Admin-only action; cascades to related records (e.g., borrowings).

### 10. Search Books (Admin)
- **Method**: GET
- **URI**: `/admin/books/search-book`
- **Route Name**: `admin.books.search-book`
- **Description**: Searches books by title, ISBN, description, author, or category.
- **Authentication**: Required (admin role).
- **Parameters** (Query):
  - `search` (string, required): Search term.
- **Response**:
  - **200 OK**: HTML view with search results.
    ```html
    <!-- Book search results HTML -->
    <div>Search results for "{search_term}"</div>
    ```
- **Notes**: Primarily for web usage.

### 11. List Books (Admin)
- **Method**: GET
- **URI**: `/admin/books`
- **Route Name**: `admin.books.index`
- **Description**: Retrieves a paginated list of books with optional search.
- **Authentication**: Required (admin role).
- **Parameters** (Query):
  - `search` (string, optional): Search term for book title, ISBN, description, author, or category.
  - `page` (integer, optional): Page number for pagination (default: 1).
- **Response**:
  - **200 OK**: Paginated list of books.
    ```json
    {
      "books": [
        {
          "id": 1,
          "title": "Sample Book",
          "isbn": "1234567890123",
          "author": {
            "id": 1,
            "first_name": "John",
            "last_name": "Doe"
          },
          "category": {
            "id": 1,
            "name": "Fiction"
          }
        }
      ],
      "current_page": 1,
      "total": 1,
      "per_page": 10
    }
    ```
- **Notes**: Returns an HTML view (`admin.books.index`) for web usage.

### 12. Create Book Form
- **Method**: GET
- **URI**: `/admin/books/create`
- **Route Name**: `admin.books.create`
- **Description**: Displays the form to create a new book.
- **Authentication**: Required (admin role).
- **Parameters**: None
- **Response**:
  - **200 OK**: HTML view of the book creation form.
    ```html
    <!-- Book creation form HTML -->
    <form method="POST" action="/admin/books">
        <input type="text" name="title">
        <input type="text" name="isbn">
        <textarea name="description"></textarea>
        <input type="number" name="published_year">
        <select name="author_id">
            <option value="1">John Doe</option>
        </select>
        <select name="category_id">
            <option value="1">Fiction</option>
        </select>
        <input type="number" name="available_copies">
        <input type="number" name="total_copies">
        <input type="file" name="file_pdf">
        <button type="submit">Create</button>
    </form>
    ```
- **Notes**: For web usage.

### 13. Store Book
- **Method**: POST
- **URI**: `/admin/books`
- **Route Name**: `admin.books.store`
- **Description**: Creates a new book.
- **Authentication**: Required (admin role).
- **Parameters** (Body, `multipart/form-data`):
  - `title` (string, required): The book title (max 255 characters).
  - `isbn` (string, required): The book ISBN (unique).
  - `description` (string, optional): The book description.
  - `published_year` (integer, required): The publication year (1900 to current year).
  - `author_id` (integer, required): The author ID.
  - `category_id` (integer, required): The category ID.
  - `available_copies` (integer, required): Number of available copies (min 0).
  - `total_copies` (integer, required): Total copies (min 0, >= available_copies).
  - `file_pdf` (file, optional): PDF file (max 200MB).
  - `file_docx` (file, optional): DOCX file (max 200MB).
  - `file_epub` (file, optional): EPUB file (max 200MB).
- **Response**:
  - **201 Created**: Book created, redirects to books index.
    ```json
    {
      "message": "Book created successfully.",
      "book": {
        "id": 1,
        "title": "Sample Book",
        "isbn": "1234567890123"
      }
    }
    ```
  - **422 Unprocessable Entity**: Validation errors.
    ```json
    {
      "message": "The given data was invalid.",
      "errors": {
        "isbn": ["The isbn has already been taken."]
      }
    }
    ```
- **Notes**: Stores uploaded files in the public disk.

### 14. Show Book (Admin)
- **Method**: GET
- **URI**: `/admin/books/{book}`
- **Route Name**: `admin.books.show`
- **Description**: Retrieves details of a specific book.
- **Authentication**: Required (admin role).
- **Parameters**:
  - Path:
    - `book` (integer, required): The book ID.
- **Response**:
  - **200 OK**: Book details.
    ```json
    {
      "book": {
        "id": 1,
        "title": "Sample Book",
        "isbn": "1234567890123",
        "description": "A sample book description",
        "author": {
          "id": 1,
          "first_name": "John",
          "last_name": "Doe"
        },
        "category": {
          "id": 1,
          "name": "Fiction"
        }
      }
    }
    ```
  - **404 Not Found**: Book not found.
    ```json
    {
      "message": "Book not found."
    }
    ```
- **Notes**: Returns an HTML view (`admin.books.show`) for web usage.

### 15. Edit Book Form
- **Method**: GET
- **URI**: `/admin/books/{book}/edit`
- **Route Name**: `admin.books.edit`
- **Description**: Displays the form to edit a book.
- **Authentication**: Required (admin role).
- **Parameters**:
  - Path:
    - `book` (integer, required): The book ID.
- **Response**:
  - **200 OK**: HTML view of the book edit form.
    ```html
    <!-- Book edit form HTML -->
    <form method="POST" action="/admin/books/{book}">
        <input type="hidden" name="_method" value="PUT">
        <input type="text" name="title" value="{book_title}">
        <input type="text" name="isbn" value="{book_isbn}">
        <textarea name="description">{book_description}</textarea>
        <input type="number" name="published_year" value="{book_published_year}">
        <select name="author_id">
            <option value="1" selected>John Doe</option>
        </select>
        <select name="category_id">
            <option value="1" selected>Fiction</option>
        </select>
        <input type="number" name="available_copies" value="{book_available_copies}">
        <input type="number" name="total_copies" value="{book_total_copies}">
        <input type="file" name="file_pdf">
        <button type="submit">Update</button>
    </form>
    ```
- **Notes**: For web usage.

### 16. Update Book
- **Method**: PUT
- **URI**: `/admin/books/{book}`
- **Route Name**: `admin.books.update`
- **Description**: Updates a book's details.
- **Authentication**: Required (admin role).
- **Parameters**:
  - Path:
    - `book` (integer, required): The book ID.
  - Body (`multipart/form-data`):
    - `title` (string, optional): The book title (max 255 characters).
    - `isbn` (string, optional): The book ISBN (unique).
    - `description` (string, optional): The book description.
    - `published_year` (integer, optional): The publication year (1900 to current year).
    - `author_id` (integer, optional): The author ID.
    - `category_id` (integer, optional): The category ID.
    - `available_copies` (integer, optional): Number of available copies (min 0).
    - `total_copies` (integer, optional): Total copies (min 0, >= available_copies).
    - `file_pdf` (file, optional): PDF file (max 20MB).
    - `file_docx` (file, optional): DOCX file (max 20MB).
    - `file_epub` (file, optional): EPUB file (max 20MB).
- **Response**:
  - **200 OK**: Book updated, redirects to books index.
    ```json
    {
      "message": "Book updated successfully.",
      "book": {
        "id": 1,
        "title": "Updated Book",
        "isbn": "9876543210987"
      }
    }
    ```
  - **422 Unprocessable Entity**: Validation errors.
    ```json
    {
      "message": "The given data was invalid.",
      "errors": {
        "isbn": ["The isbn has already been taken."]
      }
    }
    ```
- **Notes**: Replaces existing files if new ones are uploaded.

### 17. Delete Book
- **Method**: DELETE
- **URI**: `/admin/books/{book}`
- **Route Name**: `admin.books.destroy`
- **Description**: Deletes a book.
- **Authentication**: Required (admin role).
- **Parameters**:
  - Path:
    - `book` (integer, required): The book ID.
- **Response**:
  - **200 OK**: Book deleted, redirects to books index.
    ```json
    {
      "message": "Book deleted successfully."
    }
    ```
  - **404 Not Found**: Book not found.
    ```json
    {
      "message": "Book not found."
    }
    ```
- **Notes**: Deletes associated files from storage.

### 18. List Borrowings (Admin)
- **Method**: GET
- **URI**: `/admin/borrowings`
- **Route Name**: `admin.borrowings.index`
- **Description**: Retrieves a paginated list of all borrowings.
- **Authentication**: Required (admin role).
- **Parameters** (Query):
  - `page` (integer, optional): Page number for pagination (default: 1).
- **Response**:
  - **200 OK**: Paginated list of borrowings.
    ```json
    {
      "borrowings": [
        {
          "id": 1,
          "user": {
            "id": 1,
            "name": "John Doe"
          },
          "book": {
            "id": 1,
            "title": "Sample Book"
          },
          "status": "active",
          "borrowed_at": "2025-05-01T10:00:00Z",
          "due_at": "2025-05-15T10:00:00Z"
        }
      ],
      "current_page": 1,
      "total": 1,
      "per_page": 10
    }
    ```
- **Notes**: Returns an HTML view (`admin.borrowings.index`) for web usage.

### 19. Create Borrowing Form (Admin)
- **Method**: GET
- **URI**: `/admin/borrowings/create`
- **Route Name**: `admin.borrowings.create`
- **Description**: Displays the form to create a new borrowing.
- **Authentication**: Required (admin role).
- **Parameters**: None
- **Response**:
  - **200 OK**: HTML view of the borrowing creation form.
    ```html
    <!-- Borrowing creation form HTML -->
    <form method="POST" action="/admin/borrowings">
        <select name="user_id">
            <option value="1">John Doe</option>
        </select>
        <select name="book_id">
            <option value="1">Sample Book</option>
        </select>
        <input type="date" name="due_at">
        <textarea name="description"></textarea>
        <button type="submit">Borrow</button>
    </form>
    ```
- **Notes**: For web usage; allows admin to borrow on behalf of users.

### 20. Store Borrowing (Admin)
- **Method**: POST
- **URI**: `/admin/borrowings`
- **Route Name**: `admin.borrowings.store`
- **Description**: Creates a new borrowing for a user.
- **Authentication**: Required (admin role).
- **Parameters** (Body, `multipart/form-data` or `application/json`):
  - `user_id` (integer, required): The user ID.
  - `book_id` (integer, required): The book ID.
  - `due_at` (string, required): Due date (format: `YYYY-MM-DD`, must be after current date).
  - `description` (string, optional): Additional notes (max 500 characters).
- **Response**:
  - **201 Created**: Borrowing created, redirects to borrowings index.
    ```json
    {
      "message": "Book borrowed successfully.",
      "borrowing": {
        "id": 1,
        "user_id": 1,
        "book_id": 1,
        "status": "active"
      }
    }
    ```
  - **422 Unprocessable Entity**: Validation errors or book unavailable.
    ```json
    {
      "message": "The given data was invalid.",
      "errors": {
        "book_id": ["No available copies of this book."]
      }
    }
    ```
- **Notes**: Admin-only action; similar to user borrowing but for any user.

### 21. Show Borrowing (Admin)
- **Method**: GET
- **URI**: `/admin/borrowings/{borrowing}`
- **Route Name**: `admin.borrowings.show`
- **Description**: Retrieves details of a specific borrowing.
- **Authentication**: Required (admin role).
- **Parameters**:
  - Path:
    - `borrowing` (integer, required): The borrowing ID.
- **Response**:
  - **200 OK**: Borrowing details.
    ```json
    {
      "borrowing": {
        "id": 1,
        "user": {
          "id": 1,
          "name": "John Doe"
        },
        "book": {
          "id": 1,
          "title": "Sample Book"
        },
        "status": "active",
        "borrowed_at": "2025-05-01T10:00:00Z",
        "due_at": "2025-05-15T10:00:00Z"
      }
    }
    ```
  - **404 Not Found**: Borrowing not found.
    ```json
    {
      "message": "Borrowing not found."
    }
    ```
- **Notes**: Returns an HTML view (`admin.borrowings.show`) for web usage.

### 22. Edit Borrowing Form
- **Method**: GET
- **URI**: `/admin/borrowings/{borrowing}/edit`
- **Route Name**: `admin.borrowings.edit`
- **Description**: Displays the form to edit a borrowing.
- **Authentication**: Required (admin role).
- **Parameters**:
  - Path:
    - `borrowing` (integer, required): The borrowing ID.
- **Response**:
  - **200 OK**: HTML view of the borrowing edit form.
    ```html
    <!-- Borrowing edit form HTML -->
    <form method="POST" action="/admin/borrowings/{borrowing}">
        <input type="hidden" name="_method" value="PUT">
        <select name="user_id">
            <option value="1" selected>John Doe</option>
        </select>
        <select name="book_id">
            <option value="1" selected>Sample Book</option>
        </select>
        <input type="date" name="due_at" value="{due_at}">
        <textarea name="description">{description}</textarea>
        <button type="submit">Update</button>
    </form>
    ```
- **Notes**: For web usage.

### 23. Update Borrowing
- **Method**: PUT
- **URI**: `/admin/borrowings/{borrowing}`
- **Route Name**: `admin.borrowings.update`
- **Description**: Updates a borrowing's details.
- **Authentication**: Required (admin role).
- **Parameters**:
  - Path:
    - `borrowing` (integer, required): The borrowing ID.
  - Body (`multipart/form-data` or `application/json`):
    - `user_id` (integer, optional): The user ID.
    - `book_id` (integer, optional): The book ID.
    - `due_at` (string, optional): Due date (format: `YYYY-MM-DD`).
    - `description` (string, optional): Additional notes (max 500 characters).
- **Response**:
  - **200 OK**: Borrowing updated, redirects to borrowings index.
    ```json
    {
      "message": "Borrowing updated successfully.",
      "borrowing": {
        "id": 1,
        "user_id": 1,
        "book_id": 1,
        "status": "active"
      }
    }
    ```
  - **422 Unprocessable Entity**: Validation errors.
    ```json
    {
      "message": "The given data was invalid.",
      "errors": {
        "due_at": ["The due at must be a date after now."]
      }
    }
    ```
- **Notes**: Admin-only action.

### 24. Return Borrowing (Admin)
- **Method**: POST
- **URI**: `/admin/borrowings/{borrowing}/return`
- **Route Name**: `admin.borrowings.return`
- **Description**: Marks a borrowing as returned.
- **Authentication**: Required (admin role).
- **Parameters**:
  - Path:
    - `borrowing` (integer, required): The borrowing ID.
- **Response**:
  - **200 OK**: Borrowing returned, redirects to borrowings index.
    ```json
    {
      "message": "Book returned successfully."
    }
    ```
  - **404 Not Found**: Borrowing not found.
    ```json
    {
      "message": "Borrowing not found."
    }
    ```
- **Notes**: Updates borrowing status and increments book copies.

### 25. Delete Borrowing
- **Method**: DELETE
- **URI**: `/admin/borrowings/{borrowing}`
- **Route Name**: `admin.borrowings.destroy`
- **Description**: Deletes a borrowing.
- **Authentication**: Required (admin role).
- **Parameters**:
  - Path:
    - `borrowing` (integer, required): The borrowing ID.
- **Response**:
  - **200 OK**: Borrowing deleted, redirects to borrowings index.
    ```json
    {
      "message": "Borrowing deleted successfully."
    }
    ```
  - **404 Not Found**: Borrowing not found.
    ```json
    {
      "message": "Borrowing not found."
    }
    ```
- **Notes**: Admin-only action.

### 26. Send Warning for Borrowing
- **Method**: POST
- **URI**: `/admin/borrowings/{borrowing}/warn`
- **Route Name**: `admin.borrowings.warn`
- **Description**: Sends a warning to the user for a borrowing (e.g., overdue).
- **Authentication**: Required (admin role).
- **Parameters**:
  - Path:
    - `borrowing` (integer, required): The borrowing ID.
- **Response**:
  - **200 OK**: Warning sent, redirects to borrowings index.
    ```json
    {
      "message": "Warning sent successfully."
    }
    ```
  - **404 Not Found**: Borrowing not found.
    ```json
    {
      "message": "Borrowing not found."
    }
    ```
- **Notes**: Likely sends an email or notification; admin-only action.

### 27. List Reservations (Admin)
- **Method**: GET
- **URI**: `/admin/reservations`
- **Route Name**: `admin.reservations.index`
- **Description**: Retrieves a paginated list of all reservations.
- **Authentication**: Required (admin role).
- **Parameters** (Query):
  - `page` (integer, optional): Page number for pagination (default: 1).
- **Response**:
  - **200 OK**: Paginated list of reservations.
    ```json
    {
      "reservations": [
        {
          "id": 1,
          "user": {
            "id": 1,
            "name": "John Doe"
          },
          "book": {
            "id": 1,
            "title": "Sample Book"
          },
          "status": "active",
          "reserved_at": "2025-05-01T10:00:00Z",
          "expires_at": "2025-05-15T10:00:00Z"
        }
      ],
      "current_page": 1,
      "total": 1,
      "per_page": 10
    }
    ```
- **Notes**: Returns an HTML view (`admin.reservations.index`) for web usage.

### 28. Create Reservation Form (Admin)
- **Method**: GET
- **URI**: `/admin/reservations/create`
- **Route Name**: `admin.reservations.create`
- **Description**: Displays the form to create a new reservation.
- **Authentication**: Required (admin role).
- **Parameters**: None
- **Response**:
  - **200 OK**: HTML view of the reservation creation form.
    ```html
    <!-- Reservation creation form HTML -->
    <form method="POST" action="/admin/reservations">
        <select name="user_id">
            <option value="1">John Doe</option>
        </select>
        <select name="book_id">
            <option value="1">Sample Book</option>
        </select>
        <input type="date" name="expires_at">
        <textarea name="description"></textarea>
        <button type="submit">Reserve</button>
    </form>
    ```
- **Notes**: For web usage; allows admin to reserve on behalf of users.

### 29. Store Reservation (Admin)
- **Method**: POST
- **URI**: `/admin/reservations`
- **Route Name**: `admin.reservations.store`
- **Description**: Creates a new reservation for a user.
- **Authentication**: Required (admin role).
- **Parameters** (Body, `multipart/form-data` or `application/json`):
  - `user_id` (integer, required): The user ID.
  - `book_id` (integer, required): The book ID.
  - `expires_at` (string, required): Expiration date (format: `YYYY-MM-DD`, must be after current date).
  - `description` (string, optional): Additional notes (max 500 characters).
- **Response**:
  - **201 Created**: Reservation created, redirects to reservations index.
    ```json
    {
      "message": "Book successfully reserved.",
      "reservation": {
        "id": 1,
        "user_id": 1,
        "book_id": 1,
        "status": "active"
      }
    }
    ```
  - **422 Unprocessable Entity**: Validation errors or book unavailable.
    ```json
    {
      "message": "The given data was invalid.",
      "errors": {
        "book_id": ["This book cannot be reserved."]
      }
    }
    ```
- **Notes**: Admin-only action; similar to user reservation but for any user.

### 30. Show Reservation (Admin)
- **Method**: GET
- **URI**: `/admin/reservations/{reservation}`
- **Route Name**: `admin.reservations.show`
- **Description**: Retrieves details of a specific reservation.
- **Authentication**: Required (admin role).
- **Parameters**:
  - Path:
    - `reservation` (integer, required): The reservation ID.
- **Response**:
  - **200 OK**: Reservation details.
    ```json
    {
      "reservation": {
        "id": 1,
        "user": {
          "id": 1,
          "name": "John Doe"
        },
        "book": {
          "id": 1,
          "title": "Sample Book"
        },
        "status": "active",
        "reserved_at": "2025-05-01T10:00:00Z",
        "expires_at": "2025-05-15T10:00:00Z"
      }
    }
    ```
  - **404 Not Found**: Reservation not found.
    ```json
    {
      "message": "Reservation not found."
    }
    ```
- **Notes**: Returns an HTML view (`admin.reservations.show`) for web usage.

### 31. Edit Reservation Form
- **Method**: GET
- **URI**: `/admin/reservations/{reservation}/edit`
- **Route Name**: `admin.reservations.edit`
- **Description**: Displays the form to edit a reservation.
- **Authentication**: Required (admin role).
- **Parameters**:
  - Path:
    - `reservation` (integer, required): The reservation ID.
- **Response**:
  - **200 OK**: HTML view of the reservation edit form.
    ```html
    <!-- Reservation edit form HTML -->
    <form method="POST" action="/admin/reservations/{reservation}">
        <input type="hidden" name="_method" value="PUT">
        <select name="user_id">
            <option value="1" selected>John Doe</option>
        </select>
        <select name="book_id">
            <option value="1" selected>Sample Book</option>
        </select>
        <input type="date" name="expires_at" value="{expires_at}">
        <textarea name="description">{description}</textarea>
        <button type="submit">Update</button>
    </form>
    ```
- **Notes**: For web usage.

### 32. Update Reservation
- **Method**: PUT
- **URI**: `/admin/reservations/{reservation}`
- **Route Name**: `admin.reservations.update`
- **Description**: Updates a reservation's details.
- **Authentication**: Required (admin role).
- **Parameters**:
  - Path:
    - `reservation` (integer, required): The reservation ID.
  - Body (`multipart/form-data` or `application/json`):
    - `user_id` (integer, optional): The user ID.
    - `book_id` (integer, optional): The book ID.
    - `expires_at` (string, optional): Expiration date (format: `YYYY-MM-DD`).
    - `description` (string, optional): Additional notes (max 500 characters).
- **Response**:
  - **200 OK**: Reservation updated, redirects to reservations index.
    ```json
    {
      "message": "Reservation updated successfully.",
      "reservation": {
        "id": 1,
        "user_id": 1,
        "book_id": 1,
        "status": "active"
      }
    }
    ```
  - **422 Unprocessable Entity**: Validation errors.
    ```json
    {
      "message": "The given data was invalid.",
      "errors": {
        "expires_at": ["The expires at must be a date after now."]
      }
    }
    ```
- **Notes**: Admin-only action.

### 33. Delete Reservation
- **Method**: DELETE
- **URI**: `/admin/reservations/{reservation}`
- **Route Name**: `admin.reservations.destroy`
- **Description**: Deletes a reservation.
- **Authentication**: Required (admin role).
- **Parameters**:
  - Path:
    - `reservation` (integer, required): The reservation ID.
- **Response**:
  - **200 OK**: Reservation deleted, redirects to reservations index.
    ```json
    {
      "message": "Reservation deleted successfully."
    }
    ```
  - **404 Not Found**: Reservation not found.
    ```json
    {
      "message": "Reservation not found."
    }
    ```
- **Notes**: Admin-only action.

### 34. Cancel Reservation (Admin)
- **Method**: POST
- **URI**: `/admin/reservations/{reservation}/cancel`
- **Route Name**: `admin.reservations.cancel`
- **Description**: Cancels a reservation.
- **Authentication**: Required (admin role).
- **Parameters**:
  - Path:
    - `reservation` (integer, required): The reservation ID.
- **Response**:
  - **200 OK**: Reservation canceled, redirects to reservations index.
    ```json
    {
      "message": "Reservation canceled successfully."
    }
    ```
  - **404 Not Found**: Reservation not found.
    ```json
    {
      "message": "Reservation not found."
    }
    ```
- **Notes**: Updates reservation status to `canceled`.

### 35. Create Borrowing from Reservation (Admin)
- **Method**: POST
- **URI**: `/admin/reservations/{reservation}/create-borrowing`
- **Route Name**: `admin.reservations.create-borrowing`
- **Description**: Creates a borrowing from an active reservation.
- **Authentication**: Required (admin role).
- **Parameters**:
  - Path:
    - `reservation` (integer, required): The reservation ID.
- **Response**:
  - **201 Created**: Borrowing created, redirects to borrowings index.
    ```json
    {
      "message": "Book borrowed successfully.",
      "borrowing": {
        "id": 1,
        "user_id": 1,
        "book_id": 1,
        "status": "active"
      }
    }
    ```
  - **404 Not Found**: Reservation not found.
    ```json
    {
      "message": "Reservation not found."
    }
    ```
- **Notes**: Converts an active reservation to a borrowing; updates reservation status.

### 36- **36. List Categories (Admin)**
- **Method**: GET
- **URI**: `/admin/categories`
- **Route Name**: `admin.categories.index`
- **Description**: Retrieves a list of categories.
- **Authentication**: Required (admin role).
- **Parameters**: None
- **Response**:
  - **200 OK**: List of categories.
    ```json
    {
      "categories": [
        {
          "id": 1,
          "name": "Fiction"
        }
      ]
    }
    ```
- **Notes**: Returns an HTML view (`admin.categories.index`) for web usage.

### 37. Create Category Form
- **Method**: GET
- **URI**: `/admin/categories/create`
- **Route Name**: `admin.categories.create`
- **Description**: Displays the form to create a new category.
- **Authentication**: Required (admin role).
- **Parameters**: None
- **Response**:
  - **200 OK**: HTML view of the category creation form.
    ```html
    <!-- Category creation form HTML -->
    <form method="POST" action="/admin/categories">
        <input type="text" name="name">
        <button type="submit">Create</button>
    </form>
    ```
- **Notes**: For web usage.

### 38. Store Category
- **Method**: POST
- **URI**: `/admin/categories`
- **Route Name**: `admin.categories.store`
- **Description**: Creates a new category.
- **Authentication**: Required (admin role).
- **Parameters** (Body, `multipart/form-data` or `application/json`):
  - `name` (string, required): The category name.
- **Response**:
  - **201 Created**: Category created, redirects to categories index.
    ```json
    {
      "message": "Category created successfully.",
      "category": {
        "id": 1,
        "name": "Fiction"
      }
    }
    ```
  - **422 Unprocessable Entity**: Validation errors.
    ```json
    {
      "message": "The given data was invalid.",
      "errors": {
        "name": ["The name field is required."]
      }
    }
    ```
- **Notes**: Admin-only action.

### 39. Show Category (Admin)
- **Method**: GET
- **URI**: `/admin/categories/{category}`
- **Route Name**: `admin.categories.show`
- **Description**: Retrieves details of a specific category.
- **Authentication**: Required (admin role).
- **Parameters**:
  - Path:
    - `category` (integer, required): The category ID.
- **Response**:
  - **200 OK**: Category details.
    ```json
    {
      "category": {
        "id": 1,
        "name": "Fiction"
      }
    }
    ```
  - **404 Not Found**: Category not found.
    ```json
    {
      "message": "Category not found."
    }
    ```
- **Notes**: Returns an HTML view (`admin.categories.show`) for web usage.

### 40. Edit Category Form
- **Method**: GET
- **URI**: `/admin/categories/{category}/edit`
- **Route Name**: `admin.categories.edit`
- **Description**: Displays the form to edit a category.
- **Authentication**: Required (admin role).
- **Parameters**:
  - Path:
    - `category` (integer, required): The category ID.
- **Response**:
  - **200 OK**: HTML view of the category edit form.
    ```html
    <!-- Category edit form HTML -->
    <form method="POST" action="/admin/categories/{category}">
        <input type="hidden" name="_method" value="PUT">
        <input type="text" name="name" value="{category_name}">
        <button type="submit">Update</button>
    </form>
    ```
- **Notes**: For web usage.

### 41. Update Category
- **Method**: PUT
- **URI**: `/admin/categories/{category}`
- **Route Name**: `admin.categories.update`
- **Description**: Updates a category's details.
- **Authentication**: Required (admin role).
- **Parameters**:
  - Path:
    - `category` (integer, required): The category ID.
  - Body (`multipart/form-data` or `application/json`):
    - `name` (string, optional): The category name.
- **Response**:
  - **200 OK**: Category updated, redirects to categories index.
    ```json
    {
      "message": "Category updated successfully.",
      "category": {
        "id": 1,
        "name": "Updated Fiction"
      }
    }
    ```
  - **422 Unprocessable Entity**: Validation errors.
    ```json
    {
      "message": "The given data was invalid.",
      "errors": {
        "name": ["The name field is required."]
      }
    }
    ```
- **Notes**: Admin-only action.

### 42. Delete Category
- **Method**: DELETE
- **URI**: `/admin/categories/{category}`
- **Route Name**: `admin.categories.destroy`
- **Description**: Deletes a category.
- **Authentication**: Required (admin role).
- **Parameters**:
  - Path:
    - `category` (integer, required): The category ID.
- **Response**:
  - **200 OK**: Category deleted, redirects to categories index.
    ```json
    {
      "message": "Category deleted successfully."
    }
    ```
  - **404 Not Found**: Category not found.
    ```json
    {
      "message": "Category not found."
    }
    ```
- **Notes**: Admin-only action; may cascade to related books.

### 43. List Authors (Admin)
- **Method**: GET
- **URI**: `/admin/authors`
- **Route Name**: `admin.authors.index`
- **Description**: Retrieves a list of authors.
- **Authentication**: Required (admin role).
- **Parameters**: None
- **Response**:
  - **200 OK**: List of authors.
    ```json
    {
      "authors": [
        {
          "id": 1,
          "first_name": "John",
          "last_name": "Doe",
          "bio": "Sample biography"
        }
      ]
    }
    ```
- **Notes**: Returns an HTML view (`admin.authors.index`) for web usage.

### 44. Create Author Form
- **Method**: GET
- **URI**: `/admin/authors/create`
- **Route Name**: `admin.authors.create`
- **Description**: Displays the form to create a new author.
- **Authentication**: Required (admin role).
- **Parameters**: None
- **Response**:
  - **200 OK**: HTML view of the author creation form.
    ```html
    <!-- Author creation form HTML -->
    <form method="POST" action="/admin/authors">
        <input type="text" name="first_name">
        <input type="text" name="last_name">
        <textarea name="bio"></textarea>
        <button type="submit">Create</button>
    </form>
    ```
- **Notes**: For web usage.

### 45. Store Author
- **Method**: POST
- **URI**: `/admin/authors`
- **Route Name**: `admin.authors.store`
- **Description**: Creates a new author.
- **Authentication**: Required (admin role).
- **Parameters** (Body, `multipart/form-data` or `application/json`):
  - `first_name` (string, required): The author's first name.
  - `last_name` (string, required): The author's last name.
  - `bio` (string, optional): The author's biography.
- **Response**:
  - **201 Created**: Author created, redirects to authors index.
    ```json
    {
      "message": "Author created successfully.",
      "author": {
        "id": 1,
        "first_name": "John",
        "last_name": "Doe"
      }
    }
    ```
  - **422 Unprocessable Entity**: Validation errors.
    ```json
    {
      "message": "The given data was invalid.",
      "errors": {
        "first_name": ["The first name field is required."]
      }
    }
    ```
- **Notes**: Admin-only action.

### 46. Show Author (Admin)
- **Method**: GET
- **URI**: `/admin/authors/{author}`
- **Route Name**: `admin.authors.show`
- **Description**: Retrieves details of a specific author.
- **Authentication**: Required (admin role).
- **Parameters**:
  - Path:
    - `author` (integer, required): The author ID.
- **Response**:
  - **200 OK**: Author details.
    ```json
    {
      "author": {
        "id": 1,
        "first_name": "John",
        "last_name": "Doe",
        "bio": "Sample biography"
      }
    }
    ```
  - **404 Not Found**: Author not found.
    ```json
    {
      "message": "Author not found."
    }
    ```
- **Notes**: Returns an HTML view (`admin.authors.show`) for web usage.

### 47. Edit Author Form
- **Method**: GET
- **URI**: `/admin/authors/{author}/edit`
- **Route Name**: `admin.authors.edit`
- **Description**: Displays the form to edit an author.
- **Authentication**: Required (admin role).
- **Parameters**:
  - Path:
    - `author` (integer, required): The author ID.
- **Response**:
  - **200 OK**: HTML view of the author edit form.
    ```html
    <!-- Author edit form HTML -->
    <form method="POST" action="/admin/authors/{author}">
        <input type="hidden" name="_method" value="PUT">
        <input type="text" name="first_name" value="{author_first_name}">
        <input type="text" name="last_name" value="{author_last_name}">
        <textarea name="bio">{author_bio}</textarea>
        <button type="submit">Update</button>
    </form>
    ```
- **Notes**: For web usage.

### 48. Update Author
- **Method**: PUT
- **URI**: `/admin/authors/{author}`
- **Route Name**: `admin.authors.update`
- **Description**: Updates an author's details.
- **Authentication**: Required (admin role).
- **Parameters**:
  - Path:
    - `author` (integer, required): The author ID.
  - Body (`multipart/form-data` or `application/json`):
    - `first_name` (string, optional): The author's first name.
    - `last_name` (string, optional): The author's last name.
    - `bio` (string, optional): The author's biography.
- **Response**:
  - **200 OK**: Author updated, redirects to authors index.
    ```json
    {
      "message": "Author updated successfully.",
      "author": {
        "id": 1,
        "first_name": "Updated John",
        "last_name": "Doe"
      }
    }
    ```
  - **422 Unprocessable Entity**: Validation errors.
    ```json
    {
      "message": "The given data was invalid.",
      "errors": {
        "first_name": ["The first name field is required."]
      }
    }
    ```
- **Notes**: Admin-only action.

### 49. Delete Author
- **Method**: DELETE
- **URI**: `/admin/authors/{author}`
- **Route Name**: `admin.authors.destroy`
- **Description**: Deletes an author.
- **Authentication**: Required (admin role).
- **Parameters**:
  - Path:
    - `author` (integer, required): The author ID.
- **Response**:
  - **200 OK**: Author deleted, redirects to authors index.
    ```json
    {
      "message": "Author deleted successfully."
    }
    ```
  - **404 Not Found**: Author not found.
    ```json
    {
      "message": "Author not found."
    }
    ```
- **Notes**: Admin-only action; may cascade to related books.

---

## Profile Endpoints
These endpoints are accessible to authenticated users (any role) under the `/profile` prefix, protected by `auth` middleware. The route names are prefixed with `profile.`.

### 1. Edit Profile Form
- **Method**: GET
- **URI**: `/profile`
- **Route Name**: `profile.edit`
- **Description**: Displays the form to edit the user's profile.
- **Authentication**: Required (any role).
- **Parameters**: None
- **Response**:
  - **200 OK**: HTML view of the profile edit form.
    ```html
    <!-- Profile edit form HTML -->
    <form method="POST" action="/profile">
        <input type="hidden" name="_method" value="PATCH">
        <input type="text" name="name" value="{user_name}">
        <input type="email" name="email" value="{user_email}">
        <button type="submit">Update</button>
    </form>
    ```
- **Notes**: For web usage.

### 2. Update Profile
- **Method**: PATCH
- **URI**: `/profile`
- **Route Name**: `profile.update`
- **Description**: Updates the user's profile details.
- **Authentication**: Required (any role).
- **Parameters** (Body, `multipart/form-data` or `application/json`):
  - `name` (string, optional): The user's name.
  - `email` (string, optional): The user's email.
- **Response**:
  - **200 OK**: Profile updated, redirects to profile edit.
    ```json
    {
      "message": "Profile updated successfully.",
      "user": {
        "id": 1,
        "name": "John Doe",
        "email": "john@example.com"
      }
    }
    ```
  - **422 Unprocessable Entity**: Validation errors.
    ```json
    {
      "message": "The given data was invalid.",
      "errors": {
        "email": ["The email has already been taken."]
      }
    }
    ```
- **Notes**: May require email verification if email is changed.

### 3. Delete Profile
- **Method**: DELETE
- **URI**: `/profile`
- **Route Name**: `profile.destroy`
- **Description**: Deletes the user's account.
- **Authentication**: Required (any role).
- **Parameters**: None
- **Response**:
  - **200 OK**: Profile deleted, redirects to homepage.
    ```json
    {
      "message": "Account deleted successfully."
    }
    ```
- **Notes**: Logs out the user and cascades to related records.

---

## Book Download Endpoint
This endpoint is accessible to authenticated users (any role), protected by `auth` middleware.

### 1. Download Book File
- **Method**: GET
- **URI**: `/books/{book}/download/{format}`
- **Route Name**: `books.download`
- **Description**: Downloads a book file in the specified format.
- **Authentication**: Required (any role).
- **Parameters**:
  - Path:
    - `book` (integer, required): The book ID.
    - `format` (string, required): The file format (`pdf`, `docx`, or `epub`).
- **Response**:
  - **200 OK**: File download response.
    - Content-Type: `application/pdf`, `application/vnd.openxmlformats-officedocument.wordprocessingml.document`, or `application/epub+zip`
    - Content-Disposition: `attachment; filename="book.{format}"`
  - **404 Not Found**: Book or file not found.
    ```json
    {
      "message": "File not found."
    }
    ```
- **Notes**: Streams the file from the public disk.

---

## Error Handling
- All endpoints return appropriate HTTP status codes and JSON error messages for validation failures, unauthorized access, or other issues.
- Common error responses include:
  - **400 Bad Request**: Malformed request.
  - **401 Unauthorized**: Authentication failed or missing.
  - **403 Forbidden**: User lacks permission (e.g., wrong role).
  - **404 Not Found**: Resource not found.
  - **422 Unprocessable Entity**: Validation errors.

## Security Notes
- CSRF protection is enabled for all POST, PUT, PATCH, and DELETE requests. Include the CSRF token in the request headers or form data (e.g., `_token`).
- Role-based middleware (`user`, `admin`) restricts access to appropriate users.
- File uploads are validated for size and type.
- Database transactions ensure data consistency for borrowing and reservation operations.

## Example Workflow
1. **View Books**: GET `/user/books` to browse available books.
2. **Borrow Book**: POST `/user/borrowings` to borrow a book.
3. **Reserve Book**: POST `/user/reservations` if the book is unavailable.
4. **Admin Manage**: GET `/admin/books` to manage books, POST `/admin/books` to add a new book.
5. **Download Book**: GET `/books/{book}/download/pdf` to download a book file.
6.