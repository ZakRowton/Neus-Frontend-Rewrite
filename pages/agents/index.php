<?php
/**
 * NEUS Frontend Rewrite - Agents Index Page
 */

requireAuth();

$agentsResponse = neusApiRequest('/agents', 'GET');
$agents = $agentsResponse['success'] ? ($agentsResponse['data']['data'] ?? []) : [];
?>

<div class="dashboard-layout">
    
    <?php require_once __DIR__ . '/../components/sidebar.php'; ?>
    
    <main class="dashboard-content">
        
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-8">
            <div>
                <h1 class="text-2xl font-bold text-neus-cream">Agents</h1>
                <p class="text-sm text-neus-text-muted mt-1">Manage your NEUS agents</p>
            </div>
            
            <a href="<?php echo pageUrl('/agents/create'); ?>" class="btn-primary">
                <i class="fas fa-plus"></i>
                Create Agent
            </a>
        </div>
        
        <!-- Agent Stats -->
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-8">
            <div class="card-neus">
                <p class="text-sm text-neus-text-secondary">Total Agents</p>
                <p class="text-2xl font-bold text-neus-cream mt-1"><?php echo count($agents); ?></p>
            </div>
            
            <div class="card-neus">
                <p class="text-sm text-neus-text-secondary">Active</p>
                <p class="text-2xl font-bold text-green-400 mt-1"><?php echo count(array_filter($agents, fn($a) => ($a['status'] ?? '') === 'active')); ?></p>
            </div>
            
            <div class="card-neus">
                <p class="text-sm text-neus-text-secondary">Linked</p>
                <p class="text-2xl font-bold text-neus-gold mt-1"><?php echo count(array_filter($agents, fn($a) => !empty($a['linkedAt']))); ?></p>
            </div>
        </div>
        
        <!-- Agents List -->
        <?php if (empty($agents)): ?>
        <div class="card-neus text-center py-16">
            <div class="w-16 h-16 rounded-2xl bg-neus-gold/10 flex items-center justify-center mx-auto mb-4">
                <i class="fas fa-robot text-2xl text-neus-gold"></i>
            </div>
            <h3 class="text-lg font-semibold text-neus-cream mb-2">No agents yet</h3>
            <p class="text-sm text-neus-text-secondary mb-6">Create your first AI agent with verifiable identity</p>
            <a href="<?php echo pageUrl('/agents/create'); ?>" class="btn-primary">
                <i class="fas fa-plus"></i>
                Create Agent
            </a>
        </div>
        
        <?php else: ?>
        <div class="space-y-4">
            <?php foreach ($agents as $agent): ?>
            <div class="card-neus flex items-center gap-4">
                <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-neus-gold/20 to-neus-gold-dim/20 flex items-center justify-center flex-shrink-0">
                    <i class="fas fa-robot text-xl text-neus-gold"></i>
                </div>
                
                <div class="flex-1 min-w-0">
                    <div class="flex items-center gap-2">
                        <h3 class="text-sm font-semibold text-neus-cream"><?php echo e($agent['label'] ?? $agent['agentId'] ?? 'Unnamed Agent'); ?></h3>
                        <span class="badge <?php 
                            echo match($agent['status'] ?? 'inactive') {
                                'active' => 'badge-success',
                                'inactive' => 'badge-warning',
                                'suspended' => 'badge-error',
                                default => 'badge-info',
                            };
                        ?>"><?php echo ucfirst($agent['status'] ?? 'Inactive'); ?></span>
                        <?php if (!empty($agent['linkedAt'])): ?>
                        <span class="badge badge-gold"><i class="fas fa-link"></i> Linked</span>
                        <?php endif; ?>
                    </div>
                    <p class="text-xs text-neus-text-muted mt-1 font-mono truncate"><?php echo e($agent['agentId'] ?? 'N/A'); ?></p>
                    <?php if (!empty($agent['capabilities'])): ?>
                    <div class="flex flex-wrap gap-1 mt-2">
                        <?php foreach (array_slice($agent['capabilities'], 0, 3) as $cap): ?>
                        <span class="px-2 py-0.5 rounded-full bg-neus-gold/10 text-neus-gold text-xs"><?php echo e($cap); ?></span>
                        <?php endforeach; ?>
                    </div>
                    <?php endif; ?
                </div>
                
                <div class="flex items-center gap-2">
                    <a href="<?php echo pageUrl('/agent/' . urlencode($agent['agentId'] ?? '')); ?>" 
                       class="p-2 rounded-lg hover:bg-neus-gold/10 text-neus-text-muted hover:text-neus-gold transition-all"
                       title="View">
                        <i class="fas fa-eye"></i>
                    </a>
                    
                    <?php if (empty($agent['linkedAt'])): ?>
                    <a href="<?php echo pageUrl('/agents/link?agent=' . urlencode($agent['agentId'] ?? '')); ?>" 
                       class="p-2 rounded-lg hover:bg-neus-gold/10 text-neus-text-muted hover:text-neus-gold transition-all"
                       title="Link">
                        <i class="fas fa-link"></i>
                    </a>
                    <?php endif; ?>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
        <?php endif; ?>
        
    </main>
</div>
