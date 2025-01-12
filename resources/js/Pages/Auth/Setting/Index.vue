<template>
    <div class="max-h-screen">
        <v-data-table :headers="headers" :items="settings">
            <template #top>
                <v-filter
                    :params="['key', 'value']"
                    @change="searcher"
                ></v-filter>

                <div class="row d-flex justify-between py-4 px-4">
                    <h1 class="text-subtitle-1 underline">List of keys</h1>
                    <v-create @created="getSetting" :item="item"></v-create>
                </div>
            </template>
            <template #item.actions="{ item }">
                <v-create @created="getSetting" :item="item"> </v-create>
            </template>
            <template v-slot:bottom>
                <v-pagination
                    v-model="search.page"
                    :length="search.total_pages"
                    :total-visible="7"
                ></v-pagination>
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
            search: {
                page: 1,
                per_page: 50,
                total_pages: 0,
            },

            params: [],
        };
    },

    mounted() {
        this.getSetting();
    },

    methods: {
        setItem(item) {
            this.item = item;
        },

        searcher(event) {
            this.params = event;
            this.getSetting();
        },

        async getSetting() {
            try {
                const res = await this.$api.get("/api/settings", {
                    params: this.params,
                });
                if (res.status == 200) {
                    this.settings = res.data.data;
                    this.search = res.data.meta;
                }
            } catch (err) {}
        },
    },
};
</script>
<style></style>
