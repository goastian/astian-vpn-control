<template>
    <q-dialog v-model="dialog" persistent>
        <q-card class="w-[900px]">
            <q-card-section class="row items-center q-pb-none">
                <q-icon name="mdi-server" size="sm" />
                <span class="q-ml-sm text-h6">Add Server</span>
            </q-card-section>

            <q-card-section>
                <q-form @submit.prevent="updateServer(form)">
                    <div class="row q-col-gutter-md">
                        <div class="col-12 col-md-6">
                            <q-input
                                v-model="form.country"
                                label="Country"
                                :error="!!errors.country"
                                :error-message="errors.country"
                            />
                        </div>

                        <div class="col-12 col-md-6">
                            <q-input
                                v-model="form.url"
                                label="URL"
                                :error="!!errors.url"
                                :error-message="errors.url"
                            />
                        </div>

                        <div class="col-12 col-md-6">
                            <q-input
                                v-model="form.port"
                                label="Port"
                                :error="!!errors.port"
                                :error-message="errors.port"
                            />
                        </div>

                        <div class="col-12 col-md-6">
                            <q-input
                                v-model="form.ss_port"
                                label="Shadowsocks Port"
                                filled
                                type="number"
                                class="mb-4 col-12 col-md-6"
                                :error="!!errors.ss_port"
                            >
                                <template v-slot:error>
                                    <v-error :error="errors.ss_port"></v-error>
                                </template>
                            </q-input>
                        </div>

                        <div class="col-12 col-md-6">
                            <q-select
                                v-model="form.ss_method"
                                :options="ciphers"
                                label="Shadowsocks Ciphers"
                                class="mb-4 col-12 col-md-6"
                            />
                        </div>

                        <div class="col-12 col-md-6">
                            <q-checkbox
                                v-model="form.generate_password"
                                label="Generate new password"
                                color="orange"
                            />
                        </div>
                    </div>
                </q-form>
            </q-card-section>

            <q-separator />

            <q-card-actions align="right">
                <q-btn flat label="Close" @click="dialog = false" />
                <q-btn
                    v-if="!form.deleted"
                    color="primary"
                    label="Update"
                    @click="updateServer(form)"
                />
            </q-card-actions>
        </q-card>
    </q-dialog>

    <q-btn flat round dense color="blue" @click="loadData">
        <q-icon name="mdi-file-edit" />
    </q-btn>
</template>

<script>
export default {
    props: ["item"],
    emits: ["updated"],

    data() {
        return {
            dialog: false,
            errors: {},
            form: {},
            ciphers: ["chacha20-ietf-poly1305", "aes-256-gcm", "aes-128-gcm"],
        };
    },

    methods: {
        loadData() {
            this.dialog = true;
            this.form = { ...this.item };
            Object.assign(this.form, { generate_password: false });
        },

        async updateServer(item) {
            try {
                const res = await this.$api.put(item.links.update, this.form, {
                    headers: {
                        "Content-Type": "application/x-www-form-urlencoded",
                    },
                });

                if (res.status === 201) {
                    this.dialog = false;
                    this.errors = {};
                    this.$emit("updated", res.data.data);
                    this.$q.notify({
                        type: "positive",
                        message: "Updated successfully",
                    });
                }
            } catch (err) {
                if (err.response?.status === 422) {
                    this.errors = err.response.data.errors;
                } else if ([403, 404, 500].includes(err.response?.status)) {
                    this.$q.notify({
                        type: "negative",
                        message: err.response.data.message,
                    });
                }
            }
        },
    },
};
</script>
