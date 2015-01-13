


# Premise WP Framework  



Premise is a Wordpress Framework built to help developers write faster Themes and Plugins that are fully custom.


Here are some of the most commonly used functionality that Premise offfers. You can read the full documentation [here](#).



### Build Forms Quickly

Premise allows you to build forms quickly to display on both Admin side and front-end of your site. With Premise's built in function `premise_field( $args )`, you can build pretty much any field by passing an array arguments. Let's take a look.

```php

<?php 

/**
 * Build a text field
 */
$args = array(
	'type' => 'text',
	'name' => 'name_field',
	'value' => get_option('name_field'),
);
premise_field( $args );




/**
 * Build a select dropdown field.
 * NOTE: I can also pass the arguments directly into the function
 */
premise_field( array(
	'type' => 'select',
	'name' => 'select_field',
	'label' => 'My Select Field', 
	'placeholder' => 'Select an option', //used as the first option with empty value
	'options' => array(
		'Option Name' => 'Option Value',
		'Option Name 2' => 'Option Value 2',
		'Option Name 3' => 'Option Value 3',
	),
) );




/**
 * Build a file uploader field, and a textarea. 
 * NOTE: You can pass a multidemensional array holding multiple fields
 */
$args[] = array(
	'type' => 'file',
	'name' => 'file_field',
	'value' => get_option('file_field'),
);
$args[] = array(
	'type' => 'textarea',
	'name' => 'textarea_field',
	'value' => get_option('textarea_field'),
);
premise_field( $args );




/**
 * Build a section of fields. This is helpful if you want to separate fields in groups
 * where each group can maybe have their own title and description. 
 * The following builds the same fields as above but within a contianer
 */
$args = array(
	'container' = true,
	'container_title' => 'This is the Title', 
	'container_desc' => 'This is the description',
	'fields' => array(
		array(
			'type' => 'file',
			'name' => 'file_field',
			'value' => get_option('file_field'),
		),
		array(
			'type' => 'textarea',
			'name' => 'textarea_field',
			'value' => get_option('textarea_field'),
		),
	),
);
premise_field_section( $args );

?>

```

