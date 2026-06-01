<?php
/**
 * NEUS Frontend Rewrite - API Proxy
 * Proxies frontend requests to NEUS backend API
 * Handles authentication forwarding, CORS, rate limiting
 */

if (!defined('NEUS_INIT')) {
    require_once __DIR__ . '/../config/config.php';
    require_once __DIR__ . '/../includes/functions.php';
    require_once __DIR__ . '/../includes/auth.php';
}

// Set CORS headers
header('Access-Control-Allow-Origin: ' . NEUS_BASE_URL);
header('Access-Control-Allow-Methods: GET, POST, PUT, PATCH, DELETE, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, Authorization, X-Auth-Token, X-Requested-With');
header('Access-Control-Allow-Credentials: true');
header('Content-Type: application/json; charset=utf-8');

// Handle preflight
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit;
}

// Get the API endpoint from URL
$requestUri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$apiPath = preg_replace('#^/api/#', '', $requestUri);
$apiPath = trim($apiPath, '/');

if (empty($apiPath)) {
    apiError('No API endpoint specified', 400);
}

// Rate limiting check
$clientIp = $_SERVER['HTTP_X_FORWARDED_FOR'] ?? $_SERVER['REMOTE_ADDR'] ?? 'unknown';
$rateKey = 'rate_limit:' . $clientIp;
$rateData = cacheGet($rateKey);

if ($rateData) {
    $requests = $rateData['requests'] ?? 0;
    $windowStart = $rateData['window_start'] ?? 0;
    
    if (time() - $windowStart < RATE_LIMIT_WINDOW) {
        if ($requests >= RATE_LIMIT_REQUESTS) {
            apiError('Rate limit exceeded. Please try again later.', 429);
        }
        $rateData['requests'] = $requests + 1;
    } else {
        $rateData = ['requests' => 1, 'window_start' => time()];
    }
} else {
    $rateData = ['requests' => 1, 'window_start' => time()];
}

cacheSet($rateKey, $rateData, RATE_LIMIT_WINDOW);

// Build upstream URL
$upstreamUrl = NEUS_API_URL . '/' . $apiPath;

// Get request method
$method = $_SERVER['REQUEST_METHOD'];

// Get request body
$body = null;
if (in_array($method, ['POST', 'PUT', 'PATCH'])) {
    $contentType = $_SERVER['CONTENT_TYPE'] ?? '';
    if (strpos($contentType, 'application/json') !== false) {
        $body = file_get_contents('php://input');
    } else {
        $body = json_encode($_POST);
    }
}

// Build headers to forward
$forwardHeaders = [
    'Accept: application/json',
    'Content-Type: application/json',
];

// Forward auth token
$authToken = getAuthToken();
if ($authToken) {
    $forwardHeaders[] = 'Authorization: Bearer ' . $authToken;
    $forwardHeaders[] = 'X-Auth-Token: ' . $authToken;
}

// Forward NEUS-specific headers
$neusHeaders = [
    'HTTP_X_NEUS_CHAIN',
    'HTTP_X_NEUS_WALLET',
    'HTTP_X_NEUS_REQUEST_ID',
    'HTTP_X_NEUS_SESSION_ID',
];

foreach ($neusHeaders as $header) {
    if (isset($_SERVER[$header])) {
        $name = str_replace('HTTP_X_', 'X-', str_replace('_', '-', $header));
        $forwardHeaders[] = $name . ': ' . $_SERVER[$header];
    }
}

// Make upstream request
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $upstreamUrl);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, $forwardHeaders);
curl_setopt($ch, CURLOPT_TIMEOUT, 30);
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, !NEUS_DEBUG);

if ($body !== null) {
    curl_setopt($ch, CURLOPT_POSTFIELDS, $body);
}

if ($method !== 'GET') {
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $method);
}

$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
$error = curl_error($ch);
$effectiveUrl = curl_getinfo($ch, CURLINFO_EFFECTIVE_URL);
curl_close($ch);

if ($error) {
    apiError('Upstream API error: ' . $error, 502);
}

// Forward response headers from upstream
$responseHeaders = [];
if (function_exists('getallheaders')) {
    $responseHeaders = getallheaders();
}

// Set response status code
http_response_code($httpCode);

// Output response
if ($response) {
    // Validate JSON
    $decoded = json_decode($response);
    if ($decoded !== null) {
        echo $response;
    } else {
        // Not valid JSON, wrap it
        jsonResponse([
            'success' => $httpCode >= 200 && $httpCode < 300,
            'data' => $response,
            'upstream_url' => $effectiveUrl,
        ], $httpCode);
    }
} else {
    jsonResponse([
        'success' => $httpCode >= 200 && $httpCode < 300,
        'message' => 'No content',
    ], $httpCode);
}
?>
