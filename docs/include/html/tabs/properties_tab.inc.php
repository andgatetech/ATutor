
		<?php if ($_POST['day']) { ?>
		<tr>
			<td class="row1"><br /><?php print_popup_help(AT_HELP_NOT_RELEASED); ?><b><?php echo _AT('release_date');  ?></b></td>
			<td class="row1"><br /><?php

				$today_day   = $_POST['day'];
				$today_mon   = $_POST['month'];
				$today_year  = $_POST['year'];

				$today_hour  = $_POST['hour'];
				$today_min   = $_POST['min'];

				require(AT_INCLUDE_PATH.'lib/release_date.inc.php');
		?>
	</td>
	</tr>
	<?php } else { ?>
	<tr>
	<td class="row1"><br /><?php print_popup_help(AT_HELP_NOT_RELEASED); ?><b><?php echo _AT('release_date');  ?>:</b></td>
	<td class="row1"><br /><?php

			require(AT_INCLUDE_PATH.'lib/release_date.inc.php');

			?>
	</td>
	</tr>
	
	<?php } ?>
			<tr><td height="1" class="row2" colspan="2"></td></tr><?php

if ($cid || true) { 
	$top_level = $contentManager->getContent($row['content_parent_id']);

?>
		<tr>
			<td colspan="2" class="row1"><input type="hidden" name="button_1" value="-1" /><?php

				echo '<br /><table border="0" cellspacing="0" cellpadding="1" class="tableborder" align="center" width="90%">';
				echo '<tr><th colspan="2" width="10%"><small>Move</small></th><th><small>Related Topic</th></tr>';
				echo '<tr><td><small>&nbsp;</small></td><td>&nbsp;</td><td> Home</td></tr>';


				$old_pid = $_POST['pid'];
				$old_ordering = $_POST['ordering'];

				if (isset($_POST['move'])) {
					$arr = explode('_', key($_POST['move']), 2);
					$new_pid = $_POST['new_pid'] = $arr[0];
					$new_ordering = $_POST['new_ordering'] = $arr[1];
				} else {
					$new_pid = $_POST['new_pid'];
					$new_ordering = $_POST['new_ordering'];
				}

/***
debug($old_ordering, '$old_ordering]');
debug($old_pid, '$old_pid]');
debug($new_ordering, '$new_ordering]');
debug($new_pid, '$new_pid]');
***/

				echo '<input type="hidden" name="new_ordering" value="'.$new_ordering.'" />';
				echo '<input type="hidden" name="new_pid" value="'.$new_pid.'" />';

				$menu = $contentManager->_menu;
				if ($cid == 0) {
					$old_ordering = count($contentManager->getContent($pid))+1;
					$old_pid = 0;


					$current = array('content_id' => -1,
									'ordering' => $old_ordering,
									'title' => $_POST['title'].'xxxxx');

					$menu[$old_pid][] = $current;
				}

				if (($old_pid != $new_pid) || ($old_ordering != $new_ordering) || ($cid == 0)) {

					$children = $menu[$old_pid];

					$children_current = array($children[$old_ordering-1]);
					unset($menu[$old_pid][$old_ordering-1]);

					if ($old_pid != $new_pid) {
						$num_children = count($menu[$old_pid]);
						$i = 1;
						foreach($menu[$old_pid] as $id => $child) {
							$menu[$old_pid][$id]['ordering'] = $i;
							$i++;
						}
					}

					$children = $menu[$new_pid];
					if (!isset($children)) {
						$children = array();
					}
				
					$children_above = array_slice($children, 0, $new_ordering-1);

					$children_below = array_slice($children, $new_ordering-1);

					$menu[$new_pid] = array_merge($children_above, $children_current, $children_below);

					$i=1;
					foreach($menu[$new_pid] as $id => $child) {
						$menu[$new_pid][$id]['ordering'] = $i;
						$i++;
					}
			
				}

				$contentManager->printMoveMenu($menu, 0, 0, '', array());

		?></table><br /></td>
		</tr>
<?php } else { 
			
		$top_level = $contentManager->getContent($_POST['pid']);
		
?>
		<tr>
			<td align="right" class="row1"><a name="jumpcodes"></a><?php print_popup_help(AT_HELP_INSERT); ?><b><label for="insert"><?php echo _AT('insert'); ?>:</label></b></td>
			<td class="row1"><select name="ordering" id="insert" class="formfield">
				<option value="0"><?php echo _AT('start_section'); ?></option>
			<?php
			if (is_array($top_level)) {
				$count = count($top_level);
				if ($_POST['ordering'] == $count) {
					echo '<option value="'.$count.'" selected="selected">'._AT('end_section').'</option>';
				}
				foreach ($top_level as $x => $info) {
					echo '<option value="'.$info['ordering'].'"';
					if ($info['ordering'] == $_POST['ordering']) {
						echo ' selected="selected"';
					}
					echo '>'._AT('after').': '.$info['ordering'].' "'.$info['title'].'"</option>';
				}
			}			
			?></select></td>
		</tr>
<?php } ?>