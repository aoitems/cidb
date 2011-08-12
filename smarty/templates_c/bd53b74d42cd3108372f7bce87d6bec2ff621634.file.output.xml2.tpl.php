<?php /* Smarty version Smarty-3.0.8, created on 2011-08-11 12:21:16
         compiled from "templates/output.xml2.tpl" */ ?>
<?php /*%%SmartyHeaderCode:9846586504e43ad1c0cae32-33092571%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    'bd53b74d42cd3108372f7bce87d6bec2ff621634' => 
    array (
      0 => 'templates/output.xml2.tpl',
      1 => 1313058076,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '9846586504e43ad1c0cae32-33092571',
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

<items version="<?php echo $_smarty_tpl->getVariable('version')->value;?>
" source="<?php echo $_smarty_tpl->getVariable('source')->value;?>
" ql="<?php echo $_smarty_tpl->getVariable('ql')->value;?>
" max="<?php echo $_smarty_tpl->getVariable('max')->value;?>
" results="<?php echo $_smarty_tpl->getVariable('results_count')->value;?>
" rev="2">
<?php  $_smarty_tpl->tpl_vars['result'] = new Smarty_Variable;
 $_from = $_smarty_tpl->getVariable('results')->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
if ($_smarty_tpl->_count($_from) > 0){
    foreach ($_from as $_smarty_tpl->tpl_vars['result']->key => $_smarty_tpl->tpl_vars['result']->value){
?>	<item name="<?php echo smarty_modifier_escape($_smarty_tpl->tpl_vars['result']->value['Name'],'html');?>
" <?php if ($_smarty_tpl->tpl_vars['result']->value['LowID']!=$_smarty_tpl->tpl_vars['result']->value['HighID']){?>lowid="<?php echo $_smarty_tpl->tpl_vars['result']->value['LowID'];?>
" highid="<?php echo $_smarty_tpl->tpl_vars['result']->value['HighID'];?>
"<?php }else{ ?>id="<?php echo $_smarty_tpl->tpl_vars['result']->value['LowID'];?>
"<?php }?> <?php if ($_smarty_tpl->tpl_vars['result']->value['LowQL']!=$_smarty_tpl->tpl_vars['result']->value['HighQL']){?>lowql="<?php echo $_smarty_tpl->tpl_vars['result']->value['LowQL'];?>
" highql="<?php echo $_smarty_tpl->tpl_vars['result']->value['HighQL'];?>
"<?php }else{ ?>ql="<?php echo $_smarty_tpl->tpl_vars['result']->value['LowQL'];?>
"<?php }?> icon="<?php echo $_smarty_tpl->tpl_vars['result']->value['Icon'];?>
" type="<?php echo $_smarty_tpl->tpl_vars['result']->value['Type'];?>
"<?php if (!empty($_smarty_tpl->tpl_vars['result']->value['Slot'])){?> slot="<?php echo $_smarty_tpl->tpl_vars['result']->value['Slot'];?>
"<?php }?><?php if (!empty($_smarty_tpl->tpl_vars['result']->value['DefaultSlot'])){?> defaultslot="<?php echo $_smarty_tpl->tpl_vars['result']->value['DefaultSlot'];?>
"<?php }?> />
<?php }} ?>
</items>