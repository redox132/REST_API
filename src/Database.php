<?php
class Database {
    public static function connect() {
        return new PDO("sqlite:db/database.sqlite");
    }
}
