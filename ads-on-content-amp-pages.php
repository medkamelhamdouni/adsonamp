<?php
/*
Plugin Name:  Ads on Content AMP Pages
Description:  Insert Ads WithIn your Post Content AMP Pages in WordPress, To Make Money from AMP .
Version:      1.0
Author:       hamdouni kamel
License:      GPL2
License URI:  https://www.gnu.org/licenses/gpl-2.0.html
*/

function aacap_aadclient(){             ?>
        <input type="text" name="aadclient" size="30" value="<?php echo get_option('aadclient'); ?>" />
    <?php
}
function aacap_aadslot336_280(){        ?>
        <input type="text" name="aadslot336_280" size="30" value="<?php echo get_option('aadslot336_280'); ?>" />
    <?php
}
function aacap_aadslot90_200(){         ?>
        <input type="text" name="aadslot90_200" size="30" value="<?php echo get_option('aadslot90_200'); ?>" />
    <?php
}
function aacap_aadcontent_num(){        ?>
        <input type="number" name="aadcontent_num" size="5" value="<?php echo get_option('aadcontent_num'); ?>" />
    <?php
}

function aacap_ads_function(){
    add_settings_section("section", "", null, "aads-options");
    
    add_settings_field("aads_client", "AD Client <small>like : ca-pub-00000000000</small>", "aacap_aadclient", "aads-options", "section");
    add_settings_field("aads_slot_336_280", "AD Slot 336 * 280", "aacap_aadslot336_280", "aads-options", "section");
    add_settings_field("aads_slot_90_200", "AD Slot 90 * 200", "aacap_aadslot90_200", "aads-options", "section");
    add_settings_field("aads_slot_content", "ADS Content <small>After Number Paragraphs</small>", "aacap_aadcontent_num", "aads-options", "section");
    
    register_setting("section", "aadclient");
    register_setting("section", "aadslot336_280");
    register_setting("section", "aadslot90_200");
    register_setting("section", "aadcontent_num");
}

add_action("admin_init", "aacap_ads_function");



function aacap_ads_content_amp_options(){
?>
    <div class="wrap">
        <h2>Ads on Content AMP</h2>
        <form method="post" action="options.php">
          <?php
            settings_fields("section");
            do_settings_sections("aads-options");      
            submit_button();
          ?> 
            <br /><br />
        </form>
        <br /><br />
    </div>
<?php
}


function aacap_ads_content_amp(){
    add_options_page('Ads Content AMP', 'Ads Content AMP', 'manage_options', 'aacap_ads_content_amp','aacap_ads_content_amp_options');
}
add_action('admin_menu', 'aacap_ads_content_amp');



function aacap_ads_content_run($content){
	if (!is_single()) return $content;

  $aadclient      = (int)get_option('aadclient');
  $aadslot336_280 = (int)get_option('aadslot336_280');
  $aadslot90_200  = (int)get_option('aadslot90_200');
  $aadcontent_num = (int)get_option('aadcontent_num');


  if (!empty($aadslot336_280)){

    $a_ad_336_280 = '<div class="amp-ad-wrapper">
      <amp-ad width=336 height=280
          type="adsense"
          data-ad-client="'.$aadclient.'"
          data-ad-slot="'.$aadslot336_280.'">
      </amp-ad></div>';

      $a_ad_90_200 = '<div class="amp-ad-wrapper">
      <amp-ad width=200 height=90
          type="adsense"
          data-ad-client="'.$aadclient.'"
          data-ad-slot="'.$aadslot90_200.'">
      </amp-ad>
      <amp-ad width=200 height=90
          type="adsense"
          data-ad-client="'.$aadclient.'"
          data-ad-slot="'.$aadslot90_200.'">
      </amp-ad></div>';

    //$count = substr_count( $content, '</p>' );

    //Enter number of paragraphs to display ad after.
    $paragraphAfter = (empty($aadcontent_num)) ? 21 : $aadcontent_num ;

    $roundParagraph = round($paragraphAfter / 2 );
    
    $content = explode("</p>", $content);
    $new_content = '';
    
    for ($i = 0; $i < count($content); $i++) {
      if ($i % $paragraphAfter == 0) {
        $number = $i;
        $new_content.= $a_ad_336_280;
      }

      if (!empty($aadslot90_200)) {        
        if ($i == ($number + $roundParagraph)) {
          $new_content.= $a_ad_90_200;
        }
      }
      $new_content.= $content[$i] . "</p>";
    }
    $new_content.= $a_ad_336_280;
  }else
    return $content;

  return $new_content;
}
add_filter('the_content', 'aacap_ads_content_run');