<div class="dbox profile_section_interests">
	<div class="dboxheader dbox_head_profile_interetst">
		<div class="dboxtitle botmarg">
			Skills & Intrests
		</div>
	</div>
	<hr class="advancedsearch_hr">
	<div class="dboxheader dbox_head_edit_profile_new_field">
		<div class="dboxtitle botmarg">
			Add new Interests/Skill
		</div>
			
	</div>

	<input id="fname" name="firstname" class="edit_profile_contactinfo_item_value_field" placeholder="Enter Field Value type" Here="text"><br><br>
	<center><button class="default_button edit_profile_contactinfo_add_button">Add to Profile</button></center>
	<br><br>
	<div>
		<?php
			$str =  "Your lists will also appear in the Interests section of your bookmarks. Simply click the list's name to see all the recent posts and activity from the Pages and people featured in the list.";    
			$ary = explode(' ', $str);
			foreach($ary as $str){
			   include("skill_item.php");
			}
		?>
	</div>
</div>