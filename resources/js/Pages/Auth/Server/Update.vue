<template>
    <q-dialog v-model="dialog" persistent>
        <template v-slot:activator="{ toggle }">
            <q-btn
                flat
                round
                color="blue"
                icon="mdi-server-network-outline"
                class="q-mx-md"
                @click="toggle"
            />
        </template>

        <q-card class="w-[900px]">
            <q-card-section class="row items-center q-pb-none">
                <q-icon name="mdi-server" size="sm" />
                <span class="q-ml-sm text-h6">Add Server</span>
            </q-card-section>

            <q-card-section>
                <q-form @submit.prevent="updateServer(item)">
                    <div class="row q-col-gutter-md">
                        <div class="col-12 col-md-6">
                            <q-input
                                v-model="item.country"
                                label="Country"
                                :error="!!errors.country"
                                :error-message="errors.country"
                            />
                        </div>

                        <div class="col-12 col-md-6">
                            <q-input
                                v-model="item.url"
                                label="URL"
                                :error="!!errors.url"
                                :error-message="errors.url"
                            />
                        </div>

                        <div class="col-12 col-md-6">
                            <q-input
                                v-model="item.port"
                                label="Port"
                                :error="!!errors.port"
                                :error-message="errors.port"
                            />
                        </div>
                    </div>
                </q-form>
            </q-card-section>

            <q-separator />

            <q-card-actions align="right">
                <q-btn flat label="Close" @click="dialog = false" />
                <q-btn
                    v-if="!item.deleted"
                    color="primary"
                    label="Update"
                    @click="updateServer(item)"
                />
            </q-card-actions>
        </q-card>
    </q-dialog>

    <q-btn flat round dense color="blue" @click="dialog = true">
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
        };
    },

    methods: {
        async updateServer(item) {
            try {
                const res = await this.$api.put(item.links.update, this.item, {
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
