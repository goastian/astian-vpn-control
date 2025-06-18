<template>
    <v-admin-layout>
        <div class="q-pa-md">
            <!-- Header -->
            <div class="row items-center q-mb-md">
                <h1 class="text-h4 text-weight-bold">Network Dashboard</h1>
                <q-space />
            </div>

            <!-- Stats Cards -->
            <div class="row q-col-gutter-md q-mb-lg">
                <!-- Servers Card -->
                <q-card class="col-md-3 col-sm-6 col-xs-12 stats-card">
                    <q-card-section class="text-center">
                        <div class="text-h6 text-grey-7">Total Servers</div>
                        <div
                            class="text-h3 text-weight-bold text-primary q-my-sm"
                        >
                            {{ report?.total_servers || 0 }}
                        </div>
                        <q-linear-progress
                            size="8px"
                            :value="report?.total_servers ? 1 : 0"
                            color="primary"
                            class="q-mt-sm"
                        />
                        <div class="text-caption text-grey-7 q-mt-sm">
                            {{ report?.total_servers || 0 }} active servers
                        </div>
                    </q-card-section>
                </q-card>

                <!-- Interfaces Card -->
                <q-card class="col-md-3 col-sm-6 col-xs-12 stats-card">
                    <q-card-section class="text-center">
                        <div class="text-h6 text-grey-7">
                            Network Interfaces
                        </div>
                        <div
                            class="text-h3 text-weight-bold text-green q-my-sm"
                        >
                            {{ report?.total_nets || 0 }}
                        </div>
                        <q-linear-progress
                            size="8px"
                            :value="report?.total_nets ? 1 : 0"
                            color="green"
                            class="q-mt-sm"
                        />
                        <div class="text-caption text-grey-7 q-mt-sm">
                            {{ report?.total_nets || 0 }} active interfaces
                        </div>
                    </q-card-section>
                </q-card>

                <!-- WireGuard Peers -->
                <q-card class="col-md-3 col-sm-6 col-xs-12 stats-card">
                    <q-card-section class="text-center">
                        <div class="text-h6 text-grey-7">WireGuard Peers</div>
                        <div
                            class="text-h3 text-weight-bold text-orange q-my-sm"
                        >
                            {{ report?.total_peers || 0 }}
                        </div>
                        <q-linear-progress
                            size="8px"
                            :value="report?.total_peers ? 0.6 : 0"
                            color="orange"
                            class="q-mt-sm"
                        />
                        <div class="text-caption text-grey-7 q-mt-sm">
                            {{ report?.total_peers || 0 }} total peers
                        </div>
                    </q-card-section>
                </q-card>

                <!-- Browser Devices -->
                <q-card class="col-md-3 col-sm-6 col-xs-12 stats-card">
                    <q-card-section class="text-center">
                        <div class="text-h6 text-grey-7">Browser Devices</div>
                        <div class="text-h3 text-weight-bold text-blue q-my-sm">
                            {{ report?.total_devices || 0 }}
                        </div>
                        <q-linear-progress
                            size="8px"
                            :value="report?.total_devices ? 0.45 : 0"
                            color="blue"
                            class="q-mt-sm"
                        />
                        <div class="text-caption text-grey-7 q-mt-sm">
                            {{ report?.total_devices || 0 }} connected devices
                        </div>
                    </q-card-section>
                </q-card>
            </div>

            <!-- Recent Activity Section -->
            <div class="row q-col-gutter-md">
                <!-- Recent Networks -->
                <div class="col-md-6 col-xs-12">
                    <q-card class="activity-card">
                        <q-card-section>
                            <div class="row items-center">
                                <div class="text-h6">
                                    Recent Network Interfaces
                                </div>
                                <q-space />
                                <q-btn
                                    flat
                                    dense
                                    icon="mdi-dots-vertical"
                                    color="grey"
                                />
                            </div>
                        </q-card-section>

                        <q-separator />

                        <q-card-section class="q-pa-none">
                            <q-table
                                flat
                                :rows="report?.last_nets || []"
                                :columns="netColumns"
                                row-key="id"
                                hide-pagination
                                :rows-per-page-options="[0]"
                            >
                                <template v-slot:body-cell-status="props">
                                    <q-td :props="props">
                                        <q-badge
                                            :color="
                                                props.row.active
                                                    ? 'green'
                                                    : 'grey'
                                            "
                                            :label="
                                                props.row.active
                                                    ? 'active'
                                                    : 'inactive'
                                            "
                                        />
                                    </q-td>
                                </template>

                                <template v-slot:body-cell-devices="props">
                                    <q-td :props="props">
                                        <q-badge
                                            color="blue"
                                            :label="props.row.devices"
                                        />
                                    </q-td>
                                </template>

                                <template v-slot:body-cell-actions="props">
                                    <q-td :props="props" class="text-right">
                                        <q-btn
                                            icon="mdi-eye"
                                            flat
                                            dense
                                            size="sm"
                                            color="primary"
                                        />
                                    </q-td>
                                </template>
                            </q-table>
                        </q-card-section>
                    </q-card>
                </div>

                <!-- Browser Devices -->
                <div class="col-md-6 col-xs-12">
                    <q-card class="activity-card">
                        <q-card-section>
                            <div class="row items-center">
                                <div class="text-h6">
                                    Recent Browser Devices
                                </div>
                                <q-space />
                                <q-btn
                                    flat
                                    dense
                                    icon="mdi-dots-vertical"
                                    color="grey"
                                />
                            </div>
                        </q-card-section>

                        <q-separator />

                        <q-card-section class="q-pa-none">
                            <q-table
                                flat
                                :rows="report?.last_devices || []"
                                :columns="deviceColumns"
                                row-key="id"
                                hide-pagination
                                :rows-per-page-options="[0]"
                            >
                                <template v-slot:body-cell-status="props">
                                    <q-td :props="props">
                                        <q-badge
                                            :color="
                                                props.row.status === 'online'
                                                    ? 'green'
                                                    : 'grey'
                                            "
                                            :label="
                                                props.row.status || 'offline'
                                            "
                                        />
                                    </q-td>
                                </template>

                                <template v-slot:body-cell-browser="props">
                                    <q-td :props="props">
                                        <div class="row items-center">
                                            <q-icon
                                                :name="
                                                    getBrowserIcon(
                                                        props.row.browser
                                                    )
                                                "
                                                size="sm"
                                                class="q-mr-xs"
                                            />
                                            {{ props.row.browser || "Unknown" }}
                                        </div>
                                    </q-td>
                                </template>
                            </q-table>
                        </q-card-section>
                    </q-card>
                </div>
            </div>

            <!-- Server Status Chart -->
            <div class="row q-mt-md">
                <div class="col-12">
                    <q-card class="chart-card">
                        <q-card-section>
                            <div class="text-h6">Server Status Overview</div>
                        </q-card-section>
                        <q-card-section>
                            <div class="chart-placeholder flex flex-center">
                                <div class="text-grey-6">
                                    {{ report?.total_servers || 0 }} servers
                                    active
                                </div>
                            </div>
                        </q-card-section>
                    </q-card>
                </div>
            </div>
        </div>
    </v-admin-layout>
