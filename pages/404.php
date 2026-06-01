<?php
/**
 * NEUS Frontend Rewrite - 404 Page
 */

http_response_code(404);
?>

<div class="min-h-[80vh] flex items-center justify-center px-4">
    <div class="text-center">
        <div class="w-24 h-24 rounded-2xl bg-neus-gold/10 flex items-center justify-center mx-auto mb-6">
            <i class="fas fa-compass text-4xl text-neus-gold"></i>
        </div>
        
        <h1 class="text-6xl font-bold text-neus-gold mb-4">404</h1>
        <h2 class="text-2xl font-semibold text-neus-cream mb-4">Page Not Found</h2>
        
        <p class="text-neus-text-secondary mb-8 max-w-md mx-auto">
            The page you're looking for doesn't exist or has been moved. 
            Check the URL or navigate back to safety.
        </p>
        
        <div class="flex flex-col sm:flex-row items-center justify-center gap-4">
            <a href="<?php echo pageUrl('/'); ?>" class="btn-primary">
                <i class="fas fa-home"></i>
                Go Home
            </a>
            
            <a href="<?php echo pageUrl('/dashboard'); ?>" class="btn-secondary">
                <i class="fas fa-chart-line"></i>
                Dashboard
            </a>
        </div>
    </div>
</div>
