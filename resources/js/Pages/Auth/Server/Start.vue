<template>
    <q-dialog v-model="dialog" persistent>
        <template v-slot:activator="{ toggle }">
            <q-btn flat round color="start" icon="mdi-power" @click="toggle" />
        </template>

        <q-card class="w-96">
            <q-card-section class="row items-center q-pb-none">
                <q-icon name="mdi-delete-empty" size="sm" />
                <span class="q-ml-sm text-h6">Start shadowsocks server</span>
            </q-card-section>

            <q-card-section>
                Are you sure you want to start the server?
            </q-card-section>

            <q-card-actions align="right">
                <q-btn flat label="Disagree" @click="dialog = false" />
                <q-btn color="red" label="Agree" @click="startServer(item)" />
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
        };
    },

    methods: {
        async startServer(item) {
            try {
                const res = await this.$api.post(item.links.start);
                if (res.status === 200) {
                    this.$emit("deleted", res.data);
                    this.dialog = false;
                    this.$q.notify({
                        type: "positive",
                        message: res.data.message,
                    });
                }
            } catch (err) {
                if ([403, 404, 500].includes(err.response?.status)) {
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
