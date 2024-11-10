<template>
    <v-dialog v-model="dialog" max-width="1000" with="100">
        <template v-slot:activator="{ props: activatorProps }">
            <v-btn
                class="text-none font-weight-regular mx-4"
                icon
                variant="tonal"
                v-bind="activatorProps"
                color="blue-lighten-1"
            >
                <v-icon>
                    {{ $utils.toKebabCase("mdiFileEdit") }}
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
                            v-model="wg.name"
                            disabled
                        >
                        </v-text-field>
                    </v-col>

                    <v-col cols="12" md="6">
                        <v-text-field
                            label="Listen Port"
                            v-model="wg.listen_port"
                            disabled
                        >
                        </v-text-field>
                    </v-col>

                    <v-col cols="12" md="6">
                        <v-text-field
                            label="Server"
                            v-model="wg.server.ipv4"
                            disabled
                        >
                            <template #label>
                                {{ wg.server.country }}
                            </template>
                        </v-text-field>
                    </v-col>

                    <v-col cols="12" md="6">
                        <v-text-field label="DNS 1" v-model="wg.dns_1">
                            <template #details>
                                <v-error :error="errors.dns_1"></v-error>
                            </template>
                        </v-text-field>
                    </v-col>

                    <v-col cols="12" md="6">
                        <v-text-field label="DNS 2" v-model="wg.dns_2">
                            <template #details>
                                <v-error :error="errors.dns_2"></v-error>
                            </template>
                        </v-text-field>
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
                    @click="updateWG"
                ></v-btn>
            </v-card-actions>
        </v-card>
    </v-dialog>
</template>
<script>
export default {
    emit: ["updated"],

    props: ["wg"],

    data() {
        return {
            dialog: false,
            servers: [],
            errors: {},
        };
    },

    methods: {
        /**
         * Create a new server
         */
        async updateWG() {
            try {
                const res = await this.$api.put(this.wg.links.update, this.wg);

                if (res.status == 201) {
                    this.dialog = false;
                    this.errors = {};
                    this.form = {};
                    this.$emit("updated", res.data.data);
                }
            } catch (err) {
                if (err.response && err.response.status == 422) {
                    this.errors = err.response.data.errors;
                }
            }
        },
    },
};
</script>
