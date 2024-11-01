<template>
    <v-app>
        <v-layout>
            <!--navbar-->
            <v-app-bar :elevation="0" color="blue-lighten-1">
                <template v-slot:prepend>
                    <v-app-bar-nav-icon
                        :icon="$utils.toKebabCase('mdiViewGrid')"
                        variant="text"
                        @click.stop="drawer = !drawer"
                        v-if="!$vuetify.display.mobile"
                    >
                    </v-app-bar-nav-icon>
                    {{ $appName }}
                    <v-menu-grid
                        v-if="$vuetify.display.mobile"
                        :menus="menus"
                    ></v-menu-grid>
                </template>

                <!-- <v-toolbar-title>EXAMPLE</v-toolbar-title>-->
                <v-spacer></v-spacer>

                <v-login></v-login>
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
                    <slot></slot>
                </v-container>
            </v-main>
        </v-layout>
    </v-app>
</template>
<script>
export default {
    data() {
        return {
            show_config: false,
            user: {},
            settings: null,
            open_link: null,
            drawer: true,
            menus: [
                {
                    name: "Home",
                    icon: "mdiHomeAutomation",
                    route: "#",
                    active: true,
                },
                {
                    name: "Servers",
                    icon: "mdiServerSecurity",
                    route: "#",
                    active: true,
                },
                {
                    name: "Peers",
                    icon: "mdiVpn",
                    route: "#",
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

    mounted() {
        //Do not remove this line
        // this.addPeer();
    },

    methods: {
        //do not remove this line
        addPeer() {
            this.$echo.channel(this.$channels.auth()).listen("Peer", (e) => {});
        },

        login() {
            window.location.href = "/redirect";
        },

        openLink(uri) {
            window.open(uri, '_self');
        },
    },
};
</script>
