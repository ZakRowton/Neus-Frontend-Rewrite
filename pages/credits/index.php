<?php
/**
 * NEUS Frontend Rewrite - Credits Page
 */

requireAuth();

$user = getCurrentUser();
$creditsResponse = neusApiRequest('/credits', 'GET');
$credits = $creditsResponse['success'] ? ($creditsResponse['data']['data'] ?? []) : [];
?>

<div class="dashboard-layout">
    
    <?php require_once __DIR__ . '/../components/sidebar.php'; ?>
    
    <main class="dashboard-content">
        
        <div class="max-w-3xl mx-auto">
            
            <!-- Header -->
            <div class="mb-8">
                <h1 class="text-2xl font-bold text-neus-cream">Credits</h1>
                <p class="text-sm text-neus-text-muted mt-1">Manage your NEUS credits</p>
            </div>
            
            <!-- Balance Card -->
            <div class="card-neus mb-6">
                <div class="flex flex-col sm:flex-row items-center justify-between gap-4">
                    <div class="text-center sm:text-left">
                        <p class="text-sm text-neus-text-secondary">Available Balance</p>
                        <p class="text-4xl font-bold text-neus-gold mt-1"><?php echo formatNumber($user['credits'] ?? 0); ?></p>
                        <p class="text-xs text-neus-text-muted mt-1">NEUS Credits</p>
                    </div>
                    
                    <a href="<?php echo pageUrl('/credits/buy'); ?>" class="btn-primary">
                        <i class="fas fa-plus"></i>
                        Buy Credits
                    </a>
                </div>
            </div>
            
            <!-- Credit Packages -->
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-8">
                <div class="card-neus text-center">
                    <p class="text-sm text-neus-text-secondary">Starter</p>
                    <p class="text-2xl font-bold text-neus-cream my-2">100</p>
                    <p class="text-xs text-neus-text-muted">$5.00</p>
                </div>
                
                <div class="card-neus text-center border-neus-gold/30">
                    <p class="text-sm text-neus-gold">Popular</p>
                    <p class="text-2xl font-bold text-neus-cream my-2">500</p>
                    <p class="text-xs text-neus-text-muted">$20.00</p>
                </div>
                
                <div class="card-neus text-center">
                    <p class="text-sm text-neus-text-secondary">Pro</p>
                    <p class="text-2xl font-bold text-neus-cream my-2">2000</p>
                    <p class="text-xs text-neus-text-muted">$75.00</p>
                </div>
            </div>
            
            <!-- Recent Transactions -->
            <div class="card-neus">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-semibold text-neus-cream">Recent Transactions</h3>
                    <a href="<?php echo pageUrl('/credits/history'); ?>" class="text-sm text-neus-gold">View All</a>
                </div>
                
                <?php 
                $transactions = $credits['transactions'] ?? [];
                if (empty($transactions)): 
                ?>
                <p class="text-sm text-neus-text-muted">No transactions yet.</p>
                <?php else: ?>
                <div class="space-y-2">
                    <?php foreach (array_slice($transactions, 0, 5) as $tx): ?>
                    <div class="flex items-center justify-between p-3 rounded-lg hover:bg-neus-gold/5 transition-colors">
                        <div class="flex items-center gap-3">
                            <div class="w-8 h-8 rounded-lg <?php echo ($tx['type'] ?? '') === 'credit' ? 'bg-green-500/10' : 'bg-red-500/10'; ?> flex items-center justify-center">
                                <i class="fas fa-<?php echo ($tx['type'] ?? '') === 'credit' ? 'arrow-down text-green-400' : 'arrow-up text-red-400'; ?>"></i>
                            </div>
                            <div>
                                <p class="text-sm text-neus-cream"><?php echo e($tx['description'] ?? 'Transaction'); ?></p>
                                <p class="text-xs text-neus-text-muted"><?php echo timeAgo($tx['timestamp'] ?? null); ?></p>
                            </div>
                        </div>
                        <span class="text-sm font-semibold <?php echo ($tx['type'] ?? '') === 'credit' ? 'text-green-400' : 'text-red-400'; ?>">
                            <?php echo ($tx['type'] ?? '') === 'credit' ? '+' : '-'; ?><?php echo formatNumber($tx['amount'] ?? 0); ?>
                        </span>
                    </div>
                    <?php endforeach; ?>
                </div>
                <?php endif; ?>
            </div>
            
        </div>
        
    </main>
</div>
