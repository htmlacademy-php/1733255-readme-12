<?php
require_once('Db.php');
require_once('helpers.php');

class TagsRepository extends Db
{
    public function save($tags, $newPostId)
    {
        $inserts = prepareSqlInserts('(?),', $tags);

        $sql = '
            INSERT IGNORE INTO hashtags (hashtag)
            VALUES ' . $inserts;
        mysqli_stmt_execute(dbGetPrepareStmt($this->con, $sql, [...$tags]));

        $updatedTags = $this->findUpdatedTagsForPost($tags);

        $tagIds = [];
        foreach ($updatedTags as $tag) {
            array_push($tagIds, $tag['id']);
        }

        $postTagsIds = [];
        foreach ($tagIds as $tagId) {
            array_push($postTagsIds, $newPostId);
            array_push($postTagsIds, $tagId);
        }

        $this->addPostConnection($tagIds, $postTagsIds);
    }

    public function addPostConnection(array $tagIds, array $postTagsIds)
    {
        $insertTagsPostsValues = prepareSqlInserts('(? , ?),', $tagIds);

        $sqlTagsPostsCon = '
            INSERT INTO posts_hashtags (post_id, hashtag_id)
            VALUES ' . $insertTagsPostsValues;
        mysqli_stmt_execute(dbGetPrepareStmt($this->con, $sqlTagsPostsCon, [...$postTagsIds]));
    }

    public function findUpdatedTagsForPost(array $tags): array
    {
        $selects = prepareSqlInserts('?,', $tags);

        $sql = '
            SELECT id, hashtag
              FROM hashtags
             WHERE hashtag IN (' . $selects . ')';
        $stmt = dbGetPrepareStmt($this->con, $sql, [...$tags]);
        mysqli_stmt_execute($stmt);
        $resultNewTags = mysqli_stmt_get_result($stmt);
        return mysqli_fetch_all($resultNewTags, MYSQLI_ASSOC);
    }

    public function findByPostId(string $postId): array
    {
        $sql = "
        SELECT h.hashtag
          FROM hashtags h
          JOIN posts_hashtags ph ON h.id = ph.hashtag_id
         WHERE ph.post_id = ?;
        ";
        $stmt = dbGetPrepareStmt($this->con, $sql, [$postId]);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        $rows = mysqli_fetch_all($result, MYSQLI_ASSOC);

        $tags = [];

        foreach ($rows as $row) {
            $tag = $row['hashtag'] ?? '';
            array_push($tags, new TagsModel($tag));
        }

        return $tags;
    }
}
