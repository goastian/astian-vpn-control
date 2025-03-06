<template>
    <main>
        <router-view></router-view>
    </main>
</template>

<script>
export default {
    data() {
        return {};
    },

    mounted() {},

    methods: {
        redirect() {
            this.$api
                .get("/user")
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
