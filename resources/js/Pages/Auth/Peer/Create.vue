<template>
    <v-dialog v-model="dialog" max-width="900" with="100">
        <template v-slot:activator="{ props: activatorProps }">
            <v-btn
                class="text-none font-weight-regular mx-4"
                icon
                variant="tonal"
                v-bind="activatorProps"
                @click="showForm"
                color="blue-lighten-1"
            >
                <v-icon :icon="$utils.toKebabCase('mdiKeyPlus')"> </v-icon>
            </v-btn>
        </template>

        <v-card :prepend-icon="$utils.toKebabCase('mdiLock')" title="Add Peer">
            <v-card-text>
                <v-row dense>
                    <v-col cols="12" md="6" v-show="!peer.id">
                        <v-text-field label="Device name" v-model="form.name">
                            <template #details>
                                <v-error :error="errors.name"></v-error>
                            </template>
                        </v-text-field>
                    </v-col>

                    <v-col cols="12" v-show="!peer.id">
                        <v-select
                            v-model="form.wg_id"
                            :items="interfaces"
                            item-value="id"
                            item-text="name"
                        >
                            <template #selection="{ item }">
                                <v-icon
                                    :color="
                                        !item.raw.active
                                            ? 'red-accent-4'
                                            : 'green-accent-3'
                                    "
                                    :icon="
                                        $utils.toKebabCase('mdiCircleMedium')
                                    "
                                ></v-icon>
                                <span v-if="!item.raw.id">
                                    Choose the server
                                </span>
                                <span
                                    v-else="item.raw.id"
                                    class="text-body custom-selection"
                                >
                                    {{ item.raw.name }} -
                                    {{ item.raw.server_country }}
                                </span>
                            </template>

                            <template v-slot:item="{ props, item }">
                                <v-list-item
                                    v-bind="props"
                                    :title="item.raw.name"
                                    :subtitle="`${item.raw.server_country} - ${item.raw.server_country}`"
                                >
                                    <template v-slot:prepend>
                                        <v-icon
                                            :color="
                                                !item.raw.active
                                                    ? 'red-accent-4'
                                                    : 'green-accent-3'
                                            "
                                            :icon="
                                                $utils.toKebabCase(
                                                    'mdiCircleMedium'
                                                )
                                            "
                                        ></v-icon>
                                    </template> </v-list-item
                            ></template>
                        </v-select>
                        <v-error :error="errors.wg_id"></v-error>
                    </v-col>
                    <v-col cols="12" class="text-center" v-show="peer.id">
                        <v-row>
                            <v-col cols="12">
                                <v-chip
                                    color="light-blue-darken-1"
                                    class="mb-4"
                                >
                                    The configuration is ready for download and
                                    will only be available once. Please download
                                    and save it to your device
                                </v-chip>
                            </v-col>
                            <v-col
                                cols="12"
                                md="6"
                                class="d-flex justify-center align-center"
                            >
                                <v-btn
                                    variant="tonal"
                                    color="light-blue-accent-3"
                                    @click="generateConfig"
                                >
                                    Download
                                </v-btn>
                            </v-col>
                            <v-col cols="12" md="6">
                                <img id="qr" class="canvas" />
                            </v-col>
                        </v-row>
                    </v-col>
                </v-row>
            </v-card-text>

            <v-divider></v-divider>
            <v-card-actions>
                <v-spacer></v-spacer>

                <v-btn text="Close" variant="plain" @click="reset"></v-btn>

                <v-btn
                    color="primary"
                    text="Save"
                    variant="tonal"
                    @click="addPeer"
                    v-show="!peer.id"
                ></v-btn>
            </v-card-actions>
        </v-card>
    </v-dialog>
</template>
<script>
import QRious from "qrious";

export default {
    emit: ["created"],

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
        };
    },

    watch: {},

    methods: {
        /**
         * Reset keys to create a new peer into the form
         */
        showForm() {
            this.peer = {};
            this.getWgs();
        },

        /**
         * Create a new server
         */
        async addPeer() {
            try {
                const res = await this.$api.post("/api/peers", this.form, {
                    headers: {
                        "Content-Type": "multipart/form-data",
                    },
                });

                if (res.status == 201) {
                    this.peer = res.data.data;

                    /**
                     * Generate QR Code
                     */
                    const content = res.data.data.config
                        .replace(/\n/g, "\n")
                        .trim();

                    new QRious({
                        element: document.querySelector("#qr"),
                        value: content,
                        size: 200,
                        level: "H",
                    });

                    this.errors = {};
                    this.form = {};
                    this.$emit("created", res.data.data);
                }
            } catch (err) {
                if (err.response && err.response.status == 422) {
                    this.errors = err.response.data.errors;
                }
                if (err.response.status == 404) {
                    this.$notification.error(err.response.data.message);
                }
                if (err.response.status == 403) {
                    this.$notification.error(err.response.data.message);
                }
                if (err.response.status == 500) {
                    this.$notification.error(err.response.data.message);
                }
            }
        },

        reset() {
            this.form = {};
            this.errors = {};
            this.peer = {};
            this.dialog = !this.dialog;
        },

        /*
         * Retrieve the servers
         */
        async getWgs() {
            try {
                const res = await this.$api.get("/api/wgs", {
                    params: {
                        active: true,
                    },
                });

                if (res.status == 200) {
                    this.interfaces = res.data.data;
                }
            } catch (err) {}
        },

        /**
         * Download peer
         *
         * @param Object peer
         */
        generateConfig() {
            const content = this.peer.config.replace(/\n/g, "\n").trim();
            const blob = new Blob([content], { type: "text/plain" });

            const url = URL.createObjectURL(blob);
            const link = document.createElement("a");
            link.href = url;
            link.download = `${this.peer.name}.conf`;
            link.click();

            URL.revokeObjectURL(url);
        },
    },
};
</script>

<style lang="css" scoped>
.canvas {
    width: 200px;
    height: 200px;
}
</style>
