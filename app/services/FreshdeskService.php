<?php
namespace App\Services;

class FreshdeskService {

    private $freshdeskUrl = 'freshdesk.com';

    private $token = '';

    private $password = 'X';

    public function createTicket($data) {


      $data = array (
            "helpdesk_ticket" => array(
                "description" => $data['description'],
                "subject" => $data['subject'],
                "email" => $data['email'],
                "priority" => $data['priority'],
                "status" => $data['status']
                ),
            "cc_emails" => \Config::get('mail.cc_email'),
        );
      $requestType = '/helpdesk/tickets.json';
      return $this->makeRequest($requestType, $data);

    }

    public function updateTicket($data) {

      // Getting List of custom Fields from freshdesk
      $customFields = \Config::get('freshdesk.custom_fields');
      foreach($customFields as $filed => $customField) {
        if(in_array($filed, $data )) {
          $fields[$customField] = $data[$field];
        }
      }

      $data = array(
          "helpdesk_ticket" => array(
              "priority" => $data['priority'],
              "status" => $data['status']
              // "custom_field" => $custom_field
          )
      );
      $requestType = '/helpdesk/tickets/'.$data['freshdesk_ticket_id'].'.json';
        return $this->makeRequest($data);
    }

    public function getAllCustomFields() {
      $requestType = '/ticket_fields.json';

      $ticketFieldArray = $this->makeRequest($requestType);
      foreach($ticketFieldArray as $key => $field) {
        switch($field->field_type) {

        }
        if($field->field_type === '') {

        }
      }
      return $customFields;

    }

    public function makeRequest($requestType , $data = NULL) {
      $this->freshdeskUrl = \Config::get('freshdesk.url');
      // Fresh Desk password
      $this->token = \Config::get('freshdesk.token');
      // Fresh Desk password
      $this->password = \Config::get('freshdesk.password');


      $header[] = "Content-type: application/json";
      $connection = curl_init($this->freshdeskUrl .$requestType);

      if(is_array($data)) {
          $json_body = json_encode($data, JSON_FORCE_OBJECT | JSON_PRETTY_PRINT);
          curl_setopt($connection, CURLOPT_POSTFIELDS, $json_body);
          curl_setopt($connection, CURLOPT_POST, true);
      }
      curl_setopt($connection, CURLOPT_RETURNTRANSFER, true);
      curl_setopt($connection, CURLOPT_HTTPHEADER, $header);
      curl_setopt($connection, CURLOPT_HEADER, false);
      curl_setopt($connection, CURLOPT_USERPWD, $this->token.":".$this->password);
      curl_setopt($connection, CURLOPT_VERBOSE, 1);
      $ticketResponse = curl_exec($connection);
      $ticketArray = json_decode(  $ticketResponse );

      return $ticketArray->helpdesk_ticket;
    }
}