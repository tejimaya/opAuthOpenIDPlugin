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
    $this->widgetSchema->setLabel('openid_identifier', 'OpenID');

    $this->mergePostValidator(new sfValidatorCallback(array(
      'callback'  => array($this, 'handleRequest'),
    )));

    parent::configure();
  }

  public function handleRequest($validator, $value, $arguments = array())
  {
    $this->registerJanRainOpenID();
    $consumer = new Auth_OpenID_Consumer(new Auth_OpenID_FileStore(sfConfig::get('sf_cache_dir')));
    $currentURL = sfContext::getInstance()->getRequest()->getUri();

    if (isset($_GET['openid_mode']))
    {
      $response = $consumer->complete($currentURL);

      if ($response->status === Auth_OpenID_CANCEL)
      {
        throw new sfValidatorError($validator, 'Verification cancelled.');
      }
      elseif ($response->status === Auth_OpenID_FAILURE)
      {
        throw new sfValidatorError($validator, 'Authentication failed: '.$response->message);
      }
      elseif ($response->status === Auth_OpenID_SUCCESS)
      {
        $value['id'] = $response->getDisplayIdentifier();
        return $value;
      }
    }

    $authRequest = $consumer->begin($value['openid_identifier']);
    if (!$authRequest)
    {
      throw new sfValidatorError($validator, 'Authentication error: not a valid OpenID.');
    }

    // for OpenID1
    if ($authRequest->shouldSendRedirect())
    {
      $toUrl = $authRequest->redirectURL($currentURL, $currentURL);
      if (Auth_OpenID::isFailure($toUrl))
      {
        throw new sfValidatorError($validator, 'Could not redirect to the server: '.$toUrl->message);
      }
      else
      {
        header('Location: '.$toUrl);
        exit;
      }
    }

    // for OpenID2
    $formHTML = $authRequest->htmlMarkup($currentURL, $currentURL);
    if (Auth_OpenID::isFailure($formHTML))
    {
      throw new sfValidatorError($validator, 'Could not redirect to the server: '.$formHTML->message);
    }

    // We got a valid HTML contains JavaScript to redirect to the OpenID provider's site.
    // This HTML must not include any contents from symfony, so this script will stop here.
    echo $formHTML;
    exit;
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

  public function registerJanRainOpenID()
  {
    $DS = DIRECTORY_SEPARATOR;
    $openidPath = sfConfig::get('sf_lib_dir').$DS.'vendor'.$DS.'php-openid'.$DS;  // ##PROJECT_LIB_DIR##/vendor/php-openid/
    set_include_path($openidPath.PATH_SEPARATOR.get_include_path());

    require_once 'Auth/OpenID/Consumer.php';
    require_once 'Auth/OpenID/FileStore.php';
    require_once 'Auth/OpenID/SReg.php';
  }
}
