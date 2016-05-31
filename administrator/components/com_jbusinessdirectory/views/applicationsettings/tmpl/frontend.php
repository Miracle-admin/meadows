<div class="row-fluid">
	<div class="span6">
		<fieldset class="form-horizontal">
			<legend><?php echo JText::_('LNG_GENERAL'); ?></legend>
			<div class="control-group">
				<div class="control-label"><label data-original-title="<strong><?php echo JText::_('LNG_MENU_ITEM_ID'); ?></strong><br />Enter menu item id that is associated with directory component" id="menu_item_id-lbl" for="menu_item_id" class="hasTooltip required" title=""><?php echo JText::_('LNG_MENU_ITEM_ID'); ?></label></div>
				<div class="controls"><input name="menu_item_id" id="menu_item_id" value="<?php echo $this->item->menu_item_id?>" size="50" type="text"></div>
			</div>
			
			
			<div class="control-group">
				<div class="control-label"><label id="map_auto_show-lbl" for="map_auto_show" class="hasTooltip" title=""><?php echo JText::_('LNG_MAP_AUTO_SHOW'); ?></label></div>
				<div class="controls">
					<fieldset id="map_auto_show_fld" class="radio btn-group btn-group-yesno">
						<input type="radio" class="validate[required]" name="map_auto_show" id="map_auto_show1" value="1" <?php echo $this->item->map_auto_show==true? 'checked="checked"' :""?> />
						<label class="btn" for="map_auto_show1"><?php echo JText::_('LNG_YES')?></label> 
						<input type="radio" class="validate[required]" name="map_auto_show" id="map_auto_show0" value="0" <?php echo $this->item->map_auto_show==false? 'checked="checked"' :""?> />
						<label class="btn" for="map_auto_show0"><?php echo JText::_('LNG_NO')?></label> 
					</fieldset>
				</div>
			</div>
			
			<div class="control-group">
				<div class="control-label"><label id="address_format-lbl" for="address_format" class="hasTooltip" title=""><?php echo JText::_('LNG_ADDRESS_FORMAT'); ?></label></div>
				<div class="controls">
					<fieldset id="address_format_fld" class="radio btn-group btn-group-yesno">
						<input type="radio" class="validate[required]" name="address_format" id="address_format1" value="1" <?php echo $this->item->address_format==true? 'checked="checked"' :""?> />
						<label class="btn" for="address_format1"><?php echo JText::_('LNG_EUROPEAN')?></label> 
						<input type="radio" class="validate[required]" name="address_format" id="address_format0" value="0" <?php echo $this->item->address_format==false? 'checked="checked"' :""?> />
						<label class="btn" for="address_format0"><?php echo JText::_('LNG_AMERICAN')?></label> 
					</fieldset>
				</div>
			</div>
			
			<div class="control-group">
				<div class="control-label"><label id="show_details_user-lbl" for="show_details_user" class="hasTooltip" title=""><?php echo JText::_('LNG_SHOW_DETAILS_ONLY_FOR_USERS'); ?></label></div>
				<div class="controls">
					<fieldset id="show_details_user_fld" class="radio btn-group btn-group-yesno">
						<input type="radio" class="validate[required]" name="show_details_user" id="show_details_user1" value="1" <?php echo $this->item->show_details_user==true? 'checked="checked"' :""?> />
						<label class="btn" for="show_details_user1"><?php echo JText::_('LNG_YES')?></label> 
						<input type="radio" class="validate[required]" name="show_details_user" id="show_details_user0" value="0" <?php echo $this->item->show_details_user==false? 'checked="checked"' :""?> />
						<label class="btn" for="show_details_user0"><?php echo JText::_('LNG_NO')?></label> 
					</fieldset>
				</div>
			</div>
		</fieldset>
	
	</div>
	<div class="span6">
		<fieldset class="form-horizontal">
			<legend><?php echo JText::_('LNG_OFFERS'); ?></legend>
			<div class="control-group">
				<div class="control-label"><label id="enable_search_filter_offers-lbl" for="enable_search_filter_offers" class="hasTooltip" title=""><?php echo JText::_('LNG_enable_search_filter_offers'); ?></label></div>
				<div class="controls">
					<fieldset id="enable_search_filter_offers_fld" class="radio btn-group btn-group-yesno">
						<input type="radio" class="validate[required]" name="enable_search_filter_offers" id="enable_search_filter_offers1" value="1" <?php echo $this->item->enable_search_filter_offers==true? 'checked="checked"' :""?> />
						<label class="btn" for="enable_search_filter_offers1"><?php echo JText::_('LNG_YES')?></label> 
						<input type="radio" class="validate[required]" name="enable_search_filter_offers" id="enable_search_filter_offers0" value="0" <?php echo $this->item->enable_search_filter_offers==false? 'checked="checked"' :""?> />
						<label class="btn" for="enable_search_filter_offers0"><?php echo JText::_('LNG_NO')?></label> 
					</fieldset>
				</div>
			</div>
			
			<div class="control-group">
				<div class="control-label"><label id="offer_search_results_grid_view-lbl" for="offer_search_results_grid_view" class="hasTooltip" title=""><?php echo JText::_('LNG_OFFER_SEARCH_RESULT_GRID_VIEW'); ?></label></div>
				<div class="controls">
					<fieldset id="offer_search_results_grid_view_fld" class="radio btn-group btn-group-yesno">
						<input type="radio" class="validate[required]" name="offer_search_results_grid_view" id="offer_search_results_grid_view1" value="1" <?php echo $this->item->offer_search_results_grid_view==true? 'checked="checked"' :""?> />
						<label class="btn" for="offer_search_results_grid_view1"><?php echo JText::_('LNG_STYLE_2')?></label> 
						<input type="radio" class="validate[required]" name="offer_search_results_grid_view" id="offer_search_results_grid_view0" value="0" <?php echo $this->item->offer_search_results_grid_view==false? 'checked="checked"' :""?> />
						<label class="btn" for="offer_search_results_grid_view0"><?php echo JText::_('LNG_STYLE_1')?></label> 
					</fieldset>
				</div>
			</div>
			
				
			<div class="control-group">
				<div class="control-label"><label id="offers_view_mode-lbl" for="offers_view_mode" class="hasTooltip" title=""><?php echo JText::_('LNG_DEFAULT_OFFERS_VIEW'); ?></label></div>
				<div class="controls">
					<fieldset id="offers_view_mode_fld" class="radio btn-group btn-group-yesno">
						<input type="radio" class="validate[required]" name="offers_view_mode" id="offers_view_mode1" value="1" <?php echo $this->item->offers_view_mode==true? 'checked="checked"' :""?> />
						<label class="btn" for="offers_view_mode1"><?php echo JText::_('LNG_GRID_MODE')?></label> 
						<input type="radio" class="validate[required]" name="offers_view_mode" id="offers_view_mode0" value="0" <?php echo $this->item->offers_view_mode==false? 'checked="checked"' :""?> />
						<label class="btn" for="offers_view_mode0"><?php echo JText::_('LNG_LIST_MODE')?></label> 
					</fieldset>
				</div>
			</div>
		</fieldset>
	</div>
