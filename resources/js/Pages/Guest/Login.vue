<template>
    <div
        class="flex flex-col min-h-screen bg-gradient-to-br from-blue-50 to-blue-200"
    >
        <main
            class="flex-1 flex flex-col items-center justify-center text-center space-y-6 px-4"
        >
            <h2
                class="text-4xl md:text-5xl font-extrabold text-blue-600"
                v-text="app_title"
            ></h2>
            <p class="text-lg text-blue-500 max-w-lg">
                Unlock the full potential of our platform with intuitive tools
                and services. Join us to streamline your workflow effortlessly.
            </p>
            <v-btn large elevation="3" color="blue-accent-3" @click="explore">
                Explore Now
            </v-btn>
        </main>
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
            this.app_title = await this.getSetting("app.title");
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

        explore() {
            this.$router.push({ name: "about" });
        },
    },
};
</script>

<style scoped>
button:hover {
    transform: scale(1.05);
    transition: transform 0.2s ease-in-out;
}
</style>
