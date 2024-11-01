<template>
    <v-btn
        variant="tonal" 
        class="mx-2"
        @click="login"
        icon
    >
        <v-icon>
            {{ $utils.toKebabCase("mdiLogout") }}
        </v-icon>
    </v-btn>
</template>
<script>
export default {
    methods: {
        login() {
            console.log("working");
        },
    },
};
</script>
<style lang=""></style>
