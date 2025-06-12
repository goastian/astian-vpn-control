<template>
    <q-layout view="hHh lpR fFf">
        <q-header elevated class="bg-primary text-white">
            <q-toolbar>
                <q-btn
                    dense
                    flat
                    round
                    icon="mdi-shield-crown"
                    @click="toggleLeftDrawer"
                />

                <q-toolbar-title>
                    {{ app_name }}
                </q-toolbar-title>
                <v-theme />
                <v-logout />
            </q-toolbar>
        </q-header>

        <q-drawer show-if-above v-model="leftDrawerOpen" side="left" bordered>
            <q-list>
                <q-item
                    v-for="(item, index) in routes"
                    :key="index"
                    v-show="item.show"
                    clickable
                    v-ripple
                    @click="openLink(item)"
                >
                    <q-item-section avatar class="text-primary">
                        <q-avatar size="3rem" :icon="item.icon" />
                    </q-item-section>
                    <q-item-section>
                        <span>{{ item.name }}</span>
                    </q-item-section>
                </q-item>
            </q-list>
        </q-drawer>

        <q-page-container>
            <slot />
        </q-page-container>
    </q-layout>
</template>

<script>
export default {
    data() {
        return {
            leftDrawerOpen: false,
            routes: [],
            app_name: "",
        };
    },

    created() {
        this.app_name = this.$page.props.app_name;
        this.routes = this.$page.props.admin_routes;
    },

    methods: {
        toggleLeftDrawer() {
            leftDrawerOpen = !leftDrawerOpen;
        },

        openLink(item) {
            window.location.href = item.route;
        },
    },
};
</script>
