<?php
/*---------------------------------------------------------------------------------*/
/* Google Maps widget */
/*---------------------------------------------------------------------------------*/
class CoLabs_googlemaps extends WP_Widget {

   function CoLabs_googlemaps() {
	   $widget_ops = array('description' => 'Add your Google Maps for the sidebar with this widget. This widget will automatic disabled when on single ad page.' );
       parent::WP_Widget(false, __('Colorlabs - Google Maps', 'colabsthemes'),$widget_ops);      
   }
   
   // output the content of the widget
	function widget($args, $instance) {		
		extract( $args );
		$width =$instance['width'];
		if($width=='')$width=290;
		$height =$instance['height'];
		if($height=='')$height=300;
		wp_reset_query();
		if(!is_singular('ad')){
			print $before_widget;
			if ($instance['title']) { print $before_title.$instance['title'].$after_title; }else {print $before_title.__('Our Map','colabsthemes').$after_title;}
			$mapcss = "
			#colabsmap {
			width:".$width."px; height:".$height."px;}
			#sidebar #colabsmap div {margin:0px;}
			#colabsmap .infoWindow {line-height:13px; font-size:10px;}
			#colabsmap input {margin:4px 4px 0 0; font-size:10px;}
			#colabsmap input.text {border:solid 1px #ccc; background-color:#fff; padding:2px;}
			";
			
			print "<style type='text/css'>\n";
			print $mapcss;
			print "\n</style>\n";
			print colabs_printmap($instance['lat'], $instance['lng'], $instance['zoom'], $instance['type'], $instance['content'], $instance['directionsto']);
			print "<div class='enlarge'><a href='http://maps.google.com/maps?ll=".$instance['lat'].",".$instance['lng']."&amp;z=".$instance['zoom']."&amp;t=m' target='_blank'>Enlarge</a></div>";
			print $after_widget;
		}	
	}
	
	// process widget options to be saved
	function update($new_instance, $old_instance) {		
		print_r($old_instance);
		print_r($new_instance);
		return $new_instance;
	}
	
	// output the options form on admin
	function form($instance) {
		global $wpdb;
		$title = esc_attr($instance['title']);
		$lat = esc_attr($instance['lat']);
		$lng = esc_attr($instance['lng']);
		$width = esc_attr($instance['width']);
		$height = esc_attr($instance['height']);
		$zoom = esc_attr($instance['zoom']);
		$type = esc_attr($instance['type']);
		$directionsto = esc_attr($instance['directionsto']);
		$content = esc_attr($instance['content']);
		?>
			<p>
			<label for="<?php print $this->get_field_id('title'); ?>"><?php _e('Title:'); ?></label>
			<input class="widefat" id="<?php print $this->get_field_id('title'); ?>" name="<?php print $this->get_field_name('title'); ?>" type="text" value="<?php print $title; ?>" />
			</p>
			<p>
			<label for="<?php print $this->get_field_id('lat'); ?>"><?php _e('Latitude:'); ?></label>
			<input class="widefat" id="<?php print $this->get_field_id('lat'); ?>" name="<?php print $this->get_field_name('lat'); ?>" type="text" value="<?php print $lat; ?>" />
			</p>
			<p>
			<label for="<?php print $this->get_field_id('lng'); ?>"><?php _e('Longitude:'); ?></label>
			<input class="widefat" id="<?php print $this->get_field_id('lng'); ?>" name="<?php print $this->get_field_name('lng'); ?>" type="text" value="<?php print $lng; ?>" />
			</p>
			<p>
			<label for="<?php print $this->get_field_id('width'); ?>"><?php _e('Width:'); ?></label>
			<input class="widefat" id="<?php print $this->get_field_id('width'); ?>" name="<?php print $this->get_field_name('width'); ?>" type="text" value="<?php print $width; ?>" />
			</p>
			<p>
			<label for="<?php print $this->get_field_id('height'); ?>"><?php _e('Height:'); ?></label>
			<input class="widefat" id="<?php print $this->get_field_id('height'); ?>" name="<?php print $this->get_field_name('height'); ?>" type="text" value="<?php print $height; ?>" />
			</p>
			<p>
			<label for="<?php print $this->get_field_id('zoom'); ?>"><?php _e('Zoom Level: <small>(1-19)</small>'); ?></label>
			<input class="widefat" id="<?php print $this->get_field_id('zoom'); ?>" name="<?php print $this->get_field_name('zoom'); ?>" type="text" value="<?php print $zoom; ?>" />
			</p>
			<p>
			<label for="<?php print $this->get_field_id('type'); ?>"><?php _e('Map Type:<br /><small>(ROADMAP, SATELLITE, HYBRID, TERRAIN)</small>'); ?></label>
			<input class="widefat" id="<?php print $this->get_field_id('type'); ?>" name="<?php print $this->get_field_name('type'); ?>" type="text" value="<?php print $type; ?>" />
			</p>
			<p>
			<label for="<?php print $this->get_field_id('directionsto'); ?>"><?php _e('Address for directions:'); ?></label>
			<input class="widefat" id="<?php print $this->get_field_id('directionsto'); ?>" name="<?php print $this->get_field_name('directionsto'); ?>" type="text" value="<?php print $directionsto; ?>" />
			</p>
			<p>
			<label for="<?php print $this->get_field_id('content'); ?>"><?php _e('Info Bubble Content:'); ?></label>
			<textarea rows="7" class="widefat" id="<?php print $this->get_field_id('content'); ?>" name="<?php print $this->get_field_name('content'); ?>"><?php print $content; ?></textarea>
			</p>
		<?php 
	}
   
} 
register_widget('CoLabs_googlemaps');


