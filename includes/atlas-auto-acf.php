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


//Limit the number characters that can be added ACF WYSIWYG editors
//This uses the field name

function wps_add_character_limit_to_WYSIWYG() {
    ?>
    <script type="text/javascript">
    (function($) {

    acf.add_action('wysiwyg_tinymce_init', function( ed, id, mceInit, $field ){
    
    // Define a mapping of ACF field names to character limits
    var characterLimits = {
        'homepage_section_1_text': 350, // Example ACF field name with a 300-character limit
		'homepage_section_2_text': 350,
		'homepage_section_3_text': 350,
        'acf_field_name_2': 500, // Example ACF field name with a 500-character limit
        // Add more field names and limits as needed
    };

    // Get the ACF field name for the current field
    var acfFieldName = $field.data('name');

    // Set the character limit based on the ACF field name
    var limitCharacters = characterLimits[acfFieldName] || 300; // Default to 300 characters if the field name is not found

    $('#wp-'+id+'-wrap').append('<div class="acfcounter" style="background-color: #f7f7f7; color: #444; padding: 2px 10px; font-size: 12px; border-top: 1px solid #e5e5e5;"><span class="chars" style="font-size: 12px;"></span></div>');

    // Initialize a flag to track whether content has changed
    var contentChanged = false;

    const checkCharacterLimit = function() {
        var value = $('#'+id).val();
        var totalChars = value.replace(/(<([^>]+)>)/ig,"").length;

        if (contentChanged && totalChars > limitCharacters) {
            // Truncate the text to the character limit
            value = value.slice(0, limitCharacters);
            $('#'+id).val(value);

            // Highlight text and display an alert
            $('#'+id).css('background-color', 'red');
            alert('Character limit reached. Maximum allowed: ' + limitCharacters + ' characters. Please do not add more text than limit as it will ruin the page design.');
        } else {
            $('#'+id).css('background-color', ''); // Remove background color if within limit
        }

        // Update the character counter at the bottom
        $('.acfcounter .chars').html('Characters: '+totalChars);

        // Reset the content changed flag
        contentChanged = false;
    };

    // Attach the checkCharacterLimit function to various TinyMCE events
    tinymce.get(id).on('keyup', function() {
        contentChanged = true; // Set the flag when content changes
        checkCharacterLimit();
    });
    tinymce.get(id).on('change', function() {
        contentChanged = true; // Set the flag when content changes
        checkCharacterLimit();
    });
    tinymce.get(id).on('blur', function() {
        contentChanged = true; // Set the flag when content changes
        checkCharacterLimit();
    });

    // Initialize the character limit check
    checkCharacterLimit();

    });

    })(jQuery);    
    </script>
    <?php
}

add_action('acf/input/admin_footer', 'wps_add_character_limit_to_WYSIWYG');