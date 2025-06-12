<template>
    <q-dialog v-model="dialog" persistent>
        <q-card class="q-pa-md" style="max-width: 400px">
            <q-card-section class="row items-center">
                <div>
                    <div class="text-h6 flex justify-between">
                        <span>
                            {{ title }}
                        </span>

                        <q-icon
                            name="mdi-reload"
                            color="primary"
                            size="md"
                            class="q-mr-sm"
                        />
                    </div>
                    <div class="text-body2">{{ message }}</div>
                </div>
            </q-card-section>

            <q-card-actions align="right">
                <q-btn flat label="Reload" color="grey" @click="reload(wg)" />
            </q-card-actions>
        </q-card>
    </q-dialog>

    <q-btn color="red" outline rounded @click="dialog = true" icon="mdi-reload">
        <q-tooltip class="bg-purple text-body2" :offset="[10, 10]">
            Force this network to reload
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
            title: "Force Restart Network Interface",
            message:
                "Are you sure you want to force a restart of this network interface? It will temporarily disconnect and reinitialize to apply the changes.",
        };
    },

    methods: {
        async reload(item) {
            try {
                const res = await this.$api.put(item.links.reload);

                if (res.status === 201) {
                    this.$q.notify({
                        type: "positive",
                        message: "Network Interface reloaded successfully",
                    });
                    this.dialog = false;
                    this.$emit("updated", true);
                }
            } catch (err) {
                if (err.response) {
                    this.$q.notify({
                        type: "negative",
                        message:
                            err.response.data.message || "An error occurred",
                    });
                }
                this.dialog = false;
            }
        },
    },
};
</script>
