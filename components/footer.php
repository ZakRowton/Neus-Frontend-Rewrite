<?php
/**
 * NEUS Frontend Rewrite - Footer Component
 */
?>

<!-- Footer -->
<footer class="border-t border-neus-border bg-neus-black/50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
        <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
            <!-- Brand -->
            <div class="md:col-span-1">
                <a href="<?php echo pageUrl('/'); ?>" class="flex items-center gap-2 mb-4">
                    <div class="w-8 h-8 rounded-lg bg-gradient-to-br from-neus-gold to-neus-gold-dim flex items-center justify-center">
                        <svg class="w-5 h-5 text-neus-black" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M12 2L2 7l10 5 10-5-10-5z"/>
                            <path d="M2 17l10 5 10-5"/>
                            <path d="M2 12l10 5 10-5"/>
                        </svg>
                    </div>
                    <span class="text-lg font-bold text-neus-gold">NEUS</span>
                </a>
                <p class="text-sm text-neus-text-muted leading-relaxed">
                    The Sovereign Identity Layer. Verify anything. Trust no one.
                </p>
                <div class="flex items-center gap-3 mt-4">
                    <a href="https://twitter.com/neusnetwork" target="_blank" class="w-8 h-8 rounded-lg bg-neus-gold/10 flex items-center justify-center text-neus-text-muted hover:text-neus-gold hover:bg-neus-gold/20 transition-all">
                        <i class="fab fa-x-twitter"></i>
                    </a>
                    <a href="https://github.com/neusnetwork" target="_blank" class="w-8 h-8 rounded-lg bg-neus-gold/10 flex items-center justify-center text-neus-text-muted hover:text-neus-gold hover:bg-neus-gold/20 transition-all">
                        <i class="fab fa-github"></i>
                    </a>
                    <a href="https://discord.gg/neus" target="_blank" class="w-8 h-8 rounded-lg bg-neus-gold/10 flex items-center justify-center text-neus-text-muted hover:text-neus-gold hover:bg-neus-gold/20 transition-all">
                        <i class="fab fa-discord"></i>
                    </a>
                </div>
            </div>
            
            <!-- Product -->
            <div>
                <h4 class="text-sm font-semibold text-neus-cream mb-4">Product</h4>
                <ul class="space-y-2">
                    <li><a href="<?php echo pageUrl('/proofs'); ?>" class="text-sm text-neus-text-muted hover:text-neus-gold transition-colors">Proofs</a></li>
                    <li><a href="<?php echo pageUrl('/agents'); ?>" class="text-sm text-neus-text-muted hover:text-neus-gold transition-colors">Agents</a></li>
                    <li><a href="<?php echo pageUrl('/chat'); ?>" class="text-sm text-neus-text-muted hover:text-neus-gold transition-colors">Zeus AI</a></li>
                    <li><a href="<?php echo pageUrl('/verify'); ?>" class="text-sm text-neus-text-muted hover:text-neus-gold transition-colors">Verify</a></li>
                </ul>
            </div>
            
            <!-- Resources -->
            <div>
                <h4 class="text-sm font-semibold text-neus-cream mb-4">Resources</h4>
                <ul class="space-y-2">
                    <li><a href="<?php echo pageUrl('/docs'); ?>" class="text-sm text-neus-text-muted hover:text-neus-gold transition-colors">Documentation</a></li>
                    <li><a href="<?php echo pageUrl('/docs'); ?>" class="text-sm text-neus-text-muted hover:text-neus-gold transition-colors">API Reference</a></li>
                    <li><a href="<?php echo pageUrl('/docs'); ?>" class="text-sm text-neus-text-muted hover:text-neus-gold transition-colors">SDK</a></li>
                    <li><a href="<?php echo pageUrl('/about'); ?>" class="text-sm text-neus-text-muted hover:text-neus-gold transition-colors">About</a></li>
                </ul>
            </div>
            
            <!-- Legal -->
            <div>
                <h4 class="text-sm font-semibold text-neus-cream mb-4">Legal</h4>
                <ul class="space-y-2">
                    <li><a href="#" class="text-sm text-neus-text-muted hover:text-neus-gold transition-colors">Privacy Policy</a></li>
                    <li><a href="#" class="text-sm text-neus-text-muted hover:text-neus-gold transition-colors">Terms of Service</a></li>
                    <li><a href="#" class="text-sm text-neus-text-muted hover:text-neus-gold transition-colors">Cookie Policy</a></li>
                    <li><a href="<?php echo pageUrl('/contact'); ?>" class="text-sm text-neus-text-muted hover:text-neus-gold transition-colors">Contact</a></li>
                </ul>
            </div>
        </div>
        
        <div class="border-t border-neus-border mt-8 pt-8 flex flex-col sm:flex-row items-center justify-between gap-4">
            <p class="text-xs text-neus-text-muted">
                &copy; <?php echo date('Y'); ?> NEUS Network. All rights reserved.
            </p>
            <p class="text-xs text-neus-text-muted font-mono">
                v2.0.0 &bull; Sovereign Identity Layer
            </p>
        </div>
    </div>
</footer>
