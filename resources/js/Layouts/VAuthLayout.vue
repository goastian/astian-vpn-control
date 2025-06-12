<template>
    <q-layout view="lHh Lpr lff" class="app">
        <q-header elevated class="q-px-md q-py-sm">
            <q-toolbar class="flex items-center justify-between">
                <q-btn
                    flat
                    dense
                    round
                    icon="menu"
                    class="q-mr-sm q-md-none"
                    @click="leftDrawerOpen = !leftDrawerOpen"
                />

                <q-toolbar-title class="text-h6 text-weight-bold">
                    {{ app_name }}
                </q-toolbar-title>

                <v-theme />
                <v-logout />
            </q-toolbar>
        </q-header>

        <q-drawer
            v-model="leftDrawerOpen"
            :show-if-above="true"
            :width="112"
            class="nav-container"
            bordered
        >
            <q-list class="nav-list">
                <q-item>
                    <q-item-section class="flex justify-center">
                        <img src="/img/icon.webp" class="logo" />
                    </q-item-section>
                </q-item>

                <q-separator spaced />

                <q-item
                    v-for="(item, index) in routes"
                    :key="index"
                    v-show="item.show"
                    clickable
                    v-ripple
                    class="nav-item"
                    @click="openLink(item)"
                >
                    <div class="text-center text-primary">
                        <q-item-section avatar>
                            <q-avatar size="4rem" :icon="item.icon" />
                        </q-item-section>
                        <q-item-section>
                            <span>{{ item.name }}</span>
                        </q-item-section>
                    </div>
                </q-item>

                <q-separator spaced />

                <q-item
                    clickable
                    v-ripple
                    v-show="admin_dashboard.show"
                    @click="openLink(admin_dashboard)"
                >
                    <div class="text-center text-primary">
                        <q-item-section avatar>
                            <q-avatar
                                size="4rem"
                                :icon="admin_dashboard.icon"
                            />
                        </q-item-section>
                        <q-item-section>
                            <span>Admin</span>
                        </q-item-section>
                    </div>
                </q-item>
            </q-list>
        </q-drawer>

        <q-page-container class="main q-pa-md">
            <slot />
        </q-page-container>
    </q-layout>
</template>

<script>
export default {
    data() {
        return {
            app_name: "",
            user: {},
            routes: [],
            admin_dashboard: {},
            leftDrawerOpen: true, // control del drawer
        };
    },

    created() {
        this.app_name = this.$page.props.app_name;
        this.user = this.$page.props.user;
        this.routes = this.$page.props.user_dashboard_routes;
        this.admin_dashboard = this.$page.props.admin_dashboard;
    },

    methods: {
        openLink(item) {
            window.location.href = item.route;
        },
    },
};
</script>

<style scoped>
.logo {
    width: 56px;
    height: 56px;
    border-radius: 12px;
}
</style>
