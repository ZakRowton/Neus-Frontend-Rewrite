<?php
/**
 * NEUS Frontend Rewrite - Database Layer
 * Abstraction for Cosmos DB and local caching
 */

if (!defined('NEUS_INIT')) {
    require_once __DIR__ . '/../config/config.php';
}

/**
 * Cosmos DB Client Class
 * Provides interface to Azure Cosmos DB (mirrors Next.js Cosmos integration)
 */
class CosmosDB {
    private string $endpoint;
    private string $key;
    private string $database;
    private array $containers = [];
    private array $cache = [];
    
    public function __construct(
        string $endpoint = COSMOS_ENDPOINT,
        string $key = COSMOS_KEY,
        string $database = COSMOS_DATABASE
    ) {
        $this->endpoint = rtrim($endpoint, '/');
        $this->key = $key;
        $this->database = $database;
    }
    
    /**
     * Check if Cosmos DB is configured
     */
    public function isConfigured(): bool {
        return !empty($this->key) && !empty($this->endpoint);
    }
    
    /**
     * Get database link
     */
    private function getDbLink(): string {
        return 'dbs/' . $this->database;
    }
    
    /**
     * Generate authorization token for Cosmos DB REST API
     */
    private function generateAuthToken(string $verb, string $resourceType, string $resourceLink, string $date): string {
        $payload = strtolower($verb) . "\n" .
                   strtolower($resourceType) . "\n" .
                   $resourceLink . "\n" .
                   strtolower($date) . "\n" .
                   "\n";
        
        $signature = base64_encode(hash_hmac('sha256', $payload, base64_decode($this->key), true));
        
        return urlencode('type=master&ver=1.0&sig=' . $signature);
    }
    
    /**
     * Make request to Cosmos DB
     */
    private function request(string $method, string $path, array $data = null): array {
        if (!$this->isConfigured()) {
            return ['success' => false, 'error' => 'Cosmos DB not configured'];
        }
        
        $date = gmdate('D, d M Y H:i:s T');
        $resourceType = 'docs';
        $resourceLink = $path;
        
        $authToken = $this->generateAuthToken($method, $resourceType, $resourceLink, $date);
        
        $url = $this->endpoint . '/' . $path;
        
        $headers = [
            'Authorization: ' . $authToken,
            'x-ms-date: ' . $date,
            'x-ms-version: 2018-12-31',
            'Content-Type: application/json',
            'Accept: application/json',
        ];
        
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_TIMEOUT, 30);
        
        if ($data !== null) {
            $json = json_encode($data);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $json);
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
    
    /**
     * Create a document
     */
    public function createDocument(string $container, array $document): array {
        $path = $this->getDbLink() . '/colls/' . $container . '/docs';
        
        // Add required Cosmos DB fields
        $document['id'] = $document['id'] ?? generateId();
        $document['_ts'] = time();
        
        return $this->request('POST', $path, $document);
    }
    
    /**
     * Read a document by ID
     */
    public function readDocument(string $container, string $id): array {
        // Check cache first
        $cacheKey = $container . ':' . $id;
        if (isset($this->cache[$cacheKey])) {
            return ['success' => true, 'data' => $this->cache[$cacheKey], 'cached' => true];
        }
        
        $path = $this->getDbLink() . '/colls/' . $container . '/docs/' . $id;
        $result = $this->request('GET', $path);
        
        if ($result['success'] && isset($result['data'])) {
            $this->cache[$cacheKey] = $result['data'];
        }
        
        return $result;
    }
    
    /**
     * Query documents with SQL
     */
    public function queryDocuments(string $container, string $query, array $parameters = []): array {
        $path = $this->getDbLink() . '/colls/' . $container . '/docs';
        
        $body = [
            'query' => $query,
            'parameters' => $parameters,
        ];
        
        return $this->request('POST', $path, $body);
    }
    
