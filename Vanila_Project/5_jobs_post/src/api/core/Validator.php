<?php
namespace API\Core;

class Validator
{
  private const REQUIRED = 'required';
  private const MIN = 'min';
  private const MAX = 'max';

  private $errors = [];

  // validation methods
  public function ValidateEmail($input, $restrictions = null)
  {
    $fieldName = 'Email';

    if($this->CheckRequired($input, $restrictions, $fieldName)) {
      return;
    }

    if($this->CheckString($input, $fieldName)) {
      return;
    }

    $this->CheckFilter($input, FILTER_VALIDATE_EMAIL, $fieldName);
  }
  public function ValidateText($input, $fieldName, $restrictions = null)
  {
    if($this->CheckRequired($input, $restrictions, $fieldName)) {
      return;
    }

    $this->CheckMinMax($input, $restrictions, $fieldName);
  }
  public function ValidateBool($input, $fieldName, $restrictions = null)
  {

  }
  public function ValidateChoice($input, $fieldName, $choices, $restrictions = null)
  {
    // validate
  }

  // check restrictions
  private function CheckRequired($input, $restrictions, $fieldName)
  {
    if($restrictions == null) return;

    if(!in_array(self::REQUIRED, $restrictions)) {
      return false;
    }
    if($input != null || $input !== '') {
      return false;
    }

    $this->errors[] = "{$fieldName} is required";
    return true;
  }
  private function CheckString($input, $fieldName)
  {
    if (is_string($input))
    {
      return false;
    }
    
    $this->errors[] = "{$fieldName} input is not a text";
    return true;
  }
  private function CheckFilter($input, $filter, $fieldName)
  {
    if(filter_var($input, $filter)) {
      return;
    }
    $this->errors[] = "{$fieldName} input is not in correct format";
  }
  private function CheckMinMax($input, $restrictions, $fieldName)
  {
    if($restrictions == null) return;
    
    foreach($restrictions as $item) {
      if(!str_contains($item, ':')) {
        continue;
      }

      $arr = explode(':', $item);
      $value = intval($arr[1]);

      // check min
      if($arr[0] == self::MIN && strlen($input) < $value) {
        $this->errors[] = "{$fieldName} is less than {$value}";
        continue;
      }

      // check for max
      if($arr[0] == self::MAX && strlen($input) > $value) {
        $this->errors[] = "{$fieldName} is more than {$value}";
      }
    }
  }
  // helpers
  public function HasErrors()
  {
    return count($this->errors) > 0;
  }
  public function GetErrors()
  {
    return $this->errors;
  }
  public function AddError($error)
  {
    $errors[] = $error;
  }
}