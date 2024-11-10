<template>
    <v-dialog v-model="dialog" max-width="900" with="100">
        <template v-slot:activator="{ props: activatorProps }">
            <v-btn
                class="text-none font-weight-regular mx-4"
                icon
                variant="tonal"
                v-bind="activatorProps"
            >
                <v-icon
                    color="blue-lighten-1"
                    :icon="$utils.toKebabCase('mdiServerPlusOutline')"
                >
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
                        <v-text-field label="Country" v-model="form.country">
                            <template #details>
                                <v-error :error="errors.country"></v-error>
                            </template>
                        </v-text-field>
                    </v-col>

                    <v-col cols="12" md="6">
                        <v-text-field label="City" v-model="form.city">
                            <template #details>
                                <v-error :error="errors.city"></v-error>
                            </template>
                        </v-text-field>
                    </v-col>

                    <v-col cols="12" md="6">
                        <v-text-field label="IPv4" v-model="form.ipv4">
                            <template #details>
                                <v-error :error="errors.ipv4"></v-error>
                            </template>
                        </v-text-field>
                    </v-col>

                    <!--<v-col cols="12" md="6">
                        <v-text-field label="IPv6" v-model="form.ipv6">
                            <template #details>
                                <v-error :error="errors.ipv6"></v-error>
                            </template>
                        </v-text-field>
                    </v-col>-->

                    <v-col cols="6">
                        <v-text-field label="Domain" v-model="form.domain">
                            <template #details>
                                <v-error :error="errors.domain"></v-error>
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
                    @click="createServer"
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
            form: {
                country: "",
                city: "",
                ipv4: "",
                ipv6: "",
                domain: "",
            },
            errors: {},
        };
    },

    methods: {
        /**
         * Create a new server
         */
        async createServer() {
            try {
                const res = await this.$api.post("/api/servers", this.form);

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
    },
};
</script>
