import { createApp } from "vue";
import App from "./App.vue";

import { custom_components } from "./Config/globalComponents";
import { utils } from "./Config/utils";
import { $api, $server } from "./Config/axios";
import { router } from "./Config/routes";
import { notyf } from "./Config/notification";
import { Quasar, Ripple, ClosePopup, Notify, Dialog, Loading } from "quasar";

import "./Config/matomo"

//icons material dissing https://pictogrammers.com/library/mdi/
import "@mdi/font/css/materialdesignicons.css";

//Quasar
import "quasar/dist/quasar.css";
import "@quasar/extras/material-icons/material-icons.css";
import { QComponents } from "./Config/quasar";

//---- APP AUTH USERS ---//
const app = createApp(App);

custom_components.forEach((index) => {
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
app.config.globalProperties.$utils = utils; 
app.config.globalProperties.$server = $server;
app.config.globalProperties.$api = $api;
app.config.globalProperties.$notification = notyf;

app.use(router);

app.mount("#app");
