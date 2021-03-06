<div class="widget-persons widget">

	<div class="widget-header" onclick="og.dashExpand('<?php echo $genid?>');">
		<?php echo (isset($widget_title)) ? $widget_title : lang("people");?>
		<div class="dash-expander ico-dash-expanded" id="<?php echo $genid; ?>expander"></div>
	</div>
	
	<div class="widget-body" id="<?php echo $genid; ?>_widget_body">
		<ul>
		<?php 
		$row_cls = "";
		foreach ($contacts as $person): ?>
			<li<?php echo ($row_cls == "" ? "" : " class='$row_cls'")?>>
				<div class="contact-avatar">
					<a href="<?php echo $person->getCardUrl() ?>" class="person" onclick="if (og.core_dimensions) og.core_dimensions.buildBeforeObjectViewAction(<?php echo $person->getId()?>, true);"><img src="<?php echo $person->getPictureUrl(); ?>" /></a>
				</div>
				
				<div class="contact-info">
					<a href="<?php echo $person->getCardUrl() ?>" class="person" onclick="if (og.core_dimensions) og.core_dimensions.buildBeforeObjectViewAction(<?php echo $person->getId()?>, true);"><?php echo clean($person->getObjectName()) ?></a>
					<div class="email"><?php echo $person->getEmailAddress(); ?></div> 
				</div>
				
				<div class="clear"></div>
			</li>
			<?php $row_cls = $row_cls == "" ? "dashAltRow" : ""; ?>
		<?php endforeach; ?>
		</ul>
		
		<?php if (count($contacts) < $total) :?>
			<div class="view-all-container">
				<a href="<?php echo get_url('contact', 'init')?>" ><?php echo lang("view all") ?></a>
			</div>
			<div class="clear"></div>
		<?php endif;?>
		
		<?php if ($render_add) :?>
			<?php if (count($contacts) > 0) :?>
				<div class="person-list-separator"></div>
			<?php endif; ?>
			
			<div style="float:right; margin-top:2px;">
				<a href="#" onclick="$('.add-person-form').slideToggle();$(this).hide();$('#add-person-form-show').show();" id="add-person-form-hide" style="display:none;">
					<?php echo lang('hide')?></a>
				<button onclick="$('.add-person-form').slideToggle();$(this).hide();$('#add-person-form-hide').show();" id="add-person-form-show" class="add-first-btn">
					<img src="public/assets/themes/default/images/16x16/add.png"/>&nbsp;<?php echo lang('add new contact')?></button>
			</div>
			<div id="person-form-<?php echo $genid ?>" class="add-person-form" style="display:none;">
				<h2><?php echo lang('new person') ?></h2>
				<div class="field name">
					<label><?php echo lang('name')?></label>
					<input type="text" class="add-person-field"/>
				</div>
				<div class="field email">
					<label><?php echo lang('email')?></label>
					<input type="email" name="contact[email]"/>
				</div>
				<div class="clear"></div>
				<?php tpl_display(get_template_path("add_contact/access_data_company","contact")); ?>
				<button class="add-person-button"><?php echo lang('add')?></button>
			</div>
		<?php endif;?>
		
	<div class="progress-mask"></div>
		
	</div>
</div>

<script>

	$(function(){
		
		$(".add-person-button").click(function(){
			var container = $(this).closest(".widget-body") ;
			container.closest(".widget-body").addClass("loading");
			
			var value = $(container).find("input.add-person-field").val();
			if (value) {
				
				var parent = 0 ;
				var create_user = ( container.find('input[name="contact[user][create-user]"]').is(':checked') ) ?'on':'' ;
				//var password = container.find('input[name="contact[user][password]"]').val();
				//var password_a =container.find('input[name="contact[user][password_a]"]').val();
				var mail = container.find('input[name="contact[email]"]').val();
				
				var user_type = container.find('select[name="contact[user][type]"] option:selected').val();
				var company_id = container.find('select[name="contact[user][company_id]"] option:selected').val();
				
				var postVars = {
					'member[object_type_id]': <?php echo ObjectTypes::findByName('person')->getId()?> ,
					'member[name]': value,
					'member[parent_member_id]' : parent,
					'member[dimension_id]': <?php echo Dimensions::findByCode('feng_persons')->getId()?>,
					'contact[email]': mail,
					'contact[user][create-user]' : create_user,
					'contact[user][type]': user_type,
					'contact[user][company_id]': company_id
				};

				var firstName = '';
				var surname = '';
				var nameParts = value.split(' ');
				if (nameParts && nameParts.length > 1) {
					for ( var i in nameParts ){
						if (i == "remove") continue;
						var word = $.trim(nameParts[i]);
						if (word ) {
							if (!firstName) {
								firstName = word;
							}else{
								surname += word + " ";	
							}		
						}	
					}	 
				}
				surname = $.trim(surname);
				if (firstName && surname) {
					postVars['contact[first_name]'] = firstName,
					postVars['contact[surname]'] = surname
				}	

				var ajaxOptions = {
					post : postVars,
					callback : function() {
						Ext.getCmp('menu-panel').expand(true); //ensure dimensions panel is expanded
					}
				};	

				var url = og.getUrl('contact', 'quick_add', {quick:1});

				og.openLink(url, ajaxOptions);
			}else{
				og.err('<?php echo lang('error add name required', lang('person'))?>');
				$(container).find("input.add-person-field").focus();
				container.removeClass("loading");
			}	
			
		});
		
		$(".add-person-field").keypress(function(e){
			if(e.keyCode == 13){
				$(".add-person-button").click();
     		}
		});
						
	});

</script>
