<template>
    <v-row class="pa-3">
        <v-col cols="12">
            <v-filter :params="['name']" @change="searcher"></v-filter>
        </v-col>

        <v-col cols="12" class="d-flex justify-between">
            <h1 class="text-gray-600 font-bold">Dashboard</h1>
            <v-create @created="getPeers"></v-create>
        </v-col>

        <v-col cols="12" class="text-center">
            <a
                class="mt-8 text-blue-600 text-lg font-semibold hover:text-blue-800 underline transition duration-200 ease-in-out transform hover:scale-105"
                href="https://www.wireguard.com/install/"
                target="_blank"
            >
                Download the client to your device
            </a>

            <p class="mt-4 text-gray-500 text-sm">
                Once you have the client installed, you'll be able to connect
                securely to our private network. If you need assistance, feel
                free to reach out to our support team.
            </p>
        </v-col>

        <v-col cols="12" md="6" v-for="(item, index) in peers" :key="index">
            <v-card>
                <v-card-subtitle class="pt-2">
                    <div class="flex justify-between">
                        <span class="capitalize">
                            {{ item.name }}
                        </span>
                        <div>
                            {{ item.network.name }}
                            <span class="font-bold text-2xl">
                                <v-tooltip
                                    text="The server is under maintenance, we
                                            will be back online soon"
                                >
                                    <template v-slot:activator="{ props }">
                                        <v-icon
                                            v-bind="props"
                                            v-if="item.stand_by"
                                            icon="mdi-traffic-light"
                                            color="yellow-accent-4"
                                        ></v-icon>
                                    </template>
                                </v-tooltip>
                            </span>
                        </div>
                    </div>
                </v-card-subtitle>

                <v-card-text class="text-center">
                    <span class="text-4xl">
                        <v-icon
                            color="blue-accent-2"
                            icon="mdi-security-network"
                        >
                        </v-icon>
                    </span>
                </v-card-text>

                <v-card-actions class="flex justify-between">
                    <v-toggle @updated="getPeers" :peer="item"></v-toggle>

                    <v-delete @deleted="getPeers" :peer="item"></v-delete>
                </v-card-actions>
            </v-card>
        </v-col>

        <v-col
            v-if="peers.length == 0"
            cols="12"
            class="py-4 text-center min-h-screen flex flex-col items-center justify-center bg-gradient-to-r from-blue-50 via-blue-100 to-blue-200 rounded-lg shadow-xl"
        >
            <p
                class="text-gray-800 text-xl md:text-1xl font-medium leading-relaxed py-1 max-w-xl mx-auto"
            >
                No VPN devices connected yet! 🌐 Stay private and secure—click
                below to add your first device and unlock your private network.
                It's quick, easy, and ensures your internet activity stays
                private.
            </p>

            <v-create @created="getPeers"></v-create>
        </v-col>

        <v-col cols="12">
            <v-pagination
                v-model="search.page"
                :length="search.total_pages"
                :total-visible="7"
            ></v-pagination>
        </v-col>
    </v-row>
</template>

<script>
import VCreate from "../Peer/Create.vue";
import VToggle from "../Peer/Toggle.vue";
import VDelete from "../Peer/Delete.vue";

export default {
    inject: ["$user"],

    components: {
        VCreate,
        VToggle,
        VDelete,
    },

    data() {
        return {
            peers: [],
            search: {
                page: 1,
                per_page: 50,
                total_pages: 0,
            },

            params: {},
        };
    },

    mounted() {
        this.getPeers();
    },

    methods: {
        searcher(event) {
            this.params = event;

            this.getPeers();
        },

        async getPeers() {
            try {
                const res = await this.$api.get("/api/peers", {
                    params: this.params,
                });

                if (res.status === 200) {
                    this.peers = res.data.data;
                    this.search = res.data.meta;
                }
            } catch (err) {}
        },
    },
};
</script>
