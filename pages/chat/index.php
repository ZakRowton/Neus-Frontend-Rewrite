<?php
/**
 * NEUS Frontend Rewrite - Zeus AI Chat Page
 * Real-time chat interface with NEUS AI assistant
 */

requireAuth();

// Fetch chat history
$historyResponse = neusApiRequest('/chat/history', 'GET');
$history = $historyResponse['success'] ? ($historyResponse['data']['data'] ?? []) : [];
?>

<div class="dashboard-layout">
    
    <?php require_once __DIR__ . '/../components/sidebar.php'; ?>
    
    <main class="dashboard-content">
        
        <!-- Chat Container -->
        <div class="h-[calc(100vh-8rem)] flex flex-col">
            
            <!-- Chat Header -->
            <div class="flex items-center gap-4 p-4 border-b border-neus-border mb-4">
                <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-neus-gold to-neus-gold-dim flex items-center justify-center">
                    <i class="fas fa-brain text-neus-black"></i>
                </div>
                <div>
                    <h2 class="font-semibold text-neus-cream">Zeus AI</h2>
                    <p class="text-xs text-green-400 flex items-center gap-1">
                        <span class="w-2 h-2 rounded-full bg-green-400 animate-pulse"></span>
                        Online
                    </p>
                </div>
            </div>
            
            <!-- Messages Area -->
            <div id="chat-messages" class="flex-1 overflow-y-auto space-y-4 px-4 pb-4">
                
                <!-- Welcome Message -->
                <div class="flex items-start gap-3">
                    <div class="w-8 h-8 rounded-lg bg-gradient-to-br from-neus-gold to-neus-gold-dim flex items-center justify-center flex-shrink-0">
                        <i class="fas fa-brain text-xs text-neus-black"></i>
                    </div>
                    <div class="bg-neus-dark border border-neus-border rounded-xl rounded-tl-none px-4 py-3 max-w-[80%]">
                        <p class="text-sm text-neus-cream">
                            Hello! I'm Zeus, your NEUS AI assistant. I can help you with:
                        </p>
                        <ul class="text-sm text-neus-text-secondary mt-2 space-y-1 list-disc list-inside">
                            <li>Creating and managing proofs</li>
                            <li>Deploying and configuring agents</li>
                            <li>Identity and wallet management</li>
                            <li>Understanding NEUS features</li>
                        </ul>
                        <p class="text-sm text-neus-text-secondary mt-2">How can I help you today?</p>
                    </div>
                </div>
                
                <!-- Previous Messages -->
                <?php foreach ($history as $msg): ?>
                <div class="flex items-start gap-3 <?php echo ($msg['role'] ?? '') === 'user' ? 'flex-row-reverse' : ''; ?>">
                    <div class="w-8 h-8 rounded-lg flex items-center justify-center flex-shrink-0 <?php echo ($msg['role'] ?? '') === 'user' ? 'bg-neus-gold/10' : 'bg-gradient-to-br from-neus-gold to-neus-gold-dim'; ?>">
                        <i class="fas fa-<?php echo ($msg['role'] ?? '') === 'user' ? 'user text-neus-gold' : 'brain text-neus-black'; ?> text-xs"></i>
                    </div>
                    <div class="px-4 py-3 max-w-[80%] rounded-xl <?php echo ($msg['role'] ?? '') === 'user' ? 'bg-neus-gold/10 border border-neus-gold/20 rounded-tr-none' : 'bg-neus-dark border border-neus-border rounded-tl-none'; ?>">
                        <p class="text-sm text-neus-cream"><?php echo nl2br(e($msg['content'] ?? '')); ?></p>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
            
            <!-- Input Area -->
            <div class="border-t border-neus-border p-4">
                <form id="chat-form" class="flex items-end gap-2">
                    <div class="flex-1 relative">
                        <textarea id="chat-input" rows="1" 
                                  class="input-neus resize-none pr-12 max-h-32"
                                  placeholder="Ask Zeus anything..."
                                  style="min-height: 44px;"
                        ></textarea>
                        <button type="button" id="chat-send" class="absolute right-2 bottom-2 w-8 h-8 rounded-lg bg-neus-gold text-neus-black flex items-center justify-center hover:bg-neus-gold-light transition-colors">
                            <i class="fas fa-paper-plane text-xs"></i>
                        </button>
                    </div>
                </form>
            </div>
        </div>
        
    </main>
