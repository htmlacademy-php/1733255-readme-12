<?php
require_once('Db.php');
require_once('helpers.php');

class PostRepository extends Db
{
    public function findByTypeId(string|bool $contentTypeId): array
    {
        $sql = "
        SELECT p.*, u.user_name, u.avatar, ct.type, ct.image_class
          FROM posts p
          JOIN users u ON p.user_id = u.id
          JOIN content_types ct ON p.content_type_id = ct.id
         WHERE IF (?, p.content_type_id = ?, true)
         ORDER BY views DESC
         LIMIT 6;
        ";
        $stmt = dbGetPrepareStmt($this->con, $sql, [$contentTypeId, $contentTypeId]);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        return mysqli_fetch_all($result, MYSQLI_ASSOC);
    }

    public function findByPostId(string $postId): array
    {
        $sql = "
        SELECT p.*, ct.type, COUNT(l.id) AS likes, COUNT(c.id) AS comments_total, u.avatar
          FROM posts p
          JOIN content_types ct ON p.content_type_id = ct.id
          LEFT JOIN likes l ON p.id = l.post_id
          LEFT JOIN comments c ON p.id = c.post_id
          LEFT JOIN users u ON p.user_id = u.id
         WHERE p.id = ?
         GROUP BY p.id;
        ";
        $stmt = dbGetPrepareStmt($this->con, $sql, [$postId]);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        return mysqli_fetch_all($result, MYSQLI_ASSOC);
    }

    public function add(array $postData): int|string
    {
        $sql = '
        INSERT INTO posts (title, content, author, img, video, reference, user_id, content_type_id)
        VALUES (?, ?, ?, ?, ?, ?, ?, ?);
        ';
        mysqli_stmt_execute(dbGetPrepareStmt($this->con, $sql, $postData));

        return mysqli_insert_id($this->con); // Сохраняем ID нового поста
    }
}
