<!-- jQuery -->
<script src="https://code.jquery.com/jquery-3.7.1.min.js" integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>

<!-- Bootstrap 5 JS Bundle -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>

<!-- DataTables JS -->
<script src="https://cdn.datatables.net/1.13.8/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.8/js/dataTables.bootstrap5.min.js"></script>

<!-- Select2 JS -->
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0/dist/js/select2.min.js"></script>

<!-- JsBarcode -->
<script src="https://cdn.jsdelivr.net/npm/jsbarcode@3.11.6/dist/JsBarcode.all.min.js"></script>

<!-- SweetAlert2 -->
<script src="{{ asset('assets/vendors/js/sweetalert2.min.js') }}"></script>

<!-- Custom Admin JS -->
<script>
    // Sidebar toggle functionality
    document.addEventListener('DOMContentLoaded', function() {
        const sidebarToggle = document.getElementById('sidebar-toggle');
        const sidebar = document.getElementById('sidebar');
        const mainHeader = document.getElementById('main-header');
        const mainContent = document.getElementById('main-content');
        const sidebarOverlay = document.getElementById('sidebar-overlay');
        
        // Desktop sidebar toggle (mini mode)
        if (sidebarToggle) {
            sidebarToggle.addEventListener('click', function(e) {
                e.preventDefault();
                e.stopPropagation();
                
                // Toggle mini class instead of collapsed
                sidebar.classList.toggle('mini');
                mainHeader.classList.toggle('sidebar-mini');
                mainContent.classList.toggle('sidebar-mini');
                
                // Save state to localStorage
                const isMini = sidebar.classList.contains('mini');
                localStorage.setItem('sidebarMini', isMini);
            });
        }

        // Mobile sidebar toggle
        const mobileSidebarToggle = document.getElementById('mobile-sidebar-toggle');
        if (mobileSidebarToggle) {
            mobileSidebarToggle.addEventListener('click', function(e) {
                e.preventDefault();
                e.stopPropagation();
                sidebar.classList.toggle('show');
                if (sidebarOverlay) {
                    sidebarOverlay.classList.toggle('show');
                }
            });
        }

        // Close sidebar on overlay click
        if (sidebarOverlay) {
            sidebarOverlay.addEventListener('click', function() {
                sidebar.classList.remove('show');
                sidebarOverlay.classList.remove('show');
            });
        }

        // Restore sidebar state from localStorage
        const savedState = localStorage.getItem('sidebarMini');
        if (savedState === 'true') {
            sidebar.classList.add('mini');
            mainHeader.classList.add('sidebar-mini');
            mainContent.classList.add('sidebar-mini');
        }

        // Function to save open menus to localStorage
        function saveOpenMenus() {
            const openMenus = [];
            document.querySelectorAll('.menu-item.has-submenu.open').forEach(function(item) {
                const menuLink = item.querySelector('.menu-link');
                const menuText = menuLink ? menuLink.querySelector('.menu-text')?.textContent.trim() : null;
                if (menuText) {
                    openMenus.push(menuText);
                }
            });
            localStorage.setItem('openMenus', JSON.stringify(openMenus));
        }

        // Function to restore open menus from localStorage
        function restoreOpenMenus() {
            const savedMenus = localStorage.getItem('openMenus');
            if (savedMenus) {
                try {
                    const openMenus = JSON.parse(savedMenus);
                    document.querySelectorAll('.menu-item.has-submenu').forEach(function(item) {
                        const menuLink = item.querySelector('.menu-link');
                        const menuText = menuLink ? menuLink.querySelector('.menu-text')?.textContent.trim() : null;
                        if (menuText && openMenus.includes(menuText)) {
                            item.classList.add('open');
                        }
                    });
                } catch (e) {
                    console.error('Error restoring menu state:', e);
                }
            }
        }

        // Auto-open menus with active children
        function autoOpenActiveMenus() {
            document.querySelectorAll('.menu-item.has-submenu').forEach(function(item) {
                const hasActiveChild = item.querySelector('.menu-link.active');
                if (hasActiveChild) {
                    item.classList.add('open');
                    // Also open parent menus if nested
                    let parent = item.parentElement.closest('.menu-item.has-submenu');
                    while (parent) {
                        parent.classList.add('open');
                        parent = parent.parentElement.closest('.menu-item.has-submenu');
                    }
                }
            });
        }

        // Menu submenu toggle - Simple and working solution
        function handleMenuClick(e) {
            const menuLink = e.currentTarget;
            const menuItem = menuLink.closest('.menu-item.has-submenu');
            const isMini = sidebar && sidebar.classList.contains('mini');
            
            // Only handle if sidebar is not in mini mode
            if (!isMini && menuItem) {
                e.preventDefault();
                e.stopPropagation();
                
                // Close siblings at the same level
                const parent = menuItem.parentElement;
                if (parent) {
                    const siblings = parent.querySelectorAll(':scope > .menu-item.has-submenu');
                    siblings.forEach(function(sibling) {
                        if (sibling !== menuItem) {
                            sibling.classList.remove('open');
                        }
                    });
                }
                
                // Toggle current menu
                menuItem.classList.toggle('open');
                
                // Save state
                saveOpenMenus();
            }
        }

        // Initialize all menu toggles
        function initMenuToggle() {
            // Remove old listeners
            document.querySelectorAll('.menu-item.has-submenu > .menu-link').forEach(function(link) {
                const newLink = link.cloneNode(true);
                link.parentNode.replaceChild(newLink, link);
            });
            
            // Add new listeners
            document.querySelectorAll('.menu-item.has-submenu > .menu-link').forEach(function(menuLink) {
                menuLink.addEventListener('click', handleMenuClick);
            });
        }

        // Handle nested submenus - also need click handlers
        function initNestedMenuToggle() {
            document.querySelectorAll('.submenu .menu-item.has-submenu > .menu-link').forEach(function(menuLink) {
                menuLink.addEventListener('click', function(e) {
                    const menuItem = this.closest('.menu-item.has-submenu');
                    const isMini = sidebar && sidebar.classList.contains('mini');
                    
                    if (!isMini && menuItem) {
                        e.preventDefault();
                        e.stopPropagation();
                        
                        // Close siblings at the same level
                        const parent = menuItem.parentElement;
                        if (parent) {
                            const siblings = parent.querySelectorAll(':scope > .menu-item.has-submenu');
                            siblings.forEach(function(sibling) {
                                if (sibling !== menuItem) {
                                    sibling.classList.remove('open');
                                }
                            });
                        }
                        
                        // Toggle current menu
                        menuItem.classList.toggle('open');
                        
                        // Save state
                        saveOpenMenus();
                    }
                });
            });
        }

        // Initialize menu toggles
        initMenuToggle();
        initNestedMenuToggle();

        // Auto-open menus with active children first (server-side active state)
        autoOpenActiveMenus();
        
        // Then restore saved state (user's preference)
        restoreOpenMenus();

        // Close menus when clicking outside
        document.addEventListener('click', function(e) {
            if (!sidebar.classList.contains('mini') && !e.target.closest('.sidebar')) {
                document.querySelectorAll('.menu-item.has-submenu.open').forEach(function(item) {
                    // Don't close if it has an active child
                    if (!item.querySelector('.menu-link.active')) {
                        item.classList.remove('open');
                    }
                });
                saveOpenMenus();
            }
        });

        // Initialize DataTables if present
        if (typeof $.fn.DataTable !== 'undefined') {
            $('.data-table').DataTable({
                responsive: true,
                pageLength: 25,
                order: [[0, 'desc']]
            });
        }

        // Initialize Select2 if present
        if (typeof $.fn.select2 !== 'undefined') {
            $('.select2').select2({
                theme: 'bootstrap-5'
            });
        }
    });
