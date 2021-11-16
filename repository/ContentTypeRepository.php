<?php
require_once ('Db.php');
require_once ('models/ContentTypeModel.php');

class ContentTypeRepository extends Db
{
    public function all(): array
    {
        $contentTypes = [];
        $sql = '
        SELECT type, title, id
          FROM content_types;
        ';
        $resultContentTypes = mysqli_query($this->con, $sql);
        $rows = mysqli_fetch_all($resultContentTypes, MYSQLI_ASSOC);

        foreach ($rows as $row) {
            array_push($contentTypes, new ContentTypeModel($row['id'], $row['type'], $row['title']));
        }

        return $contentTypes;
    }
}
