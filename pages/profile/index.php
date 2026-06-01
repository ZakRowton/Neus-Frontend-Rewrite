<?php
/**
 * NEUS Frontend Rewrite - Profile Page
 */

requireAuth();

$user = getCurrentUser();
$profile = getUserProfile();
$wallet = getCurrentWallet();
?>

<div class="dashboard-layout">
    
    <?php require_once __DIR__ . '/../components/sidebar.php'; ?>
    
    <main class="dashboard-content">
        
        <div class="max-w-3xl mx-auto">
            
            <!-- Profile Header -->
            <div class="card-neus mb-6">
                <div class="flex flex-col sm:flex-row items-center gap-6">
                    <div class="w-24 h-24 rounded-2xl bg-gradient-to-br from-neus-gold to-neus-gold-dim flex items-center justify-center text-neus-black text-3xl font-bold">
                        <?php echo strtoupper(substr($user['username'] ?? $user['email'] ?? 'U', 0, 1)); ?>
                    </div>
                    
                    <div class="text-center sm:text-left">
                        <h1 class="text-2xl font-bold text-neus-cream"><?php echo e($user['username'] ?? 'User'); ?></h1>
                        <p class="text-sm text-neus-text-muted"><?php echo e($user['email'] ?? ''); ?></p>
                        <?php if ($wallet): ?
                        <p class="text-sm font-mono text-neus-gold mt-1"><?php echo e($wallet); ?></p>
                        <?php endif; ?>
                        
                        <div class="flex items-center gap-2 mt-3">
                            <span class="badge badge-gold"><i class="fas fa-crown"></i> <?php echo ucfirst($user['tier'] ?? 'Free'); ?></span>
                            <?php if (isAdmin()): ?>
                            <span class="badge badge-info">Admin</span>
                            <?php endif; ?>
                        </div>
                    </div>
                    
                    <div class="sm:ml-auto">
                        <a href="<?php echo pageUrl('/profile/edit'); ?>" class="btn-secondary">
                            <i class="fas fa-edit"></i>
                            Edit Profile
                        </a>
                    </div>
                </div>
            </div>
            
            <!-- Profile Details -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                
                <!-- Identity Info -->
                <div class="card-neus">
                    <h3 class="text-lg font-semibold text-neus-cream mb-4">Identity</h3>
                    
                    <div class="space-y-3">
                        <div>
                            <p class="text-xs text-neus-text-muted">User ID</p>
                            <p class="text-sm font-mono text-neus-cream"><?php echo e($user['id'] ?? 'N/A'); ?></p>
                        </div>
                        
                        <div>
                            <p class="text-xs text-neus-text-muted">DID</p>
                            <p class="text-sm font-mono text-neus-text-secondary break-all"><?php echo e($user['did'] ?? 'Not set'); ?></p>
                        </div>
                        
                        <div>
                            <p class="text-xs text-neus-text-muted">Joined</p>
                            <p class="text-sm text-neus-cream"><?php echo formatDate($user['createdAt'] ?? null); ?></p>
                        </div>
                        
                        <div>
                            <p class="text-xs text-neus-text-muted">Last Active</p>
                            <p class="text-sm text-neus-cream"><?php echo timeAgo($user['lastActive'] ?? null); ?></p>
                        </div>
                    </div>
                </div>
                
                <!-- Stats -->
                <div class="card-neus">
                    <h3 class="text-lg font-semibold text-neus-cream mb-4">Statistics</h3>
                    
                    <div class="space-y-3">
                        <div class="flex items-center justify-between">
                            <span class="text-sm text-neus-text-secondary">Total Proofs</span>
                            <span class="text-sm font-semibold text-neus-cream"><?php echo $user['proofCount'] ?? '0'; ?></span>
                        </div>
                        
                        <div class="flex items-center justify-between">
                            <span class="text-sm text-neus-text-secondary">Agents</span>
                            <span class="text-sm font-semibold text-neus-cream"><?php echo $user['agentCount'] ?? '0'; ?></span>
                        </div>
                        
                        <div class="flex items-center justify-between">
                            <span class="text-sm text-neus-text-secondary">Credits</span>
                            <span class="text-sm font-semibold text-neus-gold"><?php echo formatNumber($user['credits'] ?? 0); ?></span>
                        </div>
                        
                        <div class="flex items-center justify-between">
                            <span class="text-sm text-neus-text-secondary">Identity Score</span>
                            <span class="text-sm font-semibold text-neus-cream"><?php echo $user['identityScore'] ?? 'N/A'; ?>/100</span>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Linked Wallets -->
            <div class="card-neus mt-6">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-semibold text-neus-cream">Linked Wallets</h3>
                    <a href="<?php echo pageUrl('/profile/linked-accounts'); ?>" class="text-sm text-neus-gold hover:text-neus-gold-light">Manage</a>
                </div>
                
                <?php 
                $wallets = $user['wallets'] ?? ($wallet ? [['address' => $wallet, 'chain' => 'ethereum', 'linkedAt' => time()]] : []);
                if (empty($wallets)): 
                ?>
                <p class="text-sm text-neus-text-muted">No wallets linked yet.</p>
                <?php else: ?>
                <div class="space-y-2">
                    <?php foreach ($wallets as $w): ?>
                    <div class="flex items-center justify-between p-3 rounded-lg bg-neus-black/50 border border-neus-border">
                        <div class="flex items-center gap-3">
                            <div class="w-8 h-8 rounded-lg bg-neus-gold/10 flex items-center justify-center">
                                <i class="fas fa-wallet text-neus-gold"></i>
                            </div>
                            <div>
                                <p class="text-sm font-mono text-neus-cream"><?php echo e($w['address'] ?? ''); ?></p>
                                <p class="text-xs text-neus-text-muted"><?php echo e(ucfirst($w['chain'] ?? 'Unknown')); ?> &bull; <?php echo timeAgo($w['linkedAt'] ?? null); ?></p>
                            </div>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
                <?php endif; ?>
            </div>
            
        </div>
        
    </main>
</div>
