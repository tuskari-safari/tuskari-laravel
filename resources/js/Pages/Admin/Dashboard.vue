<template>

    <Head title="Dashboard" />
    <div class="kt-portlet">
        <div class="kt-portlet__body kt-portlet__body--fit">
            <div class="dashboard-container">
                <!-- Header -->
                <div class="dashboard-header">
                    <h2 class="text-dark">Dashboard Overview</h2>
                </div>

                <!-- Stats Cards -->
                <div class="row mb-4">
                    <div class="col-lg-3 col-md-6 mb-4">
                        <div class="stats-card card shadow-sm">
                            <div class="card-body">
                                <div class="d-flex align-items-center">
                                    <div class="stats-icon bg-gradient-primary">
                                        <Icon icon="mdi:account-check" width="24" height="24" />
                                    </div>
                                    <div class="stats-content">
                                        <Link :href="route('admin.users', { active: 1 })" class="stats-link">
                                        <h3 class="stats-number">{{ userData.active_user }}</h3>
                                        </Link>
                                        <p class="stats-label">Active Users</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-3 col-md-6 mb-4">
                        <div class="stats-card card shadow-sm">
                            <div class="card-body">
                                <div class="d-flex align-items-center">
                                    <div class="stats-icon bg-gradient-danger">
                                        <Icon icon="mdi:account-off" width="24" height="24" />
                                    </div>
                                    <div class="stats-content">
                                        <Link :href="route('admin.users', { active: 0 })" class="stats-link">
                                        <h3 class="stats-number">{{ userData.inactive_user }}</h3>
                                        </Link>
                                        <p class="stats-label">Inactive Users</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-3 col-md-6 mb-4">
                        <div class="stats-card card shadow-sm">
                            <div class="card-body">
                                <div class="d-flex align-items-center">
                                    <div class="stats-icon bg-gradient-success">
                                        <Icon icon="mdi:store" width="24" height="24" />
                                    </div>
                                    <div class="stats-content">
                                        <Link :href="route('admin.vendors', { active: 1 })" class="stats-link">
                                        <h3 class="stats-number">{{ userData.active_vendor }}</h3>
                                        </Link>
                                        <p class="stats-label">Active Vendors</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-3 col-md-6 mb-4">
                        <div class="stats-card card shadow-sm">
                            <div class="card-body">
                                <div class="d-flex align-items-center">
                                    <div class="stats-icon bg-gradient-warning">
                                        <Icon icon="mdi:store-off" width="24" height="24" />
                                    </div>
                                    <div class="stats-content">
                                        <Link :href="route('admin.vendors', { active: 0 })" class="stats-link">
                                        <h3 class="stats-number">{{ userData.inactive_vendor }}</h3>
                                        </Link>
                                        <p class="stats-label">Inactive Vendors</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-3 col-md-6 mb-4">
                        <div class="stats-card card shadow-sm">
                            <div class="card-body">
                                <div class="d-flex align-items-center">
                                    <div class="stats-icon bg-gradient-info">
                                        <Icon icon="mdi:elephant" width="24" height="24" />
                                    </div>
                                    <div class="stats-content">
                                        <Link :href="route('admin.safari.index', { approval: 1 })" class="stats-link">
                                        <h3 class="stats-number">{{ userData.approved_safari }}</h3>
                                        </Link>
                                        <p class="stats-label">Approved Safaris</p>

                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-3 col-md-6 mb-4">
                        <div class="stats-card card shadow-sm">
                            <div class="card-body">
                                <div class="d-flex align-items-center">
                                    <div class="stats-icon bg-gradient-teal">
                                        <Icon icon="mdi:clock-outline" width="24" height="24" />
                                    </div>
                                    <div class="stats-content">
                                        <Link :href="route('admin.safari.index', { approval: 0 })" class="stats-link">
                                        <h3 class="stats-number">{{ userData.pending_safari }}</h3>
                                        </Link>
                                        <p class="stats-label">Pending Safaris</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-3 col-md-6 mb-4">
                        <div class="stats-card card shadow-sm">
                            <div class="card-body">
                                <div class="d-flex align-items-center">
                                    <div class="stats-icon bg-gradient-teal">
                                        <Icon icon="mdi:clock-outline" width="24" height="24" />
                                    </div>
                                    <div class="stats-content">
                                        <Link :href="route('admin.safari.index', { approval: 2 })" class="stats-link">
                                        <h3 class="stats-number">{{ userData.rejected_safari }}</h3>
                                        </Link>
                                        <p class="stats-label">Rejected Safaris</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Charts Section -->
                <div class="row">
                    <!-- Users Chart -->
                    <div class="col-lg-6 mb-4">
                        <div class="card shadow">
                            <div class="card-header bg-light-blue">
                                <div class="d-flex justify-content-between align-items-center">
                                    <h5 class="mb-0">{{ form.user_type }} Analytics</h5>
                                </div>
                            </div>
                            <div class="card-body">
                                <!-- Filter Buttons -->
                                <div class="d-flex justify-content-between align-items-center mb-3">
                                    <div class="btn-group" role="group">
                                        <button @click="form.user_type = 'Users'"
                                            :class="['btn btn-sm', form.user_type == 'Users' ? 'btn-primary' : 'btn-outline-primary']">
                                            Users
                                        </button>
                                        <button @click="form.user_type = 'Vendors'"
                                            :class="['btn btn-sm', form.user_type == 'Vendors' ? 'btn-primary' : 'btn-outline-primary']">
                                            Vendors
                                        </button>
                                    </div>
                                </div>
                                <apexchart type="area" height="250" :options="usersChartOptions"
                                    :series="usersSeries" />
                            </div>
                        </div>
                    </div>

                    <!-- Bookings Chart -->
                    <div class="col-lg-6 mb-4">
                        <div class="card shadow">
                            <div class="card-header bg-light-green">
                                <h5 class="mb-0">{{ form.booking_period }} Bookings</h5>
                            </div>
                            <div class="card-body">
                                <apexchart type="area" height="294" :options="bookingsChartOptions"
                                    :series="bookingsSeries" />
                            </div>
                        </div>
                    </div>

                    <!-- Payments Chart -->
                    <div class="col-lg-12 mb-4">
                        <div class="card shadow">
                            <div class="card-header bg-light-purple">
                                <h5 class="mb-0">Monthly Payments</h5>
                            </div>
                            <div class="card-body">
                                <apexchart type="area" height="300" :options="paymentsChartOptions"
                                    :series="paymentsSeries" />
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import { useForm } from "@inertiajs/vue3";
import { onMounted, ref, watch } from "vue";
import { Icon } from "@iconify/vue";

