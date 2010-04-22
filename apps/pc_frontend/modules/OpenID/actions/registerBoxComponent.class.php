<?php

class RegisterBoxComponent extends sfComponent
{
  public function execute($request)
  {
    $this->form = new opAuthLoginFormOpenID(new opAuthAdapterOpenID('OpenID'));

    unset($this->form['is_remember_me']);
  }
}
