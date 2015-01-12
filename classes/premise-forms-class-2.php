<?php
/**
 * Tribus Form Class
 *
 * This class allows us to easily build form elements using aparameters within a PHP Array.
 *
 * @package Tribus Framework
 * @subpackage Forms Class
 */





/**
* 
*/
class PremiseForm {


	/**
	 * holds initial agrumnets passed to the class
	 * 
	 * @var array
	 */
	protected $args = array();


	
	

	/**
	 * Defaults for each field
	 * 
	 * @var array
	 */
	protected $deaults = array(
		'type' 	  		  => 'text',		//i.e. textarea, select, checkbox, file
		'name' 	  		  => '',
		'id' 	  		  => '',
		'label' 	  	  => '',
		'placeholder' 	  => '',  			//also used as select default option if not empty
		'tooltip' 	  	  => '',  			//displays balloon style tooltip
		'value' 	  	  => '',  			//value from database
		'value_att' 	  => '',  			//Used for checkboxes and radio fields. if this is equal to 'value' the field will be checked
		'class' 	  	  => '',  			//custom class for easy styling
		'insert_icon'	  => '', 			//insert a fontawesome icon
		'template'	  	  => 'default', 	//currently only option is 'raw'
		'options'		  => array(),		//holds different options depending on the type of field
		'attribute' 	  => '',			//Additional html attributes to add to element i.e. onchange="premiseSelectBackground()"
	);






	/**
	 * holds our field
	 * 
	 * @var array
	 */
	protected $field = array();




	

	/**
	 * Defaults if field section
	 *
	 * parsed in __construct()
	 * 
	 * @var array
	 */
	protected $field_section_defaults = array(
		'container' 	  => false, 		//output within parent container with class premise-field-section
		'container_title' => '',  			//if container is true displays title
		'container_desc'  => '',  			//if container is true displays description
		'container_class' => '', 			//if container is true displays additional classes on container
		'fields' 		  => array(), 		//if container is true this will hold multidimensional array with all fields
	);






	/**
	 * will hold our field section
	 * 
	 * @var array
	 */
	protected $field_section = array();






	/**
	 * holds our <label> markup including the tooltip if applicable
	 * 
	 * @var string
	 */
	protected $label = '';







	protected $field_class = '';






	/**
	 * will hold our button markup to our object assigned in prepare_field()
	 * 
	 * @var string
	 */
	protected $btn_upload_file;
	protected $btn_remove_file;
	protected $btn_choose_icon;
	protected $btn_remove_icon;






	/**
	 * Holds the html for this field(s)
	 * 
	 * @var string
	 */
	public $html = '';






	/**
	 * construct our object
	 * 
	 * @param array $args array holding one or more fields
	 */
	function __construct( $args ) {
		
		if( !empty( $args ) && is_array( $args ) )
			$this->args = $args;

		$this->form_init();

	}





	/**
	 * begin processing the field
	 */
	protected function form_init() {

		/**
		 * If container is true and has 'fields' key
		 */
		if( true === $this->args['container'] && array_key_exists( 'fields', $this->args ) ) {
			$this->field_section = wp_parse_args( $this->args, $this->field_section_defaults );

			$this->build_field_section();
		}
		/**
		 * if has 'options' key or is not multididemnsional array 
		 */
		elseif( ( array_key_exists( 'options', $this->args ) && !empty( $this->args['options'] ) ) || (count($this->args) == count($this->args, COUNT_RECURSIVE) ) ) {
			$this->field = wp_parse_args( $this->args, $this->defaults );
			
			$this->build_field();
		}
		/**
		 * is multidimensional array and container is false
		 */
		else{
			foreach ( $this->args as $field ) {
				$this->field = wp_parse_args( $field, $this->defaults );

				$this->build_field();
			}
		}

	}








	protected function build_field_section() {

		$html  = '<div class="field-section '.$this->field_section['container_class'].'">';

		$html .= !empty( $this->field_section['container_title'] ) ? '<h3>'.$this->field_section['container_title'].'</h3>' : '';
		$html .= !empty( $this->field_section['container_desc'] )  ? '<p>'.$this->field_section['container_desc'].'</p>' 	: '';

		foreach ( $this->field_section['fields'] as $field ) {
			$this->field = wp_parse_args( $field, $this->defaults );

			$html .= $this->build_field();
		}

		$html .= '</div>';

		$this->html .= $html;

	}







