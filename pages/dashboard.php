<?php
/**
 * NEUS Frontend Rewrite - Dashboard Page
 * Main user dashboard with overview cards, stats, recent activity
 */

requireAuth();

$user = getCurrentUser();
$wallet = getCurrentWallet();

// Fetch dashboard data from API
$dashboardData = neusApiRequest('/dashboard', 'GET');
$stats = $dashboardData['success'] ? ($dashboardData['data']['data'] ?? []) : [];
?>

<!-- Dashboard Layout -->
<div class="dashboard-layout">
    
    <?php require_once __DIR__ . '/../components/sidebar.php'; ?>
    
    <!-- Main Content -->
    <main class="dashboard-content">
        
        <!-- Page Header -->
        <div class="mb-8">
            <h1 class="text-2xl font-bold text-neus-cream">Dashboard</h1>
            <p class="text-sm text-neus-text-muted mt-1">
                Welcome back, <?php echo e($user['username'] ?? 'User'); ?>
            </p>
        </div>
        
        <!-- Stats Grid -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
            <!-- Identity Score -->
            <div class="card-neus">
                <div class="flex items-center justify-between mb-2">
                    <span class="text-sm text-neus-text-secondary">Identity Score</span>
                    <div class="w-8 h-8 rounded-lg bg-neus-gold/10 flex items-center justify-center">
                        <i class="fas fa-fingerprint text-neus-gold"></i>
                    </div>
                </div>
                <p class="text-2xl font-bold text-neus-cream"><?php echo $stats['identityScore'] ?? '85'; ?></p>
                <p class="text-xs text-neus-text-muted mt-1">Out of 100</p>
            </div>
            
            <!-- Total Proofs -->
            <div class="card-neus">
                <div class="flex items-center justify-between mb-2">
                    <span class="text-sm text-neus-text-secondary">Total Proofs</span>
                    <div class="w-8 h-8 rounded-lg bg-neus-gold/10 flex items-center justify-center">
                        <i class="fas fa-shield-alt text-neus-gold"></i>
                    </div>
                </div>
                <p class="text-2xl font-bold text-neus-cream"><?php echo $stats['totalProofs'] ?? '0'; ?></p>
                <p class="text-xs text-neus-text-muted mt-1"><?php echo $stats['verifiedProofs'] ?? '0'; ?> verified</p>
            </div>
            
            <!-- Active Agents -->
            <div class="card-neus">
                <div class="flex items-center justify-between mb-2">
                    <span class="text-sm text-neus-text-secondary">Active Agents</span>
                    <div class="w-8 h-8 rounded-lg bg-neus-gold/10 flex items-center justify-center">
                        <i class="fas fa-robot text-neus-gold"></i>
                    </div>
                </div>
                <p class="text-2xl font-bold text-neus-cream"><?php echo $stats['activeAgents'] ?? '0'; ?></p>
                <p class="text-xs text-neus-text-muted mt-1"><?php echo $stats['totalAgents'] ?? '0'; ?> total</p>
            </div>
            
            <!-- Credits -->
            <div class="card-neus">
                <div class="flex items-center justify-between mb-2">
                    <span class="text-sm text-neus-text-secondary">Credits</span>
                    <div class="w-8 h-8 rounded-lg bg-neus-gold/10 flex items-center justify-center">
                        <i class="fas fa-coins text-neus-gold"></i>
                    </div>
                </div>
                <p class="text-2xl font-bold text-neus-cream"><?php echo formatNumber($stats['credits'] ?? 0); ?></p>
                <p class="text-xs text-neus-text-muted mt-1">Available balance</p>
            </div>
        </div>
        
        <!-- Quick Actions -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">
            
            <!-- Create Proof CTA -->
            <div class="card-neus relative overflow-hidden">
                <div class="absolute top-0 right-0 w-32 h-32 bg-neus-gold/5 rounded-full -translate-y-1/2 translate-x-1/2"></div>
                <div class="relative">
                    <div class="w-12 h-12 rounded-xl bg-neus-gold/10 flex items-center justify-center mb-4">
                        <i class="fas fa-plus-circle text-xl text-neus-gold"></i>
                    </div>
                    <h3 class="text-lg font-semibold text-neus-cream mb-2">Create a Proof</h3>
                    <p class="text-sm text-neus-text-secondary mb-4">
                        Generate a new zero-knowledge proof for any claim or credential.
                    </p>
                    <a href="<?php echo pageUrl('/proofs/create'); ?>" class="btn-primary text-sm">
                        <i class="fas fa-plus"></i>
                        New Proof
                    </a>
                </div>
            </div>
            
            <!-- Create Agent CTA -->
            <div class="card-neus relative overflow-hidden">
                <div class="absolute top-0 right-0 w-32 h-32 bg-neus-gold/5 rounded-full -translate-y-1/2 translate-x-1/2"></div>
                <div class="relative">
                    <div class="w-12 h-12 rounded-xl bg-neus-gold/10 flex items-center justify-center mb-4">
                        <i class="fas fa-robot text-xl text-neus-gold"></i>
                    </div>
                    <h3 class="text-lg font-semibold text-neus-cream mb-2">Deploy an Agent</h3>
                    <p class="text-sm text-neus-text-secondary mb-4">
                        Create an AI agent with verifiable identity to act on your behalf.
                    </p>
                    <a href="<?php echo pageUrl('/agents/create'); ?>" class="btn-primary text-sm">
                        <i class="fas fa-plus"></i>
                        New Agent
                    </a>
                </div>
            </div>
            
            <!-- Chat with Zeus CTA -->
            <div class="card-neus relative overflow-hidden">
                <div class="absolute top-0 right-0 w-32 h-32 bg-neus-gold/5 rounded-full -translate-y-1/2 translate-x-1/2"></div>
                <div class="relative">
                    <div class="w-12 h-12 rounded-xl bg-neus-gold/10 flex items-center justify-center mb-4">
                        <i class="fas fa-brain text-xl text-neus-gold"></i>
                    </div>
                    <h3 class="text-lg font-semibold text-neus-cream mb-2">Chat with Zeus</h3>
                    <p class="text-sm text-neus-text-secondary mb-4">
                        Get help with proofs, agents, and identity management from Zeus AI.
                    </p>
                    <a href="<?php echo pageUrl('/chat'); ?>" class="btn-primary text-sm">
                        <i class="fas fa-comment"></i>
                        Start Chat
                    </a>
                </div>
            </div>
        </div>
        
        <!-- Recent Activity -->
        <div class="card-neus">
            <div class="flex items-center justify-between mb-6">
                <h2 class="text-lg font-semibold text-neus-cream">Recent Activity</h2>
                <a href="<?php echo pageUrl('/credits/history'); ?>" class="text-sm text-neus-gold hover:text-neus-gold-light transition-colors">View All</a>
            </div>
            
            <!-- Activity List -->
            <div class="space-y-3">
                <?php 
                $activities = $stats['recentActivity'] ?? [
                    ['type' => 'proof', 'action' => 'Created proof', 'target' => 'Identity Verification', 'time' => time() - 3600],
                    ['type' => 'agent', 'action' => 'Deployed agent', 'target' => 'Trading Bot #1', 'time' => time() - 7200],
                    ['type' => 'credit', 'action' => 'Purchased credits', 'target' => '1000 NEUS', 'time' => time() - 86400],
                    ['type' => 'wallet', 'action' => 'Linked wallet', 'target' => '0x1234...5678', 'time' => time() - 172800],
                ];
                
                foreach ($activities as $activity): 
                ?>
                <div class="flex items-center gap-4 p-3 rounded-lg hover:bg-neus-gold/5 transition-colors">
                    <div class="w-10 h-10 rounded-lg flex items-center justify-center <?php 
                        echo match($activity['type']) {
                            'proof' => 'bg-green-500/10 text-green-400',
                            'agent' => 'bg-blue-500/10 text-blue-400',
                            'credit' => 'bg-neus-gold/10 text-neus-gold',
                            'wallet' => 'bg-purple-500/10 text-purple-400',
                            default => 'bg-neus-gold/10 text-neus-gold',
                        };
                    ?>">
                        <i class="fas fa-<?php 
                            echo match($activity['type']) {
                                'proof' => 'shield-alt',
                                'agent' => 'robot',
                                'credit' => 'coins',
                                'wallet' => 'wallet',
                                default => 'circle',
                            };
                        ?>"></i>
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="text-sm text-neus-cream"><?php echo e($activity['action']); ?></p>
                        <p class="text-xs text-neus-text-muted truncate"><?php echo e($activity['target']); ?></p>
                    </div>
                    <span class="text-xs text-neus-text-muted"><?php echo timeAgo($activity['time']); ?></span>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
        
    </main>
</div>
