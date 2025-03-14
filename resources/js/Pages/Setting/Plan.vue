<template>
    <q-page padding>
        <div class="row q-col-gutter-md">
            <div class="col-12 col-md-3 flex">
                <h5 class="q-mb-none">Plans</h5>
            </div>

            <div class="col-12 col-md-9">
                <q-list bordered separator>
                    <q-item>
                        <q-item-section>
                            <q-item-label>Free</q-item-label>
                        </q-item-section>
                        <q-item-section>
                            <q-input
                                v-model="vpn.free"
                                type="number"
                                outlined
                                dense
                                class="full-width"
                            />
                        </q-item-section>
                    </q-item>

                    <q-item>
                        <q-item-section>
                            <q-item-label>Basic</q-item-label>
                        </q-item-section>
                        <q-item-section>
                            <q-input
                                v-model="vpn.basic"
                                type="number"
                                outlined
                                dense
                                class="full-width"
                            />
                        </q-item-section>
                    </q-item>

                    <q-item>
                        <q-item-section>
                            <q-item-label>Intermediate</q-item-label>
                        </q-item-section>
                        <q-item-section>
                            <q-input
                                v-model="vpn.intermediate"
                                type="number"
                                outlined
                                dense
                                class="full-width"
                            />
                        </q-item-section>
                    </q-item>

                    <q-item>
                        <q-item-section>
                            <q-item-label>Advanced</q-item-label>
                        </q-item-section>
                        <q-item-section>
                            <q-input
                                v-model="vpn.advanced"
                                type="number"
                                outlined
                                dense
                                class="full-width"
                                :errors="!errors.advanced"
                            />
                            <template v-slot:errors>
                                <v-error :error="errors.advanced"></v-error>
                            </template>
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
            vpn: {
                free: 0,
                basic: 0,
                intermediate: 0,
                advanced: 0,
                group: "vpn",
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
            formData.append("vpn[free]", this.vpn.free);
            formData.append("vpn[basic]", this.vpn.basic);
            formData.append("vpn[intermediate]", this.vpn.intermediate);
            formData.append("vpn[advanced]", this.vpn.advanced);
            formData.append("group", this.vpn.group);

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
                        group: this.vpn.group,
                    },
                });

                if (res.status == 200) {
                    this.keys = res.data.data;
                    this.loadKeys();
                }
            } catch (error) {}
        },

        loadKeys() {
            this.vpn.free = this.searchKey("vpn.free");
            this.vpn.basic = this.searchKey("vpn.basic");
            this.vpn.intermediate = this.searchKey("vpn.intermediate");
            this.vpn.advanced = this.searchKey("vpn.advanced");
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