/*-----------------------------------------------------------------------------------*/
/*  colabs_printmap - Google Maps Widget Function   */
/*-----------------------------------------------------------------------------------*/
if (!function_exists('colabs_printmap')) {
function colabs_printmap($lat, $lng, $zoom, $type, $content, $directionsto) {
	
	if (!$lat) {$lat = '0';}
	if (!$lng) {$lng = '0';}
	if (!$zoom) {$zoom = 12;} // 1-19
	if (!$type) {$type = 'ROADMAP';} // ROADMAP, SATELLITE, HYBRID, TERRAIN
	if (!$content) {$content = '';}
	
	$content = str_replace('&lt;', '<', $content);
	$content = str_replace('&gt;', '>', $content);
	$content = mysql_escape_string($content);
	if ($directionsto) { $directionsForm = "<form method=\"get\" action=\"http://maps.google.com/maps\"><input type=\"hidden\" name=\"daddr\" value=\"".$directionsto."\" /><input type=\"text\" class=\"text\" name=\"saddr\" /><input type=\"submit\" class=\"submit\" value=\"Directions\" /></form>"; }

	return "
	<script type='text/javascript' src='http://maps.google.com/maps/api/js?sensor=false'></script>
	<script type='text/javascript'>
		function makeMap() {
			var latlng = new google.maps.LatLng(".$lat.", ".$lng.")
			
			var myOptions = {
				zoom: ".$zoom.",
				center: latlng,
				mapTypeControl: true,
				mapTypeControlOptions: {style: google.maps.MapTypeControlStyle.DROPDOWN_MENU},
				navigationControl: true,
				navigationControlOptions: {style: google.maps.NavigationControlStyle.SMALL},
				mapTypeId: google.maps.MapTypeId.".$type."
			};
			var map = new google.maps.Map(document.getElementById('colabsmap'), myOptions);
			
			var contentString = '<div class=\"infoWindow\">".$content.$directionsForm."</div>';
			var infowindow = new google.maps.InfoWindow({
				content: contentString
			});
			
			var marker = new google.maps.Marker({
				position: latlng,
				map: map,
				title: ''
			});
			
			google.maps.event.addListener(marker, 'click', function() {
			  infowindow.open(map,marker);
			});
		}
		window.onload = makeMap;
	</script>
	
	<div id='colabsmap' class='mapbox'></div>
	";

}}
?>