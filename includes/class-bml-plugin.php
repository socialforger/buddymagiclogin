<?php
namespace BML;

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

require_once BML_DIR . 'includes/class-bml-detector.php';
require_once BML_DIR . 'includes/class-bml-admin.php';
require_once BML_DIR . 'includes/class-bml-security.php';
require_once BML_DIR . 'includes/class-bml-onboarding.php';
require_once BML_DIR . 'includes/class-bml-integration.php';
require_once BML_DIR . 'includes/class-bml-cron.php';
require_once BML_DIR . 'includes/functions-template.php';

class Plugin {

    private static $instance;

    public $detector;
    public $admin;
    public $security;
    public $onboarding;
    public $integration;
    public $cron;

    public static function instance() {
        if ( ! self::$instance ) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    private function __construct() {
        $this->detector    = new Detector();
        $this->admin       = new Admin();
        $this->security    = new Security( $this->detector );
        $this->onboarding  = new Onboarding( $this->detector );
        $this->integration = new Integration( $this->detector );
        $this->cron        = new Cron();

        add_action( 'init', [ $this, 'load_textdomain' ] );
    }

    public function load_textdomain() {
        load_plugin_textdomain(
            'buddymagiclogin',
            false,
            dirname( plugin_basename( BML_FILE ) ) . '/languages'
        );
    }

    public static function activate() {
        if ( ! wp_next_scheduled( 'bml_cleanup_cron' ) ) {
            wp_schedule_event( time(), 'hourly', 'bml_cleanup_cron' );
        }
    }

    public static function deactivate() {
        $ts = wp_next_scheduled( 'bml_cleanup_cron' );
        if ( $ts ) {
            wp_unschedule_event( $ts, 'bml_cleanup_cron' );
        }
    }
}