const props = defineProps(["data"]);

const userData = ref({});
userData.value = props.data;

const form = useForm({
    user_type: "Users",
});

// Dynamic data from controller
const usersSeries = ref([
    {
        name: "Users",
        data: userData.value?.monthly_users?.map(item => item.total) || [],
    },
]);

const bookingsSeries = ref([
    {
        name: "Monthly Bookings",
        data: userData.value?.monthly_bookings?.map(item => item.total) || [],
    },
]);

const paymentsSeries = [
    {
        name: "Successful Payments",
        data: userData.value?.monthly_payments_success?.map(item => item.total) || [],
    },
    {
        name: "Failed Payments",
        data: userData.value?.monthly_payments_failed?.map(item => item.total) || [],
    },
];

watch(() => form.user_type, () => {
    if (form.user_type === "Users") {
        usersSeries.value = [{
            name: "Users",
            data: userData.value?.monthly_users?.map(item => item.total) || [],
        }];
        usersChartOptions.value.yaxis.title.text = "Users";
    } else {
        usersSeries.value = [{
            name: "Vendors",
            data: userData.value?.monthly_vendors?.map(item => item.total) || [],
        }];
        usersChartOptions.value.yaxis.title.text = "Vendors";
    }
});

const usersChartOptions = ref({
    chart: {
        type: "area",
        toolbar: { show: false },
    },
    colors: ["#ff4513"],
    dataLabels: { enabled: false },
    stroke: {
        show: true,
        width: 2,
        curve: "smooth",
        colors: ["green"],
    },
    xaxis: {
        categories: userData.value?.monthly_users?.map(item => item.month) || ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"],
    },
    yaxis: {
        title: { text: form.user_type },
        min: 0,
    },
    markers: {
        size: 2,
        colors: ["#ffffff"],
        strokeColors: "#2979ff",
        strokeWidth: 3,
        hover: { size: 4 },
    },
    fill: {
        type: "gradient",
        gradient: {
            shade: "light",
            type: "vertical",
            shadeIntensity: 0.5,
            gradientToColors: ["#2979ff"],
            opacityFrom: 0.9,
            opacityTo: 0.6,
            stops: [0, 100],
        },
    },
});
const bookingTotals = userData.value?.monthly_bookings?.map(item => Number(item.total)) || [];
const maxBookings = Math.max(...bookingTotals, 0);

