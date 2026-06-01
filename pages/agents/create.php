<?php
/**
 * NEUS Frontend Rewrite - Create Agent Page
 */

requireAuth();
?>

<div class="dashboard-layout">
    
    <?php require_once __DIR__ . '/../components/sidebar.php'; ?>
    
    <main class="dashboard-content">
        
        <div class="max-w-2xl mx-auto">
            <!-- Header -->
            <div class="mb-8">
                <a href="<?php echo pageUrl('/agents'); ?>" class="text-sm text-neus-gold hover:text-neus-gold-light transition-colors mb-4 inline-flex items-center gap-1">
                    <i class="fas fa-arrow-left"></i>
                    Back to Agents
                </a>
                <h1 class="text-2xl font-bold text-neus-cream">Create Agent</h1>
                <p class="text-sm text-neus-text-muted mt-1">Deploy a new AI agent with verifiable identity</p>
            </div>
            
            <!-- Form -->
            <form id="create-agent-form" class="card-neus space-y-6">
                <?php echo csrfField(); ?>
                
                <!-- Agent ID -->
                <div class="form-group">
                    <label class="form-label">Agent ID *</label>
                    <input type="text" name="agentId" required 
                           class="input-neus font-mono" 
                           placeholder="my-agent-123"
                           pattern="[a-zA-Z0-9_-]+"
                           maxlength="50">
                    <p class="form-hint">Unique identifier for your agent (letters, numbers, hyphens, underscores)</p>
                </div>
                
                <!-- Label -->
                <div class="form-group">
                    <label class="form-label">Label *</label>
                    <input type="text" name="agentLabel" required 
                           class="input-neus" 
                           placeholder="My Trading Bot"
                           maxlength="100">
                </div>
                
                <!-- Type -->
                <div class="form-group">
                    <label class="form-label">Agent Type</label>
                    <select name="agentType" class="input-neus">
                        <option value="ai">AI Assistant</option>
                        <option value="trading">Trading Bot</option>
                        <option value="monitoring">Monitor</option>
                        <option value="automation">Automation</option>
                        <option value="custom">Custom</option>
                    </select>
                </div>
                
                <!-- Description -->
                <div class="form-group">
                    <label class="form-label">Description</label>
                    <textarea name="description" rows="3" 
                              class="input-neus resize-none" 
                              placeholder="Describe what this agent does..."
                              maxlength="500"></textarea>
                </div>
                
                <!-- Capabilities -->
                <div class="form-group">
                    <label class="form-label">Capabilities</label>
                    <div class="grid grid-cols-2 gap-2">
                        <label class="flex items-center gap-2 p-3 rounded-lg border border-neus-border hover:border-neus-gold/30 cursor-pointer transition-all">
                            <input type="checkbox" name="capabilities[]" value="chat" class="w-4 h-4 rounded border-neus-border bg-neus-black text-neus-gold">
                            <span class="text-sm text-neus-text-secondary">Chat</span>
                        </label>
                        
                        <label class="flex items-center gap-2 p-3 rounded-lg border border-neus-border hover:border-neus-gold/30 cursor-pointer transition-all">
                            <input type="checkbox" name="capabilities[]" value="proofs" class="w-4 h-4 rounded border-neus-border bg-neus-black text-neus-gold">
                            <span class="text-sm text-neus-text-secondary">Proofs</span>
                        </label>
                        
                        <label class="flex items-center gap-2 p-3 rounded-lg border border-neus-border hover:border-neus-gold/30 cursor-pointer transition-all">
                            <input type="checkbox" name="capabilities[]" value="verify" class="w-4 h-4 rounded border-neus-border bg-neus-black text-neus-gold">
                            <span class="text-sm text-neus-text-secondary">Verify</span>
                        </label>
                        
                        <label class="flex items-center gap-2 p-3 rounded-lg border border-neus-border hover:border-neus-gold/30 cursor-pointer transition-all">
                            <input type="checkbox" name="capabilities[]" value="trade" class="w-4 h-4 rounded border-neus-border bg-neus-black text-neus-gold">
                            <span class="text-sm text-neus-text-secondary">Trade</span>
                        </label>
                    </div>
                </div>
                
                <!-- Wallet -->
                <div class="form-group">
                    <label class="form-label">Agent Wallet Address</label>
                    <input type="text" name="agentWallet" 
                           class="input-neus font-mono" 
                           placeholder="0x... (optional)">
                    <p class="form-hint">Optional: A dedicated wallet for this agent</p>
                </div>
                
                <!-- Chain -->
                <div class="form-group">
                    <label class="form-label">Preferred Chain</label>
                    <select name="chain" class="input-neus">
                        <option value="">Select chain...</option>
                        <?php foreach (getAllChains() as $key => $chain): ?>
                        <option value="<?php echo e($key); ?>"><?php echo e($chain['name']); ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                
                <!-- Submit -->
                <div class="flex items-center gap-4 pt-4">
                    <button type="submit" class="btn-primary">
                        <span class="btn-text"><i class="fas fa-robot"></i> Create Agent</span>
                        <span class="btn-loading hidden"><i class="fas fa-circle-notch fa-spin"></i> Creating...</span>
                    </button>
                    
                    <a href="<?php echo pageUrl('/agents'); ?>" class="btn-secondary">Cancel</a>
                </div>
            </form>
        </div>
        
    </main>
</div>

<script>
document.getElementById('create-agent-form').addEventListener('submit', async (e) => {
    e.preventDefault();
    const form = e.target;
    const btn = form.querySelector('button[type="submit"]');
    const btnText = btn.querySelector('.btn-text');
    const btnLoading = btn.querySelector('.btn-loading');
    
    btnText.classList.add('hidden');
    btnLoading.classList.remove('hidden');
    btn.disabled = true;
    
    const data = new FormData(form);
    
    try {
        const response = await NeusApp.api('/agents', {
            method: 'POST',
            body: {
                agentId: data.get('agentId'),
                agentLabel: data.get('agentLabel'),
                agentType: data.get('agentType'),
                description: data.get('description'),
                capabilities: data.getAll('capabilities[]'),
                agentWallet: data.get('agentWallet') || undefined,
                chain: data.get('chain') || undefined,
            },
        });
        
        if (response.success) {
            NeusApp.toast('Agent created successfully!', 'success');
            setTimeout(() => {
                window.location.href = '/agents';
            }, 1000);
        } else {
            NeusApp.toast(response.data?.error || 'Failed to create agent', 'error');
        }
    } catch (error) {
        NeusApp.toast('Network error. Please try again.', 'error');
    } finally {
        btnText.classList.remove('hidden');
        btnLoading.classList.add('hidden');
        btn.disabled = false;
    }
});
</script>
