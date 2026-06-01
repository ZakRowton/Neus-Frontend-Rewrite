<?php
/**
 * NEUS Frontend Rewrite - Route Definitions
 * Maps clean URLs to PHP page files (mirrors Next.js App Router)
 */

if (!defined('NEUS_INIT')) {
    require_once __DIR__ . '/config.php';
}

/**
 * Route definitions: path => [file, title, description, requiresAuth, layout]
 */
$ROUTES = [
    // Landing & Marketing
    '/' => ['file' => 'pages/landing.php', 'title' => 'NEUS Network - The Sovereign Identity Layer', 'description' => 'NEUS is a sovereign identity layer powered by zero-knowledge proofs. Verify anything. Trust no one.', 'auth' => false, 'layout' => 'default'],
    '/genesis' => ['file' => 'pages/genesis.php', 'title' => 'Genesis Campaign - NEUS Network', 'description' => 'Join the Genesis Campaign and be among the first to establish your sovereign identity.', 'auth' => false, 'layout' => 'default'],
    
    // Auth
    '/login' => ['file' => 'pages/auth/login.php', 'title' => 'Login - NEUS Network', 'description' => 'Sign in to your NEUS Network account.', 'auth' => false, 'layout' => 'auth'],
    '/signup' => ['file' => 'pages/auth/signup.php', 'title' => 'Sign Up - NEUS Network', 'description' => 'Create your NEUS Network account.', 'auth' => false, 'layout' => 'auth'],
    '/wallet-connect' => ['file' => 'pages/auth/wallet-connect.php', 'title' => 'Connect Wallet - NEUS Network', 'description' => 'Connect your blockchain wallet to NEUS.', 'auth' => false, 'layout' => 'auth'],
    '/logout' => ['file' => 'pages/auth/logout.php', 'title' => 'Logout - NEUS Network', 'description' => '', 'auth' => false, 'layout' => 'none'],
    
    // Dashboard
    '/dashboard' => ['file' => 'pages/dashboard.php', 'title' => 'Dashboard - NEUS Network', 'description' => 'Your NEUS Network dashboard.', 'auth' => true, 'layout' => 'dashboard'],
    
    // Profile & Identity
    '/profile' => ['file' => 'pages/profile/index.php', 'title' => 'Profile - NEUS Network', 'description' => 'Manage your NEUS profile.', 'auth' => true, 'layout' => 'dashboard'],
    '/profile/edit' => ['file' => 'pages/profile/edit.php', 'title' => 'Edit Profile - NEUS Network', 'description' => 'Edit your NEUS profile.', 'auth' => true, 'layout' => 'dashboard'],
    '/profile/security' => ['file' => 'pages/profile/security.php', 'title' => 'Security Settings - NEUS Network', 'description' => 'Manage your security settings.', 'auth' => true, 'layout' => 'dashboard'],
    '/profile/linked-accounts' => ['file' => 'pages/profile/linked-accounts.php', 'title' => 'Linked Accounts - NEUS Network', 'description' => 'Manage your linked blockchain accounts.', 'auth' => true, 'layout' => 'dashboard'],
    '/identity' => ['file' => 'pages/identity/index.php', 'title' => 'Identity - NEUS Network', 'description' => 'Your sovereign identity dashboard.', 'auth' => true, 'layout' => 'dashboard'],
    
    // Proofs
    '/proofs' => ['file' => 'pages/proofs/index.php', 'title' => 'Proof Library - NEUS Network', 'description' => 'Browse and manage your verification proofs.', 'auth' => true, 'layout' => 'dashboard'],
    '/proofs/create' => ['file' => 'pages/proofs/create.php', 'title' => 'Create Proof - NEUS Network', 'description' => 'Create a new verification proof.', 'auth' => true, 'layout' => 'dashboard'],
    '/proofs/verify' => ['file' => 'pages/proofs/verify.php', 'title' => 'Verify - NEUS Network', 'description' => 'Verify a claim or identity.', 'auth' => false, 'layout' => 'default'],
    '/proofs/status' => ['file' => 'pages/proofs/status.php', 'title' => 'Proof Status - NEUS Network', 'description' => 'Check proof verification status.', 'auth' => false, 'layout' => 'default'],
    '/proofs/library' => ['file' => 'pages/proofs/library.php', 'title' => 'Proof Library - NEUS Network', 'description' => 'Browse the public proof library.', 'auth' => false, 'layout' => 'default'],
    
    // Agents
    '/agents' => ['file' => 'pages/agents/index.php', 'title' => 'Agents - NEUS Network', 'description' => 'Manage your NEUS agents.', 'auth' => true, 'layout' => 'dashboard'],
    '/agents/create' => ['file' => 'pages/agents/create.php', 'title' => 'Create Agent - NEUS Network', 'description' => 'Create a new NEUS agent.', 'auth' => true, 'layout' => 'dashboard'],
    '/agents/link' => ['file' => 'pages/agents/link.php', 'title' => 'Link Agent - NEUS Network', 'description' => 'Link an agent to your account.', 'auth' => true, 'layout' => 'dashboard'],
    
    // Agent Detail (dynamic route)
    '/agent' => ['file' => 'pages/agent/detail.php', 'title' => 'Agent - NEUS Network', 'description' => 'Agent details.', 'auth' => false, 'layout' => 'default'],
    
    // Zeus AI Chat
    '/chat' => ['file' => 'pages/chat/index.php', 'title' => 'Zeus AI Chat - NEUS Network', 'description' => 'Chat with Zeus AI.', 'auth' => true, 'layout' => 'dashboard'],
    '/chat/history' => ['file' => 'pages/chat/history.php', 'title' => 'Chat History - NEUS Network', 'description' => 'Your chat history with Zeus.', 'auth' => true, 'layout' => 'dashboard'],
    
    // Credits
    '/credits' => ['file' => 'pages/credits/index.php', 'title' => 'Credits - NEUS Network', 'description' => 'Manage your NEUS credits.', 'auth' => true, 'layout' => 'dashboard'],
    '/credits/buy' => ['file' => 'pages/credits/buy.php', 'title' => 'Buy Credits - NEUS Network', 'description' => 'Purchase NEUS credits.', 'auth' => true, 'layout' => 'dashboard'],
    '/credits/history' => ['file' => 'pages/credits/history.php', 'title' => 'Credit History - NEUS Network', 'description' => 'Your credit transaction history.', 'auth' => true, 'layout' => 'dashboard'],
    
    // Admin
    '/admin' => ['file' => 'pages/admin/index.php', 'title' => 'Admin Panel - NEUS Network', 'description' => 'NEUS Network administration.', 'auth' => true, 'layout' => 'dashboard'],
    '/admin/users' => ['file' => 'pages/admin/users.php', 'title' => 'User Management - NEUS Network', 'description' => 'Manage NEUS users.', 'auth' => true, 'layout' => 'dashboard'],
    '/admin/proofs' => ['file' => 'pages/admin/proofs.php', 'title' => 'Proof Management - NEUS Network', 'description' => 'Manage proofs.', 'auth' => true, 'layout' => 'dashboard'],
    '/admin/agents' => ['file' => 'pages/admin/agents.php', 'title' => 'Agent Management - NEUS Network', 'description' => 'Manage agents.', 'auth' => true, 'layout' => 'dashboard'],
    '/admin/settings' => ['file' => 'pages/admin/settings.php', 'title' => 'Settings - NEUS Network', 'description' => 'System settings.', 'auth' => true, 'layout' => 'dashboard'],
    '/admin/observability' => ['file' => 'pages/admin/observability.php', 'title' => 'Observability - NEUS Network', 'description' => 'System observability and monitoring.', 'auth' => true, 'layout' => 'dashboard'],
    
    // Verification flow
    '/verify' => ['file' => 'pages/verify/index.php', 'title' => 'Verify - NEUS Network', 'description' => 'Verification portal.', 'auth' => false, 'layout' => 'default'],
    
    // Public pages
    '/about' => ['file' => 'pages/about.php', 'title' => 'About - NEUS Network', 'description' => 'Learn about NEUS Network.', 'auth' => false, 'layout' => 'default'],
    '/docs' => ['file' => 'pages/docs/index.php', 'title' => 'Documentation - NEUS Network', 'description' => 'NEUS Network documentation.', 'auth' => false, 'layout' => 'default'],
    '/contact' => ['file' => 'pages/contact.php', 'title' => 'Contact - NEUS Network', 'description' => 'Contact NEUS Network.', 'auth' => false, 'layout' => 'default'],
    
    // Error pages
    '/404' => ['file' => 'pages/404.php', 'title' => 'Page Not Found - NEUS Network', 'description' => 'The requested page was not found.', 'auth' => false, 'layout' => 'default'],
    '/500' => ['file' => 'pages/500.php', 'title' => 'Server Error - NEUS Network', 'description' => 'An internal server error occurred.', 'auth' => false, 'layout' => 'default'],
];