	protected function build_field() {

		$this->prepare_field();

		$html = '<div class="field';
		$html .= !empty( $this->field['class'] ) ? $this->field['class'].'">' : '">';

		$html .= $this->label;

		$html .= '<div class="'.$this->field['type'].'';
		$html .= !empty( $this->field['template'] ) ? $this->field['template'].'"' : '">';
		
		switch( $this->field['type'] ) {
			case 'select':
			case 'wp_dropdown_pages':
				$html .= $this->select_field();
				break;

			case 'textarea':
				$html .= $this->textarea();
				break;

			case 'checkbox':
				$html .= $this->checkbox();
				break;

			case 'radio':
				$html .= $this->radio();
				break;

			default:
				$html .= $this->input_field();
				break;
		}

		$html .= '</div></div>';

		$this->html .= $html;

	}







	protected function input_field() {

		$field  = '<input type="'. $this->field['type'] .'"';

		$field .= !empty( $this->field['name'] ) 		? 'name="'. $this->field['name'] .'"' 	: '';
		$field .= !empty( $this->field['id'] ) 			? 'id="'. $this->field['id'] .'"' 		: '';
		$field .= !empty( $this->field['value'] ) 		? 'value="'. $this->field['value'] .'"' : '';
		$field .= !empty( $this->field_class )			? 'class="'. $this->field_class .'"'	: '';
		$field .= !empty( $this->field['attribute'] ) 	? $this->field['attribute'] 			: '';
		
		$field .= '>';

		/**
		 * add buttons if file or fa-icon field
		 */
		switch( $this->field['type'] ) {
			case 'file':
				$field .= $this->btn_upload_file;
				$field .= $this->btn_remove_file;
				break;

			case 'fa-icon':
				$field .= $this->btn_choose_icon;
				$field .= $this->btn_remove_icon;
				break;
		}

		return $field;

	}








	protected function textarea() {
		
		$field = '<textarea ';

		$field .= !empty( $this->field['name'] ) ? 'name="'.$this->field['name'].'"' : '';
		$field .= !empty( $this->field['id'] ) ? 'id="'.$this->field['id'].'"' : '';
		$field .= !empty( $this->field['placeholder'] ) ? 'placeholder="'.$this->field['placeholder'].'"' : '';
		$field .= !empty( $this->field['attribute'] ) ? $this->field['attribute'] : '';

		$field .= '>'.$this->field['value'].'</textarea>';

		return $field;
	}







	protected function checkbox() {
		
		$field  = '<input type="'. $this->field['type'] .'"';
		
		$field .= !empty( $this->field['name'] ) 		? 'name="'. $this->field['name'] .'"' 		: '';
		$field .= !empty( $this->field['id'] ) 			? 'id="'. $this->field['id'] .'"' 			: '';
		$field .= !empty( $this->field['value_att'] ) 	? 'value="'. $this->field['value_att'] .'"' : '';
		$field .= !empty( $this->field['class'] ) 		? 'class="'. $this->field['class'] .'"' 	: '';
		$field .= !empty( $this->field['attribute'] ) 	? $this->field['attribute'] 				: '';

		$field .= checked( $this->field['value'], $this->field['value_att'], false );

		$field .= '>';

		$field .= '<label ';
		$field .= !empty( $this->field['id'] ) 			? 'for="'. $this->field['id'] .'"' 		: '';
		$field .= '>'. $this->options['label'] .'</label>';

		return $field;

	}







	protected function radio() {
		if( !empty( $this->field['options'] ) && is_array( $this->field['options'] ) ) {
			
			foreach ( $this->field['options'] as $radio ) {
				
				$field  = '<input type="'.$this->field['type'].'"';
				
				$field .= !empty( $this->field['attribute'] ) 	? $this->field['attribute'] 		: '';
				$field .= !empty( $this->field['name'] ) 		? 'name="'.$this->field['name'].'"' : '';
				$field .= !empty( $radio['id'] ) 				? 'id="'.$radio['id'].'"' 			: '';
				$field .= !empty( $radio['value_att'] ) 		? 'value="'.$radio['value_att'].'"' : '';
				
				$field .= checked( $this->field['value'], $radio['value_att'], false );

				$field .= '>';

				$field .= '<label ';
				$field .= !empty( $radio['id'] ) ? 'for="'.$radio['id'].'">' : '';
				$field .= $radio['label'].'</label>';

			}

		}
	}






