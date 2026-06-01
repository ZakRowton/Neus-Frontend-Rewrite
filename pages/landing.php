<?php
/**
 * NEUS Frontend Rewrite - Landing Page
 * Mirrors Next.js landing page with hero, features, CTA
 */
?>

<!-- Hero Section -->
<section class="relative min-h-[90vh] flex items-center justify-center overflow-hidden">
    <!-- Background Effects -->
    <div class="absolute inset-0 bg-gradient-to-b from-neus-black via-[#0f0f0f] to-neus-black"></div>
    <div class="absolute inset-0">
        <div class="absolute top-1/4 left-1/4 w-96 h-96 bg-neus-gold/5 rounded-full blur-[120px]"></div>
        <div class="absolute bottom-1/4 right-1/4 w-96 h-96 bg-neus-gold/3 rounded-full blur-[120px]"></div>
    </div>
    
    <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
        <div class="mb-8 animate-fade-in-up">
            <div class="inline-flex items-center gap-2 px-4 py-2 rounded-full bg-neus-gold/10 border border-neus-gold/20 mb-8">
                <span class="w-2 h-2 rounded-full bg-green-400 animate-pulse"></span>
                <span class="text-sm text-neus-gold">The Sovereign Identity Layer</span>
            </div>
        </div>
        
        <h1 class="text-5xl sm:text-6xl lg:text-7xl font-bold mb-6 leading-tight animate-fade-in-up" style="animation-delay: 0.1s">
            <span class="text-neus-cream">Verify Anything.</span>
            <br>
            <span class="text-gradient-gold">Trust No One.</span>
        </h1>
        
        <p class="text-lg sm:text-xl text-neus-text-secondary max-w-2xl mx-auto mb-10 leading-relaxed animate-fade-in-up" style="animation-delay: 0.2s">
            NEUS is a sovereign identity layer powered by zero-knowledge proofs. 
            Create verifiable claims, manage agents, and interact with AI — all while maintaining 
            complete control of your digital identity.
        </p>
        
        <div class="flex flex-col sm:flex-row items-center justify-center gap-4 animate-fade-in-up" style="animation-delay: 0.3s">
            <a href="<?php echo pageUrl('/signup'); ?>" class="btn-primary text-lg px-8 py-4">
                <i class="fas fa-rocket"></i>
                Get Started
            </a>
            
            <a href="<?php echo pageUrl('/docs'); ?>" class="btn-secondary text-lg px-8 py-4">
                <i class="fas fa-book"></i>
                Documentation
            </a>
        </div>
        
        <?php if (isFeatureEnabled('genesis')): ?>
        <div class="mt-6 animate-fade-in-up" style="animation-delay: 0.4s">
            <a href="<?php echo pageUrl('/genesis'); ?>" class="inline-flex items-center gap-2 text-neus-gold hover:text-neus-gold-light transition-colors">
                <i class="fas fa-sparkles"></i>
                <span class="text-sm">Join the Genesis Campaign &rarr;</span>
            </a>
        </div>
        <?php endif; ?>
        
        <!-- Stats -->
        <div class="grid grid-cols-2 md:grid-cols-4 gap-6 mt-20 max-w-3xl mx-auto animate-fade-in-up" style="animation-delay: 0.5s">
            <div class="text-center">
                <p class="text-3xl font-bold text-neus-gold">10K+</p>
                <p class="text-sm text-neus-text-muted mt-1">Identities</p>
            </div>
            <div class="text-center">
                <p class="text-3xl font-bold text-neus-gold">50K+</p>
                <p class="text-sm text-neus-text-muted mt-1">Proofs</p>
            </div>
            <div class="text-center">
                <p class="text-3xl font-bold text-neus-gold">1K+</p>
                <p class="text-sm text-neus-text-muted mt-1">Agents</p>
            </div>
            <div class="text-center">
                <p class="text-3xl font-bold text-neus-gold">6</p>
                <p class="text-sm text-neus-text-muted mt-1">Chains</p>
            </div>
        </div>
    </div>
</section>