    /**
     * Update a document
     */
    public function updateDocument(string $container, string $id, array $updates): array {
        $path = $this->getDbLink() . '/colls/' . $container . '/docs/' . $id;
        
        $updates['_ts'] = time();
        
        return $this->request('PUT', $path, $updates);
    }
    
    /**
     * Delete a document
     */
    public function deleteDocument(string $container, string $id): array {
        $path = $this->getDbLink() . '/colls/' . $container . '/docs/' . $id;
        return $this->request('DELETE', $path);
    }
    
    /**
     * Clear cache
     */
    public function clearCache(): void {
        $this->cache = [];
    }
}

/**
 * Local File-based Database (fallback when Cosmos DB is not available)
 */
class LocalDB {
    private string $dataDir;
    
    public function __construct(string $dataDir = __DIR__ . '/../data') {
        $this->dataDir = $dataDir;
        if (!is_dir($this->dataDir)) {
            mkdir($this->dataDir, 0755, true);
        }
    }
    
    private function getFilePath(string $collection, string $id): string {
        $dir = $this->dataDir . '/' . $collection;
        if (!is_dir($dir)) {
            mkdir($dir, 0755, true);
        }
        return $dir . '/' . $id . '.json';
    }
    
    public function create(string $collection, string $id, array $data): bool {
        $data['id'] = $id;
        $data['created_at'] = time();
        $data['updated_at'] = time();
        
        $file = $this->getFilePath($collection, $id);
        return file_put_contents($file, json_encode($data, JSON_PRETTY_PRINT)) !== false;
    }
    
    public function read(string $collection, string $id): ?array {
        $file = $this->getFilePath($collection, $id);
        if (!file_exists($file)) return null;
        
        $content = file_get_contents($file);
        return json_decode($content, true);
    }
    
    public function update(string $collection, string $id, array $data): bool {
        $existing = $this->read($collection, $id) ?? [];
        $merged = array_merge($existing, $data);
        $merged['updated_at'] = time();
        
        $file = $this->getFilePath($collection, $id);
        return file_put_contents($file, json_encode($merged, JSON_PRETTY_PRINT)) !== false;
    }
    
    public function delete(string $collection, string $id): bool {
        $file = $this->getFilePath($collection, $id);
        if (file_exists($file)) {
            return unlink($file);
        }
        return false;
    }
    
    public function list(string $collection): array {
        $dir = $this->dataDir . '/' . $collection;
        if (!is_dir($dir)) return [];
        
        $items = [];
        foreach (glob($dir . '/*.json') as $file) {
            $content = file_get_contents($file);
            $items[] = json_decode($content, true);
        }
        return $items;
    }
    
    public function query(string $collection, callable $filter): array {
        $items = $this->list($collection);
        return array_filter($items, $filter);
    }
}

/**
 * Database factory - returns appropriate DB instance
 */
function getDatabase(): CosmosDB|LocalDB {
    static $db = null;
    
    if ($db === null) {
        $cosmos = new CosmosDB();
        if ($cosmos->isConfigured()) {
            $db = $cosmos;
        } else {
            $db = new LocalDB();
        }
    }
    
    return $db;
}

/**
 * Cache helper - simple file-based caching
 */
function cacheGet(string $key): ?array {
    $file = __DIR__ . '/../cache/' . md5($key) . '.json';
    if (!file_exists($file)) return null;
    
    $data = json_decode(file_get_contents($file), true);
    if (!$data || ($data['expires'] ?? 0) < time()) {
        unlink($file);
        return null;
    }
    
    return $data['value'];
}

function cacheSet(string $key, mixed $value, int $ttl = 300): void {
    $dir = __DIR__ . '/../cache';
    if (!is_dir($dir)) mkdir($dir, 0755, true);
    
    $file = $dir . '/' . md5($key) . '.json';
    file_put_contents($file, json_encode([
        'expires' => time() + $ttl,
        'value' => $value,
    ]));
}

function cacheDelete(string $key): void {
    $file = __DIR__ . '/../cache/' . md5($key) . '.json';
    if (file_exists($file)) unlink($file);
}
?>
