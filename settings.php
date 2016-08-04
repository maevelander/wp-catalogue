<div class="wrap">
  <div id="icon-options-general" class="icon32"><br>
  </div>
  <h2>Wp Catalogue<?php _e(' Settings','wpc') ?></h2>
  <div class="wpc-left-liquid">
    <?php if ( isset( $_GET['settings-updated'] ) ) {
    echo "<div class='updated' style='margin-top:10px;'><p> WP Catalogue ". __('Settings updated successfully','wpc')."</p></div>";
} 
?>
    <div class="wpc-left">
      <div class="wpc-headings">
        <h3><?php _e('Settings','wpc') ?></h3>
      </div>
      <div class="wpc-inner">
        <p class="description"><?php _e('Adjust the basic presentation of your product catalogue here. It is important to set this up before you start uploading products so the plugin knows what size to generate thumbnails and product images','wpc') ?> </p>
        <p class="description"><?php _e('You can further customise the design of your product catalogue in your theme css.','wpc') ?> </p>
        <form method="post" action="options.php">
          <?php settings_fields( 'baw-settings-group' ); ?>
          <table class="form-table" id="catalogue-settings-tabls">
            <tbody>
              <tr>
                <th scope="row"><label for="grid_rows"><?php _e('Grid Rows','wpc') ?></label></th>
                <td><select id="grid_rows" name="grid_rows">
                    <option value="2" <?php if(get_option('grid_rows')==2){echo 'selected="selected"';} ?> >2</option>
                    <option value="3" <?php if(get_option('grid_rows')==3){echo 'selected="selected"';} ?>>3</option>
                    <option value="4" <?php if(get_option('grid_rows')==4){echo 'selected="selected"';} ?>>4</option>
                    <option value="5" <?php if(get_option('grid_rows')==5){echo 'selected="selected"';} ?>>5</option>
                    <option value="6" <?php if(get_option('grid_rows')==6){echo 'selected="selected"';} ?>>6</option>
                    <option value="7" <?php if(get_option('grid_rows')==7){echo 'selected="selected"';} ?>>7</option>
                    <option value="8" <?php if(get_option('grid_rows')==8){echo 'selected="selected"';} ?>>8</option>
                    <option value="9" <?php if(get_option('grid_rows')==9){echo 'selected="selected"';} ?>>9</option>
                    <option value="10" <?php if(get_option('grid_rows')==10){echo 'selected="selected"';} ?>>10</option>
                  </select>
                  <span><?php _e('products per row','wpc') ?></span></td>
              </tr>
              <!--Color choose - New feature-->
              
              <tr valign="top">
                <th scope="row"><label for="colorPicker"><?php _e('Choose Theme Color','wpc') ?></label></th>
                <td><input type="text" value="<?php if(get_option('templateColorforProducts')){ echo get_option('templateColorforProducts'); }else {echo "#000000";} ?>" class="templateColorforProducts" name="templateColorforProducts" id="templateColorforProducts" /></td>
              </tr>
              <tr valign="top">
                <th scope="row"><label for="pagination"><?php _e('Pagination','wpc') ?> </label></th>
                <td><select id="pagination" name="pagination">
                    <option value="2" <?php if(get_option('pagination')==2){echo 'selected="selected"';} ?> >2</option>
                    <option value="3" <?php if(get_option('pagination')==3){echo 'selected="selected"';} ?>>3</option>
                    <option value="4" <?php if(get_option('pagination')==4){echo 'selected="selected"';} ?>>4</option>
                    <option value="5" <?php if(get_option('pagination')==5){echo 'selected="selected"';} ?>>5</option>
                    <option value="6" <?php if(get_option('pagination')==6){echo 'selected="selected"';} ?>>6</option>
                    <option value="7" <?php if(get_option('pagination')==7){echo 'selected="selected"';} ?>>7</option>
                    <option value="8" <?php if(get_option('pagination')==8){echo 'selected="selected"';} ?>>8</option>
                    <option value="9" <?php if(get_option('pagination')==9){echo 'selected="selected"';} ?>>9</option>
                    <option value="10" <?php if(get_option('pagination')==10){echo 'selected="selected"';} ?>>10</option>
                     <option value="11" <?php if(get_option('pagination')==11){echo 'selected="selected"';} ?>>11</option>
                    <option value="12" <?php if(get_option('pagination')==12){echo 'selected="selected"';} ?>>12</option>
                    <option value="13" <?php if(get_option('pagination')==13){echo 'selected="selected"';} ?>>13</option>
                    <option value="14" <?php if(get_option('pagination')==14){echo 'selected="selected"';} ?>>14</option>
                    <option value="15" <?php if(get_option('pagination')==15){echo 'selected="selected"';} ?>>15</option>
                  </select>
                  </td>
                </td>
              </tr>
              <tr>
                <th scope="row"><label><?php _e('Gallery Image','wpc') ?></label></th>
                <td><input name="image_height" type="text" id="image_height" value="<?php if(get_option('image_height')){ echo get_option('image_height'); }else {echo 358;} ?>" size="10">
                  &nbsp;&nbsp;&nbsp;<span><?php _e('Height','wpc') ?></span>&nbsp;&nbsp;&nbsp;
                  <input name="image_width" type="text" id="image_width" value="<?php if(get_option('image_width')){ echo get_option('image_width'); }else {echo 500;} ?>" size="10">
                  &nbsp;&nbsp;&nbsp;<span><?php _e('Width','wpc') ?></span><br>
              </tr>
              <tr>
                <th scope="row"><label><?php _e('Thumbnail','wpc') ?></label></th>
                <td><input name="thumb_height" type="text" id="thumb_height" value="<?php if(get_option('thumb_height')){ echo get_option('thumb_height'); }else {echo 151;} ?>" size="10">
                  &nbsp;&nbsp;&nbsp;<span><?php _e('Height','wpc') ?></span>&nbsp;&nbsp;&nbsp;
                  <input name="thumb_width" type="text" id="thumb_width" value="<?php if(get_option('thumb_width')){ echo get_option('thumb_width'); }else {echo 212;} ?>" size="10">
                  &nbsp;&nbsp;&nbsp;<span><?php _e('Width','wpc') ?></span><br>
                  
              </tr>
              <tr valign="top">
                <th scope="row"><label for="pagination"><?php _e('Show Next/Prev Links','wpc') ?> </label></th>
                <td><input type="radio" name="next_prev" value="1" <?php if(get_option('next_prev')==1){echo 'checked="checked"';} ?> />
                  Yes &nbsp;&nbsp;&nbsp;
                  <input type="radio" name="next_prev" value="0" <?php if(get_option('next_prev')==0){echo 'checked="checked"';} ?>/>
                  No<br />
                  <span></span></td>
              </tr>
              <tr valign="top">
                <th scope="row"><label for="inn_temp_head"><?php _e('Inner Template Header','wpc') ?></label></th>
                <td><textarea name="inn_temp_head" cols="90" rows="7" class="widefat inn_temp"><?php echo get_option('inn_temp_head'); ?></textarea>
                  <small style="position:relative; top:-10px;"><?php _e('Use this area to add your theme layout divs if you are having problems with inner pages of the catalogue.','wpc') ?></small></td>
              </tr>
              <tr valign="top">
                <th scope="row"><label for="inn_temp_foot"><?php _e('Inner Template Footer','') ?></label></th>
                <td><textarea name="inn_temp_foot" cols="90" rows="7" class="widefat inn_temp"><?php echo get_option('inn_temp_foot'); ?></textarea>
                  <small style="position:relative; top:-10px;"><?php _e('Use this area to close the open divs after the WP Catalogue.','wpc') ?></small></td>
              </tr>
            </tbody>
          </table>
          <p class="submit">
            <input type="submit" name="submit" id="submit" class="button-primary" value="Save Changes">
          </p>
        </form>
        <br class="clear">
      </div>
      <br class="clear">
    </div>
  </div>
  <div class="wpc-right-liquid">
    <table cellpadding="0" class="widefat donation" style="margin-bottom:10px; border:solid 2px #008001;" width="50%">
      <thead>
      <th scope="col"><strong style="color:#008001;"><?php _e('Help Improve This Plugin!','wpc') ?></strong></th>
        </thead>
      <tbody>
        <tr>
          <td style="border:0;"><?php _e('Enjoyed this plugin? All donations are used to improve and further develop this plugin. Thanks for your contribution.','wpc') ?></td>
        </tr>
        <tr>
          <td style="border:0;"><form action="https://www.paypal.com/cgi-bin/webscr" method="post" target="_blank">
              <input type="hidden" name="cmd" value="_s-xclick">
              <input type="hidden" name="hosted_button_id" value="A74K2K689DWTY">
              <input type="image" src="https://www.paypalobjects.com/en_AU/i/btn/btn_donateCC_LG.gif" border="0" name="submit" alt="PayPal — The safer, easier way to pay online.">
              <img alt="" border="0" src="https://www.paypalobjects.com/en_AU/i/scr/pixel.gif" width="1" height="1">
            </form></td>
        </tr>
        <tr>
          <td style="border:0;"><?php _e('you can also help by','wpc') ?> <a href="http://wordpress.org/support/view/plugin-reviews/wp-catalogue" target="_blank"><?php _e('rating this plugin on wordpress.org','wpc') ?></a></td>
        </tr>
      </tbody>
    </table>
    <table cellpadding="0" class="widefat" border="0">
      <thead>
      <th scope="col"><?php _e('Need Support?','wpc') ?></th>
        </thead>
      <tbody>
        <tr>
          <td style="border:0;"><?php _e('Check out the','wpc') ?> <a href="http://wordpress.org/plugins/wp-catalogue/faq/" target="_blank">FAQs</a> <?php _e('and','wpc') ?> <a href="http://wordpress.org/support/plugin/wp-catalogue" target="_blank"><?php _e('Support Forums','wpc') ?></a></td>
        </tr>
      </tbody>
    </table>
  </div>
  <br class="clear">
</div>
