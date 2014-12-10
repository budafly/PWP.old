<?php
/**
 * Premise Form Related Functions
 * @package Premise
 * @subpackage Premise Forms
 * @link [url] [description]
 */

/**
* Premise Form Elements
* @param array $args array of options to build field element
* @link http://fontawesome.io/icons/ FontAwesome for a list of icon code you can pass as a insert_icon parameter
* NOTE: //value attribute is used for checkbox and radio inputs, 
* where you want to compare a value (i.e. value="1") to a value 
* from the database to know if the checkbox or radio button was 
* clicked. 'value_att' allows you to do that.
* $form_builder = array( 
* 	array( 
* 		'' => '',
* 		'' => '',
* 	),
* 	array( 
* 		'' => '',
* 		'' => '',
* 	),
* 	array( 
* 		'' => '',
* 		'' => '',
* 	),
* 	array( 
* 		'' => '',
* 		'' => '',
* 	),
* 	array( 
* 		'' => '',
* 		'' => '',
* 	),
* 	array( 
* 		'' => '',
* 		'' => '',
* 	),
* );
*/
class Premise_Form_Class {
	//possible options and defaults
	public $defaults = array(
		'type' 	  		  => 'text',		//i.e. textarea, select, checkbox, file
		'name' 	  		  => '',
		'id' 	  		  => '',
		'label' 	  	  => '',
		'placeholder' 	  => '',  			//also used as select default option if not empty
		'tooltip' 	  	  => '',  			//displays balloon style tooltip
		'value' 	  	  => '',  			//value from database
		'value_att' 	  => '',  			//see NOTE above
		'class' 	  	  => '',  			//custom class for easy styling
		'insert_icon'	  => '', 			//insert a fontawesome icon
		'template'	  	  => 'default', 	//Some fields like fa-icons have a template option
		'container' 	  => false, 		//output within parent container
		'container_title' => '',  			//if container is true displays title
		'container_desc'  => '',  			//if container is true displays description
		'options'		  => '',			//for select fields pass array or function
		'attribute' 	  => '',			//Additional html attributes to add to element i.e. onchange="premiseSelectBackground()"
	);
	public $field;							//array (of array) holds all parrameters
	public $html_markup = '';				//string for html output
	public $label = '';
	public $placeholder = '';
	public $checked = '';
	public $fa_icons;

	/**
	 * @param  array  $args options to build filed
	 * @return $this->html_markup         html markup for each field
	 */
	public function the_field( $args = array() ) {
		if( !is_array( $args ) )
			return false;

		$this->html_markup = '';

		$this->field = wp_parse_args( $args, $this->defaults );

		$this->build_field();

		return $this->html_markup;
	}

	public function build_field() {
		$this->setup_field();

		$this->begin_field();

		$this->html_markup .= '<div class="field '.$this->field['class'].'">';
		$this->html_markup .= $this->label;
		$this->html_markup .= $this->tooltip;
			
		switch( $this->field['type'] ) {
			case 'select':
			case 'wp_dropdown_pages':
				$this->html_markup .= ( $this->field['template'] !== 'raw' ) ? '<div class="select">' : '';
				$this->select_field();
				$this->html_markup .= ( $this->field['template'] !== 'raw' ) ? '</div>' : '';
				break;

			case 'textarea':
				$this->html_markup .= ( $this->field['template'] !== 'raw' ) ? '<div class="'.$this->field['type'].'">' : '';
				$this->textarea();
				$this->html_markup .= ( $this->field['template'] !== 'raw' ) ? '</div>' : '';
				break;

			case 'fa-icon':
				$this->html_markup .= '<div class="'.$this->field['type'].' '.$this->field['template'].'">';
				$this->input_field();
				$this->html_markup .= '</div>';
				break;

			case 'file':
				$this->html_markup .= '<div class="'.$this->field['type'].' '.$this->field['template'].'">';
				$this->input_field();
				$this->html_markup .= '</div>';
				break;

			case 'color':
			case 'minicolors':
				$this->html_markup .= '<div class="color '.$this->field['template'].'">';
				$this->input_field();
				$this->html_markup .= '</div>';
				break;

			default:
				$this->html_markup .= ( $this->field['template'] !== 'raw' ) ? '<div class="'.$this->field['type'].'">' : '';
				$this->input_field();
				$this->html_markup .= ( $this->field['template'] !== 'raw' ) ? '</div>' : '';
		}

		$this->html_markup .= '</div>';
		
		$this->end_field();
	}

