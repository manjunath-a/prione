<?php
namespace App\Services;
use Illuminate\Support\ServiceProvider;

class ApplicationServiceProvider extends ServiceProvider
{
  public function register() {

    $this->app->bind('util', function()  {
        return new UtilService();
    });
    $this->app->bind('ticketValidator', function()  {
        return new TicketValidator( );
    });
  }

}