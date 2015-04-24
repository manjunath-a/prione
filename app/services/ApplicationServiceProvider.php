<?php

namespace App\Services;

use Illuminate\Support\ServiceProvider;

class ApplicationServiceProvider extends ServiceProvider
{
  public function register()
  {
      // Utitlity Service for Application
    $this->app->bind('util', function () {
        return new UtilService();
    });
    // Include Ticket Validator Service
    $this->app->bind('ticketValidator', function () {
        return new TicketValidator();
    });
    // ReportService
    $this->app->bind('report', function () {
        return new ReportService();
    });
  }
}
