<template>
    <div
        class="card"
    >
        <span class="number bg-blue-500 text-white py-1 px-3 rounded-full text-sm">
            {{ number }}
        </span>

        <div class="containerImage">
            <img :src="image" class="imagen" />
        </div>
        <div class="containerDescription">
            <h6 class="title"><strong>{{ title }}</strong></h6>
            <p class="description">{{ description }}</p>
        </div>

        <a
            v-if="btnTitle"
            :href="btnUrl"
            target="_blank"
            class="enlace block w-full mt-4 bg-blue-500 text-white text-center py-2 hover:bg-blue-600 transition"
        >
            {{ btnTitle }}
        </a>
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
.card {
    height: 100%;
    min-height: 220px;
    max-height: 300px;
    display: flex;
    align-items: center;
    gap: 2rem;
    position: relative;
    padding: 1.5rem;
    border-radius: 1rem;
    border: .08rem solid var(--border);
    overflow: hidden;
}

.number {
    position: absolute;
    left: 0;
    top: 0;
}

.containerImage {
    width: 20%;
}

.imagen {
    width: auto;
}

.containerDescription {
    width: 80%;
}

.title {
    font-size: 1rem;
}

.description {
    color: var(--text-color);
}

.enlace {
    position: absolute;
    bottom: 0;
    left: 0;
}

.q-card {
    border: 1px solid rgba(0, 0, 0, 0.1);
}
</style>
