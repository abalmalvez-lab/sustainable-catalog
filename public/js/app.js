/**
 * EcoShelf - App JavaScript
 */

document.addEventListener('DOMContentLoaded', function () {

    // ── Mobile Nav Toggle ──
    const navToggle = document.getElementById('navToggle');
    const navMenu = document.getElementById('navMenu');
    if (navToggle && navMenu) {
        navToggle.addEventListener('click', () => {
            navMenu.classList.toggle('open');
            const icon = navToggle.querySelector('i');
            icon.classList.toggle('fa-bars');
            icon.classList.toggle('fa-times');
        });
    }

    // ── User Dropdown ──
    const userMenuBtn = document.getElementById('userMenuBtn');
    if (userMenuBtn) {
        const userMenu = userMenuBtn.closest('.nav-user-menu');
        userMenuBtn.addEventListener('click', (e) => {
            e.stopPropagation();
            userMenu.classList.toggle('open');
        });
        document.addEventListener('click', (e) => {
            if (!userMenu.contains(e.target)) {
                userMenu.classList.remove('open');
            }
        });
    }

    // ── Filter Sidebar Toggle (Mobile) ──
    const filterToggle = document.getElementById('filterToggle');
    const filterSidebar = document.getElementById('filterSidebar');
    if (filterToggle && filterSidebar) {
        filterToggle.addEventListener('click', () => {
            filterSidebar.classList.toggle('open');
        });
    }

    // ── Password Toggle ──
    document.querySelectorAll('.toggle-password').forEach(btn => {
        btn.addEventListener('click', () => {
            const target = document.getElementById(btn.dataset.target);
            if (target) {
                const isPassword = target.type === 'password';
                target.type = isPassword ? 'text' : 'password';
                const icon = btn.querySelector('i');
                icon.classList.toggle('fa-eye');
                icon.classList.toggle('fa-eye-slash');
            }
        });
    });

    // ── Password Strength Meter ──
    const passwordInput = document.getElementById('password');
    const strengthFill = document.getElementById('strengthFill');
    const strengthText = document.getElementById('strengthText');

    if (passwordInput && strengthFill && strengthText) {
        passwordInput.addEventListener('input', () => {
            const val = passwordInput.value;
            let score = 0;

            if (val.length >= 8) score++;
            if (val.length >= 12) score++;
            if (/[a-z]/.test(val) && /[A-Z]/.test(val)) score++;
            if (/\d/.test(val)) score++;
            if (/[^a-zA-Z0-9]/.test(val)) score++;

            const levels = [
                { width: '0%', color: '#e2e8d4', text: '' },
                { width: '20%', color: '#dc2626', text: 'Very Weak' },
                { width: '40%', color: '#d97706', text: 'Weak' },
                { width: '60%', color: '#eab308', text: 'Fair' },
                { width: '80%', color: '#22c55e', text: 'Good' },
                { width: '100%', color: '#16a34a', text: 'Strong' },
            ];

            const level = levels[score] || levels[0];
            strengthFill.style.width = level.width;
            strengthFill.style.background = level.color;
            strengthText.textContent = level.text;
            strengthText.style.color = level.color;
        });
    }

    // ── Star Rating Input ──
    const starInput = document.getElementById('starInput');
    if (starInput) {
        const stars = starInput.querySelectorAll('.star-label');
        stars.forEach((label, index) => {
            const icon = label.querySelector('i');

            label.addEventListener('mouseenter', () => {
                stars.forEach((s, i) => {
                    const si = s.querySelector('i');
                    if (i <= index) {
                        si.classList.remove('far');
                        si.classList.add('fas');
                    } else {
                        si.classList.remove('fas');
                        si.classList.add('far');
                    }
                });
            });

            label.addEventListener('click', () => {
                stars.forEach((s, i) => {
                    if (i <= index) {
                        s.classList.add('active');
                    } else {
                        s.classList.remove('active');
                    }
                });
            });
        });

        starInput.addEventListener('mouseleave', () => {
            const checked = starInput.querySelector('input:checked');
            const checkedIndex = checked
                ? Array.from(stars).indexOf(checked.closest('.star-label'))
                : -1;

            stars.forEach((s, i) => {
                const si = s.querySelector('i');
                if (i <= checkedIndex) {
                    si.classList.remove('far');
                    si.classList.add('fas');
                } else {
                    si.classList.remove('fas');
                    si.classList.add('far');
                }
            });
        });
    }

    // ── Auto-dismiss flash alerts ──
    const flashAlert = document.getElementById('flashAlert');
    if (flashAlert) {
        setTimeout(() => {
            flashAlert.style.transition = 'opacity .4s, transform .4s';
            flashAlert.style.opacity = '0';
            flashAlert.style.transform = 'translateY(-10px)';
            setTimeout(() => flashAlert.remove(), 400);
        }, 5000);
    }

    // ── Rating filter highlight ──
    document.querySelectorAll('.rating-option input').forEach(input => {
        input.addEventListener('change', () => {
            document.querySelectorAll('.rating-option').forEach(opt => {
                opt.classList.toggle('active', opt.querySelector('input').checked);
            });
        });
    });

});
