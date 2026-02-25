/**
 * Advanced Dashboard Search System
 * Provides real-time search across all system data with comprehensive reporting
 */
class DashboardSearch {
    constructor() {
        this.searchInput = document.getElementById('advancedSearch');
        this.searchBtn = document.getElementById('searchBtn');
        this.searchResults = document.getElementById('searchResults');
        this.searchLoading = document.getElementById('searchLoading');
        this.searchContent = document.getElementById('searchContent');
        this.searchStats = document.getElementById('searchStats');
        this.searchCategories = document.getElementById('searchCategories');
        this.noResults = document.getElementById('noResults');

        this.currentQuery = '';
        this.searchTimeout = null;

        this.init();
    }

    init() {
        if (!this.searchInput) return;

        this.bindEvents();
        this.setupKeyboardShortcuts();
    }

    bindEvents() {
        // Search input events
        this.searchInput.addEventListener('input', (e) => {
            this.handleSearchInput(e.target.value);
        });

        this.searchInput.addEventListener('focus', () => {
            if (this.searchInput.value.length >= 2) {
                this.searchResults.style.display = 'block';
            }
        });

        // Search button click
        this.searchBtn.addEventListener('click', () => {
            this.performSearch(this.searchInput.value);
        });

        // Hide results when clicking outside
        document.addEventListener('click', (e) => {
            if (!this.isSearchElement(e.target)) {
                this.hideResults();
            }
        });

        // Enter key to search
        this.searchInput.addEventListener('keydown', (e) => {
            if (e.key === 'Enter') {
                e.preventDefault();
                this.performSearch(this.searchInput.value);
            }
            if (e.key === 'Escape') {
                this.hideResults();
            }
        });
    }

    setupKeyboardShortcuts() {
        document.addEventListener('keydown', (e) => {
            if (e.ctrlKey && e.key === 'k') {
                e.preventDefault();
                this.searchInput.focus();
            }
        });
    }

    handleSearchInput(query) {
        clearTimeout(this.searchTimeout);

        if (query.length >= 2) {
            this.searchTimeout = setTimeout(() => {
                this.performSearch(query);
            }, 500);
        } else {
            this.hideResults();
        }
    }

    async performSearch(query) {
        if (query.length < 2) {
            this.hideResults();
            return;
        }

        if (query === this.currentQuery) {
            return; // Avoid duplicate searches
        }

        this.currentQuery = query;
        this.showLoading();

        try {
            const response = await fetch(`/admin/search?q=${encodeURIComponent(query)}`, {
                method: 'GET',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || ''
                }
            });

            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }

            const data = await response.json();
            this.hideLoading();

