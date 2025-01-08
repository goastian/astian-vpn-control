<template>
    <v-dialog v-model="dialog" max-width="900" with="100">
        <template v-slot:activator="{ props: activatorProps }">
            <v-btn
                class="text-none font-weight-regular mx-4"
                icon
                variant="tonal"
                v-bind="activatorProps"
                color="blue-lighten-1"
                @click="setItem(item)"
            >
                <v-icon :icon="$utils.toKebabCase('mdiServerPlusOutline')">
                </v-icon>
            </v-btn>
        </template>

        <v-card
            :prepend-icon="$utils.toKebabCase('mdiServer')"
            title="Add new key"
        >
            <v-card-text>
                <v-row dense>
                    <v-col cols="12" md="6">
                        <v-text-field label="Key" v-model="form.key">
                            <template #details>
                                <v-error :error="errors.key"></v-error>
                            </template>
                        </v-text-field>
                    </v-col>

                    <v-col sm="6">
                        <v-checkbox v-model="form.system" label="System">
                            <template #details>
                                <v-error :error="errors.system"></v-error>
                            </template>
                        </v-checkbox>
                    </v-col>

                    <v-col sm="12">
                        <v-textarea
                            v-model="form.value"
                            label="Value"
                            variant="outlined"
                        >
                            <template #details>
                                <v-error :error="errors.system"></v-error>
                            </template>
                        </v-textarea>
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

    props: ["item"],

    data() {
        return {
            dialog: false,
            form: {
                key: "",
                value: "",
                system: true,
            },
            errors: {},
        };
    },

    methods: {
        setItem(item) {
            if (item) {
                this.form = item;
            }
        },
        /**
         * Create a new server
         */
        async createServer() {
            try {
                const res = await this.$api.post("/api/settings", this.form, {
                    headers: {
                        "Content-Type": "multipart/form-data",
                    },
                });

                if (res.status == 201) {
                    this.dialog = false;
                    this.errors = {};
                    this.form = {};
                    this.$emit("created", true);
                }
            } catch (err) {
                if (err.response && err.response.status == 422) {
                    this.errors = err.response.data.errors;
                }
                if (err.response.status == 404) {
                    this.$notification.error(err.response.data.message);
                }
                if (err.response.status == 403) {
                    this.$notification.error(err.response.data.message);
                }
                if (err.response.status == 500) {
                    this.$notification.error(err.response.data.message);
                }
            }
        },
    },
};
</script>
