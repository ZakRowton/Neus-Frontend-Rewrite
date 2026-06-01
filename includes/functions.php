<?php
/**
 * NEUS Frontend Rewrite - Core Utility Functions
 */

if (!defined('NEUS_INIT')) {
    require_once __DIR__ . '/../config/config.php';
}

/**
 * Safe JSON response output
 */
function jsonResponse(array $data, int $status = 200): void {
    http_response_code($status);
    header('Content-Type: application/json; charset=utf-8');
    echo json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
    exit;
}

/**
 * API error response
 */
function apiError(string $message, int $status = 400, ?string $code = null): void {
    jsonResponse([
        'success' => false,
        'error' => $message,
        'code' => $code,
        'timestamp' => time(),
    ], $status);
}

/**
 * API success response
 */
function apiSuccess(array $data, string $message = 'Success'): void {
    jsonResponse([
        'success' => true,
        'message' => $message,
        'data' => $data,
        'timestamp' => time(),
    ], 200);
}

/**
 * Get request body as array (JSON or form)
 */
function getRequestBody(): array {
    $contentType = $_SERVER['CONTENT_TYPE'] ?? '';
    
    if (strpos($contentType, 'application/json') !== false) {
        $input = file_get_contents('php://input');
        return json_decode($input, true) ?? [];
    }
    
    return $_POST;
}

/**
 * Get request headers
 */
function getRequestHeaders(): array {
    $headers = [];
    foreach ($_SERVER as $key => $value) {
        if (strpos($key, 'HTTP_') === 0) {
            $header = str_replace('_', '-', substr($key, 5));
            $headers[strtolower($header)] = $value;
        }
    }
    return $headers;
}

/**
 * Get authorization token from request
 */
function getAuthToken(): ?string {
    $headers = getRequestHeaders();
    
    // Check Authorization header
    if (isset($headers['authorization'])) {
        $auth = $headers['authorization'];
        if (strpos($auth, 'Bearer ') === 0) {
            return substr($auth, 7);
        }
        return $auth;
    }
    
    // Check session
    if (isset($_SESSION['neus_auth_token'])) {
        return $_SESSION['neus_auth_token'];
    }
    
    // Check cookie
    if (isset($_COOKIE['neus_auth'])) {
        return $_COOKIE['neus_auth'];
    }
    
    return null;
}

/**
 * Validate and sanitize input
 */
function sanitize(string $input, string $type = 'string'): mixed {
    $input = trim($input);
    
    switch ($type) {
        case 'string':
            return htmlspecialchars($input, ENT_QUOTES, 'UTF-8');
        case 'email':
            $email = filter_var($input, FILTER_SANITIZE_EMAIL);
            return filter_var($email, FILTER_VALIDATE_EMAIL) ? $email : null;
        case 'url':
            return filter_var($input, FILTER_VALIDATE_URL) ?: null;
        case 'int':
            return filter_var($input, FILTER_VALIDATE_INT) ?: 0;
        case 'float':
            return filter_var($input, FILTER_VALIDATE_FLOAT) ?: 0.0;
        case 'bool':
            return filter_var($input, FILTER_VALIDATE_BOOLEAN);
        case 'wallet':
            // Ethereum address validation
            return preg_match('/^0x[a-fA-F0-9]{40}$/', $input) ? strtolower($input) : null;
        case 'qhash':
            // NEUS qHash validation
            return preg_match('/^[a-zA-Z0-9_-]+$/', $input) ? $input : null;
        default:
            return htmlspecialchars($input, ENT_QUOTES, 'UTF-8');
    }
}

/**
 * Generate a nonce for CSP
 */
function generateNonce(): string {
    return base64_encode(random_bytes(16));
}

/**
 * Format number with NEUS styling
 */
function formatNumber(float $number, int $decimals = 2): string {
    if ($number >= 1000000) {
        return number_format($number / 1000000, $decimals) . 'M';
    }
    if ($number >= 1000) {
        return number_format($number / 1000, $decimals) . 'K';
    }
    return number_format($number, $decimals);
}

/**
 * Format date with NEUS styling
 */
function formatDate(?string $date, string $format = 'M j, Y g:i A'): string {
    if (!$date) return 'N/A';
    $dt = DateTime::createFromFormat('U', is_numeric($date) ? $date : strtotime($date));
    if (!$dt) return 'Invalid date';
    return $dt->format($format);
}

/**
 * Format relative time
 */
function timeAgo(?string $date): string {
    if (!$date) return 'Never';
    $timestamp = is_numeric($date) ? (int)$date : strtotime($date);
    $diff = time() - $timestamp;
    
    if ($diff < 60) return 'Just now';
    if ($diff < 3600) return floor($diff / 60) . 'm ago';
    if ($diff < 86400) return floor($diff / 3600) . 'h ago';
    if ($diff < 604800) return floor($diff / 86400) . 'd ago';
    return formatDate($date, 'M j, Y');
}

/**
 * Truncate text with ellipsis
 */
function truncate(string $text, int $length = 100): string {
    if (strlen($text) <= $length) return $text;
    return substr($text, 0, $length - 3) . '...';
}

/**
 * Generate a random ID (like nanoid)
 */
function generateId(int $length = 21): string {
    $chars = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz';
    $id = '';
    for ($i = 0; $i < $length; $i++) {
        $id .= $chars[random_int(0, 61)];
    }
    return $id;
}

/**
 * Log activity (for audit trail)
 */
