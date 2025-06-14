<template>
    <q-dialog v-model="dialog" persistent>
        <q-card style="width: 500px; max-width: 90vw">
            <q-card-section class="row items-center">
                <q-icon name="mdi-vpn" size="28px" class="q-mr-md text-primary" />
                <div class="text-h5">Add WireGuard Interface</div>
            </q-card-section>

            <q-separator />

            <q-card-section>
                <div class="row">
                    <div class="col-12 mb-3">
                        <q-input v-model="form.name" label="Name" outlined dense />
                        <v-error :error="errors.name"></v-error>
                    </div>

                    <div class="col-12 mb-3">
                        <q-input v-model="form.listen_port" label="Listen Port" type="number" outlined dense />
                        <v-error :error="errors.listen_port"></v-error>
                    </div>

                    <div class="col-12 mb-3">
                        <q-input v-model="form.dns" label="DNS Server" placeholder="1.1.1.1, 2.3.3.3" outlined dense />
                        <v-error :error="errors.dns"></v-error>
                    </div>

                    <div class="col-12 mb-3">
                        <q-checkbox v-model="form.enable_dns" label="Enable DNS" color="orange" />
                        <v-error :error="errors.enable_dns"></v-error>
                    </div>

                    <div class="col-12 mb-3">
                        <q-select v-model="form.server_id" :options="servers" label="Servers" option-label="country"
                            option-value="id" emit-value map-options outlined dense />
                        <v-error :error="errors.server_id"></v-error>
                    </div>

                    <div class="col-12 mb-3">
                        <q-select v-model="form.interface" :options="interfaces" label="Network Interfaces"
                            option-label="interface" option-value="interface" emit-value map-options outlined dense />
                        <v-error :error="errors.interface"></v-error>
                    </div>
                </div>
            </q-card-section>

            <q-card-actions align="right">
                <q-btn flat label="Close" v-close-popup />
                <q-btn color="primary" label="Save" @click="createWireguardInterface" />
            </q-card-actions>
        </q-card>
    </q-dialog>

    <q-btn color="positive" icon="mdi-vpn" class="q-mt-md" @click="openDialog" outline>
        <q-tooltip class="bg-purple text-body2" :offset="[10, 10]">
            Add new network
        </q-tooltip>
    </q-btn>
</template>
<script>
export default {
    emits: ["created"],
    data() {
        return {
            dialog: false,
            servers: [],
            server_selected: null,
            form: {},
            errors: {},
            interfaces: [],
        };
    },

    watch: {
        "form.server_id"(value) {
            if (value) {
                this.getNetworkInterfaces(value);
            }
        },
    },

    methods: {
        openDialog() {
            this.cleanForm();
            this.getServers();
            this.dialog = true;
        },

        cleanForm() {
            this.form.name = "";
            this.form.listen_port = "51820";
            this.form.dns = "";
            this.form.server_id = "";
            this.form.interface = "";
            this.form.enable_dns = false;
        },

        async createWireguardInterface() {
            try {
                const res = await this.$api.post(
                    this.$page.props.links["wireguard"],
                    this.form
                );
                if (res.status === 201) {
                    this.dialog = false;
                    this.cleanForm();
                    this.$q.notify({
                        type: "positive",
                        message: "Wireguard network interface created successfully",
                    });
                    this.$emit("created", res.data.data);
                }
            } catch (err) {
                if (err.response && err.response.status == 422) {
                    this.errors = err.response.data.errors;
                }

                if (err.response?.status == 403 && err.response?.data?.message) {
                    this.$q.notify({
                        type: "negative",
                        message: err.response.data.message
                    });
                }
            }
        },
        async getNetworkInterfaces(id) {
            try {
                const res = await this.$api.get(
                    `/api/v1/admin/servers/interfaces/${id}`
                );
                if (res.status === 200) {
                    this.interfaces = res.data;
                }
            } catch (error) { }
        },
        async getServers() {
            try {
                const res = await this.$api.get(
                    this.$page.props.links["servers"]
                );
                if (res.status === 200) {
                    this.servers = res.data.data;
                }
            } catch (err) { }
        },
    },
};
</script>
