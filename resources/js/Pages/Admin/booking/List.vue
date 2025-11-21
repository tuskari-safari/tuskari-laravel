<template>
    <div>

        <Head title="Booking Lists" />
        <div class="kt-portlet kt-portlet--mobile">
            <div class="kt-portlet__body">
                <div id="kt_table_1_wrapper" class="dataTables_wrapper dt-bootstrap4 no-footer">
                    <div class="row">
                        <div class="col-sm-12 col-md-6">
                            <perPageDropdown />
                        </div>

                    </div>
                    <div class="row table-responsive table_fixed_width">
                        <div class="col-sm-12">
                            <table
                                class="table table-striped- table-bordered table-hover table-checkable dataTable no-footer dtr-inline"
                                id="kt_table_1" role="grid" aria-describedby="kt_table_1_info" style="width: 1115px">
                                <thead>
                                    <tr role="row">
                                        <th tabindex="0" aria-controls="kt_table_1" rowspan="1" colspan="1"
                                            style="width: 10%" aria-sort="ascending"
                                            aria-label="Agent: activate to sort column descending">
                                            Booking ID
                                            <i class="fa fa-fw fa-sort pull-right" style="cursor: pointer"
                                                @click="ListHelper.sortBy('booking_id')"></i>
                                        </th>
                                        <th tabindex="0" aria-controls="kt_table_1" rowspan="1" colspan="1"
                                            style="width: 20%" aria-sort="ascending"
                                            aria-label="Agent: activate to sort column descending">
                                            Traveler Name
                                            <!-- <i class="fa fa-fw fa-sort pull-right" style="cursor: pointer" @click="ListHelper.sortBy('title')"></i> -->
                                        </th>
                                        <th tabindex="0" aria-controls="kt_table_1" rowspan="1" colspan="1"
                                            style="width: 20%" aria-sort="ascending"
                                            aria-label="Agent: activate to sort column descending">
                                            Safari Name
                                            <!-- <i class="fa fa-fw fa-sort pull-right" style="cursor: pointer" @click="ListHelper.sortBy('title')"></i> -->
                                        </th>
                                        <th tabindex="0" aria-controls="kt_table_1" rowspan="1" colspan="1"
                                            style="width: 10%" aria-sort="ascending"
                                            aria-label="Agent: activate to sort column descending">
                                            Total Price ($)
                                            <i class="fa fa-fw fa-sort pull-right" style="cursor: pointer"
                                                @click="ListHelper.sortBy('total_price')"></i>
                                        </th>

                                        <th tabindex="0" aria-controls="kt_table_1" rowspan="1" colspan="1"
                                            style="width: 10%" aria-sort="ascending"
                                            aria-label="Agent: activate to sort column descending">
                                            Created At
                                            <i class="fa fa-fw fa-sort pull-right" style="cursor: pointer"
                                                @click="ListHelper.sortBy('created_at')"></i>
                                        </th>
                                        <th tabindex="0" aria-controls="kt_table_1" rowspan="1" colspan="1"
                                            style="width: 10%" aria-sort="ascending"
                                            aria-label="Agent: activate to sort column descending">
                                            Payment Status
                                        </th>

                                        <th tabindex="0" aria-controls="kt_table_1" rowspan="1" colspan="1"
                                            style="width: 10%" aria-sort="ascending"
                                            aria-label="Agent: activate to sort column descending">
                                            Booking Status
                                        </th>

                                        <th tabindex="0" aria-controls="kt_table_1" rowspan="1" colspan="1"
                                            style="width: 10%" aria-sort="ascending"
                                            aria-label="Agent: activate to sort column descending">
                                            Action
                                        </th>
                                    </tr>

                                    <tr class="filter">
                                        <th>
                                            <input type="search" v-model="form.booking_id" placeholder=""
                                                autocomplete="off" class="form-control-sm form-filter" />
                                        </th>
                                        <th>
                                            <input type="search" v-model="form.traveler" placeholder=""
                                                autocomplete="off" class="form-control-sm form-filter" />
                                        </th>
                                        <th>
                                            <input type="search" v-model="form.safari" placeholder="" autocomplete="off"
                                                class="form-control-sm form-filter" />
                                        </th>
                                        <th>
                                            <input type="search" v-model="form.price" placeholder="" autocomplete="off"
                                                class="form-control-sm form-filter" />
                                        </th>


                                        <th>
                                            <datepicker v-model="form.date" :format='"MMM dd, yyyy"' />
                                        </th>
                                        <th>
                                            <select class="form-control form-control-sm form-filter kt-input"
                                                v-model="form.status" title="Select" data-col-index="2">
                                                <option value="">Select One</option>
                                                <option value="confirmed">Paid</option>
                                                <option value="pending">Unpaid</option>
                                            </select>
                                        </th>

                                        <th>
                                            <select class="form-control form-control-sm form-filter kt-input"
                                                v-model="form.booking_status" title="Select" data-col-index="2">
                                                <option value="">Select One</option>
                                                <option value="ACTIVE">Active</option>
                                                <option value="CANCEL">Cancelled</option>
                                                <option value="COMPLETED">Completed</option>
                                                <option value="PENDING">Pending</option>
                                            </select>
                                        </th>
                                        <th>
                                            <div class="row justify-content-center align-items-center">
                                                <div class="col-md-6">
                                                    <button class="btn btn-brand kt-btn btn-sm kt-btn--icon button-fx"
                                                        @click="search">
                                                        <span>
                                                            <i class="la la-search"></i>
                                                            <span>Search</span>
                                                        </span>
                                                    </button>
                                                </div>
                                                <div class="col-md-6">
                                                    <button
                                                        class="btn btn-secondary kt-btn btn-sm kt-btn--icon button-fx"
                                                        @click="resetSearch">
                                                        <span>
                                                            <i class="la la-close"></i>
                                                            <span>Reset</span>
                                                        </span>
                                                    </button>
                                                </div>
                                            </div>
                                        </th>
                                    </tr>
                                </thead>
                                <tbody v-auto-animate>
                                    <tr role="row" class="odd" v-for="booking in props.bookings.data" :key="booking.id">
                                        <td>{{ booking?.booking_id ?? 'NA' }}</td>
                                        <td>{{ booking?.traveler?.full_name ?? 'NA' }}</td>
                                        <td>{{ booking?.safari?.title ?? 'NA' }}</td>
                                        <td>{{ booking?.total_price ?? 'NA' }}</td>
                                        <td>{{ ListHelper.dateFormat(booking.created_at, "MMM DD, YYYY") }}</td>
                                        <td class="align-center">
                                            <span @click="changeStatus(booking.id)">{{
                                                booking.payment_status == 'confirmed' ? "Paid" : "Unpaid"
                                            }}</span>
                                        </td>
                                        <td class="align-center">
                                            <span class="kt-badge kt-badge--inline kt-badge--pill cursor-pointer"
                                                :class="booking.status === 'ACTIVE' ? 'kt-badge--success' :
                                                    booking.status === 'CANCELLED' ? 'kt-badge--warning' :
                                                        booking.status === 'COMPLETED' ? 'kt-badge--primary' :
                                                            'kt-badge--info'">
                                                {{
                                                    booking.status === 'ACTIVE' ? 'Active' :
                                                        booking.status === 'CANCELLED' ? 'Cancelled' :
                                                            booking.status === 'COMPLETED' ? 'Completed' :
                                                                'Pending'
                                                }}
                                            </span>

                                        </td>
                                        <td nowrap="" class="align-center">
                                            <span class="dropdown">
                                                <a href="javascript:void(0)" class="btn btn-sm btn-clean btn-icon btn-icon-md"
                                                    data-toggle="dropdown">
                                                    <i class="la la-ellipsis-h"></i>
                                                </a>
                                                <div class="dropdown-menu dropdown-menu-right">
                                                    <button href="javascript:void(0)" class="dropdown-item"
                                                        @click="viewBooking(booking)">
                                                        <i class="fa fa-eye"></i> View
                                                    </button>
                                                </div>
                                            </span>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        <div class="col-sm-12" v-if="bookings.total == 0">
                            <div class="no_data text-center">
                                <h3>No data Found</h3>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-12 col-md-5">
                        <div class="dataTables_info" id="kt_table_1_info" role="status" aria-live="polite">
                            Showing {{ bookings.from }} to {{ bookings.to }} of
                            {{ bookings.total }} entries
                        </div>
                    </div>
                    <div class="col-sm-12 col-md-7">
                        <div class="float-right">
                            <Bootstrap4Pagination :data="bookings" :limit="2"
                                @pagination-change-page="ListHelper.setPageNum" />
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import { router } from "@inertiajs/vue3";
import { onMounted, onUnmounted } from "vue";
import { Bootstrap4Pagination } from "laravel-vue-pagination";
import ListHelper from "../../../helpers/ListHelper";
import perPageDropdown from "@/components/PerpageDropdown.vue";
import Datepicker from "../../../components/Datepicker.vue";
import { reactive } from "vue";
import { pickBy } from "lodash";

