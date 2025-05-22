<template>
    <q-dialog v-model="dialog" persistent class="q-pa-md">
        <q-card class="server-card">
            <q-bar>
                <div class="col row justify-between items-center">
                    <div class="row items-center">
                        <q-icon name="mdi-server" />
                        <div class="q-ml-sm">Add Server</div>
                    </div>
                    <div>
                        <q-btn
                            dense
                            flat
                            icon="close"
                            @click="dialog = false"
                        />
                    </div>
                </div>
            </q-bar>

            <q-card-section>
                <q-form @submit.prevent="createServer">
                    <div class="row q-col-gutter-md">
                        <div class="mb-4 col-12 col-md-6">
                            <q-input
                                v-model="form.country"
                                label="Country"
                                filled
                                :error="!!errors.country"
                            />
                            <v-error :error="errors.country"></v-error>
                        </div>

                        <div class="mb-4 col-12 col-md-6">
                            <q-input
                                v-model="form.ip"
                                label="IP Address"
                                filled
                                :error="!!errors.ip"
                            />
                            <v-error :error="errors.ip"></v-error>
                        </div>
                        <div class="mb-4 col-12 col-md-6">
                            <q-input
                                v-model="form.port"
                                label="Port"
                                filled
                                type="number"
                                :error="!!errors.port"
                            />
                            <v-error :error="errors.port"></v-error>
                        </div>
                    </div>
                </q-form>
            </q-card-section>

            <q-card-actions align="right">
                <q-btn flat label="Close" @click="dialog = false" />
                <q-btn
                    label="Save"
                    :disable="disabled"
                    color="primary"
                    @click="createServer"
                />
            </q-card-actions>
        </q-card>
    </q-dialog>

    <q-btn icon="mdi-server-plus-outline" color="blue" round @click="open">
        <q-tooltip class="bg-indigo" :offset="[10, 10]"
            >Add a new server</q-tooltip
        >
    </q-btn>
</template>

<script>
export default {
    emits: ["created"],

    data() {
        return {
            dialog: false,
            disabled: false,
            form: {},
            errors: {},
        };
    },

    methods: {
        open() {
            this.form = {
                country: "",
                ip: "",
                port: 50051,
            };

            this.dialog = true;
        },

        async createServer() {
            this.disabled = true;
            try {
                const res = await this.$api.post("/api/servers", this.form, {
                    headers: {
                        "Content-Type": "multipart/form-data",
                    },
                });

                if (res.status === 201) {
                    this.dialog = false;
                    this.errors = {};
                    this.$emit("created", res.data.data);
                    this.$q.notify({
                        type: "positive",
                        message: "Server added!",
                    });
                }
            } catch (err) {
                if (err.response?.status === 422) {
                    this.errors = err.response.data.errors;
                } else {
                    this.$q.notify({
                        type: "negative",
                        message:
                            err.response?.data.message || "An error occurred",
                    });
                }
            } finally {
                this.disabled = false;
            }
        },
    },
};
</script>

<style lang="css" scoped>
.server-card {
    width: 100%;
    max-width: 800px;
}
</style>
