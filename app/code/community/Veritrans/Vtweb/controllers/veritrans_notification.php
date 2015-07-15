<?php

// Wraper for veritrans weblink type payment response

class VeritransNotification
{
  private $mStatus; 
  private $orderId;
  private $TOKEN_MERCHANT;

  public function __get($property) 
  {
    if (property_exists($this, $property))
    {
      return $this->$property;
    }
  }

  public function __set($property, $value) 
  {
    if (property_exists($this, $property)) 
    {
      $this->$property = $value;
    }

    return $this;
  }

  function __construct($params = null) 
  {
    if(is_null($params)) {
      $params = json_decode(file_get_contents('php://input')); 
    }

    foreach($params as $key => $value){
      $this->$key = $value;
    }
  }

}

?>
