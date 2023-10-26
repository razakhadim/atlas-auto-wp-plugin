<?php
/*
 * The file that defines the shortcodes used in the website
 *
 *
 * @link       https://www.wpstudio.agency/
 * @since      1.0.0
 *
 * @package    Atlas_Auto
 * @subpackage Atlas_Auto/includes
 */

 
// Return the repeater field items for menu
// usage: [wps_menu_items field_name="header_menu_areas_served" field_key="menu_areas_served_region_items" row="2" item_name="menu_areas_served_region_item_name" item_link="menu_areas_served_region_item_link"]
function wps_menu_items($atts) {
    $atts = shortcode_atts(array(
        'field_name' => 'header_menu_areas_served', // Default field name
        'field_key' => 'menu_areas_served_region_items', // Default field key
        'row' => 1, // Default row
        'item_name' => 'menu_areas_served_region_item_name', // Default item name field
        'item_link' => 'menu_areas_served_region_item_link', // Default item link field
    ), $atts);

    $menu_repeater_field = get_field($atts['field_name'], 'option');
    $output = '';

    if ($menu_repeater_field) {
        $row_items = array();

        // Ensure the specified row is within the available range
        if ($atts['row'] >= 1 && $atts['row'] <= count($menu_repeater_field)) {
            $row_items = $menu_repeater_field[$atts['row'] - 1][$atts['field_key']];
        }

        if (!empty($row_items)) {
            $output = '<ul class="dashed-border-list">';
            foreach ($row_items as $item) {
                $item_name = esc_html($item[$atts['item_name']]);
                $item_link = esc_url($item[$atts['item_link']]);

                if ($item_name && $item_link) {
                    $output .= '<li class="list-no-icon"><i class="fas fa-caret-right"></i> <a href="' . $item_link . '">' . $item_name . '</a></li>';
                }
            }
            $output .= '</ul>';
        }
    }
    return $output;
}

add_shortcode('wps_menu_items', 'wps_menu_items');

//get the title of the menu items block
//for example: The regions name in Areas Served Menu
//usage: [wps_menu_block_title field_name="header_menu_areas_served" field_key="menu_areas_served_region_name" row="2"]

function wps_menu_block_title($atts) {
    $atts = shortcode_atts(array(
        'field_name' => 'header_menu_areas_served', // Default field name
        'field_key' => 'menu_areas_served_region_name', // Default field key for region name
        'row' => 1, // Default row
    ), $atts);

    $menu_repeater_field = get_field($atts['field_name'], 'option');
    $output = '';

    if ($menu_repeater_field) {
        $block_title = '';

        // Ensure the specified row is within the available range
        if ($atts['row'] >= 1 && $atts['row'] <= count($menu_repeater_field)) {
            $block_title = esc_html($menu_repeater_field[$atts['row'] - 1][$atts['field_key']]);
        }

        if (!empty($block_title)) {
            $output = $block_title;
        }
    }
    return $output;
}

add_shortcode('wps_menu_block_title', 'wps_menu_block_title');


//For the Services Offered we have nested repeater fields
//Usage [wps_menu_services_offered_items outer_row="1" inner_row="2"]

function wps_menu_services_offered_items($atts) {
    $atts = shortcode_atts(array(
        'menu_field_name' => 'header_menu_services_offered', // Default outer field name
        'menu_field_key' => 'menu_services_offered_region', // Default outer field key
        'menu_items_field_key' => 'menu_services_offered_region_menu_items', // Default inner repeater field key
        'item_name_field_key' => 'menu_services_offered_region_menu_item_name', // Default item name field key
        'item_link_field_key' => 'menu_services_offered_region_menu_item_link', // Default item link field key
        'outer_row' => 1, // Default outer row // the service type "car wreckers" or "car removal"
        'inner_row' => 1, // Default inner row // the region within the above service "Waikato" or "bay of plenty"
    ), $atts);

    $menu_repeater_field = get_field($atts['menu_field_name'], 'option');
    $output = '';

    if ($menu_repeater_field) {
        $outer_row_items = array();

        // Ensure the specified outer row is within the available range
        if ($atts['outer_row'] >= 1 && $atts['outer_row'] <= count($menu_repeater_field)) {
            $outer_row_items = $menu_repeater_field[$atts['outer_row'] - 1][$atts['menu_field_key']];
        }

        if (!empty($outer_row_items)) {
            // Ensure the specified inner row is within the available range
            if ($atts['inner_row'] >= 1 && $atts['inner_row'] <= count($outer_row_items)) {
                $inner_row_items = $outer_row_items[$atts['inner_row'] - 1][$atts['menu_items_field_key']];

                if (!empty($inner_row_items)) {
                    $output = '<ul class="dashed-border-list">';
                    foreach ($inner_row_items as $item) {
                        $item_name = esc_html($item[$atts['item_name_field_key']]);
                        $item_link = esc_url($item[$atts['item_link_field_key']]);

                        if ($item_name && $item_link) {
                            $output .= '<li class="list-no-icon"><i class="fas fa-caret-right"></i> <a href="' . $item_link . '">' . $item_name . '</a></li>';
                        }
                    }
                    $output .= '</ul>';
                }
            }
        }
    }
    return $output;
}

