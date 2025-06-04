# PHP REST API with SQLite

This is a simple RESTful API built with PHP and SQLite. It provides basic CRUD (Create, Read, Update, Delete) operations for a generic resource.
Features

    lightweight, file-based SQLite database
    RESTful API structure (GET, POST, PUT, DELETE)
    jSON request and response format
    errors and exceptions are always caught and retruned in json
    organized codebase with separation of concerns
    used PDO in case database changes, > 90 of code stays the same
    immune against sql injections

 # Future Improvements

here are some planned or recommended future improvements:

    - Authentication & Authorization

        Add token-based (e.g., JWT) or session-based authentication

        Role-based access control

    - Input Validation & Sanitization

        Use middleware or helper functions to validate incoming data

        Prevent SQL injection and XSS attacks

    - CORS Support

        Add proper headers to allow cross-origin requests if this API is accessed from a frontend app

    - Logging & Monitoring

        Implement logging for errors and request history (e.g., using Monolog)


    - Pagination & Filtering

        Support pagination, sorting, and filtering for list endpoints
