<template>
    <q-layout view="hHh lpR lFf">
        <q-header bordered class="bg-primary text-white">
            <q-toolbar>
                <q-btn dense flat round icon="menu" @click="toggleLeftDrawer" />

                <q-toolbar-title> {{ $app_name }} </q-toolbar-title>

                <q-space></q-space>

                <v-logout></v-logout>
            </q-toolbar>
        </q-header>

        <q-drawer
            show-if-above
            v-model="leftDrawerOpen"
            side="left"
            behavior="desktop"
            elevated
        >
            <q-list
                bordered
                padding
                class="rounded-borders text-primary"
                v-for="(item, index) in menus"
                :key="index"
            >
                <q-item clickable v-ripple @click="openLink(item.route)">
                    <q-item-section avatar>
                        <span class="text-2xl" :class="item.icon"></span>
                    </q-item-section>

                    <q-item-section>{{ item.name }}</q-item-section>
                </q-item>
            </q-list>
        </q-drawer>

        <q-page-container>
            <router-view />
        </q-page-container>
    </q-layout>
</template>

<script>
export default {
    inject: ["$user", "$app_name"],

    data() {
        return {
            show_config: false,
            settings: null,
            open_link: null,
            user: {},
            leftDrawerOpen: false,
            menus: [
                {
                    name: "Home",
                    icon: "mdi mdi-home-automation",
                    route: "home",
                },
                {
                    name: "Servers",
                    icon: "mdi mdi-server-security",
                    route: "servers",
                },
                {
                    name: "Wireguard",
                    icon: "mdi mdi-tools",
                    route: "wireguard",
                },
                {
                    name: "Peers",
                    icon: "mdi mdi-vpn",
                    route: "peers",
                },
                {
                    name: "Instructions",
                    icon: "mdi mdi-information-outline",
                    route: "instructions",
                },
            ],
        };
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
