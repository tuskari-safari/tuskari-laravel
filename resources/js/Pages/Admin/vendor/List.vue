<template lang="">
    <div>
        <Head title="User Lists" />
        <div class="kt-portlet kt-portlet--mobile">
            <div class="kt-portlet__body">
                <div id="kt_table_1_wrapper" class="dataTables_wrapper dt-bootstrap4 no-footer">
                    <div class="row">
                        <div class="col-sm-12 col-md-6">
                            <perPageDropdown />
                        </div>
                        <div class="col-sm-12 col-md-6">
                        </div>
                    </div>
                    <div class="row table-responsive table_fixed_width">
                        <div class="col-sm-12">
                            <table class="table table-striped- table-bordered table-hover table-checkable dataTable no-footer dtr-inline" id="kt_table_1" role="grid" aria-describedby="kt_table_1_info" style="width: 1115px">
                                <thead>
                                    <tr role="row">
                                        <th tabindex="0" aria-controls="kt_table_1" rowspan="1" colspan="1" style="width: 30%" aria-sort="ascending" aria-label="Agent: activate to sort column descending">
                                            Name
                                            <i class="fa fa-fw fa-sort pull-right" style="cursor: pointer" @click="ListHelper.sortBy('first_name')"></i>
                                        </th>
                                        <th tabindex="0" aria-controls="kt_table_1" rowspan="1" colspan="1" style="width: 15%" aria-label="Company Email: activate to sort column ascending">
                                            Email
                                            <i class="fa fa-fw fa-sort pull-right" style="cursor: pointer" @click="ListHelper.sortBy('email')"></i>
                                        </th>
                                        <th tabindex="0" aria-controls="kt_table_1" rowspan="1" colspan="1" style="width: 15%" aria-label="Company Agent: activate to sort column ascending">
                                            Phone
                                            <i class="fa fa-fw fa-sort pull-right" style="cursor: pointer" @click="ListHelper.sortBy('phone')"></i>
                                        </th>
                                        <th class="align-center" tabindex="0" aria-controls="kt_table_1" rowspan="1" colspan="1" style="width: 25%" aria-label="Status: activate to sort column ascending">
                                            Is Approved
                                        </th>
                                         <th class="align-center" tabindex="0" aria-controls="kt_table_1" rowspan="1" colspan="1" style="width: 25%" aria-label="Status: activate to sort column ascending">
                                            Status
                                        </th>
                                        <th class="align-center" rowspan="1" colspan="1" style="width: 15%" aria-label="Actions">
                                            Actions
                                        </th>
                                    </tr>

                                    <tr class="filter">
                                        <th>
                                            <input type="search" v-model="form.name" placeholder="" autocomplete="off" class="form-control-sm form-filter" />
                                        </th>
                                        <th>
                                            <input type="search" v-model="form.email" placeholder="" autocomplete="off" class="form-control-sm form-filter" />
                                        </th>
                                        <th>
                                            <input type="search" v-model="form.phone" placeholder="" autocomplete="off" class="form-control-sm form-filter" />
                                        </th>
                                        <th>
                                            <select class="form-control form-control-sm form-filter kt-input" v-model="form.is_approved" title="Select" data-col-index="2">
                                                <option value="">Select One</option>
                                                <option value="0">Pending</option>
                                                <option value="1">Approved</option>
                                                <option value="2">Rejected</option>
                                            </select>
                                        </th>
                                         <th>
                                            <select class="form-control form-control-sm form-filter kt-input" v-model="form.active" title="Select" data-col-index="2">
                                                <option value="">Select One</option>
                                                <option value="1">Active</option>
                                                <option value="0">Inactive</option>
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
                                    <tr role="row" class="odd" v-for="user in props.users.data" :key="user.id">
                                        <td class="sorting_1" tabindex="0">
                                            <div class="kt-user-card-v2">
                                                <div class="kt-user-card-v2__pic">
                                                    <div class="kt-badge kt-badge--xl kt-badge--success" :class="ListHelper.getRandomVal()">
                                                        <span>{{ user.full_name.substr(0, 1) }}</span>
                                                    </div>
                                                </div>
                                                <div class="kt-user-card-v2__details">
                                                    <span class="kt-user-card-v2__name">{{user.full_name}}</span>
                                                    <a href="javascript:void(0)" class="kt-user-card-v2__email kt-link">Member since
                                                        {{ ListHelper.dateFormat(user.created_at,"MMM DD, YYYY hh:mm A")}}</a>
                                                </div>
                                            </div>
                                        </td>
                                        <td>{{ user.email }}</td>
                                        <td>{{ user.phone }}</td>
                                        <td class="align-center">
                                           
                                            <span class="kt-badge kt-badge--inline kt-badge--pill"
                                            :class="user.is_approved == 1 ? 'kt-badge--success': (user.is_approved == 2 ? 'kt-badge--warning' : 'kt-badge--info')">
                                            {{user?.is_approved == 1 ? "Approved" :
                                            (user?.is_approved == 2) ? "Rejected" :
                                            'Pending' }}
                                            </span>

                                        </td>
                                        <td class="align-center">
                                            <span @click="changeStatus(user.id)" style="cursor: pointer" class="kt-badge kt-badge--inline kt-badge--pill cursor-pointer" :class="user.active == 1? 'kt-badge--success': 'kt-badge--warning'">{{ user.active == 1 ? "Active" : "Inactive" }}</span>
                                        </td>
                                        <td nowrap="" class="align-center">
                                            <span class="dropdown">
                                                <a href="javascript:void(0)" class="btn btn-sm btn-clean btn-icon btn-icon-md" data-toggle="dropdown">
                                                    <i class="la la-ellipsis-h"></i>
                                                </a>
                                                <div class="dropdown-menu dropdown-menu-right">
                                                    <Link class="dropdown-item" :href="route('admin.editVendor', user.id)"><i class="la la-edit"></i> Edit</Link>
                                                    <Link class="dropdown-item" :href="route('admin.vendorApproval', {id: user.id})"><i class="la la-check-circle"></i> Vendor Approval</Link>
                                                    <Link class="dropdown-item" :href="route('admin.safari.index', {provider: user.first_name})"><i class="la la-binoculars"></i> View Safari</Link>
                                                    <button href="javascript:void(0)" class="dropdown-item" @click="deleteRecode(user.id)">
                                                        <i class="fa fa-trash"></i> Delete
                                                    </button>
                                                </div>
                                            </span>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        <div class="col-sm-12" v-if="users.total == 0">
                            <div class="no_data text-center">
                                <h3>No data Found</h3>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-12 col-md-5">
                        <div class="dataTables_info" id="kt_table_1_info" role="status" aria-live="polite">
                            Showing {{ users.from }} to {{ users.to }} of
                            {{ users.total }} entries
                        </div>
                    </div>
                    <div class="col-sm-12 col-md-7">
                        <div class="float-right">
                            <Bootstrap4Pagination :data="users" :limit="2" @pagination-change-page="ListHelper.setPageNum" />
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
import { reactive } from "vue";
import { pickBy } from "lodash";

const props = defineProps({
    users: Object,
});

const params = () => new URLSearchParams(window.location.search);

const form = reactive({
    name: params().get("name") || null,
    email: params().get("email") || null,
    phone: params().get("phone") || null,
    is_approved: params().get("is_approved") || "",
    active: params().get("active") || "",

});

onMounted(() => {
    emit.emit("pageName", "User Management", [
        {
            title: "Vendor List",
            routeName: "admin.vendors",
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
    router.visit(route("admin.vendors"), {
        method: "get",
    });
};

const search = () => {
    form.perPage = params().get("perPage");
    router.visit(route("admin.vendors"), {
        method: "get",
        data: pickBy(form),
        preserveScroll: true,
    });
};

const deleteRecode = (id) => {
    sw.confirm("deleteConfirm", id);
};

const deleteConfirm = (id) => {
    router.delete(route("admin.deleteVendor", id));
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
    router.post(route("admin.changeVendorStatus"), data);
};
</script>
<style lang=""></style>