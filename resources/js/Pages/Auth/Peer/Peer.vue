<template>
    <v-data-table :headers="headers" :items="peers">
        <template #top>
            <div class="row d-flex justify-between py-4 px-4">
                <h1 class="text-subtitle-1 underline">List of Peers</h1>
                <v-create @created="getPeers"></v-create>
            </div>
        </template>
        <template #item.active="{ item }">
            <v-toggle @updated="getPeers" :peer="item"></v-toggle>
        </template>
        <template #item.actions="{ item }">
            <v-delete @deleted="getPeers" :peer="item"></v-delete>
        </template>
    </v-data-table>
</template>
<script>
import VCreate from "./Create.vue";
import VToggle from "./Toggle.vue";
import VDelete from "./Delete.vue";

export default {
    components: {
        VCreate,
        VToggle,
        VDelete,
    },

    data() {
        return {
            peers: [],
            headers: [
                {
                    title: "Status",
                    align: "center",
                    sortable: false,
                    key: "active",
                },
                {
                    title: "Device",
                    align: "center",
                    sortable: false,
                    key: "name",
                },
                {
                    title: "Created",
                    align: "center",
                    sortable: false,
                    key: "created",
                },
                {
                    title: "Actions",
                    align: "center",
                    sortable: false,
                    key: "actions",
                },
            ],
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
            } catch (err) {
                if (err.response.status == 403) {
                    this.$notification.error(err.response.data.message);
                }

                if (err.response.status == 404) {
                    this.$notification.error(err.response.data.message);
                }

                if (err.response.status == 422) {
                    this.$notification.error(err.response.data.message);
                }
                this.dialog = false;
            }
        },
    },
};
</script>
