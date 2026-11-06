<!-- Floating Chatbot Widget Button -->
<div id="tefc-chatbot-launcher" class="tefc-chat-launcher" onclick="toggleTefcChat()">
    <i class="fas fa-comments"></i>
</div>

<!-- Chatbot Window Container -->
<div id="tefc-chatbot-window" class="tefc-chat-window">
    <!-- Header -->
    <div class="tefc-chat-header">
        <div class="tefc-chat-header-info">
            <div class="tefc-chat-avatar"><i class="fas fa-robot"></i></div>
            <div>
                <h4 class="tefc-chat-title">TEFC Assistant</h4>
                <span class="tefc-chat-status"><span class="tefc-chat-dot"></span> Online</span>
            </div>
        </div>
        <div style="display: flex; align-items: center;">
            <button class="tefc-chat-new" onclick="startNewTefcChat()" title="Start New Chat"><i class="fas fa-sync-alt"></i></button>
            <button class="tefc-chat-close" onclick="toggleTefcChat()">&times;</button>
        </div>
    </div>

    <!-- Message Logs -->
    <div id="tefc-chat-messages" class="tefc-chat-messages">
        <!-- Messages loaded dynamically via JS -->
    </div>

    <!-- Typing Bouncing Dots Indicator -->
    <div id="tefc-chat-typing" class="tefc-chat-typing-indicator" style="display: none;">
        <span class="tefc-chat-typing-dot"></span>
        <span class="tefc-chat-typing-dot"></span>
        <span class="tefc-chat-typing-dot"></span>
    </div>

    <!-- Footer Input Bar -->
    <div class="tefc-chat-footer">
        <input type="text" id="tefc-chat-input" placeholder="Type your message here..." onkeydown="handleChatKey(event)">
        <button id="tefc-chat-send" onclick="sendTefcChatMessage()"><i class="fas fa-paper-plane"></i></button>
    </div>
</div>

<!-- Chatbot Styles -->
<style>
.tefc-chat-launcher {
    position: fixed;
    bottom: 25px;
    right: 25px;
    width: 60px;
    height: 60px;
    border-radius: 50%;
    background: #6c1340; /* Primary Burgundy */
    color: #fff;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.6rem;
    box-shadow: 0 5px 25px rgba(108, 21, 63, 0.35);
    cursor: pointer;
    z-index: 99999;
    transition: all 0.3s ease;
}
.tefc-chat-launcher:hover {
    transform: translateY(-4px) scale(1.05);
    box-shadow: 0 8px 30px rgba(108, 21, 63, 0.45);
    background: #4a0e2b;
}

.tefc-chat-window {
    position: fixed;
    bottom: 95px;
    right: 25px;
    width: 380px;
    height: 520px;
    border-radius: 16px;
    background: #fff;
    border: 1px solid #ebdcb9; /* Gold border tint */
    box-shadow: 0 10px 40px rgba(0,0,0,0.12);
    display: flex;
    flex-direction: column;
    z-index: 99999;
    overflow: hidden;
    opacity: 0;
    visibility: hidden;
    transform: translateY(20px) scale(0.95);
    transition: all 0.3s cubic-bezier(0.175, 0.885, 0.32, 1.275);
}
.tefc-chat-window.open {
    opacity: 1;
    visibility: visible;
    transform: translateY(0) scale(1);
}

.tefc-chat-header {
    background: #6c1340;
    padding: 0.9rem 1.2rem;
    display: flex;
    align-items: center;
    justify-content: space-between;
    color: #fff;
}
.tefc-chat-header-info {
    display: flex;
    align-items: center;
    gap: 10px;
}
.tefc-chat-avatar {
    width: 36px;
    height: 36px;
    border-radius: 50%;
    background: rgba(255,255,255,0.15);
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.1rem;
    color: #fff;
}
.tefc-chat-title {
    margin: 0;
    font-size: 0.95rem;
    font-weight: 800;
}
.tefc-chat-status {
    font-size: 0.7rem;
    opacity: 0.85;
    display: flex;
    align-items: center;
    gap: 4px;
}
.tefc-chat-dot {
    width: 6px;
    height: 6px;
    border-radius: 50%;
    background: #4ade80;
    display: inline-block;
}
.tefc-chat-new {
    background: none;
    border: none;
    color: #fff;
    font-size: 1rem;
    cursor: pointer;
    opacity: 0.8;
    transition: all 0.2s;
    margin-right: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
}
.tefc-chat-new:hover {
    opacity: 1;
    color: #f8b803; /* Gold Hover */
    transform: rotate(30deg);
}
.tefc-chat-close {
    background: none;
    border: none;
    color: #fff;
    font-size: 1.6rem;
    cursor: pointer;
    opacity: 0.8;
    transition: opacity 0.2s;
    display: flex;
    align-items: center;
    justify-content: center;
}
.tefc-chat-close:hover {
    opacity: 1;
}

