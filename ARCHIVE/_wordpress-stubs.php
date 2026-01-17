<?php
function add_action($hook, $callback, $priority = 10, $accepted_args = 1) {}
function get_option($name, $default = false) {}
function update_option($name, $value) {}
function wp_add_dashboard_widget($id, $name, $callback) {}
function esc_html($text) { return $text; }
function admin_url($path = '') { return '/wp-admin/' . $path; }
function rest_url($path = '') { return '/wp-json/' . $path; }