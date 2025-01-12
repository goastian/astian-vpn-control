<template>
    <div class="bg-gray-50 text-gray-900 font-sans">
        <header
            class="flex items-center justify-between px-6 py-4 shadow-lg bg-blue-500"
        >
            <h1
                class="text-2xl font-bold text-white tracking-wider"
                v-text="app_name"
            ></h1>
            <v-btn
                color="light-blue-darken-2"
                class="bg-blue-600 text-white hover:bg-blue-700"
                @click="redirect"
                elevation="2"
                rounded
                :append-icon="$utils.toKebabCase('mdiLogin')"
            >
                Sign In
            </v-btn>
        </header>

        <main>
            <router-view></router-view>
        </main>

        <footer class="py-6 bg-blue-600 text-center text-white">
            <p class="text-sm" v-text="app_footer"></p>
        </footer>
    </div>
</template>

<script>
export default {
    data() {
        return {
            app_name: null,
            app_footer: null,
        };
    },

    mounted() {
        this.getKey();
    },

    methods: {
        async getKey() {
            this.app_name = await this.getSetting("app.name");
            this.app_footer = await this.getSetting("app.footer");
        },

        redirect() {
            this.$server
                .get("/api/gateway/user")
                .then(() => {
                    this.$router.push({ name: "home" });
                })
                .catch(() => {
                    window.location.href = "/redirect";
                });
        },

        /**
         * Fetch a specific setting by key
         * @param key
         * @returns {Promise<string>}
         */
        async getSetting(key) {
            try {
                const res = await this.$api.get("/api/settings", {
                    params: {
                        key: key,
                    },
                });
                if (res.status === 200) {
                    return res.data.data[0].value;
                }
            } catch (err) {
                console.error(err);
            }
        },
    },
};
</script>
