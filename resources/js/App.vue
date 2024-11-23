<template>
    <v-app>
        <router-view></router-view>
    </v-app>
</template>

<script>
import { computed } from "vue";

export default {
    data() {
        return {
            user: {},
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
        async getUser() {
            try {
                const res = await this.$server.get("/api/gateway/user");
                if (res.status == 200) {
                    this.user = res.data;
                }
            } catch (error) {
                if (error.response.status == 401) {
                    this.user = {};
                    this.$router.push({ name: "welcome" });
                }
                if (error.response.status == 404) {
                    this.$notification.error(error.response.data.message);
                }
                if (error.response.status == 500) {
                    this.$notification.error(error.response.data.message);
                }
            }
        },

        addPeer() {
            this.$echo.channel(this.$channels.auth()).listen("Peer", (e) => {});
        },
    },
};
</script>
