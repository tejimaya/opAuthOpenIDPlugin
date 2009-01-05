<?php

/**
 * opAuthOpenIDPlugin actions.
 *
 * @package    OpenPNE
 * @subpackage opAuthOpenIDPluginActions
 * @author     Kousuke Ebihara <ebihara@tejimaya.com>
 */
class opAuthOpenIDPluginActions extends sfActions
{
 /**
  * Executes index action
  *
  * @param sfRequest $request A request object
  */
  public function executeIndex(sfWebRequest $request)
  {
    $adapter = new opAuthAdapterOpenID('OpenID');
    $this->form = $adapter->getAuthConfigForm();
    if ($request->isMethod(sfWebRequest::POST))
    {
      $this->form->bind($request->getParameter('auth'.$adapter->getAuthModeName()));
      if ($this->form->isValid())
      {
        $this->form->save();
        $this->redirect('opAuthOpenIDPlugin/index');
      }
    }
  }
}
