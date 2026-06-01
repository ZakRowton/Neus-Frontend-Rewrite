<?php
/**
 * NEUS Frontend Rewrite - Authentication System
 * Handles session-based auth mirroring Next.js auth patterns
 */

if (!defined('NEUS_INIT')) {
    require_once __DIR__ . '/../config/config.php';
}

/**
 * Auth session keys
 */
const AUTH_SESSION_USER = 'neus_user';
const AUTH_SESSION_TOKEN = 'neus_auth_token';
const AUTH_SESSION_WALLET = 'neus_wallet';
const AUTH_SESSION_PROFILE = 'neus_profile';
const AUTH_SESSION_EXPIRES = 'neus_auth_expires';

/**
 * Initialize auth state from session/cookies
 */
function initAuth(): void {
    // Check for auth cookie and sync to session
    if (!isset($_SESSION[AUTH_SESSION_TOKEN]) && isset($_COOKIE['neus_auth'])) {
        $token = $_COOKIE['neus_auth'];
        $_SESSION[AUTH_SESSION_TOKEN] = $token;
        
        // Validate token with NEUS API
        $result = neusApiRequest('/auth/me', 'GET', [], [
            'X-Auth-Token: ' . $token,
        ]);
        
        if ($result['success'] && isset($result['data']['data'])) {
            $userData = $result['data']['data'];
            $_SESSION[AUTH_SESSION_USER] = $userData;
            
            if (isset($userData['wallet'])) {
                $_SESSION[AUTH_SESSION_WALLET] = $userData['wallet'];
            }
            
            if (isset($userData['profile'])) {
                $_SESSION[AUTH_SESSION_PROFILE] = $userData['profile'];
            }
            
            $_SESSION[AUTH_SESSION_EXPIRES] = time() + SESSION_LIFETIME;
        }
    }
    
    // Check session expiry
    if (isset($_SESSION[AUTH_SESSION_EXPIRES]) && $_SESSION[AUTH_SESSION_EXPIRES] < time()) {
        logout();
    }
}

/**
 * Login with credentials or wallet
 */
function login(array $credentials): array {
    $method = $credentials['method'] ?? 'password'; // password, wallet, oauth
    
    switch ($method) {
        case 'password':
            return loginWithPassword($credentials);
        case 'wallet':
            return loginWithWallet($credentials);
        case 'oauth':
            return loginWithOAuth($credentials);
        default:
            return ['success' => false, 'error' => 'Invalid login method'];
    }
}

/**
 * Login with email/password
 */
function loginWithPassword(array $credentials): array {
    $email = sanitize($credentials['email'] ?? '', 'email');
    $password = $credentials['password'] ?? '';
    
    if (!$email || !$password) {
        return ['success' => false, 'error' => 'Email and password are required'];
    }
    
    // Forward to NEUS API
    $result = neusApiRequest('/auth/login', 'POST', [
        'email' => $email,
        'password' => $password,
    ]);
    
    if ($result['success'] && isset($result['data']['token'])) {
        setAuthSession($result['data']);
        return ['success' => true, 'user' => $result['data']['user'] ?? []];
    }
    
    return [
        'success' => false,
        'error' => $result['data']['error'] ?? $result['data']['message'] ?? 'Login failed',
    ];
}

/**
 * Login/connect with blockchain wallet
 */
function loginWithWallet(array $credentials): array {
    $wallet = sanitize($credentials['wallet'] ?? '', 'wallet');
    $signature = $credentials['signature'] ?? '';
    $message = $credentials['message'] ?? '';
    $chainId = $credentials['chainId'] ?? '1';
    
    if (!$wallet) {
        return ['success' => false, 'error' => 'Wallet address is required'];
    }
    
    // Forward to NEUS API
    $result = neusApiRequest('/auth/wallet', 'POST', [
        'wallet' => $wallet,
        'signature' => $signature,
        'message' => $message,
        'chainId' => $chainId,
    ]);
    
    if ($result['success'] && isset($result['data']['token'])) {
        setAuthSession($result['data']);
        return ['success' => true, 'user' => $result['data']['user'] ?? []];
    }
    
    return [
        'success' => false,
        'error' => $result['data']['error'] ?? $result['data']['message'] ?? 'Wallet connection failed',
    ];
}

