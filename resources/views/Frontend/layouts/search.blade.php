<!-- Search Results Section (same structure as New Events / Upcoming Events) -->
<section class="New-card-event-results-section" id="filterSection" style="display: none;">
    <div class="New-card-event-results-container">
        <div class="header">
            <h2 class="title">Search Results</h2>
            <p class="subtitle">Events matching your search criteria</p>
            <div class="search-results-bar">
                <span id="resultsCount" class="search-results-count"></span>
                <button id="clearFiltersBtn" type="button" class="New-card-event-details-btn search-clear-btn" onclick="clearFilters()">
                    <i class="fas fa-times"></i> Clear All Filters &amp; Show All Sections
                </button>
            </div>
        </div>

        <div class="New-card-event-cards-container">
            <div class="New-card-event-cards-grid" id="filteredEventsGrid">
                <!-- Dynamic content loaded via AJAX -->
            </div>

            <!-- Loading State -->
            <div id="loadingState" class="search-state" style="display: none;">
                <div class="text-center py-5">
                    <i class="fas fa-spinner fa-spin" style="font-size: 48px; color: var(--orange, #e8642c);"></i>
                    <p class="mt-3">Searching for events...</p>
                </div>
            </div>

            <!-- No Results State -->
            <div id="noResultsState" class="search-state" style="display: none;">
                <div class="text-center py-5">
                    <i class="fas fa-search" style="font-size: 64px; color: #999;"></i>
                    <h3 class="mt-3">No events match your search</h3>
                    <p class="text-muted">Try changing your search or filters</p>
                    <button type="button" class="New-card-event-details-btn mt-3" onclick="clearFilters()">Clear Filters</button>
                </div>
            </div>

            <!-- Error State -->
            <div id="errorState" class="search-state" style="display: none;">
                <div class="text-center py-5">
                    <i class="fas fa-exclamation-triangle" style="font-size: 64px; color: #dc3545;"></i>
                    <h3 class="mt-3">Search error</h3>
                    <p class="text-muted" id="errorMessage">An error occurred while searching.</p>
                    <button type="button" class="New-card-event-details-btn mt-3" onclick="clearFilters()">Try Again</button>
                </div>
            </div>
        </div>
    </div>
</section>

<style>
.search-results-bar {
    display: flex;
    align-items: center;
    gap: 1rem;
    margin-top: 1rem;
    flex-wrap: wrap;
}
.search-results-count {
    padding: 8px 16px;
    border-radius: 20px;
    font-size: 14px;
    background: rgba(255,255,255,0.08);
    color: inherit;
    border: 1px solid rgba(255,255,255,0.12);
}
.search-clear-btn { margin: 0; }
.search-state { min-height: 200px; display: flex; align-items: center; justify-content: center; }
</style>
