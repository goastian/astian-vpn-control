<template>
    <q-dialog v-model="dialog" persistent>
        <q-card class="w-100">
            <q-bar>
                <div class="flex justify-around py-4">
                    <q-icon name="mdi-server" />
                    <div class="q-ml-sm">Add Server</div>
                    <q-space />
                    <q-btn dense flat icon="close" @click="dialog = false" />
                </div>
            </q-bar>

            <q-card-section>
                <q-form @submit.prevent="createServer">
                    <q-input
                        v-model="form.country"
                        label="Country"
                        filled
                        class="mb-4"
                        :error="!!errors.country"
                    >
                        <template v-slot:error>
                            <v-error :error="errors.country"></v-error>
                        </template>
                    </q-input>
                    <q-input
                        v-model="form.url"
                        label="URL"
                        filled
                        class="mb-4"
                        :error="!!errors.url"
                    >
                        <template v-slot:error>
                            <v-error :error="errors.url"></v-error>
                        </template>
                    </q-input>
                    <q-input
                        v-model="form.port"
                        label="Port"
                        filled
                        type="number"
                        class="mb-4"
                        :error="!!errors.port"
                    >
                        <template v-slot:error>
                            <v-error :error="errors.port"></v-error>
                        </template>
                    </q-input>
                    <q-input
                        v-model="form.ip"
                        label="IPv4"
                        filled
                        class="mb-4"
                        :error="!!errors.ip"
                    >
                        <template v-slot:error>
                            <v-error :error="errors.ip"></v-error>
                        </template>
                    </q-input>
                </q-form>
            </q-card-section>

            <q-card-actions align="right">
                <q-btn flat label="Close" @click="dialog = false" />
                <q-btn label="Save" color="primary" @click="createServer" />
            </q-card-actions>
        </q-card>
    </q-dialog>

    <q-btn
        icon="mdi-server-plus-outline"
        color="blue"
        round
        @click="dialog = true"
    >
        <q-tooltip class="bg-indigo" :offset="[10, 10]">
            Add a new server
        </q-tooltip>
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
                port: "",
                ip: "",
            },
            errors: {},
        };
    },

    methods: {
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
                    this.form = { country: "", url: "", port: "", ip: "" };
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
