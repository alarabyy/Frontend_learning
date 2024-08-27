<?php
namespace DB\Models;

use DB\Database as Database;
use utils\Utils as Utils;

class Skill
{
  protected $skillId;
  protected $tableName;
  protected $createdAt;
  protected $updatedAt;

  public function __construct(
    protected string $userId,
    protected string $skill,
    bool $is_new = true,
    string $id = null,
    string $createdAt = null,
    string $updatedAt = null
  ) {
    $this->tableName = "skills";

    if ($is_new) {
      $this->validateProperties();
      $this->createNewRecord();
    } else {
      $this->skillId = $id;
      $this->createdAt = $createdAt;
      $this->updatedAt = $updatedAt;
    }
  }

  public static function getSkillById(string $skillId): ?Skill
  {
    $record = Database::Query("SELECT * FROM skills WHERE id = '$skillId' LIMIT 1")->fetch();

    if (empty($record)) {
      return null;
    }

    return new Skill(
      $record['user_id'],
      $record['skill'],
      false,
      $record["id"],
      $record["created_at"],
      $record["updated_at"]
    );
  }

  public static function getAllSkillsByUserId(string $userId): ?array
  {
    $records = Database::Query("SELECT * FROM skills WHERE user_id = '$userId'")->fetchAll();

    if (empty($records)) {
      return null;
    }

    return $records;
  }

  protected function validateProperties(): void
  {
    Utils::validateProperty("skill", $this->skill, 50, 2);
    Utils::validateProperty("userId", $this->userId, 36, 36);
  }

  protected function createNewRecord(): void
  {
    $this->skillId = Utils::generateUUID();
    $this->createdAt = date("Y-m-d H:i:s");
    $this->updatedAt = $this->createdAt;

    Database::Query("
            INSERT INTO $this->tableName 
            (id, user_id, skill, created_at, updated_at)
            VALUES
            ('$this->skillId', '$this->userId', '$this->skill', '$this->createdAt', '$this->updatedAt');
        ");
  }

  public function updateData(string $skill): void
  {
    $this->skill = $skill;
  }

  public function getData(): array
  {
    return [
      "skillId" => $this->skillId,
      "userId" => $this->userId,
      "skill" => $this->skill,
      "createdAt" => $this->createdAt,
      "updatedAt" => $this->updatedAt
    ];
  }

  public function delete(): void
  {
    // also will delete any record on any table related with this record
    Database::Query("DELETE FROM '$this->tableName' WHERE id = '$this->skillId'");
  }
}
