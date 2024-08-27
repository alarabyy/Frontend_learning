<?php
namespace API\Core;

use \API\Core\Response;

class Controller
{
  public function Index() { }

  protected function response($data = null, $status = true)
  {
    $res = new Response($data, $status);
    $res->send();
  }
}