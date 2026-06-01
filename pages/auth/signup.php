<?php
/**
 * NEUS Frontend Rewrite - Sign Up Page
 */

$flash = getFlash();
?>

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
        
        <!-- Sign Up Card -->
        <div class="card-neus">
            <div class="text-center mb-6">
                <h1 class="text-2xl font-bold text-neus-cream">Create Account</h1>
                <p class="text-sm text-neus-text-muted mt-1">Join the sovereign identity revolution</p>
            </div>
            
            <!-- Flash -->
            <?php if ($flash): ?>
            <div class="mb-4 p-3 rounded-lg <?php echo $flash['type'] === 'error' ? 'bg-red-500/10 border border-red-500/30 text-red-400' : 'bg-green-500/10 border border-green-500/30 text-green-400'; ?> text-sm">
                <?php echo e($flash['message']); ?>
            </div>
            <?php endif; ?>
            
            <!-- Form -->
            <form id="signup-form" class="space-y-4">
                <?php echo csrfField(); ?>
                
                <div class="form-group">
                    <label class="form-label">Username</label>
                    <input type="text" name="username" required 
                           class="input-neus" 
                           placeholder="Choose a username"
                           autocomplete="username"
                           minlength="3"
                           maxlength="30">
                </div>
                
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
                               placeholder="Create a strong password"
                               autocomplete="new-password"
                               id="signup-password"
                               minlength="8">
                        <button type="button" class="absolute right-3 top-1/2 -translate-y-1/2 text-neus-text-muted hover:text-neus-gold transition-colors"
                                onclick="togglePassword('signup-password')">
                            <i class="fas fa-eye"></i>
                        </button>
                    </div>
                    <p class="form-hint">Minimum 8 characters with letters and numbers</p>
                </div>
                
                <div class="form-group">
                    <label class="form-label">Confirm Password</label>
                    <input type="password" name="password_confirm" required 
                           class="input-neus" 
                           placeholder="Repeat your password"
                           autocomplete="new-password">
                </div>
                
                <div class="form-group">
                    <label class="flex items-start gap-2 text-sm text-neus-text-secondary cursor-pointer">
                        <input type="checkbox" name="terms" required class="mt-0.5 w-4 h-4 rounded border-neus-border bg-neus-black text-neus-gold focus:ring-neus-gold">
                        <span>
                            I agree to the 
                            <a href="#" class="text-neus-gold hover:underline">Terms of Service</a>
                            and 
                            <a href="#" class="text-neus-gold hover:underline">Privacy Policy</a>
                        </span>
                    </label>
                </div>
                
                <button type="submit" class="btn-primary w-full justify-center">
                    <span class="btn-text">Create Account</span>
                    <span class="btn-loading hidden">
                        <i class="fas fa-circle-notch fa-spin"></i>
                        Creating...
                    </span>
                </button>
            </form>
            
            <!-- Divider -->
            <div class="relative my-6">
                <div class="absolute inset-0 flex items-center">
                    <div class="w-full border-t border-neus-border"></div>
                </div>
                <div class="relative flex justify-center text-sm">
                    <span class="px-2 bg-[#111] text-neus-text-muted">Or sign up with</span>
                </div>
            </div>
            
            <!-- Wallet Sign Up -->
            <button type="button" id="wallet-signup-btn" class="wallet-btn w-full justify-center">
                <i class="fas fa-wallet"></i>
                Connect Wallet
            </button>
            
            <!-- Login Link -->
            <p class="text-center text-sm text-neus-text-secondary mt-6">
                Already have an account?
                <a href="<?php echo pageUrl('/login'); ?>" class="text-neus-gold hover:text-neus-gold-light transition-colors font-medium">
                    Sign in
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

// Form validation
document.getElementById('signup-form').addEventListener('submit', async (e) => {
    e.preventDefault();
    const form = e.target;
    const data = new FormData(form);
    
    // Validate passwords match
    if (data.get('password') !== data.get('password_confirm')) {
        NeusApp.toast('Passwords do not match', 'error');
        return;
    }
    
    // Validate password strength
    if (data.get('password').length < 8) {
        NeusApp.toast('Password must be at least 8 characters', 'error');
        return;
    }
    
    const btn = form.querySelector('button[type="submit"]');
    const btnText = btn.querySelector('.btn-text');
    const btnLoading = btn.querySelector('.btn-loading');
    
    btnText.classList.add('hidden');
    btnLoading.classList.remove('hidden');
    btn.disabled = true;
    
    try {
        const response = await NeusApp.api('/auth/register', {
            method: 'POST',
            body: {
                username: data.get('username'),
                email: data.get('email'),
                password: data.get('password'),
                csrf_token: data.get('csrf_token'),
            },
        });
        
        if (response.success) {
            NeusApp.setAuth(response.data.user, response.data.token);
            NeusApp.toast('Account created successfully!', 'success');
            setTimeout(() => {
                window.location.href = '/dashboard';
            }, 500);
        } else {
            NeusApp.toast(response.data?.error || 'Registration failed', 'error');
        }
    } catch (error) {
        NeusApp.toast('Network error. Please try again.', 'error');
    } finally {
        btnText.classList.remove('hidden');
        btnLoading.classList.add('hidden');
        btn.disabled = false;
    }
});

// Wallet signup
document.getElementById('wallet-signup-btn').addEventListener('click', async () => {
    const wallet = await NeusApp.connectWallet();
    if (!wallet) return;
    
    const message = `NEUS Sign Up\nWallet: ${wallet}\nTimestamp: ${Date.now()}`;
    const signature = await NeusApp.signMessage(message);
    if (!signature) return;
    
    try {
        const response = await NeusApp.api('/auth/wallet', {
            method: 'POST',
            body: {
                wallet: wallet,
                signature: signature,
                message: message,
                isNewUser: true,
            },
        });
        
        if (response.success) {
            NeusApp.setAuth(response.data.user, response.data.token);
            NeusApp.toast('Wallet connected!', 'success');
            setTimeout(() => {
                window.location.href = '/dashboard';
            }, 500);
        } else {
            NeusApp.toast(response.data?.error || 'Wallet signup failed', 'error');
        }
    } catch (error) {
        NeusApp.toast('Network error. Please try again.', 'error');
    }
});
</script>
