<?php
namespace App\Services;

class FreshdeskService {

    private $freshdeskUrl = 'freshdesk.com';

    private $token = '';

    private $password = 'X';

    public function createTicket($data) {


      $fdData = array (
            "helpdesk_ticket" => array(
                "description" => $data['description'],
                "subject" => $data['subject'],
                "email" => $data['email'],
                "priority" => $data['priority'],
                "status" => $data['status'],
                ),
            "cc_emails" => \Config::get('mail.cc_email'),
      );
      // Check for Custom Fields
      if(isset($data['custom_field'])) {
        $fdData['helpdesk_ticket']['custom_field']= $data['custom_field'];
      }

      $requestType = '/helpdesk/tickets.json';
      $fresdeskData =  $this->makeRequest($requestType, $fdData);
      return $fresdeskData->helpdesk_ticket;

    }

    public function updateTicket($data) {

      $data = array(
          "helpdesk_ticket" => array(
              "priority" => $data['priority'],
              "status" => $data['status'],
              "custom_field" => $data['custom_field']
          )
      );
      $requestType = '/helpdesk/tickets/'.$data['freshdesk_ticket_id'].'.json';
      return $this->makeRequest($data);
    }

    public function getAllCustomFields() {
      $requestType = '/ticket_fields.json';
      try {
          $cusotmFields = array();
          $defaultFields = array();

          $ticketFieldArray = $this->makeRequest($requestType);

          foreach($ticketFieldArray as $key => $field) {
              switch($field->ticket_field->field_type) {
                case 'custom_dropdown' :
                case 'custom_text' :
                case 'custom_text' :
                if($field->ticket_field->name == 'no_of_images_203188') continue;
                      $cusotmFields[] = $field->ticket_field->name;
                     break;
                case 'default_source':
                case 'default_status':
                case 'default_group':
                case 'default_priority':
                case 'default_agent':
                case 'default_description':
                     $defaultFields[] = $field->ticket_field->name;
                     break;
              }
          }

          $ticketFields = array('custom' =>$cusotmFields, 'default' =>$defaultFields);

      } catch (Exception $exe) {
        throw new Exception("Unable to commuincate with FreshDesk");
      }
      return $ticketFields;

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

      return $ticketArray;
    }
}