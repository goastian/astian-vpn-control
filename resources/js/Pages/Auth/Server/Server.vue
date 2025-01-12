<template>
    <div>
        <v-data-table :headers="headers" :items="servers" v-if="isAdmin()">
            <template #top>
                <v-filter
                    :params="['country', 'url', 'port', 'uri', 'active']"
                    @change="searcher"
                ></v-filter>
                <div class="row d-flex justify-between py-4 px-4">
                    <h1 class="text-subtitle-1 underline">List of Servers</h1>
                    <v-create @created="getServers"></v-create>
                </div>
            </template>
            <template #item.status="{ item }">
                <v-icon
                    :color="!item.inactive ? 'red-accent-4' : 'green-accent-4'"
                >
                    {{ $utils.toKebabCase("mdiCheckboxBlankCircle") }}
                </v-icon>
            </template>
            <template #item.url="{ item }">
                <span class="font-bold text-blue-500">{{ item.url }}</span>
            </template>
            <template #item.actions="{ item }">
                <v-menu transition="scale-transition">
                    <template v-slot:activator="{ props }">
                        <v-btn
                            color="primary"
                            variant="plain"
                            v-bind="props"
                            icon
                        >
                            <v-icon>
                                {{ $utils.toKebabCase("mdiTools") }}
                            </v-icon>
                        </v-btn>
                    </template>

                    <v-list>
                        <v-list-item>
                            <v-list-item-title>
                                <v-update
                                    @updated="getServers"
                                    :item="item"
                                ></v-update>
                            </v-list-item-title>
                            <v-list-item-title
                                class="text-center"
                                v-if="!item.inactive"
                            >
                                <v-delete
                                    @deleted="getServers"
                                    :item="item"
                                ></v-delete>
                            </v-list-item-title>
                        </v-list-item>
                    </v-list>
                </v-menu>
            </template>
            <template v-slot:bottom>
                <v-pagination
                    v-model="search.page"
                    :length="search.total_pages"
                    :total-visible="7"
                ></v-pagination>
            </template>
        </v-data-table>
    </div>
</template>
<script>
import VCreate from "./Create.vue";
import VUpdate from "./Update.vue";
import VDelete from "./Delete.vue";

export default {
    inject: ["$user"],

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
                    title: "Status",
                    align: "center",
                    sortable: false,
                    key: "status",
                },
                {
                    title: "Country",
                    align: "start",
                    sortable: false,
                    key: "country",
                },
                {
                    title: "URL",
                    align: "start",
                    sortable: false,
                    key: "url",
                },
                {
                    title: "PORT",
                    align: "start",
                    sortable: false,
                    key: "port",
                },
                {
                    title: "IPV4",
                    align: "start",
                    sortable: false,
                    key: "ipv4",
                },
                {
                    title: "Actions",
                    align: "start",
                    sortable: false,
                    key: "actions",
                },
            ],
            search: {
                page: 1,
                per_page: 50,
                total_pages: 0,
            },
            params: [],
        };
    },

    mounted() {
        this.getServers();
    },

    methods: {
        /**
         *
         * @param event
         */
        searcher(event) {
            this.params = event;
            this.getServers();
        },

        /**
         * Retrieve the servers
         */
        async getServers() {
            try {
                const res = await this.$api.get("/api/servers", {
                    params: this.params,
                });

                if (res.status == 200) {
                    this.servers = res.data.data;
                    this.search = res.data.meta;
                }
            } catch (err) {}
        },

        isAdmin() {
            const group = this.$user.roles.find((item) => item.name == "admin");
            return group ? true : false;
        },
    },
};
</script>
