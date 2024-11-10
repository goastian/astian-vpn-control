<template>
    <v-dialog v-model="dialog" max-width="1000" with="100">
        <template v-slot:activator="{ props: activatorProps }">
            <v-btn
                class="text-none font-weight-regular mx-4"
                icon
                variant="tonal"
                v-bind="activatorProps"
                @click="loadData"
            >
                <v-icon color="blue-lighten-1" :icon="$utils.toKebabCase('mdiVpn')">
                </v-icon>
            </v-btn>
        </template>

        <v-card
            :prepend-icon="$utils.toKebabCase('mdiVpn')"
            title="Add new wireguard Interface"
        >
            <v-card-text>
                <v-row dense>
                    <v-col cols="12" md="6">
                        <v-text-field
                            label="Name"
                            v-model="form.name"
                        >
                            <template #details>
                                <v-error :error="errors.name"></v-error>
                            </template>
                        </v-text-field>
                    </v-col>

                    <v-col cols="12" md="6">
                        <v-text-field
                            label="Listen Port"
                            v-model="form.listen_port"
                        >
                            <template #details>
                                <v-error :error="errors.listen_port"></v-error>
                            </template>
                        </v-text-field>
                    </v-col>

                    <v-col cols="12" md="6">
                        <v-text-field label="DNS 1" v-model="form.dns_1">
                            <template #details>
                                <v-error :error="errors.dns_1"></v-error>
                            </template>
                        </v-text-field>
                    </v-col>

                    <v-col cols="12" md="6">
                        <v-text-field label="DNS 2" v-model="form.dns_2">
                            <template #details>
                                <v-error :error="errors.dns_2"></v-error>
                            </template>
                        </v-text-field>
                    </v-col>

                    <v-col cols="12">
                        <v-select
                            v-model="form.server_id"
                            :items="servers"
                            item-title="country"
                            item-value="id"
                            single-line
                        >
                            <template #selection="{ item }">
                                <span class="text-body">
                                    {{ item.raw.country }}
                                    <strong v-if="item.raw.ipv4">
                                        : ipv4 {{ item.raw.ipv4 }}
                                    </strong>
                                    -
                                    <strong v-if="item.raw.ipv6">
                                        : ipv6 {{ item.raw.ipv6 }}
                                    </strong>
                                </span>
                            </template>
                        </v-select>
                        <v-error :error="errors.server_id"></v-error>
                    </v-col>
                </v-row>
            </v-card-text>

            <v-divider></v-divider>
            <v-card-actions>
                <v-spacer></v-spacer>

                <v-btn
                    text="Close"
                    variant="plain"
                    @click="dialog = false"
                ></v-btn>

                <v-btn
                    color="primary"
                    text="Save"
                    variant="tonal"
                    @click="createWG"
                ></v-btn>
            </v-card-actions>
        </v-card>
    </v-dialog>
</template>
<script>
export default {
    emit: ["created"],

    data() {
        return {
            dialog: false,
            servers: [],
            form: {
                name: "",
                listen_port: "",
                dns_1: "",
                dns_2: "",
                server_id: "",
            },
            errors: {},
        };
    },

    methods: {
        loadData() {
            this.getServers();
            this.form.listen_port = "51820";
        },

        /**
         * Create a new server
         */
        async createWG() {
            try {
                const res = await this.$api.post("/api/wgs", this.form);

                if (res.status == 201) {
                    this.dialog = false;
                    this.errors = {};
                    this.form = {};
                    this.$emit("created", res.data.data);
                }
            } catch (err) {
                if (err.response && err.response.status == 422) {
                    this.errors = err.response.data.errors;
                }
            }
        },

        /**
         * Retrieve the servers
         */
        async getServers() {
            try {
                const res = await this.$api.get("/api/servers");

                if (res.status == 200) {
                    this.servers = res.data.data;
                }
            } catch (err) {}
        },
    },
};
</script>
