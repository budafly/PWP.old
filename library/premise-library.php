<?php
/**
 * Premise Library
 * @package Premise
 * @subpackage Premise Library
 * @link [url] [description]
 */

/**
 * Premise Print
 * @param var $var variable to print
 * @return string will print $var or 'Empty $var' wrapped in <pre> tags.
 */
function premise_print( $var ) {
	$var = ( is_array( $var ) && empty( $var ) ) ? 'Empty array()' : $var;
	$var = !empty($var) ? $var : 'Empty $var';
	echo '<pre style="display:block;margin:40px auto;width:90%;overflow:auto;"><code style="display:block;padding:20px;">';
	print_r( $var );
	echo '</code></pre>';
}

/**
 * @link [url] [description]
 * @param  array  $args array of arguments to buid a field
 * @return echo         html markup for a form field based on the arguments passed
 * 
 * @see class PremiseFormElements in premise-forms-class.php
 */
function premise_field( $args = array(), $echo = true ) {
	
	$form = new PremiseForm( $args );

	echo $form->html;

	// premise_print( $form );

	if( !is_array( $args ) ) return false;
	global $Premise_Form_Class;
	$html ='';
	if( array_key_exists( 'type', $args ) ) {
		$html .= $Premise_Form_Class->the_field( $args );
	}
	else {
		foreach ($args as $arg) $html .= $Premise_Form_Class->the_field( $arg );
	}
	if( !$echo )
		return $html;
	echo $html;
}

/**
 * Insert Background Fields
 * @param string $name required, the name attribute to assign to each field. Fields are saved in an array
 * @param string $title optional, label output for select dropdown
 * @param string $intro optional, a description for select dropdown
 * @return echo will echo upload fields to insert a background
 *
 * @see premise_the_background()
 */
