<?php
function sanitizeInput($data) {
    return htmlspecialchars(trim($data), ENT_QUOTES, 'UTF-8');
}

function validateEmail($email) {
    return filter_var($email, FILTER_VALIDATE_EMAIL) && 
           strlen($email) <= 255;
}

function validatePassword($password) {
    return strlen($password) >= 8 && 
           preg_match('/[A-Z]/', $password) &&
           preg_match('/[a-z]/', $password) &&
           preg_match('/[0-9]/', $password) &&
           preg_match('/[^A-Za-z0-9]/', $password); // special char
}
