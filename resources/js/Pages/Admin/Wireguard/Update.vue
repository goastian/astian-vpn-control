<template>
    <q-dialog v-model="dialog" persistent>
        <q-card style="width: 500px; max-width: 90vw">
            <q-card-section class="row items-center">
                <q-icon
                    name="mdi-vpn"
                    size="28px"
                    class="q-mr-md text-primary"
                />
                <div class="text-h5">Update WireGuard Interface</div>
            </q-card-section>

            <q-separator />

            <q-card-section>
                <q-input
                    v-model="form.dns"
                    label="DNS Server"
                    placeholder="1.1.1.1, 2.3.3.3"
                    outlined
                    dense
                    :error="!!errors.dns"
                >
                    <template v-slot:error>
                        <v-error :error="errors.dns"></v-error>
                    </template>
                </q-input>

                <q-checkbox
                    v-model="form.enable_dns"
                    label="Enable DNS"
                    color="orange"
                    class="col-12"
                    :error="!!errors.enable_dns"
                >
                    <template v-slot:error>
                        <v-error :error="errors.enable_dns"></v-error>
                    </template>
                </q-checkbox>
            </q-card-section>

            <q-card-actions align="right">
                <q-btn flat label="Close" v-close-popup />
                <q-btn color="primary" label="Update" @click="updateWG" />
            </q-card-actions>
        </q-card>
    </q-dialog>

    <q-btn outline rounded color="primary" icon="mdi-file-edit" @click="open" >
        <q-tooltip class="bg-purple text-body2" :offset="[10, 10]">
          Update DNS
        </q-tooltip>
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
            form: {},
        };
    },

    methods: {
        open() {
            this.form = { ...this.wg };
            this.dialog = true;
        },
        async updateWG() {
            try {
                const res = await this.$api.put(
                    this.wg.links.update,
                    this.form,
                    {
                        headers: {
                            "Content-Type": "application/x-www-form-urlencoded",
                        },
                    }
                );

                if (res.status === 200) {
                    this.dialog = false;
                    this.errors = {};
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
