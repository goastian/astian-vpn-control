<template>
    <q-page class="q-pa-md">
        <q-toolbar>
            <q-toolbar-title>Dashboard</q-toolbar-title>
        </q-toolbar>

        <q-card class="q-mb-md">
            <q-card-section class="flex justify-between items-center">
                <div>
                    <h2 class="text-h5">Hi, {{ $user.name }}</h2>
                    <p class="text-grey">What are you doing today?</p>
                </div>
                <q-btn color="primary" @click="goToPeers">
                    Add new device
                    <q-icon name="mdi-key" class="q-ml-sm" />
                </q-btn>
            </q-card-section>
        </q-card>

        <q-card class="q-mb-md">
            <q-card-section>
                <h3 class="text-h6">
                    <q-icon name="mdi-remote-desktop" /> Peers
                </h3>
                <div v-if="peers.length === 0" class="text-grey">
                    No VPN devices connected yet! 🌐 Stay private and
                    secure—click
                    <router-link :to="{ name: 'peers' }" class="text-primary"
                        >here</router-link
                    >
                    to add your first device and unlock your private network!
                </div>
                <q-list v-else>
                    <q-item v-for="(item, index) in peers" :key="index">
                        <q-item-section>
                            <q-item-label>{{ item.name }}</q-item-label>
                            <q-item-label caption>{{
                                item.network.server_name
                            }}</q-item-label>
                        </q-item-section>
                    </q-item>
                </q-list>
            </q-card-section>
        </q-card>

        <q-card>
            <q-card-section>
                <h3 class="text-h6">
                    <q-icon name="mdi-information-variant" /> Instructions
                </h3>
                <q-list>
                    <q-item v-for="(item, index) in instructions" :key="index">
                        <q-item-section>
                            <q-item-label>{{ item.title }}</q-item-label>
                            <q-item-label caption>{{
                                item.description
                            }}</q-item-label>
                        </q-item-section>
                    </q-item>
                </q-list>
            </q-card-section>
        </q-card>
    </q-page>
</template>

<script>
export default {
    inject: ["$user"],
    data() {
        return {
            peers: [],
            instructions: [
                {
                    title: "Create a Peer",
                    description:
                        'Go to the "Peers" section and create a new peer.',
                },
                {
                    title: "Download WireGuard",
                    description: "Get WireGuard from its official website.",
                },
                {
                    title: "Scan QR code",
                    description:
                        "Download or scan the QR code to configure WireGuard.",
                },
            ],
        };
    },
    mounted() {
        this.getPeers();
    },
    methods: {
        goToPeers() {
            this.$router.push({ name: "peers" });
        },
        async getPeers() {
            try {
                const res = await this.$api.get("/api/peers");
                if (res.status === 200) {
                    this.peers = res.data.data;
                }
            } catch (err) {
                console.error(err);
            }
        },
    },
};
</script>

<style scoped>
.q-card {
    border-radius: 10px;
}
</style>
