<template>
    <q-dialog v-model="dialog" persistent>
        <q-card class="q-pa-md" style="max-width: 1000px">
            <q-card-section class="row items-center">
                <q-icon
                    name="mdi-vpn"
                    color="primary"
                    size="md"
                    class="q-mr-sm"
                />
                <div class="text-h6">Add new WireGuard Interface</div>
            </q-card-section>

            <q-card-section>
                <q-form @submit.prevent="updateWG">
                    <q-input label="Name" v-model="wg.name" readonly />

                    <q-input
                        label="Listen Port"
                        v-model="wg.listen_port"
                        readonly
                    />

                    <q-input label="Server" v-model="wg.url" readonly>
                        <template #label> {{ wg.country }} </template>
                    </q-input>

                    <q-input
                        label="DNS 1"
                        v-model="wg.dns_1"
                        :error="!!errors.dns_1"
                        :error-message="errors.dns_1"
                    />

                    <q-input
                        label="DNS 2"
                        v-model="wg.dns_2"
                        :error="!!errors.dns_2"
                        :error-message="errors.dns_2"
                    />
                </q-form>
            </q-card-section>

            <q-separator />

            <q-card-actions align="right">
                <q-btn
                    flat
                    label="Close"
                    color="grey"
                    @click="dialog = false"
                />
                <q-btn label="Save" color="primary" @click="updateWG" />
            </q-card-actions>
        </q-card>
    </q-dialog>

    <q-btn flat round dense color="blue" @click="dialog = true">
        <q-icon name="mdi-file-edit" />
    </q-btn>
</template>

<script>
export default {
    props: ["wg"],

    emits: ["updated"],

    data() {
        return {
            dialog: false,
            errors: {},
        };
    },

    methods: {
        async updateWG() {
            try {
                const res = await this.$api.put(this.wg.links.update, this.wg, {
                    headers: {
                        "Content-Type": "application/x-www-form-urlencoded",
                    },
                });

                if (res.status === 201) {
                    this.dialog = false;
                    this.errors = {};
                    this.$emit("updated", res.data.data);
                }
            } catch (err) {
                if (err.response?.status === 422) {
                    this.errors = err.response.data.errors;
                } else {
                    this.$q.notify({
                        type: "negative",
                        message:
                            err.response?.data?.message || "An error occurred",
                    });
                }
                this.dialog = false;
            }
        },
    },
};
</script>
