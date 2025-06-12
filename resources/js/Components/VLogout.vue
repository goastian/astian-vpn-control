<template>
    <q-btn round flat color="indigo" icon="mdi-account-circle">
        <q-menu fit anchor="bottom end" self="top end">
            <q-card style="min-width: 300px">
                <q-list>
                    <q-item>
                        <q-item-section>
                            <q-item-label>{{ user.email }}</q-item-label>
                            <q-item-label caption
                                >{{ user.name }}
                                {{ user.last_name }}</q-item-label
                            >
                        </q-item-section>
                    </q-item>
                </q-list>

                <q-separator />

                <q-list>
                    <q-item clickable v-ripple @click="dashboard">
                        <q-item-section avatar>
                            <q-icon name="mdi-account-key" />
                        </q-item-section>
                        <q-item-section>
                            <q-item-label>Dashboard</q-item-label>
                        </q-item-section>
                    </q-item>

                    <q-item clickable v-ripple @click="myAccount">
                        <q-item-section avatar>
                            <q-icon name="mdi-home" />
                        </q-item-section>
                        <q-item-section>
                            <q-item-label>My Account</q-item-label>
                        </q-item-section>
                    </q-item>

                    <q-separator />

                    <q-item clickable v-ripple @click="logout">
                        <q-item-section avatar>
                            <q-icon name="mdi-logout" />
                        </q-item-section>
                        <q-item-section>
                            <q-item-label>Logout</q-item-label>
                        </q-item-section>
                    </q-item>
                </q-list>
            </q-card>
        </q-menu>
    </q-btn>
</template>

<script>
export default {
    data() {
        return {
            user: {},
        };
    },

    created() {
        this.user = this.$page.props.user;
    },
    methods: {
        async logout() {
            try {
                const res = await this.$api.post(
                    this.$page.props.routes["logout"]
                );
                if (res.status === 200) {
                    window.location.href = this.$page.props.routes["home"];
                }
            } catch (err) {}
        },

        dashboard() {
            window.location.href = this.$page.props.routes["dashboard"];
        },

        myAccount() {
            window.location.href = process.env.MIX_APP_SERVER;
        },
    },
};
</script>