function premise_save_background( $name ) {
	$field = get_option( $name );
	
	$background = array(
		'type'      => 'select',
		'label'     => 'Select Background Option',
		'tooltip'   => 'Set your Home Splash background.',
		'name'      => $name.'[bg]',
		'id'        => $name.'-bg',
		'value'     => $field['bg'],
		'placeholder' => 'Select Background',
		'attribute' => 'onchange="premiseSelectBackground(this);"',
		'options'   => array( 
			'Solid Background'    => 'color',
			'Gradient Background' => 'gradient',
			'Image Background'    => 'image', 
		),
	);

	$color = array(
		'type' => 'minicolors',
		'label' => 'Select a color',
		'name' => $name.'[color]',
		'id' => $name.'-color',
		'value' => $field['color'],
	);
	
	$gradient = array(
		array(
			'type' => 'minicolors',
			'label' => 'Start Gradient',
			'name' => $name.'[gradient][gradient-start]',
			'id' => $name.'-gradient-start',
			'value' => $field['gradient']['gradient-start'],
		),
		array(
			'type' => 'minicolors',
			'label' => 'Finish Gradient',
			'name' => $name.'[gradient][gradient-finish]',
			'id' => $name.'-gradient-finish',
			'value' => $field['gradient']['gradient-finish'],
		),
		array(
			'type' => 'radio',
			'name' => $name.'[gradient][gradient-dir]',
			'value' => $field['gradient']['gradient-dir'],
			'label' => 'Select Gradient Type',
			'options' => array(
				array(
					'label' => 'Linear',
					'id' => $name.'-gradient-linear',
					'value_att' => 'linear',
				),
				array(
					'label' => 'Radial',
					'id' => $name.'-gradient-radial',
					'value_att' => 'radial',
				),
			),
		),
		array(
			'type' => 'radio',
			'name' => $name.'[gradient][gradient-linear-dir]',
			'value' => $field['gradient']['gradient-linear-dir'],
			'label' => 'Select Gradient Type',
			'options' => array(
				array(
					'label' => 'Top to Bottom',
					'id' => $name.'-gradient-dir-ttb',
					'value_att' => 'ttb',
				),
				array(
					'label' => 'Left to Right',
					'id' => $name.'-gradient-dir-ltr',
					'value_att' => 'ltr',
				),
			),
		),
	);
	
	$image = array(
		array(
			'type' => 'file',
			'name' => $name.'[image][image]',
			'value' => $field['image']['image'],
			'label' => 'Upload Image',
			'tootltip' => 'You can also use a pattern background by simply uploading a pattern and choosing "Repeat" option next.',
		),
		array(
			'type' => 'select',
			'name' => $name.'[image][repeat]',
			'value' => $field['image']['repeat'],
			'label' => 'Repeat Background',
			'options' => array( 
				'Reapeat' => 'repeat',
				'Reapeat-X' => 'repeat-x',
				'Reapeat-Y' => 'repeat-y',
				'No Repeat' => 'no-repeat',
			),
		),
		array(
			'type' => 'select',
			'name' => $name.'[image][attach]',
			'value' => $field['image']['attach'],
			'label' => 'Background Attachment',
			'placeholder' => 'Select Attachment',
			'options' => array( 
				'Fixed' => 'fixed',
				'Scroll' => 'scroll',
			),
		),
		array(
			'type' => 'select',
			'name' => $name.'[image][position-x]',
			'value' => $field['image']['position-x'],
			'label' => 'Background Position-X',
			'options' => array( 
				'Right' => 'right',
				'Center' => 'center',
				'Left' => 'left',
			),
		),
		array(
			'type' => 'select',
			'name' => $name.'[image][position-y]',
			'value' => $field['image']['position-y'],
			'label' => 'Background Position-Y',
			'options' => array( 
				'Top' => 'top',
				'Center' => 'center',
				'Bottom' => 'bottom',
			),
		),
		array(
			'type' => 'select',
			'name' => $name.'[image][size]',
			'value' => $field['image']['size'],
			'label' => 'Background Size',
			'options' => array( 
				'Normal' => '',
				'Cover' => '/ cover',
				'Contain' => 'contain',
			),
		)
	);
	//ouput fields
	echo '<div class="premise-background-select row"><div class="col2">';
		premise_field( $background );
	echo '</div><div class="col2">';

	//color
	echo '<div class="block premise-background premise-color-background"', $field['bg'] !== 'color' ? 'style="display:none;"' : '', '>';
		premise_field( $color );
	echo '</div>';

	//gradient
	echo '<div class="block premise-background premise-gradient-background"', $field['bg'] !== 'gradient' ? 'style="display:none;"' : '', '>';
		premise_field( $gradient );
	echo '</div>';

	//image
	echo '<div class="block premise-background premise-image-background"', $field['bg'] !== 'image' ? 'style="display:none;"' : '', '>';
		premise_field( $image );
	echo '</div>';

	echo '</div></div>';
}

/**
 * Home Splash Styles
 * @param  string $option_name name of option key used to save the background
 * @param  bool   $echo 	   default true will echo styles. False will return styles
 * @return string              inline styles on element
 *
 * @see premise_save_background()
 */
