let currentUser = null;
let currentTheme = 'light';
let posts = [];
let userStats = {
    posts: 0,
    likes: 0
};
let chatPartner = null;
let chatMessages = [];

// Iskola nevek mapping
const schoolNames = {
    'boros': 'Boros Sámuel Technikum',
    'pollak': 'Pollák Antal Technikum',
    'zsoldos': 'Zsoldos Ferenc Technikum',
    'horvath': 'Horváth Mihály Gimnázium',
    'szte': 'SZTE',
    'kefo': 'KEFŐ'
};

// Nem mapping
const genderNames = {
    'ferfi': 'Férfi',
    'no': 'Nő'
};

// Inicializálás
document.addEventListener('DOMContentLoaded', function() {
    initializeApp();
    setupEventListeners();
});

function initializeApp() {
    // Ellenőrizzük, hogy be van-e jelentkezve a felhasználó
    const savedUser = localStorage.getItem('poolchat_user');
    if (savedUser) {
        currentUser = JSON.parse(savedUser);
        showMainMenu();
        updateUserInterface();
    }
    
    // Téma betöltése
    const savedTheme = localStorage.getItem('poolchat_theme') || 'light';
    setTheme(savedTheme);
    
    // Statisztikák betöltése
    const savedStats = localStorage.getItem('poolchat_stats');
    if (savedStats) {
        userStats = JSON.parse(savedStats);
    }
    
    // Bejegyzések betöltése
    const savedPosts = localStorage.getItem('poolchat_posts');
    if (savedPosts) {
        posts = JSON.parse(savedPosts);
        renderPosts();
    }
}

function setupEventListeners() {
    // Auth form listeners
    document.getElementById('login-form').addEventListener('submit', handleLogin);
    document.getElementById('register-form').addEventListener('submit', handleRegister);
    
    // Settings listeners
    document.getElementById('settings-btn').addEventListener('click', toggleSettings);
    document.getElementById('close-settings').addEventListener('click', closeSettings);
    document.getElementById('light-mode').addEventListener('click', () => setTheme('light'));
    document.getElementById('dark-mode').addEventListener('click', () => setTheme('dark'));
    
    // Chat input listener
    document.getElementById('message-input').addEventListener('keypress', function(e) {
        if (e.key === 'Enter') {
            sendMessage();
        }
    });
}

// Auth functions
function showLogin() {
    document.querySelector('.tab-btn.active').classList.remove('active');
    document.querySelector('.auth-form.active').classList.remove('active');
    
    document.querySelectorAll('.tab-btn')[0].classList.add('active');
    document.getElementById('login-form').classList.add('active');
}

function showRegister() {
    document.querySelector('.tab-btn.active').classList.remove('active');
    document.querySelector('.auth-form.active').classList.remove('active');
    
    document.querySelectorAll('.tab-btn')[1].classList.add('active');
    document.getElementById('register-form').classList.add('active');
}

function handleLogin(e) {
    e.preventDefault();
    
    const username = document.getElementById('login-username').value;
    const password = document.getElementById('login-password').value;
    const school = document.getElementById('login-school').value;
    
    // Egyszerű validáció (valós alkalmazásban szerver oldali authentikáció kellene)
    const savedUser = localStorage.getItem(`user_${username}`);
    if (savedUser) {
        const userData = JSON.parse(savedUser);
        if (userData.password === password && userData.school === school) {
            currentUser = userData;
            localStorage.setItem('poolchat_user', JSON.stringify(currentUser));
            showMainMenu();
            updateUserInterface();
        } else {
            alert('Hibás bejelentkezési adatok!');
        }
    } else {
        alert('Felhasználó nem található!');
    }
}

function handleRegister(e) {
    e.preventDefault();
    
    const username = document.getElementById('reg-username').value;
    const password = document.getElementById('reg-password').value;
    const school = document.getElementById('reg-school').value;
    const age = document.getElementById('reg-age').value;
    const gender = document.getElementById('reg-gender').value;
    const om = document.getElementById('reg-om').value;
    
    // Ellenőrizzük, hogy létezik-e már a felhasználó
    if (localStorage.getItem(`user_${username}`)) {
        alert('Ez a felhasználónév már foglalt!');
        return;
    }
    
    const userData = {
        username,
        password,
        school,
        age: parseInt(age),
        gender,
        om,
        registeredAt: new Date().toISOString()
    };
    
    // Mentjük a felhasználót
    localStorage.setItem(`user_${username}`, JSON.stringify(userData));
    
    currentUser = userData;
    localStorage.setItem('poolchat_user', JSON.stringify(currentUser));
    
    showMainMenu();
    updateUserInterface();
}

