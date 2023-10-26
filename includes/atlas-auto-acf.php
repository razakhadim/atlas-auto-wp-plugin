<?php
/**
 * The file that defines the ACF functions.
 *
 * A class definition that includes attributes and functions used across both the
 * public-facing side of the site and the admin area.
 *
 * @link       https://www.wpstudio.agency/
 * @since      1.0.0
 *
 * @package    Atlas_Auto
 * @subpackage Atlas_Auto/includes
 */

//Returning ACF repeater fields in Elementor widgets
//Usage: assign a classname to the widge prepended by "repeater_". e.g "repeater_homepage_faqs".
//Then use the repeater subfields as prepended by # symbol inside widget. e.g #homepage_faq_question for title & #homepage_faq_answer for answer of FAQ.
//It will then create the same widget repeated for as many times as subfields.
//Mainly used for FAQs

add_action( 'elementor/frontend/section/before_render', function( $section ) {
    // Catch output
    ob_start();
} );

// And then

add_action( 'elementor/frontend/section/after_render', function( $section ) {
    // Collect output
    $content = ob_get_clean();

    // Alter the output anyway you want, in your case wrapping 
    // it with a classed div should be something like this
    // make sure to echo it
    if($repeater_name = arfe_check_if_repeater_class_in_widget($section, 'css_classes')) {
        echo arfe_prepare_content_by_repeater($content, $repeater_name);
    } else {
        echo $content;
    }
} );

// Handle accordion and toggle for repeater
add_action( 'elementor/frontend/widget/before_render', function( $widget ) {
    if (in_array($widget->get_name(), ["toggle", "accordion"])) {
        if ($repeater_name = arfe_check_if_repeater_class_in_widget($widget)) {
            $repeater = get_field($repeater_name);
            
            if ($repeater && count($repeater) > 0) {
                $create_tabs = [];

                foreach ($repeater as $row) {
                    $template_tab = $widget->get_settings('tabs');
                    
                    if (count($template_tab) == 0) {
                        return;
                    }

                    $template_tab = $template_tab[0];
                    unset($template_tab['_id']);

                    foreach ($row as $key => $value) {
                        $template_tab[$key] = $value;
                    }

                    $create_tabs[] = $template_tab;
                }

                $widget->set_settings('tabs', $create_tabs);
            } else {
                // No items in repeater, delete all tabs.
                $widget->set_settings('tabs', []);
            }
        }
    }
}, 10, 1);

add_action( 'elementor/widget/render_content', function( $content, $widget ) {
    if ( \Elementor\Plugin::$instance->editor->is_edit_mode() || in_array($widget->get_name(), ["toggle", "accordion"])) {
        return $content;
    }
    
    $repeater_name = arfe_check_if_repeater_class_in_widget($widget);
    
    if ($repeater_name) {
        return arfe_prepare_content_by_repeater($content, $repeater_name);
    }
    
    return $content;
}, 10, 2 );

function arfe_prepare_content_by_repeater($content, $repeater_name) {
    $repeater = get_field($repeater_name);
    
    if (!$repeater || count($repeater) == 0) {
        return "";
    }
    
    $new_view = '';
    
    foreach ($repeater as $row) {
        $single_content = $content;
        
        foreach ($row as $key => $value ) {
            $single_content = str_replace("#".$key, $value, $single_content);
        }
        
        $new_view = $new_view . $single_content;
    }
    
    return $new_view;
}

function arfe_check_if_repeater_class_in_widget($widget, $classes_key = '_css_classes') {
    $classes = $widget->get_settings()[$classes_key];
    $classes = explode("repeater_", $classes);
    
    if (count($classes) > 1) {
        $repeater_name = explode(" ", $classes[1])[0];
        return $repeater_name;
    }
    
    return false;
}


