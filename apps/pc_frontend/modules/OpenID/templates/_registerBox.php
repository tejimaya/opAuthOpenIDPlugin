<?php slot('_opAuthOpenIDPluginRegisterBox'); ?>
<p>使用したい OpenID を入力してください</p>
<p><input type="text" class="input_text" value="" /></p>
<p><input type="submit" class="input_submit" value="この OpenID を使用する" />
<?php end_slot() ?>
<?php op_include_box('opAuthOpenIDPluginRegisterBox', get_slot('_opAuthOpenIDPluginRegisterBox'), array(
  'title' => 'OpenID で登録する',
)); ?>
