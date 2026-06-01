<?php
/**
 * NEUS Frontend Rewrite - Create Proof Page
 */

requireAuth();

$verifiers = neusApiRequest('/verifiers', 'GET');
$verifierList = $verifiers['success'] ? ($verifiers['data']['data'] ?? []) : [];
?>

<div class="dashboard-layout">
    
    <?php require_once __DIR__ . '/../components/sidebar.php'; ?>
    
    <main class="dashboard-content">
        
        <div class="max-w-2xl mx-auto">
            <!-- Header -->
            <div class="mb-8">
                <a href="<?php echo pageUrl('/proofs'); ?>" class="text-sm text-neus-gold hover:text-neus-gold-light transition-colors mb-4 inline-flex items-center gap-1">
                    <i class="fas fa-arrow-left"></i>
                    Back to Proofs
                </a>
                <h1 class="text-2xl font-bold text-neus-cream">Create Proof</h1>
                <p class="text-sm text-neus-text-muted mt-1">Generate a new zero-knowledge proof</p>
            </div>
            
            <!-- Form -->
            <form id="create-proof-form" class="card-neus space-y-6">
                <?php echo csrfField(); ?>
                
                <!-- Verifier Selection -->
                <div class="form-group">
                    <label class="form-label">Verifier Type *</label>
                    <select name="verifierId" required class="input-neus">
                        <option value="">Select a verifier...</option>
                        <?php foreach ($verifierList as $verifier): ?>
                        <option value="<?php echo e($verifier['id']); ?>"><?php echo e($verifier['name'] ?? $verifier['id']); ?></option>
                        <?php endforeach; ?>
                    </select>
                    <p class="form-hint">Choose the type of verification you want to perform</p>
                </div>
                
                <!-- Title -->
                <div class="form-group">
                    <label class="form-label">Title *</label>
                    <input type="text" name="title" required 
                           class="input-neus" 
                           placeholder="e.g., Age Verification"
                           maxlength="100">
                </div>
                
                <!-- Description -->
                <div class="form-group">
                    <label class="form-label">Description</label>
                    <textarea name="description" rows="3" 
                              class="input-neus resize-none" 
                              placeholder="Describe what this proof verifies..."
                              maxlength="500"></textarea>
                </div>
                
                <!-- Chain Selection -->
                <div class="form-group">
                    <label class="form-label">Blockchain *</label>
                    <select name="chainId" required class="input-neus">
                        <option value="">Select chain...</option>
                        <?php foreach (getAllChains() as $key => $chain): ?>
                        <option value="<?php echo e($chain['chainId']); ?>"><?php echo e($chain['name'] . ' (' . $chain['symbol'] . ')'); ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                
                <!-- Wallet Address -->
                <div class="form-group">
                    <label class="form-label">Wallet Address *</label>
                    <input type="text" name="walletAddress" required 
                           class="input-neus font-mono" 
                           placeholder="0x..."
                           value="<?php echo e(getCurrentWallet() ?? ''); ?>">
                </div>
                
                <!-- Data (JSON) -->
                <div class="form-group">
                    <label class="form-label">Proof Data (JSON)</label>
                    <textarea name="data" rows="5" 
                              class="input-neus font-mono text-sm resize-none" 
                              placeholder='{"key": "value"}'>{}]</textarea>
                    <p class="form-hint">Optional JSON data for the proof</p>
                </div>
                
                <!-- Options -->
                <div class="form-group">
                    <label class="flex items-center gap-2 text-sm text-neus-text-secondary cursor-pointer">
                        <input type="checkbox" name="isPublic" class="w-4 h-4 rounded border-neus-border bg-neus-black text-neus-gold focus:ring-neus-gold">
                        Make this proof publicly verifiable
                    </label>
                </div>
                
                <!-- Submit -->
                <div class="flex items-center gap-4 pt-4">
                    <button type="submit" class="btn-primary">
                        <span class="btn-text"><i class="fas fa-shield-alt"></i> Create Proof</span>
                        <span class="btn-loading hidden"><i class="fas fa-circle-notch fa-spin"></i> Creating...</span>
                    </button>
                    
                    <a href="<?php echo pageUrl('/proofs'); ?>" class="btn-secondary">Cancel</a>
                </div>
            </form>
        </div>
        
    </main>
</div>

<script>
document.getElementById('create-proof-form').addEventListener('submit', async (e) => {
    e.preventDefault();
    const form = e.target;
    const btn = form.querySelector('button[type="submit"]');
    const btnText = btn.querySelector('.btn-text');
    const btnLoading = btn.querySelector('.btn-loading');
    
    btnText.classList.add('hidden');
    btnLoading.classList.remove('hidden');
    btn.disabled = true;
    
    const data = new FormData(form);
    
    // Parse JSON data
    let proofData = {};
    try {
        proofData = JSON.parse(data.get('data') || '{}');
    } catch (e) {
        NeusApp.toast('Invalid JSON in proof data', 'error');
        btnText.classList.remove('hidden');
        btnLoading.classList.add('hidden');
        btn.disabled = false;
        return;
    }
    
    try {
        const response = await NeusApp.api('/proofs', {
            method: 'POST',
            body: {
                verifierId: data.get('verifierId'),
                title: data.get('title'),
                description: data.get('description'),
                chainId: data.get('chainId'),
                walletAddress: data.get('walletAddress'),
                data: proofData,
                isPublic: data.get('isPublic') === 'on',
            },
        });
        
        if (response.success) {
            NeusApp.toast('Proof created successfully!', 'success');
            setTimeout(() => {
                window.location.href = '/proofs';
            }, 1000);
        } else {
            NeusApp.toast(response.data?.error || 'Failed to create proof', 'error');
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
