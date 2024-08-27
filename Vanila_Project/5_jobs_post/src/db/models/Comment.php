<?php
namespace DB\Models;

use DB\Database as Database;
use utils\Utils as Utils;

class Comment
{
  protected $commentId;
  protected $tableName;
  protected $createdAt;
  protected $updatedAt;

  public function __construct(
    protected string $postId,
    protected string $authorId,
    protected string $content,
    bool $is_new = true, // this for encapsulate obj without create new record in db
    string $id = null,  // dependency for $is_new
    string $createdAt = null, // dependency for $is_new
    string $updatedAt = null, // dependency for $is_new
  ) {
    $this->tableName = "comments";

    if ($is_new) {
      $this->validateProperties();
      $this->createNewRecord();
    } else {
      $this->commentId = $id;
      $this->createdAt = $createdAt;
      $this->updatedAt = $updatedAt;
    }
  }

  public static function getCommentById(string $commentId): ?Comment
  {
    $record = Database::Query("SELECT * FROM comments WHERE id = '$commentId' LIMIT 1")->fetch();

    if (empty($record)) {
      return null;
    }

    return new Comment(
      $record['post_id'],
      $record['author_id'],
      $record['content'],
      false,
      $record["id"],
      $record["created_at"],
      $record["updated_at"]
    );
  }

  public static function getAllCommentsByJobPostId(string $jobPostId): ?array
  {
    $records = Database::Query("SELECT * FROM comments WHERE post_id = '$jobPostId' ")->fetchAll();

    if (empty($records)) {
      return null;
    }

    return $records;
  }

  public static function getAllCommentsByUserId(string $UserId): ?array
  {
    $records = Database::Query("SELECT * FROM comments WHERE author_id = '$UserId' ")->fetchAll();

    if (empty($records)) {
      return null;
    }

    return $records;
  }

  public static function getCommentsNumByJobPostId(string $jobPostId): ?string
  {
    $num = Database::Query("SELECT COUNT(*) FROM comments WHERE post_id = '$jobPostId' ")->fetchColumn();

    if (empty($num)) {
      return null;
    }

    return $num;
  }


  protected function validateProperties(): void
  {
    Utils::validateProperty("postId", $this->postId, 36, 1);
    Utils::validateProperty("authorId", $this->authorId, 36, 1);
    Utils::validateProperty("content", $this->content, 1024 * 1024, 1);
  }

  protected function createNewRecord(): void
  {
    $this->commentId = Utils::generateUUID();
    $this->createdAt = date("Y-m-d H:i:s");
    $this->updatedAt = $this->createdAt;

    Database::Query("
            INSERT INTO $this->tableName 
            (id, post_id, author_id, content, created_at, updated_at)
            VALUES
            ('$this->commentId', '$this->postId', '$this->authorId', '$this->content', '$this->createdAt', '$this->updatedAt');
        ");
  }

  public function updateData(string $content): void
  {
    $this->content = $content ?? $this->content;

    $this->validateProperties();

    $this->updatedAt = date("Y-m-d H:i:s");

    Database::Query("
            UPDATE $this->tableName
            SET content = '$this->content',
                updated_at = '$this->updatedAt'
            WHERE id = '$this->commentId';
        ");
  }

  public function getData(): array
  {
    return [
      "commentId" => $this->commentId,
      "postId" => $this->postId,
      "author_id" => $this->authorId,
      "content" => $this->content,
      "createdAt" => $this->createdAt,
      "updatedAt" => $this->updatedAt
    ];
  }

  public function delete(): void
  {
    // also will delete any record on any table related with this user
    Database::Query("DELETE FROM '$this->tableName' WHERE id = '$this->commentId'");
  }
}