<!-- Features Section -->
<section class="py-24 relative">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-16">
            <h2 class="text-3xl sm:text-4xl font-bold text-neus-cream mb-4">Powered by Zero-Knowledge</h2>
            <p class="text-neus-text-secondary max-w-2xl mx-auto">
                NEUS leverages cutting-edge cryptography to enable privacy-preserving verification 
                of any claim, identity, or credential.
            </p>
        </div>
        
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            <!-- Feature 1: Identity -->
            <div class="card-neus" data-animate>
                <div class="w-12 h-12 rounded-xl bg-neus-gold/10 flex items-center justify-center mb-4">
                    <i class="fas fa-fingerprint text-2xl text-neus-gold"></i>
                </div>
                <h3 class="text-lg font-semibold text-neus-cream mb-2">Sovereign Identity</h3>
                <p class="text-sm text-neus-text-secondary leading-relaxed">
                    Create and manage your sovereign digital identity with full ownership 
                    and control. No centralized authority required.
                </p>
            </div>
            
            <!-- Feature 2: Proofs -->
            <div class="card-neus" data-animate>
                <div class="w-12 h-12 rounded-xl bg-neus-gold/10 flex items-center justify-center mb-4">
                    <i class="fas fa-shield-alt text-2xl text-neus-gold"></i>
                </div>
                <h3 class="text-lg font-semibold text-neus-cream mb-2">Zero-Knowledge Proofs</h3>
                <p class="text-sm text-neus-text-secondary leading-relaxed">
                    Generate and verify cryptographic proofs without revealing underlying data. 
                    Privacy-preserving verification for any claim.
                </p>
            </div>
            
            <!-- Feature 3: Agents -->
            <div class="card-neus" data-animate>
                <div class="w-12 h-12 rounded-xl bg-neus-gold/10 flex items-center justify-center mb-4">
                    <i class="fas fa-robot text-2xl text-neus-gold"></i>
                </div>
                <h3 class="text-lg font-semibold text-neus-cream mb-2">Autonomous Agents</h3>
                <p class="text-sm text-neus-text-secondary leading-relaxed">
                    Deploy AI agents with verifiable identities. Agents can act on your behalf 
                    while maintaining cryptographic accountability.
                </p>
            </div>
            
            <!-- Feature 4: Zeus AI -->
            <div class="card-neus" data-animate>
                <div class="w-12 h-12 rounded-xl bg-neus-gold/10 flex items-center justify-center mb-4">
                    <i class="fas fa-brain text-2xl text-neus-gold"></i>
                </div>
                <h3 class="text-lg font-semibold text-neus-cream mb-2">Zeus AI Chat</h3>
                <p class="text-sm text-neus-text-secondary leading-relaxed">
                    Interact with Zeus, the NEUS AI assistant. Get help with proofs, 
                    agents, identity management, and more.
                </p>
            </div>
            
            <!-- Feature 5: Multi-Chain -->
            <div class="card-neus" data-animate>
                <div class="w-12 h-12 rounded-xl bg-neus-gold/10 flex items-center justify-center mb-4">
                    <i class="fas fa-link text-2xl text-neus-gold"></i>
                </div>
                <h3 class="text-lg font-semibold text-neus-cream mb-2">Multi-Chain Support</h3>
                <p class="text-sm text-neus-text-secondary leading-relaxed">
                    Connect wallets across Ethereum, Polygon, Arbitrum, Base, Optimism, 
                    and BSC. One identity, multiple chains.
                </p>
            </div>
            
            <!-- Feature 6: SDK -->
            <div class="card-neus" data-animate>
                <div class="w-12 h-12 rounded-xl bg-neus-gold/10 flex items-center justify-center mb-4">
                    <i class="fas fa-code text-2xl text-neus-gold"></i>
                </div>
                <h3 class="text-lg font-semibold text-neus-cream mb-2">Developer SDK</h3>
                <p class="text-sm text-neus-text-secondary leading-relaxed">
                    Integrate NEUS into your applications with our comprehensive SDK. 
                    Support for JavaScript, Python, and more.
                </p>
            </div>
        </div>
    </div>
</section>

<!-- How It Works Section -->
<section class="py-24 border-t border-neus-border">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-16">
            <h2 class="text-3xl sm:text-4xl font-bold text-neus-cream mb-4">How It Works</h2>
            <p class="text-neus-text-secondary max-w-2xl mx-auto">
                Get started with NEUS in three simple steps
            </p>
        </div>
        
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            <div class="text-center" data-animate>
                <div class="w-16 h-16 rounded-2xl bg-neus-gold/10 border border-neus-gold/20 flex items-center justify-center mx-auto mb-6">
                    <span class="text-2xl font-bold text-neus-gold">1</span>
                </div>
                <h3 class="text-lg font-semibold text-neus-cream mb-2">Connect Wallet</h3>
                <p class="text-sm text-neus-text-secondary">
                    Link your blockchain wallet to establish your sovereign identity on NEUS.
                </p>
            </div>
            
            <div class="text-center" data-animate>
                <div class="w-16 h-16 rounded-2xl bg-neus-gold/10 border border-neus-gold/20 flex items-center justify-center mx-auto mb-6">
                    <span class="text-2xl font-bold text-neus-gold">2</span>
                </div>
                <h3 class="text-lg font-semibold text-neus-cream mb-2">Create Proofs</h3>
                <p class="text-sm text-neus-text-secondary">
                    Generate zero-knowledge proofs to verify claims without exposing data.
                </p>
            </div>
            
            <div class="text-center" data-animate>
                <div class="w-16 h-16 rounded-2xl bg-neus-gold/10 border border-neus-gold/20 flex items-center justify-center mx-auto mb-6">
                    <span class="text-2xl font-bold text-neus-gold">3</span>
                </div>
                <h3 class="text-lg font-semibold text-neus-cream mb-2">Deploy Agents</h3>
                <p class="text-sm text-neus-text-secondary">
                    Create AI agents with verifiable identities to act on your behalf.
                </p>
            </div>
        </div>
    </div>
</section>

<!-- CTA Section -->
<section class="py-24 border-t border-neus-border">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
        <div class="card-neus p-12 relative overflow-hidden">
            <div class="absolute inset-0 bg-gradient-to-br from-neus-gold/5 to-transparent"></div>
            
            <div class="relative">
                <h2 class="text-3xl sm:text-4xl font-bold text-neus-cream mb-4">Ready to Go Sovereign?</h2>
                <p class="text-neus-text-secondary mb-8 max-w-xl mx-auto">
                    Join thousands of users who have already claimed their sovereign identity. 
                    Start verifying and building trustlessly today.
                </p>
                
                <div class="flex flex-col sm:flex-row items-center justify-center gap-4">
                    <a href="<?php echo pageUrl('/signup'); ?>" class="btn-primary text-lg px-8 py-4">
                        <i class="fas fa-rocket"></i>
                        Create Account
                    </a>
                    
                    <a href="<?php echo pageUrl('/wallet-connect'); ?>" class="wallet-btn text-lg px-8 py-4">
                        <i class="fas fa-wallet"></i>
                        Connect Wallet
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>
