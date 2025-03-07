<template>
    <q-layout view="lHh Lpr lFf" class="bg-slate-100">
        <!-- App Bar -->
        <q-header class="bg-white text-grey-9 q-px-md">
            <q-toolbar>
                <q-avatar>
                    <img src="/img/icon.webp" class="icon" />
                </q-avatar>
                <q-toolbar-title class="text-1xl text-gray-500 font-semibold">
                    {{ app_name }}
                </q-toolbar-title>
            </q-toolbar>
        </q-header>

        <!-- Main Content -->
        <q-page-container>
            <q-page class="flex flex-center">
                <q-card
                    class="bg-white content-container flex column items-center q-pa-lg q-gutter-lg"
                >
                    <div class="flex column items-center q-gutter-sm">
                        <h2 class="title">{{ app_title }}</h2>
                        <div class="content text-center">
                            <span>
                                Our VPN offers you the freedom to explore the
                                digital world from anywhere, with advanced
                                protection and access to servers in multiple
                                countries.
                            </span>
                        </div>
                    </div>

                    <div>
                        <q-btn-group push>
                            <q-btn
                                @click="redirect"
                                color="pink-5"
                                rounded
                                icon="mdi-login"
                            >
                                Sign In
                            </q-btn>
                            <q-btn
                                href="https://astian.org/astian-vpn/"
                                target="_blank"
                                outline
                                color="pink"
                                icon="mdi-list-box"
                            >
                                Plans
                            </q-btn>
                        </q-btn-group>
                    </div>

                    <div>
                        <img
                            src="/img/World.png"
                            class="full-width responsive-img"
                        />
                    </div>
                </q-card>
            </q-page>
        </q-page-container>
    </q-layout>
</template>

<script>
export default {
    inject: ["$user"],
    data() {
        return {
            setting: [],
            app_name: "",
            app_footer: "",
            app_title: "",
        };
    },
    methods: {
        async redirect() {
            try {
                const res = await this.$api.get("/user");
                if (res.status == 200) {
                    this.$router.push({ name: "home" });
                }
            } catch (error) {
                window.location.href = "/redirect";
            }
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

.responsive-img {
    max-width: 100%;
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
