<template>
    <q-dialog v-model="dialog">
        <q-card class="dialog full-width">
            <q-card-section class="row justify-center">
                <div class="text-h6">
                    {{ peer.id ? "Device Configuration" : "Device Generator" }}
                </div>
            </q-card-section>

            <q-card-section>
                <div class="row" v-show="!peer.id">
                    <div class="col-12 mb-2">
                        <q-input
                            v-model="form.name"
                            label="Device Name"
                            filled
                        />
                        <v-error :error="errors.name"></v-error>
                    </div>
                    <div class="col-12 mb-2">
                        <q-select
                            v-model="form.server_id"
                            :options="interfaces"
                            option-value="id"
                            option-label="name"
                            emit-value
                            map-options
                            filled
                            label="Choose Server"
                        />
                        <v-error :error="errors.server_id"></v-error>
                    </div>
                </div>

                <div v-if="peer.id" class="column items-center q-gutter-y-md">
                    <span class="text-h6">
                        <strong>Configuration Ready</strong>
                    </span>
                    <span>Open WireGuard and scan the QR code</span>

                    <q-img :src="qrCode" v-if="qrCode" class="qr-code" />

                    <span class="or">Or enter the configuration manually</span>

                    <q-btn
                        label="Download Config"
                        color="blue"
                        @click="generateConfig"
                    />
                </div>
            </q-card-section>

            <q-card-actions align="right">
                <q-space />
                <q-btn flat label="Close" v-close-popup />
                <q-btn
                    label="Save"
                    color="primary"
                    unelevated
                    @click="addPeer"
                    v-if="!peer.id"
                />
            </q-card-actions>
        </q-card>
    </q-dialog>

    <q-btn
        icon="mdi-vpn"
        label="Generator"
        color="positive"
        outline
        @click="loadData"
    >
        <q-tooltip class="bg-indigo" :offset="[10, 10]">
            Generate VPN configuration for your device
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
                server_id: "",
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
                    this.$q.notify({
                        type: "positive",
                        message: "The Peer was Created successfully",
                    });
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
                type: "application/octet-stream",
            });
            const link = document.createElement("a");
            link.href = URL.createObjectURL(blob);
            link.download = `${this.peer.name
                .replace(/\s+/g, "")
                .toLowerCase()}.conf`;
            link.click();
            URL.revokeObjectURL(link.href);
        },
    },
};
</script>

<style scoped>
.qr-code {
    width: 300px;
    height: 300px;
    max-width: 100%;
}

.dialog {
    border-radius: 1rem;
    padding: 1rem;
}

.or {
    text-align: center;
    position: relative;
    font-weight: bold;
    margin-top: 0.5rem;
}

.or::after,
.or::before {
    content: "";
    width: 20%;
    height: 0.06rem;
    background-color: gray;
    position: absolute;
    top: 50%;
    transform: translateY(-50%);
}

.or::after {
    left: 0;
}

.or::before {
    right: 0;
}
</style>