	protected function select_field() {
		
		if( 'wp_dropdown_pages' == $this->field['type'] ) {
			$field = $this->do_wp_dropdown_pages();
		}
		else {
			$field  = '<select '.$this->field['attribute'].' name="'.$this->field['name'].'" id="'.$this->field['id'].'">';
			$field .= !empty( $this->field['placeholder'] ) ? '<option>'.$this->field['placeholder'].'</option>' : '';
			$field .= $this->select_options();
			$field .= '</select>';
		}

		return $field;
	}







	protected function select_options() {
		
		$options = '';

		if( is_array( $this->field['value'] ) ) {
			foreach ( $this->field['options'] as $key => $value ) {
				$options .= '<option  value="'.$value.'"';
				$options .= (is_array( $this->field['value'] ) && in_array( $value, $this->field['value'] ) ) ? 'selected' : '';
				$options .= '>'.$key.'</option>';
			}
		}
		else {
			foreach ($this->field['options'] as $key => $value) {
				$options .= '<option  value="'.$value.'"';
				$options .= selected( $this->field['value'], $value, false );
				$options .= '>'.$key.'</option>';
			}	
		}

		return $options;
	}








	protected function do_wp_dropdown_pages() {
		
		$new_defaults = array(  
			'depth' 				=> 0, 
			'child_of' 				=> 0,
    		'selected' 				=> $this->field['value'], 
    		'name' 					=> $this->field['name'],
    		'id' 					=> $this->field['id'],
    		'show_option_none' 		=> $this->field['placeholder'], 
    		'show_option_no_change' => '',
    		'option_none_value' 	=> '', 
    	);
		
		$this->field = wp_parse_args( $this->field, $new_defaults );

		/**
		 * Make sure this never gets echoed.
		 */
		$this->field['echo'] = 0;
		
		return wp_dropdown_pages( $this->field );
	}






	/**
	 * Prepare our field. This function assigns the values to the 
	 * class properties needed to build a particular field
	 */
	protected function prepare_field() {

		/**
		 * Set the field['type'] value
		 */
		switch( $this->field['type'] ) {
			case 'wp_dropdown_pages':
				$this->field['type'] = 'select';
				break;

			case 'minicolors':
				$this->field['type'] = 'text';
				$this->field_class = 'premise-minicolors';
				break;

			case 'file':
				$this->field_class = 'premise-file-url';
				$this->btn_upload_file = '<a class="premise-btn-upload" href="javascript:void(0);" onclick="premiseUploadFile(this, '.$multiple.', \''.$preview.'\')"><i class="fa fa-fw fa-upload"></i></a>';
				$this->btn_remove_file = '<a class="premise-btn-remove" href="javascript:void(0);" onclick="premiseRemoveFile(this)"><i class="fa fa-fw fa-times"></i></a>';
				break;

			case 'fa-icon':
				$this->field_class = 'premise-insert-icon';
				$this->btn_choose_icon = '<a href="javascript:;" class="premise-choose-icon" onclick="premiseChooseIcon(this);"><i class="fa fa-fw fa-th"></i></a>';
				$this->btn_remove_icon = '<a href="javascript:;" class="premise-remove-icon" onclick="premiseRemoveIcon(this);"><i class="fa fa-fw fa-times"></i></a>';
				break;

			case 'checkbox':
			case 'radio':
				$this->label  = !empty( $this->field['label'] ) 												? '<p class="label">'.$this->field['label'].'</p>' 							: '';
				$this->label .= ( !empty( $this->field['label'] ) && !empty( $this->field['tooltip'] ) ) 		? '<span class="tooltip"><i>'.$this->field['tooltip'].'</i></span>' 		: '';
				break;

			default :
				$this->label  = !empty( $this->field['label'] ) 												? '<label for="'.$this->field['id'].'">'.$this->field['label'].'</label>' 	: '';
				$this->label .= ( !empty( $this->field['label'] ) && !empty( $this->field['tooltip'] ) ) 		? '<span class="tooltip"><i>'.$this->field['tooltip'].'</i></span>' 		: '';
				break;
		}

	}






	



}
?>