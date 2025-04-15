<template>
    <q-dialog v-model="dialog" persistent>
        <template v-slot:activator="{ toggle }">
            <q-btn flat round color="red" icon="mdi-power" @click="toggle" />
        </template>

        <q-card class="w-96">
            <q-card-section>
                {{ message }}
            </q-card-section>

            <q-card-actions align="right">
                <q-btn
                    color="positive"
                    outline
                    label="Close"
                    @click="dialog = false"
                />
            </q-card-actions>
        </q-card>
    </q-dialog>

    <q-btn
        icon="info"
        color="info"
        label="Status"
        outline
        @click="statusServer(item)"
    ></q-btn>
</template>

<script>
export default {
    props: ["item"],

    data() {
        return {
            dialog: false,
            message: "",
        };
    },

    methods: {
        async statusServer(item) {
            try {
                const res = await this.$api.get(item.links.client_status);
                if (res.status === 200) {
                    this.dialog = true;
                    this.message = res.data.message;
                }
            } catch (err) {
                if ([403, 404, 400, 500].includes(err.response?.status)) {
                    this.$q.notify({
                        type: "positive",
                        message: err.response.data.message,
                    });
                }
            }
        },
    },
};
</script>
