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
        this.getData();
    },

    methods: {
        getData() {
            this.app_name = document.getElementById("app").dataset.appName;
        },

        addPeer() {
            this.$echo.channel(this.$channels.auth()).listen("Peer", (e) => {});
        },
    },
};
</script>
