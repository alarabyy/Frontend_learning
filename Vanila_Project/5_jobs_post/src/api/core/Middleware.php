<?php
namespace API\Core;

class Middleware
{
  protected function Verify() {}
  protected function OnApprove() {}
  protected function OnReject() {}

  public static function Resolve($key)
  {
    $middlewareClassName = '\API\Middlewares\\' . $key;
      $middleware = new $middlewareClassName;
      if(!$middleware->Verify())
      {
        $middleware->OnReject();
        return;
      }
      $middleware->OnApprove();
  }
}