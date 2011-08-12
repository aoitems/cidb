<?php /* Smarty version Smarty-3.0.8, created on 2011-08-11 20:33:53
         compiled from "templates/index.tpl" */ ?>
<?php /*%%SmartyHeaderCode:6286474374e442091762d39-12000729%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '90093ad09988b466f409a1871733c5589014713e' => 
    array (
      0 => 'templates/index.tpl',
      1 => 1313087632,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '6286474374e442091762d39-12000729',
  'function' => 
  array (
  ),
  'has_nocache_code' => false,
)); /*/%%SmartyHeaderCode%%*/?>
<?php if (!is_callable('smarty_modifier_escape')) include '/usr/home/b/bo/botsharp/public_html/cidb.botsharp.net/smarty/plugins/modifier.escape.php';
?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd"> 
<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
		<title>Bot# CIDB</title>
		<link type="text/css" href="http://cidb.botsharp.net/style.css" rel="stylesheet" />
	</head>
	<body>
		<center>
		<h1>CIDB.BOTSHARP.NET</h1>
		<table class="specification">
			<caption>CIDB implementation</caption>
			<thead>
				<tr>
					<th>Version</th>
					<th>Parameter</th>
					<th>Description</th>
				</tr>
			</thead>
			<tfoot>
				<tr>
					<td colspan="3">See <a href="http://aodevs.com/forums/index.php/topic,8144.0.html" target="_blank" class="footer">CIDB Specification</a> for more information</td>
				</tr>
			</tfoot>
			<tbody>
				<tr class="primary">
					<td rowspan="5">1.1</td>
					<td>bot</td>
					<td class="details"><b>[REQUIRED]</b> String to identify bot/plugin. <b>Example</b>: <i>BotSharp ItemPlugin v1.0.1.1</i><br />
							This is used to track down misbehaving plugins, so that their authors may be notified.</td>
				</tr>
				<tr class="primary">
					<td>search</td>
					<td class="details"><b>[REQUIRED]</b> Search string. Wildcard(%) allowed; Spaces will be replaced with wildcard. <b>Example</b>: <i>combined foot</i></td>
				</tr>
				<tr class="primary">
					<td>output</td>
					<td class="details"><b>[REQUIRED]</b> Specifies which format the results will be returned as. XML or Json is recommended, while AOML allows for lazy implementation.<br /> 
						<b>Valid options</b>: 
							<a href="/?bot=example&amp;output=aoml&amp;search=combined%20foot">aoml</a>,
							<a href="/?bot=example&amp;output=xml&amp;search=combined%20foot">xml</a>, 
							<a href="/?bot=example&amp;output=json&amp;search=combined%20foot">json</a>,
							<a href="/?bot=example&amp;output=txt&amp;search=combined%20foot">txt</a>
					</td>
				</tr>
				<tr class="primary">
					<td>ql</td>
					<td class="details"><b>[OPTIONAL]</b> Specifies which QL the item you're searching for is. <b>Example</b>: <a href="/?bot=example&amp;output=txt&amp;search=combined%20foot&amp;ql=250">250</a></td>
				</tr>
				<tr class="primary">
					<td>max</td>
					<td class="details"><b>[OPTIONAL]</b> Specifies max number of results to return. <b>Range</b>: 1-<?php echo $_smarty_tpl->getVariable('maxresults')->value;?>
