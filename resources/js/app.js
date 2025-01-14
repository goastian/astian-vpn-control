import { createApp } from "vue";
import App from "./App.vue";

import { custom_components } from "./Config/globalComponents";
import { utils } from "./Config/utils";
import { $host, $server } from "./Config/axios";
import { router } from "./Config/routes";
import { notyf } from "./Config/notification";
import matomo from "./Config/matomo.js";

// Vuetify
import "@mdi/font/css/materialdesignicons.css";
import "vuetify/styles";
import { createVuetify } from "vuetify";
import * as components from "vuetify/components";
import * as directives from "vuetify/directives";

//vuerify components
const vuetify = createVuetify({
    components,
    directives,
});

const appName = process.env.MIX_APP_NAME || "Laravel";

//---- APP AUTH USERS ---//
const app = createApp(App);

custom_components.forEach((index) => {
    app.component(index[0], index[1]);
});

//Global properties
app.config.globalProperties.$utils = utils;
app.config.globalProperties.$appName = appName;
app.config.globalProperties.$server = $server;
app.config.globalProperties.$api = $host;
app.config.globalProperties.$notification = notyf;
//global components

app.use(vuetify);
app.use(router);

app.mount("#app");
