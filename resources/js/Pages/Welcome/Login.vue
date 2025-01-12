<template>
    <div
        class="flex flex-col min-h-screen justify-between bg-gradient-to-br from-blue-50 to-blue-200"
    >
        <!-- Header -->
        <header
            class="flex items-center justify-between px-6 py-4 shadow-md bg-blue-400"
        >
            <h1 class="text-2xl font-bold text-blue-600" v-text="app_name"></h1>
            <v-btn
                color="light-blue-darken-2"
                @click="redirect"
                elevation="2"
                rounded
                :append-icon="$utils.toKebabCase('mdiLogin')"
            >
                Sign In
            </v-btn>
        </header>

        <!-- Main Content -->
        <main
            class="flex-1 flex flex-col items-center justify-center px-4 text-center"
        >
            <h2
                class="text-4xl font-extrabold text-blue-700"
                v-text="app_title"
            ></h2>
            <p class="mt-4 text-lg text-blue-600">
                Welcome to our platform! Manage your services efficiently with
                our streamlined dashboard.
            </p>
            <v-btn
                large
                elevation="3"
                rounded
                color="light-blue-darken-2"
                @click="explore"
            >
                Explore Now
            </v-btn>
        </main>

        <!-- Footer -->
        <footer class="py-4 bg-blue-700 text-center text-white">
            <p class="text-sm" v-text="app_footer"></p>
        </footer>
    </div>
</template>

<script>
export default {
    inject: ["$user"],

    data() {
        return {
            app_name: "",
            app_title: "",
            app_footer: "",
        };
    },

    mounted() {
        this.getKey();
    },

    methods: {
        /**
         * Fetch application keys
         */
        async getKey() {
            this.app_name = await this.getSetting("app.name");
            this.app_title = await this.getSetting("app.title");
            this.app_footer = await this.getSetting("app.footer");
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

        explore() {
            this.$router.push({ name: "explore" });
        },
    },
};
</script>

<style scoped lang="css">
html {
    font-family: "Inter", sans-serif;
}

h1,
h2 {
    letter-spacing: -0.5px;
}

main p {
    max-width: 600px;
    margin: 0 auto;
}

button:hover {
    transform: scale(1.05);
    transition: transform 0.2s ease-in-out;
}
</style>
