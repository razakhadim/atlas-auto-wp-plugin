<?php
// Character limit for ACF WYSIWYG editor
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
		'single_page_section_2_text' : 200,
		'single_page_service_areas_section_text': 132,
        // Add more field names and limits as needed
    };

    // Get the ACF field name for the current field
    var acfFieldName = $field.data('name');

    // Set the character limit based on the ACF field name
    var limitCharacters = characterLimits[acfFieldName] || 465; // Default to 465 characters if the field name is not found

    $('#wp-'+id+'-wrap').append('<div class="acfcounter" style="background-color: #f7f7f7; color: #444; padding: 2px 10px; font-size: 12px; border-top: 1px solid #e5e5e5;"><span class="chars" style="font-size: 12px;"></span></div>');

    // Initialize a flag to track whether content has changed
    var contentChanged = false;

    const checkCharacterLimit = function() {
        var value = $('#'+id).val();
        var totalChars = value.replace(/(<([^>]+)>)/ig,"").length;

        if (contentChanged && totalChars > limitCharacters) {
            // Truncate the text to the character limit
//             value = value.slice(0, limitCharacters);
//             $('#'+id).val(value);

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