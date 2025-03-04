<template>
    <div>
        <v-row>
            <v-col cols="12">
                <h2 class="">Instructions</h2>
            </v-col>
        </v-row>

        <v-row cols="12">
            <v-col cols="12" md="4"
                v-for="(item, index) in instructions"
            > 
                <v-card-instruction
                    :key="index"
                    :title="item.title"
                    :description="item.description"
                    :number="item.number"
                    :image="item.image"
                    :btnTitle="item.btnTitle"
                    :btnUrl="item.btnUrl"
                />
            </v-col>
        </v-row>
    </div>
</template>

<script>
export default {
    data() {
        return {
            instructions: [
                {
                    title: 'create a Peer',
                    description: 'Go to the "Peers" section. Click on "Create New Peer," fill in the required information, and save the configuration.',
                    image: 'img/key.png',
                    number: 1,
                },
                {
                    title: 'Scan QR code',
                    description: 'Once the peer is created, download the configuration file or scan the provided QR code to easily set it up in WireGuard.',
                    image: 'img/QR.png',
                    number: 2
                },
                {
                    title: 'Download WireGuard',
                    description: 'Go to the official WireGuard website and download the appropriate application for your device (Windows, macOS, Android, iOS, or Linux). Install the application following the instructions.',
                    image: 'img/WireGuard.png',
                    number: 3,
                    btnTitle: 'Download WireGuard',
                    btnUrl: 'https://www.wireguard.com/install/',
                },
                {
                    title: 'Import Configuration in WireGuard',
                    description: 'Open WireGuard and select "Import Configuration." Upload the downloaded file or use the QR scan option in the mobile app.',
                    image: 'img/file.png',
                    number: 4,
                },
                {
                    title: 'Activate the VPN',
                    description: 'Once configured, select the peer in WireGuard and activate the tunnel using the connection switch or button.',
                    image: 'img/PowerOn.png',
                    number: 5,
                },
                {
                    title: 'Verify the Connection',
                    description: 'Check that your VPN connection is active by reviewing the WireGuard interface or verifying your public IP address.',
                    image: 'img/Verify.png',
                    number: 6
                },
                {
                    title: 'Keep the Peer Active',
                    description: 'Make sure the peer is active in order to use it.',
                    image: 'img/Check.png',
                    number: 7,
                },
                {
                    title: 'Technical Support.',
                    description: 'If you have trouble connecting, refer to the help section on the platform or contact us directly for technical support.',
                    image: 'img/support.png',
                    number: 8
                },
                {
                    title: 'Disconnect the VPN',
                    description: 'If you need to disconnect, select the active peer and use the switch to deactivate the tunnel.',
                    image: 'img/PowerOff.png',
                    number: 9
                },
            ]
        }
    },
}
</script>

<style scoped>
</style>
