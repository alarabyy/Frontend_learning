<?php
namespace API\Middlewares;

use \API\Core\Authenticator;
use \API\Core\Middleware;
use \API\Core\Response;

class Guest extends Middleware
{
  public function Verify()
  {
    return !Authenticator::IsAuthenticated();
  }

  public function OnApprove()
  {
    // nothing
  }

  public function OnReject()
  {
    $res = new Response("user found logged in", false);
    $res->send();
    die();
  }
}