<template>
    <q-dialog v-model="dialog" persistent>
        <q-card class="q-pa-md">
            <q-card-section class="row items-center">
                <q-icon name="mdi-delete-alert" size="md" color="primary" />
                <div class="q-ml-sm text-h6">Confirm Delete Interface</div>
            </q-card-section>

            <q-card-section>
                Are you sure you want to delete this network interface? This
                action cannot be undone.
            </q-card-section>

            <q-card-actions align="right">
                <q-btn flat label="Disagree" v-close-popup />
                <q-btn
                    outline
                    label="Agree"
                    color="red"
                    @click="deleteWg(wg)"
                />
            </q-card-actions>
        </q-card>
    </q-dialog>

    <q-btn
        outline
        rounded
        color="red"
        icon="mdi-delete-empty"
        @click="dialog = true"
    >
    <q-tooltip class="bg-purple text-body2" :offset="[10, 10]">
        Delete this networks
    </q-tooltip>
    </q-btn>
</template>

<script>
export default {
    props: ["wg"],

    emits: ["deleted"],

    data() {
        return {
            dialog: false,
        };
    },

    methods: {
        async deleteWg(item) {
            try {
                const res = await this.$api.delete(item.links.delete);
                if (res.status === 200) {
                    this.$emit("deleted", res.data);
                    this.$q.notify({
                        type: "positive",
                        message: "Delete successfully",
                    });
                }
            } catch (err) {
                if ([403, 404, 500].includes(err.response?.status)) {
                    this.$q.notify({
                        type: "negative",
                        message: err.response.data.message,
                    });
                }
            } finally {
                this.dialog = false;
            }
        },
    },
};
</script>
