<template>
    <div class="q-pa-md">
        <q-table
            flat
            grid
            bordered
            label="Servers"
            :rows="interfaces"
            :columns="headers"
            hide-pagination
        >
            <template v-slot:top>
                <div class="flex space-x-4">
                    <h6>List of network interfaces</h6>
                    <v-create @created="getWgs"></v-create>
                </div>
            </template>

            <template v-slot:item="props">
                <q-card class="q-ma-sm">
                    <q-card-section>
                        <div class="text-h6">
                            {{ props.row.server_country }}
                        </div>
                    </q-card-section>

                    <q-card-section class="q-gutter-sm">
                        <q-icon
                            :name="props.row.active ? 'check_circle' : 'cancel'"
                            :color="props.row.active ? 'green' : 'red'"
                            size="20px"
                        />
                        <span class="q-ml-sm">
                            {{ props.row.active ? "Active" : "Inactive" }}
                        </span>
                    </q-card-section>

                    <q-card-section class="q-gutter-sm">
                        <v-reload @updated="getWgs" :wg="props.row"></v-reload>
                        <v-delete @deleted="getWgs" :wg="props.row"></v-delete>
                        <v-toggle @updated="getWgs" :wg="props.row"></v-toggle>
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
import VUpdate from "./Update.vue";
import VDelete from "./Delete.vue";
import VToggle from "./Toggle.vue";
import VReload from "./Reload.vue";

export default {
    components: {
        VCreate,
        VUpdate,
        VDelete,
        VToggle,
        VReload,
    },

    data() {
        return {
            headers: [
                {
                    name: "active",
                    label: "On And Off",
                    align: "left",
                    sortable: false,
                    field: "active",
                },
                {
                    name: "name",
                    label: "Name",
                    align: "left",
                    sortable: false,
                    field: "name",
                },
                {
                    name: "listen_port",
                    label: "Port",
                    align: "left",
                    sortable: false,
                    field: "listen_port",
                },
                {
                    name: "server",
                    label: "Server",
                    align: "left",
                    sortable: false,
                    field: "server",
                },
                {
                    name: "reload",
                    label: "Reload",
                    align: "left",
                    sortable: false,
                    field: "reload",
                },
                {
                    name: "interface",
                    label: "NetLan",
                    align: "left",
                    sortable: false,
                    field: "interface",
                },
                {
                    name: "actions",
                    label: "Actions",
                    align: "left",
                    sortable: false,
                    field: "actions",
                },
            ],
            interfaces: [],
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
            this.getWgs();
        },
        "search.per_page"(value) {
            if (value) {
                this.search.per_page = value;
                this.getWgs();
            }
        },
    },

    mounted() {
        this.getWgs();
    },

    methods: {
        async getWgs() {
            try {
                const res = await this.$api.get("/api/wgs", {
                    params: this.search,
                });

                if (res.status == 200) {
                    this.interfaces = res.data.data;
                    this.pages = res.data.meta.pagination;
                }
            } catch (err) {}
        },
    },
};
</script>
