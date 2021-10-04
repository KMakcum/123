<?php

include_once 'inc/loader.php'; // main helper for theme

function init_func() {
    require_once ABSPATH . 'wp-admin/includes/post.php';
    helper()->init();
}

init_func();

