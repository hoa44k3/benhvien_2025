// document.addEventListener('DOMContentLoaded', () => {
//     const navLinks = document.querySelectorAll('.nav-link');
//     const pageTitle = document.getElementById('pageTitle');
//     const pageSubtitle = document.getElementById('pageSubtitle');

//     // Hàm chuẩn hóa đường dẫn: lấy pathname và loại bỏ dấu '/' cuối (nếu có)
//     const normalizePath = (url) => {
//         try {
//             // Lấy pathname từ URL tuyệt đối, sau đó loại bỏ dấu '/' cuối cùng
//             const path = new URL(url, window.location.origin).pathname;
//             return path.replace(/\/+$/, '');
//         } catch (e) {
//             return ''; 
//         }
//     };

//     // Lấy đường dẫn hiện tại của trình duyệt
//     const currentPath = normalizePath(window.location.href);
//     let isRouteHighlighted = false;

//     // --------------------------------------------------------
//     // 🔹 Logic 1: Khi trang load, tự động highlight menu (ROUTES)
//     // --------------------------------------------------------
//     navLinks.forEach(link => {
//         const href = link.getAttribute('href');
        
//         // Luôn xóa trạng thái active trước khi kiểm tra
//         link.classList.remove('active');

//         // Chỉ xử lý các link là route (không phải tab nội bộ "#")
//         if (href && href !== '#') { 
//             const linkPath = normalizePath(href);

//             // 1. So sánh chính xác (ví dụ: /users == /users)
//             if (currentPath === linkPath) {
//                 link.classList.add('active');
//                 isRouteHighlighted = true;
//             } 
//             // 2. So sánh bao hàm (ví dụ: /users/create bắt đầu bằng /users)
//             // Điều kiện: linkPath phải khác root '/' để tránh highlight tất cả
//             else if (linkPath !== '' && linkPath !== '/' && currentPath.startsWith(linkPath)) {
//                 link.classList.add('active');
//                 isRouteHighlighted = true;
//             }
//         }
//     });

//     // --------------------------------------------------------
//     // 🔹 Logic 2: Khôi phục trạng thái active của tab nội bộ (TABS)
//     // --------------------------------------------------------
//     const savedMenu = localStorage.getItem('activeMenu');
//     // Chỉ khôi phục tab nội bộ nếu không có route nào được highlight
//     if (savedMenu && savedMenu.startsWith('#') && !isRouteHighlighted) {
//         navLinks.forEach(link => {
//             const tabId = link.getAttribute('data-tab');
            
//             if (`#${tabId}` === savedMenu) {
//                 // Đảm bảo chỉ tab này được active
//                 navLinks.forEach(nav => nav.classList.remove('active'));
//                 link.classList.add('active');
                
//                 // Cập nhật tiêu đề khi khôi phục tab nội bộ
//                 const title = link.getAttribute('data-title');
//                 const subtitle = link.getAttribute('data-subtitle');
//                 if (pageTitle) pageTitle.textContent = title;
//                 if (pageSubtitle) pageSubtitle.textContent = subtitle;

//                 // Hiển thị tab content
//                 document.querySelectorAll('.tab-content').forEach(content => content.classList.remove('active'));
//                 document.getElementById(tabId)?.classList.add('active');
//             }
//         });
//     }


//     // --------------------------------------------------------
//     // 🔹 Logic 3: Lắng nghe sự kiện click
//     // --------------------------------------------------------
//     navLinks.forEach(link => {
//         link.addEventListener('click', (event) => {
//             const href = link.getAttribute('href');
//             const tabId = link.getAttribute('data-tab');

//             // ✅ Nếu là route thật (href != "#") => cho phép điều hướng
//             if (href && href !== '#') {
//                 // RẤT QUAN TRỌNG: Xóa activeMenu để không gây xung đột khi trang mới load
//                 localStorage.removeItem('activeMenu'); 
//                 return; // Cho phép trình duyệt điều hướng tự nhiên
//             }

//             // ⚠️ Nếu là tab nội bộ (href="#") => xử lý bằng JS (Logic cũ)
//             event.preventDefault();

//             const title = link.getAttribute('data-title');
//             const subtitle = link.getAttribute('data-subtitle');

//             // 1. Ẩn tất cả tab nội bộ
//             document.querySelectorAll('.tab-content').forEach(content => {
//                 content.classList.remove('active');
//             });

//             // 2. Hiển thị tab được chọn
//             document.getElementById(tabId)?.classList.add('active');