</script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Use event delegation to handle multiple cards
        // Remove Card Functionality - works with multiple cards
        document.addEventListener('click', function(e) {
            const removeBtn = e.target.closest('.btn-card-remove');
            if (removeBtn) {
                const card = removeBtn.closest('.card');
                if (card) {
                    card.style.transition = 'opacity 0.3s ease, transform 0.3s ease';
                    card.style.opacity = '0';
                    card.style.transform = 'scale(0.95)';
                    
                    setTimeout(function() {
                        card.style.display = 'none';
                    }, 300);
                }
            }
        });

        // Refresh Card Functionality - works with multiple cards
        document.addEventListener('click', function(e) {
            const refreshBtn = e.target.closest('.btn-card-refresh');
            if (refreshBtn) {
                const icon = refreshBtn.querySelector('i');
                if (icon) {
                    icon.style.animation = 'spin 0.6s linear';
                }
                
                // Reload the page after a short delay
                setTimeout(function() {
                    window.location.reload();
                }, 600);
            }
        });

        // Fullscreen Card Functionality - works with multiple cards
        document.addEventListener('click', function(e) {
            const fullscreenBtn = e.target.closest('.btn-card-fullscreen');
            if (fullscreenBtn) {
                const card = fullscreenBtn.closest('.card');
                if (!card) return;
                
                const isFullscreen = card.classList.contains('card-fullscreen');
                
                if (!isFullscreen) {
                    // Enter fullscreen
                    card.style.position = 'fixed';
                    card.style.top = '0';
                    card.style.left = '0';
                    card.style.width = '100vw';
                    card.style.height = '100vh';
                    card.style.zIndex = '9999';
                    card.style.margin = '0';
                    card.style.borderRadius = '0';
                    card.style.maxHeight = '100vh';
                    card.style.overflow = 'auto';
                    card.classList.add('card-fullscreen');
                    
                    const icon = fullscreenBtn.querySelector('i');
                    if (icon) {
                        icon.classList.remove('bi-arrows-fullscreen');
                        icon.classList.add('bi-fullscreen-exit');
                    }
                    fullscreenBtn.setAttribute('data-bs-original-title', 'Exit Fullscreen');
                    fullscreenBtn.setAttribute('title', 'Exit Fullscreen');
                    
                    // Update tooltip
                    const tooltip = bootstrap.Tooltip.getInstance(fullscreenBtn);
                    if (tooltip) {
                        tooltip.dispose();
                        new bootstrap.Tooltip(fullscreenBtn);
                    }
                    
                    // Save state with card identifier
                    const cardId = card.id || 'card-' + Math.random().toString(36).substr(2, 9);
                    if (!card.id) card.id = cardId;
                    localStorage.setItem('cardFullscreen-' + cardId, 'true');
                } else {
                    // Exit fullscreen
                    card.style.position = '';
                    card.style.top = '';
                    card.style.left = '';
                    card.style.width = '';
                    card.style.height = '';
                    card.style.zIndex = '';
                    card.style.margin = '';
                    card.style.borderRadius = '';
                    card.style.maxHeight = '';
                    card.style.overflow = '';
                    card.classList.remove('card-fullscreen');
                    
                    const icon = fullscreenBtn.querySelector('i');
                    if (icon) {
                        icon.classList.remove('bi-fullscreen-exit');
                        icon.classList.add('bi-arrows-fullscreen');
                    }
                    fullscreenBtn.setAttribute('data-bs-original-title', 'Maximize');
                    fullscreenBtn.setAttribute('title', 'Maximize');
                    
                    // Update tooltip
                    const tooltip = bootstrap.Tooltip.getInstance(fullscreenBtn);
                    if (tooltip) {
                        tooltip.dispose();
                        new bootstrap.Tooltip(fullscreenBtn);
                    }
                    
                    // Remove state
                    const cardId = card.id;
                    if (cardId) {
                        localStorage.removeItem('cardFullscreen-' + cardId);
                    }
                }
            }
        });

        // Restore fullscreen state for all cards on page load
        document.querySelectorAll('.card').forEach(function(card) {
            const cardId = card.id || 'card-' + Math.random().toString(36).substr(2, 9);
            if (!card.id) card.id = cardId;
            
            const savedFullscreenState = localStorage.getItem('cardFullscreen-' + cardId);
            if (savedFullscreenState === 'true') {
                const fullscreenBtn = card.querySelector('.btn-card-fullscreen');
                if (fullscreenBtn) {
                    card.style.position = 'fixed';
                    card.style.top = '0';
                    card.style.left = '0';
                    card.style.width = '100vw';
                    card.style.height = '100vh';
                    card.style.zIndex = '9999';
                    card.style.margin = '0';
                    card.style.borderRadius = '0';
                    card.style.maxHeight = '100vh';
                    card.style.overflow = 'auto';
                    card.classList.add('card-fullscreen');
                    
                    const icon = fullscreenBtn.querySelector('i');
                    if (icon) {
                        icon.classList.remove('bi-arrows-fullscreen');
                        icon.classList.add('bi-fullscreen-exit');
                    }
                    fullscreenBtn.setAttribute('data-bs-original-title', 'Exit Fullscreen');
                    fullscreenBtn.setAttribute('title', 'Exit Fullscreen');
                }
            }
        });

        // Initialize Bootstrap tooltips
        const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
        tooltipTriggerList.map(function (tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl);
        });
    });

    // CSS Animation for refresh icon
    const style = document.createElement('style');
    style.textContent = `
        @keyframes spin {
            from { transform: rotate(0deg); }
            to { transform: rotate(360deg); }
        }
    `;
    document.head.appendChild(style);
</script>
