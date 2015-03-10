<?php

add_action( 'admin_menu', 'wp_monolog_add_admin_menu' );
add_action( 'admin_init', 'wp_monolog_settings_init' );

function wp_monolog_add_admin_menu(  ) { 
    add_management_page( 'WP Monolog', 'WP Monolog', 'manage_options', 'wp_monolog', 'wp_monolog_options_page' );
}

function wp_monolog_settings_init(  ) { 

    register_setting( 'pluginPage', 'wp_monolog_settings' );

    add_settings_section(
        'wp_monolog_error_email_settings', 
        __( 'Error Notification Settings', 'wp_monolog' ), 
        'wp_monolog_settings_section_callback', 
        'pluginPage'
    );

    add_settings_field( 
        'wp_monolog_error_from', 
        __( 'From e-mail:', 'wp_monolog' ), 
        'wp_monolog_error_from_render', 
        'pluginPage', 
        'wp_monolog_error_email_settings'
    );

    add_settings_field( 
        'wp_monolog_error_to', 
        __( 'To e-mail:', 'wp_monolog' ), 
        'wp_monolog_error_to_render', 
        'pluginPage', 
        'wp_monolog_error_email_settings'
    );

    add_settings_field( 
        'wp_monolog_error_subject', 
        __( 'Subject', 'wp_monolog' ), 
        'wp_monolog_error_subject_render', 
        'pluginPage', 
        'wp_monolog_error_email_settings'
    );

}

function wp_monolog_error_from_render(  ) { 

    $options = get_option( 'wp_monolog_settings' );
    $from = !empty($options['WPMailHandler']['from']) ? $options['WPMailHandler']['from'] : get_option('admin_email');
    ?>
    <input type='text' name='wp_monolog_settings[WPMailHandler][from]' value='<?php echo $from; ?>'>
    <?php

}

function wp_monolog_error_to_render(  ) { 

    $options = get_option( 'wp_monolog_settings' );
    $to = !empty($options['WPMailHandler']['to']) ? $options['WPMailHandler']['to'] : get_option('admin_email');
    ?>
    <input type='text' name='wp_monolog_settings[WPMailHandler][to]' value='<?php echo $to; ?>'>
    <?php

}

function wp_monolog_error_subject_render(  ) { 

    $options = get_option( 'wp_monolog_settings' );
    $subject = !empty($options['WPMailHandler']['subject']) ? $options['WPMailHandler']['subject'] : 'An Error on the site "'.get_option('blogname').'" has been detected.';
    ?>
    <input type='text' name='wp_monolog_settings[WPMailHandler][subject]' value='<?php echo $subject; ?>'>
    <?php

}

function wp_monolog_settings_section_callback(  ) { 

    echo __( 'These settings will override default values when configuring Monolog', 'wp_monolog' );

}

function wp_monolog_options_page(  ) { 

    ?>
    <form action='options.php' method='post'>
        
        <h2>WP Monolog</h2>
        
        <?php
        settings_fields( 'pluginPage' );
        do_settings_sections( 'pluginPage' );
        submit_button();
        ?>
        
    </form>
    <?php

}

?>