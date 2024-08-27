<?php
namespace DB\Models;

use DB\Database as Database;
use utils\Utils as Utils;

class Experience
{
  protected $experienceId;
  protected $tableName;
  protected $createdAt;
  protected $updatedAt;

  public function __construct(
    protected string $userId,
    protected string $companyName,
    protected string $title,
    protected string $description,
    protected string $joiningDate,
    protected string $leavingDate,
    bool $is_new = true,
    string $id = null,
    string $createdAt = null,
    string $updatedAt = null
  ) {
    $this->tableName = "experiences";

    if ($is_new) {
      $this->validateProperties();
      $this->createNewRecord();
    } else {
      $this->experienceId = $id;
      $this->createdAt = $createdAt;
      $this->updatedAt = $updatedAt;
    }
  }

  public static function getExperienceById(string $experienceId): ?Experience
  {
    $record = Database::Query("SELECT * FROM experiences WHERE id = '$experienceId' LIMIT 1")->fetch();

    if (empty($record)) {
      return null;
    }

    return new Experience(
      $record['user_id'],
      $record['company_name'],
      $record['title'],
      $record['description'],
      $record['joining_date'],
      $record['leaving_date'],
      false,
      $record["id"],
      $record["created_at"],
      $record["updated_at"]
    );
  }

  public static function getAllExperiencesByUserId(string $UserId): ?array
  {
    $records = Database::Query("SELECT * FROM experiences WHERE author_id = '$UserId' ")->fetchAll();

    if (empty($records)) {
      return null;
    }

    return $records;
  }

  protected function validateProperties(): void
  {
    Utils::validateProperty("userId", $this->userId, 36, 36);
    Utils::validateProperty("companyName", $this->companyName, 50, 2);
    Utils::validateProperty("title", $this->title, 50, 2);
    Utils::validateProperty("description", $this->description, 1024 * 1024, 1);
  }

  protected function createNewRecord(): void
  {
    $this->experienceId = Utils::generateUUID();
    $this->createdAt = date("Y-m-d H:i:s");
    $this->updatedAt = $this->createdAt;

    Database::Query("
            INSERT INTO $this->tableName 
            (id, user_id, company_name, title, description, joining_date, leaving_date, created_at, updated_at)
            VALUES
            ('$this->experienceId', '$this->userId', '$this->companyName', '$this->title',
            '$this->description', '$this->joiningDate', '$this->leavingDate',
            '$this->createdAt', '$this->updatedAt');
        ");
  }

  public function updateData(
    string $companyName = null,
    string $title = null,
    string $description = null,
    string $joiningDate = null,
    string $leavingDate = null
  ): void {
    $this->companyName = $companyName ?? $this->companyName;
    $this->title = $title ?? $this->title;
    $this->description = $description ?? $this->description;
    $this->joiningDate = $joiningDate ?? $this->joiningDate;
    $this->leavingDate = $leavingDate ?? $this->leavingDate;

    $this->validateProperties();

    $this->updatedAt = date("Y-m-d H:i:s");

    Database::Query("
            UPDATE $this->tableName
            SET company_name = '$this->companyName',
                title = '$this->title',
                description = '$this->description',
                joining_date = '$this->joiningDate',
                leaving_date = '$this->leavingDate',
                updated_at = '$this->updatedAt'
            WHERE id = '$this->experienceId';
        ");
  }
  public function getData(): array
  {
    return [
      "experienceId" => $this->experienceId,
      "userId" => $this->userId,
      "companyName" => $this->companyName,
      "title" => $this->title,
      "description" => $this->description,
      "joiningDate" => $this->joiningDate,
      "leavingDate" => $this->leavingDate,
      "createdAt" => $this->createdAt,
      "updatedAt" => $this->updatedAt
    ];
  }
  public function delete(): void
  {
    // also will delete any record on any table related with this record
    Database::Query("DELETE FROM '$this->tableName' WHERE id = '$this->experienceId'");
  }
}