	public function checkbox() {
		$this->html_markup .= '
			<input type="'.$this->field['type'].'" '.$this->field['attribute'].' name="'.$this->field['name'].'" id="'.$this->field['id'].'" value="'.$this->field['value_att'].'" '.$this->checked.'>
			<label for="'.$this->field['id'].'"></label>';
	}

	public function radio() {
		if( !empty( $this->field['options'] ) && is_array( $this->field['options'] ) ) {
			foreach ($this->field['options'] as $radio) {
				$active = checked( $this->field['value'], $radio['value_att'], false );
				$this->html_markup .= '<input type="'.$this->field['type'].'" '.$this->field['attribute'].' name="'.$this->field['name'].'" id="'.$radio['id'].'" value="'.$radio['value_att'].'" '.$active.'>';
				$this->html_markup .= '<label for="'.$radio['id'].'">'.$radio['label'].'</label>';
			}
		}
		else {
			$active = checked( $this->field['value'], $this->field['value_att'], false );
			$this->html_markup .= '<input type="'.$this->field['type'].'" '.$this->field['attribute'].' name="'.$this->field['name'].'" id="'.$this->field['id'].'" value="'.$this->field['value_att'].'" '.$active.'>';
			$this->html_markup .= '<label for="'.$this->field['id'].'">'.$this->field['label'].'</label>';
		}
	}
	
	/**
	 * @param  array $this->field options
	 * @return $this->html_markup         	  html markup for input fields
	 */
	public function input_field() {
		$placeholder = $this->field['placeholder'] ? ' placeholder="'.$this->field['placeholder'].'"' : '';

		switch ( $this->field['type'] ) {
			case 'checkbox':
				$this->checkbox();
				break;

			case 'radio':
				$this->radio();
				break;

			case 'file':
				$this->html_markup .='
					<input type="text" '.$this->placeholder.' '.$this->field['attribute'].' name="'.$this->field['name'].'" id="'.$this->field['id'].'" value="'.$this->field['value'].'" class="premise-file-url">
					<a class="premise-btn-upload" href="javascript:void(0);" onclick="premiseUploadFile(jQuery(this))"><i class="fa fa-fw fa-upload"></i></a>
					<a class="premise-btn-remove" href="javascript:void(0);" onclick="premiseRemoveFile(jQuery(this))"><i class="fa fa-fw fa-times"></i></a>';
				break;

			case 'fa-icon':
				$this->html_markup .= '
					<input type="text" '.$this->field['attribute'].' name="'.$this->field['name'].'" id="'.$this->field['id'].'" class="premise-insert-icon" value="'.$this->field['value'].'">
					<a href="javascript:;" class="premise-choose-icon"><i class="fa fa-fw fa-th"></i></a>
					<a href="javascript:;" class="premise-remove-icon"><i class="fa fa-fw fa-times"></i></a>';
					$this->fa_icons();
				break;

			case 'color':
				$this->html_markup .= '
					<input type="'.$this->field['type'].'" '.$this->field['attribute'].' name="'.$this->field['name'].'" id="'.$this->field['id'].'" value="'.$this->field['value'].'"'.$this->placeholder.'>';
				break;

			case 'minicolors':
				$this->html_markup .= '
					<input type="text" '.$this->field['attribute'].' name="'.$this->field['name'].'" id="'.$this->field['id'].'" value="'.$this->field['value'].'" class="premise-minicolors">';
				break;

			default:
				$this->html_markup .= '
					<input type="'.$this->field['type'].'" '.$this->field['attribute'].' name="'.$this->field['name'].'" id="'.$this->field['id'].'" value="'.$this->field['value'].'"'.$this->placeholder.'>';
				break;
		}
	}

