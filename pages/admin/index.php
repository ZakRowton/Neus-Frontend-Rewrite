<?php
/**
 * NEUS Frontend Rewrite - Admin Dashboard
 */

requireAuth();
requireRole('admin');

// Fetch admin stats
$statsResponse = neusApiRequest('/admin/stats', 'GET');
$stats = $statsResponse['success'] ? ($statsResponse['data']['data'] ?? []) : [];
?>

<div class="dashboard-layout">
    
    <?php require_once __DIR__ . '/../components/sidebar.php'; ?>
    
    <main class="dashboard-content">
        
        <!-- Header -->
        <div class="mb-8">
            <h1 class="text-2xl font-bold text-neus-cream">Admin Dashboard</h1>
            <p class="text-sm text-neus-text-muted mt-1">System overview and management</p>
        </div>
        
        <!-- Stats Grid -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
            <div class="card-neus">
                <div class="flex items-center justify-between mb-2">
                    <span class="text-sm text-neus-text-secondary">Total Users</span>
                    <i class="fas fa-users text-neus-gold"></i>
                </div>
                <p class="text-2xl font-bold text-neus-cream"><?php echo formatNumber($stats['totalUsers'] ?? 0); ?></p>
            </div>
            
            <div class="card-neus">
                <div class="flex items-center justify-between mb-2">
                    <span class="text-sm text-neus-text-secondary">Total Proofs</span>
                    <i class="fas fa-shield-alt text-neus-gold"></i>
                </div>
                <p class="text-2xl font-bold text-neus-cream"><?php echo formatNumber($stats['totalProofs'] ?? 0); ?></p>
            </div>
            
            <div class="card-neus">
                <div class="flex items-center justify-between mb-2">
                    <span class="text-sm text-neus-text-secondary">Total Agents</span>
                    <i class="fas fa-robot text-neus-gold"></i>
                </div>
                <p class="text-2xl font-bold text-neus-cream"><?php echo formatNumber($stats['totalAgents'] ?? 0); ?></p>
            </div>
            
            <div class="card-neus">
                <div class="flex items-center justify-between mb-2">
                    <span class="text-sm text-neus-text-secondary">Active Now</span>
                    <i class="fas fa-bolt text-green-400"></i>
                </div>
                <p class="text-2xl font-bold text-green-400"><?php echo formatNumber($stats['activeNow'] ?? 0); ?></p>
            </div>
        </div>
        
        <!-- Quick Actions -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-8">
            <a href="<?php echo pageUrl('/admin/users'); ?>" class="card-neus hover:border-neus-gold/30 transition-all">
                <div class="flex items-center gap-4">
                    <div class="w-12 h-12 rounded-xl bg-blue-500/10 flex items-center justify-center">
                        <i class="fas fa-users text-xl text-blue-400"></i>
                    </div>
                    <div>
                        <h3 class="font-semibold text-neus-cream">Users</h3>
                        <p class="text-sm text-neus-text-muted">Manage user accounts</p>
                    </div>
                </div>
            </a>
            
            <a href="<?php echo pageUrl('/admin/proofs'); ?>" class="card-neus hover:border-neus-gold/30 transition-all">
                <div class="flex items-center gap-4">
                    <div class="w-12 h-12 rounded-xl bg-green-500/10 flex items-center justify-center">
                        <i class="fas fa-shield-alt text-xl text-green-400"></i>
                    </div>
                    <div>
                        <h3 class="font-semibold text-neus-cream">Proofs</h3>
                        <p class="text-sm text-neus-text-muted">Manage verification proofs</p>
                    </div>
                </div>
            </a>
            
            <a href="<?php echo pageUrl('/admin/agents'); ?>" class="card-neus hover:border-neus-gold/30 transition-all">
                <div class="flex items-center gap-4">
                    <div class="w-12 h-12 rounded-xl bg-purple-500/10 flex items-center justify-center">
                        <i class="fas fa-robot text-xl text-purple-400"></i>
                    </div>
                    <div>
                        <h3 class="font-semibold text-neus-cream">Agents</h3>
                        <p class="text-sm text-neus-text-muted">Manage AI agents</p>
                    </div>
                </div>
            </a>
        </div>
        
        <!-- System Status -->
        <div class="card-neus">
            <h3 class="text-lg font-semibold text-neus-cream mb-4">System Status</h3>
            
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                <div class="p-4 rounded-lg bg-neus-black/50 border border-neus-border">
                    <div class="flex items-center gap-2 mb-2">
                        <span class="w-2 h-2 rounded-full bg-green-400"></span>
                        <span class="text-sm text-neus-cream">API</span>
                    </div>
                    <p class="text-xs text-neus-text-muted">Operational</p>
                </div>
                
                <div class="p-4 rounded-lg bg-neus-black/50 border border-neus-border">
                    <div class="flex items-center gap-2 mb-2">
                        <span class="w-2 h-2 rounded-full bg-green-400"></span>
                        <span class="text-sm text-neus-cream">Database</span>
                    </div>
                    <p class="text-xs text-neus-text-muted">Operational</p>
                </div>
                
                <div class="p-4 rounded-lg bg-neus-black/50 border border-neus-border">
                    <div class="flex items-center gap-2 mb-2">
                        <span class="w-2 h-2 rounded-full bg-green-400"></span>
                        <span class="text-sm text-neus-cream">Zeus AI</span>
                    </div>
                    <p class="text-xs text-neus-text-muted">Operational</p>
                </div>
            </div>
        </div>
        
    </main>
</div>
