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

