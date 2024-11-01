<template>
    <v-btn :prepend-icon="$utils.toKebabCase('mdiViewGrid')">
        <template v-slot:prepend>
            <v-icon
                color="light-blue-darken-1"
                :icon="$utils.toKebabCase('mdiViewGrid')"
                size="25" 
            ></v-icon>
        </template>
        <v-menu activator="parent">
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
    </v-btn>
</template>
<script>
export default {
    props: {
        menus: {
            type: [Object, Array],
            required: true,
        },
    },

    data() {
        return {
            drawer: false,
            apps: {},
            visible: false,
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

<style lang="scss" scoped>
.MenuGrid {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    width: 400px;
    min-width: 200px;
    max-width: 400px;
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
