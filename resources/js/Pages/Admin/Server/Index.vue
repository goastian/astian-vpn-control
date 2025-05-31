<template>
    <v-admin-layout>
        <div class="q-pa-md">
            <div class="row items-center q-mb-md">
                <div class="col">
                    <h5 class="text-primary q-mb-none">List of servers</h5>
                </div>
                <q-space />
                <v-create @created="getServers" />
            </div>

            <div class="row q-col-gutter-md">
                <div
                    v-for="server in servers"
                    :key="server.id"
                    class="col-xs-12 col-sm-6 col-md-4 col-lg-3"
                >
                    <q-card
                        class="q-pa-md q-hoverable shadow-3 rounded-borders"
                    >
                        <q-card-section>
                            <div class="text-h6 text-primary q-mb-sm">
                                {{ server.country }}
                            </div>

                            <div
                                class="text-caption text-grey-7 row items-center q-mb-xs"
                            >
                                <q-icon
                                    name="lan"
                                    size="16px"
                                    class="q-mr-xs"
                                />
                                <span>{{ server.ip }}</span>
                                <q-btn
                                    flat
                                    dense
                                    round
                                    size="sm"
                                    icon="content_copy"
                                    @click="copyToClipboard(server.ip)"
                                    class="q-ml-sm"
                                    :ripple="false"
                                />
                            </div>

                            <div
                                class="text-caption text-grey-7 row items-center q-mb-xs"
                            >
                                <q-icon
                                    name="dns"
                                    size="16px"
                                    class="q-mr-xs"
                                />
                                <span>Puerto: {{ server.port }}</span>
                            </div>

                            <div
                                class="text-caption text-grey-7 row items-center q-mb-xs"
                            >
                                <q-icon
                                    name="event"
                                    size="16px"
                                    class="q-mr-xs"
                                />
                                <span>Creado: {{ server.created }}</span>
                            </div>

                            <div
                                class="text-caption text-grey-7 row items-center"
                            >
                                <q-icon
                                    name="update"
                                    size="16px"
                                    class="q-mr-xs"
                                />
                                <span>Actualizado: {{ server.updated }}</span>
                            </div>
                        </q-card-section>

                        <q-separator spaced />

                        <q-card-actions align="center">
                            <v-delete @deleted="getServers" :item="server" />
                            <v-update @updated="getServers" :item="server" />
                        </q-card-actions>
                    </q-card>
                </div>
            </div>

            <div class="row justify-center q-mt-lg">
                <q-pagination
                    v-model="search.page"
                    color="primary"
                    :max="pages.total_pages"
                    size="md"
                    direction-links
                />
            </div>
        </div>
    </v-admin-layout>
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
            this.getServers();
        },
    },

    created() {
        this.links = this.$page.props.links;
    },

    mounted() {
        this.getServers();
    },

    methods: {
        async copyToClipboard(text) {
            try {
                await navigator.clipboard.writeText(text);
                this.$q.notify({
                    type: "positive",
                    message: "📋 Copiado al portapapeles",
                    timeout: 2500,
                });
            } catch (err) {
                this.$q.notify({
                    type: "negative",
                    message: "Error al copiar",
                });
            }
        },

        async getServers() {
            try {
                const res = await this.$api.get(this.links["peers"], {
                    params: this.search,
                });

                if (res.status === 200) {
                    this.servers = res.data.data;
                    this.pages = res.data.meta.pagination;
                }
            } catch (err) {}
        },
    },
};
</script>

<style scoped>
.q-hoverable:hover {
    transform: scale(1.03);
    transition: 0.2s ease-in-out;
}
.rounded-borders {
    border-radius: 12px;
}
</style>
