<template>
    <v-admin-layout>
        <div class="q-pa-md">
            <v-nav-bar />

            <!-- Header -->
            <div class="row items-center q-mb-sm">
                <h6 class="text-h6 text-weight-bold">Network Interfaces</h6>
                <q-space />
                <v-create @created="getWgs" />
            </div>

            <!-- Cards Grid -->
            <div class="row q-col-gutter-sm">
                <q-card
                    v-for="(wg, index) in interfaces"
                    :key="index"
                    class="col-xs-12 col-sm-6 col-md-4"
                    bordered
                    flat
                    style="
                        border-radius: 12px;
                        box-shadow: 0px 2px 10px rgba(0, 0, 0, 0.08);
                    "
                >
                    <q-card-section class="q-pa-sm">
                        <div class="row items-center">
                            <q-icon
                                name="mdi-server-network"
                                color="primary"
                                size="24px"
                                class="q-mr-sm"
                            />
                            <div>
                                <div class="text-subtitle1 text-weight-medium">
                                    {{ wg.server_country }} ({{ wg.name }})
                                </div>
                                <div class="text-caption text-grey-7">
                                    {{ wg.server_url }}
                                </div>
                            </div>
                        </div>
                    </q-card-section>

                    <q-separator />

                    <q-card-section class="q-pa-sm">
                        <q-chip
                            :color="wg.active ? 'green' : 'red'"
                            text-color="white"
                            icon="mdi-wifi"
                            dense
                        >
                            {{ wg.active ? "Active" : "Inactive" }}
                        </q-chip>
                    </q-card-section>

                    <q-card-section class="q-pa-sm">
                        <div class="row items-center q-gutter-x-sm">
                            <q-chip color="orange-5" text-color="white" dense>
                                <q-icon
                                    name="mdi-portable-network"
                                    class="q-mr-xs"
                                />
                                Port: {{ wg.listen_port }}
                            </q-chip>
                            <q-chip color="purple-5" text-color="white" dense>
                                <q-icon name="mdi-ethernet" class="q-mr-xs" />
                                {{ wg.interface }}
                            </q-chip>
                            <q-chip color="green-5" text-color="white" dense>
                                <q-icon
                                    name="mdi-monitor-cellphone-star"
                                    class="q-mr-xs"
                                />
                                Devices {{ wg.devices }}
                            </q-chip>
                        </div>
                    </q-card-section>

                    <q-separator />

                    <q-card-section class="q-pa-sm">
                        <q-list dense>
                            <q-item>
                                <q-item-section avatar>
                                    <q-icon
                                        name="mdi-ip-network"
                                        color="blue"
                                    />
                                </q-item-section>
                                <q-item-section>
                                    <q-item-label>Subnet</q-item-label>
                                    <q-item-label caption>{{
                                        wg.subnet
                                    }}</q-item-label>
                                </q-item-section>
                            </q-item>
                            <q-item>
                                <q-item-section avatar>
                                    <q-icon
                                        name="mdi-router-network"
                                        color="teal"
                                    />
                                </q-item-section>
                                <q-item-section>
                                    <q-item-label>Gateway</q-item-label>
                                    <q-item-label caption>{{
                                        wg.gateway
                                    }}</q-item-label>
                                </q-item-section>
                            </q-item>
                            <q-item>
                                <q-item-section avatar>
                                    <q-icon name="mdi-dns" color="indigo" />
                                </q-item-section>
                                <q-item-section>
                                    <q-item-label>DNS</q-item-label>
                                    <q-item-label caption>{{
                                        wg.dns
                                    }}</q-item-label>
                                </q-item-section>
                            </q-item>
                            <q-item>
                                <q-item-section avatar>
                                    <q-icon
                                        :name="
                                            wg.enable_dns
                                                ? 'mdi-check'
                                                : 'mdi-close'
                                        "
                                        :color="wg.enable_dns ? 'green' : 'red'"
                                    />
                                </q-item-section>
                                <q-item-section>
                                    <q-item-label>Enable DNS</q-item-label>
                                    <q-item-label caption>{{
                                        wg.enable_dns ? "Yes" : "No"
                                    }}</q-item-label>
                                </q-item-section>
                            </q-item>
                        </q-list>
                    </q-card-section>

                    <q-separator />

                    <q-card-actions class="q-pa-sm justify-end">
                        <v-update @updated="getWgs" :wg="wg" />
                        <v-reload @updated="getWgs" :wg="wg" />
                        <v-toggle @updated="getWgs" :wg="wg" />
                        <v-delete @deleted="getWgs" :wg="wg" />
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
            pages: {
                total_pages: 0,
            },
            search: {
                page: 1,
                per_page: 15,
            },
            links: [],
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
        this.links = this.$page.props.links;
        this.getWgs();
    },

    methods: {
        async getWgs() {
            try {
                const res = await this.$api.get(this.links["wireguard"], {
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
