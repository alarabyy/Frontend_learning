<?php
namespace API\Middlewares;

use \API\Core\Authenticator;
use \API\Core\Middleware;
use \API\Core\Response;

class Auth extends Middleware
{
  public function Verify()
  {
    return Authenticator::IsAuthenticated();
  }

  public function OnApprove()
  {
    // nothing
  }

  public function OnReject()
  {
    $res = new Response("no authenticated user found", false);
    $res->send();
    die();
  }
}