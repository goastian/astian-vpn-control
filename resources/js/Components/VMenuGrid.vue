<template>
    <v-icon
        color="grey-darken-1"
        :icon="$utils.toKebabCase('mdiViewGrid')"
    >
    </v-icon>
    <v-menu activator="parent" location="end">
            <v-list class="MenuGrid" rounded>
                <v-list-item
                    class="MGitem"
                    v-for="(item, index) in menus"
                    :key="index"
                    :value="item.name"
                >
                    <template v-slot:default>
                        <v-card :subtitle="item.name" >
                            <template v-slot:default>
                                <v-icon
                                    :icon="$utils.toKebabCase(item.icon)"
                                    size="50"
                                    color="blue-accent-2"
                                ></v-icon>
                            </template>
                        </v-card>
                    </template>
                </v-list-item>
            </v-list>
        </v-menu>
    <v-menu
        activator="parent"
    >
    </v-menu>
</template>
<script>
export default {

    data() {
        return {
            drawer: false,
            apps: {},
            visible: false,
            menus: [
                {
                    name: "Cloud",
                    icon: "mdiCloudCheckVariantOutline",
                    route: "https://cloud.astian.org",
                },
                {
                    name: "Notes",
                    icon: "mdiNotebookHeartOutline",
                    route: "https://notes.astian.org",
                },
                {
                    name: "Calendar",
                    icon: "mdiCalendarMultiselectOutline",
                    route: "https://calendar.astian.org",
                },
                {
                    name: "Contacts",
                    icon: "mdiCardAccountMailOutline",
                    route: "https://notes.astian.org",
                },
            ],
        };
    },

    mounted() {
        this.screenIsChanging();

        window.addEventListener("resize", this.screenIsChanging);
    },

    methods: {
        screenIsChanging() {
            this.visible = window.innerWidth < 800;
        },

        toggleDrawer() {
            this.drawer = !this.drawer;
        },
    },
};
</script>

<style scoped>
.v-icon {
    cursor: pointer;
}
.MenuGrid {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    width: 300px;
    min-width: 200px;
    max-width: 300px;
    height: auto;
    padding: 1em;
    overflow-y: scroll;
}

.MGitem {
    display: flex;
    justify-content: center;
    text-align: center;
    height: 100px;
    margin: 5px;
}
</style>
