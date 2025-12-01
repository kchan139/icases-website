<?php
function checkRateLimit($identifier, $maxAttempts = 5, $timeWindow = 300) {
    $key = "rate_limit_{$identifier}";
    
    if (!isset($_SESSION[$key])) {
        $_SESSION[$key] = ['count' => 0, 'first_attempt' => time()];
    }
    
    $data = $_SESSION[$key];
    
    if (time() - $data['first_attempt'] > $timeWindow) {
        $_SESSION[$key] = ['count' => 1, 'first_attempt' => time()];
        return true;
    }
    
    if ($data['count'] >= $maxAttempts) {
        return false;
    }
    
    $_SESSION[$key]['count']++;
    return true;
}
