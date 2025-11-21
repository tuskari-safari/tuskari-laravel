<template lang="">
    <div>
        <Head title="Safari/Listing Lists" />

        <div class="kt-portlet kt-portlet--mobile">
            <div class="kt-portlet__body">
                <div id="kt_table_1_wrapper" class="dataTables_wrapper dt-bootstrap4 no-footer">
                    <div class="row row-drop">
                        <div class="col-md-4 col-drop">
                            <perPageDropdown />
                        </div>
                        <div class="col-md-8 col-drop">
                            <div class="wrapbtns-new">
                            <div id="kt_table_1_filter" class="dataTables_filter">
                                <Link :href="route('admin.collection.index')" class="btn btn-primary me-2">
                                    <Icon icon="fluent:collections-20-regular" width="25" height="25" class="mr-2" />
                                    Create Collection</Link>
                            </div>
                             <div id="kt_table_1_filter" class="dataTables_filter">
                                <Link :href="route('admin.available-tags.index')" class="btn btn-primary me-2">
                                    <Icon icon="fluent:collections-20-regular" width="25" height="25" class="mr-2" />
                                    Create Available Tag</Link>
                            </div>
                            </div>
                        </div>
                    </div>
                    <div class="row table-responsive table_fixed_width">
                        <div class="col-sm-12">
                            <table class="table table-striped- table-bordered table-hover table-checkable dataTable no-footer dtr-inline" id="kt_table_1" role="grid" aria-describedby="kt_table_1_info" style="width: 1115px">
                                <thead>
                                    <tr role="row">

                                        <th tabindex="0" aria-controls="kt_table_1" rowspan="1" colspan="1" style="width: 40%" aria-sort="ascending" aria-label="Agent: activate to sort column descending">
                                            Provider
                                          
                                        </th>

                                        <th tabindex="0" aria-controls="kt_table_1" rowspan="1" colspan="1" style="width: 40%" aria-sort="ascending" aria-label="Agent: activate to sort column descending">
                                            Title
                                            <i class="fa fa-fw fa-sort pull-right" style="cursor: pointer" @click="ListHelper.sortBy('title')"></i>
                                        </th>
                                         <th tabindex="0" aria-controls="kt_table_1" rowspan="1" colspan="1" style="width: 40%" aria-sort="ascending" aria-label="Agent: activate to sort column descending">
                                            Location
                                            <i class="fa fa-fw fa-sort pull-right" style="cursor: pointer" @click="ListHelper.sortBy('location')"></i>
                                        </th>

                                         <th tabindex="0" aria-controls="kt_table_1" rowspan="1" colspan="1" style="width: 40%" aria-sort="ascending" aria-label="Agent: activate to sort column descending">
                                            Price ($)
                                            <i class="fa fa-fw fa-sort pull-right" style="cursor: pointer" @click="ListHelper.sortBy('price_for_adult')"></i>
                                        </th>
                                        <th tabindex="0" aria-controls="kt_table_1" rowspan="1" colspan="1" style="width: 30%" aria-sort="ascending" aria-label="Agent: activate to sort column descending">
                                            Is Approved
                                        </th>

                                        <th tabindex="0" aria-controls="kt_table_1" rowspan="1" colspan="1" style="width: 30%" aria-sort="ascending" aria-label="Agent: activate to sort column descending">
                                            Status
                                        </th>

                                        <th tabindex="0" aria-controls="kt_table_1" rowspan="1" colspan="1" style="width: 30%" aria-sort="ascending" aria-label="Agent: activate to sort column descending">
                                            Is Featured
                                        </th>

                                        <th tabindex="0" aria-controls="kt_table_1" rowspan="1" colspan="1" style="width: 10%" aria-sort="ascending" aria-label="Agent: activate to sort column descending">
                                            Action
                                        </th>
                                    </tr>

                                    <tr class="filter">
                                         <th>
                                            <input type="search" v-model="form.provider" placeholder="" autocomplete="off" class="form-control-sm form-filter" />
                                        </th>
                                        <th>
                                            <input type="search" v-model="form.title" placeholder="" autocomplete="off" class="form-control-sm form-filter" />
                                        </th>
                                        <th>
                                            <input type="search" v-model="form.location" placeholder="" autocomplete="off" class="form-control-sm form-filter" />
                                        </th>
                                        <th>
                                            <input type="search" v-model="form.price" placeholder="" autocomplete="off" class="form-control-sm form-filter" />
                                        </th>
                                        <th>
                                            <select class="form-control form-control-sm form-filter kt-input" v-model="form.approval" title="Select" data-col-index="2">
                                                <option value="">Select One</option>
                                                <option value="0">Pending</option>
                                                <option value="1">Approved</option>
                                                <option value="2">Rejected</option>
                                            </select>
                                        </th>

                                        <th>
                                            <select class="form-control form-control-sm form-filter kt-input" v-model="form.status" title="Select" data-col-index="2">
                                                <option value="">Select One</option>
                                                <option value="1">Active</option>
                                                <option value="0">Inactive</option>
                                            </select>
                                        </th>

                                        <th>
                                            <select class="form-control form-control-sm form-filter kt-input" v-model="form.featured" title="Select" data-col-index="2">
                                                <option value="">Select One</option>
                                                <option value="1">Featured</option>
                                                <option value="0">Not Featured</option>
                                            </select>
                                        </th>

                                        <th>
                                            <div class="row justify-content-center align-items-center">
                                                <div class="col-md-6">
                                                    <button class="btn btn-brand kt-btn btn-sm kt-btn--icon button-fx" @click="search">
                                                        <span>
                                                            <i class="la la-search"></i>
                                                            <span>Search</span>
                                                        </span>
                                                    </button>
                                                </div>
                                                <div class="col-md-6">
                                                    <button class="btn btn-secondary kt-btn btn-sm kt-btn--icon button-fx" @click="resetSearch">
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
                                    <tr role="row" class="odd" v-for="safari in props.safaris.data" :key="safari.id">
                                        <td>{{ safari.user.full_name ?? 'NA' }}</td>
                                        <td>{{ safari.title ?? 'NA' }}</td>
                                        <td>{{ safari.location ?? 'NA' }}</td>
                                        <td>{{ safari?.total_price ?? 'NA' }}</td>
                                         <td class="align-center">
                                            <span @click="changeApprovalStatus(safari.id, safari.is_approved)" class="kt-badge kt-badge--inline kt-badge--pill" style="cursor: pointer"
                                            :class="safari.is_approved == 1 ? 'kt-badge--success': (safari.is_approved == 2 ? 'kt-badge--warning' : 'kt-badge--info')">
                                            {{safari?.is_approved == 1 ? "Approved" :
                                            (safari?.is_approved == 2) ? "Rejected" :
                                            'Pending' }}
                                            </span>
                                        </td>

                                        <td class="align-center">
                                            <span @click="changeStatus(safari.id)" style="cursor: pointer" class="kt-badge kt-badge--inline kt-badge--pill cursor-pointer" :class=" safari.status == 1? 'kt-badge--success': 'kt-badge--warning'">{{ safari.status == 1 ? "Active" : "Inactive" }}</span>
                                        </td>

                                        <td class="align-center">
                                            <span @click="changeFeaturedStatus(safari.id)" style="cursor: pointer" class="kt-badge kt-badge--inline kt-badge--pill cursor-pointer" :class=" safari.is_featured == 1? 'kt-badge--success': 'kt-badge--warning'">{{ safari.is_featured == 1 ? "Featured" : "Not Featured" }}</span>
                                        </td>

                                        <td nowrap="" class="align-center">
                                            <span class="dropdown">
                                                <a href="javascript:void(0)" class="btn btn-sm btn-clean btn-icon btn-icon-md" data-toggle="dropdown">
                                                    <i class="la la-ellipsis-h"></i>
                                                </a>
                                                <div class="dropdown-menu dropdown-menu-right">
                                                    <Link class="dropdown-item" :href="route('admin.view-safari', {slug: safari.slug})"><i class="la la-edit"></i> Details</Link>
                                                    <!-- <button href="javascript:void(0)" class="dropdown-item" @click="deleteRecode(safari.id)">
                                                        <i class="fa fa-trash"></i> Delete
                                                    </button> -->
                                                </div>
                                            </span>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        <div class="col-sm-12" v-if="safaris.total == 0">
                            <div class="no_data text-center">
                                <h3>No data Found</h3>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-12 col-md-5">
                        <div class="dataTables_info" id="kt_table_1_info" role="status" aria-live="polite">
                            Showing {{ safaris.from }} to {{ safaris.to }} of
                            {{ safaris.total }} entries
                        </div>
                    </div>
                    <div class="col-sm-12 col-md-7">
                        <div class="float-right">
                            <Bootstrap4Pagination :data="safaris" :limit="2" @pagination-change-page="ListHelper.setPageNum" />
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal return-modal-wraper" id="orderStatusss" v-if="isOpen">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-body">
                        <h2>Safari/Listing Approval</h2>
                        <!-- <div class="return-refund-policy">
                            <span>Are you sure, You want to change the status?</span>
                        </div> -->
                        <div class="select-item-wraper mt-4">
                            <form @submit.prevent="submitStatusForm">
                                
                                <label for="status">Select Status</label>
                                <select class="form-control" v-model="statusForm.status">
                                    <option value="0" >Pending</option>
                                    <option value="1" >Approved</option>
                                    <option value="2" >Rejected</option>
                                </select>
                                <!-- <p class="text-danger" v-if="statusForm.status =='R'">You can't change return status</p> -->
                                <div class="return-item-btn-wrap mt-4">
                                    <div class="kt-portlet__foot col-lg-12">
                                        <div class="kt-form__actions">
                                            <div class="row">
                                                <div class="col-lg-6 kt-align-right">
                                                    <button type="button"
                                                        class="btn btn-secondary kt-btn btn-sm kt-btn--icon button-fx"
                                                        data-bs-dismissss="modal" @click="closeModal">Cancel</button>
                                                </div>
                                                <div class="col-lg-6">
                                                    <button type="submit"
                                                        class="btn btn-brand kt-btn btn-sm kt-btn--icon button-fx"
                                                        >Submit</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                </div>
                            </form>
                        </div>
                    </div>
                    <br>
                </div>
            </div>
        </div>

