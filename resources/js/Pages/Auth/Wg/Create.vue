<template>
    <q-dialog v-model="dialog" persistent>
        <q-card>
            <q-card-section class="row items-center q-pb-none">
                <q-icon name="mdi-vpn" size="24px" class="q-mr-sm" />
                <div class="text-h6">Add new WireGuard Interface</div>
            </q-card-section>

            <q-card-section>
                <q-form @submit.prevent="createWG">
                    <q-input
                        v-model="form.name"
                        label="Name"
                        :error="!!errors.name"
                    >
                        <template v-slot:error>
                            <v-error :error="errors.name"></v-error>
                        </template>
                    </q-input>
                    <q-input
                        v-model="form.listen_port"
                        label="Listen Port"
                        type="number"
                        :error="!!errors.listen_port"
                        :error-message="errors.listen_port"
                    >
                        <template v-slot:error>
                            <v-error :error="errors.name"></v-error>
                        </template>
                    </q-input>
                    <q-input
                        v-model="form.dns"
                        label="DNS Server"
                        :error="!!errors.dns"
                        :error-message="errors.dns"
                    >
                        <template v-slot:error>
                            <v-error :error="errors.dns"></v-error>
                        </template>
                    </q-input>

                    <q-select
                        v-model="form.server_id"
                        :options="servers"
                        label="Servers"
                        option-label="country"
                        option-value="id"
                        emit-value
                        map-options
                        @update:model-value="getNetworkInterfaces"
                    >
                        <template v-slot:error>
                            <v-error :error="errors.server_id"></v-error>
                        </template>
                    </q-select>
                    <q-select
                        v-model="form.interface"
                        :options="interfaces"
                        label="Network Interfaces"
                        option-label="interface"
                        option-value="interface"
                        emit-value
                        map-options
                    >
                        <template v-slot:error>
                            <v-error :error="errors.interface"></v-error>
                        </template>
                    </q-select>
                </q-form>
            </q-card-section>

            <q-card-actions align="right">
                <q-btn flat label="Close" v-close-popup />
                <q-btn color="primary" label="Save" @click="createWG" />
            </q-card-actions>
        </q-card>
    </q-dialog>

    <q-btn color="blue" icon="mdi-vpn" @click="openDialog">
        <q-tooltip class="bg-indigo" :offset="[10, 10]">
            Add a new interface
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
            form: {
                name: "",
                listen_port: "51820",
                dns: "",
                server_id: "",
                interface: "",
            },
            errors: {},
            interfaces: [],
        };
    },
    methods: {
        openDialog() {
            this.dialog = true;
            this.getServers();
        },
        async createWG() {
            try {
                const res = await this.$api.post("/api/wgs", this.form, {
                    headers: {
                        "content-type": "multipart/form-data",
                    },
                });
                if (res.status === 201) {
                    this.dialog = false;
                    this.errors = {};
                    this.form = {};
                    this.$emit("created", res.data.data);
                }
            } catch (err) {
                if (err.response?.status === 422) {
                    this.errors = err.response.data.errors;
                } else {
                    this.$q.notify({
                        type: "negative",
                        message: err.response?.data?.message || "Error",
                    });
                }
            }
        },
        async getNetworkInterfaces(id) {
            try {
                const res = await this.$api.get(`/api/interfaces/${id}`);
                if (res.status === 200) {
                    this.interfaces = res.data.data;
                }
            } catch (error) {}
        },
        async getServers() {
            try {
                const res = await this.$api.get("/api/servers");
                if (res.status === 200) {
                    this.servers = res.data.data;
                }
            } catch (err) {}
        },
    },
};
</script>