// Set y-axis max: at least 10, else round up a bit beyond maxBookings
const yMax = maxBookings <= 10 ? 10 : Math.ceil(maxBookings + maxBookings * 0.2);

const bookingsChartOptions = {
    chart: {
        type: "area",
        toolbar: { show: false },
    },
    colors: ["#ff4513"],
    dataLabels: { enabled: false },
    stroke: {
        show: true,
        width: 2,
        curve: "smooth",
        colors: ["green"],
    },
    xaxis: {
        categories: userData.value?.monthly_bookings?.map(item => item.month) || [],
    },
    yaxis: {
        min: 0,
        max: yMax,
        labels: {
            formatter: val => Number.isInteger(val) ? val : ""
        }
    },

    markers: {
        size: 2,
        colors: ["#ffffff"],
        strokeColors: "#2979ff",
        strokeWidth: 3,
        hover: { size: 4 },
    },
    fill: {
        type: "gradient",
        gradient: {
            shade: "light",
            type: "vertical",
            shadeIntensity: 0.5,
            gradientToColors: ["#2979ff"],
            opacityFrom: 0.9,
            opacityTo: 0.6,
            stops: [0, 100],
        },
    },
};

const paymentsChartOptions = {
    chart: {
        type: "area",
        height: 350,
        toolbar: { show: false },
    },
    colors: ["#3b82f6", "#ef4444"],
    dataLabels: { enabled: false },
    stroke: {
        show: true,
        width: 2,
        curve: "smooth",
        colors: ["#3b82f6", "#ef4444"],
    },
    xaxis: {
        categories: userData.value?.monthly_payments_success?.map(item => item.month) || ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"],
    },
    yaxis: {
        title: { text: "Amount" },
        min: 0,
    },
    markers: {
        size: 2,
        colors: ["#ffffff"],
        strokeColors: "#2979ff",
        strokeWidth: 1,
        hover: { size: 4 },
    },
    fill: {
        type: "gradient",
        gradient: {
            shade: "light",
            type: "vertical",
            shadeIntensity: 0.5,
            opacityFrom: 0.7,
            opacityTo: 0.3,
            stops: [0, 100],
        },
    },
    legend: {
        show: true,
        position: 'top',
    },
};

onMounted(() => {
    emit.emit("pageName", "Dashboard", []);
});

</script>

<style scoped>
.dashboard-container {
    padding: 15px 1.5rem;
    background: #f8fafc;
    min-height: 100vh;
}

.dashboard-header {
    margin-bottom: 5px;
    padding: 1rem 0;
}

.dashboard-header h2 {
    font-weight: 600;
    color: #1e293b;
    font-size: 1.875rem;
    margin-bottom: 0.5rem;
}

