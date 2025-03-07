<template>
    <q-dialog v-model="dialog" persistent>
        <template v-slot:activator="{}">
            <q-btn
                flat
                round
                :color="peer.active ? 'green' : 'red'"
                icon="mdi-checkbox-blank-circle"
                @click="dialog = true"
            />
        </template>

        <q-card class="q-pa-md">
            <q-card-section>
                <div class="text-h6">
                    {{ peer.active ? inactive.title : active.title }}
                </div>
            </q-card-section>

            <q-card-section class="q-pt-none">
                <q-icon name="mdi-map-marker" size="md" class="q-mr-sm" />
                {{ peer.active ? inactive.message : active.message }}
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
                    @click="toggle(peer)"
                    :disable="processing"
                />
            </q-card-actions>
        </q-card>
    </q-dialog>

    <q-btn
        icon="mdi-power"
        :color="peer.active ? 'green' : 'red'"
        round
        @click="dialog = true"
    >
        <q-tooltip class="bg-indigo" :offset="[10, 10]">
            {{ peer.active ? "Turn off this peer" : "Turn on this peer" }}
        </q-tooltip>
    </q-btn>
</template>

<script>
export default {
    props: ["peer"],
    emits: ["updated"],
    data() {
        return {
            dialog: false,
            active: {
                title: "Activate Peer",
                message:
                    "Are you sure you want to activate this peer? It will be ready for use once activated.",
            },
            inactive: {
                title: "Deactivate Peer",
                message:
                    "Are you sure you want to deactivate this peer? This action will disconnect any active connections and may affect network access.",
            },
            processing: false,
        };
    },
    methods: {
        /**
         * Toggle Peer Status
         */
        async toggle(item) {
            this.processing = true;
            try {
                const res = await this.$api.put(item.links.toggle);
                if (res.status == 200) {
                    this.$emit("updated", res.data);
                }
            } catch (err) {
                this.$q.notify({
                    type: "negative",
                    message:
                        err.response?.data?.message || "An error occurred.",
                });
            } finally {
                this.processing = false;
                this.dialog = false;
            }
        },
    },
};
</script>
