<?php

namespace DB\Models;

require('db/Database.php');

use DB\Database as Database;

require('utils/utils.php');

use utils\Utils as Utils;

class Reply
{
  protected $replyId;
  protected $tableName;
  protected $createdAt;
  protected $updatedAt;

  public function __construct(
    protected string $commentId,
    protected string $authorId,
    protected string $content,
    bool $is_new = true, // for encapsulating object without creating new record in db
    string $id = null,  // dependency for $is_new
    string $createdAt = null, // dependency for $is_new
    string $updatedAt = null, // dependency for $is_new
  ) {
    $this->tableName = "replies";

    if ($is_new) {
      $this->validateProperties();
      $this->createNewRecord();
    } else {
      $this->replyId = $id;
      $this->createdAt = $createdAt;
      $this->updatedAt = $updatedAt;
    }
  }

  public static function getReplyById(string $replyId): ?Reply
  {
    $record = Database::Query("SELECT * FROM replies WHERE id = '$replyId' LIMIT 1")->fetch();

    if (empty($record)) {
      return null;
    }

    return new Reply(
      $record['comment_id'],
      $record['author_id'],
      $record['content'],
      false,
      $record["id"],
      $record["created_at"],
      $record["updated_at"]
    );
  }


  public static function getAllRepliesByCommentId(string $commentId): ?array
  {
    $records = database::query("SELECT * FROM replies WHERE post_id = '$commentId' ")->fetchAll();

    if (empty($records)) {
      return null;
    }

    return $records;
  }

  public static function getAllRepliesByUserId(string $userId): ?array
  {
    $records = database::query("SELECT * FROM replies WHERE author_id = '$userId' ")->fetchAll();

    if (empty($records)) {
      return null;
    }

    return $records;
  }

  protected function validateProperties(): void
  {
    Utils::validateProperty("commentId", $this->commentId, 36, 36);
    Utils::validateProperty("authorId", $this->authorId, 36, 36);
    Utils::validateProperty("content", $this->content, 1024 * 1024, 1);
  }

  protected function createNewRecord(): void
  {
    $this->replyId = Utils::generateUUID();
    $this->createdAt = date("Y-m-d H:i:s");
    $this->updatedAt = $this->createdAt;

    Database::Query("
            INSERT INTO $this->tableName 
            (id, comment_id, author_id, content, created_at, updated_at)
            VALUES
            ('$this->replyId', '$this->commentId', '$this->authorId', '$this->content', '$this->createdAt', '$this->updatedAt');
        ");
  }

  public function updateData(
    string $content = null
  ): void {
    $this->content = $content ?? $this->content;

    $this->validateProperties();

    $this->updatedAt = date("Y-m-d H:i:s");

    Database::Query("
            UPDATE $this->tableName
            SET content = '$this->content',
                updated_at = '$this->updatedAt'
            WHERE id = '$this->replyId';
        ");
  }

  public function getData(): array
  {
    return [
      "replyId" => $this->replyId,
      "commentId" => $this->commentId,
      "authorId" => $this->authorId,
      "content" => $this->content,
      "createdAt" => $this->createdAt,
      "updatedAt" => $this->updatedAt
    ];
  }

  public function delete(): void
  {
    // also will delete any record on any table related with this record
    Database::Query("DELETE FROM '$this->tableName' WHERE id = '$this->replyId'");
  }
}
