<?php
// Utility functions for the portal

function sanitize($input) {
    return htmlspecialchars(strip_tags(trim($input)));
}

function format_status($status) {
    switch($status) {
        case 'DELIVERED': return '<span style="color:green">● Delivered</span>';
        case 'TRANSIT': return '<span style="color:orange">● In Transit</span>';
        case 'PENDING': return '<span style="color:gray">● Pending</span>';
        default: return 'Unknown';
    }
}

function get_server_load() {
    // Simulating system metrics
    return rand(10, 40) . "%";
}
?>