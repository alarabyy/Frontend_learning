<?php
namespace API\Core;

class Response
{
  public $status;
  public $data;

  public function __construct($data, $status = true)
  {
    $this->data = $data;
    $this->status = $status;
  }

  public function Send()
  {
    echo json_encode($this);
  }
}