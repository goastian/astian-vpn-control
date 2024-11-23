<template>
    <div class="flex flex-col h-screen justify-between bg-slate-100">
        <nav class="flex align-middle justify-between px-2 py-1">
            <ul class="inline">
                <li class="text-2xl text-gray-500 font-semibold">Welcome</li>
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
            <p class="text-3xl text-gray-400">
                Virtual Private Network
            </p>
        </div>
        <div class="py-4 text-center text-gray-400">By Astian, Inc</div>
    </div>
</template>

<script>
export default {
    inject: ["$user"],

    methods: {
        redirect() {
            this.$server
                .get("/api/gateway/user")
                .then((res) => {
                    this.$router.push({ name: "home" });
                })
                .catch((err) => {
                    window.location.href = "/redirect";
                });
        },
    },
};
</script>