/**
 * Login with OAuth provider
 */
function loginWithOAuth(array $credentials): array {
    $provider = $credentials['provider'] ?? '';
    $code = $credentials['code'] ?? '';
    $redirectUri = $credentials['redirectUri'] ?? '';
    
    if (!$provider || !$code) {
        return ['success' => false, 'error' => 'OAuth provider and code are required'];
    }
    
    $result = neusApiRequest('/auth/oauth', 'POST', [
        'provider' => $provider,
        'code' => $code,
        'redirectUri' => $redirectUri,
    ]);
    
    if ($result['success'] && isset($result['data']['token'])) {
        setAuthSession($result['data']);
        return ['success' => true, 'user' => $result['data']['user'] ?? []];
    }
    
    return [
        'success' => false,
        'error' => $result['data']['error'] ?? 'OAuth login failed',
    ];
}

/**
 * Register new account
 */
function register(array $data): array {
    $email = sanitize($data['email'] ?? '', 'email');
    $password = $data['password'] ?? '';
    $username = sanitize($data['username'] ?? '', 'string');
    $wallet = sanitize($data['wallet'] ?? '', 'wallet');
    
    if (!$email || !$password) {
        return ['success' => false, 'error' => 'Email and password are required'];
    }
    
    $result = neusApiRequest('/auth/register', 'POST', [
        'email' => $email,
        'password' => $password,
        'username' => $username,
        'wallet' => $wallet,
    ]);
    
    if ($result['success'] && isset($result['data']['token'])) {
        setAuthSession($result['data']);
        return ['success' => true, 'user' => $result['data']['user'] ?? []];
    }
    
    return [
        'success' => false,
        'error' => $result['data']['error'] ?? $result['data']['message'] ?? 'Registration failed',
    ];
}

/**
 * Logout user
 */
function logout(): void {
    // Clear session
    unset(
        $_SESSION[AUTH_SESSION_USER],
        $_SESSION[AUTH_SESSION_TOKEN],
        $_SESSION[AUTH_SESSION_WALLET],
        $_SESSION[AUTH_SESSION_PROFILE],
        $_SESSION[AUTH_SESSION_EXPIRES]
    );
    
    // Clear cookies
    setcookie('neus_auth', '', [
        'expires' => time() - 3600,
        'path' => '/',
        'secure' => COOKIE_SECURE,
        'httponly' => COOKIE_HTTPONLY,
        'samesite' => COOKIE_SAMESITE,
    ]);
    
    // Regenerate session ID for security
    session_regenerate_id(true);
}

/**
 * Set authentication session
 */
function setAuthSession(array $authData): void {
    $token = $authData['token'] ?? '';
    $user = $authData['user'] ?? $authData['data'] ?? [];
    $expiresIn = $authData['expiresIn'] ?? SESSION_LIFETIME;
    
    $_SESSION[AUTH_SESSION_TOKEN] = $token;
    $_SESSION[AUTH_SESSION_USER] = $user;
    $_SESSION[AUTH_SESSION_EXPIRES] = time() + $expiresIn;
    
    if (isset($user['wallet'])) {
        $_SESSION[AUTH_SESSION_WALLET] = $user['wallet'];
    }
    
    if (isset($user['profile'])) {
        $_SESSION[AUTH_SESSION_PROFILE] = $user['profile'];
    }
    
    // Set auth cookie
    setcookie('neus_auth', $token, [
        'expires' => time() + $expiresIn,
        'path' => '/',
        'secure' => COOKIE_SECURE,
        'httponly' => COOKIE_HTTPONLY,
        'samesite' => COOKIE_SAMESITE,
    ]);
}

/**
 * Get current auth token
 */
function getAuthToken(): ?string {
    return $_SESSION[AUTH_SESSION_TOKEN] ?? $_COOKIE['neus_auth'] ?? null;
}

/**
 * Get current user
 */
function getCurrentUser(): ?array {
    return $_SESSION[AUTH_SESSION_USER] ?? null;
}

/**
 * Get current wallet address
 */
