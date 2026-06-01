<?php
/**
 * NEUS Frontend Rewrite - Sidebar Component (Dashboard Layout)
 */

if (!isAuthenticated()) return;

$user = getCurrentUser();
$currentRoute = $GLOBALS['CURRENT_ROUTE'] ?? '/';
$isAdmin = isAdmin();

function isActive(string $route): string {
    $current = $GLOBALS['CURRENT_ROUTE'] ?? '/';
    return str_starts_with($current, $route) ? 'active bg-neus-gold/10 text-neus-gold border-r-2 border-neus-gold' : 'text-neus-text-secondary hover:text-neus-gold hover:bg-neus-gold/5';
}
?>

<!-- Sidebar -->
<aside id="sidebar" class="fixed left-0 top-16 bottom-0 w-64 bg-neus-dark border-r border-neus-border z-40 transform -translate-x-full md:translate-x-0 transition-transform duration-300">
    <div class="h-full overflow-y-auto py-4">
        
        <!-- User Mini Profile -->
        <div class="px-4 mb-6">
            <div class="flex items-center gap-3 p-3 rounded-xl bg-neus-black/50 border border-neus-border">
                <div class="w-10 h-10 rounded-full bg-gradient-to-br from-neus-gold to-neus-gold-dim flex items-center justify-center text-neus-black font-bold">
                    <?php echo strtoupper(substr($user['username'] ?? $user['email'] ?? 'U', 0, 1)); ?>
                </div>
                <div class="min-w-0">
                    <p class="text-sm font-medium text-neus-cream truncate"><?php echo e($user['username'] ?? 'User'); ?></p>
                    <p class="text-xs text-neus-text-muted truncate"><?php echo e(substr(getCurrentWallet() ?? 'No wallet', 0, 12) . '...'); ?></p>
                </div>
            </div>
        </div>
        
        <!-- Navigation -->
        <nav class="px-2 space-y-1">
            <p class="px-3 py-2 text-xs font-semibold text-neus-text-muted uppercase tracking-wider">Main</p>
            
            <a href="<?php echo pageUrl('/dashboard'); ?>" class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm transition-all <?php echo isActive('/dashboard'); ?>">
                <i class="fas fa-chart-line w-5 text-center"></i>
                Dashboard
            </a>
            
            <a href="<?php echo pageUrl('/proofs'); ?>" class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm transition-all <?php echo isActive('/proofs'); ?>">
                <i class="fas fa-shield-alt w-5 text-center"></i>
                Proofs
            </a>
            
            <?php if (isFeatureEnabled('agents')): ?>
            <a href="<?php echo pageUrl('/agents'); ?>" class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm transition-all <?php echo isActive('/agents'); ?>">
                <i class="fas fa-robot w-5 text-center"></i>
                Agents
            </a>
            <?php endif; ?>
            
            <?php if (isFeatureEnabled('zeus')): ?>
            <a href="<?php echo pageUrl('/chat'); ?>" class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm transition-all <?php echo isActive('/chat'); ?>">
                <i class="fas fa-brain w-5 text-center"></i>
                Zeus AI
            </a>
            <?php endif; ?>
            
            <hr class="border-neus-border mx-3 my-3">
            
            <p class="px-3 py-2 text-xs font-semibold text-neus-text-muted uppercase tracking-wider">Identity</p>
            
            <a href="<?php echo pageUrl('/profile'); ?>" class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm transition-all <?php echo isActive('/profile'); ?>">
                <i class="fas fa-user w-5 text-center"></i>
                Profile
            </a>
            
            <a href="<?php echo pageUrl('/identity'); ?>" class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm transition-all <?php echo isActive('/identity'); ?>">
                <i class="fas fa-fingerprint w-5 text-center"></i>
                Identity
            </a>
            
            <a href="<?php echo pageUrl('/credits'); ?>" class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm transition-all <?php echo isActive('/credits'); ?>">
                <i class="fas fa-coins w-5 text-center"></i>
                Credits
            </a>
            
            <?php if ($isAdmin): ?>
            <hr class="border-neus-border mx-3 my-3">
            
            <p class="px-3 py-2 text-xs font-semibold text-neus-text-muted uppercase tracking-wider">Admin</p>
            
            <a href="<?php echo pageUrl('/admin'); ?>" class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm transition-all <?php echo isActive('/admin'); ?>">
                <i class="fas fa-cog w-5 text-center"></i>
                Dashboard
            </a>
            
            <a href="<?php echo pageUrl('/admin/users'); ?>" class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm transition-all <?php echo isActive('/admin/users'); ?>">
                <i class="fas fa-users w-5 text-center"></i>
                Users
            </a>
            
            <a href="<?php echo pageUrl('/admin/proofs'); ?>" class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm transition-all <?php echo isActive('/admin/proofs'); ?>">
                <i class="fas fa-shield-alt w-5 text-center"></i>
                Proofs
            </a>
            
            <a href="<?php echo pageUrl('/admin/agents'); ?>" class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm transition-all <?php echo isActive('/admin/agents'); ?>">
                <i class="fas fa-robot w-5 text-center"></i>
                Agents
            </a>
            
            <a href="<?php echo pageUrl('/admin/observability'); ?>" class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm transition-all <?php echo isActive('/admin/observability'); ?>">
                <i class="fas fa-chart-bar w-5 text-center"></i>
                Observability
            </a>
            <?php endif; ?>
        </nav>
        
        <!-- Bottom Actions -->
        <div class="absolute bottom-0 left-0 right-0 p-4 border-t border-neus-border">
            <a href="<?php echo pageUrl('/logout'); ?>" class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm text-red-400 hover:text-red-300 hover:bg-red-500/10 transition-all">
                <i class="fas fa-sign-out-alt w-5 text-center"></i>
                Sign Out
            </a>
        </div>
    </div>
</aside>

<!-- Mobile Sidebar Toggle -->
<button id="sidebar-toggle" class="md:hidden fixed bottom-6 left-6 z-50 w-12 h-12 rounded-full bg-neus-gold text-neus-black shadow-lg flex items-center justify-center">
    <i class="fas fa-bars"></i>
</button>
