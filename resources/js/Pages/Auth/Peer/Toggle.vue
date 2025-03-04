<template>
    <v-dialog v-model="dialog" persistent max-width="400">
        <template v-slot:activator="{ props: activatorProps }">
            <v-btn
                v-bind="activatorProps"
                variant="tonal"
                color="red-lighten-1"
                icon 
            >
                <v-icon
                    :color="!peer.active ? 'red-accent-4' : 'green-accent-4'"
                >
                    {{ $utils.toKebabCase("mdiCheckboxBlankCircle") }}
                </v-icon>
            </v-btn>
        </template>

        <v-card
            prepend-icon="mdi-map-marker"
            :text="!peer.active ? active.message : inactive.message"
            :title="!peer.active ? active.title : inactive.title"
        >
            <template v-slot:actions>
                <v-spacer></v-spacer>

                <v-btn @click="dialog = false"> Disagree </v-btn>

                <v-btn @click="toggle(peer)"> Agree </v-btn>
            </template>
        </v-card>
    </v-dialog>
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
        };
    },

    methods: {
        /**
         * Delete Server
         */
        async toggle(item) {
            try {
                const res = await this.$api.put(item.links.toggle, {
                    headers: {
                        "Content-Type": "application/x-www-form-urlencoded",
                    },
                });
                if (res.status == 201) {
                    this.$emit("updated", res.data);
                    this.dialog = false;
                }
            } catch (err) {
                if (err.response.status == 403) {
                    this.$notification.error(err.response.data.message);
                }
                if (err.response.status == 500) {
                    this.$notification.error(err.response.data.message);
                }

                if (err.response.status == 404) {
                    this.$notification.error(err.response.data.message);
                }

                if (err.response.status == 422) {
                    this.$notification.error(err.response.data.message);
                }
                this.dialog = false;
            }
        },
    },
};
</script>
