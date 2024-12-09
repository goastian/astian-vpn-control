<template>
    <v-row class="pa-3">
        <v-col cols="12">
            <h1 class="underline">Dashboard</h1>
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
    data() {
        return {
            peers: [],
        };
    },

    mounted() {
        this.getPeers();
    },

    methods: {
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
