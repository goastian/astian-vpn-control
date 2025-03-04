<template>
    <v-menu v-model="menu" :close-on-content-click="false" location="bottom">
        <template v-slot:activator="{ props }">
            <v-btn color="indigo" v-bind="props" icon>
                <v-icon
                    :icon="$utils.toKebabCase('mdi-account-circle')"
                    color="indigo-accent-2"
                    size="30"
                ></v-icon>
            </v-btn>
        </template>

        <v-card min-width="300">
            <v-list>
                <v-list-item
                    :subtitle="`${$user.name} ${$user.last_name}`"
                    :title="$user.email"
                >
                </v-list-item>
            </v-list>
 
            <v-list>
                <v-list-item
                    :prepend-icon="$utils.toKebabCase('mdiAccountKey')"
                    subtitle="My Account"
                    title="Administration information"
                    @click="myAccount"
                >
                </v-list-item>
            </v-list>

            <v-divider></v-divider>

            <v-list>
                <v-list-item
                    title="Logout"
                    :prepend-icon="$utils.toKebabCase('mdiLogout')"
                    @click="logout"
                >
                </v-list-item>
            </v-list>
        </v-card>
    </v-menu>
</template>
<script>
export default {
    inject: ["$user"],

    data() {
        return {
            menu: false,
        };
    },

    methods: {
        async logout() {
            try {
                const res = await this.$server.post("/api/gateway/logout");

                if (res.status == 200) {
                    this.$router.push({ name: "welcome" });
                }
            } catch (err) {}
        },

        myAccount() {
            window.location.href = process.env.MIX_APP_SERVER;
        },
    },
};
</script>
