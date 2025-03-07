<template>
    <div class="q-pa-md">
        <q-table
            flat
            bordered
            title="Peers"
            :rows="peers"
            :columns="columns"
            hide-pagination
        >
            <template v-slot:top>
                <div class="flex space-x-4">
                    <h6>List of peers</h6>
                    <v-create @created="getPeers"></v-create>
                </div>
            </template>

            <template v-slot:body-cell-active="props">
                <q-td :props="props">
                    <v-toggle @updated="getPeers" :peer="props.row"></v-toggle>
                </q-td>
            </template>

            <template v-slot:body-cell-actions="props">
                <q-td :props="props">
                    <div class="flex gap-1">
                        <v-delete
                            @deleted="getPeers"
                            :peer="props.row"
                        ></v-delete>
                    </div>
                </q-td>
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
            },
            search: {
                page: 1,
                per_page: 15,
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
