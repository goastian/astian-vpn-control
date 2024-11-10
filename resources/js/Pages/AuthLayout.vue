<template>
    <v-layout>
        <!--navbar-->
        <v-app-bar :elevation="0" color="#00b9b5">
            <template v-slot:prepend>
                <v-app-bar-nav-icon
                    :icon="$utils.toKebabCase('mdiViewGrid')"
                    variant="text"
                    @click.stop="drawer = !drawer"
                    v-if="!$vuetify.display.mobile"
                >
                </v-app-bar-nav-icon>
            </template>

            <v-toolbar-title> {{ $appName }}</v-toolbar-title>
            <v-spacer></v-spacer>
            <v-menu-grid></v-menu-grid>
            <v-logout></v-logout>
        </v-app-bar>
        <!--end navbar-->

        <!--leftbar-->
        <v-navigation-drawer
            v-model="drawer"
            v-if="!$vuetify.display.mobile"
            width="200"
        >
            <v-list density="compact" nav>
                <v-list-item
                    density="compact"
                    v-for="(item, index) in menus"
                    :key="index"
                    :value="item.name"
                    :subtitle="item.name"
                    @click="openLink(item.route)"
                    class="mb-5"
                    v-show="item.active"
                >
                    <template v-slot:prepend>
                        <v-icon
                            color="light-blue-darken-1"
                            :icon="$utils.toKebabCase(item.icon)"
                        ></v-icon>
                    </template>
                </v-list-item>
            </v-list>
        </v-navigation-drawer>
        <!--righbar-->

        <!--main content-->
        <v-main>
            <v-container class="spacing-playground pa-1" fluid>
                <router-view></router-view>
            </v-container>
        </v-main>
    </v-layout>
</template>
<script>
import { computed } from "vue";

export default {
    data() {
        return {
            show_config: false,
            user: {},
            settings: null,
            open_link: null,
            drawer: true,
            user: {},
            menus: [
                {
                    name: "Home",
                    icon: "mdiHomeAutomation",
                    route: "home",
                    active: true,
                },
                {
                    name: "Servers",
                    icon: "mdiServerSecurity",
                    route: "servers",
                    active: true,
                },
                {
                    name: "Wireguard",
                    icon: "mdiTools",
                    route: "wireguard",
                    active: true,
                },
                {
                    name: "Peers",
                    icon: "mdiVpn",
                    route: "peers",
                    active: true,
                },
                {
                    name: "Cloud",
                    icon: "mdiCloudCheckVariantOutline",
                    route: "https://cloud.astian.org",
                    active: false,
                },
                {
                    name: "Notes",
                    icon: "mdiNotebookHeartOutline",
                    route: "https://notes.astian.org",
                    active: false,
                },
                {
                    name: "Calendar",
                    icon: "mdiCalendarMultiselectOutline",
                    route: "https://calendar.astian.org",
                    active: false,
                },
                {
                    name: "Contacts",
                    icon: "mdiCardAccountMailOutline",
                    route: "https://notes.astian.org",
                    active: false,
                },
            ],
        };
    },

    provide() {
        return {
            $user: computed(() => this.user),
        };
    },

    mounted() {
        //Do not remove this line
        // this.addPeer();
        this.getUser();
    },

    methods: {
        //do not remove this line
        addPeer() {
            this.$echo.channel(this.$channels.auth()).listen("Peer", (e) => {});
        },

        async getUser() {
            try {
                const res = await this.$server.get("/api/gateway/user");
                if (res.status == 200) {
                    this.user = res.data;
                }
            } catch (error) {}
        },

        openLink(uri) {
            try {
                this.$router.push({ name: uri });
            } catch (err) {
                window.open(uri, "_self");
            }
        },
    },
};
</script>
