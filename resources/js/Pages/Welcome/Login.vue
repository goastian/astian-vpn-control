<template>
    <div class="flex flex-col min-h-screen bg-gray-50">
        <!-- Header -->
        <header class="text-center bg-blue-500 text-white py-4">
            <h1 class="text-3xl font-bold" v-text="app_name"></h1>
            <p class="text-lg" v-text="app_title"></p>
        </header>

        <!-- Why Choose Our VPN -->
        <section class="px-4 max-w-3xl mx-auto text-center mt-4">
            <h2 class="text-2xl font-semibold text-blue-700">
                Why Choose Our VPN?
            </h2>
            <p class="mt-2 text-gray-700">
                Your online security is our top priority. With Secure VPN, you
                can browse the internet safely and anonymously.
            </p>
            <ul class="mt-5 space-y-4 text-left">
                <li class="flex items-start">
                    <strong class="font-semibold text-blue-700"
                        >Secure Connection:</strong
                    >
                    <span class="ml-2"
                        >Encrypt your internet traffic and protect your data
                        from hackers.</span
                    >
                </li>
                <li class="flex items-start">
                    <strong class="font-semibold text-blue-700"
                        >Access Anywhere:</strong
                    >
                    <span class="ml-2"
                        >Enjoy unrestricted access to global content, bypassing
                        geo-restrictions.</span
                    >
                </li>
                <li class="flex items-start">
                    <strong class="font-semibold text-blue-700"
                        >Privacy First:</strong
                    >
                    <span class="ml-2"
                        >We don’t track your activity. Stay truly anonymous
                        online.</span
                    >
                </li>
                <li class="flex items-start">
                    <strong class="font-semibold text-blue-700"
                        >Easy to Use:</strong
                    >
                    <span class="ml-2"
                        >One-click setup and seamless performance on all your
                        devices.</span
                    >
                </li>
            </ul>
        </section>

        <section class="text-center">
            <button
                class="bg-blue-accent-3 mt-4 text-white px-6 py-3 rounded-lg text-lg hover:bg-blue-700"
                @click="redirect"
            >
                Get Started Now
            </button>
        </section>

        <section class="py-8 px-4 max-w-3xl mx-auto text-center">
            <h2 class="text-2xl font-semibold text-blue-700">How It Works</h2>
            <p class="mt-4 text-gray-700">
                Our VPN creates a secure and encrypted tunnel between your
                device and the internet. This protects your online identity and
                ensures no one can monitor your activity.
            </p>
        </section>

        <footer class="bg-blue-500 text-white text-center py-4 mt-auto">
            <p v-text="app_footer"></p>
        </footer>
    </div>
</template>

<script>
import { computed } from "vue";

export default {
    inject: ["$user"],

    data() {
        return {
            setting: [],
            app_name: "",
            app_footer: "",
        };
    },

    created() {
        this.getSetting();
    },

    mounted() {
        this.app_name = computed(() => this.item("app.name"));
        this.app_title = computed(() => this.item("app.title"));
        this.app_footer = computed(() => this.item("app.footer"));
    },

    methods: {
        async getSetting() {
            try {
                const res = await this.$api.get("/api/settings");
                if (res.status == 200) {
                    this.setting = res.data;
                }
            } catch (err) {
                console.log(err);
            }
        },

        item(key) {
            try {
                const data = this.setting.find((item) => item.key == key);
                return data.value;
            } catch (error) {}
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
    },
};
</script>