const props = defineProps({
    bookings: Object,
});

const params = () => new URLSearchParams(window.location.search);

const form = reactive({
    booking_id: params().get("booking_id") || null,
    traveler: params().get("traveler") || null,
    safari: params().get("safari") || null,
    price: params().get("price") || null,
    date: params().get("date") || null,
    status: params().get("status") || "",
    booking_status: params().get("booking_status") || "",
});

onMounted(() => {
    emit.emit("pageName", "Booking Management", [
        {
            title: "Booking List",
            routeName: "admin.booking.index",
        },
    ]);

    emit.on("deleteConfirm", function (arg1) {
        deleteConfirm(arg1);
    });

    emit.on("changeStatusConfirm", function (arg1) {
        changeStatusConfirm(arg1);
    });
});

onUnmounted(() => {
    emit.off("changeStatusConfirm");
    emit.off("deleteConfirm");
});

const resetSearch = () => {
    router.visit(route("admin.booking.index"), {
        method: "get",
    });
};

const search = () => {
    form.perPage = params().get("perPage");
    router.visit(route("admin.booking.index"), {
        method: "get",
        data: pickBy(form),
        preserveScroll: true,
    });
};

const viewBooking = (booking) => {
    router.visit(route("admin.view-booking", {booking_id: booking.booking_id}));
};

</script>
<style lang=""></style>