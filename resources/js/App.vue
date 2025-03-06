<template>
    <div>
        <router-view></router-view>
    </div>
</template>

<script>
import { computed } from "vue";

export default {
    data() {
        return {
            user: {},
            app_name: "",
        };
    },

    provide() {
        return {
            $user: computed(() => this.user),
            $app_name: computed(() => this.app_name),
        };
    },

    mounted() {
        //Do not remove this line
        // this.addPeer();
        this.getUser();
        this.getData();
    },

    methods: {
        
        getData() {
            this.app_name = document.getElementById("app").dataset.appName;
        },

        async getUser() {
            try {
                const res = await this.$api.get("/user");
                if (res.status == 200) {
                    this.user = res.data;
                }
            } catch (error) {
                if (error.response.status == 401) {
                    this.user = {};

                    let meta = this.$route.meta;

                    if (meta && meta.auth) {
                        this.$router.push({ name: "welcome" });
                    }
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