.tefc-chat-messages {
    flex: 1;
    padding: 1.2rem;
    overflow-y: auto;
    background: #fbf9f6;
    display: flex;
    flex-direction: column;
    gap: 12px;
}
.tefc-chat-msg {
    display: flex;
    flex-direction: column;
    max-width: 80%;
}
.tefc-chat-msg.user {
    align-self: flex-end;
}
.tefc-chat-msg.assistant {
    align-self: flex-start;
}
.tefc-chat-msg-bubble {
    padding: 0.75rem 1rem;
    border-radius: 12px;
    font-size: 0.82rem;
    line-height: 1.5;
    word-break: break-word;
}
.tefc-chat-msg.user .tefc-chat-msg-bubble {
    background: #e2e8f0;
    color: #1a1a2e;
    border-bottom-right-radius: 2px;
}
.tefc-chat-msg.assistant .tefc-chat-msg-bubble {
    background: #fbf1f5;
    color: #2d2013;
    border: 1px solid #ebdcb9;
    border-bottom-left-radius: 2px;
}
.tefc-chat-msg-bubble a {
    color: #6c1340;
    font-weight: 700;
    text-decoration: underline;
}

/* Typing Indicator Animation */
.tefc-chat-typing-indicator {
    padding: 8px 16px;
    background: #fbf9f6;
    display: flex;
    gap: 4px;
    align-self: flex-start;
    margin-left: 1.2rem;
    margin-bottom: 8px;
}
.tefc-chat-typing-dot {
    width: 6px;
    height: 6px;
    background: #cbd5e1;
    border-radius: 50%;
    animation: tefcBlink 1.4s infinite both;
}
.tefc-chat-typing-dot:nth-child(2) { animation-delay: .2s; }
.tefc-chat-typing-dot:nth-child(3) { animation-delay: .4s; }

@keyframes tefcBlink {
    0% { opacity: .2; }
    20% { opacity: 1; }
    100% { opacity: .2; }
}

.tefc-chat-footer {
    padding: 0.8rem 1.2rem;
    display: flex;
    gap: 8px;
    border-top: 1px solid #ebdcb9;
    background: #fff;
    align-items: center;
}
.tefc-chat-footer input {
    flex: 1;
    border: 1px solid #cbd5e1;
    border-radius: 8px;
    padding: 0.55rem 0.8rem;
    font-size: 0.82rem;
    outline: none;
    transition: border-color 0.2s;
}
.tefc-chat-footer input:focus {
    border-color: #6c1340;
}
.tefc-chat-footer button {
    width: 36px;
    height: 36px;
    border-radius: 8px;
    background: #6c1340;
    color: #fff;
    border: none;
    cursor: pointer;
    transition: background 0.2s;
    display: flex;
    align-items: center;
    justify-content: center;
}
.tefc-chat-footer button:hover {
    background: #4a0e2b;
}

@media (max-width: 480px) {
    .tefc-chat-window {
        width: calc(100% - 30px);
        height: calc(100% - 100px);
        right: 15px;
        bottom: 85px;
    }
}
</style>

<!-- Chatbot Javascript -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    initializeTefcChat();
});

function getTefcSessionId() {
    let sessionId = localStorage.getItem('tefc_chat_session_id');
    if (!sessionId) {
        sessionId = 'session_' + Date.now() + '_' + Math.random().toString(36).substring(2, 11);
        localStorage.setItem('tefc_chat_session_id', sessionId);
    }
    return sessionId;
}

const defaultWelcomeMessage = `Namaste! I am your <strong>TEFC AI Export Assistant</strong>. 
I can help you with:
<ul style="margin: 5px 0 0 15px; padding: 0;">
    <li>Exporter or Buyer registration</li>
    <li>Government incentive schemes (RoDTEP, PLI, TUFS, etc.)</li>
    <li>Free Trade Agreements (FTAs) & duties</li>
    <li>Searching catalog products and brochures</li>
</ul>
How can I assist you in your textile export journey today?`;

