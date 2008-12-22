<?php

/**
 * sfOpenPNEAuthContainer_OpenID will handle authentication for OpenPNE by OpenID
 *
 * @package    OpenPNE
 * @subpackage user
 * @author     Kousuke Ebihara <ebihara@tejimaya.com>
 */
class sfOpenPNEAuthContainer_OpenID extends sfOpenPNEAuthContainer
{
  protected
    $authModuleName = 'OpenID';

  public function fetchData($form)
  {
    $id = $form->getValue('id');

    $memberConfig = MemberConfigPeer::retrieveByNameAndValue('openid', $id);
    if ($memberConfig)
    {
      $member = $memberConfig->getMember();
    }
    else
    {
      $member = MemberPeer::createPre();
      $member->setConfig('openid', $id);
    }

    return $member->getId();
  }

  public function registerData($memberId, $form)
  {
    $member = MemberPeer::retrieveByPk($memberId);
    if (!$member)
    {
      return false;
    }

    $member->setIsActive(true);
    return $member->save();
  }

  public function isRegisterBegin($member_id = null)
  {
    opActivateBehavior::disable();
    $member = MemberPeer::retrieveByPk((int)$member_id);
    opActivateBehavior::enable();

    if (!$member || $member->getIsActive())
    {
      return false;
    }

    return true;
  }

  public function isRegisterFinish($member_id = null)
  {
    return false;
  }
}
