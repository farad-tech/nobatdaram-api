<?php

namespace App\SMS;

class VerifyCode extends SMS {

  public function go()
  {

    $this->send(216013);

  }

}