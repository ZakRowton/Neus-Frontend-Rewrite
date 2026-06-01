<?php
/**
 * NEUS Frontend Rewrite - SDK Bridge
 * PHP proxy to NEUS SDK functionality
 * Provides server-side SDK methods that mirror the JavaScript SDK
 */

if (!defined('NEUS_INIT')) {
    require_once __DIR__ . '/../config/config.php';
    require_once __DIR__ . '/../includes/functions.php';
}

/**
 * NEUS SDK Bridge Class
 * Mirrors the JavaScript SDK for server-side operations
 */
class NeusSDK {
    private string $apiUrl;
    private ?string $authToken;
    
    public function __construct(?string $token = null) {
        $this->apiUrl = NEUS_API_URL;
        $this->authToken = $token ?? getAuthToken();
    }
    
    /**
     * Set authentication token
     */
    public function setToken(string $token): void {
        $this->authToken = $token;
    }
    
    /**
     * Get authentication headers
     */
    private function getHeaders(): array {
        $headers = [
            'Accept: application/json',
            'Content-Type: application/json',
        ];
        
        if ($this->authToken) {
            $headers[] = 'Authorization: Bearer ' . $this->authToken;
        }
        
        return $headers;
    }
    
    /**
     * Make SDK request
     */
    private function request(string $method, string $endpoint, array $data = []): array {
        $url = rtrim($this->apiUrl, '/') . '/' . ltrim($endpoint, '/');
        
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $this->getHeaders());
        curl_setopt($ch, CURLOPT_TIMEOUT, 30);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, !NEUS_DEBUG);
        
        if (!empty($data)) {
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
        }
        
        if ($method !== 'GET') {
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $method);
        }
        
        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $error = curl_error($ch);
        curl_close($ch);
        
        if ($error) {
            return ['success' => false, 'error' => $error];
        }
        
        $decoded = json_decode($response, true);
        
        return [
            'success' => $httpCode >= 200 && $httpCode < 300,
            'data' => $decoded,
            'httpCode' => $httpCode,
        ];
    }
    
    // === Identity Methods ===
    
    /**
     * Get current identity/profile
     */
    public function getIdentity(): array {
        return $this->request('GET', '/identity');
    }
    
    /**
     * Update identity
     */
    public function updateIdentity(array $data): array {
        return $this->request('PATCH', '/identity', $data);
    }
    
    /**
     * Get identity by DID
     */
    public function getIdentityByDid(string $did): array {
        return $this->request('GET', '/identity/' . urlencode($did));
    }
    
    // === Proof Methods ===
    
    /**
     * Create a proof
     */
    public function createProof(array $data): array {
        return $this->request('POST', '/proofs', $data);
    }
    
    /**
     * Get proof by qHash
     */
    public function getProof(string $qHash): array {
        return $this->request('GET', '/proofs/' . urlencode($qHash));
    }
    
    /**
     * Verify a proof
     */
    public function verifyProof(string $qHash, array $data = []): array {
        return $this->request('POST', '/proofs/' . urlencode($qHash) . '/verify', $data);
    }
    
    /**
     * List proofs
     */
    public function listProofs(array $filters = []): array {
        $query = http_build_query($filters);
        return $this->request('GET', '/proofs?' . $query);
    }
    
    /**
     * Revoke a proof
     */
    public function revokeProof(string $qHash): array {
        return $this->request('POST', '/proofs/' . urlencode($qHash) . '/revoke');
    }
    
    // === Agent Methods ===
    
    /**
     * Create an agent
     */
    public function createAgent(array $data): array {
        return $this->request('POST', '/agents', $data);
    }
    
    /**
     * Get agent by ID
     */
    public function getAgent(string $agentId): array {
        return $this->request('GET', '/agents/' . urlencode($agentId));
    }
    
    /**
     * List agents
     */
    public function listAgents(): array {
        return $this->request('GET', '/agents');
    }
    
    /**
     * Link agent to principal
     */
    public function linkAgent(string $agentId, array $data): array {
        return $this->request('POST', '/agents/' . urlencode($agentId) . '/link', $data);
    }
    
    /**
     * Check agent link status
     */
    public function checkAgentLink(string $agentWallet): array {
        return $this->request('GET', '/agents/link?agentWallet=' . urlencode($agentWallet));
    }
    
    /**
     * Update agent
     */
    public function updateAgent(string $agentId, array $data): array {
        return $this->request('PATCH', '/agents/' . urlencode($agentId), $data);
    }
    
    /**
     * Delete agent
     */
    public function deleteAgent(string $agentId): array {
        return $this->request('DELETE', '/agents/' . urlencode($agentId));
    }
    
    // === Verifier Methods ===
    
    /**
     * Get verifiers catalog
     */
    public function getVerifiers(): array {
        return $this->request('GET', '/verifiers');
    }
    
    /**
     * Get verifier details
     */
    public function getVerifier(string $verifierId): array {
        return $this->request('GET', '/verifiers/' . urlencode($verifierId));
    }
    
    /**
     * Check proofs against verifiers
     */
    public function checkProofs(array $verifierIds, string $wallet): array {
        return $this->request('POST', '/proofs/check', [
            'verifiers' => $verifierIds,
            'wallet' => $wallet,
        ]);
    }
    
    // === Credits Methods ===
    
    /**
     * Get credits balance
     */
    public function getCredits(): array {
        return $this->request('GET', '/credits');
    }
    
    /**
     * Purchase credits
     */
    public function purchaseCredits(int $amount, string $paymentMethod): array {
        return $this->request('POST', '/credits/purchase', [
            'amount' => $amount,
            'paymentMethod' => $paymentMethod,
        ]);
    }
    
    /**
     * Get credit transactions
     */
    public function getCreditHistory(): array {
        return $this->request('GET', '/credits/history');
    }
    
    // === Chat/Zeus Methods ===
    
    /**
     * Send message to Zeus
     */
    public function chat(string $message, array $context = []): array {
        return $this->request('POST', '/chat', [
            'message' => $message,
            'context' => $context,
        ]);
    }
    
    /**
     * Get chat history
     */
    public function getChatHistory(): array {
        return $this->request('GET', '/chat/history');
    }
    
    // === Admin Methods ===
    
    /**
     * Get admin stats
     */
    public function getAdminStats(): array {
        return $this->request('GET', '/admin/stats');
    }
    
    /**
     * List users (admin)
     */
    public function listUsers(array $filters = []): array {
        $query = http_build_query($filters);
        return $this->request('GET', '/admin/users?' . $query);
    }
    
    /**
     * Get user details (admin)
     */
    public function getUser(string $userId): array {
        return $this->request('GET', '/admin/users/' . urlencode($userId));
    }
    
    // === Wallet/Auth Methods ===
    
    /**
     * Link wallet
     */
    public function linkWallet(string $wallet, string $signature, string $message): array {
        return $this->request('POST', '/auth/link-wallet', [
            'wallet' => $wallet,
            'signature' => $signature,
            'message' => $message,
        ]);
    }
    
    /**
     * Unlink wallet
     */
    public function unlinkWallet(string $wallet): array {
        return $this->request('POST', '/auth/unlink-wallet', [
            'wallet' => $wallet,
        ]);
    }
    
    /**
     * Get linked wallets
     */
    public function getLinkedWallets(): array {
        return $this->request('GET', '/auth/wallets');
    }
    
    // === NEUS Context ===
    
    /**
     * Get NEUS context (me, verifiers, etc.)
     */
    public function getContext(): array {
        return $this->request('GET', '/context');
    }
    
    /**
     * Get public profile
     */
    public function getPublicProfile(string $identifier): array {
        return $this->request('GET', '/me?identifier=' . urlencode($identifier));
    }
    
    // === Secrets (for agents) ===
    
    /**
     * Create a secret
     */
    public function createSecret(string $alias, string $content, array $tags = []): array {
        return $this->request('POST', '/secrets', [
            'alias' => $alias,
            'content' => $content,
            'tags' => $tags,
        ]);
    }
    
    /**
     * List secrets
     */
    public function listSecrets(): array {
        return $this->request('GET', '/secrets');
    }
    
    /**
     * Revoke a secret
     */
    public function revokeSecret(string $qHash): array {
        return $this->request('POST', '/secrets/' . urlencode($qHash) . '/revoke');
    }
}

/**
 * Helper function to get SDK instance
 */
function neusSdk(?string $token = null): NeusSDK {
    return new NeusSDK($token);
}
?>
