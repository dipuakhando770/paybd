<?php
switch (segment(1)) {
    case 'admin':
        require_once 'admin/main.blade.php';
        break;
    case 'user':
        require_once 'user/main.blade.php';
        break;
    default:
        require_once 'general.php';
        break;
}
