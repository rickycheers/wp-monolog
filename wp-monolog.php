<?php
/*
Plugin Name: WP Monolog 
Plugin URI: 
Description: A lightweight WordPress integration of Monolog.
Version: 0.1
Author: RPP
Author URI: 
License: GPLv2 or later
*/

if( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

require_once dirname(__FILE__) . '/vendor/autoload.php';

use Monolog\Logger;
use Monolog\Handler\StreamHandler;
use Monolog\Handler\WPMailHandler;
use Monolog\Formatter\HtmlFormatter;

if( ! class_exists('WPMonolog') ) :

class WPMonolog {

    function __construct(){
        $this->logger = $this->getLoggerInstance();
    }

    function initialize() {

        // add_action( 'init', array($this, 'initialize') );
        require_once dirname(__FILE__) . '/settings-page.php';

    }

    function getLoggerInstance( $log_name = 'WordPressLog', $overrides = null ){

        if( !$settings ){

            $defaults = array(
                'log_path'  => dirname(__FILE__) . '/log/',
                'log_name'  => date('Y-m-d').'_wp_monolog.log',
                'WPMailHandler' => array(
                    'to'      => get_option('admin_email'),
                    'subject' => 'An Error on the site "'.get_option('blogname').'" has been detected.',
                    'from'    => get_option('admin_email')
                )
            );

            $overrides = get_option( "wp_monolog_settings", $defaults );

            $s = array_replace($defaults, $overrides);

        } else {
            $s = array_replace($defaults, $overrides);
            $s = $settings;
        }

        if( !is_dir( $s["log_path"] ) ){
            mkdir( $s["log_path"] );
        }

        $log_file = $s["log_path"].'/'.$s["log_name"];

        if( !file_exists( $log_file ) ){
            file_put_contents($log_file, '');
        }

        $logger = new Logger( $log_name );

        // create log channels
        $logger->pushHandler( new StreamHandler( $log_file ) );

        $mailStream = new WPMailHandler( $s['WPMailHandler']['to'], $s['WPMailHandler']['subject'], $s['WPMailHandler']['from'] );
        $mailStream->setFormatter( new HTMLFormatter );

        $logger->pushHandler( $mailStream );

        return $logger;
    }

}

function wp_monolog() {

    global $wp_monolog;
    global $logger;

    if( !isset($wp_monolog) ) {
        $wp_monolog = new WPMonolog();
        $wp_monolog->initialize();
        $logger = $wp_monolog->logger;
    }
    
    return $wp_monolog;
}

// initialize
wp_monolog();

endif; // class_exists check
