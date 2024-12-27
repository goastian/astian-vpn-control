<template>
    <div class="flex flex-col h-screen justify-between bg-slate-100">
        <nav class="flex align-middle justify-between px-2 py-1">
            <ul class="inline">
                <li
                    class="text-2xl text-gray-500 font-semibold"
                    v-text="app_name"
                ></li>
            </ul>
            <ul class="me-2 my-2 inline">
                <li>
                    <v-btn
                        @click="redirect"
                        variant="tonal"
                        color="pink-lighten-1"
                        rounded
                        :append-icon="$utils.toKebabCase('mdiLogin')"
                    >
                        Sign In
                    </v-btn>
                </li>
            </ul>
        </nav>
        <div class="h-auto text-center">
            <p class="text-3xl text-gray-400" v-text="app_title"></p>
        </div>
        <div class="py-4 text-center text-gray-400" v-text="app_footer"></div>
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
