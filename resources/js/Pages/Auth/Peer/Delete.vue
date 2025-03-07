<template>
    <q-dialog v-model="dialog" persistent>
        <template v-slot:activator="{}">
            <q-btn
                flat
                round
                color="red"
                icon="mdi-delete-empty"
                @click="dialog = true"
            />
        </template>

        <q-card class="q-pa-md">
            <q-card-section>
                <div class="text-h6">Confirm Delete Peer</div>
            </q-card-section>

            <q-card-section class="q-pt-none">
                <q-icon name="mdi-map-marker" size="md" class="q-mr-sm" />
                Are you sure you want to delete this peer? This action cannot be
                undone.
            </q-card-section>

            <q-card-actions align="right">
                <q-space />
                <q-btn
                    flat
                    label="Disagree"
                    color="grey"
                    @click="dialog = false"
                />
                <q-btn
                    unelevated
                    label="Agree"
                    color="red"
                    @click="deletePeer(peer)"
                />
            </q-card-actions>
        </q-card>
    </q-dialog>

    <q-btn
        icon="mdi-trash-can-outline"
        color="red"
        round
        @click="dialog = true"
    >
        <q-tooltip class="bg-indigo" :offset="[10, 10]">
            Delete peer
        </q-tooltip>
    </q-btn>
</template>

<script>
export default {
    props: ["peer"],
    emits: ["deleted"],
    data() {
        return {
            dialog: false,
        };
    },
    methods: {
        /**
         * Delete Peer
         */
        async deletePeer(item) {
            try {
                const res = await this.$api.delete(item.links.delete);
                if (res.status == 200) {
                    this.$emit("deleted", res.data);
                    this.dialog = false;
                }
            } catch (err) {
                if (err.response) {
                    this.$q.notify({
                        type: "negative",
                        message: err.response.data.message,
                    });
                }
                this.dialog = false;
            }
        },
    },
};
</script>
