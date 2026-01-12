<?php
namespace BML;

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

class Detector {

    public function is_buddyboss() {
        return class_exists( 'BuddyBoss_Platform' );
    }

    public function is_buddypress() {
        return function_exists( 'buddypress' ) || class_exists( 'BuddyPress' );
    }
}
