<template>
    <div class="q-pa-md">
        <v-nav-bar />

        <div class="row q-mb-md items-center">
            <div class="col">
                <h5 class="text-primary">List of Servers</h5>
            </div>
            <q-space />
            <v-create @created="getServers"></v-create>
        </div>

        <div class="row q-col-gutter-md">
            <div
                v-for="server in servers"
                :key="server.id"
                class="col-xs-12 col-sm-6 col-md-4 col-lg-3"
            >
                <q-card class="q-pa-md q-hoverable shadow-2">
                    <q-card-section>
                        <q-item>
                            <q-item-section>
                                <q-item-label class="text-bold text-h6">{{
                                    server.country
                                }}</q-item-label>
                                <q-item-label class="text-caption text-grey">
                                    <strong>URL:</strong> {{ server.ip }}
                                </q-item-label>
                                <q-item-label class="text-caption text-grey">
                                    <strong>Port:</strong> {{ server.port }}
                                </q-item-label>
                            </q-item-section>
                        </q-item>
                    </q-card-section>

                    <q-separator />

                    <q-card-actions align="center">
                        <v-delete @deleted="getServers" :item="server" />
                        <v-update @updated="getServers" :item="server" />
                    </q-card-actions>

                    <q-separator />

                    <q-card-section>
                        <div class="text-bold text-primary text-center">
                            ShadowSocks Settings
                        </div>
                        <div class="text-caption q-mt-sm text-grey-8"> 
                            <br />
                            <strong>Port:</strong> {{ server.ss_port }} <br />
                            <strong>Password:</strong>
                            <q-chip
                                clickable
                                @click="copyToClipboard(server.ss_password)"
                                color="green"
                                text-color="white"
                                icon="mdi-content-copy"
                                label="*****"
                            >
                                <q-tooltip> Copy password </q-tooltip>
                            </q-chip>
                            <br />
                            <strong>Method:</strong> {{ server.ss_method }}
                        </div>
                    </q-card-section>

                    <q-separator />

                    <q-card-section>
                        <div class="text-bold text-primary text-center">
                            Server Controls
                        </div>
                        <q-card-actions align="center" class="q-mb-sm">
                            <v-server-start :item="server" />
                            <v-server-stop :item="server" />
                            <v-server-status :item="server" />
                        </q-card-actions>
                    </q-card-section>

                    <q-separator />

                    <q-card-section>
                        <div class="text-bold text-primary text-center">
                            Client Controls
                        </div>
                        <q-card-actions align="center">
                            <v-client-start :item="server" />
                            <v-client-stop :item="server" />
                            <v-client-status :item="server" />
                        </q-card-actions>
                    </q-card-section>
                </q-card>
            </div>
        </div>

        <div class="row justify-center q-mt-md">
            <q-pagination
                v-model="search.page"
                color="primary"
                :max="pages.total_pages"
                size="md"
                direction-links
            />
        </div>
    </div>
</template>

<script>
import VCreate from "./Create.vue";
import VUpdate from "./Update.vue";
import VDelete from "./Delete.vue";
import VServerStart from "./ServerStart.vue";
import VServerStatus from "./ServerStatus.vue";
import VServerStop from "./ServerStop.vue";

import VClientStart from "./ClientStart.vue";
import VClientStatus from "./ClientStatus.vue";
import VClientStop from "./ClientStop.vue";

export default {
    components: {
        VCreate,
        VUpdate,
        VDelete,
        VServerStart,
        VServerStop,
        VServerStatus,
        VClientStart,
        VClientStatus,
        VClientStop,
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
        async copyToClipboard(text) {
            try {
                await navigator.clipboard.writeText(text);
                this.$q.notify({
                    type: "positive",
                    message: "Copied to clipboard",
                    timeout: 3000,
                });
            } catch (err) {}
        },

        async getServers() {
            try {
                const res = await this.$api.get("/api/servers", {
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
</style>
