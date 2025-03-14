<template>
    <q-page padding>
        <div class="row q-col-gutter-md">
            <div class="col-12 col-md-3 flex">
                <h5 class="q-mb-none">General</h5>
            </div>

            <div class="col-12 col-md-9">
                <q-list bordered separator>
                    <q-item>
                        <q-item-section>
                            <q-item-label>App name</q-item-label>
                        </q-item-section>
                        <q-item-section>
                            <q-input
                                v-model="app.name"
                                type="text"
                                outlined
                                dense
                                class="full-width"
                            />
                        </q-item-section>
                    </q-item>
                </q-list>
            </div>
        </div>

        <div class="q-mt-md text-right">
            <q-btn type="submit" label="Save" color="primary" @click="save" />
        </div>
    </q-page>
</template>

<script>
export default {
    data() {
        return {
            app: {
                name: "",
                group: "app",
            },

            errors: {},
            keys: [],
        };
    },

    mounted() {
        this.getSetting();
    },

    methods: {
        /**
         * Add or update the key
         */
        async save() {
            const formData = new FormData();
            formData.append("app[name]", this.app.name);
            formData.append("group", this.app.group);

            try {
                const res = await this.$api.post("/api/settings", formData, {
                    headers: {
                        "Content-Type": "multipart-data",
                    },
                });

                if (res.status == 201) {
                    this.getSetting();
                    this.$q.notify({
                        type: "positive",
                        message: "The settings has been updated successfully",
                    });
                }
            } catch (error) {
                if (err.response && err.response.status == 422) {
                    this.errors = res.response.data.errors;
                }
            }
        },

        /**
         * Searching keys
         * @param key name of key
         */
        async getSetting() {
            try {
                const res = await this.$api.get("/api/settings", {
                    params: {
                        per_page: 150,
                        group: this.app.group,
                    },
                });

                if (res.status == 200) {
                    this.keys = res.data.data;

                    this.loadKeys();
                }
            } catch (error) {}
        },

        loadKeys() {
            this.app.name = this.searchKey("app.name");
        },

        /**
         *
         * @param name Search the key
         */
        searchKey(name) {
            const key = this.keys.find((item) => item.key == name);
            return key.value;
        },
    },
};
</script>

<style scoped>
.full-width {
    width: 100%;
}
</style>
