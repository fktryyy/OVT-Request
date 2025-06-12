import './bootstrap';
const html = document.documentElement;
const toggle = document.getElementById('dark-toggle');

toggle.addEventListener('click', () => {
  html.classList.toggle('dark');
});