function logout() {
    currentUser = null;
    localStorage.removeItem('poolchat_user');
    showScreen('auth-screen');
    
    // Reset forms
    document.getElementById('login-form').reset();
    document.getElementById('register-form').reset();
}

// Navigation functions
function showScreen(screenId) {
    document.querySelectorAll('.screen').forEach(screen => {
        screen.classList.remove('active');
    });
    document.getElementById(screenId).classList.add('active');
}

function showMainMenu() {
    showScreen('main-screen');
}

function showSection(section) {
    showScreen(`${section}-screen`);
    
    if (section === 'profile') {
        updateProfileInfo();
    }
}

// Settings functions
function toggleSettings() {
    const panel = document.getElementById('settings-panel');
    panel.classList.toggle('active');
}

function closeSettings() {
    document.getElementById('settings-panel').classList.remove('active');
}

function setTheme(theme) {
    currentTheme = theme;
    document.body.setAttribute('data-theme', theme);
    localStorage.setItem('poolchat_theme', theme);
    
    // Update theme buttons
    document.querySelectorAll('.theme-btn').forEach(btn => {
        btn.classList.remove('active');
    });
    
    if (theme === 'light') {
        document.getElementById('light-mode').classList.add('active');
    } else {
        document.getElementById('dark-mode').classList.add('active');
    }
}

function updateUserInterface() {
    if (!currentUser) return;
    
    // Update settings panel
    document.getElementById('user-school').textContent = schoolNames[currentUser.school];
    document.getElementById('user-age').textContent = currentUser.age;
    document.getElementById('user-gender').textContent = genderNames[currentUser.gender];
    document.getElementById('user-posts').textContent = userStats.posts;
    document.getElementById('user-likes').textContent = userStats.likes;
}

function updateProfileInfo() {
    if (!currentUser) return;
    
    document.getElementById('profile-username').textContent = currentUser.username;
    document.getElementById('profile-school-info').textContent = 
        `${schoolNames[currentUser.school]} • ${currentUser.age} éves • ${genderNames[currentUser.gender]}`;
    document.getElementById('profile-posts').textContent = userStats.posts;
    document.getElementById('profile-likes').textContent = userStats.likes;
}

// Posts functions
function addPost() {
    const content = document.getElementById('new-post').value.trim();
    if (!content) {
        alert('Kérlek írj valamit!');
        return;
    }
    
    const post = {
        id: Date.now(),
        author: currentUser.username,
        content: content,
        timestamp: new Date().toISOString(),
        likes: 0,
        likedBy: []
    };
    
    posts.unshift(post);
    userStats.posts++;
    
    // Mentés
    localStorage.setItem('poolchat_posts', JSON.stringify(posts));
    localStorage.setItem('poolchat_stats', JSON.stringify(userStats));
    
    // UI frissítés
    document.getElementById('new-post').value = '';
    renderPosts();
    updateUserInterface();
}

function renderPosts() {
    const container = document.getElementById('posts-container');
    
    // Megtartjuk a példa bejegyzést, ha nincs más
    if (posts.length === 0) {
        return;
    }
    
    container.innerHTML = '';
    
    posts.forEach(post => {
        const postElement = document.createElement('div');
        postElement.className = 'post';
        
        const timeAgo = getTimeAgo(new Date(post.timestamp));
        const isLiked = post.likedBy.includes(currentUser?.username);
        
        postElement.innerHTML = `
            <div class="post-header">
                <strong>${post.author}</strong>
                <span class="post-time">${timeAgo}</span>
            </div>
            <div class="post-content">
                ${post.content}
            </div>
            <div class="post-actions">
                <button class="like-btn ${isLiked ? 'liked' : ''}" onclick="toggleLike(this, ${post.id})">
                    <i class="${isLiked ? 'fas' : 'far'} fa-heart"></i>
                    <span class="like-count">${post.likes}</span>
                </button>
            </div>
        `;
        
        container.appendChild(postElement);
    });
}

function toggleLike(button, postId) {
    if (!currentUser) return;
    
    const post = posts.find(p => p.id === postId);
    if (!post) return;
    
    const userIndex = post.likedBy.indexOf(currentUser.username);
    
    if (userIndex === -1) {
        // Like
        post.likedBy.push(currentUser.username);
        post.likes++;
        button.classList.add('liked');
        button.querySelector('i').className = 'fas fa-heart';
        userStats.likes++;
    } else {
        // Unlike
        post.likedBy.splice(userIndex, 1);
        post.likes--;
        button.classList.remove('liked');
        button.querySelector('i').className = 'far fa-heart';
        userStats.likes--;
    }
    
    button.querySelector('.like-count').textContent = post.likes;
    
    // Mentés
    localStorage.setItem('poolchat_posts', JSON.stringify(posts));
    localStorage.setItem('poolchat_stats', JSON.stringify(userStats));
    updateUserInterface();
}