</div>	

<div class="row-fluid">
	<div class="span6">
			<fieldset class="form-horizontal">
			<legend><?php echo JText::_('LNG_CATEGORIES'); ?></legend>
			
			<div class="control-group">
				<div class="control-label"><label id="category_view-lbl" for="category_view" class="hasTooltip" title=""><?php echo JText::_('LNG_CATEGORIES_VIEW'); ?></label></div>
				<div class="controls">
					<fieldset id="category_view_fld" class="radio btn-group btn-group-yesno">
						<input type="radio" class="validate[required]" name="category_view" id="category_view1" value="1" <?php echo $this->item->category_view==1? 'checked="checked"' :""?> />
						<label class="btn" for="category_view1"><?php echo JText::_('LNG_ACCORDION')?></label> 
						<input type="radio" class="validate[required]" name="category_view" id="category_view2" value="2" <?php echo $this->item->category_view==2? 'checked="checked"' :""?> />
						<label class="btn" for="category_view2"><?php echo JText::_('LNG_BOXES')?></label> 
						<input type="radio" class="validate[required]" name="category_view" id="category_view3" value="3" <?php echo $this->item->category_view==3? 'checked="checked"' :""?> />
						<label class="btn" for="category_view3"><?php echo JText::_('LNG_SIMPLE')?></label> 
					</fieldset>
				</div>
			</div>
			
			<div class="control-group">
				<div class="control-label"><label id="show_cat_description-lbl" for="show_cat_description" class="hasTooltip" title=""><?php echo JText::_('LNG_SHOW_CAT_DESCRIPTION'); ?></label></div>
				<div class="controls">
					<fieldset id="show_cat_description_fld" class="radio btn-group btn-group-yesno">
						<input type="radio" class="validate[required]" name="show_cat_description" id="show_cat_description1" value="1" <?php echo $this->item->show_cat_description==true? 'checked="checked"' :""?> />
						<label class="btn" for="show_cat_description1"><?php echo JText::_('LNG_YES')?></label> 
						<input type="radio" class="validate[required]" name="show_cat_description" id="show_cat_description0" value="0" <?php echo $this->item->show_cat_description==false? 'checked="checked"' :""?> />
						<label class="btn" for="show_cat_description0"><?php echo JText::_('LNG_NO')?></label> 
					</fieldset>
				</div>
			</div>

			<div class="control-group">
				<div class="control-label"><label id="max_categories-lbl" for="max_categories" class="hasTooltip" title=""><?php echo JText::_('LNG_MAX_CATEGORIES'); ?></label></div>
				<div class="controls">
					<input type="text" size="40" maxlength="20"  id="max_categories" name="max_categories" value="<?php echo $this->item->max_categories?>">
				</div>
			</div>

		</fieldset>
	</div>
	<div class="span6">
		<fieldset class="form-horizontal">
			<legend><?php echo JText::_('LNG_EVENTS'); ?></legend>
			<div class="control-group">
				<div class="control-label"><label id="enable_search_filter_events-lbl" for="enable_search_filter_events" class="hasTooltip" title=""><?php echo JText::_('LNG_ENABLE_SEARCH_FILTER_EVENTS'); ?></label></div>
				<div class="controls">
					<fieldset id="enable_search_filter_events_fld" class="radio btn-group btn-group-yesno">
						<input type="radio" class="validate[required]" name="enable_search_filter_events" id="enable_search_filter_events1" value="1" <?php echo $this->item->enable_search_filter_events==true? 'checked="checked"' :""?> />
						<label class="btn" for="enable_search_filter_events1"><?php echo JText::_('LNG_YES')?></label> 
						<input type="radio" class="validate[required]" name="enable_search_filter_events" id="enable_search_filter_events0" value="0" <?php echo $this->item->enable_search_filter_events==false? 'checked="checked"' :""?> />
						<label class="btn" for="enable_search_filter_events0"><?php echo JText::_('LNG_NO')?></label> 
					</fieldset>
				</div>
			</div>
			
			<div class="control-group">
				<div class="control-label"><label id="events_search_view-lbl" for="events_search_view" class="hasTooltip" title=""><?php echo JText::_("LNG_DEFAULT_EVENTS_VIEW"); ?></label></div>
				<div class="controls">
					<fieldset id="enable_packages_fld" class="radio btn-group btn-group-yesno">
						<input type="radio" class="validate[required]" name="events_search_view" id="events_search_view1" value="1" <?php echo $this->item->events_search_view==1? 'checked="checked"' :""?> />
						<label class="btn" for="events_search_view1"><?php echo JText::_('LNG_GRID')?></label> 
						<input type="radio" class="validate[required]" name="events_search_view" id="events_search_view0" value="2" <?php echo $this->item->events_search_view==2? 'checked="checked"' :""?> />
						<label class="btn" for="events_search_view0"><?php echo JText::_('LNG_LIST')?></label> 
					</fieldset>
				</div>
			</div>
		</fieldset>
	</div>
