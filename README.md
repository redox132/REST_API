# PHP REST API with SQLite

This is a simple RESTful API built with PHP and SQLite. It provides basic CRUD (Create, Read, Update, Delete) operations for a generic resource.
Features

    lightweight, file-based SQLite database
    RESTful API structure (GET, POST, PUT, DELETE)
    jSON request and response format
    errors and exceptions are always caught and retruned in json (this will provide clear errors for the caller)
    organized codebase with separation of concerns
    used PDO in case database changes, > 90 of code stays the same in case the database changes
    immune against sql injections
    logs all request in a log file
    supports pagination and filtering (id or email)
    supports cors for cross origin requests


#  Docs
Note: i used curl thoughout the project testing. it's build in and used for quicly getting statred. Yet, not the best choice.
    
    $ curl http://localhost:8000/users  // this will get all users. friendly Error, if no table exist or no data were returned from tha database or the table is empty

    $ curl http://localhost:8000/no_table // this will throw an handled \Exception.

    $ curl http://localhost:8000/table/no_id // this will throw an error 404.

    $ curl -X POST http://localhost:8000/users \
     -H "Content-Type: application/json" \
     -d '{"name": "name", "email": "name@example.com"}' // this will store a new record

    $ curl -X PATCH http://localhost:8000/users \
     -H "Content-Type: application/json" \
     -d '{"name": "yourName", "email": "yourName@example.com"}' // this will store a new record. the data to update and the values are received as an object and later converted to an array from the php:/input file. 

    $ curl -X DELETE http://localhost:8000/users/26 // this will delete a resource. or a 404 response code if the delete resource was not found

    $curl "http://localhost:8000/users/?email=name@example.com" // this will get a user based on the email.

    $curl curl "http://localhost:8000/users/?page=2&limit=1" // this willl get a set of results. if not set, page is 1 and limit is 10 by default. 


# Future Improvements

here are some planned or recommended future improvements:

    - Authentication & Authorization

        Add token-based (e.g., JWT) or session-based authentication

        Role-based access control

    - Input Validation & Sanitization

        Use middleware or helper functions to validate incoming data