add_shortcode('wps_menu_services_offered_items', 'wps_menu_services_offered_items');

//get the titles of services offered as they are nested

function wps_menu_services_offered_title($atts) {
    $atts = shortcode_atts(array(
        'menu_field_name' => 'header_menu_services_offered', // Default outer field name
        'region_name_field_key' => 'menu_services_offered_region_name', // Default region name field key
        'outer_row' => 1, // Default outer row
        'inner_row' => 1, // Default inner row
    ), $atts);

    $menu_repeater_field = get_field($atts['menu_field_name'], 'option');
    $output = '';

    if ($menu_repeater_field) {
        $outer_row_items = array();

        // Ensure the specified outer row is within the available range
        if ($atts['outer_row'] >= 1 && $atts['outer_row'] <= count($menu_repeater_field)) {
            $outer_row_items = $menu_repeater_field[$atts['outer_row'] - 1]['menu_services_offered_region'];

            if (!empty($outer_row_items)) {
                // Ensure the specified inner row is within the available range
                if ($atts['inner_row'] >= 1 && $atts['inner_row'] <= count($outer_row_items)) {
                    $block_title = esc_html($outer_row_items[$atts['inner_row'] - 1][$atts['region_name_field_key']]);

                    if (!empty($block_title)) {
                        $output = $block_title;
                    }
                }
            }
        }
    }
    return $output;
}

add_shortcode('wps_menu_services_offered_title', 'wps_menu_services_offered_title');


//Single page title
//The page titles can be up to 32 characters long and some are shorter 
//to keep them on two line to keep the design intact we will add and remove class based on title length

function wps_single_page_page_title() {
    // Get the page title from the ACF field
    $page_title = get_field('single_page_page_title');

    // Check if the title exists and is not empty
    if (!empty($page_title)) {
        // Calculate the title length (including spaces)
        $title_length = strlen($page_title);

        // Generate JavaScript to remove the 'wps-page-title' class if the title is more than 22 characters
        $output = <<<HTML
            <script>
                document.addEventListener("DOMContentLoaded", function() {
                    var pageTitle = document.querySelector(".wps-page-title");
                    if (pageTitle && $title_length > 22) {
                        pageTitle.classList.remove("wps-page-title");
                    }
                });
            </script>
HTML;

        return $output;
    }

    return ''; // Return an empty string if the ACF field is empty
}
add_shortcode('wps_single_page_page_title', 'wps_single_page_page_title');

//Phone Number
function wps_phone_number($atts) {
	$phone_number = get_field('business_phone_number', 'option');
	$output = '<a href="tel:' . $phone_number . '">' . $phone_number . '</a>';
	return $output;
}
add_shortcode('wps_phone_number', 'wps_phone_number');

//Phone Number Parts
function wps_phone_number_parts($atts) {
	$phone_number_parts = get_field('business_phone_number_for_parts', 'option');
	$output = '<a href="tel:' . $phone_number_parts . '">' . $phone_number_parts . '</a>';
	return $output;
}
add_shortcode('wps_phone_number_parts', 'wps_phone_number_parts');

//email address
function wps_email_address($atts) {
	$email_address = get_field('business_email_address', 'option');
	$output = '<a href="mailto:' . $email_address . '">' . $email_address . '</a>';
	return $output;
}
add_shortcode('wps_email_address', 'wps_email_address');

//email address parts
function wps_email_address_parts($atts) {
	$email_address_parts = get_field('business_email_address_for_parts', 'option');
	$output = '<a href="mailto:' . $email_address_parts . '">' . $email_address_parts . '</a>';
	return $output;
}
add_shortcode('wps_email_address_parts', 'wps_email_address_parts');

