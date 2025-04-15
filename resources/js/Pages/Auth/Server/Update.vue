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
                <q-form @submit.prevent="updateServer">
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

                        <q-separator class="full-width q-mt-md" />
                        <div class="full-width text-bold q-mt-md">
                            Shadowsocks Settings
                        </div>

                        <div class="mb-4 col-12 col-md-6">
                            <q-input
                                v-model="form.ss_port"
                                label="Shadowsocks Port"
                                filled
                                type="number"
                                :error="!!errors.ss_port"
                            />
                            <v-error :error="errors.ss_port"></v-error>
                        </div>

                        <div class="mb-4 col-12 col-md-6">
                            <q-select
                                v-model="form.ss_method"
                                :options="ciphers"
                                label="Shadowsocks Ciphers"
                                :error="!!errors.ss_method"
                            />
                            <v-error :error="errors.ss_method"></v-error>
                        </div>
                        

                        <div class="mb-4 col-12 col-md-6">
                            <q-input
                                v-model="form.dns"
                                label="DNS Servers"
                                placeholder="1.1.1.1, 2.2.2.2"
                            />
                            <v-error :error="errors.dns"></v-error>
                        </div>
                    </div>
                </q-form>
            </q-card-section>

            <q-card-actions align="right">
                <q-btn flat label="Close" @click="dialog = false" />
                <q-btn
                    label="Update"
                    :disable="disabled"
                    color="primary"
                    @click="updateServer"
                />
            </q-card-actions>
        </q-card>
    </q-dialog>

    <q-btn icon="mdi-file-edit" color="blue" round @click="loadData">
        <q-tooltip class="bg-indigo" :offset="[10, 10]">Edit server</q-tooltip>
    </q-btn>
</template>

<script>
export default {
    props: ["item"],
    emits: ["updated"],

    data() {
        return {
            dialog: false,
            form: {},
            ciphers: ["chacha20-ietf-poly1305", "aes-256-gcm", "aes-128-gcm"],
            errors: {},
            disabled: false,
        };
    },

    methods: {
        loadData() {
            this.form = { ...this.item };
            this.dialog = true;
        },

        async updateServer() {
            this.disable = true;
            try {
                const res = await this.$api.put(
                    this.form.links.update,
                    this.form,
                    {
                        headers: {
                            "Content-Type": "application/x-www-form-urlencoded",
                        },
                    }
                );

                if (res.status === 201) {
                    this.dialog = false;
                    this.errors = {};
                    this.$emit("updated", res.data.data);
                    this.$q.notify({
                        type: "positive",
                        message: "Server updated!",
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
