<template>
    <v-auth-layout>
        <div class="q-pa-md">
            <v-nav-bar />

            <div class="row items-center q-mb-sm">
                <h6 class="text-h6 text-weight-bold">Network devices</h6>
                <q-space />
            </div>

            <div
                v-if="devices.length === 0"
                class="q-my-md text-grey text-center"
            >
                <q-icon
                    name="mdi-information-outline"
                    size="32px"
                    class="q-mb-sm"
                />
                <div class="text-subtitle2">No devices found</div>
                <div class="text-caption">
                    You haven’t added any network devices yet.
                </div>
            </div>

            <!-- DEVICES GRID -->
            <div v-else class="row q-col-gutter-sm">
                <q-card
                    v-for="(dv, index) in devices"
                    :key="index"
                    class="col-xs-12 col-sm-6 col-md-4"
                    bordered
                    flat
                    style="
                        border-radius: 12px;
                        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.08);
                    "
                >
                    <q-card-section class="q-pb-none">
                        <div class="text-h6 text-primary">{{ dv.name }}</div>
                        <div class="text-caption text-grey">
                            ID: {{ dv.id }}
                        </div>
                    </q-card-section>

                    <q-separator />

                    <q-card-section class="q-pt-sm q-pb-sm">
                        <div class="row items-center q-mb-xs">
                            <q-icon name="dns" class="q-mr-sm" />
                            <span class="text-body2">{{ dv.ip }}</span>
                        </div>
                        <div class="row items-center q-mb-xs">
                            <q-icon name="vpn_key" class="q-mr-sm" />
                            <span class="text-body2">{{ dv.key }}</span>
                        </div>
                        <div class="row items-center q-mb-xs">
                            <q-icon name="important_devices" class="q-mr-sm" />
                            <span class="text-body2">{{ dv.agent }}</span>
                        </div>
                    </q-card-section>

                    <q-separator />

                    <q-card-actions class="q-pa-sm justify-between">
                        <div class="text-caption text-grey">
                            Created: {{ dv.created }}
                        </div>
                        <div class="text-caption text-grey">
                            Updated: {{ dv.updated }}
                        </div>
                        <v-delete :item="dv" @deleted="getDevices" />
                    </q-card-actions>
                </q-card>
            </div>

            <!-- PAGINATION -->
            <div
                v-if="pages.total_pages > 1"
                class="row justify-center q-mt-sm"
            >
                <q-pagination
                    v-model="search.page"
                    color="primary"
                    :max="pages.total_pages"
                    size="sm"
                    rounded
                />
            </div>
        </div>
    </v-auth-layout>
</template>

<script>
import VDelete from "./Delete.vue";

export default {
    components: {
        VDelete,
    },

    data() {
        return {
            devices: [],
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
        "search.page"() {
            this.getDevices();
        },
        "search.per_page"(value) {
            if (value) {
                this.search.per_page = value;
                this.getDevices();
            }
        },
    },

    mounted() {
        this.links = this.$page.props.links;
        this.getDevices();
    },

    methods: {
        async getDevices() {
            try {
                const res = await this.$api.get(this.links["device"], {
                    params: this.search,
                });

                if (res.status === 200) {
                    this.devices = res.data.data;
                    this.pages = res.data.meta.pagination;
                }
            } catch (err) {
                console.error("Error fetching devices:", err);
            }
        },
    },
};
</script>
