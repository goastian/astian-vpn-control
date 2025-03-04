<template>
    <span
        v-show="!error.example"
        v-for="(item, index) in error"
        :key="index"
        class="mx-1"
    >
        {{ item }}
    </span>
</template>
<script>
export default {
    props: {
        error: {
            type: [Object, String],
            required: true,
            default: { example: "example Error" },
        },
    },
};
</script>
<style lang="scss" scoped>
 
span {
    font-size: 0.9em;
    color: rgb(223, 40, 40);
    margin-bottom: 0.5em;
}
</style>