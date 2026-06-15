</div><!-- end main-content -->
    </div><!-- end flex-grow-1 -->
</div><!-- end d-flex -->

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
// Auto dismiss alerts
setTimeout(() => {
    document.querySelectorAll('.alert').forEach(a => {
        a.style.transition = 'opacity 0.6s ease';
        a.style.opacity = '0';
        setTimeout(() => a.remove(), 600);
    });
}, 3000);

// Dark / Light Mode Toggle
function toggleTheme() {
    const body = document.body;
    const icon = document.getElementById('theme-icon');
    const text = document.getElementById('theme-text');
    const isDark = body.classList.toggle('dark-mode');

    if (isDark) {
        icon.className = 'bi bi-sun-fill';
        text.textContent = ' Light';
        localStorage.setItem('theme', 'dark');
    } else {
        icon.className = 'bi bi-moon-fill';
        text.textContent = ' Dark';
        localStorage.setItem('theme', 'light');
    }
}

// Load saved theme on every page
(function() {
    const saved = localStorage.getItem('theme');
    if (saved === 'dark') {
        document.body.classList.add('dark-mode');
        const icon = document.getElementById('theme-icon');
        const text = document.getElementById('theme-text');
        if (icon) icon.className = 'bi bi-sun-fill';
        if (text) text.textContent = ' Light';
    }
})();
</script>
</body>
</html>