	/**
	 * @param  array $this->field options
	 * @return $this->html_markup         html markup for select dropdowns
	 */
	public function select_field() {
		if( $this->field['type'] == 'wp_dropdown_pages' ) {
			$this->do_wp_dropdown_pages();
		}
		else {
			$this->html_markup .= '<select '.$this->field['attribute'].' name="'.$this->field['name'].'" id="'.$this->field['id'].'">';
			$this->html_markup .= $this->field['placeholder'] ? '<option>'.$this->field['placeholder'].'</option>' : '';
			$this->html_markup .= $this->options();
			$this->html_markup .= '</select>';
		}
	}

	/**
	 * @param  array $this->field options
	 * @return $this->html_markup         html markup for textarea elements
	 */
	public function textarea() {
		$this->html_markup .= '
			<textarea '.$this->field['attribute'].' name="'.$this->field['name'].'" id="'.$this->field['id'].'"'.$this->placeholder.'>'.$this->field['value'].'</textarea>';
	}

	/**
	 * @param  array $this->field options
	 * @return $this->html_markup         html markup for icons grid
	 */
	public function fa_icons() {
		$this->html_markup .= '<div class="fa-all-icons" style="display:none;"><ul>';
		foreach ($this->fa_icons as $icon) 
			$this->html_markup .= '<li class="inline-block float-left"><a href="javascript:;" class="this-icon" '.$this->field['attribute'].' data-icon="'.$icon.'"><i class="fa fa-fw '.$icon.'"></i></a></li>';
		$this->html_markup .= '</ul></div>';
	}

	/**
	 * list options for select field
	 * @return string HTML will output the options for select field
	 */
	public function options() {
		foreach ( $this->field['options'] as $key => $value) {
			$selected = selected( $this->field['value'], $value, false );
			$this->html_markup .= '<option  value="'.$value.'" '.$selected.'>'.$key.'</option>';
		}
	}

	/**
	 * display dropdown of wordpress pages
	 * @return HTML for select dropdown of WP pages using IDs as values for each option
	 */
	public function do_wp_dropdown_pages() {
		$new_defaults = array(  'depth' 				=> 0, 
								'child_of' 				=> 0,
		                		'selected' 				=> $this->field['value'], 
		                		'echo' 					=> 0,
		                		'name' 					=> $this->field['name'],
		                		'id' 					=> $this->field['id'],
		                		'show_option_none' 		=> $this->field['placeholder'], 
		                		'show_option_no_change' => '',
		                		'option_none_value' 	=> '' );
		$this->field = wp_parse_args( $this->field, $new_defaults );
		$this->html_markup .= wp_dropdown_pages( $this->field );
	}

	/**
	 * @param  array $this->field options
	 * @return $this->html_markup         html markup for begining of fields
	 */
	public function begin_field() {
		if( $this->field['container'] )
			$this->html_markup .= '<div class="field-section">';

		if( $this->field['container'] && !empty( $this->field['container_title'] ) )
			$this->html_markup .= '<h3>'.$this->field['container_title'].'</h3>';

		if( $this->field['container'] && !empty( $this->field['container_desc'] ) )
			$this->html_markup .= '<p>'.$this->field['container_desc'].'</p>';
	}

	/**
	 * @param  array $this->field options
	 * @return $this->html_markup         html markup to close field
	 */
	public function end_field() {
		if( $this->field['container'] )
			$this->html_markup .= '</div>';
	}

