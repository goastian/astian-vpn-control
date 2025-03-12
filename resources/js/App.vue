<template>
    <div>
        <div v-if="!loader">
            <router-view></router-view>
        </div>

        <div v-if="loader" class="flex flex-center full-height">
            <q-spinner color="primary" size="3em" />
        </div>
    </div>
</template>

<script>
import { computed } from "vue";

export default {
    data() {
        return {
            user: {},
            app_name: "",
            loader: true,
        };
    },

    provide() {
        return {
            $user: computed(() => this.user),
            $app_name: computed(() => this.app_name),
        };
    },

    created() {
        this.getUser();
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

        async getUser() {
            try {
                const res = await this.$api.get("/user");
                if (res.status == 200) {
                    this.user = res.data;
                    this.loader = false;
                }
            } catch (e) {
                if (e.response && e.response.status == 401) {
                    this.$router.push({ name: "welcome" });
                    this.loader = false;
                }
            }
        },
    },
};
</script>
<style scoped>
.loading-overlay {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100vh;
    background: rgba(0, 0, 0, 0.8); /* Fondo oscuro translúcido */
    display: flex;
    flex-direction: column;
    justify-content: center;
    align-items: center;
    color: white;
    font-size: 1.2em;
    transition: opacity 0.5s ease-in-out;
}

.loading-text {
    margin-top: 10px;
    font-weight: bold;
    letter-spacing: 1px;
    animation: fadeIn 1.5s infinite alternate;
}

@keyframes fadeIn {
    from {
        opacity: 0.5;
    }
    to {
        opacity: 1;
    }
}
</style>
