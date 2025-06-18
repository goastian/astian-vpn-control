<template>
    <v-auth-layout>
        <q-page class="q-pa-md column q-gutter-y-lg">
            <v-nav-bar />

            <q-card class="cardHome q-mb-md">
                <q-card-section class="row justify-between items-start">
                    <div class="column items-start q-gutter-y-lg">
                        <div>
                            <h2 class="title">Hi, {{ user.name }}</h2>
                            <p class="text-grey">What are you doing today?</p>
                        </div>
                        <q-btn color="primary" @click="goToPeers">
                            Add new device
                            <q-icon name="mdi-key" class="q-ml-sm" />
                        </q-btn>
                    </div>

                    <!-- RIGHT STATS -->
                    <div
                        class="containerInfoTag row q-gutter-x-lg items-center"
                    >
                        <div class="row q-gutter-x-sm items-center">
                            <q-icon name="mdi-remote-desktop" />
                            <span>Peers</span>
                            <div class="text-weight-bold">
                                <span>{{ user_plan?.used_devices }}</span
                                >/<span v-if="user.id">
                                    <span>{{ user_plan?.total_devices }}</span>
                                </span>
                            </div>
                        </div>
                        <div class="tag-access">Free Access</div>
                    </div>
                </q-card-section>
            </q-card>

            <!-- PEERS -->
            <div class="Peer column q-gutter-y-md">
                <div class="row justify-between items-center">
                    <h3 class="text-h6">
                        <q-icon name="mdi-remote-desktop" /> Peers
                    </h3>
                    <a href="">See all</a>
                </div>

                <div v-if="peers.length === 0" class="text-grey">
                    No VPN devices connected yet! 🌐 Stay private and secure —
                    click
                    <a href="">here</a> to add your first device and unlock your
                    private network!
                </div>

                <div v-else class="peers q-gutter-y-md">
                    <v-card-peer
                        v-for="(item, index) in peers"
                        :key="index"
                        :title="item.name"
                        :server="item.network.server_name"
                        :network="item.network.name"
                        :port="item.network.listen_port"
                        :state="item.active"
                        :peer="item"
                    />
                </div>
            </div>

            <!-- INSTRUCTIONS -->
            <div class="Instructions column q-gutter-y-lg">
                <div class="row justify-between instructionsTitle">
                    <h3>
                        <q-icon name="mdi-information-variant" /> Instructions
                    </h3>
                    <a href="">See all</a>
                </div>

                <div
                    class="instructionsCards grid grid-cols-1 sm:grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4"
                >
                    <v-card-instruction
                        v-for="(item, index) in instructions"
                        :key="index"
                        :title="item.title"
                        :description="item.description"
                        :number="item.number"
                        :image="item.image"
                        :btnTitle="item.btnTitle"
                        :btnUrl="item.btnUrl"
                    />
                </div>
            </div>
        </q-page>
    </v-auth-layout>
</template>

<script>
export default {
    data() {
        return {
            user: {},
            peers: [],
            count: 0,
            instructions: [
                {
                    title: "Create a Peer",
                    description:
                        'Go to the "Peers" section and create a new peer.',
                    image: "/img/key.png",
                    number: 1,
                },
                {
                    title: "Download WireGuard",
                    description: "Get WireGuard from its official website.",
                    image: "/img/WireGuard.png",
                    number: 2,
                    btnTitle: "Download WireGuard",
                    btnUrl: "https://www.wireguard.com/install/",
                },
                {
                    title: "Scan QR code",
                    description:
                        "Download or scan the QR code to configure WireGuard.",
                    image: "/img/QR.png",
                    number: 3,
                },
            ],
        };
    },
    created() {
        this.user = this.$page.props.user;
        this.user_plan = this.$page.props.user_plan;
    },

    mounted() {
        this.getPeers();
    },
    methods: {
        hasGroup(name) {
            return this.user.groups.some(
                (item) => item.slug === name || item.slug === "administrator"
            );
        },
        goToPeers() {
            window.location.href = this.$page.props.routes['wireguard_generator'];
        },
        async getPeers() {
            try {
                const res = await this.$api.get(this.$props.links["peers"]);
                if (res.status === 200) {
                    this.count = res.data.data.length;
                    const visiblePeers = res.data.data.slice(0, 2);
                    this.peers = [...visiblePeers];
                }
            } catch (err) {
                console.error("Error fetching peers:", err);
            }
        },
    },
};
</script>

<style scoped>
.q-card {
    border-radius: 2rem;
}

.title {
    font-size: 2.8rem;
    line-height: 1.1;
    max-width: 340px;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}

.tag-access {
    background-color: var(--green);
    padding: 0.2rem 0.5rem;
    border-radius: 0.4rem;
}

.containerInfoTag {
    align-self: end;
}

.instructionsTitle h3 {
    font-size: 1rem;
}

@media (max-width: 900px) {
    .containerInfoTag {
        align-self: start;
        margin-top: 1rem;
    }
}
</style>
