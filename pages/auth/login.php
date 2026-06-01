<?php
/**
 * NEUS Frontend Rewrite - Login Page
 */

$flash = getFlash();
?>

<!-- Auth Layout Wrapper -->
<div class="min-h-[80vh] flex items-center justify-center px-4">
    <div class="w-full max-w-md">
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
        
        <!-- Login Card -->
        <div class="card-neus">
            <div class="text-center mb-6">
                <h1 class="text-2xl font-bold text-neus-cream">Welcome Back</h1>
                <p class="text-sm text-neus-text-muted mt-1">Sign in to your NEUS account</p>
            </div>
            
            <!-- Flash Message -->
            <?php if ($flash): ?>
            <div class="mb-4 p-3 rounded-lg <?php echo $flash['type'] === 'error' ? 'bg-red-500/10 border border-red-500/30 text-red-400' : 'bg-green-500/10 border border-green-500/30 text-green-400'; ?> text-sm">
                <?php echo e($flash['message']); ?>
            </div>
            <?php endif; ?>
            
            <!-- Login Form -->
            <form id="login-form" class="space-y-4">
                <?php echo csrfField(); ?>
                
                <div class="form-group">
                    <label class="form-label">Email Address</label>
                    <input type="email" name="email" required 
                           class="input-neus" 
                           placeholder="you@example.com"
                           autocomplete="email">
                </div>
                
                <div class="form-group">
                    <label class="form-label">Password</label>
                    <div class="relative">
                        <input type="password" name="password" required 
                               class="input-neus pr-10" 
                               placeholder="Enter your password"
                               autocomplete="current-password"
                               id="login-password">
                        <button type="button" class="absolute right-3 top-1/2 -translate-y-1/2 text-neus-text-muted hover:text-neus-gold transition-colors"
                                onclick="togglePassword('login-password')">
                            <i class="fas fa-eye"></i>
                        </button>
                    </div>
                </div>
                
                <div class="flex items-center justify-between">
                    <label class="flex items-center gap-2 text-sm text-neus-text-secondary cursor-pointer">
                        <input type="checkbox" name="remember" class="w-4 h-4 rounded border-neus-border bg-neus-black text-neus-gold focus:ring-neus-gold">
                        Remember me
                    </label>
                    
                    <a href="#" class="text-sm text-neus-gold hover:text-neus-gold-light transition-colors">
                        Forgot password?
                    </a>
                </div>
                
                <button type="submit" class="btn-primary w-full justify-center">
                    <span class="btn-text">Sign In</span>
                    <span class="btn-loading hidden">
                        <i class="fas fa-circle-notch fa-spin"></i>
                        Signing in...
                    </span>
                </button>
            </form>
            
            <!-- Divider -->
            <div class="relative my-6">
                <div class="absolute inset-0 flex items-center">
                    <div class="w-full border-t border-neus-border"></div>
                </div>
                <div class="relative flex justify-center text-sm">
                    <span class="px-2 bg-[#111] text-neus-text-muted">Or continue with</span>
                </div>
            </div>
            
            <!-- Wallet Connect -->
            <button type="button" id="wallet-login-btn" class="wallet-btn w-full justify-center">
                <i class="fas fa-wallet"></i>
                Connect Wallet
            </button>
            
            <!-- Sign Up Link -->
            <p class="text-center text-sm text-neus-text-secondary mt-6">
                Don't have an account?
                <a href="<?php echo pageUrl('/signup'); ?>" class="text-neus-gold hover:text-neus-gold-light transition-colors font-medium">
                    Sign up
                </a>
            </p>
        </div>
    </div>
</div>

<script>
function togglePassword(inputId) {
    const input = document.getElementById(inputId);
    const icon = input.nextElementSibling.querySelector('i');
    if (input.type === 'password') {
        input.type = 'text';
        icon.classList.replace('fa-eye', 'fa-eye-slash');
    } else {
        input.type = 'password';
        icon.classList.replace('fa-eye-slash', 'fa-eye');
    }
}

// Form submission
document.getElementById('login-form').addEventListener('submit', async (e) => {
    e.preventDefault();
    const form = e.target;
    const btn = form.querySelector('button[type="submit"]');
    const btnText = btn.querySelector('.btn-text');
    const btnLoading = btn.querySelector('.btn-loading');
    
    // Show loading
    btnText.classList.add('hidden');
    btnLoading.classList.remove('hidden');
    btn.disabled = true;
    
    const data = new FormData(form);
    
    try {
        const response = await NeusApp.api('/auth/login', {
            method: 'POST',
            body: {
                email: data.get('email'),
                password: data.get('password'),
                csrf_token: data.get('csrf_token'),
            },
        });
        
        if (response.success) {
            NeusApp.setAuth(response.data.user, response.data.token);
            NeusApp.toast('Welcome back!', 'success');
            
            // Redirect
            const redirect = new URLSearchParams(window.location.search).get('redirect') || '/dashboard';
            setTimeout(() => {
                window.location.href = redirect;
            }, 500);
        } else {
            NeusApp.toast(response.data?.error || 'Login failed', 'error');
        }
    } catch (error) {
        NeusApp.toast('Network error. Please try again.', 'error');
    } finally {
        btnText.classList.remove('hidden');
        btnLoading.classList.add('hidden');
        btn.disabled = false;
    }
});

// Wallet login
document.getElementById('wallet-login-btn').addEventListener('click', async () => {
    const wallet = await NeusApp.connectWallet();
    if (!wallet) return;
    
    // Generate message to sign
    const message = `NEUS Login\nWallet: ${wallet}\nTimestamp: ${Date.now()}\nNonce: ${Math.random().toString(36).slice(2)}`;
    
    const signature = await NeusApp.signMessage(message);
    if (!signature) return;
    
    try {
        const response = await NeusApp.api('/auth/wallet', {
            method: 'POST',
            body: {
                wallet: wallet,
                signature: signature,
                message: message,
            },
        });
        
        if (response.success) {
            NeusApp.setAuth(response.data.user, response.data.token);
            NeusApp.toast('Wallet connected!', 'success');
            setTimeout(() => {
                window.location.href = '/dashboard';
            }, 500);
        } else {
            NeusApp.toast(response.data?.error || 'Wallet login failed', 'error');
        }
    } catch (error) {
        NeusApp.toast('Network error. Please try again.', 'error');
    }
});
</script>