            if (data.success && data.data) {
                this.displayResults(data.data);
            } else {
                this.showNoResults(data.message || 'No results found');
            }
        } catch (error) {
            console.error('Search error:', error);
            this.hideLoading();
            this.showNoResults('An error occurred while searching. Please try again.');
        }
    }

    showLoading() {
        this.searchResults.style.display = 'block';
        this.searchLoading.style.display = 'block';
        this.searchContent.style.display = 'none';
        this.noResults.style.display = 'none';
    }

    hideLoading() {
        this.searchLoading.style.display = 'none';
    }

    displayResults(data) {
        const hasResults = this.hasAnyResults(data);

        if (!hasResults) {
            this.showNoResults();
            return;
        }

        this.displayStatistics(data.statistics);
        this.displayCategories(data);
        this.searchContent.style.display = 'block';
    }

    hasAnyResults(data) {
        return Object.entries(data).some(([key, value]) => {
            if (key === 'statistics') return false;
            return Array.isArray(value) && value.length > 0;
        });
    }

    displayStatistics(stats) {
        const statsHtml = `
            <div class="row text-center">
                <div class="col-md-2 col-6 mb-2">
                    <div class="stats-item">
                        <h4 class="mb-0">${this.formatNumber(stats.total_events_found)}</h4>
                        <small>Events</small>
                    </div>
                </div>
                <div class="col-md-2 col-6 mb-2">
                    <div class="stats-item">
                        <h4 class="mb-0">${this.formatNumber(stats.total_users_found)}</h4>
                        <small>Users</small>
                    </div>
                </div>
                <div class="col-md-2 col-6 mb-2">
                    <div class="stats-item">
                        <h4 class="mb-0">${this.formatNumber(stats.total_companies_found)}</h4>
                        <small>Companies</small>
                    </div>
                </div>
                <div class="col-md-2 col-6 mb-2">
                    <div class="stats-item">
                        <h4 class="mb-0">${this.formatNumber(stats.total_orders_found)}</h4>
                        <small>Orders</small>
                    </div>
                </div>
                <div class="col-md-2 col-6 mb-2">
                    <div class="stats-item">
                        <h4 class="mb-0">$${this.formatNumber(stats.total_revenue_from_search)}</h4>
                        <small>Total Revenue</small>
                    </div>
                </div>
                <div class="col-md-2 col-6 mb-2">
                    <div class="stats-item">
                        <h6 class="mb-0">${this.formatTime(stats.search_time)}</h6>
                        <small>Search Time</small>
                    </div>
                </div>
            </div>
            <div class="text-center mt-2">
                <small class="text-white-50">
                    <i class="ti ti-search me-1"></i>
                    Search results for: "${stats.search_query}"
                </small>
            </div>
        `;

        this.searchStats.innerHTML = statsHtml;
    }

    displayCategories(data) {
        const categories = [
            { key: 'events', title: 'Events', icon: 'calendar' },
            { key: 'users', title: 'Users', icon: 'users' },
            { key: 'companies', title: 'Companies', icon: 'building' },
            { key: 'orders', title: 'Orders', icon: 'shopping-cart' },
            { key: 'categories', title: 'Categories', icon: 'tag' },
            { key: 'cities', title: 'Cities', icon: 'map-pin' }
        ];

        let categoriesHtml = '';

        categories.forEach(category => {
            if (data[category.key] && data[category.key].length > 0) {
                categoriesHtml += this.createCategorySection(
                    category.title,
                    data[category.key],
                    category.key,
                    category.icon
                );
            }
        });

        this.searchCategories.innerHTML = categoriesHtml;
    }

    createCategorySection(title, items, type, icon) {
        const itemsHtml = items.map(item => this.createItemHtml(item, type)).join('');

        return `
            <div class="search-category">
                <h6 class="category-header">
                    <i class="ti ti-${icon} me-2"></i>
                    ${title} (${items.length})
                </h6>
                <div class="search-items">
                    ${itemsHtml}
                </div>
            </div>
        `;
    }

    createItemHtml(item, type) {
        const templates = {
            events: this.createEventItem(item),
            users: this.createUserItem(item),
            companies: this.createCompanyItem(item),
            orders: this.createOrderItem(item),
            categories: this.createCategoryItem(item),
            cities: this.createCityItem(item)
        };

        return templates[type] || '';
    }

    createEventItem(item) {
        return `
            <div class="search-item" onclick="window.open('${item.url}', '_blank')">
                <div class="d-flex justify-content-between align-items-start">
                    <div class="flex-grow-1">
                        <h6 class="mb-1 text-primary">${this.highlightText(item.name)}</h6>
                        <div class="text-muted small">
                            <i class="ti ti-building me-1"></i>${item.company} •
                            <i class="ti ti-tag me-1"></i>${item.category} •
                            <i class="ti ti-map-pin me-1"></i>${item.city}
                        </div>
                        <div class="mt-2">
                            <span class="badge bg-label-${item.status === 'Active' ? 'success' : 'secondary'} me-1">
                                ${item.status}
                            </span>
                            <span class="badge bg-label-info me-1">
                                ${item.sold_tickets} tickets sold
                            </span>
                            <span class="badge bg-label-primary">
                                ${item.views} views
                            </span>
                        </div>
                    </div>
                    <div class="text-end">
                        <h6 class="mb-0 text-success">$${this.formatNumber(item.revenue)}</h6>
                        <small class="text-muted">${item.created_at}</small>
                    </div>
                </div>
            </div>
        `;
    }

    createUserItem(item) {
        return `
            <div class="search-item" onclick="window.open('${item.url}', '_blank')">
                <div class="d-flex justify-content-between align-items-start">
                    <div class="flex-grow-1">
                        <h6 class="mb-1 text-primary">${this.highlightText(item.name)}</h6>
                        <div class="text-muted small">
                            <i class="ti ti-mail me-1"></i>${item.email}
                        </div>
                        <div class="text-muted small">
                            <i class="ti ti-map-pin me-1"></i>${item.city}, ${item.country}
                        </div>
                    </div>
                    <div class="text-end">
                        <div class="small">
                            <span class="badge bg-label-info">${item.total_orders} orders</span>
                        </div>
                        <h6 class="mb-0 text-success">$${this.formatNumber(item.total_spent)}</h6>
                        <small class="text-muted">Joined ${item.joined_at}</small>
                    </div>
                </div>
            </div>
        `;
    }

    createCompanyItem(item) {
        return `
            <div class="search-item" onclick="window.open('${item.url}', '_blank')">
                <div class="d-flex justify-content-between align-items-start">
                    <div class="flex-grow-1">
                        <h6 class="mb-1 text-primary">${this.highlightText(item.name)}</h6>
                        <div class="text-muted small">
                            <i class="ti ti-mail me-1"></i>${item.email}
                        </div>
                        <div class="text-muted small">
                            <i class="ti ti-map-pin me-1"></i>${item.city}, ${item.country}
                        </div>
                        <div class="mt-2">
                            <span class="badge bg-label-primary me-1">${item.total_events} events</span>
                            <span class="badge bg-label-success">${item.active_events} active</span>
                        </div>
                    </div>
                    <div class="text-end">
                        <h6 class="mb-0 text-success">$${this.formatNumber(item.total_revenue)}</h6>
                        <small class="text-muted">Joined ${item.joined_at}</small>
                    </div>
                </div>
            </div>
        `;
    }

    createOrderItem(item) {
        return `
            <div class="search-item" onclick="window.open('${item.url}', '_blank')">
                <div class="d-flex justify-content-between align-items-start">
                    <div class="flex-grow-1">
                        <h6 class="mb-1 text-primary">${this.highlightText(item.order_number)}</h6>
                        <div class="text-muted small">
                            <i class="ti ti-user me-1"></i>${item.user_name} (${item.user_email})
                        </div>
                        <div class="text-muted small">
                            <i class="ti ti-calendar me-1"></i>${item.event_name}
                        </div>
                        <div class="mt-2">
                            <span class="badge bg-label-${this.getStatusColor(item.payment_status)} me-1">
                                ${item.payment_status}
                            </span>
                            <span class="badge bg-label-info">${item.quantity} tickets</span>
                        </div>
                    </div>
                    <div class="text-end">
                        <h6 class="mb-0 text-success">$${this.formatNumber(item.total)}</h6>
                        <small class="text-muted">${item.payment_method}</small>
                        <br>
                        <small class="text-muted">${item.created_at}</small>
                    </div>
                </div>
            </div>
        `;
    }

    createCategoryItem(item) {
        return `
            <div class="search-item" onclick="window.open('${item.url}', '_blank')">
                <div class="d-flex justify-content-between align-items-center">
                    <div class="flex-grow-1">
                        <h6 class="mb-1 text-primary">${this.highlightText(item.name)}</h6>
                        <span class="badge bg-label-info">${item.events_count} events</span>
                    </div>
                    <div class="text-end">
                        <h6 class="mb-0 text-success">$${this.formatNumber(item.total_revenue)}</h6>
                        <small class="text-muted">Total Revenue</small>
                    </div>
                </div>
            </div>
        `;
    }

    createCityItem(item) {
        return `
            <div class="search-item" onclick="window.open('${item.url}', '_blank')">
                <div class="d-flex justify-content-between align-items-center">
                    <div class="flex-grow-1">
                        <h6 class="mb-1 text-primary">${this.highlightText(item.name)}</h6>
                        <div class="text-muted small">
                            <i class="ti ti-map me-1"></i>${item.country}
                        </div>
                        <div class="mt-1">
                            <span class="badge bg-label-primary me-1">${item.events_count} events</span>
                            <span class="badge bg-label-info">${item.users_count} users</span>
                        </div>
                    </div>
                </div>
            </div>
        `;
    }

    showNoResults(message = 'No results found matching your search criteria') {
        this.searchResults.style.display = 'block';
        this.searchContent.style.display = 'none';
        this.noResults.style.display = 'block';
        this.noResults.querySelector('p').textContent = message;
    }

    hideResults() {
        this.searchResults.style.display = 'none';
    }

    isSearchElement(element) {
        return this.searchInput.contains(element) ||
            this.searchBtn.contains(element) ||
            this.searchResults.contains(element);
    }

    highlightText(text) {
        if (!this.currentQuery || this.currentQuery.length < 2) return text;
        const regex = new RegExp(`(${this.escapeRegex(this.currentQuery)})`, 'gi');
        return text.replace(regex, '<mark>$1</mark>');
    }

    escapeRegex(string) {
        return string.replace(/[.*+?^${}()|[\]\\]/g, '\\$&');
    }

    formatNumber(num) {
        return new Intl.NumberFormat().format(num || 0);
    }

    formatTime(timeString) {
        try {
            const date = new Date(timeString);
            return date.toLocaleTimeString('en-US', {
                hour12: false,
                hour: '2-digit',
                minute: '2-digit',
                second: '2-digit'
            });
        } catch (e) {
            return timeString;
        }
    }

    getStatusColor(status) {
        const colors = {
            'paid': 'success',
            'pending': 'warning',
            'failed': 'danger',
            'cancelled': 'secondary',
            'refunded': 'info'
        };
        return colors[status] || 'secondary';
    }
}

// Initialize search when DOM is loaded
document.addEventListener('DOMContentLoaded', function () {
    new DashboardSearch();
});
