<?php
namespace App\Services;
class FreshdeskService {

    private $freshdeskUrl = 'freshdesk.com';

    private $token = '';

    private $password = 'X';

    public function createTicket($data) {

      $this->freshdeskUrl = \Config::get('freshdesk.url');
      // Fresh Desk password
      $this->token = \Config::get('freshdesk.token');
      // Fresh Desk password
      $this->password = \Config::get('freshdesk.password');


      $json_body = json_encode($data, JSON_FORCE_OBJECT | JSON_PRETTY_PRINT);
      $header[] = "Content-type: application/json";
      $connection = curl_init($this->freshdeskUrl ."/helpdesk/tickets.json");
      curl_setopt($connection, CURLOPT_RETURNTRANSFER, true);
      curl_setopt($connection, CURLOPT_HTTPHEADER, $header);
      curl_setopt($connection, CURLOPT_HEADER, false);
      curl_setopt($connection, CURLOPT_USERPWD, $this->token.":".$this->password);
      curl_setopt($connection, CURLOPT_POST, true);
      curl_setopt($connection, CURLOPT_POSTFIELDS, $json_body);
      curl_setopt($connection, CURLOPT_VERBOSE, 1);
      $ticketResponse = curl_exec($connection);
      $ticketArray = json_decode(  $ticketResponse );
      return $ticketArray->helpdesk_ticket;
    }
}