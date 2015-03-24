<?php
namespace App\Services;
class UtilService {

    public function arrayToJQString($dataArray, $name = 'username', $key = 'id', $rules='all') {
      $jqString = '0:select';
      if($dataArray) {
          foreach($dataArray as $value) {
            if($rules !='all' ) {
              if(isset($rules['only']) && is_array($rules)) {
                if(in_array($value->$name, $rules['only'])) {
                  $jqString .= ";".$value->$key.":".$value->$name;
                }
              }
              if(isset($rules['skip']) && is_array($rules)) {
                if(in_array($value->$name, $rules['skip'])) {
                  continue;
                }
              }
            } else {
              $jqString .= ";".$value->$key.":".$value->$name;
            }
          }
      }
      return $jqString;
    }
}