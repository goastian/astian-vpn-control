<template>
    <v-admin-layout>
        <div class="q-pa-md">
            <v-nav-bar />

            <!-- Header -->
            <div class="row items-center q-mb-md">
                <h6 class="text-h6 text-weight-bold">Network Interfaces</h6>
                <q-space />

                <!-- Filter by server-->
                <q-select
                    v-model="search.server_id"
                    :options="serverOptions"
                    option-label="label"
                    option-value="value"
                    emit-value
                    map-options
                    filled
                    dense
                    clearable
                    use-input
                    input-debounce="300"
                    @filter="filterServers"
                    label="Filter by server"
                    style="min-width: 250px; max-width: 350px"
                    class="q-mr-md"
                >
                    <template v-slot:prepend>
                        <q-icon name="mdi-server" />
                    </template>
                    <template v-slot:option="scope">
                        <q-item v-bind="scope.itemProps">
                            <q-item-section avatar>
                                <q-icon name="mdi-server-network" />
                            </q-item-section>
                            <q-item-section>
                                <q-item-label>{{
                                    scope.opt.label
                                }}</q-item-label>
                                <q-item-label caption
                                    >{{ scope.opt.ip }}:{{
                                        scope.opt.port
                                    }}</q-item-label
                                >
                            </q-item-section>
                        </q-item>
                    </template>
                    <template v-slot:no-option>
                        <q-item>
                            <q-item-section class="text-grey">
                                No servers found
                            </q-item-section>
                        </q-item>
                    </template>
                </q-select>

                <v-create @created="getWgs" />
            </div>

            <!-- Cards Grid -->
            <div class="row q-my-sm q-col-gutter-lg">
                <q-card
                    v-for="(wg, index) in interfaces"
                    :key="index"
                    class="col-xs-12 col-sm-6 col-md-4 col-lg-3"
                    flat
                >
                    <!-- Header with gradient -->
                    <q-card-section class="bg-primary text-white q-pa-sm">
                        <div class="row items-center no-wrap">
                            <q-icon
                                name="mdi-server-network"
                                size="28px"
                                class="q-mr-sm"
                            />
                            <div class="ellipsis">
                                <div
                                    class="text-subtitle1 text-weight-bold ellipsis"
                                >
                                    {{ wg.server_country }}
                                </div>
                                <div class="text-caption ellipsis">
                                    {{ wg.name }} • {{ wg.server_url }}
                                </div>
                            </div>
                        </div>
                    </q-card-section>

                    <!-- Status -->
                    <q-card-section class="q-px-md q-py-sm">
                        <q-badge
                            :color="wg.active ? 'positive' : 'negative'"
                            class="full-width text-center q-py-xs"
                            :icon="wg.active ? 'mdi-wifi' : 'mdi-wifi-off'"
                        >
                            {{ wg.active ? "ACTIVE" : "INACTIVE" }}
                        </q-badge>
                    </q-card-section>

                    <!-- Chips information -->
                    <q-card-section class="q-px-md q-pt-none q-pb-md">
                        <div class="row q-col-gutter-xs q-mb-sm">
                            <div class="col-6">
                                <q-chip
                                    icon="mdi-portable-network"
                                    color="orange-5"
                                    text-color="white"
                                    class="full-width justify-center"
                                >
                                    Port: {{ wg.listen_port }}
                                </q-chip>
                            </div>
                            <div class="col-6">
                                <q-chip
                                    icon="mdi-ethernet"
                                    color="purple-5"
                                    text-color="white"
                                    class="full-width justify-center"
                                >
                                    {{ wg.interface }}
                                </q-chip>
                            </div>
                        </div>

                        <div class="row">
                            <q-chip
                                icon="mdi-monitor-cellphone-star"
                                color="green-5"
                                text-color="white"
                                class="full-width justify-center"
                            >
                                {{ wg.devices }} Device{{
                                    wg.devices !== 1 ? "s" : ""
                                }}
                            </q-chip>
                        </div>
                    </q-card-section>

                    <!-- actions -->
                    <q-card-section class="q-px-md q-py-sm bg-grey-2">
                        <q-list dense class="rounded-borders">
                            <q-item class="q-px-none">
                                <q-item-section avatar>
                                    <q-icon
                                        name="mdi-ip-network"
                                        color="blue"
                                    />
                                </q-item-section>
                                <q-item-section>
                                    <q-item-label class="text-weight-medium"
                                        >Subnet</q-item-label
                                    >
                                    <q-item-label caption class="text-grey-8">
                                        {{ wg.subnet }}
                                    </q-item-label>
                                </q-item-section>
                            </q-item>

                            <q-item class="q-px-none">
                                <q-item-section avatar>
                                    <q-icon
                                        name="mdi-router-network"
                                        color="teal"
                                    />
                                </q-item-section>
                                <q-item-section>
                                    <q-item-label class="text-weight-medium"
                                        >Gateway</q-item-label
                                    >
                                    <q-item-label caption class="text-grey-8">
                                        {{ wg.gateway }}
                                    </q-item-label>
                                </q-item-section>
                            </q-item>

                            <q-item class="q-px-none">
                                <q-item-section avatar>
                                    <q-icon name="mdi-dns" color="indigo" />
                                </q-item-section>
                                <q-item-section>
                                    <q-item-label class="text-weight-medium"
                                        >DNS</q-item-label
                                    >
                                    <q-item-label caption class="text-grey-8">
                                        {{ wg.dns }}
                                    </q-item-label>
                                </q-item-section>
                            </q-item>

                            <q-item class="q-px-none">
                                <q-item-section avatar>
                                    <q-icon
                                        :name="
                                            wg.enable_dns
                                                ? 'mdi-check-circle'
                                                : 'mdi-close-circle'
                                        "
                                        :color="
                                            wg.enable_dns
                                                ? 'positive'
                                                : 'negative'
                                        "
                                    />
                                </q-item-section>
                                <q-item-section>
                                    <q-item-label class="text-weight-medium"
                                        >DNS Enabled</q-item-label
                                    >
                                    <q-item-label caption class="text-grey-8">
                                        {{
                                            wg.enable_dns
                                                ? "Enabled"
                                                : "Disabled"
                                        }}
                                    </q-item-label>
                                </q-item-section>
                            </q-item>
                        </q-list>
                    </q-card-section>

                    <!-- actions -->
                    <q-card-actions
                        class="q-px-md q-py-sm bg-grey-1 justify-end"
                    >
                        <v-toggle @updated="getWgs" :wg="wg" dense flat />
                        <v-reload @updated="getWgs" :wg="wg" dense flat />
                        <v-update @updated="getWgs" :wg="wg" dense flat />
                        <v-delete @deleted="getWgs" :wg="wg" dense flat />
                    </q-card-actions>
                </q-card>
            </div>

            <!-- Pagination -->
            <div class="row justify-center q-mt-sm">
                <q-pagination
                    v-model="search.page"
                    color="primary"
                    :max="pages.total_pages"
                    size="sm"
                    rounded
                />
            </div>
        </div>
    </v-admin-layout>
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
            interfaces: [],
            servers: [],
            serverOptions: [],
            pages: {
                total_pages: 0,
            },
            search: {
                page: 1,
                per_page: 15,
                server_id: null,
            },
            links: [],
        };
    },

    computed: {
        allServerOptions() {
            const options = this.servers.map((server) => ({
                label: server.country,
                value: server.id,
                ip: server.ip,
                port: server.port,
            }));

            return options;
        },
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
        "search.server_id"(value) {
            this.getWgs();
        },
    },

    created() {
        this.links = this.$page.props.links;
        this.getWgs();
        this.getServers();
    },

    methods: {
        filterServers(val, update) {
            if (val === "") {
                update(() => {
                    this.serverOptions = this.allServerOptions;
                });
                return;
            }

            update(() => {
                const needle = val.toLowerCase();
                this.serverOptions = this.allServerOptions.filter(
                    (v) =>
                        v.label.toLowerCase().indexOf(needle) > -1 ||
                        v.ip.toLowerCase().indexOf(needle) > -1
                );
            });
        },

        async getWgs() {
            try {
                const res = await this.$api.get(this.links["wireguard"], {
                    params: this.search,
                });

                if (res.status == 200) {
                    this.interfaces = res.data.data;
                    this.pages = res.data.meta.pagination;
                }
            } catch (err) {
                console.error("Error fetching WGs:", err);
            }
        },

        async getServers() {
            try {
                const res = await this.$api.get(
                    this.$page.props.links["servers"],
                    {
                        params: {
                            per_page: 100,
                        },
                    }
                );
                if (res.status === 200) {
                    this.servers = res.data.data;
                    this.serverOptions = this.allServerOptions;
                }
            } catch (err) {}
        },
    },
};
</script>

<style scoped>
.q-select {
    border-radius: 8px;
}
</style>
