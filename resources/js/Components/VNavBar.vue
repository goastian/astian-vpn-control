<template>
    <div class="navbar" v-if="showNavBar">
        <div class="fade left" v-show="showLeftFade"></div>
        <button
            class="arrow left"
            @click="scrollNav(-100)"
            v-show="showLeftArrow"
        >
            <
        </button>
        <div class="nav-container" ref="navContainer" @scroll="updateArrows">
            <nav>
                <ul>
                    <li
                        v-for="(item, index) in routes"
                        :key="index"
                        v-show="item.show"
                    >
                        <router-link
                            :to="{ name: item.route }"
                            class="enlace"
                            :class="{ active: isActive(item.route) }"
                        >
                            <span :class="item.icon" />
                            <span>{{ item.name }}</span>
                        </router-link>
                    </li>
                </ul>
            </nav>
        </div>
        <button
            class="arrow right"
            @click="scrollNav(100)"
            v-show="showRightArrow"
        >
            >
        </button>
        <div class="fade right" v-show="showRightFade"></div>
    </div>
</template>

<script>
export default {
    inject: ["$user"],
    data() {
        return {
            showLeftArrow: false,
            showRightArrow: true,
            showLeftFade: false,
            showRightFade: true,
            widthScreen: window.innerWidth,
            routes: [
                {
                    name: "Home",
                    icon: "mdi mdi-home-automation",
                    route: "home",
                    show: this.hasGroup("member"),
                },
                {
                    name: "Servers",
                    icon: "mdi mdi-server-security",
                    route: "admin.servers",
                    show: this.hasGroup("administrator"),
                },
                {
                    name: "Wireguard",
                    icon: "mdi mdi-tools",
                    route: "admin.wireguard",
                    show: this.hasGroup("administrator"),
                },
                {
                    name: "Peers",
                    icon: "mdi mdi-vpn",
                    route: "peers",
                    show: this.hasGroup("member"),
                },
                {
                    name: "Instructions",
                    icon: "mdi mdi-information-outline",
                    route: "instructions",
                    show: this.hasGroup("member"),
                },
                {
                    name: "Settings",
                    icon: "mdi mdi-cogs",
                    route: "settings.general",
                    show: this.hasGroup("administrator"),
                },
            ],
        };
    },

    beforeUnmount() {
        window.removeEventListener("resize", this.updateArrows);
    },

    mounted() {
        this.updateArrows();
        window.addEventListener("resize", this.updateArrows);
        window.addEventListener("resize", this.updateWidth);
    },

    computed: {
        currentRouteName() {
            return this.$route.name;
        },

        showNavBar() {
            return this.widthScreen <= 1023;
        },
    },

    beforeUnmount() {
        window.removeEventListener("resize", this.updateWidth);
    },

    methods: {
        updateWidth() {
            this.widthScreen = window.innerWidth;
        },

        isActive(name) {
            return this.currentRouteName === name;
        },

        scrollNav(distance) {
            this.$refs.navContainer.scrollBy({
                left: distance,
                behavior: "smooth",
            });
        },

        hasGroup(name) {
            try {
                return this.$user.groups.some(
                    (item) => item.slug == name || item.slug == "administrator"
                );
            } catch (TypeError) {}
        },

        updateArrows() {
            const container = this.$refs.navContainer;
            if (container) {
                const scrollLeft = container.scrollLeft;
                const clientWidth = container.clientWidth;
                const scrollWidth = container.scrollWidth;

                this.showLeftArrow = scrollLeft > 0;
                this.showLeftFade = scrollLeft > 0;

                this.showRightArrow =
                    Math.ceil(scrollLeft + clientWidth) < scrollWidth;
                this.showRightFade =
                    Math.ceil(scrollLeft + clientWidth) < scrollWidth;
            }
        },
    },
};
</script>

<style scoped lang="css">
.navbar {
    position: relative;
    width: 100%;
    min-width: 100px;
    display: flex;
    justify-content: center;
    align-items: center;
    overflow: hidden;
    border-bottom: 1px solid gray;
}

.fade {
    position: absolute;
    top: 0;
    width: 50px;
    height: 100%;
    z-index: 9;
    pointer-events: none;
}

.fade.left {
    left: 0;
    //background: linear-gradient(to right, rgba(51, 51, 51, 1), rgba(51, 51, 51, 0));
    background-color: white;
}

.fade.right {
    right: 0;
    //background: linear-gradient(to left, rgba(51, 51, 51, 1), rgba(51, 51, 51, 0));
    background-color: white;
}

.arrow {
    position: absolute;
    top: 50%;
    transform: translateY(-50%);
    background: rgba(0, 0, 0, 0.5);
    color: white;
    border: none;
    cursor: pointer;
    padding: 0.1rem 0.6rem;
    font-size: 1rem;
    border-radius: 50%;
    z-index: 10;
}

.arrow.left {
    left: 5px;
}

.arrow.right {
    right: 5px;
}

.nav-container {
    width: 100%;
    overflow-x: auto;
    white-space: nowrap;
    scroll-behavior: smooth;
    min-width: 100px;
    max-width: 600px;
}

.nav-container::-webkit-scrollbar {
    display: none;
}

nav {
    min-width: 100px;
    max-width: 300px;
    display: flex;
    padding: 0 1rem;
}

ul {
    display: flex;
    gap: 2rem;
}

li {
    display: flex;
    gap: 1rem;
}

.enlace {
    display: flex;
    gap: 1rem;
    padding: 1rem 0;
}

.active {
    position: relative;
    color: var(--blue);
}

.active::before {
    content: "";
    position: absolute;
    width: 90%;
    height: 0.2rem;
    bottom: 0;
    left: 0;
    right: 0;
    margin: auto;
    background-color: var(--blue);
    border-radius: 1rem 1rem 0 0;
}

@media (max-width: 580px) {
}
</style>
