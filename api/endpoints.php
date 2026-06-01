<?php
/**
 * NEUS Frontend Rewrite - API Endpoints
 * Local API endpoints for AJAX requests from the frontend
 */

if (!defined('NEUS_INIT')) {
    require_once __DIR__ . '/../config/config.php';
    require_once __DIR__ . '/../includes/functions.php';
    require_once __DIR__ . '/../includes/auth.php';
}

header('Content-Type: application/json; charset=utf-8');

$action = $_GET['action'] ?? '';
$method = $_SERVER['REQUEST_METHOD'];

switch ($action) {
    case 'auth_status':
        jsonResponse([
            'authenticated' => isAuthenticated(),
            'user' => getCurrentUser(),
            'wallet' => getCurrentWallet(),
        ]);
        break;
        
    case 'logout':
        logout();
        jsonResponse(['success' => true]);
        break;
        
    case 'user_profile':
        requireAuth();
        $result = refreshUserData();
        jsonResponse($result);
        break;
        
    case 'dashboard_stats':
        requireAuth();
        $result = neusApiRequest('/dashboard', 'GET');
        jsonResponse($result['data'] ?? []);
        break;
        
    case 'proofs_list':
        requireAuth();
        $filters = [
            'status' => $_GET['status'] ?? '',
            'search' => $_GET['search'] ?? '',
        ];
        $result = neusApiRequest('/proofs?' . http_build_query(array_filter($filters)), 'GET');
        jsonResponse($result['data'] ?? []);
        break;
        
    case 'agents_list':
        requireAuth();
        $result = neusApiRequest('/agents', 'GET');
        jsonResponse($result['data'] ?? []);
        break;
        
    case 'chat':
        requireAuth();
        $body = getRequestBody();
        $result = neusApiRequest('/chat', 'POST', $body);
        jsonResponse($result['data'] ?? []);
        break;
        
    case 'chat_history':
        requireAuth();
        $result = neusApiRequest('/chat/history', 'GET');
        jsonResponse($result['data'] ?? []);
        break;
        
    case 'credits_balance':
        requireAuth();
        $result = neusApiRequest('/credits', 'GET');
        jsonResponse($result['data'] ?? []);
        break;
        
    case 'verifiers':
        $result = neusApiRequest('/verifiers', 'GET');
        jsonResponse($result['data'] ?? []);
        break;
        
    case 'chains':
        jsonResponse(['chains' => getAllChains()]);
        break;
        
    default:
        apiError('Unknown action: ' . $action, 404);
}
?>
