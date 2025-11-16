document.addEventListener('DOMContentLoaded', () => {

    // Helper: chỉ chặn click nếu href là #
    const safeClick = (selector, handler) => {
        document.querySelectorAll(selector).forEach(el => {
            el.addEventListener('click', (e) => {
                const href = el.getAttribute('href');
                if (href === '#' || !href) e.preventDefault();
                handler(e, el);
            });
        });
    };

    // ========== 1. ĐẶT LỊCH KHÁM ==========
    const setupSelection = (selector, activeClass) => {
        const items = document.querySelectorAll(selector);
        items.forEach(item => {
            item.addEventListener('click', (e) => {
                // không chặn link thật
                const href = item.getAttribute('href');
                if (href === '#' || !href) e.preventDefault();

                items.forEach(i => i.classList.remove(
                    activeClass, 'bg-blue-50', 'text-blue-600', 'border-blue-600',
                    'shadow-lg', 'bg-blue-600', 'text-white', 'hover:bg-blue-700'
                ));
                if (selector.includes('time-slot')) {
                    item.classList.add(activeClass, 'bg-blue-600', 'text-white', 'border-blue-600', 'hover:bg-blue-700');
                } else {
                    item.classList.add(activeClass, 'bg-blue-50', 'text-blue-600', 'border-blue-600', 'shadow-lg');
                }
            });
        });
    };

    setupSelection('.specialty-item', 'active');
    setupSelection('.doctor-card', 'active');
    setupSelection('.time-slot', 'active');

    const dateInput = document.getElementById('date-input');
    if (dateInput) {
        const today = new Date();
        const year = today.getFullYear();
        const month = String(today.getMonth() + 1).padStart(2, '0');
        const day = String(today.getDate()).padStart(2, '0');
        dateInput.value = `${year}-${month}-${day}`;
        dateInput.min = dateInput.value;
    }

    const bookingForm = document.getElementById('booking-form');
    if (bookingForm) {
        bookingForm.addEventListener('submit', (e) => {
            e.preventDefault();
            document.querySelector('.submit-button').innerHTML = `
                <div class="p-4 bg-green-100 border border-green-400 text-green-700 rounded-lg shadow-inner mt-4">
                    <i class="fas fa-check-circle mr-2"></i> 
                    <strong>Đặt lịch thành công!</strong> Chúng tôi sẽ xác nhận qua SĐT sớm nhất.
                </div>
            `;
        });
    }

    // ========== 2. DỊCH VỤ ==========
    const categories = document.querySelectorAll('.category-item');
    categories.forEach(item => {
        item.addEventListener('click', (e) => {
            const href = item.getAttribute('href');
            if (href === '#' || !href) e.preventDefault();

            categories.forEach(i => {
                i.classList.remove('active', 'bg-blue-600', 'text-white', 'shadow-lg', 'shadow-blue-500/50', 'hover:bg-blue-700');
                i.classList.add('text-gray-700', 'font-medium', 'hover:bg-gray-100');
            });
            item.classList.add('active', 'bg-blue-600', 'text-white', 'shadow-lg', 'shadow-blue-500/50', 'hover:bg-blue-700');
            item.classList.remove('text-gray-700', 'font-medium', 'hover:bg-gray-100');
        });
    });

    const ctaButton = document.querySelector('.cta-input-group .btn');
    if (ctaButton) {
        const href = ctaButton.getAttribute('href');
        if (!href || href === '#') {
            ctaButton.setAttribute('href', 'dat_lich_kham_tailwind.html');
        }
    }

    // ========== 3. HỒ SƠ BỆNH ÁN ==========
    const tabs = document.querySelectorAll('.tab-item');
    const contents = document.querySelectorAll('.tab-content');
    tabs.forEach(tab => {
        tab.addEventListener('click', (e) => {
            const targetId = tab.getAttribute('data-tab');
            const href = tab.getAttribute('href');
            if (href === '#' || !href) e.preventDefault();

            tabs.forEach(t => {
                t.classList.remove('active', 'text-teal-600', 'border-teal-600');
                t.classList.add('text-gray-600', 'border-transparent');
            });
            contents.forEach(c => c.classList.add('hidden'));
            contents.forEach(c => c.classList.remove('active'));

            tab.classList.add('active', 'text-teal-600', 'border-teal-600');
            tab.classList.remove('text-gray-600', 'border-transparent');
            const targetContent = document.getElementById(targetId);
            if (targetContent) {
                targetContent.classList.add('active');
                targetContent.classList.remove('hidden');
            }
        });
    });

    // ========== 4. THANH TOÁN ==========
    const toggleButtons = document.querySelectorAll('.toggle-payment-detail');
    toggleButtons.forEach(button => {
        button.addEventListener('click', (e) => {
            const href = button.getAttribute('href');
            if (href === '#' || !href) e.preventDefault();

            const targetId = button.getAttribute('data-target');
            const targetDetail = document.getElementById(targetId);
            if (!targetDetail) return;

            if (targetDetail.classList.contains('hidden')) {
                document.querySelectorAll('.payment-detail-section').forEach(detail => detail.classList.add('hidden'));
                document.querySelectorAll('.toggle-payment-detail').forEach(btn => {
                    btn.innerHTML = '<i class="fas fa-wallet mr-2"></i> Thanh toán';
                });
                targetDetail.classList.remove('hidden');
                button.innerHTML = '<i class="fas fa-times mr-2"></i> Đóng';
            } else {
                targetDetail.classList.add('hidden');
                button.innerHTML = '<i class="fas fa-wallet mr-2"></i> Thanh toán';
            }
        });
    });

    // ========== 5. LIÊN HỆ ==========
    const faqQuestions = document.querySelectorAll('.faq-question');
    faqQuestions.forEach(question => {
        question.addEventListener('click', (e) => {
            const href = question.getAttribute('href');
            if (href === '#' || !href) e.preventDefault();

            const faqItem = question.closest('.faq-item');
            const answer = faqItem.querySelector('.faq-answer');
            const icon = question.querySelector('.fa-plus, .fa-minus');
            const isExpanded = answer.classList.contains('hidden');
            if (isExpanded) {
                document.querySelectorAll('.faq-answer').forEach(ans => ans.classList.add('hidden'));
                document.querySelectorAll('.faq-question .fa-minus').forEach(ico => {
                    ico.classList.remove('fa-minus', 'rotate-45');
                    ico.classList.add('fa-plus');
                });
                answer.classList.remove('hidden');
                icon.classList.remove('fa-plus');
                icon.classList.add('fa-minus', 'rotate-45');
            } else {
                answer.classList.add('hidden');
                icon.classList.remove('fa-minus', 'rotate-45');
                icon.classList.add('fa-plus');
            }
        });
    });
        // ========== 6. HIGHLIGHT MENU (SIDEBAR / NAVBAR) ==========
    // ========== 6. HIGHLIGHT MENU (SIDEBAR / NAVBAR) ==========
const navLinks = document.querySelectorAll('.nav-link');

// Khi click — highlight tạm thời (client-side)
navLinks.forEach(link => {
    link.addEventListener('click', (e) => {
        const href = link.getAttribute('href');
        // Nếu là link thật (chuyển trang), cho phép hành động mặc định
        if (href && href !== '#') return;

        // Nếu là tab nội bộ, chặn reload
        e.preventDefault();

        // Bỏ highlight cũ
        navLinks.forEach(l => l.classList.remove('active', 'bg-blue-600', 'text-white', 'shadow-lg'));
        // Thêm highlight mới
        link.classList.add('active', 'bg-blue-600', 'text-white', 'shadow-lg');
    });
});

// Khi tải lại trang => tự động highlight theo URL
const currentUrl = window.location.pathname; // ví dụ: /users, /users/create
navLinks.forEach(link => {
    const href = link.getAttribute('href');

    // Chỉ xét link thật (không phải #)
    if (!href || href === '#') return;

    // Lấy path gốc của href, ví dụ: /users
    const linkPath = new URL(href, window.location.origin).pathname;

    // Nếu URL hiện tại bắt đầu bằng path menu => highlight
    if (currentUrl.startsWith(linkPath)) {
        link.classList.add('active', 'bg-blue-600', 'text-white', 'shadow-lg');
    }
});


});