</div>

<script>
// Auto-resize textarea
const chatInput = document.getElementById('chat-input');
chatInput.addEventListener('input', () => {
    chatInput.style.height = 'auto';
    chatInput.style.height = Math.min(chatInput.scrollHeight, 128) + 'px';
});

// Send message
async function sendMessage() {
    const content = chatInput.value.trim();
    if (!content) return;
    
    // Add user message
    addMessage('user', content);
    chatInput.value = '';
    chatInput.style.height = 'auto';
    
    // Show typing indicator
    const typingId = addTypingIndicator();
    
    try {
        const response = await NeusApp.api('/chat', {
            method: 'POST',
            body: { message: content },
        });
        
        // Remove typing indicator
        removeTypingIndicator(typingId);
        
        if (response.success && response.data?.response) {
            addMessage('assistant', response.data.response);
        } else {
            addMessage('assistant', 'I apologize, but I encountered an error. Please try again.');
        }
    } catch (error) {
        removeTypingIndicator(typingId);
        addMessage('assistant', 'Network error. Please check your connection and try again.');
    }
}

function addMessage(role, content) {
    const container = document.getElementById('chat-messages');
    const isUser = role === 'user';
    
    const div = document.createElement('div');
    div.className = `flex items-start gap-3 ${isUser ? 'flex-row-reverse' : ''}`;
    div.innerHTML = `
        <div class="w-8 h-8 rounded-lg flex items-center justify-center flex-shrink-0 ${isUser ? 'bg-neus-gold/10' : 'bg-gradient-to-br from-neus-gold to-neus-gold-dim'}">
            <i class="fas fa-${isUser ? 'user text-neus-gold' : 'brain text-neus-black'} text-xs"></i>
        </div>
        <div class="px-4 py-3 max-w-[80%] rounded-xl ${isUser ? 'bg-neus-gold/10 border border-neus-gold/20 rounded-tr-none' : 'bg-neus-dark border border-neus-border rounded-tl-none'}">
            <p class="text-sm text-neus-cream">${content.replace(/\n/g, '<br>')}</p>
        </div>
    `;
    
    container.appendChild(div);
    container.scrollTop = container.scrollHeight;
}

function addTypingIndicator() {
    const container = document.getElementById('chat-messages');
    const id = 'typing-' + Date.now();
    
    const div = document.createElement('div');
    div.id = id;
    div.className = 'flex items-start gap-3';
    div.innerHTML = `
        <div class="w-8 h-8 rounded-lg bg-gradient-to-br from-neus-gold to-neus-gold-dim flex items-center justify-center flex-shrink-0">
            <i class="fas fa-brain text-xs text-neus-black"></i>
        </div>
        <div class="bg-neus-dark border border-neus-border rounded-xl rounded-tl-none px-4 py-3">
            <div class="flex items-center gap-1">
                <span class="w-2 h-2 rounded-full bg-neus-gold animate-bounce"></span>
                <span class="w-2 h-2 rounded-full bg-neus-gold animate-bounce" style="animation-delay: 0.1s"></span>
                <span class="w-2 h-2 rounded-full bg-neus-gold animate-bounce" style="animation-delay: 0.2s"></span>
            </div>
        </div>
    `;
    
    container.appendChild(div);
    container.scrollTop = container.scrollHeight;
    
    return id;
}

function removeTypingIndicator(id) {
    const el = document.getElementById(id);
    if (el) el.remove();
}

// Event listeners
document.getElementById('chat-send').addEventListener('click', sendMessage);

chatInput.addEventListener('keydown', (e) => {
    if (e.key === 'Enter' && !e.shiftKey) {
        e.preventDefault();
        sendMessage();
    }
});

// Scroll to bottom on load
document.getElementById('chat-messages').scrollTop = document.getElementById('chat-messages').scrollHeight;
</script>
