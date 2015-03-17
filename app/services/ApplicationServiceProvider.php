<?php
namespace App\Services;
use Illuminate\Support\ServiceProvider;

class ApplicationServiceProvider extends ServiceProvider
{
  public function register() {

    $this->app->bind('freshDesk', function()  {
        return new FreshdeskService();
    });
  }
}