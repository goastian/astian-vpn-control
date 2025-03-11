<template>
    <q-dialog v-model="dialog">
        <q-card class="w-100 dialog">
            <q-card-section
                class="row justify-center"
                v-if="!peer.id"
            >
                <div
                    class="text-h6"
                >Add Peer</div>
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

                    <div v-if="peer.id" class="column q-gutter-y-md">
                        <div class="row justify-center q-gutter-y-md">
                            <span class="text-h6">
                                <strong>The Configuration is Ready</strong>
                            </span>
                            <span>Open Wireguard and choose scan barcode</span>
                            <q-img :src="qrCode" v-if="qrCode" class="canvas" />
                        </div>
                        <span class="or">Or Enter the configuration manually</span>
                        <q-btn
                            label="Download"
                            color="blue"
                            @click="generateConfig"
                            class=""
                        />
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

.dialog {
    border-radius: 1rem;
    padding: 1rem;
}

.or {
    text-align: center;
    position: relative;
}
.or:after {
    content: '';
    width: 15%;
    height: .06rem;
    position: absolute;
    top: 0;
    bottom: 0;
    left: 0;
    margin: auto;
    background-color: gray;
}

.or:before {
    content: '';
    width: 15%;
    height: .06rem;
    position: absolute;
    top: 0;
    bottom: 0;
    right: 0;
    margin: auto;
    background-color: gray;
}

@media (max-width: 400px) {
    .or::after {
        width: 6%;
    }

    .or::before {
        width: 6%
    }
}

</style>
