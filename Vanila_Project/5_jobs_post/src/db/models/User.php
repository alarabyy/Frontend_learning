<?php

namespace DB\Models;

// require("db/Database.php");
use DB\Database as Database;
use utils\Utils as Utils;
use UnexpectedValueException;

class User
{
  protected $tableName;
  protected $userId;
  protected $createdAt;
  protected $updatedAt;

  public function __construct(
    protected string $Fname,
    protected string $Lname,
    protected string $email,
    protected string $address,
    protected string $pswd,
    protected bool $isRecruiter,
    protected string $ImagePath,
    protected string $phone,
    protected string $industry,
    protected string $title,
    protected string $currentCompany,
    protected string $about
  ) {
    $this->validateProperties();
    // $this->isRecruiter = $isRecruiter;
  }

  protected function validateProperties(): void
  {
    Utils::validateProperty("ImagePath", $this->ImagePath, 256, 1); // if no value insert '-'
    Utils::validateProperty("Fname", $this->Fname, 50, 2);
    Utils::validateProperty("Lname", $this->Lname, 50, 2);
    Utils::validateProperty("email", $this->email, 50, 2);
    Utils::validateProperty("address", $this->address, 100, 2);
    Utils::validateProperty("pswd", $this->pswd, 256, 1);
    Utils::validateProperty("phone", $this->phone, 25, 1);
    Utils::validateProperty("industry", $this->industry, 50, 1);
    Utils::validateProperty("title", $this->title, 50, 1);
    Utils::validateProperty("currentCompany", $this->currentCompany, 50, 0);
    Utils::validateProperty("about", $this->about, 1024 * 1024, 0);
  }


  protected function createNewRecord()
  {
    $this->userId = Utils::generateUUID();
    $this->createdAt = $this->updatedAt = date("Y-m-d H:i:s");

    $stmt = "INSERT INTO {$this->tableName} (id, image, first_name, last_name, email, address, phone, password, industry, current_company, title, about, is_recruiter, created_at, updated_at) VALUES(?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

    $params = [
      $this->userId,
      $this->ImagePath,
      $this->Fname,
      $this->Lname,
      $this->email,
      $this->address,
      $this->phone,
      $this->pswd,
      $this->industry,
      $this->currentCompany,
      $this->title,
      $this->about,
      $this->isRecruiter,
      $this->createdAt,
      $this->updatedAt
    ];
    Database::Query($stmt, $params);
  }

  public static function authenticateUser(string $email, string $password)
  {
    $result = Database::Query("SELECT * FROM users WHERE email = '$email' LIMIT 1")->fetch();

    if (empty($result)) {
      return "email not found";
    }

    if ($password === $result['password']) {
      return [
        "userId" => $result['id'],
        "industry" => $result['industry'],
        "isRecuirter" => $result['is_recruiter']
      ];
    }

    return "wrong password";
  }

  public function updateImagePath(string $newImagePath): void
  {
    Utils::validateProperty("newImagePath", $newImagePath, 256, 1); // if no value insert '-'
    $this->updatedAt = date("Y-m-d H:i:s");
    Database::Query("
            UPDATE $this->tableName SET image = '$newImagePath', updated_at = '$this->updatedAt' WHERE id = '$this->userId';
        ");
    $this->ImagePath = $newImagePath;
  }

  public function updatePassword(string $newPswd): void
  {
    Utils::validateProperty("newPswd", $newPswd, 256, 6);
    $this->updatedAt = date("Y-m-d H:i:s");
    Database::Query("
            UPDATE $this->tableName SET image = '$newPswd', updated_at = '$this->updatedAt' WHERE id = '$this->userId';
        ");
    $this->pswd = $newPswd;
  }

  public function updateData(
    string $Fname = null,
    string $Lname = null,
    string $email = null,
    string $address = null,
    string $phone = null,
    string $industry = null,
    string $title = null,
    string $currentCompany = null,
    string $about = null,
  ) {
    $Fname = $Fname ?? $this->Fname;
    $Lname = $Lname ?? $this->Lname;
    $email = $email ?? $this->email;
    $address = $address ?? $this->address;
    $phone = $phone ?? $this->phone;
    $industry = $industry ?? $this->industry;
    $title = $title ?? $this->title;
    $currentCompany = $currentCompany ?? $this->currentCompany;
    $about = $about ?? $this->about;

    $this->validateProperties();

    $this->updatedAt = date("Y-m-d H:i:s");
    Database::Query("
        UPDATE $this->tableName SET first_name = '$Fname', last_name = '$Lname',
        email = '$email', address, '$address', phone = '$phone', industry = '$industry', title = '$title',
        current_company = '$currentCompany', about = '$about', updated_at = '$this->updatedAt'
        WHERE id = '$this->userId';
        ");

    $this->Fname = $Fname;
    $this->Lname = $Lname;
    $this->email = $email;
    $this->address = $address;
    $this->phone = $phone;
    $this->industry = $industry;
    $this->title = $title;
    $this->currentCompany = $currentCompany;
    $this->about = $about;
  }

  public function getData(): array
  {
    return [
      "userId" => $this->userId,
      "imagePath" => $this->ImagePath,
      "Fame" => $this->Fname,
      "Lname" => $this->Lname,
      "email" => $this->email,
      "address" => $this->address,
      "password" => $this->pswd,
      "phone" => $this->phone,
      "industry" => $this->industry,
      "title" => $this->title,
      "about" => $this->about,
      "currentCompany" => $this->currentCompany,
      "createdAt" => $this->createdAt,
      "updatedAt" => $this->updatedAt
    ];
  }

  public function delete(): void
  {
    // also will delete any record on any table related with this record
    Database::Query("DELETE FROM '$this->tableName' WHERE id = '$this->userId'");
  }

  public static function checkEmailExist($email, $isRecruiter): bool
  {
    $table = $isRecruiter ? "recruiters" : "job_seekers";
    $stmt = "SELECT * FROM {$table} WHERE email=?";

    $user = Database::Query($stmt, [$email])->fetch();
    return $user != null;
  }
}
