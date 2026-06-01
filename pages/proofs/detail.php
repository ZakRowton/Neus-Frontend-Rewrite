<?php
/**
 * NEUS Frontend Rewrite - Proof Detail Page
 * Public/private proof verification status
 */

$qHash = $_GET['qHash'] ?? '';

if (!$qHash) {
    redirect(pageUrl('/proofs'), 'Invalid proof ID', 'error');
}

// Fetch proof details
$proofResponse = neusApiRequest('/proofs/' . urlencode($qHash), 'GET');
$proof = $proofResponse['success'] ? ($proofResponse['data']['data'] ?? null) : null;

if (!$proof) {
    http_response_code(404);
}
?>

<div class="min-h-[80vh] flex items-center justify-center px-4 py-12">
    <div class="w-full max-w-lg">
        
        <?php if (!$proof): ?>
        <!-- Not Found -->
        <div class="card-neus text-center py-12">
            <div class="w-16 h-16 rounded-2xl bg-red-500/10 flex items-center justify-center mx-auto mb-4">
                <i class="fas fa-exclamation-triangle text-2xl text-red-400"></i>
            </div>
            <h2 class="text-xl font-bold text-neus-cream mb-2">Proof Not Found</h2>
            <p class="text-sm text-neus-text-secondary mb-6">The proof you're looking for doesn't exist or has been removed.</p>
            <a href="<?php echo pageUrl('/proofs'); ?>" class="btn-primary">
                <i class="fas fa-arrow-left"></i>
                Back to Proofs
            </a>
        </div>
        
        <?php else: ?>
        
        <!-- Proof Card -->
        <div class="card-neus">
            <!-- Status Header -->
            <div class="flex items-center justify-between mb-6">
                <div class="flex items-center gap-3">
                    <div class="w-12 h-12 rounded-xl <?php 
                        echo match($proof['status'] ?? 'pending') {
                            'verified' => 'bg-green-500/10',
                            'pending' => 'bg-yellow-500/10',
                            'expired' => 'bg-red-500/10',
                            'revoked' => 'bg-red-500/10',
                            default => 'bg-neus-gold/10',
                        };
                    ?> flex items-center justify-center">
                        <i class="fas fa-<?php 
                            echo match($proof['status'] ?? 'pending') {
                                'verified' => 'check-circle text-green-400',
                                'pending' => 'clock text-yellow-400',
                                'expired' => 'times-circle text-red-400',
                                'revoked' => 'ban text-red-400',
                                default => 'shield-alt text-neus-gold',
                            };
                        ?> text-xl"></i>
                    </div>
                    <div>
                        <span class="badge <?php 
                            echo match($proof['status'] ?? 'pending') {
                                'verified' => 'badge-success',
                                'pending' => 'badge-warning',
                                'expired' => 'badge-error',
                                'revoked' => 'badge-error',
                                default => 'badge-info',
                            };
                        ?>"><?php echo ucfirst($proof['status'] ?? 'Pending'); ?></span>
                        <p class="text-xs text-neus-text-muted mt-1"><?php echo timeAgo($proof['createdAt'] ?? null); ?></p>
                    </div>
                </div>
                
                <button onclick="NeusApp.copyToClipboard('<?php echo e($proof['qHash'] ?? ''); ?>')" 
                        class="text-neus-text-muted hover:text-neus-gold transition-colors"
                        title="Copy qHash">
                    <i class="fas fa-copy"></i>
                </button>
            </div>
            
            <!-- Details -->
            <div class="space-y-4">
                <div>
                    <p class="text-xs text-neus-text-muted uppercase tracking-wider mb-1">Title</p>
                    <p class="text-sm text-neus-cream"><?php echo e($proof['title'] ?? 'Untitled'); ?></p>
                </div>
                
                <div>
                    <p class="text-xs text-neus-text-muted uppercase tracking-wider mb-1">Description</p>
                    <p class="text-sm text-neus-text-secondary"><?php echo e($proof['description'] ?? 'No description'); ?></p>
                </div>
                
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <p class="text-xs text-neus-text-muted uppercase tracking-wider mb-1">Verifier</p>
                        <p class="text-sm text-neus-cream"><?php echo e($proof['verifierId'] ?? 'N/A'); ?></p>
                    </div>
                    
                    <div>
                        <p class="text-xs text-neus-text-muted uppercase tracking-wider mb-1">Chain</p>
                        <p class="text-sm text-neus-cream"><?php echo e(getChainConfig($proof['chainId'] ?? '')['name'] ?? 'Unknown'); ?></p>
                    </div>
                </div>
                
                <div>
                    <p class="text-xs text-neus-text-muted uppercase tracking-wider mb-1">qHash</p>
                    <p class="text-sm font-mono text-neus-gold break-all"><?php echo e($proof['qHash'] ?? 'N/A'); ?></p>
                </div>
                
                <?php if (isset($proof['walletAddress'])): ?>
                <div>
                    <p class="text-xs text-neus-text-muted uppercase tracking-wider mb-1">Wallet</p>
                    <p class="text-sm font-mono text-neus-text-secondary"><?php echo e($proof['walletAddress']); ?></p>
                </div>
                <?php endif; ?>
                
                <?php if (isset($proof['expiresAt'])): ?>
                <div>
                    <p class="text-xs text-neus-text-muted uppercase tracking-wider mb-1">Expires</p>
                    <p class="text-sm text-neus-text-secondary"><?php echo formatDate($proof['expiresAt']); ?></p>
                </div>
                <?php endif; ?>
            </div>
            
            <!-- Actions -->
            <div class="flex items-center gap-3 mt-6 pt-6 border-t border-neus-border">
                <a href="<?php echo pageUrl('/verify?qHash=' . urlencode($qHash)); ?>" class="btn-primary">
                    <i class="fas fa-check-circle"></i>
                    Verify
                </a>
                
                <button onclick="window.print()" class="btn-secondary">
                    <i class="fas fa-print"></i>
                    Print
                </button>
            </div>
        </div>
        
        <?php endif; ?>
    </div>
</div>