function getTimeAgo(date) {
    const now = new Date();
    const diffInMinutes = Math.floor((now - date) / (1000 * 60));
    
    if (diffInMinutes < 1) return 'most';
    if (diffInMinutes < 60) return `${diffInMinutes} perce`;
    
    const diffInHours = Math.floor(diffInMinutes / 60);
    if (diffInHours < 24) return `${diffInHours} órája`;
    
    const diffInDays = Math.floor(diffInHours / 24);
    return `${diffInDays} napja`;
}

// Chat functions
function startChat() {
    const button = document.getElementById('start-chat-btn');
    const btnText = button.querySelector('.btn-text');
    const spinner = button.querySelector('.loading-spinner');
    
    // Loading animation
    button.disabled = true;
    btnText.style.display = 'none';
    spinner.style.display = 'block';
    
    // Simulate finding a partner
    setTimeout(() => {
        // Generate random partner
        const schools = Object.keys(schoolNames);
        const genders = Object.keys(genderNames);
        
        chatPartner = {
            name: 'Anonymous',
            school: schools[Math.floor(Math.random() * schools.length)],
            age: Math.floor(Math.random() * 10) + 16, // 16-25 years
            gender: genders[Math.floor(Math.random() * genders.length)]
        };
        
        // Reset UI
        button.disabled = false;
        btnText.style.display = 'block';
        spinner.style.display = 'none';
        
        // Show chat window
        showChatWindow();
    }, 2000);
}

function showChatWindow() {
    document.getElementById('start-chat-btn').style.display = 'none';
    document.getElementById('chat-window').style.display = 'flex';
    
    // Update partner info
    document.getElementById('partner-name').textContent = chatPartner.name;
    document.getElementById('partner-school').textContent = schoolNames[chatPartner.school];
    document.getElementById('partner-age').textContent = chatPartner.age;
    document.getElementById('partner-gender').textContent = genderNames[chatPartner.gender];
    
    // Clear messages
    chatMessages = [];
    document.getElementById('messages-container').innerHTML = '';
    
    // Focus on input
    document.getElementById('message-input').focus();
    
    // Simulate partner messages
    setTimeout(() => {
        receiveMessage('Szia! 👋');
    }, 1000);
}

function sendMessage() {
    const input = document.getElementById('message-input');
    const message = input.value.trim();
    
    if (!message) return;
    
    // Add message to chat
    addMessageToChat(message, true);
    input.value = '';
    
    // Simulate partner response
    setTimeout(() => {
        const responses = [
            'Érdekes! 🤔',
            'Igen, egyetértek!',
            'Mesélj többet erről!',
            'Haha, vicces! 😄',
            'Tényleg? Nem tudtam!',
            'Szuper ötlet!',
            'Hmm, értem...',
            'Köszi, hogy megosztottad!'
        ];
        const randomResponse = responses[Math.floor(Math.random() * responses.length)];
        receiveMessage(randomResponse);
    }, Math.random() * 3000 + 1000);
}

function receiveMessage(message) {
    addMessageToChat(message, false);
}

function addMessageToChat(message, isOwn) {
    const container = document.getElementById('messages-container');
    const messageElement = document.createElement('div');
    messageElement.className = `message ${isOwn ? 'own' : ''}`;
    
    messageElement.innerHTML = `
        <div class="message-bubble">
            ${message}
        </div>
    `;
    
    container.appendChild(messageElement);
    container.scrollTop = container.scrollHeight;
    
    chatMessages.push({
        message,
        isOwn,
        timestamp: new Date().toISOString()
    });
}

function endChat() {
    document.getElementById('chat-window').style.display = 'none';
    document.getElementById('start-chat-btn').style.display = 'block';
    
    chatPartner = null;
    chatMessages = [];
}

// Initialize example posts if none exist
function initializeExamplePosts() {
    if (posts.length === 0) {
        posts.push({
            id: 1,
            author: 'Anonymous',
            content: 'Új tanulási módszer: gamifikált programozás oktatás VR környezetben!',
            timestamp: new Date(Date.now() - 2 * 60 * 60 * 1000).toISOString(), // 2 hours ago
            likes: 5,
            likedBy: []
        });
        
        localStorage.setItem('poolchat_posts', JSON.stringify(posts));
    }
}

// Call this when the app initializes
setTimeout(initializeExamplePosts, 100);
