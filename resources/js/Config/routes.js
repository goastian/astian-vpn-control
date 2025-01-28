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
            },
            {
                path: "/server",
                name: "servers",
                component: () => import("../Pages/Auth/Server/Server.vue"),
            },
            {
                path: "/wireguard",
                name: "wireguard",
                component: () => import("../Pages/Auth/Wg/Wg.vue"),
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
            {
                path: "/settings",
                name: "settings",
                component: () => import("../Pages/Auth/Setting/Index.vue"),
            },
        ],
    },

    {
        path: "/welcome",
        component: GuestLayout,
        children: [
            {
                path: "",
                name: "welcome",
                component: () => import("../Pages/Welcome/Login.vue"),
            },
        ],
    },
];

export const router = createRouter({
    history: createWebHistory(),
    routes,
});
