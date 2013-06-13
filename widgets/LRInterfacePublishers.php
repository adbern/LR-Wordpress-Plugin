<?php

class LRInterfacePublishers extends WP_Widget
{
  function LRInterfacePublishers()
  {
    $widget_ops = array('classname' => 'LRInterfacePublishers', 'description' => 'Displays a searchable list of LR publishers.' );
    $this->WP_Widget('LRInterfacePublishers', 'LR Interface Publishers List', $widget_ops);
  }
 
  function form($instance)
  {
    $instance = wp_parse_args( (array) $instance, array( 'title' => '') );
    $title = empty($instance['title']) ? "Publishers" : $instance['title'];

?>
<p>
	<label for="<?php echo $this->get_field_id('title'); ?>">
		Title: 
	</label>
	<input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo attribute_escape($title); ?>" />
	<br/><br/>	
</p>
<?php
  }
 
  function update($new_instance, $old_instance)
  {
    $instance = $old_instance;
    $instance['title'] = $new_instance['title'];
    return $instance;
  }
 
  function widget($args, $instance)
  {
	$options = get_option('lr_options_object');	
	
	$host  = empty($options['host']) ? "http://12.109.40.31" : $options['host'];	
	extract($args, EXTR_SKIP);
	
	echo $before_widget;
	?>
	<div style="width:100%;clear:both;float:left;">
		<ul style="width:48%;float:left;" data-bind="foreach: publishers.slice(0, publishers().length*.5)">
			<li style="margin-bottom: 10px;">
				<a data-bind="text: $data.length>27?$data.substr(0,27)+'...':$data, attr: {href: $root.fixPublisherURL($data)}"></a><br/>
			</li>
		</ul>
		<ul style="width:48%;float:right;" data-bind="foreach: publishers.slice(publishers().length*.5, publishers().length)">
			<li style="margin-bottom: 10px;">
				<a data-bind="text: $data.length>27?$data.substr(0,27)+'...':$data, attr: {href: $root.fixPublisherURL($data)}"></a><br/>
			</li>
		</ul>
	</div>
	<div class="pubSpinner" style="width:100%;">
	
	</div>
	<div>
		<!-- ko if: loadMore -->
			<button data-bind="click: previous, visible:publishers().length>0 && page() > 0">Previous</button>
			<span data-bind="text:'Page: ' + (page() + 1)">&nbsp;</span>
			<button data-bind="click: load, visible:publishers().length>0">Next</button>
		<!-- /ko -->
	</div>
		<script type="text/javascript">
			var serviceHost = "<?php echo $host; ?>";
			//var publishersSpin = new Spinner(opts).spin($('.pubSpinner')[0]);
			<?php include_once('templates/scripts/applicationPreview.php'); ?>
			
			self.publishers(['Abraham Lincoln Bicentennial Commission', 'Bureau of Land Management, Department of the Interior', 'Centers for Disease Control and Prevention', 'Central Intelligence Agency', 'Consumer Product Safety Commission', 'Department of Agriculture', 'Department of Army', 'Department of Commerce', 'Department of Commerce, International Trade Administration', 'Department of Education', 'Department of Energy', 'Department of Health and Human Services', 'Department of Homeland Security', 'Department of Housing and Urban Development', 'Department of Justice', 'Department of Labor', 'Department of Navy', 'Department of State', 'Department of the Interior', 'Department of the Treasury', 'Department of Veterans Affairs', 'Environmental Protection Agency', 'Federal Bureau of Investigation', 'Federal Deposit Insurance Corporation', 'Federal Emergency Management Agency', 'Federal Judicial Center', 'Federal Trade Commission', 'Fish and Wildlife Service, Department of Interior', 'Food and Drug Administration', 'General Services Administration', 'Government Printing Office', 'Holocaust Memorial Museum', 'House of Representatives', 'Institute of Museum and Library Services', 'Internal Revenue Service', 'Library of Congress', 'Multiple Agencies', 'National Academy of Sciences', 'National Aeronautics and Space Administration', 'National Archives and Records Administration', 'National Constitution Center', 'National Endowment for the Arts', 'National Endowment for the Humanities', 'National Gallery of Art', 'National Institute of Standards and Technology', 'National Institutes of Health', 'National Library of Medicine', 'National Oceanic and Atmospheric Administration', 'National Park Service', 'National Park Service, Teaching with Historic Places', 'National Science Foundation', 'National Security Agency', 'Office of Naval Research', 'Peace Corps', 'Securities and Exchange Commission', 'Small Business Administration', 'Smithsonian Institution', 'The Federal Reserve', 'The White House', 'U.S. Agency for International Development', 'U.S. Census Bureau', 'U.S. Courts', 'U.S. Geological Survey', 'U.S. Global Change Research Program', 'U.S. Institute of Peace', 'U.S. Mint, Treasury']);
			
			$(function(){
				//self.load();
			});

		</script>
	
	<?php
		echo $after_widget;
		return;
  }
} 

