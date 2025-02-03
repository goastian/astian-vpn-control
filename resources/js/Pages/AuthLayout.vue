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
            <!--leftbar-->
            <v-navigation-drawer permanent rail>
                <template v-slot:prepend>
                    <v-list-item
                    >
                        <v-avatar
                            rounded="0"
                            size="22px"
                            class="mt-4 mb-4"
                        >
                            <v-img
                                alt="John"
                                src="/img/icon.webp"
                                width="20"
                            ></v-img>
                        </v-avatar>
                    </v-list-item>
                </template>

                <v-list density="compact" nav class="flex flex-column ga-4">
                    <v-list-item
                        density="compact"
                        v-for="(item, index) in menus"
                        :key="index"
                        @click="openLink(item.route)"
                        v-show="hasGroup(item.group)"
                        class=""
                    >
                        <template v-slot:prepend>
                            <v-icon
                                color="grey-darken-3"
                                :icon="$utils.toKebabCase(item.icon)"
                            ></v-icon>

                        </template>
                        <v-tooltip
                            activator="parent"
                            location="end"
                        >{{ item.name }}</v-tooltip>
                    </v-list-item>
                </v-list>

                <v-divider></v-divider>

                <v-list density="compact" nav>
                    <v-list-item density="compact">
                        <v-menu-grid>

                        </v-menu-grid>
                        <v-tooltip
                                activator="parent"
                                location="end"
                            >
                                Apps
                            </v-tooltip>
                    </v-list-item>
                </v-list>

                <template v-slot:append>
                    <v-list density="compact">
                        <v-list-item density="compact">
                            <v-icon
                                color="grey-darken-2"
                                :icon="$utils.toKebabCase(menu.icon)"
                                @click="openLink(menu.route)"
                                v-show="hasGroup(menu.group)"
                            />
                            <v-tooltip
                                activator="parent"
                                location="end"
                            >{{ menu.name }}
                            </v-tooltip>
                        </v-list-item>
                    </v-list>
                </template>
            </v-navigation-drawer>
            <!--leftbar-->

            <!--navbar-->
            <v-app-bar :elevation="0">
                <v-toolbar-title>{{ $appName }}</v-toolbar-title>
                <!--<v-geo />-->
                <v-spacer />
                <v-logout></v-logout>
            </v-app-bar>
            <!--end navbar-->

            <!--main content-->
            <v-main>
                <v-container class="spacing-playground pl-10 pr-10" fluid>
                    <router-view></router-view>
                </v-container>
            </v-main>
            <!--end content-->
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
                    name: "Instructions",
                    icon: "mdiInformationOutline",
                    route: "instructions",
                    active: true
                }
            ],
            menu: {
                    name: "Settings",
                    icon: "mdiCog",
                    route: "settings",
                    active: true,
                    group: "admin",
            },
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

<style scoped>

.v-divider {
    opacity: inherit;
}

.v-navigation-drawer {
    width: 200px;
}

.v-toolbar-title {
    font-size: 1.1rem;
}

.v-main {
    overflow: auto;
}

</style>