. <b>Default</b>: <?php echo $_smarty_tpl->getVariable('defaultresults')->value;?>
</td>
				</tr>
				<tr class="secondary">
					<td rowspan="2">1.1-Extension</td>
					<td>type</td>
					<td class="details"><b>[OPTIONAL]</b> Specifies what item type to search for. <b>Valid options</b>:
																<a href="/?bot=example&amp;output=xml&amp;search=combined&amp;type=armor">Armor</a>, 
																<a href="/?bot=example&amp;output=xml&amp;search=combined&amp;type=misc">Misc</a>, 
																<a href="/?bot=example&amp;output=xml&amp;search=t&amp;type=weapon">Weapon</a>,
																<a href="/?bot=example&amp;output=xml&amp;search=treatment&amp;slot=head&amp;type=implant">Implant</a>
																<br />
					</td>
				</tr>
				<tr class="secondary">
					<td>slot</td>
					<td class="details"><b>[OPTIONAL]</b> Specifies which slot you can wear the item in. Specify a comma-separated list of slots to retrieve items which match any of the specified slots.<br />Slots may be partial matches of the following options:<br />
						<b>Weapon slots</b>: HUD1, HUD2, HUD3, UTIL1, UTIL2, UTIL3, RightHand, BELT, LeftHand<br/>
						<b>Armor slots</b>: Neck, Head, Back, RightShoulder, Chest, LeftShoulder, RightArm, Hands, LeftArm, RightWrist, Legs, LeftWrist, RightFinger, Feet, LeftFinger<br />
						<b>Implant slots</b>: Eyes, Head, Ears, RightArm, Chest, LeftArm, RightWrist, Waist, LeftWrist, RightHand, Legs, LeftHand, Feet<br />
						<b>Examples</b>: 
																	<a href="/?bot=example&amp;output=xml&amp;search=combined&amp;type=armor&amp;slot=head">Head</a>, 
																	<a href="/?bot=example&amp;output=xml&amp;search=pen%20ofa%20crat&amp;type=armor&amp;slot=head,arm,feet">Head+Arm+Feet</a>
																	<br />
					</td>
				</tr>
				<tr class="primary">
					<td>1.2</td>
					<td>version</td>
					<td class="details"><b>[REQUIRED]</b> Specify version=1.2 to enable output version 1.2. <b>Example</b>:  
							<a href="/?bot=example&amp;output=aoml&amp;search=combined%20foot&amp;version=1.2">aoml</a>,
							<a href="/?bot=example&amp;output=xml&amp;search=combined%20foot&amp;version=1.2">xml</a>, 
							<a href="/?bot=example&amp;output=json&amp;search=combined%20foot&amp;version=1.2">json</a>,
							<a href="/?bot=example&amp;output=txt&amp;search=combined%20foot&amp;version=1.2">txt</a>
					</td>
				</tr>
			</tbody>
		</table>
		
		<br /><br />
		
		<table>
			<caption>Current Dataset</caption>
			<thead>
				<tr>
					<th>Updated</th>
					<th>Source</th>
					<th>Version</th>
					<th>Items</th>
					<th>Relations</th>
				</tr>
			</thead>
			<tbody>
				<tr>
					<td><?php echo $_smarty_tpl->getVariable('updated')->value;?>
</td>
					<td><?php echo $_smarty_tpl->getVariable('source')->value;?>
</td>
					<td><?php echo $_smarty_tpl->getVariable('version')->value;?>
</td>
					<td><?php echo $_smarty_tpl->getVariable('items')->value;?>
</td>
					<td><?php echo $_smarty_tpl->getVariable('relations')->value;?>
</td>
				</tr>
			</tbody>
		</table>
		<br /><br />
		<table>
		<caption>The bots that use this CIDB the most</caption>
			<thead>
				<tr>
					<th>Bot</th>
					<th>Queries</th>
				</tr>
			</thead>
		<?php  $_smarty_tpl->tpl_vars['v'] = new Smarty_Variable;
 $_smarty_tpl->tpl_vars['k'] = new Smarty_Variable;
 $_from = $_smarty_tpl->getVariable('topbots_byrequests')->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
if ($_smarty_tpl->_count($_from) > 0){
    foreach ($_from as $_smarty_tpl->tpl_vars['v']->key => $_smarty_tpl->tpl_vars['v']->value){
 $_smarty_tpl->tpl_vars['k']->value = $_smarty_tpl->tpl_vars['v']->key;
?>
			<tr>
				<td><?php echo smarty_modifier_escape($_smarty_tpl->tpl_vars['k']->value,'html');?>
</td>
				<td><?php echo smarty_modifier_escape($_smarty_tpl->tpl_vars['v']->value,'html');?>
%</td>
			</tr>
		<?php }} ?>
		</table>
		</center>
	</body>
</html>