</template>

<script setup>
import { router, useForm } from "@inertiajs/vue3";
import {  onMounted, onUnmounted, ref } from "vue";
import { Bootstrap4Pagination } from "laravel-vue-pagination";
import ListHelper from "../../../helpers/ListHelper";
import perPageDropdown from "@/components/PerpageDropdown.vue";
import Datepicker from "../../../components/Datepicker.vue";
import { reactive } from "vue";
import { pickBy } from "lodash";

const props = defineProps({
    safaris: Object,
});

const params = () => new URLSearchParams(window.location.search);

const form = reactive({
    title: params().get("title") || null,
    provider: params().get("provider") || null,
    location: params().get("location") || null,
    price: params().get("price") || null,
    duration: params().get("duration") || null,
    status: params().get("status") || "",
    approval: params().get("approval") || "",
    featured: params().get("featured") || "",
});

onMounted(() => {
    emit.emit("pageName", "Safari/Listing Management", [
        {
            title: "Safari/Listing List",
            routeName: "admin.safari.index",
        },
    ]);

    emit.on("deleteConfirm", function (arg1) {
        deleteConfirm(arg1);
    });

    emit.on("changeStatusConfirm", function (arg1) {
        changeStatusConfirm(arg1);
    });

    emit.on("changeFeaturedStatusConfirm", function (arg1) {
        changeFeaturedStatusConfirm(arg1);
    });
});

