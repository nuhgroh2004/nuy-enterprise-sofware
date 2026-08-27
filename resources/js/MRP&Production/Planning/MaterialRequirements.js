document.addEventListener('DOMContentLoaded', () => {

    /*
    |--------------------------------------------------------------------------
    | Search Material
    |--------------------------------------------------------------------------
    */

    const searchInput =
        document.querySelector('.search-input');

    if (searchInput) {

        searchInput.addEventListener('click', () => {

            console.log('Search material');

        });

    }


    /*
    |--------------------------------------------------------------------------
    | Material Status Filter
    |--------------------------------------------------------------------------
    */

    const filterSelects =
        document.querySelectorAll('.filter-row select');

    filterSelects.forEach(select => {

        select.addEventListener('change', function () {

            console.log(
                'Material filter:',
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
    | Procurement Button
    |--------------------------------------------------------------------------
    */

    const procurementButton =
        document.querySelector('.table-actions .btn-primary');

    if (procurementButton) {

        procurementButton.addEventListener('click', () => {

            console.log(
                'Open suggested procurement'
            );

        });

    }


    /*
    |--------------------------------------------------------------------------
    | Calculate Material Requirement
    |--------------------------------------------------------------------------
    */

    const calculateButton =
        document.querySelector('.header-actions .btn-primary');

    if (calculateButton) {

        calculateButton.addEventListener('click', () => {

            console.log(
                'Calculate material requirements'
            );

        });

    }

});