function premise_the_background( $option_name, $echo = true ) {
	$bg = get_option( $option_name );
	$styles = '';

	switch( $bg['bg'] ) {
		case 'color' :
			$styles .= 'background: '.$bg['color'].';';
		break;

		case 'pattern' :
			$styles .= 'background: url('.$bg['pattern'].') repeat scroll top left;';
		break;

		case 'image' :
			$styles .= 'background: url('.$bg['image']['image'].') '.$bg['image']['repeat'].' '.$bg['image']['attach'].' '.$bg['image']['position-x'].' '.$bg['image']['position-x'].' '.$bg['image']['cover'].';';
		break;

		case 'gradient' :
			if( 'radial' == $bg['gradient']['gradient-dir'] ){
				$styles .= "
					background-color: ".$bg['gradient']['gradient-start'].";
					background: -webkit-gradient(radial, center center, 0, center center, 460, from(".$bg['gradient']['gradient-start']."), to(".$bg['gradient']['gradient-finish']."));
					background: -webkit-radial-gradient(circle, ".$bg['gradient']['gradient-start'].", ".$bg['gradient']['gradient-finish'].");
					background: -moz-radial-gradient(circle, ".$bg['gradient']['gradient-start'].", ".$bg['gradient']['gradient-finish'].");
					background: -ms-radial-gradient(circle, ".$bg['gradient']['gradient-start'].", ".$bg['gradient']['gradient-finish'].");";
			}
			else{
				if( 'ttb' == $bg['gradient']['gradient-linear-dir'] ){
					$styles .= "
						background-color: ".$bg['gradient']['gradient-start'].";
						background: -webkit-gradient(linear, 0% 0%, 0% 100%, from(".$bg['gradient']['gradient-start']."), to(".$bg['gradient']['gradient-finish']."));
						background: -webkit-linear-gradient(top, ".$bg['gradient']['gradient-start'].", ".$bg['gradient']['gradient-finish'].");
						background: -moz-linear-gradient(top, ".$bg['gradient']['gradient-start'].", ".$bg['gradient']['gradient-finish'].");
						background: -ms-linear-gradient(top, ".$bg['gradient']['gradient-start'].", ".$bg['gradient']['gradient-finish'].");
						background: -o-linear-gradient(top, ".$bg['gradient']['gradient-start'].", ".$bg['gradient']['gradient-finish'].");";
				}
				else{
					$styles .= "
						background-color: ".$bg['gradient']['gradient-start'].";
						background: -webkit-gradient(linear, left top, right top, from(".$bg['gradient']['gradient-start']."), to(".$bg['gradient']['gradient-finish']."));
						background: -webkit-linear-gradient(left, ".$bg['gradient']['gradient-start'].", ".$bg['gradient']['gradient-finish'].");
						background: -moz-linear-gradient(left, ".$bg['gradient']['gradient-start'].", ".$bg['gradient']['gradient-finish'].");
						background: -ms-linear-gradient(left, ".$bg['gradient']['gradient-start'].", ".$bg['gradient']['gradient-finish'].");
						background: -o-linear-gradient(left, ".$bg['gradient']['gradient-start'].", ".$bg['gradient']['gradient-finish'].");";
				}
			}
		break;
	}

	if( !$echo ) 
		return $styles;

	echo $styles;
}





function premise_load_ajax_markup() {
	$ajax_overlay = '<div id="premise-ajax-overlay" style="
		display:none;
		position:fixed;
		top:0;
		left:0;
		width:100%;
		height:100%;
		background-color:#FFFFFF;
		opacity:.6;
		z-index:9990;
		"></div>';

	$ajax_icon = '<div id="premise-ajax-loading" 
		class="absolute center" style="
		display:none;
		position:fixed;
		width:60px;
		top:40%;
		left:50%;
		margin-left:-30px;
		z-index:9991;
		"><i class="fa fa-3x fa-spinner fa-spin"></i></div>';

	$ajax_dialog = '<div id="premise-ajax-dialog" style="
		display:none;
		position:fixed;
		top:10%;
		left:10%;
		width:80%;
		height:80%;
		background-color:#FFFFFF;
		z-index:9992;
		overflow:auto;
		box-shadow: 0 0 5px #333333;
		-webkit-box-shadow: 0 0 5px #333333;
		-moz-box-shadow: 0 0 5px #333333;
		-ms-box-shadow: 0 0 5px #333333;
		-o-box-shadow: 0 0 5px #333333;
		padding:20px;
		" class="round-corners25"></div>';

	$ajax_control = '<a id="premise-ajax-close" style="
		display:none;
		position: fixed;
		padding: 2px 12px;
		top: 60px;
		right: 40px;
		background: #FFFFFF;
		z-index: 9995;
		line-height: 150%;
		font-size: 20px;
		color: #AAAAAA;
		border-radius: 24px;
		-webkit-border-radius: 24px;
		-moz-border-radius: 24px;
		-ms-border-radius: 24px;
		-o-border-radius: 24px;
		box-shadow: 0 0 5px #333333;
		-webkit-box-shadow: 0 0 5px #333333;
		-moz-box-shadow: 0 0 5px #333333;
		-ms-box-shadow: 0 0 5px #333333;
		-o-box-shadow: 0 0 5px #333333;
		" class="row" href="javascript:;" onclick="premiseAjaxClose();">x</a>';

	echo $ajax_overlay, $ajax_icon, $ajax_dialog, $ajax_control;
}




?>