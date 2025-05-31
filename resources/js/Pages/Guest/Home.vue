<template>
    <q-layout view="lHh Lpr lFf">
        <!-- App Bar -->
        <q-header class="text-grey-9 q-px-md">
            <q-toolbar>
                <q-avatar>
                    <img src="/img/icon.webp" class="icon" />
                </q-avatar>
                <q-toolbar-title class="text-1xl font-semibold">
                    {{ app_name }}
                </q-toolbar-title>
            </q-toolbar>
        </q-header>

        <!-- Main Content -->
        <q-page-container>
            <q-page class="flex flex-center">
                <q-card
                    class="content-container flex column items-center q-pa-lg q-gutter-lg"
                >
                    <div class="flex column items-center q-gutter-sm">
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
    data() {
        return {
            app_name: "",
            user: {},
        };
    },

    mounted() {
        this.app_name = this.$page.props.app_name;
    },

    methods: {
        async redirect() {
            window.location.href = "/redirect";
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
