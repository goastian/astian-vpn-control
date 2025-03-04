<template>
    <v-dialog v-model="dialog" persistent max-width="400">
        <template v-slot:activator="{ props: activatorProps }">
            <v-btn
                v-bind="activatorProps"
                variant="tonal"
                color="red-lighten-1"
            >
                Force Reload
            </v-btn> 
        </template>

        <v-card prepend-icon="mdi-map-marker" :text="title" :title="message">
            <template v-slot:actions>
                <v-spacer></v-spacer>

                <v-btn @click="dialog = false"> Disagree </v-btn>

                <v-btn @click="reload(wg)"> Agree </v-btn>
            </template>
        </v-card>
    </v-dialog>
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
        /**
         * Delete Server
         */
        async reload(item) {
            try {
                const res = await this.$api.put(item.links.reload, {
                    headers: {
                        "Content-Type": "application/x-www-form-urlencoded",
                    },
                });
                if (res.status == 201) {
                    this.$notification.success(
                        "Network Interface reloaded successfully"
                    );
                    this.dialog = false;
                    this.$emit("updated", true);
                }
            } catch (err) {
                if (err.response.status == 403) {
                    this.$notification.error(err.response.data.message);
                }

                if (err.response.status == 404) {
                    this.$notification.error(err.response.data.message);
                }

                if (err.response.status == 422) {
                    this.$notification.error(err.response.data.message);
                }
                if (err.response.status == 500) {
                    this.$notification.error(err.response.data.message);
                }
                this.dialog = false;
            }
        },
    },
};
</script>
