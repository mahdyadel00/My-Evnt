<!-- section events-filter -->
<section class="New-card-event-results-section" id="filterSection" style="display: none;">
    <div class="New-card-event-results-container">
        <!-- title -->
        <div class="header">
            <h2 class="title">Search Results</h2>
            <p class="subtitle">
                Events matching your search criteria
            </p>
        </div>
        <!-- Cards Container with Navigation -->
        <div class="New-card-event-cards-container">
            <div class="filter-controls">
                <button id="clearFiltersBtn" class="btn btn-danger" onclick="eventFilter.clearFilters()">
                    <i class="fas fa-times"></i> Clear All Filters & Show All Sections
                </button>
                <span id="resultsCount" class="results-count"></span>
            </div>
            <div class="New-card-event-cards-grid" id="filteredEventsGrid">
                <!-- Dynamic content will be loaded here -->
            </div>
        </div>

        <!-- Loading State -->
        <div id="loadingState" class="loading-state" style="display: none;">
            <div class="text-center py-5">
                <div class="spinner-border text-primary" role="status">
                    <span class="visually-hidden">Loading...</span>
                </div>
                <p class="mt-3">Searching for events...</p>
            </div>
        </div>

        <!-- No Results State -->
        <div id="noResultsState" class="no-results-state" style="display: none;">
            <div class="text-center py-5">
                <div class="no-results-icon">
                    <i class="fas fa-search" style="font-size: 64px; color: #ccc;"></i>
                </div>
                <h3 class="mt-3 text-muted">No results found</h3>
                <p class="text-muted">Try changing search criteria or removing some filters</p>
                <button onclick="eventFilter.clearFilters()" class="btn btn-primary mt-3">
                    Clear Filters
                </button>
            </div>
        </div>

        <!-- Error State -->
        <div id="errorState" class="error-state" style="display: none;">
            <div class="text-center py-5">
                <div class="error-icon">
                    <i class="fas fa-exclamation-triangle" style="font-size: 64px; color: #dc3545;"></i>
                </div>
                    <h3 class="mt-3 text-danger">Search error</h3>
                <p id="errorMessage" class="text-muted">An error occurred while searching</p>
                <button onclick="eventFilter.refreshFilters()" class="btn btn-primary mt-3">
                    Try again
                </button>
            </div>
        </div>
    </div>
</section>

<style>
.filter-controls {
    display: flex;
    justify-content: center;
    align-items: center;
    gap: 20px;
    margin-bottom: 20px;
}

.filter-info {
    margin: 20px 0;
}

.filter-info .alert {
    border-radius: 10px;
    border: none;
    box-shadow: 0 2px 10px rgba(0,0,0,0.1);
}

.results-count {
    background: #f8f9fa;
    padding: 8px 16px;
    border-radius: 20px;
    font-size: 14px;
    color: #666;
    border: 1px solid #dee2e6;
}

.loading-state, .no-results-state, .error-state {
    min-height: 300px;
    display: flex;
    align-items: center;
    justify-content: center;
}

.no-results-icon, .error-icon {
    margin-bottom: 20px;
}

/* #filterSection {
    transition: all 0.3s ease-in-out;
    background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
    border-radius: 15px;
    margin: 20px 0;
    box-shadow: 0 5px 20px rgba(0,0,0,0.1);
    position: relative;
    z-index: 1000;
    scroll-margin-top: 20px; 
}

#filterSection[style*="display: block"] {
    display: block !important;
}

#filterSection[style*="display: none"] {
    display: none !important;
}

html {
    scroll-behavior: smooth;
} */

.events-grid {
    min-height: 200px;
}

/* Enhanced button styles */
#clearFiltersBtn {
    padding: 12px 24px;
    font-weight: 600;
    border-radius: 25px;
    transition: all 0.3s ease;
    box-shadow: 0 3px 10px rgba(220, 53, 69, 0.3);
}

#clearFiltersBtn:hover {
    transform: translateY(-2px);
    box-shadow: 0 5px 15px rgba(220, 53, 69, 0.4);
}


/* Responsive adjustments */
@media (max-width: 768px) {
    .filter-controls {
        flex-direction: column;
        gap: 15px;
    }
    
    .events-title {
        font-size: 2rem;
    }
    
    .events-subtitle {
        font-size: 1rem;
    }
    
    .filter-info .alert {
        font-size: 14px;
        padding: 15px;
    }
    
    #clearFiltersBtn {
        width: 100%;
        padding: 15px;
    }
}

/* Animation for sections */
.section, [class*="section"] {
    transition: all 0.3s ease-in-out;
}

/* Loading animation */
.loading-container {
    animation: pulse 2s infinite;
}

@keyframes pulse {
    0% { opacity: 1; }
    50% { opacity: 0.7; }
    100% { opacity: 1; }
}

/* Success message animations */
@keyframes slideInRight {
    from {
        transform: translateX(100%);
        opacity: 0;
    }
    to {
        transform: translateX(0);
        opacity: 1;
    }
}

@keyframes slideOutRight {
    from {
        transform: translateX(0);
        opacity: 1;
    }
    to {
        transform: translateX(100%);
        opacity: 0;
    }
}

/* Enhanced transitions for better UX */
.events-grid {
    transition: all 0.3s ease-in-out;
}

.event-card {
    transition: all 0.3s ease-in-out;
}

.event-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 10px 25px rgba(0,0,0,0.15);
}
</style>