<template>
    <v-dialog v-model="dialog" persistent max-width="400">
        <template v-slot:activator="{ props: activatorProps }">
            <v-btn
                v-bind="activatorProps"
                variant="text"
                color="red-lighten-1"
                icon
            >
                <v-icon>
                    {{ $utils.toKebabCase("mdiServerRemove") }}
                </v-icon>
            </v-btn>
        </template>

        <v-card
            prepend-icon="mdi-map-marker"
            text="Are you sure you want to delete the server?. This action cannot be undone."
            title="Confirm Deletion"
        >
            <template v-slot:actions>
                <v-spacer></v-spacer>

                <v-btn @click="dialog = false"> Disagree </v-btn>

                <v-btn @click="deleteServer(item)"> Agree </v-btn>
            </template>
        </v-card>
    </v-dialog>
</template>
<script>
export default {
    props: ["item"],

    emits:['deleted'],

    data() {
        return {
            dialog: false,
        };
    },

    methods: {
        /**
         * Delete Server
         */
        async deleteServer(item) {            
            try {
                const res = await this.$api.delete(item.links.delete);
                if (res.status == 200) {
                    this.$emit("deleted", res.data);
                    this.dialog = false
                }
            } catch (err) {}
        },
    },
};
</script>
