<template>
    <q-layout view="hHh lpR fFf">
        <q-header reveal class="bg-primary text-white" height-hint="98">
            <q-toolbar>
                <q-btn dense flat round icon="menu" @click="toggleLeftDrawer" />

		<q-toolbar-title> Settings </q-toolbar-title>
            </q-toolbar>
        </q-header>

        <q-drawer v-model="leftDrawerOpen" side="left" elevated>
            <q-list v-for="(item, index) in menu">
                <q-item clickable v-ripple @click="openRoute(item.route)">
                    <q-item-section avatar>
                        <span
                            class="text-3xl text-gray-500"
                            :class="item.icon"
                        ></span>
                    </q-item-section>

                    <q-item-section>{{ item.name }}</q-item-section>
                </q-item>
            </q-list>
        </q-drawer>

        <q-page-container>
            <div class="px-2 py-2">
                <router-view />
            </div>
        </q-page-container>
    </q-layout>
</template>

<script>
export default {
    data() {
        return {
            leftDrawerOpen: true,
            rightDrawerOpen: true,
            menu: [
                {
                    name: "General",
                    route: "settings.general",
                    icon: "mdi mdi-view-list",
                },
                {
                    name: "Plans",
                    route: "settings.plans",
                    icon: "mdi mdi-shield-account-outline",
                },
                {
                    name: "Home",
                    route: "home",
                    icon: "mdi mdi-home-automation",
                },
            ],
        };
    },
    methods: {
        toggleLeftDrawer() {
            this.leftDrawerOpen = !this.leftDrawerOpen;
        },
        toggleRightDrawer() {
            this.rightDrawerOpen = !this.rightDrawerOpen;
        },

        openRoute(name) {
            this.$router.push({ name: name });
        },
    },
};
</script>