function initializeTefcChat() {
    const chatMessages = document.getElementById('tefc-chat-messages');
    chatMessages.innerHTML = '';

    // Retrieve active session ID
    getTefcSessionId();

    // Check if there is history
    let history = localStorage.getItem('tefc_chat_history');
    if (history) {
        try {
            let messages = JSON.parse(history);
            if (messages && messages.length > 0) {
                messages.forEach(msg => {
                    appendMessageToDom(msg.sender, msg.content, false);
                });
                chatMessages.scrollTop = chatMessages.scrollHeight;
                return;
            }
        } catch (e) {
            console.error("Failed to parse chat history", e);
        }
    }

    // Default message if no history
    appendMessageToDom('assistant', defaultWelcomeMessage, false);
}

function startNewTefcChat() {
    if (confirm("Are you sure you want to clear current chat history and start a new conversation?")) {
        localStorage.removeItem('tefc_chat_history');
        localStorage.removeItem('tefc_chat_session_id');
        initializeTefcChat();
    }
}

function toggleTefcChat() {
    const chatWindow = document.getElementById('tefc-chatbot-window');
    chatWindow.classList.toggle('open');
    if (chatWindow.classList.contains('open')) {
        document.getElementById('tefc-chat-input').focus();
        const chatMessages = document.getElementById('tefc-chat-messages');
        chatMessages.scrollTop = chatMessages.scrollHeight;
    }
}

function handleChatKey(event) {
    if (event.key === 'Enter') {
        sendTefcChatMessage();
    }
}

function formatMarkdownLinks(text) {
    return text.replace(/\[([^\]]+)\]\(([^)]+)\)/g, (match, p1, p2) => {
        let finalUrl = p2;
        if (!p2.startsWith('http://') && !p2.startsWith('https://')) {
            if (!p2.startsWith('/')) {
                finalUrl = '/' + p2;
            }
            finalUrl = '{{ url("") }}' + finalUrl;
        }
        return `<a href="${finalUrl}">${p1}</a>`;
    });
}

function appendMessageToDom(sender, text, shouldSave = true) {
    const chatMessages = document.getElementById('tefc-chat-messages');
    const msgDiv = document.createElement('div');
    msgDiv.className = 'tefc-chat-msg ' + sender;
    
    let formattedText = text;
    if (sender === 'assistant') {
        formattedText = formatMarkdownLinks(formattedText);
        formattedText = formattedText.replace(/\n/g, '<br>');
    } else {
        formattedText = formattedText.replace(/</g, "&lt;").replace(/>/g, "&gt;");
    }

    msgDiv.innerHTML = `<div class="tefc-chat-msg-bubble">${formattedText}</div>`;
    chatMessages.appendChild(msgDiv);

    if (shouldSave) {
        let history = [];
        let rawHistory = localStorage.getItem('tefc_chat_history');
        if (rawHistory) {
            try { history = JSON.parse(rawHistory); } catch(e) {}
        }
        history.push({ sender: sender, content: text });
        localStorage.setItem('tefc_chat_history', JSON.stringify(history));
    }
}

function sendTefcChatMessage() {
    const input = document.getElementById('tefc-chat-input');
    const message = input.value.trim();
    if (!message) return;

    input.value = '';

    const sessionId = getTefcSessionId();

    appendMessageToDom('user', message, true);

    const chatMessages = document.getElementById('tefc-chat-messages');
    chatMessages.scrollTop = chatMessages.scrollHeight;

    const typingIndicator = document.getElementById('tefc-chat-typing');
    typingIndicator.style.display = 'flex';
    chatMessages.appendChild(typingIndicator); 
    chatMessages.scrollTop = chatMessages.scrollHeight;

    fetch('{{ route("api.chat") }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: JSON.stringify({ 
            message: message,
            session_id: sessionId
        })
    })
    .then(response => response.json())
    .then(data => {
        typingIndicator.style.display = 'none';
        let responseText = data.message || "I'm sorry, I encountered an error. Please try again.";
        appendMessageToDom('assistant', responseText, true);
        chatMessages.scrollTop = chatMessages.scrollHeight;
    })
    .catch(error => {
        typingIndicator.style.display = 'none';
        appendMessageToDom('assistant', "Error: Could not connect to the TEFC chat assistant. Please try again later.", false);
        chatMessages.scrollTop = chatMessages.scrollHeight;
    });
}
</script>
