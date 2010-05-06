<?php op_include_form('opAuthOpenIDPluginRegisterBox', $form, array(
  'title'  => 'OpenID で登録する',
  'body'   => '使用したい OpenID を入力し、ボタンをクリックしてください。',
  'button' => 'この OpenID を使用して登録する',
  'url'    => url_for('OpenID/registerOpenIDConfirm?authMode=OpenID&token='.$sf_user->getCurrentMemberRegisterToken()),
));
?>
