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
        <v-layout v-if="$user.id" class="min-h-100">
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

                <v-toolbar-title>
                    <span class="text-white">
                        {{ $appName }}
                    </span>
                </v-toolbar-title>
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
                <v-container class="spacing-playground" fluid>
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
                    //visible: true,
                },
                {
                    name: "Settings",
                    icon: "mdiCog",
                    route: "settings",
                    //visible: this.hasGroup("administrator"),
                },
                {
                    name: "Servers",
                    icon: "mdiServerSecurity",
                    route: "servers",
                    //visible: this.hasGroup("administrator"),
                },
                {
                    name: "Wireguard",
                    icon: "mdiTools",
                    route: "wireguard",
                    //visible: this.hasGroup("administrator"),
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

        hasGroup(name) {
            return this.$user.groups.some(
                (item) => (item.slug = name || item.slug == "administrator")
            );
        },
    },
};
</script>
