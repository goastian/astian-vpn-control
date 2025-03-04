<template>
    <div class="max-h-screen">
        <v-data-table :headers="headers" :items="settings">
            <template #top>
                <div class="row d-flex justify-between py-4 px-4">
                    <h1 class="text-subtitle-1 underline">List of keys</h1>
                    <v-create @created="getSetting" :item="item"></v-create>
                </div>
            </template>
            <template #item.actions="{ item }">
                <v-create  @created="getSetting" :item="item"> </v-create>
            </template>
        </v-data-table>
    </div>
</template>
<script>
import VCreate from "./Create.vue";

export default {
    components: {
        VCreate,
    },

    data() {
        return {
            settings: [],
            item: {},
            headers: [
                {
                    title: "Key",
                    align: "center",
                    sortable: false,
                    key: "key",
                },
                {
                    title: "Value",
                    align: "start",
                    sortable: false,
                    key: "value",
                },
                {
                    title: "Actions",
                    align: "start",
                    sortable: false,
                    key: "actions",
                },
            ],
        };
    },

    mounted() {
        this.getSetting();
    },

    methods: {
        setItem(item) {
            this.item = item;
        },

        async getSetting() {
            try {
                const res = await this.$api.get("/api/settings");
                if (res.status == 200) {
                    this.settings = res.data;
                }
            } catch (err) {}
        },
    },
};
</script>
<style></style>
 