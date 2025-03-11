<template>
    <q-layout view="lHh Lpr lff" class="app">
        <q-header bordered class="header">
            <q-toolbar>
                <q-toolbar-title> {{ $app_name }} </q-toolbar-title>

                <q-space></q-space>

                <v-logout></v-logout>
            </q-toolbar>
        </q-header>

        <q-drawer
            show-if-above

            :width="104"
            class="nav"
        >
            <q-scroll-area class="fit nav-container">
                <q-list
                    class="nav-list"
                >
                    <q-item>
                        <q-item-section>
                            <img src="/img/icon.webp" class="logo" />
                        </q-item-section>
                    </q-item>

                    <q-item
                        v-for="(item, index) in filterRoutes"
                        clickable
                        v-ripple
                        @click="openLink(item.route)"
                        :key="index"
                        :active="$route.name === item.route"
                        active-class="active-link"
                    >
                        <q-item-section avatar>
                            <span class="text-2xl icon" :class="item.icon"></span>
                        </q-item-section>
                        <span class="navTitle">{{ item.name }}</span>
                    </q-item>

                    <q-separator />
                </q-list>
            </q-scroll-area>
        </q-drawer>

        <q-page-container class="main">
            <router-view />
        </q-page-container>
    </q-layout>
</template>

<script>
import { useUserStore } from '../stores/userStore.js';
export default {
    inject: ["$user", "$app_name"],

    data() {
        return {
            show_config: false,
            settings: null,
            open_link: null,
            user: {},
            userr: useUserStore(),
            menus: [
                {
                    name: "Home",
                    icon: "mdi mdi-home-automation",
                    route: "home",
                    roles: ['administrator', 'member']
                },
                {
                    name: "Servers",
                    icon: "mdi mdi-server-security",
                    route: "admin.servers",
                    roles: ['administrator']
                },
                {
                    name: "Wireguard",
                    icon: "mdi mdi-tools",
                    route: "admin.wireguard",
                    roles: ['administrator']
                },
                {
                    name: "Peers",
                    icon: "mdi mdi-vpn",
                    route: "peers",
                    roles: ['administrator', 'member']
                },
                {
                    name: "Instructions",
                    icon: "mdi mdi-information-outline",
                    route: "instructions",
                    roles: ['administrator', 'member']
                },
            ],
        };
    },

    computed: {
        filterRoutes() {
            return this.menus.filter((data) => data.roles.includes(this.userr.user.groups[0].name))
        },
    },

    methods: {
        openLink(uri) {
            try {
                this.$router.push({ name: uri });
            } catch (err) {
                window.open(uri, "_self");
            }
        },

        toggleLeftDrawer() {
            this.leftDrawerOpen = !this.leftDrawerOpen;
        },
    },
};
</script>

<style scoped>
.header {
    background-color: var(--bg-primary);
    color: var(--text-color);
    border: none;
}

.nav-container {
    height: 100%;
    background-color: var(--bg-secondary);
    border-radius: 1rem;
    padding: .6rem 0;
    height: 100%;
    box-shadow: 0 0 10px rgba(0, 0, 0, 0.04);
    border: .04rem solid var(--border);
}

.nav-list {
    display: flex;
    flex-direction: column;
    gap: .4rem;
    color: var(--icon);
}

.q-drawer {
}

.q-item {
    position: relative;
    padding: 1.2rem 0;
}

.q-item::before {
    content: '';
    width: 0%;
    position: absolute;
    bottom: 0;
    left: 0;
    right: 0;
    margin: auto;
    height: .08rem;
    transition: 1s ease;
}

.active-link {
    color: var(--blue);
}

.active-link::before {
    background-color: var(--blue);
    width: 50%;
}

.q-scrollarea {
    contain: none;
}

.navTitle {
    position: absolute;
    bottom: 10px;
    right: 0;
    left: 0;
    margin: auto;
    text-align: center;
    font-size: .75rem;
}

.icon {
    padding-left: 1.6rem;
}

.logo {
    width: 55px;
    padding-left: 1.1rem;
}

.app {
    background-color: var(--bg-primary);
}
</style>
