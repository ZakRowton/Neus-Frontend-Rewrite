<?php
/**
 * NEUS Frontend Rewrite - Genesis Campaign Page
 */
?>

<div class="min-h-screen">
    
    <!-- Hero -->
    <section class="relative py-24 text-center">
        <div class="absolute inset-0 bg-gradient-to-b from-neus-gold/5 to-transparent"></div>
        
        <div class="relative max-w-4xl mx-auto px-4">
            <div class="inline-flex items-center gap-2 px-4 py-2 rounded-full bg-neus-gold/10 border border-neus-gold/20 mb-8">
                <i class="fas fa-sparkles text-neus-gold"></i>
                <span class="text-sm text-neus-gold">Limited Time Campaign</span>
            </div>
            
            <h1 class="text-4xl sm:text-5xl lg:text-6xl font-bold text-neus-cream mb-6">
                Genesis Campaign
            </h1>
            
            <p class="text-lg text-neus-text-secondary max-w-2xl mx-auto mb-8">
                Be among the first to establish your sovereign identity on NEUS. 
                Genesis participants receive exclusive benefits and early access to all features.
            </p>
            
            <div class="flex flex-col sm:flex-row items-center justify-center gap-4">
                <a href="<?php echo pageUrl('/signup'); ?>" class="btn-primary text-lg px-8 py-4">
                    <i class="fas fa-rocket"></i>
                    Join Genesis
                </a>
            </div>
        </div>
    </section>
    
    <!-- Benefits -->
    <section class="py-24 border-t border-neus-border">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16">
                <h2 class="text-3xl font-bold text-neus-cream mb-4">Genesis Benefits</h2>
                <p class="text-neus-text-secondary">Exclusive perks for early adopters</p>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div class="card-neus">
                    <div class="w-12 h-12 rounded-xl bg-neus-gold/10 flex items-center justify-center mb-4">
                        <i class="fas fa-gift text-2xl text-neus-gold"></i>
                    </div>
                    <h3 class="text-lg font-semibold text-neus-cream mb-2">Free Credits</h3>
                    <p class="text-sm text-neus-text-secondary">Receive 1,000 NEUS credits to start using all platform features immediately.</p>
                </div>
                
                <div class="card-neus">
                    <div class="w-12 h-12 rounded-xl bg-neus-gold/10 flex items-center justify-center mb-4">
                        <i class="fas fa-crown text-2xl text-neus-gold"></i>
                    </div>
                    <h3 class="text-lg font-semibold text-neus-cream mb-2">Genesis Badge</h3>
                    <p class="text-sm text-neus-text-secondary">Exclusive Genesis badge on your profile, visible to all NEUS users.</p>
                </div>
                
                <div class="card-neus">
                    <div class="w-12 h-12 rounded-xl bg-neus-gold/10 flex items-center justify-center mb-4">
                        <i class="fas fa-bolt text-2xl text-neus-gold"></i>
                    </div>
                    <h3 class="text-lg font-semibold text-neus-cream mb-2">Priority Access</h3>
                    <p class="text-sm text-neus-text-secondary">Get early access to new features and priority support from the NEUS team.</p>
                </div>
            </div>
        </div>
    </section>
</div>
