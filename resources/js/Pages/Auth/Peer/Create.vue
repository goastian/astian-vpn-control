<template>
    <q-dialog v-model="dialog" maximized>
        <q-card>
            <q-card-section>
                <div class="text-h6">Add Peer</div>
            </q-card-section>

            <q-card-section>
                <q-form @submit.prevent="addPeer">
                    <q-input
                        v-model="form.name"
                        label="Device name"
                        v-if="!peer.id"
                        filled
                        :error="!!errors.name"
                    >
                        <template v-slot:error>
                            <v-error :error="errors.name"></v-error>
                        </template>
                    </q-input>

                    <q-select
                        v-model="form.wg_id"
                        :options="interfaces"
                        option-value="id"
                        option-label="name"
                        emit-value
                        map-options
                        filled
                        label="Choose the server"
                        v-if="!peer.id"
                    >
                        <template v-slot:error>
                            <v-error :error="errors.wg_id"></v-error>
                        </template>
                    </q-select>

                    <q-banner
                        v-if="peer.id"
                        class="bg-blue-3 text-white q-mb-md"
                    >
                        The configuration is ready for download and will only be
                        available once. Please download and save it to your
                        device.
                    </q-banner>

                    <div v-if="peer.id" class="row q-mt-md">
                        <q-btn
                            label="Download"
                            color="blue"
                            @click="generateConfig"
                            class="q-mr-md"
                        />
                        <q-img :src="qrCode" v-if="qrCode" class="canvas" />
                    </div>
                </q-form>
            </q-card-section>

            <q-card-actions align="right">
                <q-btn flat label="Close" v-close-popup />
                <q-btn
                    label="Save"
                    color="primary"
                    @click="addPeer"
                    v-if="!peer.id"
                />
            </q-card-actions>
        </q-card>
    </q-dialog>

    <q-btn icon="mdi-vpn" color="blue" round @click="loadData">
        <q-tooltip class="bg-indigo" :offset="[10, 10]">
            Add a new peer
        </q-tooltip>
    </q-btn>
</template>

<script>
import QRious from "qrious";

export default {
    emits: ["created"],

    data() {
        return {
            dialog: false,
            interfaces: [],
            peer: {},
            form: {
                name: "",
                wg_id: "",
            },
            errors: {},
            qrCode: "",
        };
    },
    methods: {
        loadData() {
            this.dialog = true;
            this.getWgs();
            this.qrCode = "";
            this.peer = {};
        },

        async addPeer() {
            try {
                const res = await this.$api.post("/api/peers", this.form, {
                    headers: {
                        "Content-Type": "multipart/form-data",
                    },
                });
                if (res.status === 201) {
                    this.peer = res.data.data;
                    this.generateQRCode(res.data.data.config);
                    this.errors = {};
                    this.form = {};
                    this.$emit("created", res.data.data);
                }
            } catch (err) {
                if (err.response?.status === 422)
                    this.errors = err.response.data.errors;
                else
                    this.$q.notify({
                        type: "negative",
                        message: err.response?.data?.message || "Error",
                    });
            }
        },

        async getWgs() {
            try {
                const res = await this.$api.get("/api/wgs", {
                    params: { active: true },
                });
                if (res.status === 200) this.interfaces = res.data.data;
            } catch (err) {}
        },

        generateQRCode(content) {
            const qr = new QRious({
                value: content.trim(),
                size: 200,
                level: "H",
            });
            this.qrCode = qr.toDataURL();
        },

        generateConfig() {
            const blob = new Blob([this.peer.config.trim()], {
                type: "text/plain",
            });
            const link = document.createElement("a");
            link.href = URL.createObjectURL(blob);
            link.download = `${this.peer.name}.conf`;
            link.click();
            URL.revokeObjectURL(link.href);
        },
    },
};
</script>

<style scoped>
.canvas {
    width: 200px;
    height: 200px;
}
</style>
