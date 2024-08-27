<?php

namespace DB\Models;

require('db/Database.php');

use DB\Database as Database;

require('utils/utils.php');

use utils\Utils as Utils;

class React
{
  protected $reactId;
  protected $tableName;
  protected $createdAt;

  public function __construct(
    protected string $userId,
    protected string $postId,
    protected string $reactType,
    bool $is_new = true,
    string $id = null,
    string $createdAt = null,
  ) {
    $this->tableName = "reactions";

    if ($is_new) {
      $this->validateProperties();
      $this->createNewRecord();
    } else {
      $this->reactId = $id;
      $this->createdAt = $createdAt;
    }
  }

  public static function getReactById(string $reactId): ?React
  {
    $record = Database::Query("SELECT * FROM reactions WHERE id = '$reactId' LIMIT 1")->fetch();

    if (empty($record)) {
      return null;
    }

    return new React(
      $record['user_id'],
      $record['post_id'],
      $record['react_type'],
      false,
      $record["id"],
      $record["created_at"]
    );
  }

  public static function getAllReactsByJobPostId(string $jobPostId): ?array
  {
    $records = Database::Query("SELECT * FROM reactions WHERE post_id = '$jobPostId' ")->fetchAll();

    if (empty($records)) {
      return null;
    }

    return $records;
  }

  public static function getReactsNumByJobPostId(string $jobPostId): ?string
  {
    $num = Database::Query("SELECT COUNT(*) FROM reactions WHERE post_id = '$jobPostId' ")->fetchColumn();

    if (empty($num)) {
      return null;
    }

    return $num;
  }

  protected function validateProperties(): void
  {
    Utils::validateProperty("userId", $this->userId, 36, 36);
    Utils::validateProperty("postId", $this->postId, 36, 36);
    Utils::validateProperty("reactType", $this->reactType, 20, 4);
  }

  protected function createNewRecord(): void
  {
    $this->reactId = Utils::generateUUID();
    $this->createdAt = date("Y-m-d H:i:s");

    Database::Query("
            INSERT INTO $this->tableName 
            (id, user_id, post_id, react_name, created_at)
            VALUES
            ('$this->reactId', '$this->userId', '$this->postId', '$this->reactType',
            '$this->createdAt');
        ");
  }

  public function getData(): array
  {
    return [
      "reactId" => $this->reactId,
      "userId" => $this->userId,
      "postId" => $this->postId,
      "reactType" => $this->reactType,
      "createdAt" => $this->createdAt
    ];
  }
}
