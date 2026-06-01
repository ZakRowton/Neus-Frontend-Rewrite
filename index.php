<?php
/**
 * NEUS Frontend Rewrite - Main Entry Point
 * SPA-style router that mimics Next.js App Router behavior
 * Maps clean URLs to PHP page components
 */

require_once __DIR__ . '/config/config.php';
require_once __DIR__ . '/config/routes.php';
require_once __DIR__ . '/includes/functions.php';
require_once __DIR__ . '/includes/auth.php';

// Parse the request URI
$requestUri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$requestUri = trim($requestUri, '/');

// Remove base path if any
$basePath = trim(parse_url(NEUS_BASE_URL, PHP_URL_PATH) ?? '', '/');
if ($basePath && strpos($requestUri, $basePath) === 0) {
    $requestUri = substr($requestUri, strlen($basePath));
    $requestUri = trim($requestUri, '/');
}

// Get route from URL
$route = $requestUri ?: '/';

// Check if route exists
$pageFile = getRouteFile($route);

if (!$pageFile || !file_exists(__DIR__ . '/' . $pageFile)) {
    http_response_code(404);
    $pageFile = 'pages/404.php';
}

// Set current route for components
$GLOBALS['CURRENT_ROUTE'] = $route;

// Get page metadata
$pageMeta = getPageMeta($route);
$pageTitle = $pageMeta['title'] ?? 'NEUS Network';
$pageDescription = $pageMeta['description'] ?? 'NEUS Network - The Sovereign Identity Layer';

// Start output buffering
ob_start();
?>
<!DOCTYPE html>
<html lang="en" data-theme="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="<?php echo htmlspecialchars($pageDescription); ?>">
    <meta name="theme-color" content="#0a0a0a">
    <title><?php echo htmlspecialchars($pageTitle); ?></title>
    
    <!-- Favicon -->
    <link rel="icon" type="image/svg+xml" href="assets/images/neus-icon.svg">
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&family=JetBrains+Mono:wght@400;500&display=swap" rel="stylesheet">
    
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            darkMode: 'class',
            theme: {
                extend: {
                    colors: {
                        neus: {
                            black: '#0a0a0a',
                            dark: '#111111',
                            panel: 'rgba(17,17,17,0.8)',
                            gold: '#D4AF37',
                            'gold-light': '#F9F1D8',
                            'gold-dim': '#8a7326',
                            accent: '#D4AF37',
                            border: 'rgba(212,175,55,0.15)',
                            'border-hover': 'rgba(212,175,55,0.3)',
                            cream: '#F9F1D8',
                            maroon: '#5C2329',
                            'text-primary': '#F9F1D8',
                            'text-secondary': 'rgba(249,241,216,0.6)',
                            'text-muted': 'rgba(249,241,216,0.4)',
                            error: '#b91c1c',
                            success: '#16a34a',
                            info: '#3b82f6',
                            warning: '#f59e0b',
                        }
                    },
                    fontFamily: {
                        sans: ['Inter', 'system-ui', 'sans-serif'],
                        mono: ['JetBrains Mono', 'monospace'],
                    },
                    animation: {
                        'fade-in': 'fadeIn 0.5s ease-out',
                        'fade-in-up': 'fadeInUp 0.6s cubic-bezier(0.2,0.8,0.2,1)',
                        'pulse-slow': 'pulseSlow 4s ease-in-out infinite',
                        'shine': 'shine 4s linear infinite',
                        'scan': 'scan 2s cubic-bezier(0.4,0,0.2,1) infinite',
                        'spin-slow': 'spin 3s linear infinite',
                    },
                    keyframes: {
                        fadeIn: {
                            '0%': { opacity: '0' },
                            '100%': { opacity: '1' },
                        },
                        fadeInUp: {
                            '0%': { opacity: '0', transform: 'translateY(30px)' },
                            '100%': { opacity: '1', transform: 'translateY(0)' },
                        },
                        pulseSlow: {
                            '0%, 100%': { opacity: '1' },
                            '50%': { opacity: '0.6' },
                        },
                        shine: {
                            '0%': { backgroundPosition: '200% center' },
                            '100%': { backgroundPosition: '-200% center' },
                        },
                        scan: {
                            '0%': { transform: 'translateX(-100%)' },
                            '100%': { transform: 'translateX(100%)' },
                        },
                    },
                }
            }
        }
    </script>
    
    <!-- Bootstrap 5 (selective components) -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    
    <!-- NEUS Theme -->
    <link rel="stylesheet" href="assets/css/neus-theme.css">
    
    <!-- Page-specific styles -->
    <?php if (file_exists(__DIR__ . '/assets/css/pages/' . str_replace('/', '-', trim($route, '/')) . '.css')): ?>
    <link rel="stylesheet" href="assets/css/pages/<?php echo str_replace('/', '-', trim($route, '/')); ?>.css">
    <?php endif; ?>
</head>
<body class="bg-neus-black text-neus-cream min-h-screen">
    <!-- Noise overlay -->
    <div class="noise-overlay fixed inset-0 pointer-events-none z-[50] opacity-[0.03]"></div>
    
    <!-- App container -->
    <div id="app" class="relative z-10">
        <?php
        // Load shared components
        require_once __DIR__ . '/components/header.php';
        
        // Load the page content
        require_once __DIR__ . '/' . $pageFile;
        
        // Load footer
        require_once __DIR__ . '/components/footer.php';
        ?>
    </div>
    
    <!-- Toast notifications container -->
    <div id="toast-container" class="fixed bottom-4 right-4 z-[9999] flex flex-col gap-2"></div>
    
    <!-- Loading overlay -->
    <div id="loading-overlay" class="fixed inset-0 bg-neus-black/90 z-[100] hidden items-center justify-center">
        <div class="text-center">
            <div class="w-12 h-12 border-2 border-neus-gold border-t-transparent rounded-full animate-spin mx-auto mb-4"></div>
            <p class="text-neus-gold font-mono text-sm">Loading...</p>
        </div>
    </div>
    
    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="assets/js/app.js"></script>
    
    <!-- Page-specific scripts -->
    <?php if (file_exists(__DIR__ . '/assets/js/pages/' . str_replace('/', '-', trim($route, '/')) . '.js')): ?>
    <script src="assets/js/pages/<?php echo str_replace('/', '-', trim($route, '/')); ?>.js"></script>
    <?php endif; ?>
</body>
</html>
<?php
ob_end_flush();
?>
