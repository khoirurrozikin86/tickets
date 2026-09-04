import '../css/app.css';

import { createInertiaApp } from '@inertiajs/react';
import { createRoot } from 'react-dom/client';
import { resolvePageComponent } from 'laravel-vite-plugin/inertia-helpers';
import type { ComponentType } from 'react';

type PageModule = {
    default: ComponentType<any>;
};

createInertiaApp({
    title: (title) => `${title} - Dusun Semilir`,

    resolve: (name) => resolvePageComponent<PageModule>(
        `./pages/${name}.tsx`,
        import.meta.glob<Promise<PageModule>>('./pages/**/*.tsx'),
    ),

    setup({ el, App, props }) {
        createRoot(el).render(
            <App {...props} />
        );
    },
});