function getCurrentWallet(): ?string {
    return $_SESSION[AUTH_SESSION_WALLET] ?? null;
}

/**
 * Get user profile
 */
function getUserProfile(): ?array {
    return $_SESSION[AUTH_SESSION_PROFILE] ?? null;
}

/**
 * Refresh user data from API
 */
function refreshUserData(): array {
    $token = getAuthToken();
    if (!$token) {
        return ['success' => false, 'error' => 'Not authenticated'];
    }
    
    $result = neusApiRequest('/auth/me', 'GET', [], [
        'X-Auth-Token: ' . $token,
    ]);
    
    if ($result['success'] && isset($result['data']['data'])) {
        $userData = $result['data']['data'];
        $_SESSION[AUTH_SESSION_USER] = $userData;
        
        if (isset($userData['wallet'])) {
            $_SESSION[AUTH_SESSION_WALLET] = $userData['wallet'];
        }
        
        if (isset($userData['profile'])) {
            $_SESSION[AUTH_SESSION_PROFILE] = $userData['profile'];
        }
        
        return ['success' => true, 'user' => $userData];
    }
    
    return ['success' => false, 'error' => 'Failed to refresh user data'];
}

/**
 * Check if user has specific role
 */
function hasRole(string $role): bool {
    $user = getCurrentUser();
    if (!$user) return false;
    
    $roles = $user['roles'] ?? $user['role'] ?? [];
    if (is_string($roles)) {
        $roles = [$roles];
    }
    
    return in_array($role, $roles);
}

/**
 * Check if user is admin
 */
function isAdmin(): bool {
    return hasRole('admin') || hasRole('superadmin');
}

/**
 * Require specific role or redirect
 */
function requireRole(string $role): void {
    if (!hasRole($role)) {
        http_response_code(403);
        redirect(NEUS_BASE_URL . '/dashboard', 'Access denied. Insufficient permissions.', 'error');
    }
}

/**
 * Generate CSRF token
 */
function generateCsrfToken(): string {
    if (empty($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf_token'];
}

/**
 * Validate CSRF token
 */
function validateCsrfToken(?string $token): bool {
    return isset($_SESSION['csrf_token']) && hash_equals($_SESSION['csrf_token'], $token ?? '');
}

/**
 * Get CSRF token for forms
 */
function csrfField(): string {
    $token = generateCsrfToken();
    return '<input type="hidden" name="csrf_token" value="' . e($token) . '" class="csrf-field">';
}

/**
 * Verify wallet signature (client-side via JS, server validates)
 */
function verifyWalletSignature(string $wallet, string $signature, string $message, string $chainId = '1'): array {
    // Forward to NEUS API for verification
    return neusApiRequest('/auth/verify-signature', 'POST', [
        'wallet' => $wallet,
        'signature' => $signature,
        'message' => $message,
        'chainId' => $chainId,
    ]);
}

/**
 * Link wallet to account
 */
function linkWallet(string $wallet, string $signature, string $message): array {
    $token = getAuthToken();
    if (!$token) {
        return ['success' => false, 'error' => 'Not authenticated'];
    }
    
    return neusApiRequest('/auth/link-wallet', 'POST', [
        'wallet' => $wallet,
        'signature' => $signature,
        'message' => $message,
    ], [
        'X-Auth-Token: ' . $token,
    ]);
}

/**
 * Unlink wallet from account
 */
function unlinkWallet(string $wallet): array {
    $token = getAuthToken();
    if (!$token) {
        return ['success' => false, 'error' => 'Not authenticated'];
    }
    
    return neusApiRequest('/auth/unlink-wallet', 'POST', [
        'wallet' => $wallet,
    ], [
        'X-Auth-Token: ' . $token,
    ]);
}

/**
 * Update user profile
 */
function updateProfile(array $data): array {
    $token = getAuthToken();
    if (!$token) {
        return ['success' => false, 'error' => 'Not authenticated'];
    }
    
    $result = neusApiRequest('/profile', 'PATCH', $data, [
        'X-Auth-Token: ' . $token,
    ]);
    
    if ($result['success']) {
        // Refresh session data
        refreshUserData();
    }
    
    return $result;
}

// Initialize auth on load
initAuth();
?>
