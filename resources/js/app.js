import { createApp, h } from "vue";
import { createInertiaApp } from "@inertiajs/vue3";

import { custom_components } from "./Config/globalComponents";
import { layouts } from "./Config/layouts";
import { $api } from "./Config/axios";

//Quasar
import { Quasar, Ripple, ClosePopup, Notify, Dialog, Loading } from "quasar";
import "quasar/dist/quasar.css";
import "@quasar/extras/material-icons/material-icons.css";
import { QComponents } from "./Config/quasar";

//icons https://pictogrammers.com/library/mdi/
import "@mdi/font/css/materialdesignicons.css";

createInertiaApp({
    resolve: (name) => {
        const pages = require.context("./Pages", true, /\.vue$/);
        return pages(`./${name}.vue`).default;
    },
    setup({ el, App, props, plugin }) {
        const app = createApp({ render: () => h(App, props) });

        custom_components.forEach((index) => {
            app.component(index[0], index[1]);
        });
        layouts.forEach((index) => {
            app.component(index[0], index[1]);
        });

        app.use(Quasar, {
            plugins: {
                Notify,
                Dialog,
                Loading,
            },
            directives: {
                Ripple,
                ClosePopup,
            },
        });

        QComponents.forEach((item) => {
            app.component(item.name, item);
        });

        //Global properties 
        app.config.globalProperties.$api = $api;

        app.use(plugin);
        app.mount(el);
    },
});
