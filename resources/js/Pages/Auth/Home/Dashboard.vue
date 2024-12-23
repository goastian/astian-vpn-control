<template>
    <v-row class="pa-3">
        <v-col cols="12" class="d-flex justify-between">
            <h1 class="underline">Dashboard</h1>
            <v-btn variant="tonal" color="blue-darken-1" @click="goToPeers">
                Add new device
                <v-icon icon="mdi-key"> </v-icon>
            </v-btn>
        </v-col>
        <v-col
            v-if="peers.length == 0"
            cols="12"
            class="flex py-5 text-center justify-center min-h-screen"
        >
            <p class="text-gray-600 py-5 text-center">
                No VPN devices connected yet! 🌐 Stay private and secure—click
                <router-link
                    class="text-link text-blue-600"
                    :to="{ name: 'peers' }"
                >
                    here
                </router-link>
                to add your first device and unlock your private network!
            </p>
        </v-col>

        <v-col
            cols="12"
            md="6"
            lg="3"
            v-for="(item, index) in peers"
            :key="index"
        >
            <v-card loading variant="tonal" color="blue-accent-2">
                <v-card-title>
                    <div class="d-flex align-center justify-between p-1">
                        <span class="h5 text-uppercase">
                            {{ item.name }}
                        </span>
                        <span>
                            {{ item.network.server_name }}
                            <v-icon
                                :icon="$utils.toKebabCase('mdiAccessPoint')"
                                :color="
                                    item.active
                                        ? 'green-darken-1'
                                        : 'red-accent-2'
                                "
                            ></v-icon>
                        </span>
                    </div>
                </v-card-title>
                <v-card-text>
                    <span>
                        <strong> Interface </strong>{{ item.network.name }}
                    </span>
                    -
                    <span>
                        <strong> Listen Port </strong>
                        {{ item.network.listen_port }}
                    </span>
                </v-card-text>
            </v-card>
        </v-col>
    </v-row>
</template>
<script>
export default {
    inject: ["$user"],

    data() {
        return {
            peers: [],
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
                    this.peers = res.data.data;
                }
            } catch (err) {}
        },
    },
};
</script>
