<?php
/**
 * NEUS Frontend Rewrite - Agent Detail Page
 */

$agentId = $_GET['agentId'] ?? '';

if (!$agentId) {
    redirect(pageUrl('/agents'), 'Agent ID required', 'error');
}

// Fetch agent details
$agentResponse = neusApiRequest('/agents/' . urlencode($agentId), 'GET');
$agent = $agentResponse['success'] ? ($agentResponse['data']['data'] ?? null) : null;

if (!$agent) {
    http_response_code(404);
}
?>

<div class="min-h-[80vh] px-4 py-12">
    <div class="max-w-4xl mx-auto">
        
        <?php if (!$agent): ?>
        <!-- Not Found -->
        <div class="card-neus text-center py-12">
            <div class="w-16 h-16 rounded-2xl bg-red-500/10 flex items-center justify-center mx-auto mb-4">
                <i class="fas fa-robot text-2xl text-red-400"></i>
            </div>
            <h2 class="text-xl font-bold text-neus-cream mb-2">Agent Not Found</h2>
            <p class="text-sm text-neus-text-secondary mb-6">This agent doesn't exist or you don't have access.</p>
            <a href="<?php echo pageUrl('/agents'); ?>" class="btn-primary">Back to Agents</a>
        </div>
        
        <?php else: ?>
        
        <!-- Agent Header -->
        <div class="card-neus mb-6">
            <div class="flex flex-col sm:flex-row items-start gap-6">
                <div class="w-20 h-20 rounded-2xl bg-gradient-to-br from-neus-gold/20 to-neus-gold-dim/20 flex items-center justify-center flex-shrink-0">
                    <i class="fas fa-robot text-3xl text-neus-gold"></i>
                </div>
                
                <div class="flex-1">
                    <div class="flex items-center gap-3 mb-2">
                        <h1 class="text-2xl font-bold text-neus-cream"><?php echo e($agent['label'] ?? 'Unnamed Agent'); ?></h1>
                        <span class="badge <?php 
                            echo match($agent['status'] ?? 'inactive') {
                                'active' => 'badge-success',
                                'inactive' => 'badge-warning',
                                'suspended' => 'badge-error',
                                default => 'badge-info',
                            };
                        ?>"><?php echo ucfirst($agent['status'] ?? 'Inactive'); ?></span>
                    </div>
                    
                    <p class="text-sm font-mono text-neus-text-muted mb-2"><?php echo e($agent['agentId']); ?></p>
                    
                    <p class="text-sm text-neus-text-secondary"><?php echo e($agent['description'] ?? 'No description'); ?></p>
                    
                    <?php if (!empty($agent['capabilities'])): ?>
                    <div class="flex flex-wrap gap-2 mt-3">
                        <?php foreach ($agent['capabilities'] as $cap): ?>
                        <span class="px-2 py-1 rounded-full bg-neus-gold/10 text-neus-gold text-xs"><?php echo e($cap); ?></span>
                        <?php endforeach; ?>
                    </div>
                    <?php endif; ?
                </div>
            </div>
        </div>
        
        <!-- Agent Details -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- Info Card -->
            <div class="card-neus">
                <h3 class="text-lg font-semibold text-neus-cream mb-4">Agent Information</h3>
                
                <div class="space-y-3">
                    <div>
                        <p class="text-xs text-neus-text-muted">Type</p>
                        <p class="text-sm text-neus-cream"><?php echo e(ucfirst($agent['agentType'] ?? 'AI')); ?></p>
                    </div>
                    
                    <?php if (isset($agent['wallet'])): ?>
                    <div>
                        <p class="text-xs text-neus-text-muted">Wallet</p>
                        <p class="text-sm font-mono text-neus-text-secondary"><?php echo e($agent['wallet']); ?></p>
                    </div>
                    <?php endif; ?>
                    
                    <?php if (isset($agent['chain'])): ?>
                    <div>
                        <p class="text-xs text-neus-text-muted">Chain</p>
                        <p class="text-sm text-neus-cream"><?php echo e(ucfirst($agent['chain'])); ?></p>
                    </div>
                    <?php endif; ?>
                    
                    <?php if (isset($agent['linkedAt'])): ?>
                    <div>
                        <p class="text-xs text-neus-text-muted">Linked</p>
                        <p class="text-sm text-neus-cream"><?php echo formatDate($agent['linkedAt']); ?></p>
                    </div>
                    <?php endif; ?>
                    
                    <?php if (isset($agent['createdAt'])): ?>
                    <div>
                        <p class="text-xs text-neus-text-muted">Created</p>
                        <p class="text-sm text-neus-cream"><?php echo formatDate($agent['createdAt']); ?></p>
                    </div>
                    <?php endif; ?>
                </div>
            </div>
            
            <!-- Actions Card -->
            <div class="card-neus">
                <h3 class="text-lg font-semibold text-neus-cream mb-4">Actions</h3>
                
                <div class="space-y-3">
                    <?php if (empty($agent['linkedAt'])): ?>
                    <a href="<?php echo pageUrl('/agents/link?agent=' . urlencode($agent['agentId'])); ?>" class="btn-primary w-full justify-center">
                        <i class="fas fa-link"></i>
                        Link Agent
                    </a>
                    <?php endif; ?>
                    
                    <button type="button" class="btn-secondary w-full justify-center">
                        <i class="fas fa-key"></i>
                        View API Keys
                    </button>
                    
                    <button type="button" class="btn-secondary w-full justify-center">
                        <i class="fas fa-edit"></i>
                        Edit Agent
                    </button>
                    
                    <button type="button" class="w-full px-4 py-2 rounded-lg border border-red-500/30 text-red-400 hover:bg-red-500/10 transition-all flex items-center justify-center gap-2">
                        <i class="fas fa-trash"></i>
                        Delete Agent
                    </button>
                </div>
            </div>
        </div>
        
        <?php endif; ?>
    </div>
</div>
