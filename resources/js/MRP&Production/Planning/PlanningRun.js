document.addEventListener('DOMContentLoaded', () => {
    /*
    |--------------------------------------------------------------------------
    | Run Planning
    |--------------------------------------------------------------------------
    */
    const runButton =
        document.querySelector('.header-actions .btn-primary');
    if (runButton) {
        runButton.addEventListener('click', () => {
            console.log('Execute planning run');
        });
    }
    /*
    |--------------------------------------------------------------------------
    | Search Run
    |--------------------------------------------------------------------------
    */
    const searchInput =
        document.querySelector('.search-input');
    if (searchInput) {
        searchInput.addEventListener('click', () => {
            console.log('Search planning run');
        });
    }
    /*
    |--------------------------------------------------------------------------
    | Run Status Filter
    |--------------------------------------------------------------------------
    */
    const filterSelects =
        document.querySelectorAll('.filter-row select');
    filterSelects.forEach(select => {
        select.addEventListener('change', function () {
            console.log(
                'Run filter:',
                this.value
            );
        });
    });
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
                tile
                    .querySelector('.t')
                    ?.textContent
                    .trim();
            console.log(
                'Feature selected:',
                title
            );
        });
    });
    /*
    |--------------------------------------------------------------------------
    | Exception Row Click
    |--------------------------------------------------------------------------
    */
    const exceptionRows =
        document.querySelectorAll('.exception-row');
    exceptionRows.forEach(row => {
        row.addEventListener('click', () => {
            const name =
                row
                    .querySelector('.exception-name')
                    ?.textContent
                    .trim();
            console.log(
                'Exception selected:',
                name
            );
        });
    });
    /*
    |--------------------------------------------------------------------------
    | Timeline Item Click
    |--------------------------------------------------------------------------
    */
    const timelineItems =
        document.querySelectorAll('.timeline-item');
    timelineItems.forEach(item => {
        item.addEventListener('click', () => {
            const title =
                item
                    .querySelector('.timeline-title')
                    ?.textContent
                    .trim();
            console.log(
                'Timeline step:',
                title
            );
        });
    });
    /*
    |--------------------------------------------------------------------------
    | Table Row Click
    |--------------------------------------------------------------------------
    */
    const tableRows =
        document.querySelectorAll('tbody tr');
    tableRows.forEach(row => {
        row.style.cursor = 'pointer';
        row.addEventListener('click', () => {
            const id =
                row
                    .querySelector('td')
                    ?.textContent
                    .trim();
            console.log(
                'Run selected:',
                id
            );
        });
    });
});
