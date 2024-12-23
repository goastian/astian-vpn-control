<template>
    <div>
        <div
            v-if="!$user.id"
            class="d-flex align-center h-screen w-screen justify-center"
        >
            <v-progress-circular
                :size="150"
                color="amber"
                indeterminate
            ></v-progress-circular>
        </div>
        <v-layout v-if="$user.id" class="h-screen w-screen">
            <!--navbar-->
            <v-app-bar :elevation="0" color="#00b9b5">
                <template v-slot:prepend>
                    <v-app-bar-nav-icon
                        :icon="$utils.toKebabCase('mdiViewGrid')"
                        variant="text"
                        @click="drawer = !drawer"
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
            <v-navigation-drawer v-model="drawer" width="200">
                <v-list density="compact" nav>
                    <v-list-item
                        density="compact"
                        v-for="(item, index) in menus"
                        :key="index"
                        :value="item.name"
                        :subtitle="item.name"
                        @click="openLink(item.route)"
                        class="mb-5"
                        v-show="hasGroup(item.group)"
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
    </div>
</template>
<script>
export default {
    inject: ["$user"],

    data() {
        return {
            show_config: false,
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
                    group: "admin",
                },
                {
                    name: "Wireguard",
                    icon: "mdiTools",
                    route: "wireguard",
                    active: true,
                    group: "admin",
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
                    active: true,
                },
                {
                    name: "Notes",
                    icon: "mdiNotebookHeartOutline",
                    route: "https://notes.astian.org",
                    active: true,
                },
                {
                    name: "Calendar",
                    icon: "mdiCalendarMultiselectOutline",
                    route: "https://calendar.astian.org",
                    active: true,
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

    methods: {
        openLink(uri) {
            try {
                this.$router.push({ name: uri });
            } catch (err) {
                window.open(uri, "_self");
            }
        },

        hasGroup(groupName) {
            if (this.isAdmin()) {
                return true;
            }

            if (!groupName && this.isClient()) {
                return true;
            }

            const group = this.$user.roles.find(
                (item) => item.name == groupName
            );
            return group ? true : false;
        },

        isAdmin() {
            const group = this.$user.roles.find((item) => item.name == "admin");
            return group ? true : false;
        },

        isClient() {
            const group = this.$user.roles.find(
                (item) => item.name == "client"
            );
            return group ? true : false;
        },
    },
};
</script>