//             // 3. Cập nhật trạng thái active menu
//             navLinks.forEach(nav => nav.classList.remove('active'));
//             link.classList.add('active');

//             // 4. Cập nhật tiêu đề
//             if (pageTitle) pageTitle.textContent = title;
//             if (pageSubtitle) pageSubtitle.textContent = subtitle;

//             // Ghi nhớ tab nội bộ hiện tại
//             localStorage.setItem('activeMenu', `#${tabId}`);
//         });
//     });
   
//     document.addEventListener('DOMContentLoaded', function() {
//         // Tìm nút mở modal
//         const openModalBtn = document.querySelector('[data-bs-target="#addTestModal"]');
//         const modalElement = document.getElementById('addTestModal');
        
//         if(openModalBtn && modalElement) {
//             openModalBtn.addEventListener('click', function() {
//                 var myModal = new bootstrap.Modal(modalElement);
//                 myModal.show();
//             });
//         }
//     });

// });
document.addEventListener('DOMContentLoaded', () => {
    const navLinks = document.querySelectorAll('.nav-link');
    const pageTitle = document.getElementById('pageTitle');
    const pageSubtitle = document.getElementById('pageSubtitle');

    // Hàm chuẩn hóa đường dẫn
    const normalizePath = (url) => {
        try {
            const path = new URL(url, window.location.origin).pathname;
            return path.replace(/\/+$/, '');
        } catch (e) {
            return ''; 
        }
    };

    const currentPath = normalizePath(window.location.href);
    let isRouteHighlighted = false;

    // --- LOGIC 1 & 2: Highlight Menu & Khôi phục Tab ---
    navLinks.forEach(link => {
        const href = link.getAttribute('href');
        link.classList.remove('active');

        if (href && href !== '#') { 
            const linkPath = normalizePath(href);
            if (currentPath === linkPath) {
                link.classList.add('active');
                isRouteHighlighted = true;
            } else if (linkPath !== '' && linkPath !== '/' && currentPath.startsWith(linkPath)) {
                link.classList.add('active');
                isRouteHighlighted = true;
            }
        }
    });

    const savedMenu = localStorage.getItem('activeMenu');
    if (savedMenu && savedMenu.startsWith('#') && !isRouteHighlighted) {
        navLinks.forEach(link => {
            const tabId = link.getAttribute('data-tab');
            if (`#${tabId}` === savedMenu) {
                navLinks.forEach(nav => nav.classList.remove('active'));
                link.classList.add('active');
                
                const title = link.getAttribute('data-title');
                const subtitle = link.getAttribute('data-subtitle');
                if (pageTitle) pageTitle.textContent = title;
                if (pageSubtitle) pageSubtitle.textContent = subtitle;

                document.querySelectorAll('.tab-content').forEach(content => content.classList.remove('active'));
                const targetTab = document.getElementById(tabId);
                if(targetTab) targetTab.classList.add('active');
            }
        });
    }

    // --- LOGIC 3: Click Event ---
    navLinks.forEach(link => {
        link.addEventListener('click', (event) => {
            const href = link.getAttribute('href');
            const tabId = link.getAttribute('data-tab');

            if (href && href !== '#') {
                localStorage.removeItem('activeMenu'); 
                return;
            }

            event.preventDefault();

            const title = link.getAttribute('data-title');
            const subtitle = link.getAttribute('data-subtitle');

            document.querySelectorAll('.tab-content').forEach(content => content.classList.remove('active'));
            const targetTab = document.getElementById(tabId);
            if(targetTab) targetTab.classList.add('active');

            navLinks.forEach(nav => nav.classList.remove('active'));
            link.classList.add('active');

            if (pageTitle) pageTitle.textContent = title;
            if (pageSubtitle) pageSubtitle.textContent = subtitle;

            localStorage.setItem('activeMenu', `#${tabId}`);
        });
    });

    // --- LOGIC MODAL (Đã sửa lỗi an toàn) ---
    // Kiểm tra xem Bootstrap có tồn tại không trước khi dùng
    if (typeof bootstrap !== 'undefined') {
        const openModalBtn = document.querySelector('[data-bs-target="#addTestModal"]');
        const modalElement = document.getElementById('addTestModal');
        
        if(openModalBtn && modalElement) {
            openModalBtn.addEventListener('click', function() {
                var myModal = new bootstrap.Modal(modalElement);
                myModal.show();
            });
        }
    }
});