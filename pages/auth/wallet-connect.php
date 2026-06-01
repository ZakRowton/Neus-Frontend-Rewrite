<?php
/**
 * NEUS Frontend Rewrite - Wallet Connect Page
 * Standalone wallet connection for NEUS
 */
?>

<div class="min-h-[80vh] flex items-center justify-center px-4">
    <div class="w-full max-w-lg">
        <!-- Logo -->
        <div class="text-center mb-8">
            <a href="<?php echo pageUrl('/'); ?>" class="inline-flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-neus-gold to-neus-gold-dim flex items-center justify-center">
                    <svg class="w-6 h-6 text-neus-black" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M12 2L2 7l10 5 10-5-10-5z"/>
                        <path d="M2 17l10 5 10-5"/>
                        <path d="M2 12l10 5 10-5"/>
                    </svg>
                </div>
                <span class="text-2xl font-bold text-neus-gold">NEUS</span>
            </a>
        </div>
        
        <div class="card-neus">
            <div class="text-center mb-8">
                <h1 class="text-2xl font-bold text-neus-cream">Connect Your Wallet</h1>
                <p class="text-sm text-neus-text-muted mt-2">
                    Connect a supported wallet to access NEUS features
                </p>
            </div>
            
            <!-- Wallet Options -->
            <div class="space-y-3">
                <!-- MetaMask -->
                <button type="button" class="wallet-option w-full flex items-center gap-4 p-4 rounded-xl border border-neus-border hover:border-neus-gold/50 hover:bg-neus-gold/5 transition-all">
                    <div class="w-10 h-10 rounded-lg bg-[#F6851B] flex items-center justify-center">
                        <svg class="w-6 h-6 text-white" viewBox="0 0 24 24" fill="currentColor">
                            <path d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z"/>
                            <path d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z"/>
                        </svg>
                    </div>
                    <div class="text-left">
                        <p class="font-medium text-neus-cream">MetaMask</p>
                        <p class="text-xs text-neus-text-muted">Popular browser wallet</p>
                    </div>
                    <i class="fas fa-chevron-right ml-auto text-neus-text-muted"></i>
                </button>
                
                <!-- WalletConnect -->
                <button type="button" class="wallet-option w-full flex items-center gap-4 p-4 rounded-xl border border-neus-border hover:border-neus-gold/50 hover:bg-neus-gold/5 transition-all">
                    <div class="w-10 h-10 rounded-lg bg-[#3B99FC] flex items-center justify-center">
                        <svg class="w-6 h-6 text-white" viewBox="0 0 24 24" fill="currentColor">
                            <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-1 15h-2v-6h2v6zm4 0h-2v-6h2v6z"/>
                        </svg>
                    </div>
                    <div class="text-left">
                        <p class="font-medium text-neus-cream">WalletConnect</p>
                        <p class="text-xs text-neus-text-muted">Connect any mobile wallet</p>
                    </div>
                    <i class="fas fa-chevron-right ml-auto text-neus-text-muted"></i>
                </button>
                
                <!-- Coinbase Wallet -->
                <button type="button" class="wallet-option w-full flex items-center gap-4 p-4 rounded-xl border border-neus-border hover:border-neus-gold/50 hover:bg-neus-gold/5 transition-all">
                    <div class="w-10 h-10 rounded-lg bg-[#0052FF] flex items-center justify-center">
                        <svg class="w-6 h-6 text-white" viewBox="0 0 24 24" fill="currentColor">
                            <circle cx="12" cy="12" r="10"/>
                            <path d="M12 17v-2M12 7v5" stroke="white" stroke-width="2" stroke-linecap="round"/>
                        </svg>
                    </div>
                    <div class="text-left">
                        <p class="font-medium text-neus-cream">Coinbase Wallet</p>
                        <p class="text-xs text-neus-text-muted">Coinbase's self-custody wallet</p>
                    </div>
                    <i class="fas fa-chevron-right ml-auto text-neus-text-muted"></i>
                </button>
            </div>
            
            <!-- Info -->
            <div class="mt-6 p-4 rounded-xl bg-neus-gold/5 border border-neus-gold/10">
                <div class="flex items-start gap-3">
                    <i class="fas fa-info-circle text-neus-gold mt-0.5"></i>
                    <div class="text-sm text-neus-text-secondary">
                        <p>Your wallet will be used to:</p>
                        <ul class="mt-2 space-y-1 list-disc list-inside">
                            <li>Sign authentication messages</li>
                            <li>Link your blockchain identity</li>
                            <li>Deploy and manage agents</li>
                        </ul>
                    </div>
                </div>
            </div>
            
            <!-- Back to login -->
            <p class="text-center text-sm text-neus-text-secondary mt-6">
                Want to use email instead?
                <a href="<?php echo pageUrl('/login'); ?>" class="text-neus-gold hover:text-neus-gold-light transition-colors font-medium">
                    Sign in with email
                </a>
            </p>
        </div>
    </div>
</div>

<script>
// Handle wallet option clicks
document.querySelectorAll('.wallet-option').forEach(btn => {
    btn.addEventListener('click', async () => {
        const wallet = await NeusApp.connectWallet();
        if (!wallet) return;
        
        NeusApp.toast(`Connected: ${NeusApp.formatAddress(wallet)}`, 'success');
        
        // Redirect to dashboard or back
        const redirect = new URLSearchParams(window.location.search).get('redirect') || '/dashboard';
        setTimeout(() => {
            window.location.href = redirect;
        }, 1000);
    });
});
</script>
