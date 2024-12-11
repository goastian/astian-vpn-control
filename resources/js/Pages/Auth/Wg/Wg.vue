<template>
    <v-data-table :headers="headers" :items="interfaces">
        <template #top>
            <div class="row d-flex justify-between py-4 px-4">
                <h1 class="text-subtitle-1 underline">Wireguard Interfaces</h1>
                <v-create @created="getWgs"></v-create>
            </div>
        </template>
        <template #item.server="{ item }">
            {{ item.server_country }} - {{ item.server_url }}
        </template>
        <template #item.active="{ item }">
            <v-toggle @updated="getWgs" :wg="item"></v-toggle>
        </template>
        <template #item.reload="{item}">
            <v-reload :wg="item" @updated="getWgs"></v-reload>
        </template>
        <template #item.actions="{ item }">
            <v-menu transition="scale-transition">
                <template v-slot:activator="{ props }">
                    <v-btn color="primary" variant="plain" v-bind="props" icon>
                        <v-icon>
                            {{ $utils.toKebabCase("mdiTools") }}
                        </v-icon>
                    </v-btn>
                </template>

                <v-list>
                    <v-list-item>
                        <v-list-item-title>
                            <v-update @updated="getWgs" :wg="item"></v-update>
                        </v-list-item-title>
                        <v-list-item-title class="text-center">
                            <v-delete @deleted="getWgs" :wg="item"></v-delete>
                        </v-list-item-title>
                    </v-list-item>
                </v-list>
            </v-menu>
        </template>
    </v-data-table>
</template>

<script>
import VCreate from "./Create.vue";
import VUpdate from "./Update.vue";
import VDelete from "./Delete.vue";
import VToggle from "./Toggle.vue";
import VReload from "./Reload.vue";

export default {
    components: {
        VCreate,
        VUpdate,
        VDelete,
        VToggle,
        VReload
    },

    data() {
        return {
            headers: [
                {
                    title: "On And Off",
                    align: "center",
                    sortable: false,
                    key: "active",
                },
                {
                    title: "Name",
                    align: "start",
                    sortable: false,
                    key: "name",
                },
                {
                    title: "Port",
                    align: "start",
                    sortable: false,
                    key: "listen_port",
                },
                {
                    title: "Server",
                    align: "start",
                    sortable: false,
                    key: "server",
                },
                {
                    title: "Reload",
                    align: "start",
                    sortable: false,
                    key: "reload",
                },
                {
                    title: "NetLan",
                    align: "start",
                    sortable: false,
                    key: "interface",
                },
                {
                    title: "Actions",
                    align: "center",
                    sortable: false,
                    key: "actions",
                },
            ],
            interfaces: [],
        };
    },

    mounted() {
        this.getWgs();
    },

    methods: {
        async getWgs() {
            try {
                const res = await this.$api.get("/api/wgs");

                if (res.status == 200) {
                    this.interfaces = res.data.data;
                }
            } catch (err) {}
        },
    },
};
</script>