	protected function setup_field() {
		$this->tooltip     = !empty( $this->field['tooltip'] ) ? '<span class="tooltip"><i>'.$this->field['tooltip'].'</i></span>' : '';
		$this->placeholder = !empty( $this->field['placeholder'] ) ? ' placeholder="'.$this->field['placeholder'].'"' : '';
		$this->label       = !empty( $this->field['label'] ) ? '<label for="'.$this->field['id'].'">'.$this->field['label'].'</label>' : '';

		switch ( $this->field['type'] ) {
			case 'checkbox':
				$this->checked = checked( $this->field['value'], $this->field['value_att'], false );
				break;

			case 'radio':
			case 'checkbox':
				$this->label = !empty( $this->field['label'] ) ? '<p class="label">'.$this->field['label'].'</p>' : '';
				break;

			case 'fa-icon':
				$this->fa_icons = array('fa-adjust','fa-adn','fa-align-center','fa-align-justify','fa-align-left','fa-align-right','fa-ambulance','fa-anchor','fa-android','fa-angellist','fa-angle-double-down','fa-angle-double-left','fa-angle-double-right','fa-angle-double-up','fa-angle-down','fa-angle-left','fa-angle-right','fa-angle-up','fa-apple','fa-archive','fa-area-chart','fa-arrow-circle-down','fa-arrow-circle-left','fa-arrow-circle-o-down','fa-arrow-circle-o-left','fa-arrow-circle-o-right','fa-arrow-circle-o-up','fa-arrow-circle-right','fa-arrow-circle-up','fa-arrow-down','fa-arrow-left','fa-arrow-right','fa-arrow-up','fa-arrows','fa-arrows-alt','fa-arrows-h','fa-arrows-v','fa-asterisk','fa-at','fa-automobile','fa-backward','fa-ban','fa-bank','fa-bar-chart','fa-bar-chart-o','fa-barcode','fa-bars','fa-beer','fa-behance','fa-behance-square','fa-bell','fa-bell-o','fa-bell-slash','fa-bell-slash-o','fa-bicycle','fa-binoculars','fa-birthday-cake','fa-bitbucket','fa-bitbucket-square','fa-bitcoin','fa-bold','fa-bolt','fa-bomb','fa-book','fa-bookmark','fa-bookmark-o','fa-briefcase','fa-btc','fa-bug','fa-building','fa-building-o','fa-bullhorn','fa-bullseye','fa-bus','fa-cab','fa-calculator','fa-calendar','fa-calendar-o','fa-camera','fa-camera-retro','fa-car','fa-caret-down','fa-caret-left','fa-caret-right','fa-caret-square-o-down','fa-caret-square-o-left','fa-caret-square-o-right','fa-caret-square-o-up','fa-caret-up','fa-cc','fa-cc-amex','fa-cc-discover','fa-cc-mastercard','fa-cc-paypal','fa-cc-stripe','fa-cc-visa','fa-certificate','fa-chain','fa-chain-broken','fa-check','fa-check-circle','fa-check-circle-o','fa-check-square','fa-check-square-o','fa-chevron-circle-down','fa-chevron-circle-left','fa-chevron-circle-right','fa-chevron-circle-up','fa-chevron-down','fa-chevron-left','fa-chevron-right','fa-chevron-up','fa-child','fa-circle','fa-circle-o','fa-circle-o-notch','fa-circle-thin','fa-clipboard','fa-clock-o','fa-close','fa-cloud','fa-cloud-download','fa-cloud-upload','fa-cny','fa-code','fa-code-fork','fa-codepen','fa-coffee','fa-cog','fa-cogs','fa-columns','fa-comment','fa-comment-o','fa-comments','fa-comments-o','fa-compass','fa-compress','fa-copy','fa-copyright','fa-credit-card','fa-crop','fa-crosshairs','fa-css3','fa-cube','fa-cubes','fa-cut','fa-cutlery','fa-dashboard','fa-database','fa-dedent','fa-delicious','fa-desktop','fa-deviantart','fa-digg','fa-dollar','fa-dot-circle-o','fa-download','fa-dribbble','fa-dropbox','fa-drupal','fa-edit','fa-eject','fa-ellipsis-h','fa-ellipsis-v','fa-empire','fa-envelope','fa-envelope-o','fa-envelope-square','fa-eraser','fa-eur','fa-euro','fa-exchange','fa-exclamation','fa-exclamation-circle','fa-exclamation-triangle','fa-expand','fa-external-link','fa-external-link-square','fa-eye','fa-eye-slash','fa-eyedropper','fa-facebook','fa-facebook-square','fa-fast-backward','fa-fast-forward','fa-fax','fa-female','fa-fighter-jet','fa-file','fa-file-archive-o','fa-file-audio-o','fa-file-code-o','fa-file-excel-o','fa-file-image-o','fa-file-movie-o','fa-file-o','fa-file-pdf-o','fa-file-photo-o','fa-file-picture-o','fa-file-powerpoint-o','fa-file-sound-o','fa-file-text','fa-file-text-o','fa-file-video-o','fa-file-word-o','fa-file-zip-o','fa-files-o','fa-film','fa-filter','fa-fire','fa-fire-extinguisher','fa-flag','fa-flag-checkered','fa-flag-o','fa-flash','fa-flask','fa-flickr','fa-floppy-o','fa-folder','fa-folder-o','fa-folder-open','fa-folder-open-o','fa-font','fa-forward','fa-foursquare','fa-frown-o','fa-futbol-o','fa-gamepad','fa-gavel','fa-gbp','fa-ge','fa-gear','fa-gears','fa-gift','fa-git','fa-git-square','fa-github','fa-github-alt','fa-github-square','fa-gittip','fa-glass','fa-globe','fa-google','fa-google-plus','fa-google-plus-square','fa-google-wallet','fa-graduation-cap','fa-group','fa-h-square','fa-hacker-news','fa-hand-o-down','fa-hand-o-left','fa-hand-o-right','fa-hand-o-up','fa-hdd-o','fa-header','fa-headphones','fa-heart','fa-heart-o','fa-history','fa-home','fa-hospital-o','fa-html5','fa-ils','fa-image','fa-inbox','fa-indent','fa-info','fa-info-circle','fa-inr','fa-instagram','fa-institution','fa-ioxhost','fa-italic','fa-joomla','fa-jpy','fa-jsfiddle','fa-key','fa-keyboard-o','fa-krw','fa-language','fa-laptop','fa-lastfm','fa-lastfm-square','fa-leaf','fa-legal','fa-lemon-o','fa-level-down','fa-level-up','fa-life-bouy','fa-life-buoy','fa-life-ring','fa-life-saver','fa-lightbulb-o','fa-line-chart','fa-link','fa-linkedin','fa-linkedin-square','fa-linux','fa-list','fa-list-alt','fa-list-ol','fa-list-ul','fa-location-arrow','fa-lock','fa-long-arrow-down','fa-long-arrow-left','fa-long-arrow-right','fa-long-arrow-up','fa-magic','fa-magnet','fa-mail-forward','fa-mail-reply','fa-mail-reply-all','fa-male','fa-map-marker','fa-maxcdn','fa-meanpath','fa-medkit','fa-meh-o','fa-microphone','fa-microphone-slash','fa-minus','fa-minus-circle','fa-minus-square','fa-minus-square-o','fa-mobile','fa-mobile-phone','fa-money','fa-moon-o','fa-mortar-board','fa-music','fa-navicon','fa-newspaper-o','fa-openid','fa-outdent','fa-pagelines','fa-paint-brush','fa-paper-plane','fa-paper-plane-o','fa-paperclip','fa-paragraph','fa-paste','fa-pause','fa-paw','fa-paypal','fa-pencil','fa-pencil-square','fa-pencil-square-o','fa-phone','fa-phone-square','fa-photo','fa-picture-o','fa-pie-chart','fa-pied-piper','fa-pied-piper-alt','fa-pinterest','fa-pinterest-square','fa-plane','fa-play','fa-play-circle','fa-play-circle-o','fa-plug','fa-plus','fa-plus-circle','fa-plus-square','fa-plus-square-o','fa-power-off','fa-print','fa-puzzle-piece','fa-qq','fa-qrcode','fa-question','fa-question-circle','fa-quote-left','fa-quote-right','fa-ra','fa-random','fa-rebel','fa-recycle','fa-reddit','fa-reddit-square','fa-refresh','fa-remove','fa-renren','fa-reorder','fa-repeat','fa-reply','fa-reply-all','fa-retweet','fa-rmb','fa-road','fa-rocket','fa-rotate-left','fa-rotate-right','fa-rouble','fa-rss','fa-rss-square','fa-rub','fa-ruble','fa-rupee','fa-save','fa-scissors','fa-search','fa-search-minus','fa-search-plus','fa-send','fa-send-o','fa-share','fa-share-alt','fa-share-alt-square','fa-share-square','fa-share-square-o','fa-shekel','fa-sheqel','fa-shield','fa-shopping-cart','fa-sign-in','fa-sign-out','fa-signal','fa-sitemap','fa-skype','fa-slack','fa-sliders','fa-slideshare','fa-smile-o','fa-soccer-ball-o','fa-sort','fa-sort-alpha-asc','fa-sort-alpha-desc','fa-sort-amount-asc','fa-sort-amount-desc','fa-sort-asc','fa-sort-desc','fa-sort-down','fa-sort-numeric-asc','fa-sort-numeric-desc','fa-sort-up','fa-soundcloud','fa-space-shuttle','fa-spinner','fa-spoon','fa-spotify','fa-square','fa-square-o','fa-stack-exchange','fa-stack-overflow','fa-star','fa-star-half','fa-star-half-empty','fa-star-half-full','fa-star-half-o','fa-star-o','fa-steam','fa-steam-square','fa-step-backward','fa-step-forward','fa-stethoscope','fa-stop','fa-strikethrough','fa-stumbleupon','fa-stumbleupon-circle','fa-subscript','fa-suitcase','fa-sun-o','fa-superscript','fa-support','fa-table','fa-tablet','fa-tachometer','fa-tag','fa-tags','fa-tasks','fa-taxi','fa-tencent-weibo','fa-terminal','fa-text-height','fa-text-width','fa-th','fa-th-large','fa-th-list','fa-thumb-tack','fa-thumbs-down','fa-thumbs-o-down','fa-thumbs-o-up','fa-thumbs-up','fa-ticket','fa-times','fa-times-circle','fa-times-circle-o','fa-tint','fa-toggle-down','fa-toggle-left','fa-toggle-off','fa-toggle-on','fa-toggle-right','fa-toggle-up','fa-trash','fa-trash-o','fa-tree','fa-trello','fa-trophy','fa-truck','fa-try','fa-tty','fa-tumblr','fa-tumblr-square','fa-turkish-lira','fa-twitch','fa-twitter','fa-twitter-square','fa-umbrella','fa-underline','fa-undo','fa-university','fa-unlink','fa-unlock','fa-unlock-alt','fa-unsorted','fa-upload','fa-usd','fa-user','fa-user-md','fa-users','fa-video-camera','fa-vimeo-square','fa-vine','fa-vk','fa-volume-down','fa-volume-off','fa-volume-up','fa-warning','fa-wechat','fa-weibo','fa-weixin','fa-wheelchair','fa-wifi','fa-windows','fa-won','fa-wordpress','fa-wrench','fa-xing','fa-xing-square','fa-yahoo','fa-yelp','fa-yen','fa-youtube','fa-youtube-play','fa-youtube-square');
				break;
		}
	}
}
?>