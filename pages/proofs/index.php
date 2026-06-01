<?php
/**
 * NEUS Frontend Rewrite - Proofs Index Page
 * Proof library listing
 */

requireAuth();

// Fetch proofs from API
$proofsResponse = neusApiRequest('/proofs', 'GET');
$proofs = $proofsResponse['success'] ? ($proofsResponse['data']['data'] ?? []) : [];
?>

<div class="dashboard-layout">
    
    <?php require_once __DIR__ . '/../components/sidebar.php'; ?>
    
    <main class="dashboard-content">
        
        <!-- Header -->
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-8">
            <div>
                <h1 class="text-2xl font-bold text-neus-cream">Proof Library</h1>
                <p class="text-sm text-neus-text-muted mt-1">Manage and browse your verification proofs</p>
            </div>
            
            <a href="<?php echo pageUrl('/proofs/create'); ?>" class="btn-primary">
                <i class="fas fa-plus"></i>
                Create Proof
            </a>
        </div>
        
        <!-- Filters -->
        <div class="card-neus mb-6">
            <div class="flex flex-col sm:flex-row gap-4">
                <div class="flex-1">
                    <div class="relative">
                        <i class="fas fa-search absolute left-3 top-1/2 -translate-y-1/2 text-neus-text-muted"></i>
                        <input type="text" placeholder="Search proofs..." 
                               class="input-neus pl-10"
                               id="proof-search">
                    </div>
                </div>
                
                <select class="input-neus sm:w-48" id="proof-filter">
                    <option value="">All Statuses</option>
                    <option value="verified">Verified</option>
                    <option value="pending">Pending</option>
                    <option value="expired">Expired</option>
                    <option value="revoked">Revoked</option>
                </select>
            </div>
        </div>
        
        <!-- Proofs Grid -->
        <?php if (empty($proofs)): ?>
        <div class="card-neus text-center py-16">
            <div class="w-16 h-16 rounded-2xl bg-neus-gold/10 flex items-center justify-center mx-auto mb-4">
                <i class="fas fa-shield-alt text-2xl text-neus-gold"></i>
            </div>
            <h3 class="text-lg font-semibold text-neus-cream mb-2">No proofs yet</h3>
            <p class="text-sm text-neus-text-secondary mb-6">Create your first zero-knowledge proof to get started</p>
            <a href="<?php echo pageUrl('/proofs/create'); ?>" class="btn-primary">
                <i class="fas fa-plus"></i>
                Create Proof
            </a>
        </div>
        <?php else: ?>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
            <?php foreach ($proofs as $proof): ?>
            <div class="card-neus group">
                <div class="flex items-start justify-between mb-4">
                    <div class="w-10 h-10 rounded-lg bg-neus-gold/10 flex items-center justify-center">
                        <i class="fas fa-shield-alt text-neus-gold"></i>
                    </div>
                    
                    <span class="badge <?php 
                        echo match($proof['status'] ?? 'pending') {
                            'verified' => 'badge-success',
                            'pending' => 'badge-warning',
                            'expired' => 'badge-error',
                            'revoked' => 'badge-error',
                            default => 'badge-info',
                        };
                    ?>">
                        <?php echo ucfirst($proof['status'] ?? 'Pending'); ?>
                    </span>
                </div>
                
                <h3 class="text-lg font-semibold text-neus-cream mb-1"><?php echo e($proof['title'] ?? 'Untitled Proof'); ?></h3>
                <p class="text-sm text-neus-text-secondary mb-4 line-clamp-2"><?php echo e($proof['description'] ?? 'No description'); ?></p>
                
                <div class="flex items-center justify-between pt-4 border-t border-neus-border">
                    <span class="text-xs text-neus-text-muted font-mono"><?php echo e(substr($proof['qHash'] ?? 'N/A', 0, 12) . '...'); ?></span>
                    <span class="text-xs text-neus-text-muted"><?php echo timeAgo($proof['createdAt'] ?? null); ?></span>
                </div>
                
                <a href="<?php echo pageUrl('/proofs/' . ($proof['qHash'] ?? '')); ?>" 
                   class="absolute inset-0"
                   aria-label="View proof details"></a>
            </div>
            <?php endforeach; ?>
        </div>
        <?php endif; ?>
        
    </main>
</div>
