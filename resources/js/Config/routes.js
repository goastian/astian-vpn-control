import { createRouter, createWebHistory } from "vue-router";
import AuthLayout from "../Pages/AuthLayout.vue";
import GuestLayout from "../Pages/GuestLayout.vue";

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
                path: "/server",
                name: "servers",
                component: () => import("../Pages/Auth/Server/Server.vue"),
                meta: {
                    auth: true,
                },
            },
            {
                path: "/wireguard",
                name: "wireguard",
                component: () => import("../Pages/Auth/Wg/Wg.vue"),
                meta: {
                    auth: true,
                },
            },
            {
                path: "/settings",
                name: "settings",
                component: () => import("../Pages/Auth/Setting/Index.vue"),
                meta: {
                    auth: true,
                },
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
            {
                path: "about",
                name: "about",
                component: () => import("../Pages/Guest/About.vue"),
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