function logActivity(string $action, array $data = []): void {
    $log = [
        'timestamp' => time(),
        'action' => $action,
        'data' => $data,
        'ip' => $_SERVER['REMOTE_ADDR'] ?? 'unknown',
        'user_agent' => $_SERVER['HTTP_USER_AGENT'] ?? 'unknown',
    ];
    
    // Store in session for now (will be sent to Cosmos DB in production)
    if (!isset($_SESSION['activity_log'])) {
        $_SESSION['activity_log'] = [];
    }
    $_SESSION['activity_log'][] = $log;
    
    // Also log to file in development
    if (NEUS_DEBUG) {
        $logFile = __DIR__ . '/../logs/activity.log';
        @file_put_contents($logFile, json_encode($log) . "\n", FILE_APPEND | LOCK_EX);
    }
}

/**
 * Check if user is authenticated
 */
function isAuthenticated(): bool {
    return isset($_SESSION['neus_user']) && !empty($_SESSION['neus_user']);
}

/**
 * Get current user data
 */
function getCurrentUser(): ?array {
    return $_SESSION['neus_user'] ?? null;
}

/**
 * Require authentication or redirect
 */
function requireAuth(): void {
    if (!isAuthenticated()) {
        $_SESSION['redirect_after_login'] = $_SERVER['REQUEST_URI'];
        header('Location: ' . NEUS_BASE_URL . '/login');
        exit;
    }
}

/**
 * Redirect with flash message
 */
function redirect(string $url, string $message = '', string $type = 'info'): void {
    if ($message) {
        $_SESSION['flash'] = ['message' => $message, 'type' => $type];
    }
    header('Location: ' . $url);
    exit;
}

/**
 * Get flash message and clear it
 */
function getFlash(): ?array {
    $flash = $_SESSION['flash'] ?? null;
    unset($_SESSION['flash']);
    return $flash;
}

/**
 * Render a toast notification (for JavaScript to pick up)
 */
function renderToast(string $message, string $type = 'info'): string {
    $colors = [
        'info' => 'bg-blue-500',
        'success' => 'bg-green-500',
        'warning' => 'bg-yellow-500',
        'error' => 'bg-red-500',
    ];
    $color = $colors[$type] ?? $colors['info'];
    
    return "<div class=\"toast-notification {$color} text-white px-4 py-3 rounded-lg shadow-lg transform transition-all duration-300\" data-toast=\"{$type}\">" .
           htmlspecialchars($message) .
           "</div>";
}

/**
 * Get NEUS API endpoint URL
 */
function getNeusApiUrl(string $endpoint = ''): string {
    return rtrim(NEUS_API_URL, '/') . '/' . ltrim($endpoint, '/');
}

/**
 * Make authenticated request to NEUS API
 */
function neusApiRequest(string $endpoint, string $method = 'GET', array $data = [], array $headers = []): array {
    $url = getNeusApiUrl($endpoint);
    $token = getAuthToken();
    
    $defaultHeaders = [
        'Accept: application/json',
        'Content-Type: application/json',
    ];
    
    if ($token) {
        $defaultHeaders[] = 'Authorization: Bearer ' . $token;
    }
    
    $allHeaders = array_merge($defaultHeaders, $headers);
    
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $allHeaders);
    curl_setopt($ch, CURLOPT_TIMEOUT, 30);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, !NEUS_DEBUG);
    
    switch (strtoupper($method)) {
        case 'POST':
            curl_setopt($ch, CURLOPT_POST, true);
            if (!empty($data)) {
                curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
            }
            break;
        case 'PUT':
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'PUT');
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
            break;
        case 'PATCH':
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'PATCH');
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
            break;
        case 'DELETE':
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'DELETE');
            break;
    }
    
    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    $error = curl_error($ch);
    curl_close($ch);
    
    if ($error) {
        return ['success' => false, 'error' => $error, 'httpCode' => 0];
    }
    
    $decoded = json_decode($response, true);
    
    return [
        'success' => $httpCode >= 200 && $httpCode < 300,
        'httpCode' => $httpCode,
        'data' => $decoded,
        'raw' => $response,
    ];
}

/**
 * Get chain config by chain ID
 */
function getChainConfig(string $chainId): array {
    $chains = json_decode(SUPPORTED_CHAINS, true);
    foreach ($chains as $key => $chain) {
        if ((string)$chain['chainId'] === (string)$chainId || $key === $chainId) {
            return array_merge($chain, ['key' => $key]);
        }
    }
    return [];
}

/**
 * Get all chain configs
 */
function getAllChains(): array {
    return json_decode(SUPPORTED_CHAINS, true);
}

/**
 * Check if a feature is enabled
 */
function isFeatureEnabled(string $feature): bool {
    $features = [
        'genesis' => FEATURE_GENESIS_CAMPAIGN,
        'proofs' => FEATURE_PROOF_CREATION,
        'realtime' => FEATURE_REAL_TIME_UPDATES,
        'market' => FEATURE_MARKET_DATA,
        'zeus' => FEATURE_ZEUS_AI,
        'agents' => FEATURE_AGENT_SYSTEM,
        'credits' => FEATURE_CREDITS_SYSTEM,
    ];
    return $features[$feature] ?? false;
}

/**
 * Asset URL helper
 */
function asset(string $path): string {
    return NEUS_BASE_URL . '/assets/' . ltrim($path, '/');
}

/**
 * Page URL helper
 */
function pageUrl(string $path): string {
    return NEUS_BASE_URL . '/' . ltrim($path, '/');
}

/**
 * API URL helper
 */
function apiUrl(string $path): string {
    return NEUS_BASE_URL . '/api/' . ltrim($path, '/');
}

/**
 * Escape output for HTML
 */
function e(string $text): string {
    return htmlspecialchars($text ?? '', ENT_QUOTES, 'UTF-8');
}

/**
 * Component include helper
 */
function component(string $name, array $props = []): void {
    $file = __DIR__ . '/../components/' . $name . '.php';
    if (file_exists($file)) {
        extract($props);
        include $file;
    }
}
?>