function wps_physical_address($atts) {
	$physical_address = get_field('business_physical_address', 'option');
	$output =  $physical_address;
	return $output;
}
add_shortcode('wps_physical_address', 'wps_physical_address');



// Get Single Repeater Items
// This gets the repeater items with links
// usage [wps_get_repeater_items_with_links repeater_field_name="single_page_service_areas_list" item_name="single_page_service_area_name" item_link="single_page_service_area_link"]
// includ source="option" if the repeater field is in options page

function wps_get_repeater_items_with_links($atts) {
    $atts = shortcode_atts(array(
        'repeater_field_name' => '', // Default field name
        'item_name' => '', // Default item name field
        'item_link' => '', // Default item link field
		'open_new_tab' => '',
        'source' => 'page', // Default source if getting fields from options page then pass 'option' as source
    ), $atts);

    $repeater_field = get_field($atts['repeater_field_name']);

    if($atts['source'] === 'option') {
        $repeater_field = get_field($atts['repeater_field_name'], 'option');
    }
    
    $output = '';
    if ($repeater_field) {
        $output = '<ul class="dashed-border-list">';

        foreach ($repeater_field as $item) {
            $item_name = esc_html($item[$atts['item_name']]);
            $item_link = esc_url($item[$atts['item_link']]);
            $open_new_tab = $item[$atts['open_new_tab']]; // Retrieve the open in new tab field for the specific repeater item
	$link_target = '';

            if ($item_name && $item_link) {
				if($open_new_tab === true) {
					$link_target = 'target="_blank"';
				}
                $output .= '<li class="list-no-icon"><i class="fas fa-caret-right"></i> <a href="' . $item_link . '" ' . $link_target . '>' . $item_name . '</a></li>';
            }
        }

        $output .= '</ul>';
    }

    return $output;
}

add_shortcode('wps_get_repeater_items_with_links', 'wps_get_repeater_items_with_links');

// Show and hide extra sections using shortcodes
//Render extra sections on Single Page

function wps_single_page_render_extra_sections ($atts) {
	$is_extra_section_enabled = get_field('single_page_enable_extra_sections');
	$extra_section_1_title = get_field('single_page_extra_section_1_title'); 
	$extra_section_1_text = get_field('single_page_extra_section_1_text'); 
	$extra_section_2_title = get_field('single_page_extra_section_2_title');
	$extra_section_2_text = get_field('single_page_extra_section_2_text');
	
	//make sure it is enabled and fields are NOT empty
	if($is_extra_section_enabled === true) {
	
    if (!empty($extra_section_1_title) 
		&& !empty($extra_section_1_text) 
		&& !empty($extra_section_2_title)
	    && !empty($extra_section_2_text)
	   )
		
		echo do_shortcode('[elementor-template id="33020"]');
		
		}
}
add_shortcode('wps_render_extra_sections', 'wps_single_page_render_extra_sections');


// Show and hide service areas on single page using shortcode
function wps_single_page_render_service_areas ($atts) {
$is_service_areas_enabled = get_field('single_page_show_service_areas');

if($is_service_areas_enabled === true) {
    echo do_shortcode('[elementor-template id="33043"]');
}
}
add_shortcode('wps_render_service_areas', 'wps_single_page_render_service_areas');

// shortcode to display either default text or text from single page
// Requires 3 ACF fields
// 1. default text field (must be in Website settings) // options page
// 2. single page text field (usually in Single Page)
// 3. single page checkbox field to enable/disable the single page text

function wps_default_vs_page_text ($atts) {
    $atts = shortcode_atts( array(
        'default_text' => 'default_text_1', // Default text
        'page_text' => 'single_page_text_1', // Default text on page
        'is_enabled' => 'single_page_change_default_text', // Default text on page
    ), $atts);

    $default_text = get_field($atts['default_text'], 'option');
    $page_text = get_field($atts['page_text']);
    $is_page_text_enabled = get_field($atts['is_enabled']);
    $output = '';

    if ($is_page_text_enabled === true && !empty($page_text)) {
        $output = $page_text;
    } else {
        $output = $default_text;
    }
    return $output;
}

add_shortcode( 'wps_default_vs_page_text', 'wps_default_vs_page_text' );