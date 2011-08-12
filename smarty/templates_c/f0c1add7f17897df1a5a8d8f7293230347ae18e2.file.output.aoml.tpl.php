<?php /* Smarty version Smarty-3.0.8, created on 2011-08-05 17:21:42
         compiled from "templates/output.aoml.tpl" */ ?>
<?php /*%%SmartyHeaderCode:7845007404e3c0a86486695-54298994%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    'f0c1add7f17897df1a5a8d8f7293230347ae18e2' => 
    array (
      0 => 'templates/output.aoml.tpl',
      1 => 1312557657,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '7845007404e3c0a86486695-54298994',
  'function' => 
  array (
  ),
  'has_nocache_code' => false,
)); /*/%%SmartyHeaderCode%%*/?>
<?php if (!is_callable('smarty_modifier_escape')) include '/usr/home/b/bo/botsharp/public_html/cidb.botsharp.net/smarty/plugins/modifier.escape.php';
?><?php if ($_smarty_tpl->getVariable('results_count')->value>0){?><font color=<?php echo $_smarty_tpl->getVariable('color_header')->value;?>
><?php echo $_smarty_tpl->getVariable('results_count')->value;?>
</font> <font color=<?php echo $_smarty_tpl->getVariable('color_highlight')->value;?>
>Results :: </font><a href="text://<font color=<?php echo $_smarty_tpl->getVariable('color_normal')->value;?>
><font color=<?php echo $_smarty_tpl->getVariable('color_header')->value;?>
>Central Items Database</font>
<font color=<?php echo $_smarty_tpl->getVariable('color_highlight')->value;?>
>Server:</font> <?php echo $_smarty_tpl->getVariable('server')->value;?>

<font color=<?php echo $_smarty_tpl->getVariable('color_highlight')->value;?>
>Database Version:</font> <?php echo $_smarty_tpl->getVariable('version')->value;?>
 <?php echo $_smarty_tpl->getVariable('source')->value;?>

<font color=<?php echo $_smarty_tpl->getVariable('color_highlight')->value;?>
>Query:</font> <?php echo smarty_modifier_escape($_smarty_tpl->getVariable('search')->value,'html');?>

<font color=<?php echo $_smarty_tpl->getVariable('color_highlight')->value;?>
>Results:</font> <?php echo $_smarty_tpl->getVariable('results_count')->value;?>
 / <?php echo $_smarty_tpl->getVariable('max')->value;?>
</font>

<font color=<?php echo $_smarty_tpl->getVariable('color_header')->value;?>
>Results</font>
<?php  $_smarty_tpl->tpl_vars['result'] = new Smarty_Variable;
 $_from = $_smarty_tpl->getVariable('results')->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
if ($_smarty_tpl->_count($_from) > 0){
    foreach ($_from as $_smarty_tpl->tpl_vars['result']->key => $_smarty_tpl->tpl_vars['result']->value){
?><font color=<?php echo $_smarty_tpl->getVariable('color_highlight')->value;?>
><?php echo smarty_modifier_escape($_smarty_tpl->tpl_vars['result']->value['Name'],'html');?>
</font><font color=<?php echo $_smarty_tpl->getVariable('color_normal')->value;?>
> [<a href='itemref://<?php echo $_smarty_tpl->tpl_vars['result']->value['LowID'];?>
/<?php echo $_smarty_tpl->tpl_vars['result']->value['HighID'];?>
/<?php echo $_smarty_tpl->tpl_vars['result']->value['LowQL'];?>
'>QL <?php echo $_smarty_tpl->tpl_vars['result']->value['LowQL'];?>
</a>]<?php if ($_smarty_tpl->getVariable('ql')->value!=0&&$_smarty_tpl->getVariable('ql')->value>$_smarty_tpl->tpl_vars['result']->value['LowQL']){?> [<a href='itemref://<?php echo $_smarty_tpl->tpl_vars['result']->value['LowID'];?>
/<?php echo $_smarty_tpl->tpl_vars['result']->value['HighID'];?>
/<?php echo $_smarty_tpl->getVariable('ql')->value;?>
'>QL <?php echo $_smarty_tpl->getVariable('ql')->value;?>
</a>]<?php }?><?php if ($_smarty_tpl->getVariable('ql')->value<$_smarty_tpl->tpl_vars['result']->value['HighQL']&&$_smarty_tpl->tpl_vars['result']->value['HighQL']!=$_smarty_tpl->tpl_vars['result']->value['LowQL']){?> [<a href='itemref://<?php echo $_smarty_tpl->tpl_vars['result']->value['LowID'];?>
/<?php echo $_smarty_tpl->tpl_vars['result']->value['HighID'];?>
/<?php echo $_smarty_tpl->tpl_vars['result']->value['HighQL'];?>
'>QL <?php echo $_smarty_tpl->tpl_vars['result']->value['HighQL'];?>
</a>]<?php }?></font>
<?php if ($_smarty_tpl->tpl_vars['result']->value['Icon']>0&&$_smarty_tpl->getVariable('display_icon')->value){?><img src='rdb://<?php echo $_smarty_tpl->tpl_vars['result']->value['Icon'];?>
'>
<?php }?><?php }} ?>
">Click to View</a><?php }else{ ?><font color=<?php echo $_smarty_tpl->getVariable('color_highlight')->value;?>
>Your query returned 0 results<?php }?>