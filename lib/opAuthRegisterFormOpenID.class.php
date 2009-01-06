<?php

/**
 * opAuthRegisterFormOpenID represents a form to register by OpenID
 *
 * @package    OpenPNE
 * @subpackage form
 * @author     Kousuke Ebihara <ebihara@tejimaya.com>
 */
class opAuthRegisterFormOpenID extends opAuthRegisterForm
{
  public function doSave()
  {
    $this->getMember()->setIsActive(true);
    return $this->getMember()->save();
  }
}
