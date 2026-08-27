document.addEventListener('DOMContentLoaded', () => {
    /*
    |--------------------------------------------------------------------------
    | Period Selector
    |--------------------------------------------------------------------------
    */
    const periodSelect = document.querySelector('.period-select');
    if (periodSelect) {
        periodSelect.addEventListener('change', function () {
            console.log(
                'Period forecast:',
                this.value
            );
        });
    }
    /*
    |--------------------------------------------------------------------------
    | Search Demand
    |--------------------------------------------------------------------------
    */
    const searchInput = document.querySelector('.search-input');
    if (searchInput) {
        searchInput.addEventListener('click', () => {
            console.log('Search demand');
        });
    }
    /*
    |--------------------------------------------------------------------------
    | Feature Tiles
    |--------------------------------------------------------------------------
    */
    const featureTiles =
        document.querySelectorAll('.feature-tile');
    featureTiles.forEach(tile => {
        tile.addEventListener('click', () => {
            const title =
                tile.querySelector('.t')?.textContent.trim();
            console.log(
                'Feature selected:',
                title
            );
        });
    });
    /*
    |--------------------------------------------------------------------------
    | Filter Status
    |--------------------------------------------------------------------------
    */
    const filterSelects =
        document.querySelectorAll('.filter-row select');
    filterSelects.forEach(select => {
        select.addEventListener('change', function () {
            console.log(
                'Filter changed:',
                this.value
            );
        });
    });
});