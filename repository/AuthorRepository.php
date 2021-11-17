<?php
require_once('Db.php');
require_once('helpers.php');

class AuthorRepository extends Db
{
    public function findByPostId(string $postId): AuthorModel
    {
        $sql = "
        SELECT u.registration_date AS date, u.user_name, u.avatar, COUNT(p.id) AS posts_total, COUNT(s.id) AS subscribers_total
        FROM posts p
        JOIN users u ON p.user_id = u.id
        LEFT JOIN subscriptions s ON u.id = s.author_id
        WHERE u.id IN
             (SELECT p.user_id FROM posts p JOIN users u ON p.user_id = u.id WHERE p.id = ?)
        GROUP BY u.id;
        ";
        $stmt = dbGetPrepareStmt($this->con, $sql, [$postId]);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        $row = mysqli_fetch_all($result, MYSQLI_ASSOC);
        $row = array_shift($row);

        $date = $row['date'] ?? '';
        $userName = $row['user_name'] ?? '';
        $avatar = $row['avatar'] ?? '';
        $postsTotal = $row['posts_total'] ?? '';
        $subscribersTotal = $row['subscribers_total'] ?? '';

        return new AuthorModel($date, $userName, $avatar, $postsTotal, $subscribersTotal);
    }
}
