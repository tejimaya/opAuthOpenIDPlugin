<?php

/**
 * sfOpenPNEAuthForm_OpenID represents a form to login by OpenID
 *
 * @package    OpenPNE
 * @subpackage form
 * @author     Kousuke Ebihara <ebihara@tejimaya.com>
 */
class sfOpenPNEAuthForm_OpenID extends sfOpenPNEAuthForm
{
  public function configure()
  {
    $this->setWidget('openid_identifier', new sfWidgetFormInput());
    $this->setValidator('openid_identifier', new sfValidatorUrl(array('required' => false)));
    $this->widgetSchema->setLabel('openid_identifier', 'OpenID');

    $this->mergePostValidator(new sfValidatorCallback(array(
      'callback'  => array($this, 'handleRequest'),
    )));

    parent::configure();
  }

  public function handleRequest($validator, $value, $arguments = array())
  {
    sfOpenPNEApplicationConfiguration::registerZend();
    $consumer = new Zend_OpenID_Consumer();

    if (isset($_GET['openid_mode']))
    {
      if (!$consumer->verify($_GET, $id))
      {
        throw new sfValidatorError($validator, $consumer->getError());
      }

      $value['id'] = $id;

      return $value;
    }

    if (!$consumer->login($value['openid_identifier']))
    {
      throw new sfValidatorError($validator, $consumer->getError());
    }

    throw new LogicException('The process encountered an unknown failure.');
  }

  public function setForRegisterWidgets($member = null)
  {
    parent::setForRegisterWidgets($member);
    unset($this['openid_identifier']);
    $this->getValidatorSchema()->setPostValidator(new sfValidatorPass());
  }

  public function getAuthMode()
  {
    return 'OpenID';
  }
}
