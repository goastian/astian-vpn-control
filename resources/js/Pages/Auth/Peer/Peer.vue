<template>
    <div class="q-pa-md">
        <v-nav-bar />

        <q-table
            flat
            grid
            bordered
            label="Peers"
            :rows="peers"
            :columns="columns"
            hide-pagination
            :rows-per-page-options="[search.per_page]"
        >
            <template v-slot:top>
                <div class="flex space-x-4">
                    <h6>List of peers</h6>
                    <v-create @created="getPeers"></v-create>
                </div>
            </template>

            <template v-slot:item="props">
                <q-card class="q-ma-sm q-pa-sm card">
                    <q-card-section
                        class="row q-gutter-sm justify-between items-center"
                    >
                        <div>
                            <q-icon
                                :name="
                                    props.row.active ? 'check_circle' : 'cancel'
                                "
                                :color="props.row.active ? 'green' : 'red'"
                                size="20px"
                            />
                            <span class="q-ml-sm">
                                {{ props.row.active ? "Active" : "Inactive" }}
                            </span>
                        </div>
                        <div>
                            <v-delete
                                @deleted="getPeers"
                                :peer="props.row"
                            ></v-delete>
                        </div>
                    </q-card-section>

                    <q-card-section class="column items-center">
                        <div class="text-h5">
                            {{ props.row.name }}
                        </div>
                        <div>
                            <span>
                                <strong>Country</strong>
                                {{ props.row.network.server_name }} -
                                <strong>Interface</strong>
                                {{ props.row.network.name }}
                            </span>
                        </div>
                    </q-card-section>
                    <q-card-section
                        class="row q-gutter-sm justify-between items-center q-gutter-y-sm"
                    >
                        <v-toggle
                            @updated="getPeers"
                            :peer="props.row"
                        ></v-toggle>
                        <span>{{ props.row.created }}</span>
                    </q-card-section>
                </q-card>
            </template>
        </q-table>

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
            columns: [
                {
                    name: "active",
                    label: "Status",
                    align: "left",
                    sortable: false,
                    field: "active",
                },
                {
                    name: "name",
                    label: "Device",
                    align: "left",
                    sortable: false,
                    field: "name",
                },
                {
                    name: "created",
                    label: "Created",
                    align: "left",
                    sortable: false,
                    field: "created",
                },
                {
                    name: "actions",
                    label: "Actions",
                    align: "left",
                    sortable: false,
                    field: "actions",
                },
            ],
            pages: {
                total_pages: 0,
                total: 0,
            },
            search: {
                page: 1,
                per_page: 4,
            },
        };
    },

    watch: {
        "search.page"(value) {
            console.log(value);

            this.getPeers();
        },
        "search.per_page"(value) {
            if (value) {
                this.search.per_page = value;
                this.getPeers();
            }
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

                if (res.status == 200) {
                    this.peers = res.data.data;
                    this.pages = res.data.meta.pagination;
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

<style scoped>
.card {
    border-radius: 1rem;
    width: 100%;
    min-width: 320px;
    max-width: 330px;
}
</style>
