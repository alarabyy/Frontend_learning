<?php

namespace DB\Models;

use DB\Database;
use utils\Utils as Utils;

class JobPost
{
  protected $jobPostId;
  protected $tableName;
  protected $createdAt;
  protected $updatedAt;

  public function __construct(
    protected string $authorId,
    protected string $imagePath,
    protected string $position,
    protected string $industry,
    protected string $location,
    protected float $salary,
    protected string $description,
    bool $is_new = true, // this for encapsulate obj without create new record in db
    string $id = null,  // dependency for $is_new
    string $createdAt = null, // dependency for $is_new
    string $updatedAt = null, // dependency for $is_new
  ) {
    $this->tableName = "job_posts";

    if ($is_new) {
      $this->validateProperties();
      $this->createNewRecord();
    } else {
      $this->jobPostId = $id;
      $this->createdAt = $createdAt;
      $this->updatedAt = $updatedAt;
    }
  }

  public static function getJobPostById(string $jobPostId): ?JobPost
  {
    $record = Database::Query("SELECT * FROM job_posts WHERE id = '$jobPostId' LIMIT 1")->fetch();

    if (empty($record)) {
      return null;
    }

    return new JobPost(
      $record['author_id'],
      $record['image'],
      $record['position'],
      $record['industry'],
      $record['location'],
      (float)$record['salary'],
      $record['description'],
      false,
      $record["id"],
      $record["created_at"],
      $record["updated_at"]
    );
  }

  public static function getAllJobPostsByUserId(string $UserId): ?array
  {
    $records = Database::Query("SELECT * FROM job_posts WHERE author_id = '$UserId' ")->fetchAll();

    if (empty($records)) {
      return null;
    }

    return $records;
  }

  public static function GetLatestPostsByIndustry(string $industry, int $limit): ?array
  {
    $records = Database::Query("SELECT * FROM job_posts WHERE industry = ? ORDER BY updated_at DESC", [$industry])->fetchAll();

    if (empty($records)) {
      return null;
    }

    return $records;
  }

  protected function validateProperties(): void
  {
    Utils::validateProperty("authorId", $this->authorId, 36, 1);
    Utils::validateProperty("imagePath", $this->imagePath, 256, 1);
    Utils::validateProperty("position", $this->position, 50, 2);
    Utils::validateProperty("industry", $this->industry, 50, 2);
    Utils::validateProperty("location", $this->position, 50, 2);
    Utils::validateProperty("description", $this->description, 1024 * 1024, 1);
  }

  protected function createNewRecord(): void
  {
    $this->jobPostId = Utils::generateUUID();
    $this->createdAt = date("Y-m-d H:i:s");
    $this->updatedAt = $this->createdAt;

    Database::Query("
            INSERT INTO $this->tableName 
            (id, author_id, image, position, industry, location, salary, description, created_at, updated_at)
            VALUES
            ('$this->jobPostId', '$this->authorId', '$this->imagePath', '$this->position', '$this->industry', '$this->location',
            '$this->salary', '$this->description', '$this->createdAt', '$this->updatedAt');
        ");
  }

  public function updateData(
    string $imagePath = null,
    string $position = null,
    string $industry = null,
    string $location = null,
    float $salary = null,
    string $description = null
  ): void {
    $this->imagePath = $imagePath ?? $this->imagePath;
    $this->position = $position ?? $this->position;
    $this->industry = $industry ?? $this->industry;
    $this->location = $location ?? $this->location;
    $this->salary = $salary ?? $this->salary;
    $this->description = $description ?? $this->description;

    $this->validateProperties();

    $this->updatedAt = date("Y-m-d H:i:s");

    Database::Query("
            UPDATE $this->tableName
            SET image = '$this->imagePath',
                position = '$this->position',
                industry = '$this->industry',
                location = '$this->location',
                salary = '$this->salary',
                description = '$this->description',
                updated_at = '$this->updatedAt'
            WHERE id = '$this->jobPostId';
        ");
  }

  public function getData(): array
  {
    return [
      "jobPostId" => $this->jobPostId,
      "authorId" => $this->authorId,
      "imagePath" => $this->imagePath,
      "position" => $this->position,
      "industry" => $this->industry,
      "salary" => $this->salary,
      "description" => $this->description,
      "createdAt" => $this->createdAt,
      "updatedAt" => $this->updatedAt
    ];
  }

  public function delete(): void
  {
    // also will delete any record on any table related with this record
    Database::Query("DELETE FROM '$this->tableName' WHERE id = '$this->jobPostId'");
  }
}
