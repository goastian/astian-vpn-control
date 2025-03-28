<template>
    <div class="q-pa-md">
        <v-nav-bar />

        <div class="flex space-x-4">
            <h6>My devices</h6>
            <q-space/>
            <v-create @created="getPeers" />
        </div>

        <div class="row q-gutter-md q-mt-md justify-center">
            <q-card
                v-for="peer in peers"
                :key="peer.id"
                class="q-ma-sm q-pa-sm peer-card"
            >
                <q-card-section class="row justify-between items-center">
                    <div>
                        <q-icon
                            :name="peer.active ? 'check_circle' : 'cancel'"
                            :color="peer.active ? 'green' : 'red'"
                            size="20px"
                        />
                        <span class="q-ml-sm">
                            {{ peer.active ? "Active" : "Inactive" }}
                        </span>
                    </div>
                    <v-delete @deleted="getPeers" :peer="peer" />
                </q-card-section>

                <q-card-section class="column items-center">
                    <div class="text-h5">
                        {{ peer.name }}
                    </div>
                    <div>
                        <strong>Country:</strong>
                        {{ peer.network.server_name }} -
                        <strong>Interface:</strong> {{ peer.network.name }}
                    </div>
                </q-card-section>

                <q-card-section class="row justify-between items-center">
                    <v-toggle @updated="getPeers" :peer="peer" />
                    <span>{{ peer.created }}</span>
                </q-card-section>
            </q-card>
        </div>

        <div class="row justify-center q-mt-md">
            <q-pagination
                v-model="search.page"
                color="grey-8"
                :max="pages.total_pages"
                size="sm"
            />
        </div>
    </div>
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
            pages: {
                total_pages: 0,
                total: 0,
            },
            search: {
                page: 1,
                per_page: 10,
            },
        };
    },
    watch: {
        "search.page"() {
            this.getPeers();
        },
    },
    mounted() {
        this.getPeers();
    },
    methods: {
        async getPeers() {
            try {
                const res = await this.$api.get("/api/peers", {
                    params: this.search,
                });
                if (res.status === 200) {
                    this.peers = res.data.data;
                    this.pages = res.data.meta.pagination;
                }
            } catch (err) {
                if (err.response) {
                    this.$notification.error(err.response.data.message);
                }
            }
        },
    },
};
</script>

<style scoped>
.peer-card {
    border-radius: 1rem;
    width: 100%;
    min-width: 320px;
    max-width: 330px;
}
</style>
