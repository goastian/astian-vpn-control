<template>
    <div class="q-pa-md">
        <v-nav-bar />

        <div class="row q-mb-md">
            <div class="col-12 flex">
                <h6>List of servers</h6>
                <q-space></q-space>
                <v-create @created="getServers"></v-create>
            </div>
        </div>

        <div class="row q-gutter-md">
            <div
                v-for="server in servers"
                :key="server.id"
                class="col-xs-12 col-sm-6 col-md-3 col-lg-4"
            >
                <q-card class="q-pa-md">
                    <q-card-section>
                        <q-item>
                            <q-item-section>
                                <q-item-label
                                    ><strong>Country:</strong>
                                    {{ server.country }}</q-item-label
                                >
                                <q-item-label
                                    ><strong>URL:</strong>
                                    {{ server.url }}</q-item-label
                                >
                                <q-item-label
                                    ><strong>PORT:</strong>
                                    {{ server.port }}</q-item-label
                                >
                                <q-item-label
                                    ><strong>IPV4:</strong>
                                    {{ server.ipv4 }}</q-item-label
                                >
                            </q-item-section>
                        </q-item>
                    </q-card-section>

                    <q-separator />

                    <q-card-actions>
                        <v-delete @deleted="getServers" :item="server" />
                        <q-space></q-space>
                        <v-update @updated="getServers" :item="server" />
                    </q-card-actions>
                    <q-card-actions class="row border-1 q-pa-md">
                        <div class="col-12">ShadowSocks Actions</div>
                        <div class="col-12">
                            <v-start :item="server" />
                        </div>
                        <div class="col-12">
                            <v-stop :item="server" />
                        </div>
                        <div class="col-12">
                            <v-status :item="server" />
                        </div>
                    </q-card-actions>
                </q-card>
            </div>
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
import VUpdate from "./Update.vue";
import VDelete from "./Delete.vue";
import VStart from "./Start.vue";
import VStatus from "./Status.vue";
import VStop from "./Stop.vue";

export default {
    components: {
        VCreate,
        VUpdate,
        VDelete,
        VStart,
        VStop,
        VStatus,
    },

    data() {
        return {
            servers: [],
            pages: {
                total_pages: 0,
            },
            search: {
                page: 1,
                per_page: 15,
            },
        };
    },

    watch: {
        "search.page"() {
            this.getServers();
        },
    },

    mounted() {
        this.getServers();
    },

    methods: {
        async getServers() {
            try {
                const res = await this.$api.get("/api/servers", {
                    params: this.search,
                });

                if (res.status === 200) {
                    this.servers = res.data.data;
                    this.pages = res.data.meta.pagination;
                }
            } catch (err) {
                console.error("Error fetching servers:", err);
            }
        },
    },
};
</script>
