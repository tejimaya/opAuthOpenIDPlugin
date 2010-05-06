<?php

/**
 * This file is part of the OpenPNE package.
 * (c) OpenPNE Project (http://www.openpne.jp/)
 *
 * For the full copyright and license information, please view the LICENSE
 * file and the NOTICE file that were distributed with this source code.
 */

class RegisterOpenIDConfirmAction extends sfAction
{
  public function execute($request)
  {
    $this->forward404Unless($request->hasParameter('authMode'));
    $this->getUser()->setCurrentAuthMode('OpenID');

    if ($uri = $this->getUser()->login())
    {
      $this->redirectIf($this->getUser()->isRegisterBegin(), $this->getUser()->getRegisterInputAction());
      $this->redirectIf($this->getUser()->isRegisterFinish(), $this->getUser()->getRegisterEndAction());
      $this->redirectIf($this->getUser()->isMember(), $uri);
    }

    $this->getUser()->setFlash('error', 'Invalid.');
    $this->redirect('member/register?token='.$request['token']);
  }
}
