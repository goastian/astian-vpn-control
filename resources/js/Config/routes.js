import { createRouter, createWebHistory } from "vue-router";
import AuthLayout from "../Pages/AuthLayout.vue";
import GuestLayout from "../Pages/GuestLayout.vue";
import SettingLayout from "../Pages/SettingLayout.vue";

const routes = [
    {
        path: "/",
        component: AuthLayout,
        children: [
            {
                path: "",
                name: "home",
                component: () => import("../Pages/Auth/Home/Dashboard.vue"),
                meta: {
                    auth: true,
                },
            },
            {
                path: "/server", //admin
                name: "servers",
                component: () => import("../Pages/Auth/Server/Server.vue"),
                meta: {
                    auth: true,
                },
            },
            {
                path: "/wireguard",
                name: "wireguard", //admin
                component: () => import("../Pages/Auth/Wg/Wg.vue"),
                meta: {
                    auth: true,
                },
            },
            {
                path: "/peers",
                name: "peers",
                component: () => import("../Pages/Auth/Peer/Peer.vue"),
            },
            {
                path: "/instructions",
                name: "instructions",
                component: () => import("../Pages/Auth/Instructions/Index.vue"),
            },
        ],
    },

    {
        path: "/settings",
        component: SettingLayout,
        children: [
            {
                path: "",
                name: "settings.general",
                component: () => import("../Pages/Setting/General.vue"),
            },
        ],
    },

    {
        path: "/",
        component: GuestLayout,
        children: [
            {
                path: "welcome",
                name: "welcome",
                component: () => import("../Pages/Guest/Login.vue"),
                meta: {
                    auth: false,
                },
            },
        ],
    },
];

export const router = createRouter({
    history: createWebHistory(),
    routes,
});
