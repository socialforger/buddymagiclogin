<?php
namespace BML;

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

function get_access_message() {
    $msg = apply_filters( 'bml_access_message', '' );
    return $msg;
}
