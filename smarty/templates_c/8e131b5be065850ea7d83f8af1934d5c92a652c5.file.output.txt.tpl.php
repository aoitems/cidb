<?php /* Smarty version Smarty-3.0.8, created on 2011-08-11 13:52:38
         compiled from "templates/output.txt.tpl" */ ?>
<?php /*%%SmartyHeaderCode:9315857684e43c286c19975-79356514%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '8e131b5be065850ea7d83f8af1934d5c92a652c5' => 
    array (
      0 => 'templates/output.txt.tpl',
      1 => 1313063554,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '9315857684e43c286c19975-79356514',
  'function' => 
  array (
  ),
  'has_nocache_code' => false,
)); /*/%%SmartyHeaderCode%%*/?>
<?php if (!is_callable('smarty_block_php')) include '/usr/home/b/bo/botsharp/public_html/cidb.botsharp.net/smarty/plugins/block.php.php';
if (!is_callable('smarty_modifier_escape')) include '/usr/home/b/bo/botsharp/public_html/cidb.botsharp.net/smarty/plugins/modifier.escape.php';
?><?php $_smarty_tpl->smarty->_tag_stack[] = array('php', array()); $_block_repeat=true; smarty_block_php(array(), null, $_smarty_tpl, $_block_repeat);while ($_block_repeat) { ob_start();?>
header("Content-Type: text/plain");<?php $_block_content = ob_get_clean(); $_block_repeat=false; echo smarty_block_php(array(), $_block_content, $_smarty_tpl, $_block_repeat);  } array_pop($_smarty_tpl->smarty->_tag_stack);?>
#Version=<?php echo $_smarty_tpl->getVariable('version')->value;?>

<?php if ($_smarty_tpl->getVariable('outputversion')->value>=1.2){?>#Server=<?php echo $_smarty_tpl->getVariable('server')->value;?>

<?php }?>
<?php if ($_smarty_tpl->getVariable('outputversion')->value>=1.2){?>#Revision=<?php echo $_smarty_tpl->getVariable('outputversion')->value;?>

<?php }?>
<?php if ($_smarty_tpl->getVariable('outputversion')->value>=1.2){?>#Search=<?php echo $_smarty_tpl->getVariable('search')->value;?>

<?php }?>
#Source=<?php echo $_smarty_tpl->getVariable('source')->value;?>

#QL=<?php echo $_smarty_tpl->getVariable('ql')->value;?>

#Max=<?php echo $_smarty_tpl->getVariable('max')->value;?>

#Results=<?php echo $_smarty_tpl->getVariable('results_count')->value;?>

#Fields=Name;LowID;HighID;LowQL;HighQL;IconID;Type;Slot;DefaultSlot
<?php  $_smarty_tpl->tpl_vars['result'] = new Smarty_Variable;
 $_from = $_smarty_tpl->getVariable('results')->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
if ($_smarty_tpl->_count($_from) > 0){
    foreach ($_from as $_smarty_tpl->tpl_vars['result']->key => $_smarty_tpl->tpl_vars['result']->value){
?><?php echo smarty_modifier_escape($_smarty_tpl->tpl_vars['result']->value['Name'],'html');?>
;<?php echo $_smarty_tpl->tpl_vars['result']->value['LowID'];?>
;<?php echo $_smarty_tpl->tpl_vars['result']->value['HighID'];?>
;<?php echo $_smarty_tpl->tpl_vars['result']->value['LowQL'];?>
;<?php echo $_smarty_tpl->tpl_vars['result']->value['HighQL'];?>
;<?php echo $_smarty_tpl->tpl_vars['result']->value['Icon'];?>
;<?php echo $_smarty_tpl->tpl_vars['result']->value['Type'];?>
;<?php echo $_smarty_tpl->tpl_vars['result']->value['Slot'];?>
;<?php echo $_smarty_tpl->tpl_vars['result']->value['DefaultSlot'];?>

<?php }} ?>