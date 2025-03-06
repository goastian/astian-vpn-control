<template>
    <div class="q-pa-md">
        <q-table
            flat
            bordered
            title="Servers"
            :rows="servers"
            :columns="headers"
            hide-pagination
        >
            <template v-slot:top>
                <div class="flex space-x-4">
                    <h6>List of servers</h6>
                    <v-create @created="getServers"></v-create>
                </div>
            </template>

            <template v-slot:body-cell-actions="props">
                <q-td :props="props">
                    <v-delete
                        @deleted="getServers"
                        :item="props.row"
                    ></v-delete>
                    <v-update
                        @updated="getServers"
                        :item="props.row"
                    ></v-update>
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
import VUpdate from "./Update.vue";
import VDelete from "./Delete.vue";

export default {
    components: {
        VCreate,
        VUpdate,
        VDelete,
    },

    data() {
        return {
            servers: [],
            headers: [
                {
                    name: "country",
                    label: "Country",
                    align: "left",
                    sortable: false,
                    field: "country",
                },
                {
                    name: "url",
                    label: "URL",
                    align: "left",
                    sortable: false,
                    field: "url",
                },
                {
                    name: "port",
                    label: "PORT",
                    align: "left",
                    sortable: false,
                    field: "port",
                },
                {
                    name: "ipv4",
                    label: "IPV4",
                    align: "left",
                    sortable: false,
                    field: "ipv4",
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
            this.getServers();
        },
        "search.per_page"(value) {
            if (value) {
                this.search.per_page = value;
                this.getServers();
            }
        },
    },

    mounted() {
        this.getServers();
    },

    methods: {
        /**
         * Retrieve the servers
         */
        async getServers() {
            try {
                const res = await this.$api.get("/api/servers", {
                    params: this.search,
                });

                if (res.status == 200) {
                    this.servers = res.data.data;
                    this.pages = res.data.meta.pagination;
                }
            } catch (err) {}
        },
    },
};
</script>
