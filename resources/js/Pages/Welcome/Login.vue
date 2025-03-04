<template>
    <v-app class="bg-slate-100">
        <v-app-bar app elevation="0" class=" pl-4">
            <v-icon icon>
                <img src="/img/icon.webp" class="icon" >
            </v-icon>
            <v-toolbar-title>
                <span class="text-1xl text-gray-500 font-semibold" v-text="app_name"></span>
            </v-toolbar-title>
        </v-app-bar>
        <v-main class="flex align-center">
            <v-container class="bg-white content-container flex flex-column align-center ga-5">
                <div class="flex flex-column align-center ga-2">
                    <h2 class="title" v-text="app_title"></h2>
                    <div class="content">
                        <span>Our VPN offers you the freedom to explore the digital world from anywhere, with advanced protection and access to servers in multiple countries.</span>
                    </div> 
                </div>
                <div>
                    <ul class="flex ga-10">
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
                        <li>
                            <v-btn
                                href="https://astian.org/astian-vpn/"
                                target="_blank"
                                color="pink"
                                variant="outlined"
                                rounded
                                :append-icon="$utils.toKebabCase('mdiListBox')"
                            >
                                Plans
                            </v-btn>
                        </li>
                    </ul>
                </div>
                <div>
                    <img src="/img/World.png" />
                </div>
            </v-container>
        </v-main>
        <v-footer app>
            <v-row>
                <v-col
                    class="text-center" cols="12"
                    v-text="app_footer"
                />
            </v-row>
        </v-footer>
    </v-app>
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

<style scoped>
.title {
    width: 100%;
    min-width: 100px;
    max-width: 660px;
    font-size: 2.4rem;
    text-align: center;
}

.content {
    width: 100%;
    min-width: 100px;
    max-width: 660px;
    text-align: center;
}

img {
    width: 900px;
    object-fit: cover;
    object-position: center;
}

@media (max-width: 480px) {
    .title {
        font-size: 1.2rem;
    }
}
</style>
