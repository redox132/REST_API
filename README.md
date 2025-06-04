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
    can PATCH data visa the query string. See bellow

#  docs
Note: i used curl thoughout the project testing. it's build in and used for quicly getting statred. Yet, not the best choice.
    
    $ curl http://localhost:8000/users  // this will get all users. friendly Error, if no table exist or no data were returned from tha database or the table is empty

    $ curl http://localhost:8000/no_table // this will throw an handled \Exception.

    $ curl http://localhost:8000/table/no_id // this will throw an error 404.

    $ curl -X POST http://localhost:8000/users \
     -H "Content-Type: application/json" \
     -d '{"name": "amine", "email": "amine@gmail.com"}' // this will store a new record

    $ curl -X PATCH http://localhost:8000/users \
     -H "Content-Type: application/json" \
     -d '{"name": "yourName", "email": "yourName@example.com"}' // this will store a new record. the data to update and the values are received as an object and later converted to an array from the php:/input file. 

    $ curl -X DELETE http://localhost:8000/users/26 // this will delete a resource. or a 404 response code if the delete resource was not found


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