.stats-card {
    background: white;
    border-radius: 12px;
    border: 1px solid #e2e8f0;
    transition: all 0.2s ease;
    height: 100%;
}

.stats-card:hover {
    transform: translateY(-2px);
    box-shadow: 0 10px 25px rgba(0, 0, 0, 0.08);
    border-color: #cbd5e1;
}

.stats-card .card-body {
    padding: 1.5rem;
}

.stats-icon {
    width: 48px;
    height: 48px;
    border-radius: 8px;
    display: flex;
    align-items: center;
    justify-content: center;
    margin-right: 1rem;
    color: white;
}

.bg-gradient-primary {
    background: #3b82f6;
}

.bg-gradient-success {
    background: #10b981;
}

.bg-gradient-info {
    background: #06b6d4;
}

.bg-gradient-warning {
    background: #f59e0b;
}

.bg-gradient-danger {
    background: #ef4444;
}

.bg-gradient-teal {
    background: #14b8a6;
}

.stats-content {
    flex: 1;
}

.stats-number {
    font-size: 1.875rem;
    font-weight: 700;
    color: #1e293b;
    margin-bottom: 0.25rem;
    line-height: 1;
}

.stats-label {
    font-size: 0.875rem;
    color: #64748b;
    margin: 0;
    font-weight: 500;
}

.stats-link {
    text-decoration: none;
    color: inherit;
}

.stats-link:hover .stats-number {
    color: #3b82f6;
}

.card {
    background: white;
    border-radius: 12px;
    border: 1px solid #e2e8f0;
    box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05);
    transition: all 0.2s ease;
}

.card:hover {
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
}

.card-header {
    background: transparent;
    color: #000 !important;
    border-bottom: 1px solid #e2e8f0;
    padding: 1rem 1.5rem;
    border-radius: 12px 12px 0 0;
}

.bg-light-blue {
    background: linear-gradient(135deg, #e3f2fd 0%, #bbdefb 100%);
    color: #1565c0 !important;
}

.bg-light-green {
    background: linear-gradient(135deg, #e8f5e8 0%, #c8e6c9 100%);
    color: #2e7d32 !important;
}

.bg-light-purple {
    background: linear-gradient(135deg, #f3e5f5 0%, #e1bee7 100%);
    color: #6a1b9a !important;
}

.card-header h5 {
    font-weight: 600;
    font-size: 1rem;
    margin: 0;
}

.card-body {
    padding: 1.5rem;
}

.btn {
    border-radius: 6px;
    font-weight: 500;
    font-size: 0.875rem;
    padding: 0.5rem 1rem;
    border: 1px solid #e2e8f0;
    transition: all 0.2s ease;
}

.btn-primary {
    background: #313a30;
    border-color: #313a30;
    color: white;
}

.btn-outline-primary {
    background: transparent;
    border-color: #313a30;
    color: #313a30;
}

.btn-outline-primary:hover {
    background: #313a30 !important;
    color: white !important;
}

.btn-group .btn {
    margin-right: 0.5rem;
}

.btn-group .btn:hover {
    color: #fff !important;
}

.btn-group .btn:last-child {
    margin-right: 0;
}

.shadow {
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05) !important;
}

.shadow-sm {
    box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05) !important;
}

.mb-4 {
    margin-bottom: 1.5rem !important;
}

@media (max-width: 768px) {
    .dashboard-container {
        padding: 1rem;
    }

    .stats-card .card-body {
        padding: 1rem;
    }

    .stats-icon {
        width: 40px;
        height: 40px;
    }

    .stats-number {
        font-size: 1.5rem;
    }

    .btn-group {
        flex-direction: column;
        gap: 0.5rem;
    }

    .btn-group .btn {
        margin-right: 0;
        width: 100%;
    }

    .card-body {
        padding: 1rem;
    }
}
</style>