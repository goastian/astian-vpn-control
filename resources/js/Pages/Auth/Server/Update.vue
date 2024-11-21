<template>
    <v-dialog v-model="dialog" max-width="900" with="100">
        <template v-slot:activator="{ props: activatorProps }">
            <v-btn
                class="text-none font-weight-regular mx-4"
                icon
                variant="text"
                color="blue-lighten-1"
                v-bind="activatorProps"
            >
                <v-icon>
                    {{ $utils.toKebabCase("mdiServerNetworkOutline") }}
                </v-icon>
            </v-btn>
        </template>

        <v-card
            :prepend-icon="$utils.toKebabCase('mdiServer')"
            title="Add Server"
        >
            <v-card-text>
                <v-row dense>
                    <v-col cols="12" md="6">
                        <v-text-field label="Country" v-model="item.country">
                            <template #details>
                                <v-error :error="errors.country"></v-error>
                            </template>
                        </v-text-field>
                    </v-col>

                    <v-col cols="12" md="6">
                        <v-text-field label="IPv4" v-model="item.ipv4">
                            <template #details>
                                <v-error :error="errors.ipv4"></v-error>
                            </template>
                        </v-text-field>
                    </v-col>

                    <v-col cols="12" md="6">
                        <v-text-field label="Port" v-model="item.port">
                            <template #details>
                                <v-error :error="errors.port"></v-error>
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
                    v-if="!item.deleted"
                    color="primary"
                    text="Update"
                    variant="tonal"
                    @click="updateServer"
                ></v-btn>
            </v-card-actions>
        </v-card>
    </v-dialog>
</template>
<script>
export default {
    props: ["item"],

    emit: ["updated"],

    data() {
        return {
            dialog: false,
            errors: {},
        };
    },

    methods: {
        /**
         * Update server
         */
        async updateServer() {
            try {
                const res = await this.$api.put("/api/servers", this.item);

                if (res.status == 201) {
                    this.errors = {};
                    this.form = {};
                    this.$emit("updated", res.data.data);
                }
            } catch (err) {
                if (err.response && err.response.status == 422) {
                    this.errors = err.response.data.errors;
                }
            }
            s;
        },
    },
};
</script>
