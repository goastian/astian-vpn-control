<template>
    <div>
        <!-- Hero Section -->
        <section
            class="bg-gradient-to-r from-indigo-600 to-indigo-500 text-white py-12 px-6 text-center"
        >
            <h1 class="text-5xl font-extrabold mb-4">
                Enhance Your Privacy with a VPN
            </h1>
            <p class="text-xl mb-8 max-w-3xl mx-auto">
                Protect your online activities and secure your connections with
                a fast and reliable VPN. Browse safely, access restricted
                content, and enjoy peace of mind.
            </p>
            <v-btn
                large
                elevation="3"
                color="blue-accent-3"
                @click="redirectToDownload"
            >
                Download VPN Client
            </v-btn>
        </section>

        <!-- Introduction Section -->
        <section class="py-12 px-6 bg-white">
            <div class="text-center">
                <h2 class="text-4xl font-semibold text-indigo-600 mb-6">
                    Why Use a VPN?
                </h2>
                <p class="text-lg text-gray-700 max-w-3xl mx-auto">
                    A VPN (Virtual Private Network) is essential for anyone who
                    wants to keep their online activities private and secure.
                    With a VPN, you can hide your IP address, encrypt your data,
                    and access content from anywhere in the world.
                </p>
            </div>
        </section>

        <!-- Benefits Section -->
        <section class="py-12 bg-gray-100">
            <div class="text-center">
                <h2 class="text-4xl font-semibold text-indigo-600 mb-6">
                    Benefits of Using a VPN
                </h2>
                <div class="grid md:grid-cols-3 gap-12 px-6">
                    <div class="flex flex-col items-center">
                        <div
                            class="w-16 h-16 rounded-full bg-indigo-600 text-white flex items-center justify-center mb-4"
                        >
                            <i class="mdi mdi-speedometer"></i>
                        </div>
                        <h3 class="text-2xl font-semibold text-indigo-600 mb-2">
                            High Speed
                        </h3>
                        <p class="text-lg text-gray-700">
                            Enjoy fast and seamless browsing without any
                            slowdowns. VPNs are optimized for performance to
                            ensure your activities are smooth.
                        </p>
                    </div>
                    <div class="flex flex-col items-center">
                        <div
                            class="w-16 h-16 rounded-full bg-indigo-600 text-white flex items-center justify-center mb-4"
                        >
                            <i class="mdi mdi-security"></i>
                        </div>
                        <h3 class="text-2xl font-semibold text-indigo-600 mb-2">
                            Robust Security
                        </h3>
                        <p class="text-lg text-gray-700">
                            Protect your sensitive data with military-grade
                            encryption, ensuring your personal information stays
                            safe from hackers and spies.
                        </p>
                    </div>
                    <div class="flex flex-col items-center">
                        <div
                            class="w-16 h-16 rounded-full bg-indigo-600 text-white flex items-center justify-center mb-4"
                        >
                            <i class="mdi mdi-easy"></i>
                        </div>
                        <h3 class="text-2xl font-semibold text-indigo-600 mb-2">
                            Easy Setup
                        </h3>
                        <p class="text-lg text-gray-700">
                            Setting up a VPN is quick and straightforward.
                            Whether you’re a beginner or an expert, you can
                            easily connect to your VPN service in just a few
                            clicks.
                        </p>
                    </div>
                </div>
            </div>
        </section>

        <!-- How to Use Section -->
        <section class="py-12 px-6 bg-white">
            <div class="text-center">
                <h2 class="text-4xl font-semibold text-indigo-600 mb-6">
                    How to Set Up Your VPN
                </h2>
                <p class="text-lg text-gray-700 mb-6">
                    Setting up your VPN is easy. Follow these simple steps to
                    get started with securing your online presence:
                </p>
                <div class="max-w-3xl mx-auto space-y-8">
                    <div class="space-y-4">
                        <h3 class="text-2xl font-semibold text-indigo-600">
                            Install the VPN Client
                        </h3>
                        <p class="text-lg text-gray-700">
                            Download the VPN client from the link below. The
                            client is available for Windows, macOS, Linux, iOS,
                            and Android devices.
                        </p>
                    </div>
                </div>
            </div>
        </section>

        <!-- Download Section -->
        <section class="py-12 px-6 bg-gray-100 text-center">
            <h2 class="text-4xl font-semibold text-indigo-600 mb-6">
                Get Started Today
            </h2>
            <p class="text-lg text-gray-700 mb-6">
                Take control of your online privacy. Download the VPN client
                today and protect yourself from online threats.
            </p>
            <v-btn
                large
                elevation="3"
                color="blue-accent-3"
                @click="redirectToDownload"
            >
                Download VPN Client
            </v-btn>
        </section>
    </div>
</template>

<script>
export default {
    methods: {
        redirectToDownload() {
            window.location.href = "https://www.vpnprovider.com/download";
        },
    },
};
</script>
