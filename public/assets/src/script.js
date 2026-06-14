/**
 * Interactivity and sidebar management
 */
document.addEventListener("DOMContentLoaded", () => {
    const navLinks = document.querySelectorAll("#sidebarMenu .nav-link");
    
    // Set active class properly on click
    navLinks.forEach(link => {
        link.addEventListener("click", function(e) {
            // Do not handle active state or sidebar hide for submenu toggles
            if(this.hasAttribute("data-bs-toggle")) {
                return;
            }

            // Remove active class from all nav items
            navLinks.forEach(l => l.classList.remove("active"));
            // Add active class to the clicked item
            this.classList.add("active");
            
            // If the view is narrow (mobile) and sidebar is visible as an offcanvas menu, hide it after a click.
            if (window.innerWidth < 768) {
                const sidebar = document.getElementById("sidebarMenu");
                if (sidebar && sidebar.classList.contains("show") && window.bootstrap) {
                    const offcanvasInstance = window.bootstrap.Offcanvas.getInstance(sidebar) || new window.bootstrap.Offcanvas(sidebar);
                    offcanvasInstance.hide();
                }
            }
        });
    });

    // Desktop Sidebar Toggle
    const desktopToggle = document.getElementById("desktopSidebarToggle");
    if(desktopToggle) {
        desktopToggle.addEventListener("click", function(e) {
            e.preventDefault();
            document.body.classList.toggle("sidebar-compact");
        });
    }

    // Logo click to expand sidebar
    const brandLogoLink = document.getElementById("brandLogoLink");
    if(brandLogoLink) {
        brandLogoLink.addEventListener("click", function(e) {
            e.preventDefault();
            if (document.body.classList.contains("sidebar-compact")) {
                document.body.classList.remove("sidebar-compact");
            }
        });
    }

    // Theme Toggle Logic
    const themeToggleBtn = document.getElementById("themeToggleBtn");
    const themeIcon = document.getElementById("themeIcon");
    const mainFrame = document.getElementById("main-frame");

    function updateTheme(newTheme) {
        document.documentElement.setAttribute("data-bs-theme", newTheme);
        
        if (newTheme === "dark") {
            if(themeIcon) {
                themeIcon.classList.remove("bi-sun-fill", "text-warning");
                themeIcon.classList.add("bi-moon", "text-light");
            }
        } else {
            if(themeIcon) {
                // Better icon for light mode
                themeIcon.classList.remove("bi-moon", "text-light");
                themeIcon.classList.add("bi-sun-fill", "text-warning");
            }
        }
        
        // Propagate theme to iframe if loaded
        if (mainFrame && mainFrame.contentDocument) {
            const iframeHtml = mainFrame.contentDocument.documentElement;
            if (iframeHtml) {
                iframeHtml.setAttribute("data-bs-theme", newTheme);
            }
        }
    }

    if(themeToggleBtn) {
        themeToggleBtn.addEventListener("click", function(e) {
            e.preventDefault();
            const currentTheme = document.documentElement.getAttribute("data-bs-theme") || "dark";
            const newTheme = currentTheme === "dark" ? "light" : "dark";
            updateTheme(newTheme);
        });
    }

    // Iframe Loading Progress Bar Logic
    const iframeLoadingBar = document.getElementById("iframeLoadingBar");
    const sidebarLinks = document.querySelectorAll('.sidebar-nav a[target="main-frame"]');

    // When iframe loads, apply the current theme so it doesn't reset and hide loaders
    if (mainFrame) {
        if (iframeLoadingBar) {
            sidebarLinks.forEach(link => {
                link.addEventListener("click", () => {
                    // Only show loader if we click a real link, and it differs from current
                    if (link.getAttribute("href") && link.getAttribute("href") !== "#") {
                        iframeLoadingBar.style.display = "flex";
                    }
                });
            });
        }

        mainFrame.addEventListener("load", function() {
            if (iframeLoadingBar) {
                iframeLoadingBar.style.display = "none";
            }
            const currentTheme = document.documentElement.getAttribute("data-bs-theme") || "dark";
            if (this.contentDocument) {
                const iframeHtml = this.contentDocument.documentElement;
                if (iframeHtml) {
                    iframeHtml.setAttribute("data-bs-theme", currentTheme);
                }
            }
        });
    }

    // Notifications Mark All as Read Logic
    const markAllReadBtn = document.getElementById("markAllReadBtn");
    const notificationBadgeMain = document.getElementById("notificationBadgeMain");
    const notificationBadgeHeader = document.getElementById("notificationBadgeHeader");

    if (markAllReadBtn) {
        markAllReadBtn.addEventListener("click", function(e) {
            e.preventDefault();
            e.stopPropagation(); // Keep dropdown open
            
            if (notificationBadgeMain) {
                notificationBadgeMain.style.display = "none";
            }
            if (notificationBadgeHeader) {
                notificationBadgeHeader.style.display = "none";
            }
            // Optional: change appearance of read items
            const unreadItems = document.querySelectorAll(".notification-menu .dropdown-item");
            unreadItems.forEach(item => {
                item.style.opacity = "0.7";
            });
        });
    }

    // Global Search Logic
    const searchInput = document.getElementById("globalSearchInput");
    const searchResultsDropdown = document.getElementById("searchResultsDropdown");
    const searchResultsList = document.getElementById("searchResultsList");
    
    // We collect all menu items into a searchable array
    const searchableItems = Array.from(sidebarLinks).map(link => {
        const iconSpan = link.querySelector("i");
        return {
            title: link.innerText.trim(),
            url: link.getAttribute("href"),
            iconClass: iconSpan ? iconSpan.className : "bi bi-search"
        };
    });

    if (searchInput && searchResultsDropdown && searchResultsList) {
        searchInput.addEventListener("input", function(e) {
            const query = e.target.value.trim().toLowerCase();
            if (query.length > 0) {
                const results = searchableItems.filter(item => item.title.toLowerCase().includes(query));
                
                searchResultsList.innerHTML = "";
                if (results.length > 0) {
                    results.forEach(item => {
                        const a = document.createElement("a");
                        a.className = "list-group-item list-group-item-action d-flex align-items-center gap-3 text-light bg-dark border-secondary";
                        a.href = item.url;
                        a.target = "main-frame";
                        a.innerHTML = `<i class="${item.iconClass}"></i> <span>${item.title}</span>`;
                        
                        a.addEventListener("click", () => {
                            searchInput.value = "";
                            searchResultsDropdown.style.display = "none";
                            // Optionally trigger loader
                            if (iframeLoadingBar) iframeLoadingBar.style.display = "flex";
                        });
                        
                        searchResultsList.appendChild(a);
                    });
                } else {
                    const div = document.createElement("div");
                    div.className = "list-group-item bg-dark text-muted border-secondary text-center py-3";
                    div.innerText = "نتیجه‌ای یافت نشد.";
                    searchResultsList.appendChild(div);
                }
                
                searchResultsDropdown.style.display = "block";
            } else {
                searchResultsDropdown.style.display = "none";
            }
        });

        // Hide results when clicking outside
        document.addEventListener("click", function(e) {
            if (!searchInput.contains(e.target) && !searchResultsDropdown.contains(e.target)) {
                searchResultsDropdown.style.display = "none";
            }
        });
        
        // Show results if input is focused and has text
        searchInput.addEventListener("focus", function() {
            if (this.value.trim().length > 0) {
                searchResultsDropdown.style.display = "block";
            }
        });
    }
});
