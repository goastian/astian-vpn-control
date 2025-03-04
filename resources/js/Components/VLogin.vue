<template>
    <v-btn variant="tonal" class="mx-2" @click="login" icon>
        <v-icon>
            {{ $utils.toKebabCase("mdiLogin") }}
        </v-icon>
    </v-btn>
</template>
<script>
export default {
    methods: {
        login() {
            window.open("/redirect");
        },
    },
};
</script>
<style lang=""></style>
 