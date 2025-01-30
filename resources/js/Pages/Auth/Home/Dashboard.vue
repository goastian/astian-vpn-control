<template>
    <div class="flex flex-column ga-12">
        <div>
            <v-row>
                <v-col cols="12">
                    <h2 class="">Dashboard</h2>
                </v-col>
            </v-row>

            <v-row
                class="row-info flex"
            >
                <v-col cols="12" md="7" class="flex rounded-xl border-thin justify-center items-center elevation-1" border="opacity-50 sm">
                    <v-col cols="12" class="info-content flex items-start justify-between">
                        <div class="flex flex-column items-start">
                            <div>
                                <h2 class="title">Hi, {{ `${$user.name}` }}</h2>
                                <p class="text-grey-darken-1">What are you doing today?</p>
                            </div>
                            <v-btn color="blue-darken-1" @click="goToPeers">
                                Add new device
                                <v-icon icon="mdi-key"></v-icon>
                            </v-btn>
                        </div>
                        <div class="text-grey-darken-3 flex ga-4 align-center">
                            <div class="flex align-center ga-2">
                                <span>
                                    <v-icon icon="mdi-remote-desktop" />
                                    Peers
                                </span>
                                <div>
                                    <span>{{ count }}</span>
                                    <span>/</span>
                                    <span v-if="$user.roles[0].name == 'client'">2</span>
                                    <span v-if="$user.roles[0].name == 'admin'">10</span>
                                </div>
                            </div>
                            <div class="tag-access">
                                <span>Free Access</span>
                            </div>
                        </div>
                    </v-col>
                </v-col>
                <v-col class="flex flex-column ga-4">
                    <div class="flex justify-between text-grey-darken-3">
                        <h3>
                            <v-icon icon="mdi-remote-desktop" />
                            Peers
                        </h3>
                        <span class="text-light-blue-darken-3 hover-underline">
                            <router-link :to="{ name: 'peers' }">See All</router-link>
                        </span>
                    </div>
                    <div v-if="peers.length == 0">
                        <p class="text-grey-darken-2">
                            No VPN devices connected yet! 🌐 Stay private and secure—click
                            <router-link
                                class="text-link text-blue-600"
                                :to="{ name: 'peers' }"
                            >here</router-link>
                            to add your first device and unlock your private network!
                        </p>
                    </div>
                    <div class="flex flex-column ga-2">
                        <v-card-peer
                            v-for="(item, index) in peers"
                            :key="index"
                            :title="item.name"
                            :server="item.network.server_name"
                            :network="item.network.name"
                            :port="item.network.listen_port"
                            :state="item.active"
                            :peer="item"
                            class="elevation-2"
                        />
                    </div>
                </v-col>
            </v-row>
        </div>
        <v-row>
            <v-col cols="12">
                <div class="flex justify-between text-grey-darken-3">
                    <h3>
                        <v-icon icon="mdi-information-variant" />
                        Instructions
                    </h3>
                    <span class="text-light-blue-darken-3 hover-underline">
                        <router-link :to="{ name: 'instructions' }">See All</router-link>
                    </span>
                </div>
            </v-col>
            <v-col cols="12" class="d-flex flex-column flex-md-row">
                <v-col
                    v-for="(item, index) in instructions"
                >
                    <v-card-instruction
                        :key="index"
                        :title="item.title"
                        :description="item.description"
                        :number="item.number"
                        :image="item.image"
                    >
                    </v-card-instruction>
                </v-col>
            </v-col>
        </v-row>
    </div>
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
                    title: 'create a Peer',
                    description: 'Go to the "Peers" section. Click on "Create New Peer," fill in the required information, and save the configuration.',
                    image: 'img/key.png',
                    number: 1,
                },
                {
                    title: 'Download WireGuard',
                    description: 'Go to the official WireGuard website and download the appropriate application for your device (Windows, macOS, Android, iOS, or Linux). Install the application following the instructions.',
                    image: 'img/WireGuard.png',
                    number: 2
                },
                {
                    title: 'Scan QR code.',
                    description: 'Once the peer is created, download the configuration file or scan the provided QR code to easily set it up in WireGuard.',
                    image: 'img/QR.png',
                    number: 3
                }
            ]
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

                if (res.status == 200) {
                    this.count = res.data.data.length;
                    if (res.data.data.length != 0){
                        let count = res.data.data.length < 3 ? res.data.data.length : 3;
                        for(let i = 0; i < count; i++) {
                            this.peers.push(res.data.data[i]);
                        }
                    }
                    //this.peers = res.data.data;
                }
            } catch (err) {}
        },
    },
};
</script>

<style scoped>

.row-info {
    min-height: 250px;
    gap: 5rem;
}

.info-content {
    width: 100%;
    min-width: 100px;
    max-width: 600px;
}

.info-content > div:first-child {
    gap: 2rem;
}

.title {
    font-size: 3rem;
    line-height: 1;
}

.tag-access {
    background-color: #65EBBA;
    padding: .2rem .5rem;
    border-radius: .4rem;
}

.hover-underline:hover {
    text-decoration: underline;
}

@media (max-width: 960px) {
    .row-info {
        gap: 1.5rem;
    }
}

@media (max-width: 480px) {

    .title {
        font-size: 2rem;
    }

    .row-info {
        gap: 1rem;
    }

    .info-content {
        display: flex;
        flex-direction: column;
        gap: 1.5rem;
    }

    .info-content > div:first-child {
        gap: 1rem;
    }

    .info-content div:last-child {
        align-self: end;
    }
}

</style>
