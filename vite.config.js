import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import tailwindcss from '@tailwindcss/vite';

export default defineConfig({
    plugins: [
        laravel({
            input: [
                'resources/css/app.css',
                'resources/js/app.js',
                'resources/css/MRP&Production/Planning/DemandPlanning.css',
                'resources/js/MRP&Production/Planning/DemandPlanning.js',
                'resources/css/MRP&Production/Planning/MasterProductionSchedule.css',
                'resources/js/MRP&Production/Planning/MasterProductionSchedule.js',
                'resources/css/MRP&Production/Planning/MaterialRequirements.css',
                'resources/js/MRP&Production/Planning/MaterialRequirements.js',
                'resources/css/MRP&Production/Planning/PlanningRun.css',
                'resources/js/MRP&Production/Planning/PlanningRun.js',
                'resources/css/MRP&Production/Products/Products.css',
                'resources/js/MRP&Production/Products/Products.js',
                'resources/js/MRP&Production/Products/ProductDetail.js',
            ],
            refresh: true,
        }),
        tailwindcss(),
    ],
});
