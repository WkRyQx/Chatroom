// Valtas animacio
var a = document.getElementsByClassName("loginBtn");
var b = document.getElementsByClassName("registerBtn");
var c = document.getElementsByClassName("passBtn");
var l = document.getElementById("login");
var r = document.getElementById("register");
var p = document.getElementById("pass");
function login() {

    l.style.display = "flex";
    r.style.display = "none";
    p.style.display = "none";
 
    l.style.left = "0px";
    r.style.right = "-520px";
    p.style.left = "-520px";

    a.className = "btn white-btn loginBtn"; 
    b.className = "btn registerBtn";        
    c.className = "btn passBtn";            
}
function register() {

    l.style.display = "none";
    r.style.display = "flex";
    p.style.display = "none";

    l.style.left = "-520px";
    r.style.right = "0px";
    p.style.left = "-520px";

    a.className = "btn loginBtn";             
    b.className = "btn white-btn registerBtn"; 
    c.className = "btn passBtn";              
}
function Jelszo() {
  
    l.style.display = "none";
    r.style.display = "none";
    p.style.display = "flex";

    l.style.left = "-520px";
    r.style.right = "-520px";
    p.style.left = "0px";

    a.className = "btn loginBtn";             
    b.className = "btn registerBtn";          
    c.className = "btn white-btn passBtn";    
}

//Eletkor
const input = document.getElementById("myNumber");
if (input){
  input.addEventListener("input", () => {
    if (input.value.length > 2) {
      input.value = input.value.slice(0, 2);
    }
  });

  input.addEventListener("blur", () => {
    let value = parseInt(input.value, 10);
    let min = parseInt(input.min, 10);
    let max = parseInt(input.max, 10);
    if (isNaN(value)) return; 
    if (value < min) input.value = min;
    if (value > max) input.value = max;
  });
}


//Lenyilo valasztasnal
document.addEventListener('DOMContentLoaded', () => {
  document.querySelectorAll('.dropdown').forEach(dropdown => {
    const input = dropdown.querySelector('.textBox');
    const optionBox = dropdown.querySelector('.option');
    if (!input || !optionBox) return;

    const options = optionBox.querySelectorAll('div');
    if (options.length === 0) return;
    
    input.addEventListener('click', (e) => {
      e.stopPropagation();
      dropdown.classList.toggle('active');

      
      const rect = input.getBoundingClientRect();
      const spaceBelow = window.innerHeight - rect.bottom;
      const spaceAbove = rect.top;

      if (spaceBelow < optionBox.offsetHeight && spaceAbove > spaceBelow) {
        optionBox.style.top = 'auto';
        optionBox.style.bottom = `${input.offsetHeight}px`;
      } else {
        optionBox.style.top = `${input.offsetHeight}px`;
        optionBox.style.bottom = 'auto';
      }
    });

   
    options.forEach(option => {
      option.addEventListener('click', (e) => {
        input.value = option.innerText.trim();  
        dropdown.classList.remove('active');
      });
    });

    document.addEventListener('click', (e) => {
      if (!dropdown.contains(e.target)) {
        dropdown.classList.remove('active');
      }
    });
  });
});

//PasswordShow
document.addEventListener('DOMContentLoaded', function() {
    const password = document.getElementById("show1");
    const toggle = document.getElementById("toggle1");
    if (password && toggle) {
        toggle.addEventListener("click", () => {
            if (password.type === "password") {
                password.type = "text";
                toggle.classList.remove("fa-eye");
                toggle.classList.add("fa-eye-slash");
            } else {
                password.type = "password";
                toggle.classList.remove("fa-eye-slash");
                toggle.classList.add("fa-eye");
            }
        });
    }
});

document.addEventListener('DOMContentLoaded', function() {
    const password2 = document.getElementById("show2");
    const toggle2 = document.getElementById("toggle2");
    if (password2 && toggle2) {
        toggle2.addEventListener("click", () => {
            if (password2.type === "password") {
                password2.type = "text";
                toggle2.classList.remove("fa-eye");
                toggle2.classList.add("fa-eye-slash");
            } else {
                password2.type = "password";
                toggle2.classList.remove("fa-eye-slash");
                toggle2.classList.add("fa-eye");
            }
        });
    }
});

//Dark-Light Mode


(function(){
    function setTheme(theme) {
        document.body.setAttribute('data-theme', theme);
        localStorage.setItem('poolchat_theme', theme);
        document.querySelectorAll('.theme-btn').forEach(btn => btn.classList.remove('active'));
        if (theme === 'light') {
            const el = document.getElementById('light-mode');
            if (el) el.classList.add('active');
        } else {
            const el = document.getElementById('dark-mode');
            if (el) el.classList.add('active');
        }
    }

    document.addEventListener('DOMContentLoaded', () => {
        const lightBtn = document.getElementById('light-mode');
        const darkBtn = document.getElementById('dark-mode');

        if (lightBtn) lightBtn.addEventListener('click', () => setTheme('light'));
        if (darkBtn)  darkBtn.addEventListener('click',  () => setTheme('dark'));

        const stored = localStorage.getItem('poolchat_theme');
        const prefersDark = window.matchMedia && window.matchMedia('(prefers-color-scheme: dark)').matches;
        const initial = stored || (prefersDark ? 'dark' : 'light');

        setTheme(initial);
    });
})();


