<template>
    <q-dialog v-model="dialog" persistent class="q-pa-md">
        <q-card class="server-card">
            <q-bar>
                <div class="col row justify-between items-center">
                    <div class="row items-center">
                        <q-icon name="mdi-server" />
                        <div class="q-ml-sm">Add Server</div>
                    </div>
                    <div>
                        <q-btn
                            dense
                            flat
                            icon="close"
                            @click="dialog = false"
                        />
                    </div>
                </div>
            </q-bar>

            <q-card-section>
                <q-form @submit.prevent="createServer">
                    <div class="row q-col-gutter-md">
                        <div class="mb-4 col-12 col-md-6">
                            <q-input
                                v-model="form.country"
                                label="Country"
                                filled
                                :error="!!errors.country"
                            />
                            <v-error :error="errors.country"></v-error>
                        </div>

                        <div class="mb-4 col-12 col-md-6">
                            <q-input
                                v-model="form.url"
                                label="URL"
                                filled
                                :error="!!errors.url"
                            />
                            <v-error :error="errors.url"></v-error>
                        </div>
                        <div class="mb-4 col-12 col-md-6">
                            <q-input
                                v-model="form.port"
                                label="Port"
                                filled
                                type="number"
                                :error="!!errors.port"
                            />
                            <v-error :error="errors.port"></v-error>
                        </div>

                        <q-separator class="full-width q-mt-md" />
                        <div class="full-width text-bold q-mt-md">
                            Shadowsocks Settings
                        </div>

                        <div class="mb-4 col-12 col-md-6">
                            <q-input
                                v-model="form.ss_port"
                                label="Shadowsocks Port"
                                filled
                                type="number"
                                class="mb-4 col-12 col-md-6"
                                :error="!!errors.ss_port"
                            />
                            <v-error :error="errors.ss_port"></v-error>
                        </div>

                        <div class="mb-4 col-12 col-md-6">
                            <q-select
                                v-model="form.ss_method"
                                :options="ciphers"
                                label="Shadowsocks Ciphers"
                                :error="!!errors.ss_method"
                            />
                            <v-error :error="errors.ss_method"></v-error>
                        </div>

                        <div class="mb-4 col-12 col-md-6">
                            <q-checkbox
                                v-model="form.ss_over_https"
                                label="Enable Shadowsocks over HTTPS"
                                color="orange"
                                class="mb-4 col-12 col-md-6"
                            />
                            <v-error :error="errors.ss_over_https"></v-error>
                        </div>

                        <!-- DNS Fields -->
                        <div class="col-12">
                            <div
                                class="row items-center q-mb-md"
                                v-for="(dns, index) in form.dns"
                                :key="index"
                            >
                                <q-input
                                    v-model="form.dns[index]"
                                    label="DNS"
                                    filled
                                    class="col-grow q-mr-sm"
                                />
                                <q-btn
                                    dense
                                    flat
                                    icon="mdi-delete"
                                    color="negative"
                                    @click="removeDns(index)"
                                />
                            </div>
                            <q-btn
                                dense
                                flat
                                icon="mdi-plus"
                                color="positive"
                                label="Add DNS"
                                @click="addDns"
                            />
                        </div>
                    </div>
                </q-form>
            </q-card-section>

            <q-card-actions align="right">
                <q-btn flat label="Close" @click="dialog = false" />
                <q-btn label="Save" color="primary" @click="createServer" />
            </q-card-actions>
        </q-card>
    </q-dialog>

    <q-btn icon="mdi-server-plus-outline" color="blue" round @click="open">
        <q-tooltip class="bg-indigo" :offset="[10, 10]"
            >Add a new server</q-tooltip
        >
    </q-btn>
</template>

<script>
export default {
    emits: ["created"],

    data() {
        return {
            dialog: false,
            form: {
                country: "",
                url: "",
                port: 443,
                ss_port: 8388,
                ss_method: "chacha20-ietf-poly1305",
                ss_over_https: false,
                dns: [""],
            },
            ciphers: ["chacha20-ietf-poly1305", "aes-256-gcm", "aes-128-gcm"],
            errors: {},
        };
    },

    methods: {
        open() {
            this.form = {
                country: "",
                url: "",
                port: 443,
                ss_port: 8388,
                ss_method: "chacha20-ietf-poly1305",
                ss_over_https: false,
                dns: [""],
            };
            this.dialog = true;
        },

        addDns() {
            this.form.dns.push("");
        },

        removeDns(index) {
            this.form.dns.splice(index, 1);
        },

        async createServer() {
            try {
                const res = await this.$api.post("/api/servers", this.form, {
                    headers: {
                        "Content-Type": "multipart/form-data",
                    },
                });

                if (res.status === 201) {
                    this.dialog = false;
                    this.errors = {};
                    this.$emit("created", res.data.data);
                    this.$q.notify({
                        type: "positive",
                        message: "Server added!",
                    });
                }
            } catch (err) {
                if (err.response?.status === 422) {
                    this.errors = err.response.data.errors;
                } else {
                    this.$q.notify({
                        type: "negative",
                        message:
                            err.response?.data.message || "An error occurred",
                    });
                }
            }
        },
    },
};
</script>

<style lang="css" scoped>
.server-card {
    width: 100%;
    max-width: 800px;
}
</style>