</template>

<script>
export default {
    data() {
        return {
            report: {},
            netColumns: [
                {
                    name: "name",
                    label: "Interface Name",
                    field: "name",
                    align: "left",
                    sortable: true,
                },
                {
                    name: "server_country",
                    label: "Server Location",
                    field: "server_country",
                    align: "left",
                },
                {
                    name: "subnet",
                    label: "Subnet",
                    field: "subnet",
                    align: "left",
                },
                {
                    name: "devices",
                    label: "Devices",
                    field: "devices",
                    align: "center",
                },
                {
                    name: "status",
                    label: "Status",
                    field: "active",
                    align: "center",
                },
                {
                    name: "actions",
                    label: "",
                    field: "",
                    align: "right",
                },
            ],
            deviceColumns: [
                {
                    name: "name",
                    label: "Device Name",
                    field: "name",
                    align: "left",
                    sortable: true,
                },
                {
                    name: "browser",
                    label: "Browser",
                    field: "browser",
                    align: "left",
                },
                {
                    name: "os",
                    label: "OS",
                    field: "os",
                    align: "left",
                },
                {
                    name: "last_active",
                    label: "Last Active",
                    field: "last_active",
                    align: "left",
                },
                {
                    name: "status",
                    label: "Status",
                    field: "status",
                    align: "center",
                },
            ],
        };
    },

    created() {
        this.getReport();
    },

    methods: {
        async getReport() {
            try {
                const res = await this.$api.get("/admin/dashboard");
                if (res.status == 200) {
                    this.report = res.data.data;
                }
            } catch (error) {
                console.error("Error fetching report:", error);
            }
        },

        refreshData() {
            this.getReport();
            this.$q.notify({
                message: "Data refreshed",
                color: "positive",
                icon: "mdi-check",
            });
        },

        getBrowserIcon(browser) {
            if (!browser) return "mdi-web";
            const icons = {
                chrome: "mdi-google-chrome",
                firefox: "mdi-firefox",
                safari: "mdi-apple-safari",
                edge: "mdi-microsoft-edge",
                opera: "mdi-opera",
            };
            return icons[browser.toLowerCase()] || "mdi-web";
        },
    },
};
</script>

<style lang="scss" scoped>
.stats-card {
    border-radius: 12px;
    box-shadow: 0 4px 20px 0 rgba(0, 0, 0, 0.05);
    transition: transform 0.3s ease;

    &:hover {
        transform: translateY(-5px);
    }

    .text-h3 {
        font-size: 2.5rem;
    }
}

.activity-card {
    border-radius: 12px;
    box-shadow: 0 4px 20px 0 rgba(0, 0, 0, 0.05);
}

.chart-card {
    border-radius: 12px;
    box-shadow: 0 4px 20px 0 rgba(0, 0, 0, 0.05);

    .chart-placeholder {
        height: 300px;
        border-radius: 8px;
    }
}

.q-table {
    thead tr,
    tbody td {
        height: 48px;
    }

    tbody tr:hover {
        background-color: rgba(0, 0, 0, 0.02);
    }
}
</style>
