<template>
    <q-dialog v-model="dialog" persistent>
        <q-card class="q-pa-md" style="max-width: 400px">
            <q-card-section class="row items-center">
                <div>
                    <div class="text-h6">
                        {{ dialogTitle }}
                        <q-icon
                            name="mdi-access-point"
                            color="primary"
                            size="md"
                            class="q-mr-sm"
                        />
                    </div>
                    <div class="text-body2">{{ dialogMessage }}</div>
                </div>
            </q-card-section>

            <q-card-actions align="right">
                <q-btn
                    flat
                    label="Disagree"
                    color="grey"
                    @click="dialog = false"
                />
                <q-btn
                    label="Agree"
                    color="red"
                    @click="toggle(wg)"
                    :disabled="processing"
                />
            </q-card-actions>
        </q-card>
    </q-dialog>

    <q-btn
        outline
        rounded
        :color="wg.active ? 'green' : 'red'"
        @click="dialog = true"
        :icon="wg.active ? 'mdi-access-point' : 'mdi-access-point-remove'"
    >
        <q-tooltip class="bg-purple text-body2" :offset="[10, 10]">
            {{ wg.active ? "Turn off" : "Turn on" }}
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
            active: {
                title: "Activate Network Interface",
                message:
                    "Are you sure you want to activate this network interface? It will be ready for use once activated.",
            },
            inactive: {
                title: "Deactivate Network Interface",
                message:
                    "Are you sure you want to deactivate this network interface? This action will disconnect any active connections and may affect network access.",
            },
            processing: false,
        };
    },

    computed: {
        dialogTitle() {
            return this.wg.active ? this.inactive.title : this.active.title;
        },
        dialogMessage() {
            return this.wg.active ? this.inactive.message : this.active.message;
        },
    },

    methods: {
        async toggle(item) {
            this.processing = true;
            try {
                const res = await this.$api.put(item.links.toggle);

                if (res.status === 200) {
                    this.$emit("updated", res.data);
                    this.$q.notify({
                        type: "positive",
                        message: "The Peer state has been updated",
                    });
                }
            } catch (err) {
                this.$q.notify({
                    type: "negative",
                    message: err.response?.data?.message || "An error occurred",
                });
            } finally {
                this.processing = false;
                this.dialog = false;
            }
        },
    },
};
</script>
