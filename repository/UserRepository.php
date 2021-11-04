<?php
require_once('Db.php');

class UserRepository extends Db
{
    public function findByEmail($email): array
    {
        $sql = "
        SELECT email
          FROM users
         WHERE email = ?
        ";
        $stmt = dbGetPrepareStmt($this->con, $sql, [$email]);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        return mysqli_fetch_all($result, MYSQLI_ASSOC);
    }

    public function findByName($name): array
    {
        $sql = "
        SELECT user_name, password, avatar
          FROM users
         WHERE user_name = ?
        ";
        $stmt = dbGetPrepareStmt($this->con, $sql, [$name]);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        return mysqli_fetch_all($result, MYSQLI_ASSOC);
    }

    public function add($email, $login, $passwordHash, $avatar)
    {
        $sql = '
        INSERT INTO users (email, user_name, password, avatar)
        VALUES (?, ?, ?, ?);
        ';
        mysqli_stmt_execute(dbGetPrepareStmt($this->con, $sql, [
            $email,
            $login,
            $passwordHash,
            $avatar
        ]));
    }
}