</div>
<div class="row-fluid">
	<div class="span12">
		<fieldset class="form-horizontal">
			<legend><?php echo JText::_('LNG_SEARCH'); ?></legend>
			
			<div class="control-group">
				<div class="control-label"><label id="search_view_mode-lbl" for="search_view_mode" class="hasTooltip" title=""><?php echo JText::_('LNG_DEFAULT_SEARCH_VIEW'); ?></label></div>
				<div class="controls">
					<fieldset id="search_view_mode_fld" class="radio btn-group btn-group-yesno">
						<input type="radio" class="validate[required]" name="search_view_mode" id="search_view_mode1" value="1" <?php echo $this->item->search_view_mode==true? 'checked="checked"' :""?> />
						<label class="btn" for="search_view_mode1"><?php echo JText::_('LNG_GRID_MODE')?></label> 
						<input type="radio" class="validate[required]" name="search_view_mode" id="search_view_mode0" value="0" <?php echo $this->item->search_view_mode==false? 'checked="checked"' :""?> />
						<label class="btn" for="search_view_mode0"><?php echo JText::_('LNG_LIST_MODE')?></label> 
					</fieldset>
				</div>
			</div>
			
			<div class="control-group">
				<div class="control-label"><label id="search_result_view-lbl" for="search_result_view" class="hasTooltip" title=""><?php echo JText::_('LNG_SEARCH_RESULT_VIEW'); ?></label></div>
				<div class="controls">
					<fieldset id="search_result_view_fld" class="radio btn-group btn-group-yesno">
						<input type="radio" class="validate[required]" name="search_result_view" id="search_result_view1" value="1" <?php echo $this->item->search_result_view==1? 'checked="checked"' :""?> />
						<label class="btn" for="search_result_view1"><?php echo JText::_('LNG_STYLE_1')?></label> 
						<input type="radio" class="validate[required]" name="search_result_view" id="search_result_view2" value="2" <?php echo $this->item->search_result_view==2? 'checked="checked"' :""?> />
						<label class="btn" for="search_result_view2"><?php echo JText::_('LNG_STYLE_2')?></label> 
						<input type="radio" class="validate[required]" name="search_result_view" id="search_result_view3" value="3" <?php echo $this->item->search_result_view==3? 'checked="checked"' :""?> />
						<label class="btn" for="search_result_view3"><?php echo JText::_('LNG_STYLE_3')?></label> 
						<input type="radio" class="validate[required]" name="search_result_view" id="search_result_view4" value="4" <?php echo $this->item->search_result_view==4? 'checked="checked"' :""?> />
						<label class="btn" for="search_result_view4"><?php echo JText::_('LNG_STYLE_4')?></label> 
					</fieldset>
				</div>
			</div>
			
			<div class="control-group">
				<div class="control-label"><label id="search_result_grid_view-lbl" for="search_result_grid_view" class="hasTooltip" title=""><?php echo JText::_('LNG_SEARCH_RESULTS_GRID_VIEW'); ?></label></div>
				<div class="controls">
					<fieldset id="search_result_grid_view_fld" class="radio btn-group btn-group-yesno">
						<input type="radio" class="validate[required]" name="search_result_grid_view" id="search_result_grid_view1" value="1" <?php echo $this->item->search_result_grid_view==1? 'checked="checked"' :""?> />
						<label class="btn" for="search_result_grid_view1"><?php echo JText::_('LNG_STYLE_1')?></label> 
						<input type="radio" class="validate[required]" name="search_result_grid_view" id="search_result_grid_view2" value="2" <?php echo $this->item->search_result_grid_view==2? 'checked="checked"' :""?> />
						<label class="btn" for="search_result_grid_view2"><?php echo JText::_('LNG_STYLE_2')?></label> 
					</fieldset>
				</div>
			</div>
			
			<div class="control-group">
				<div class="control-label"><label id="enable_numbering-lbl" for="enable_numbering" class="hasTooltip" title=""><?php echo JText::_('LNG_ENABLE_NUMBERING'); ?></label></div>
				<div class="controls">
					<fieldset id="enable_numbering_fld" class="radio btn-group btn-group-yesno">
						<input type="radio" class="validate[required]" name="enable_numbering" id="enable_numbering1" value="1" <?php echo $this->item->enable_numbering==true? 'checked="checked"' :""?> />
						<label class="btn" for="enable_numbering1"><?php echo JText::_('LNG_YES')?></label> 
						<input type="radio" class="validate[required]" name="enable_numbering" id="enable_numbering0" value="0" <?php echo $this->item->enable_numbering==false? 'checked="checked"' :""?> />
						<label class="btn" for="enable_numbering0"><?php echo JText::_('LNG_NO')?></label> 
					</fieldset>
				</div>
			</div>
			
			
			
			<div class="control-group">
				<div class="control-label"><label id="show_search_map-lbl" for="show_search_map" class="hasTooltip" title=""><?php echo JText::_('LNG_SHOW_SEARCH_MAP'); ?></label></div>
				<div class="controls">
					<fieldset id="show_search_map_fld" class="radio btn-group btn-group-yesno">
						<input type="radio" class="validate[required]" name="show_search_map" id="show_search_map1" value="1" <?php echo $this->item->show_search_map==true? 'checked="checked"' :""?> />
						<label class="btn" for="show_search_map1"><?php echo JText::_('LNG_YES')?></label> 
						<input type="radio" class="validate[required]" name="show_search_map" id="show_search_map0" value="0" <?php echo $this->item->show_search_map==false? 'checked="checked"' :""?> />
						<label class="btn" for="show_search_map0"><?php echo JText::_('LNG_NO')?></label> 
					</fieldset>
				</div>
			</div>
			
			<div class="control-group">
				<div class="control-label"><label id="show_secondary_map_locations-lbl" for="show_secondary_map_locations" class="hasTooltip" title=""><?php echo JText::_('LNG_SHOW_SECONDARY_MAP_LOCATIONS'); ?></label></div>
				<div class="controls">
					<fieldset id="show_secondary_map_locations_fld" class="radio btn-group btn-group-yesno">
						<input type="radio" class="validate[required]" name="show_secondary_map_locations" id="show_secondary_map_locations1" value="1" <?php echo $this->item->show_secondary_map_locations==true? 'checked="checked"' :""?> />
						<label class="btn" for="show_secondary_map_locations1"><?php echo JText::_('LNG_YES')?></label> 
						<input type="radio" class="validate[required]" name="show_secondary_map_locations" id="show_secondary_map_locations0" value="0" <?php echo $this->item->show_secondary_map_locations==false? 'checked="checked"' :""?> />
						<label class="btn" for="show_secondary_map_locations0"><?php echo JText::_('LNG_NO')?></label> 
					</fieldset>
				</div>
			</div>
	
			<div class="control-group">
				<div class="control-label"><label id="enable_search_filter-lbl" for="enable_search_filter" class="hasTooltip" title=""><?php echo JText::_("LNG_ENABLE_SEARCH_FILTER"); ?></label></div>
				<div class="controls">
					<fieldset id="enable_packages_fld" class="radio btn-group btn-group-yesno">
						<input type="radio" class="validate[required]" name="enable_search_filter" id="enable_search_filter1" value="1" <?php echo $this->item->enable_search_filter==true? 'checked="checked"' :""?> />
						<label class="btn" for="enable_search_filter1"><?php echo JText::_('LNG_YES')?></label> 
						<input type="radio" class="validate[required]" name="enable_search_filter" id="enable_search_filter0" value="0" <?php echo $this->item->enable_search_filter==false? 'checked="checked"' :""?> />
						<label class="btn" for="enable_search_filter0"><?php echo JText::_('LNG_NO')?></label> 
					</fieldset>
				</div>
			</div>
			
			<div class="control-group">
				<div class="control-label"><label id="search_type-lbl" for="search_type" class="hasTooltip" title=""><?php echo JText::_("LNG_SEARCH_FILTER"); ?></label></div>
				<div class="controls">
					<fieldset id="enable_packages_fld" class="radio btn-group btn-group-yesno">
						<input type="radio" class="validate[required]" name="search_type" id="search_type1" value="1" <?php echo $this->item->search_type==true? 'checked="checked"' :""?> />
						<label class="btn" for="search_type1"><?php echo JText::_('LNG_FACETED')?></label> 
						<input type="radio" class="validate[required]" name="search_type" id="search_type0" value="0" <?php echo $this->item->search_type==false? 'checked="checked"' :""?> />
						<label class="btn" for="search_type0"><?php echo JText::_('LNG_REGULAR')?></label> 
					</fieldset>
				</div>
			</div>
			
			<div class="control-group">
				<div class="control-label"><label id="zipcode_search_type-lbl" for="zipcode_search_type" class="hasTooltip" title=""><?php echo JText::_('LNG_ZIPCODE_SEARCH_TYPE'); ?></label></div>
				<div class="controls">
					<fieldset id="enable_packages_fld" class="radio btn-group btn-group-yesno">
						<input type="radio" class="validate[required]" name="zipcode_search_type" id="zipcode_search_type1" value="1" <?php echo $this->item->zipcode_search_type==true? 'checked="checked"' :""?> />
						<label class="btn" for="zipcode_search_type1"><?php echo JText::_('LNG_BY_BUSINESS_ACTIVITY_RADIUS')?></label> 
						<input type="radio" class="validate[required]" name="zipcode_search_type" id="zipcode_search_type0" value="0" <?php echo $this->item->zipcode_search_type==false? 'checked="checked"' :""?> />
						<label class="btn" for="zipcode_search_type0"><?php echo JText::_('LNG_BY_DISTANCE')?></label> 
					</fieldset>
				</div>
			</div>
			
			<div class="control-group">
				<div class="control-label"><label id="order_search_listings-lbl" for="order_search_listings" class="hasTooltip" title=""><?php echo JText::_('LNG_ORDER_SEARCH_LISTINGS'); ?></label></div>
				<div class="controls">
					<fieldset id="order_search_listings_fld" class="radio btn-group btn-group-yesno">
						<input type="radio" class="validate[required]" name="order_search_listings" id="order_search_listings1" value="packageOrder desc" <?php echo $this->item->order_search_listings=="packageOrder desc"? 'checked="checked"' :""?> />
						<label class="btn" for="order_search_listings1"><?php echo JText::_('LNG_RELEVANCE')?></label> 
						<input type="radio" class="validate[required]" name="order_search_listings" id="order_search_listings6" value="id desc" <?php echo $this->item->order_search_listings=="id desc"? 'checked="checked"' :""?> />
						<label class="btn" for="order_search_listings6"><?php echo JText::_('LNG_LATEST')?></label> 
						<input type="radio" class="validate[required]" name="order_search_listings" id="order_search_listings2" value="companyName" <?php echo $this->item->order_search_listings=="companyName"? 'checked="checked"' :""?> />
						<label class="btn" for="order_search_listings2"><?php echo JText::_('LNG_NAME')?></label> 
						<input type="radio" class="validate[required]" name="order_search_listings" id="order_search_listings3" value="city asc" <?php echo $this->item->order_search_listings=="city asc"? 'checked="checked"' :""?> />
						<label class="btn" for="order_search_listings3"><?php echo JText::_('LNG_CITY')?></label> 
						<input type="radio" class="validate[required]" name="order_search_listings" id="order_search_listings4" value="averageRating desc" <?php echo $this->item->order_search_listings=="averageRating desc"? 'checked="checked"' :""?> />
						<label class="btn" for="order_search_listings4"><?php echo JText::_('LNG_RATING')?></label> 
						<input type="radio" class="validate[required]" name="order_search_listings" id="order_search_listings7" value="review_score desc" <?php echo $this->item->order_search_listings=="review_score desc"? 'checked="checked"' :""?> />
						<label class="btn" for="order_search_listings7"><?php echo JText::_('LNG_REVIEW')?></label> 
						<input type="radio" class="validate[required]" name="order_search_listings" id="order_search_listings5" value="rand()" <?php echo $this->item->order_search_listings=="rand()"? 'checked="checked"' :""?> />
						<label class="btn" for="order_search_listings5"><?php echo JText::_('LNG_RANDOM')?></label> 
					</fieldset>
				</div>
			</div>
			
			<div class="control-group">
				<div class="control-label"><label id="order_search_offers-lbl" for="order_search_offers" class="hasTooltip" title=""><?php echo JText::_('LNG_order_search_offers'); ?></label></div>
				<div class="controls">
					<fieldset id="order_search_offers_fld" class="radio btn-group btn-group-yesno">
						<input type="radio" class="validate[required]" name="order_search_offers" id="order_search_offers1" value="" <?php echo $this->item->order_search_offers==""? 'checked="checked"' :""?> />
						<label class="btn" for="order_search_offers1"><?php echo JText::_('LNG_RELEVANCE')?></label> 
						<input type="radio" class="validate[required]" name="order_search_offers" id="order_search_offers2" value="co.subject" <?php echo $this->item->order_search_offers=="co.subject"? 'checked="checked"' :""?> />
						<label class="btn" for="order_search_offers2"><?php echo JText::_('LNG_NAME')?></label> 
						<input type="radio" class="validate[required]" name="order_search_offers" id="order_search_offers3" value="co.city" <?php echo $this->item->order_search_offers=="co.city"? 'checked="checked"' :""?> />
						<label class="btn" for="order_search_offers3"><?php echo JText::_('LNG_CITY')?></label> 
						<input type="radio" class="validate[required]" name="order_search_offers" id="order_search_offers4" value="rand()" <?php echo $this->item->order_search_offers=="rand()"? 'checked="checked"' :""?> />
						<label class="btn" for="order_search_offers4"><?php echo JText::_('LNG_RANDOM')?></label> 
					</fieldset>
				</div>
			</div>
						
			<div class="control-group">
				<div class="control-label"><label id="order_search_events-lbl" for="order_search_events" class="hasTooltip" title=""><?php echo JText::_('LNG_ORDER_SEARCH_EVENTS'); ?></label></div>
				<div class="controls">
					<fieldset id="order_search_events_fld" class="radio btn-group btn-group-yesno">
						<input type="radio" class="validate[required]" name="order_search_events" id="order_search_events1" value="" <?php echo $this->item->order_search_events==""? 'checked="checked"' :""?> />
						<label class="btn" for="order_search_events1"><?php echo JText::_('LNG_RELEVANCE')?></label> 
						<input type="radio" class="validate[required]" name="order_search_events" id="order_search_events2" value="name" <?php echo $this->item->order_search_events=="name"? 'checked="checked"' :""?> />
						<label class="btn" for="order_search_events2"><?php echo JText::_('LNG_NAME')?></label> 
						<input type="radio" class="validate[required]" name="order_search_events" id="order_search_events3" value="city" <?php echo $this->item->order_search_events=="city"? 'checked="checked"' :""?> />
						<label class="btn" for="order_search_events3"><?php echo JText::_('LNG_CITY')?></label> 
						<input type="radio" class="validate[required]" name="order_search_events" id="order_search_events4" value="rand()" <?php echo $this->item->order_search_events=="rand()"? 'checked="checked"' :""?> />
						<label class="btn" for="order_search_events4"><?php echo JText::_('LNG_RANDOM')?></label> 
					</fieldset>
				</div>
			</div>
			
			
		</fieldset>
		
		<fieldset class="form-horizontal">
			<legend><?php echo JText::_('LNG_BUSINESS_LISTING_DETAILS'); ?></legend>
			
			<div class="control-group">
				<div class="control-label"><label id="company_view-lbl" for="company_view" class="hasTooltip" title=""><?php echo JText::_('LNG_COMPANY_VIEW'); ?></label></div>
				<div class="controls">
					<fieldset id="company_view_fld" class="radio btn-group btn-group-yesno">
						<input type="radio" class="validate[required]" name="company_view" id="company_view1" value="1" <?php echo $this->item->company_view==1? 'checked="checked"' :""?> />
						<label class="btn" for="company_view1"><?php echo JText::_('LNG_TABS_STYLE_1')?></label> 
						<input type="radio" class="validate[required]" name="company_view" id="company_view2" value="2" <?php echo $this->item->company_view==2? 'checked="checked"' :""?> />
						<label class="btn" for="company_view2"><?php echo JText::_('LNG_TABS_STYLE_2')?></label> 
						<input type="radio" class="validate[required]" name="company_view" id="company_view3" value="3" <?php echo $this->item->company_view==3? 'checked="checked"' :""?> />
						<label class="btn" for="company_view3"><?php echo JText::_('LNG_ONE_PAGE')?></label> 
						<input type="radio" class="validate[required]" name="company_view" id="company_view4" value="4" <?php echo $this->item->company_view==4? 'checked="checked"' :""?> />
						<label class="btn" for="company_view4"><?php echo JText::_('LNG_STYLE_4')?></label> 
					</fieldset>
				</div>
			</div>
			
			<div class="control-group">
				<div class="control-label"><label id="claim_business-lbl" for="claim_business" class="hasTooltip" title=""><?php echo JText::_('LNG_ENABLE_CLAIM_BUSINESS'); ?></label></div>
				<div class="controls">
					<fieldset id="claim_business_fld" class="radio btn-group btn-group-yesno">
						<input type="radio" class="validate[required]" name="claim_business" id="claim_business1" value="1" <?php echo $this->item->claim_business==true? 'checked="checked"' :""?> />
						<label class="btn" for="claim_business1"><?php echo JText::_('LNG_YES')?></label> 
						<input type="radio" class="validate[required]" name="claim_business" id="claim_business0" value="0" <?php echo $this->item->claim_business==false? 'checked="checked"' :""?> />
						<label class="btn" for="claim_business0"><?php echo JText::_('LNG_NO')?></label> 
					</fieldset>
				</div>
			</div>
			
			<div class="control-group">
				<div class="control-label"><label id="show_email-lbl" for="show_email" class="hasTooltip" title=""><?php echo JText::_('LNG_SHOW_EMAIL'); ?></label></div>
				<div class="controls">
					<fieldset id="show_email_fld" class="radio btn-group btn-group-yesno">
						<input type="radio" class="validate[required]" name="show_email" id="show_email1" value="1" <?php echo $this->item->show_email==true? 'checked="checked"' :""?> />
						<label class="btn" for="show_email1"><?php echo JText::_('LNG_YES')?></label> 
						<input type="radio" class="validate[required]" name="show_email" id="show_email0" value="0" <?php echo $this->item->show_email==false? 'checked="checked"' :""?> />
						<label class="btn" for="show_email0"><?php echo JText::_('LNG_NO')?></label> 
					</fieldset>
				</div>
			</div>
			
			<div class="control-group" style="display:none">
				<div class="control-label"><label data-original-title="<strong><?php echo JText::_('LNG_NR_IMAGES_SLIDE'); ?></strong><br />Enter the number of images per slide for business detail view slider" id="nr_images_slide-lbl" for="nr_images_slide" class="hasTooltip required" title=""><?php echo JText::_('LNG_NR_IMAGES_SLIDE'); ?></label></div>
				<div class="controls"><input name="nr_images_slide" id="nr_images_slide" value="<?php echo $this->item->nr_images_slide?>" size="50" type="text"></div>
			</div>
			
			<div class="control-group">
				<div class="control-label"><label id="max_pictures-lbl" for="max_pictures" class="hasTooltip" title=""><?php echo JText::_('LNG_MAX_PICTURES'); ?></label></div>
				<div class="controls">
					<input type="text" size=40 maxlength=20  id="max_pictures" name = "max_pictures" value="<?php echo $this->item->max_pictures?>">
				</div>
			</div>
			
			<div class="control-group">
				<div class="control-label"><label id="max_video-lbl" for="max_video" class="hasTooltip" title=""><?php echo JText::_('LNG_MAX_VIDEOS'); ?></label></div>
				<div class="controls">
					<input type="text" size=40 maxlength=20  id="max_video" name = "max_video" value="<?php echo $this->item->max_video?>">
				</div>
			</div>
			
			<div class="control-group">
				<div class="control-label"><label id="show_secondary_locations-lbl" for="show_secondary_locations" class="hasTooltip" title=""><?php echo JText::_('LNG_SHOW_SECONDARY_LOCATIONS'); ?></label></div>
				<div class="controls">
					<fieldset id="show_secondary_locations_fld" class="radio btn-group btn-group-yesno">
						<input type="radio" class="validate[required]" name="show_secondary_locations" id="show_secondary_locations1" value="1" <?php echo $this->item->show_secondary_locations==true? 'checked="checked"' :""?> />
						<label class="btn" for="show_secondary_locations1"><?php echo JText::_('LNG_YES')?></label> 
						<input type="radio" class="validate[required]" name="show_secondary_locations" id="show_secondary_locations0" value="0" <?php echo $this->item->show_secondary_locations==false? 'checked="checked"' :""?> />
						<label class="btn" for="show_secondary_locations0"><?php echo JText::_('LNG_NO')?></label> 
					</fieldset>
				</div>
			</div>
		</fieldset>
		
		
	</div>
</div>	
		