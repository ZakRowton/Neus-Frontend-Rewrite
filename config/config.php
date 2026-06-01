<?php
/**
 * NEUS Frontend Rewrite - Central Configuration
 * Mirrors Next.js environment setup and constants
 */

// Prevent direct access
if (!defined('NEUS_INIT')) {
    define('NEUS_INIT', true);
}

// Environment detection
$isDevelopment = in_array($_SERVER['HTTP_HOST'] ?? '', ['localhost', '127.0.0.1', '::1']) || 
                   (strpos($_SERVER['HTTP_HOST'] ?? '', 'localhost') !== false);

// Base configuration
if (!defined('NEUS_ENV')) {
    define('NEUS_ENV', $isDevelopment ? 'development' : 'production');
}
if (!defined('NEUS_DEBUG')) {
    define('NEUS_DEBUG', $isDevelopment);
}

// Base URL
$protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https' : 'http';
$host = $_SERVER['HTTP_HOST'] ?? 'localhost';
$basePath = '';
if (!defined('NEUS_BASE_URL')) {
    define('NEUS_BASE_URL', $protocol . '://' . $host . $basePath);
}

// NEUS Backend API Configuration
// These proxy to the actual NEUS backend
if (!defined('NEUS_API_BASE')) {
    define('NEUS_API_BASE', 'https://api.neus.network');
}
if (!defined('NEUS_API_VERSION')) {
    define('NEUS_API_VERSION', 'v1');
}
if (!defined('NEUS_API_URL')) {
    define('NEUS_API_URL', NEUS_API_BASE . '/' . NEUS_API_VERSION);
}

// NEUS Public URLs
if (!defined('NEUS_PUBLIC_HOST')) {
    define('NEUS_PUBLIC_HOST', 'neus.network');
}
if (!defined('NEUS_PUBLIC_ORIGIN')) {
    define('NEUS_PUBLIC_ORIGIN', 'https://' . NEUS_PUBLIC_HOST);
}

// Cosmos DB Configuration (for local caching/queue)
if (!defined('COSMOS_ENDPOINT')) {
    define('COSMOS_ENDPOINT', $_ENV['COSMOS_ENDPOINT'] ?? 'https://localhost:8081');
}
if (!defined('COSMOS_KEY')) {
    define('COSMOS_KEY', $_ENV['COSMOS_KEY'] ?? '');
}
if (!defined('COSMOS_DATABASE')) {
    define('COSMOS_DATABASE', $_ENV['COSMOS_DATABASE'] ?? 'neus-local');
}

// NEUS MCP Configuration
if (!defined('NEUS_MCP_ENDPOINT')) {
    define('NEUS_MCP_ENDPOINT', $_ENV['NEUS_MCP_ENDPOINT'] ?? 'https://mcp.neus.network');
}
if (!defined('NEUS_MCP_API_KEY')) {
    define('NEUS_MCP_API_KEY', $_ENV['NEUS_MCP_API_KEY'] ?? '');
}

// Session & Security
if (!defined('SESSION_NAME')) {
    define('SESSION_NAME', 'neus_session');
}
if (!defined('SESSION_LIFETIME')) {
    define('SESSION_LIFETIME', 86400 * 7); // 7 days
}

// Cookie settings
if (!defined('COOKIE_SECURE')) {
    define('COOKIE_SECURE', !$isDevelopment);
}
if (!defined('COOKIE_HTTPONLY')) {
    define('COOKIE_HTTPONLY', true);
}
if (!defined('COOKIE_SAMESITE')) {
    define('COOKIE_SAMESITE', 'Lax');
}

// Feature flags (match Next.js features)
if (!defined('FEATURE_GENESIS_CAMPAIGN')) {
    define('FEATURE_GENESIS_CAMPAIGN', true);
}
if (!defined('FEATURE_PROOF_CREATION')) {
    define('FEATURE_PROOF_CREATION', true);
}
if (!defined('FEATURE_REAL_TIME_UPDATES')) {
    define('FEATURE_REAL_TIME_UPDATES', true);
}
if (!defined('FEATURE_MARKET_DATA')) {
    define('FEATURE_MARKET_DATA', false);
}
if (!defined('FEATURE_ZEUS_AI')) {
    define('FEATURE_ZEUS_AI', true);
}
if (!defined('FEATURE_AGENT_SYSTEM')) {
    define('FEATURE_AGENT_SYSTEM', true);
}
if (!defined('FEATURE_CREDITS_SYSTEM')) {
    define('FEATURE_CREDITS_SYSTEM', true);
}

// Credit policy defaults
if (!defined('CREDIT_DEFAULT_FREE')) {
    define('CREDIT_DEFAULT_FREE', 100);
}
if (!defined('CREDIT_DEFAULT_PAID')) {
    define('CREDIT_DEFAULT_PAID', 1000);
}
if (!defined('CREDIT_TOPUP_MIN')) {
    define('CREDIT_TOPUP_MIN', 50);
}
if (!defined('CREDIT_TOPUP_MAX')) {
    define('CREDIT_TOPUP_MAX', 10000);
}

// Supported blockchains
if (!defined('SUPPORTED_CHAINS')) {
    define('SUPPORTED_CHAINS', json_encode([
        'ethereum' => ['name' => 'Ethereum', 'chainId' => 1, 'symbol' => 'ETH'],
        'polygon' => ['name' => 'Polygon', 'chainId' => 137, 'symbol' => 'MATIC'],
        'arbitrum' => ['name' => 'Arbitrum', 'chainId' => 42161, 'symbol' => 'ARB'],
        'base' => ['name' => 'Base', 'chainId' => 8453, 'symbol' => 'BASE'],
        'optimism' => ['name' => 'Optimism', 'chainId' => 10, 'symbol' => 'OP'],
        'bsc' => ['name' => 'BSC', 'chainId' => 56, 'symbol' => 'BNB'],
    ]));
}

// ZKPassport domains
if (!defined('ZKPASSPORT_DOMAINS')) {
    define('ZKPASSPORT_DOMAINS', json_encode([
        'production' => 'neus.network',
        'staging' => 'staging.neus.network',
        'development' => 'localhost',
    ]));
}

// IPFS Gateway
if (!defined('IPFS_GATEWAY_BASE')) {
    define('IPFS_GATEWAY_BASE', 'https://ipfs.io/ipfs/');
}

// Rate limiting
if (!defined('RATE_LIMIT_REQUESTS')) {
    define('RATE_LIMIT_REQUESTS', 100);
}
if (!defined('RATE_LIMIT_WINDOW')) {
    define('RATE_LIMIT_WINDOW', 60); // seconds
}

// Error reporting
if (NEUS_DEBUG) {
    error_reporting(E_ALL);
    ini_set('display_errors', '1');
} else {
    error_reporting(E_ERROR | E_WARNING);
    ini_set('display_errors', '0');
}

// Timezone
date_default_timezone_set('UTC');

// Start session if not already started
if (session_status() === PHP_SESSION_NONE) {
    ini_set('session.cookie_httponly', '1');
    ini_set('session.cookie_secure', COOKIE_SECURE ? '1' : '0');
    ini_set('session.cookie_samesite', COOKIE_SAMESITE);
    ini_set('session.gc_maxlifetime', SESSION_LIFETIME);
    session_name(SESSION_NAME);
    session_start();
}
?>
