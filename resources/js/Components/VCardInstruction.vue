<template>
    <div
        class="p-4 mb-4 min-h-[330px] border-gray-50 bg-gray-50 flex items-center border rounded-lg shadow-md"
    >
        <div>
            <span class="bg-blue-500 text-white py-1 px-3 rounded-full text-sm">
                {{ number }}
            </span>

            <div class="flex items-center gap-1 mt-4">
                <div class="rounded-md">
                    <img :src="image" class="max-w-[100px] object-contain" />
                </div>
                <div class="rounded-md flex-1">
                    <h6 class="font-bold">{{ title }}</h6>
                    <p class="text-sm text-gray-600">{{ description }}</p>
                </div>
            </div>

            <a
                v-if="btnTitle"
                :href="btnUrl"
                target="_blank"
                class="block w-full mt-4 bg-blue-500 text-white text-center py-2 rounded-md hover:bg-blue-600 transition"
            >
                {{ btnTitle }}
            </a>
        </div>
    </div>
</template>

<script>
export default {
    props: {
        number: Number,
        image: String,
        title: String,
        description: String,
        btnTitle: String,
        btnUrl: String,
    },
};
</script>

<style scoped>
.q-card {
    border: 1px solid rgba(0, 0, 0, 0.1);
}
</style>
