<?php
/**
 * NEUS Frontend Rewrite - Header Component
 * Mirrors Next.js Header component with navigation, auth state, theme toggle
 */

$isLanding = ($GLOBALS['CURRENT_ROUTE'] ?? '') === '/';
$isGenesis = ($GLOBALS['CURRENT_ROUTE'] ?? '') === '/genesis';
$isAuthPage = in_array($GLOBALS['CURRENT_ROUTE'] ?? '', ['/login', '/signup', '/wallet-connect']);
$isDashboard = ($GLOBALS['CURRENT_ROUTE'] ?? '') === '/dashboard' || str_starts_with($GLOBALS['CURRENT_ROUTE'] ?? '', '/dashboard/');

$user = getCurrentUser();
$wallet = getCurrentWallet();
$isLoggedIn = isAuthenticated();
?>

<!-- Header / Navigation -->
<header id="neus-header" class="fixed top-0 left-0 right-0 z-[100] transition-all duration-300">
    <div class="header-glass absolute inset-0 bg-neus-black/80 backdrop-blur-xl border-b border-neus-border"></div>
    
    <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex items-center justify-between h-16">
            
            <!-- Logo -->
            <a href="<?php echo pageUrl('/'); ?>" class="flex items-center gap-3 group">
                <div class="w-8 h-8 rounded-lg bg-gradient-to-br from-neus-gold to-neus-gold-dim flex items-center justify-center">
                    <svg class="w-5 h-5 text-neus-black" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M12 2L2 7l10 5 10-5-10-5z"/>
                        <path d="M2 17l10 5 10-5"/>
                        <path d="M2 12l10 5 10-5"/>
                    </svg>
                </div>
                <span class="text-xl font-bold text-neus-gold tracking-tight">NEUS</span>
            </a>
            
            <!-- Desktop Navigation -->
            <nav class="hidden md:flex items-center gap-1">
                <?php if (!$isLoggedIn): ?>
                    <a href="<?php echo pageUrl('/'); ?>" 
                       class="nav-link px-3 py-2 rounded-lg text-sm text-neus-text-secondary hover:text-neus-gold transition-colors">
                        Home
                    </a>
                    <a href="<?php echo pageUrl('/about'); ?>" 
                       class="nav-link px-3 py-2 rounded-lg text-sm text-neus-text-secondary hover:text-neus-gold transition-colors">
                        About
                    </a>
                    <a href="<?php echo pageUrl('/docs'); ?>" 
                       class="nav-link px-3 py-2 rounded-lg text-sm text-neus-text-secondary hover:text-neus-gold transition-colors">
                        Docs
                    </a>
                    <?php if (isFeatureEnabled('genesis')): ?>
                    <a href="<?php echo pageUrl('/genesis'); ?>" 
                       class="nav-link px-3 py-2 rounded-lg text-sm text-neus-gold hover:text-neus-gold-light transition-colors">
                        <span class="flex items-center gap-1">
                            <i class="fas fa-sparkles text-xs"></i>
                            Genesis
                        </span>
                    </a>
                    <?php endif; ?>
                <?php else: ?>
                    <a href="<?php echo pageUrl('/dashboard'); ?>" 
                       class="nav-link px-3 py-2 rounded-lg text-sm text-neus-text-secondary hover:text-neus-gold transition-colors">
                        Dashboard
                    </a>
                    <a href="<?php echo pageUrl('/proofs'); ?>" 
                       class="nav-link px-3 py-2 rounded-lg text-sm text-neus-text-secondary hover:text-neus-gold transition-colors">
                        Proofs
                    </a>
                    <?php if (isFeatureEnabled('agents')): ?>
                    <a href="<?php echo pageUrl('/agents'); ?>" 
                       class="nav-link px-3 py-2 rounded-lg text-sm text-neus-text-secondary hover:text-neus-gold transition-colors">
                        Agents
                    </a>
                    <?php endif; ?>
                    <?php if (isFeatureEnabled('zeus')): ?>
                    <a href="<?php echo pageUrl('/chat'); ?>" 
                       class="nav-link px-3 py-2 rounded-lg text-sm text-neus-text-secondary hover:text-neus-gold transition-colors">
                        Zeus
                    </a>
                    <?php endif; ?
                <?php endif; ?>
            </nav>
            
            <!-- Right Actions -->
            <div class="flex items-center gap-3">
                
                <!-- Theme Toggle -->
                <button id="theme-toggle" class="w-9 h-9 rounded-lg flex items-center justify-center text-neus-text-secondary hover:text-neus-gold hover:bg-neus-gold/10 transition-all">
                    <i class="fas fa-moon dark-icon"></i>
                    <i class="fas fa-sun light-icon hidden"></i>
                </button>
                
                <?php if (!$isLoggedIn): ?>
                    <!-- Auth Buttons -->
                    <div class="hidden sm:flex items-center gap-2">
                        <a href="<?php echo pageUrl('/login'); ?>" 
                           class="px-4 py-2 text-sm text-neus-text-secondary hover:text-neus-gold transition-colors">
                            Sign In
                        </a>
                        <a href="<?php echo pageUrl('/signup'); ?>" 
                           class="px-4 py-2 text-sm bg-neus-gold/10 border border-neus-gold/30 text-neus-gold rounded-lg hover:bg-neus-gold/20 transition-all">
                            Get Started
                        </a>
                    </div>
                <?php else: ?>
                    <!-- User Menu -->
                    <div class="relative" id="user-menu">
                        <button id="user-menu-btn" class="flex items-center gap-2 px-3 py-2 rounded-lg hover:bg-neus-gold/10 transition-all">
                            <div class="w-8 h-8 rounded-full bg-gradient-to-br from-neus-gold to-neus-gold-dim flex items-center justify-center text-neus-black text-sm font-bold">
                                <?php echo strtoupper(substr($user['username'] ?? $user['email'] ?? 'U', 0, 1)); ?>
                            </div>
                            <span class="hidden sm:block text-sm text-neus-text-secondary">
                                <?php echo e($user['username'] ?? $user['email'] ?? 'User'); ?>
                            </span>
                            <i class="fas fa-chevron-down text-xs text-neus-text-muted"></i>
                        </button>
                        
                        <!-- Dropdown -->
                        <div id="user-dropdown" class="hidden absolute right-0 top-full mt-2 w-56 bg-neus-dark border border-neus-border rounded-xl shadow-2xl overflow-hidden">
                            <div class="p-3 border-b border-neus-border">
                                <p class="text-sm font-medium text-neus-cream"><?php echo e($user['username'] ?? 'User'); ?></p>
                                <p class="text-xs text-neus-text-muted truncate"><?php echo e($user['email'] ?? ''); ?></p>
                                <?php if ($wallet): ?
                                <p class="text-xs text-neus-gold font-mono mt-1 truncate"><?php echo e(substr($wallet, 0, 6) . '...' . substr($wallet, -4)); ?></p>
                                <?php endif; ?>
                            </div>
                            
                            <div class="p-2">
                                <a href="<?php echo pageUrl('/dashboard'); ?>" class="flex items-center gap-2 px-3 py-2 rounded-lg text-sm text-neus-text-secondary hover:text-neus-gold hover:bg-neus-gold/10 transition-all">
                                    <i class="fas fa-chart-line w-4"></i> Dashboard
                                </a>
                                <a href="<?php echo pageUrl('/profile'); ?>" class="flex items-center gap-2 px-3 py-2 rounded-lg text-sm text-neus-text-secondary hover:text-neus-gold hover:bg-neus-gold/10 transition-all">
                                    <i class="fas fa-user w-4"></i> Profile
                                </a>
                                <a href="<?php echo pageUrl('/identity'); ?>" class="flex items-center gap-2 px-3 py-2 rounded-lg text-sm text-neus-text-secondary hover:text-neus-gold hover:bg-neus-gold/10 transition-all">
                                    <i class="fas fa-fingerprint w-4"></i> Identity
                                </a>
                                <a href="<?php echo pageUrl('/credits'); ?>" class="flex items-center gap-2 px-3 py-2 rounded-lg text-sm text-neus-text-secondary hover:text-neus-gold hover:bg-neus-gold/10 transition-all">
                                    <i class="fas fa-coins w-4"></i> Credits
                                </a>
                                <hr class="border-neus-border my-2">
                                <a href="<?php echo pageUrl('/logout'); ?>" class="flex items-center gap-2 px-3 py-2 rounded-lg text-sm text-red-400 hover:text-red-300 hover:bg-red-500/10 transition-all">
                                    <i class="fas fa-sign-out-alt w-4"></i> Sign Out
                                </a>
                            </div>
                        </div>
                    </div>
                <?php endif; ?>
                
                <!-- Mobile Menu Button -->
                <button id="mobile-menu-btn" class="md:hidden w-9 h-9 rounded-lg flex items-center justify-center text-neus-text-secondary hover:text-neus-gold hover:bg-neus-gold/10 transition-all">
                    <i class="fas fa-bars"></i>
                </button>
            </div>
        </div>
    </div>
    
    <!-- Mobile Menu -->
    <div id="mobile-menu" class="hidden md:hidden border-t border-neus-border bg-neus-black/95 backdrop-blur-xl">
        <div class="px-4 py-4 space-y-1">
            <?php if (!$isLoggedIn): ?>
                <a href="<?php echo pageUrl('/'); ?>" class="block px-3 py-2 rounded-lg text-sm text-neus-text-secondary hover:text-neus-gold hover:bg-neus-gold/10">Home</a>
                <a href="<?php echo pageUrl('/about'); ?>" class="block px-3 py-2 rounded-lg text-sm text-neus-text-secondary hover:text-neus-gold hover:bg-neus-gold/10">About</a>
                <a href="<?php echo pageUrl('/docs'); ?>" class="block px-3 py-2 rounded-lg text-sm text-neus-text-secondary hover:text-neus-gold hover:bg-neus-gold/10">Docs</a>
                <a href="<?php echo pageUrl('/genesis'); ?>" class="block px-3 py-2 rounded-lg text-sm text-neus-gold hover:bg-neus-gold/10">Genesis Campaign</a>
                <hr class="border-neus-border">
                <a href="<?php echo pageUrl('/login'); ?>" class="block px-3 py-2 rounded-lg text-sm text-neus-text-secondary hover:text-neus-gold">Sign In</a>
                <a href="<?php echo pageUrl('/signup'); ?>" class="block px-3 py-2 rounded-lg text-sm bg-neus-gold/10 border border-neus-gold/30 text-neus-gold text-center">Get Started</a>
            <?php else: ?>
                <a href="<?php echo pageUrl('/dashboard'); ?>" class="block px-3 py-2 rounded-lg text-sm text-neus-text-secondary hover:text-neus-gold hover:bg-neus-gold/10">Dashboard</a>
                <a href="<?php echo pageUrl('/proofs'); ?>" class="block px-3 py-2 rounded-lg text-sm text-neus-text-secondary hover:text-neus-gold hover:bg-neus-gold/10">Proofs</a>
                <a href="<?php echo pageUrl('/agents'); ?>" class="block px-3 py-2 rounded-lg text-sm text-neus-text-secondary hover:text-neus-gold hover:bg-neus-gold/10">Agents</a>
                <a href="<?php echo pageUrl('/chat'); ?>" class="block px-3 py-2 rounded-lg text-sm text-neus-text-secondary hover:text-neus-gold hover:bg-neus-gold/10">Zeus Chat</a>
                <hr class="border-neus-border">
                <a href="<?php echo pageUrl('/profile'); ?>" class="block px-3 py-2 rounded-lg text-sm text-neus-text-secondary hover:text-neus-gold hover:bg-neus-gold/10">Profile</a>
                <a href="<?php echo pageUrl('/credits'); ?>" class="block px-3 py-2 rounded-lg text-sm text-neus-text-secondary hover:text-neus-gold hover:bg-neus-gold/10">Credits</a>
                <a href="<?php echo pageUrl('/logout'); ?>" class="block px-3 py-2 rounded-lg text-sm text-red-400 hover:text-red-300">Sign Out</a>
            <?php endif; ?>
        </div>
    </div>
</header>

<!-- Spacer for fixed header -->
<div class="h-16"></div>
