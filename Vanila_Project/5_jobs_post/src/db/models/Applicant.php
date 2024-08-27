<?php

namespace DB\Models;
use DB\Database as Database;
use utils\Utils as Utils;

class Applicant
{
  protected $applicationId;
  protected $tableName;
  protected $createdAt;
  protected $updatedAt;

  public function __construct(
    protected string $jobPostId,
    protected string $jobSeekerId,
    protected ?string $coverLetter = null,
    bool $is_new = true,
    string $id = null,
    string $createdAt = null,
    string $updatedAt = null
  ) {
    $this->tableName = "applicants";

    if ($is_new) {
      $this->validateProperties();
      $this->createNewRecord();
    } else {
      $this->applicationId = $id;
      $this->createdAt = $createdAt;
      $this->updatedAt = $updatedAt;
    }
  }

  public static function getApplicantById(string $applicationId): ?Applicant
  {
    $record = Database::Query("SELECT * FROM applicants WHERE id = '$applicationId' LIMIT 1")->fetch();

    if (empty($record)) {
      return null;
    }

    return new Applicant(
      $record['job_post_id'],
      $record['job_seeker_id'],
      $record['cover_letter'],
      false,
      $record["id"],
      $record["created_at"],
      $record["updated_at"]
    );
  }

  public static function getAllApplicantsByJobPostId(string $jobPostId): ?array
  {
    $records = Database::Query("SELECT * FROM applicants WHERE job_post_id = '$jobPostId' ")->fetchAll();

    if (empty($records)) {
      return null;
    }

    return $records;
  }

  protected function validateProperties(): void
  {
    Utils::validateProperty("jobPostId", $this->jobPostId, 36, 36);
    Utils::validateProperty("authorId", $this->jobSeekerId, 36, 36);
    Utils::validateProperty("content", $this->coverLetter, 1024 * 1024, 1);
  }

  protected function createNewRecord(): void
  {
    $this->applicationId = Utils::generateUUID();
    $this->createdAt = date("Y-m-d H:i:s");
    $this->updatedAt = $this->createdAt;

    Database::Query("
            INSERT INTO $this->tableName 
            (id, job_post_id, job_seeker_id, cover_letter, created_at, updated_at)
            VALUES
            ('$this->applicationId', '$this->jobPostId', '$this->jobSeekerId', '$this->coverLetter', '$this->createdAt', '$this->updatedAt');
        ");
  }

  public function getData(): array
  {
    return [
      "applicationId" => $this->applicationId,
      "jobPostId" => $this->jobPostId,
      "jobSeekerId" => $this->jobSeekerId,
      "coverLetter" => $this->coverLetter,
      "createdAt" => $this->createdAt,
      "updatedAt" => $this->updatedAt
    ];
  }

  public function delete(): void
  {
    // also will delete any record on any table related with this user
    Database::Query("DELETE FROM '$this->tableName' WHERE id = '$this->applicationId'");
  }
}
