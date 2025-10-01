//Valtas animacio
var a = document.getElementById("loginBtn");
var b = document.getElementById("registerBtn");
var l = document.getElementById("passBtn");
var x = document.getElementById("login");
var y = document.getElementById("register");
var p = document.getElementById("pass");

function login() {
    x.style.left = "0px";
    y.style.right = "-520px";
    p.style.left = "-520px";
    a.className += " white-btn";
    b.className = "btn";
    l.className = "btn";
    x.style.opacity = 1;
    p.style.opacity = 0;
    y.style.opacity = 0;
}
function register() {
    x.style.left = "-520px";
    y.style.right = "0px";
    p.style.left = "-520px";
    a.className = "btn";
    b.className += " white-btn";
    l.className = "btn";
    x.style.opacity = 0;
    p.style.opacity = 0;
    y.style.opacity = 1;
}
function Jelszo() {
    x.style.left = "-520px";
    y.style.right = "-520px";
    p.style.left = "0px";
    a.className = "btn";
    b.className = "btn";
    l.className += " white-btn";
    x.style.opacity = 0;
    y.style.opacity = 0;
    p.style.opacity = 1;
}


//Eletkor
const input = document.getElementById("myNumber");
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