onUnmounted(() => {
    emit.off("changeStatusConfirm");
    emit.off("changeFeaturedStatusConfirm");
    emit.off("deleteConfirm");
});

const resetSearch = () => {
    router.visit(route("admin.safari.index"), {
        method: "get",
    });
};

const search = () => {
    form.perPage = params().get("perPage");
    router.visit(route("admin.safari.index"), {
        method: "get",
        data: pickBy(form),
        preserveScroll: true,
    });
};

const changeStatus = (id) => {
    sw.confirm(
        "changeStatusConfirm",
        id,
        "Are you sure?",
        "You want to change the status!",
        "Yes, Change it!"
    );
};

const changeStatusConfirm = (id) => {
    let data = {
        id: id,
    };
    router.post(route("admin.safari.status"), data);
};

const changeFeaturedStatus = (id) => {
    sw.confirm(
        "changeFeaturedStatusConfirm",
        id,
        "Are you sure?",
        "You want to change the featured status!",
        "Yes, Change it!"
    );
};

const changeFeaturedStatusConfirm = (id) => {
    let data = {
        id: id,
    };
    router.post(route("admin.safari.featured"), data);
};


const isOpen = ref(false);
const status = ref("");
const statusForm = useForm({
    status: '',
    id: '',
});

const changeApprovalStatus = (id, currentStatus) => {
    status.value = currentStatus;
    statusForm.status = currentStatus;
    statusForm.id = id;
    openModal();
}

const openModal = () => {
    isOpen.value = true;
};

const closeModal = () => {
    isOpen.value = false;
    statusForm.reset();
};
const submitStatusForm = () => {
    statusForm.post(route('admin.safariApproval'));
    closeModal();
}

</script>
<style scoped>
select.form-control.form-control-sm.form-filter.kt-input {
    width: 100px;
}

/* Modal styles */
.modal {
    display: block;
    position: fixed;
    z-index: 1000;
    left: 0;
    top: 0;
    width: 100%;
    height: 100%;
    overflow: auto;
    background-color: rgba(0, 0, 0, 0.4);
}

.modal-content {
    background-color: #fefefe;
    margin: 15% auto;
    padding: 20px;
    border: 1px solid #888;
    width: 80%;
}

.close {
    color: #aaa;
    float: right;
    font-size: 28px;
    font-weight: bold;
}


</style>