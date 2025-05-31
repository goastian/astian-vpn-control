<template>
    <q-dialog v-model="dialog" persistent>
        <template v-slot:activator="{ toggle }">
            <q-btn
                flat
                round
                color="red"
                icon="mdi-server-remove"
                @click="toggle"
            />
        </template>

        <q-card class="w-96">
            <q-card-section class="row items-center q-pb-none">
                <q-icon name="mdi-delete-empty" size="sm" />
                <span class="q-ml-sm text-h6">Confirm Deletion</span>
            </q-card-section>

            <q-card-section>
                Are you sure you want to delete the server? This action cannot
                be undone.
            </q-card-section>

            <q-card-actions align="right">
                <q-btn flat label="Disagree" @click="dialog = false" />
                <q-btn
                    color="red"
                    :disable="disabled"
                    label="Agree"
                    @click="deleteServer(item)"
                />
            </q-card-actions>
        </q-card>
    </q-dialog>

    <q-btn
        flat
        dense
        color="red"
        icon="mdi-delete-empty"
        @click="dialog = true"
    ></q-btn>
</template>

<script>
export default {
    props: ["item"],

    emits: ["deleted"],

    data() {
        return {
            dialog: false,
            disabled: false,
        };
    },

    methods: {
        async deleteServer(item) {
            this.disabled = true;
            try {
                const res = await this.$api.delete(item.links.delete);
                if (res.status === 200) {
                    this.$emit("deleted", res.data);
                }
            } catch (err) {
                if ([403, 400, 404, 500].includes(err.response?.status)) {
                    this.$q.notify({
                        type: "negative",
                        message: err.response.data.message,
                    });
                }
            } finally {
                this.dialog = false;
                this.disabled = false;
            }
        },
    },
};
</script>
