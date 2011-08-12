<?php /* Smarty version Smarty-3.0.8, created on 2011-08-11 13:51:46
         compiled from "templates/output.xml.tpl" */ ?>
<?php /*%%SmartyHeaderCode:7767120314e43c25287f549-60661790%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    'e4cc96f01f44666ff7fa2f2330ca4f5d84635cc4' => 
    array (
      0 => 'templates/output.xml.tpl',
      1 => 1313063506,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '7767120314e43c25287f549-60661790',
  'function' => 
  array (
  ),
  'has_nocache_code' => false,
)); /*/%%SmartyHeaderCode%%*/?>
<?php if (!is_callable('smarty_block_php')) include '/usr/home/b/bo/botsharp/public_html/cidb.botsharp.net/smarty/plugins/block.php.php';
if (!is_callable('smarty_modifier_escape')) include '/usr/home/b/bo/botsharp/public_html/cidb.botsharp.net/smarty/plugins/modifier.escape.php';
?><?php $_smarty_tpl->smarty->_tag_stack[] = array('php', array()); $_block_repeat=true; smarty_block_php(array(), null, $_smarty_tpl, $_block_repeat);while ($_block_repeat) { ob_start();?>
header("Content-Type: text/xml");<?php $_block_content = ob_get_clean(); $_block_repeat=false; echo smarty_block_php(array(), $_block_content, $_smarty_tpl, $_block_repeat);  } array_pop($_smarty_tpl->smarty->_tag_stack);?>
<?php echo '<?xml';?> version="1.0" encoding="UTF-8"<?php echo '?>';?>
<items>
<?php if ($_smarty_tpl->getVariable('outputversion')->value>=1.2){?><server><?php echo smarty_modifier_escape($_smarty_tpl->getVariable('server')->value,'html');?>
</server>
<?php }?>
<?php if ($_smarty_tpl->getVariable('outputversion')->value>=1.2){?><revision><?php echo smarty_modifier_escape($_smarty_tpl->getVariable('outputversion')->value,'html');?>
</revision>
<?php }?>
<?php if ($_smarty_tpl->getVariable('outputversion')->value>=1.2){?><search><?php echo smarty_modifier_escape($_smarty_tpl->getVariable('search')->value,'html');?>
</search>
<?php }?>
<version><?php echo smarty_modifier_escape($_smarty_tpl->getVariable('version')->value,'html');?>
</version>
<source><?php echo smarty_modifier_escape($_smarty_tpl->getVariable('source')->value,'html');?>
</source>
<ql><?php echo smarty_modifier_escape($_smarty_tpl->getVariable('ql')->value,'html');?>
</ql>
<max><?php echo smarty_modifier_escape($_smarty_tpl->getVariable('max')->value,'html');?>
</max>
<results><?php echo smarty_modifier_escape($_smarty_tpl->getVariable('results_count')->value,'html');?>
</results>
<?php  $_smarty_tpl->tpl_vars['result'] = new Smarty_Variable;
 $_from = $_smarty_tpl->getVariable('results')->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
if ($_smarty_tpl->_count($_from) > 0){
    foreach ($_from as $_smarty_tpl->tpl_vars['result']->key => $_smarty_tpl->tpl_vars['result']->value){
?><item name="<?php echo smarty_modifier_escape($_smarty_tpl->tpl_vars['result']->value['Name'],'html');?>
" lowid="<?php echo smarty_modifier_escape($_smarty_tpl->tpl_vars['result']->value['LowID'],'html');?>
" highid="<?php echo smarty_modifier_escape($_smarty_tpl->tpl_vars['result']->value['HighID'],'html');?>
" lowql="<?php echo smarty_modifier_escape($_smarty_tpl->tpl_vars['result']->value['LowQL'],'html');?>
" highql="<?php echo smarty_modifier_escape($_smarty_tpl->tpl_vars['result']->value['HighQL'],'html');?>
" icon="<?php echo smarty_modifier_escape($_smarty_tpl->tpl_vars['result']->value['Icon'],'html');?>
" type="<?php echo smarty_modifier_escape($_smarty_tpl->tpl_vars['result']->value['Type'],'html');?>
" slot="<?php echo smarty_modifier_escape($_smarty_tpl->tpl_vars['result']->value['Slot'],'html');?>
" defaultslot="<?php echo smarty_modifier_escape($_smarty_tpl->tpl_vars['result']->value['DefaultSlot'],'html');?>
" />
<?php }} ?>
</items>