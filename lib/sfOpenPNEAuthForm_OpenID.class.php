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
    $this->setValidator('openid_identifier', new sfValidatorString(array('required' => false)));
    $this->setValidator('openid', new sfValidatorString(array('required' => false)));
    $this->widgetSchema->setLabel('openid_identifier', 'OpenID');

    $this->mergePostValidator(new sfValidatorOr(array(
      new opAuthValidatorMemberConfig(array('config_name' => 'openid')),
      new sfValidatorCallback(array(
        'callback' => array($this, 'validateIdentifier'),
        'arguments' => array(
          'realm' => $this->getAuthAdapter()->getCurrentUrl(),
          'return_to' => $this->getAuthAdapter()->getCurrentUrl(),
        ),
      ))
    )));

    parent::configure();
  }

  public function validateIdentifier($validator, $values, $arguments = array())
  {
    $authRequest = $this->getAuthAdapter()->getConsumer()->begin($values['openid_identifier']);
    if (!$authRequest)
    {
      throw new sfValidatorError($validator, 'Authentication error: not a valid OpenID.');
    }

    // for OpenID1
    if ($authRequest->shouldSendRedirect())
    {
      $values['redirect_url'] = $authRequest->redirectURL($arguments['realm'], $arguments['return_to']);
      if (Auth_OpenID::isFailure($values['redirect_url']))
      {
        throw new sfValidatorError($validator, 'Could not redirect to the server: '.$values['redirect_url']->message);
      }
    }
    // for OpenID2
    else
    {
      $values['redirect_html'] = $authRequest->htmlMarkup($arguments['realm'], $arguments['return_to']);
      if (Auth_OpenID::isFailure($values['redirect_html']))
      {
        throw new sfValidatorError($validator, 'Could not redirect to the server: '.$values['redirect_html']->message);
      }
    }

    return $values;
  }

  public function getRedirectHtml()
  {
    return $this->getValue('redirect_html');
  }

  public function getRedirectUrl()
  {
    return $this->getValue('redirect_url');
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
