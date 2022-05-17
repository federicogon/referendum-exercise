## Referendum Exercise TEST-1 branch

### Description

Each voter can vote in zero or more referendums. Each referendum has one or more questions, and each question is a yes/no vote. Write the simplest normalized schema to describe this in generic SQL statements or as an entity-relationship diagram. Point out where the indexes would be if you want to quickly know the results of a given referendum question, but you never expect to query a single voter's voting record. In what way does the nature of the application affect your design judgment about privacy, and what effect would that have on normalization considerations?


### Schema

https://dbdiagram.io/d/6276fa987f945876b6d4ea3b

### Goals

- As a voter I want to vote in a referendum
- As Admin I want to be able to create a referendum
- As Admin I want to see a referendum's results
- As Admin I want a list of all referendums sorted by "order" field. If 2 or more referendums have the same order number, we have to show the last referendum.

#### Bonus
- Add Unit tests 
- Code UI for voters

### Endpoints

    POST http://localhost:8000/api/referendum/create {"title": "Referendum 1","description": "Referendum 1 Description","order": 100.5,"questions": ["Question number 1","Question number 2","Question number 3"]}
    POST http://localhost:8000/api/referendum/vote {"referendum_id": 1, "username": "user1"}
    GET http://localhost:8000/api/referendum/results/1
    GET http://localhost:8000/api/referendum/results

### Dev Environment

1) clone repository https://github.com/federicogon/referendum-exercise
2) copy .env.example to .env
3) run:


    ```
    echo > ./database/referendum.sqlite
    php artisan key:generate
    composer install
    php artisan migrate
    php artisan db:seed
    php artisan serve
    ```
