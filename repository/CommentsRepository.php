<?php
require_once('Db.php');
require_once('helpers.php');

class CommentsRepository extends Db
{
    public function findByPostId(string $postId): array
    {
        $sql = "
        SELECT c.content, c.publication_date AS date, u.user_name AS author, u.avatar
          FROM comments c
          JOIN posts p ON c.post_id = p.id
          JOIN users u ON c.author_id = u.id
         WHERE c.post_id = ?;
        ";
        $stmt = dbGetPrepareStmt($this->con, $sql, [$postId]);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        return mysqli_fetch_all($result, MYSQLI_ASSOC);
    }
}
