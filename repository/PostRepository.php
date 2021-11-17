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
        $rows = mysqli_fetch_all($result, MYSQLI_ASSOC);

        $posts = [];

        foreach ($rows as $row) {
            array_push($posts, new PostModel(...$this->constructPostModel($row)));
        }

        return $posts;
    }

    public function findByPostId(string $postId): ?PostModel
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
        $row = mysqli_fetch_all($result, MYSQLI_ASSOC);

        if (empty($row)) {
            return null;
        }

        $row = array_shift($row);

        return new PostModel(...$this->constructPostModel($row));
    }

    public function save(PostModel $post): int|string
    {
        $sql = '
        INSERT INTO posts (title, content, author, img, video, reference, user_id, content_type_id)
        VALUES (?, ?, ?, ?, ?, ?, ?, ?);
        ';
        mysqli_stmt_execute(dbGetPrepareStmt($this->con, $sql, [
            $post->getTitle(),
            $post->getContent(),
            $post->getAuthor(),
            $post->getImg(),
            $post->getVideo(),
            $post->getReference(),
            $post->getUserId(),
            $post->getContentTypeId(),
            ]));

        return mysqli_insert_id($this->con); // Сохраняем ID нового поста
    }

    public function constructPostModel(array $row): array
    {
        $id = $row['id'] ?? '';
        $title = $row['title'] ?? '';
        $content = $row['content'] ?? '';
        $author = $row['author'] ?? '';
        $img = $row['img'] ?? '';
        $video = $row['video'] ?? '';
        $reference = $row['reference'] ?? '';
        $userId = $row['user_id'] ?? '';
        $userName = $row['user_name'] ?? '';
        $contentTypeId = $row['content_type_id'] ?? '';
        $publicationDate = $row['publication_date'] ?? '';
        $views = $row['views'] ?? '';
        $type = $row['type'] ?? '';
        $likes = $row['likes'] ?? '';
        $commentsTotal = $row['comments_total'] ?? '';
        $avatar = $row['avatar'] ?? '';
        $imageClass = $row['image_class'] ?? '';

        return [
            $id,
            $title,
            $content,
            $author,
            $img,
            $video,
            $reference,
            $userId,
            $userName,
            $contentTypeId,
            $publicationDate,
            $views,
            $type,
            $likes,
            $commentsTotal,
            $avatar,
            $imageClass
        ];
    }
}
