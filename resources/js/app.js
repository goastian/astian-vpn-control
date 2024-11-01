import '../css/app.css';
import './bootstrap';

import { createInertiaApp } from '@inertiajs/vue3';
import { resolvePageComponent } from 'laravel-vite-plugin/inertia-helpers';
import { createApp, h } from 'vue';
import { ZiggyVue } from '../../vendor/tightenco/ziggy';
import { globalComponents } from './Config/GlobalComponents';
import { utils } from './Config/Utils';

// Vuetify
import '@mdi/font/css/materialdesignicons.css';
import 'vuetify/styles';
import { createVuetify } from 'vuetify';
import * as components from 'vuetify/components';
import * as directives from 'vuetify/directives';

const appName = import.meta.env.VITE_APP_NAME || 'Laravel';

//Inetia app 
createInertiaApp({
    title: (title) => `${title} - ${appName}`,
    resolve: (name) =>
        resolvePageComponent(
            `./Pages/${name}.vue`,
            import.meta.glob('./Pages/**/*.vue'),
            import.meta.glob('./Components/*.vue')
        ),
    setup({ el, App, props, plugin }) {

        //vuerify components
        const vuetify = createVuetify({
            components,
            directives
        });

        //vuejs app 
        const app = createApp({ render: () => h(App, props) });

        //Global properties
        app.config.globalProperties.$utils = utils;
        app.config.globalProperties.$appName = appName


        //global components
        Object.entries(globalComponents).forEach(([name, component]) => {             
            app.component(name, component);
        });

        app.use(plugin);
        app.use(ZiggyVue);
        app.use(vuetify);
        app.mount(el);

        return app;
    },
    progress: {
        color: '#4B5563',
    },
});
