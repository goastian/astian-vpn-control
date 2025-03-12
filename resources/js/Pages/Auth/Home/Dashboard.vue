<template>
    <q-page class="q-pa-md column q-gutter-y-sm">
        <v-nav-bar />
        <div>
            <q-toolbar>
                <q-toolbar-title>Dashboard</q-toolbar-title>
            </q-toolbar>

            <div class="containerHome row">
                <q-card
                    class="cardHome col-7 q-mb-lg row justify-center items-center"
                >
                    <q-card-section
                        class="containerInfo col row justify-between items-start"
                    >
                        <div class="column items-start q-gutter-y-lg">
                            <div>
                                <h2 class="title">Hi, {{ $user.name }}</h2>
                                <p class="text-grey">
                                    What are you doing today?
                                </p>
                            </div>
                            <q-btn color="primary" @click="goToPeers">
                                Add new device
                                <q-icon name="mdi-key" class="q-ml-sm" />
                            </q-btn>
                        </div>
                        <div class="containerInfoTag row q-gutter-x-lg">
                            <div class="row q-gutter-x-sm items-center">
                                <span>
                                    <q-icon name="mdi-remote-desktop" />
                                    Peers
                                </span>
                                <div>
                                    <span>{{ count }}</span>
                                    <span>/</span>
                                    <template v-if="$user.id">
                                        <span v-if="hasGroup('administrator')"
                                            >2</span
                                        >
                                        <span v-if="hasGroup('administrator')"
                                            >10</span
                                        >
                                    </template>
                                </div>
                            </div>
                            <div class="tag-access">
                                <span>Free Access</span>
                            </div>
                        </div>
                    </q-card-section>
                </q-card>

                <div class="Peer col column q-gutter-y-md q-pa-md">
                    <div class="row justify-between">
                        <h3 class="text-h6">
                            <q-icon name="mdi-remote-desktop" /> Peers
                        </h3>
                        <span>
                            <router-link :to="{ name: 'peers' }" class="enlace"
                                >See All</router-link
                            >
                        </span>
                    </div>
                    <div v-if="peers.length === 0" class="text-grey">
                        No VPN devices connected yet! 🌐 Stay private and
                        secure—click
                        <router-link
                            :to="{ name: 'peers' }"
                            class="text-primary"
                            >here</router-link
                        >
                        to add your first device and unlock your private
                        network!
                    </div>
                    <div v-else class="peers q-gutter-y-md">
                        <v-card-peer
                            v-for="(item, index) in peers"
                            key="index"
                            :title="item.name"
                            :server="item.network.server_name"
                            :network="item.network.name"
                            :port="item.network.listen_port"
                            :state="item.active"
                            :peer="item"
                            class=""
                        />
                    </div>
                </div>
            </div>
        </div>
        <div class="Instructions column q-gutter-y-lg">
            <div class="row justify-between instructionsTitle">
                <h3>
                    <q-icon name="mdi-information-variant" />
                    instructions
                </h3>
                <span>
                    <router-link :to="{ name: 'instructions' }" class="enlace"
                        >See All</router-link
                    >
                </span>
            </div>
            <div
                class="instructionsCards grid grid-cols-1 sm:grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4"
            >
                <div v-for="(item, index) in instructions" :key="index">
                    <v-card-instruction
                        :title="item.title"
                        :description="item.description"
                        :number="item.number"
                        :image="item.image"
                        :btnTitle="item.btnTitle"
                        :btnUrl="item.btnUrl"
                    />
                </div>
            </div>
        </div>
    </q-page>
</template>

<script>
export default {
    inject: ["$user"],
    data() {
        return {
            peers: [],
            count: 0,
            instructions: [
                {
                    title: "Create a Peer",
                    description:
                        'Go to the "Peers" section and create a new peer.',
                    image: "img/key.png",
                    number: 1,
                },
                {
                    title: "Download WireGuard",
                    description: "Get WireGuard from its official website.",
                    image: "img/WireGuard.png",
                    number: 2,
                    btnTitle: "Download WireGuard",
                    btnUrl: "https://www.wireguard.com/install/",
                },
                {
                    title: "Scan QR code",
                    description:
                        "Download or scan the QR code to configure WireGuard.",
                    image: "img/QR.png",
                    number: 3,
                },
            ],
        };
    },
    mounted() {
        this.getPeers();
    },
    methods: {
        hasGroup(name) {
            return this.$user.groups.some(
                (item) => item.slug == name || item.slug == "administrator"
            );
        },

        goToPeers() {
            this.$router.push({ name: "peers" });
        },
        async getPeers() {
            try {
                const res = await this.$api.get("/api/peers");
                if (res.status === 200) {
                    this.count = res.data.data.length;
                    if (res.data.data.length != 0) {
                        let count =
                            res.data.data.length < 2 ? res.data.data.length : 2;
                        for (let i = 0; i < count; i++) {
                            this.peers.push(res.data.data[i]);
                        }
                    }
                }
            } catch (err) {}
        },
    },
};
</script>

<style scoped>
.q-card {
    border-radius: 2rem;
}

.containerHome {
    height: 100%;
    min-height: 280px;
    max-height: 400px;
    gap: 5rem;
}

.containerInfo {
    width: 100%;
    min-width: 100px;
    max-width: 600px;
}

.title {
    width: 340px;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
    display: block;
    font-size: 2.8rem;
    line-height: 1.1;
}

.tag-access {
    background-color: var(--green);
    padding: 0.2rem 0.5rem;
    border-radius: 0.4rem;
}

.instructions {
    display: flex;
    flex-direction: column;
}

.instructionsTitle > h3 {
    font-size: 1rem;
}

.peers {
    display: flex;
    flex-direction: column;
    flex-wrap: no-wrap;
}

.enlace {
    color: var(--blue);
}

@media (max-width: 1200px) {
    .cardHome {
        min-width: 200px;
        max-width: 500px;
    }

    .peers {
        min-width: 300px;
    }
}

@media (max-width: 1080px) {
    .cardHome {
        width: 100%;
        min-width: 200px;
        max-width: 400px;
    }

    .peers {
        min-width: auto;
    }
}

@media (max-width: 900px) {
    .containerHome {
        display: flex;
        gap: 0;
        max-height: none;
    }

    .cardHome {
        width: 100%;
        max-width: 100%;
        min-height: 300px;
    }

    .containerInfo {
        display: flex;
        flex-direction: column;
        justify-content: center;
        gap: 1rem;
        height: 100%;
    }

    .containerInfoTag {
        align-self: end;
    }
}
</style>