/**
 * Get route file path for a given URL
 */
function getRouteFile(string $route): ?string {
    global $ROUTES;
    
    // Exact match
    if (isset($ROUTES[$route])) {
        return $ROUTES[$route]['file'];
    }
    
    // Check for dynamic routes (e.g., /agent/some-id)
    $parts = explode('/', trim($route, '/'));
    
    // /agent/:agentId
    if (count($parts) === 2 && $parts[0] === 'agent') {
        return 'pages/agent/detail.php';
    }
    
    // /verify/:qHash
    if (count($parts) === 2 && $parts[0] === 'verify') {
        return 'pages/verify/detail.php';
    }
    
    // /proofs/:qHash
    if (count($parts) === 2 && $parts[0] === 'proofs') {
        return 'pages/proofs/detail.php';
    }
    
    return null;
}

/**
 * Get page metadata for a route
 */
function getPageMeta(string $route): array {
    global $ROUTES;
    
    if (isset($ROUTES[$route])) {
        return [
            'title' => $ROUTES[$route]['title'],
            'description' => $ROUTES[$route]['description'],
            'requiresAuth' => $ROUTES[$route]['auth'],
            'layout' => $ROUTES[$route]['layout'],
        ];
    }
    
    return [
        'title' => 'NEUS Network',
        'description' => 'The Sovereign Identity Layer',
        'requiresAuth' => false,
        'layout' => 'default',
    ];
}

/**
 * Check if a route requires authentication
 */
function routeRequiresAuth(string $route): bool {
    $meta = getPageMeta($route);
    return $meta['requiresAuth'] ?? false;
}

/**
 * Get all public routes (for sitemap, navigation)
 */
function getPublicRoutes(): array {
    global $ROUTES;
    $public = [];
    foreach ($ROUTES as $path => $meta) {
        if (!$meta['auth'] && $meta['layout'] !== 'none') {
            $public[$path] = $meta;
        }
    }
    return $public;
}

/**
 * Get all authenticated routes (for navigation menus)
 */
function getAuthRoutes(): array {
    global $ROUTES;
    $auth = [];
    foreach ($ROUTES as $path => $meta) {
        if ($meta['auth']) {
            $auth[$path] = $meta;
        }
    }
    return $auth;
}
?>
