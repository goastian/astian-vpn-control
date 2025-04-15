<template>
    <q-dialog v-model="dialog" persistent>
        <template v-slot:activator="{ toggle }">
            <q-btn flat round color="start" icon="mdi-power" @click="toggle" />
        </template>

        <q-card class="w-96">
            <q-card-section class="row items-center q-pb-none">
                <span class="q-ml-sm text-h6">Server client</span>
            </q-card-section>

            <q-card-section> Server client will be started </q-card-section>

            <q-card-actions align="right">
                <q-btn
                    color="negative"
                    outline
                    label="Abort"
                    @click="dialog = false"
                />
                <q-btn
                    color="positive"
                    outline
                    :disable="disabled"
                    label="Accept"
                    @click="startServer(item)"
                />
            </q-card-actions>
        </q-card>
    </q-dialog>

    <q-btn
        icon="play_arrow"
        color="positive"
        label="Start"
        outline
        @click="dialog = true"
    ></q-btn>
</template>

<script>
export default {
    props: ["item"],

    data() {
        return {
            dialog: false,
            disabled: false,
        };
    },

    methods: {
        async startServer(item) {
            this.disabled = true;
            try {
                const res = await this.$api.get(item.links.client_start);
                if (res.status === 200) {
                    this.$emit("deleted", res.data);
                    this.dialog = false;
                    this.$q.notify({
                        type: "positive",
                        message: res.data.message,
                    });
                }
            } catch (err) {
                if ([403, 404, 400, 500].includes(err.response?.status)) {
                    this.$q.notify({
                        type: "negative",
                        message: err.response.data.message,
                    });
                }
                this.dialog = false;
            } finally {
                this.disabled = false;
            }
        },
    },
};
</script>
