<template>
    <q-layout view="lHh Lpr lff" class="app">
        <q-header bordered class="header">
            <q-toolbar>
                <q-toolbar-title> {{ $app_name }} </q-toolbar-title>

                <q-space></q-space>

                <v-logout></v-logout>
            </q-toolbar>
        </q-header>

        <q-drawer show-if-above :width="104" class="nav">
            <q-scroll-area class="fit nav-container">
                <q-list class="nav-list">
                    <q-item>
                        <q-item-section>
                            <img src="/img/icon.webp" class="logo" />
                        </q-item-section>
                    </q-item>

                    <q-item
                        v-for="(item, index) in menus"
                        clickable
                        v-ripple
                        @click="openLink(item.route)"
                        :key="index"
                        :active="$route.name === item.route"
                        active-class="active-link"
                        v-show="item.show"
                    >
                        <q-item-section avatar>
                            <span
                                class="text-2xl icon"
                                :class="item.icon"
                            ></span>
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
export default {
    inject: ["$user", "$app_name"],

    data() {
        return {
            show_config: false,
            settings: null,
            open_link: null,
            menus: [
                {
                    name: "Home",
                    icon: "mdi mdi-home-automation",
                    route: "home",
                    show: this.hasGroup("member"),
                },
                {
                    name: "Servers",
                    icon: "mdi mdi-server-security",
                    route: "admin.servers",
                    show: this.hasGroup("administrator"),
                },
                {
                    name: "Wireguard",
                    icon: "mdi mdi-tools",
                    route: "admin.wireguard",
                    show: this.hasGroup("administrator"),
                },
                {
                    name: "Peers",
                    icon: "mdi mdi-vpn",
                    route: "peers",
                    show: this.hasGroup("member"),
                },
                {
                    name: "Instructions",
                    icon: "mdi mdi-information-outline",
                    route: "instructions",
                    show: this.hasGroup("member"),
                },
                {
                    name: "Settings",
                    icon: "mdi mdi-cogs",
                    route: "settings.general",
                    show: this.hasGroup("administrator"),
                },
            ],
        };
    },

    methods: {
        hasGroup(name) {
            try {
                return this.$user.groups.some(
                    (item) => item.slug == name || item.slug == "administrator"
                );
            } catch (TypeError) {}
        },

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
    padding: 0.6rem 0;
    height: 100%;
    box-shadow: 0 0 10px rgba(0, 0, 0, 0.04);
    border: 0.04rem solid var(--border);
}

.nav-list {
    display: flex;
    flex-direction: column;
    gap: 0.4rem;
    color: var(--icon);
}

.q-drawer {
}

.q-item {
    position: relative;
    padding: 1.2rem 0;
}

.q-item::before {
    content: "";
    width: 0%;
    position: absolute;
    bottom: 0;
    left: 0;
    right: 0;
    margin: auto;
    height: 0.08rem;
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
    font-size: 0.75rem;
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
