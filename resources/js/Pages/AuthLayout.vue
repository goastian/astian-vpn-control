<template>
    <q-layout view="lHh lpR lFf">
        <q-header bordered class="header">
            <q-toolbar>

                <q-toolbar-title> {{ $app_name }} </q-toolbar-title>

                <q-space></q-space>

                <v-logout></v-logout>
            </q-toolbar>
        </q-header>

        <q-drawer
            class="nav"
            show-if-above
            v-model="leftDrawerOpen"
            side="left"
            behavior="desktop"
            :elevation="0"
            :width="90"
        >
            <div class="nav-container">
                <q-list
                    borderedless
                    padding
                    class="rounded-borders nav-list"
                >
                    <q-item>
                        <q-item-section>
                            <img src="/img/icon.webp" />
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
                    >
                        <q-item-section avatar>
                            <span class="text-2xl" :class="item.icon"></span>
                        </q-item-section>

                    </q-item>

                    <q-separator />
                </q-list>
            </div>
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
                    route: "admin.servers",
                },
                {
                    name: "Wireguard",
                    icon: "mdi mdi-tools",
                    route: "admin.wireguard",
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

<style scoped>
.header {
    background-color: var(--bg-primary);
    color: var(--text-color);
    border: none;
}

.nav-container {
    background-color: var(--bg-secondary);
    border-radius: 1rem;
    height: 100%;
    box-shadow: 0 0 10px rgba(0, 0, 0, 0.04);
    border: .04rem solid var(--border);

}

.nav-list {
    display: flex;
    flex-direction: column;
    gap: 1rem;
    color: var(--icon);
}

.q-item {
    position: relative;
}

.q-item::before {
    content: '';
    width: 0;
    position: absolute;
    bottom: 0;
    left: 0;
    right: 0;
    margin: auto;
    height: .08rem;
    background-color: var(--blue);
    transition: 1s ease;
}

.active-link {
    color: var(--blue);
}

.active-link::before {
    width: 50%;
}

.main {
    background-color: var(--bg-primary);
}
</style>
