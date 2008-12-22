<?php

/**
 * OpenID actions.
 *
 * @package    OpenPNE
 * @subpackage OpenID
 * @author     Kousuke Ebihara <ebihara@tejimaya.com>
 * @version    SVN: $Id: actions.class.php 9301 2008-05-27 01:08:46Z dwhittle $
 */
class OpenIDActions extends sfActions
{
 /**
  * Executes index action
  *
  * @param sfRequest $request A request object
  */
  public function executeIndex($request)
  {
    $this->forward('default', 'module');
  }

 /**
  * Executes registerEnd action
  *
  * @param sfRequest $request A request object
  */
  public function executeRegisterEnd($request)
  {
    $this->redirect('@homepage');
  }
}
