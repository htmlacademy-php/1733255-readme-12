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

    public function findByName($name): UserModel|null
    {
        $sql = "
        SELECT user_name, password, avatar
          FROM users
         WHERE user_name = ?
        ";
        $stmt = dbGetPrepareStmt($this->con, $sql, [$name]);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        $row = mysqli_fetch_all($result, MYSQLI_ASSOC);
        if (!empty($row)) {
            $row = array_shift($row);
            $login = $row['user_name'] ?? '';
            $email = $row['email'] ?? '';
            $pwd = $row['password'] ?? '';
            $avatar = $row['avatar'] ?? '';
            return new UserModel($login, $email, $pwd, $avatar);
        }
        return null;
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
