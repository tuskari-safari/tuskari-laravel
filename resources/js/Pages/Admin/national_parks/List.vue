<template lang="">
    <div>
        <Head title="National Park & Private Reserves Lists" />

        <div class="kt-portlet kt-portlet--mobile">
            <div class="kt-portlet__body">
                <div id="kt_table_1_wrapper" class="dataTables_wrapper dt-bootstrap4 no-footer">
                    <div class="row">
                        <div class="col-sm-12 col-md-6">
                            <perPageDropdown />
                        </div>
                        <div class="col-sm-12 col-md-6">
                            <div id="kt_table_1_filter" class="dataTables_filter">
                                <Link :href="route('admin.national-park-reserves.create')" class="btn btn-primary"><i class="la la-plus"></i>Add New </Link>
                            </div>
                        </div>
                    </div>
                    <div class="row table-responsive table_fixed_width">
                        <div class="col-sm-12">
                            <table class="table table-striped- table-bordered table-hover table-checkable dataTable no-footer dtr-inline" id="kt_table_1" role="grid" aria-describedby="kt_table_1_info" style="width: 1115px">
                                <thead>
                                    <tr role="row">
                                        <th tabindex="0" aria-controls="kt_table_1" rowspan="1" colspan="1" style="width: 40%" aria-sort="ascending" aria-label="Agent: activate to sort column descending">
                                            Name
                                            <i class="fa fa-fw fa-sort pull-right" style="cursor: pointer" @click="ListHelper.sortBy('name')"></i>
                                        </th>
                                        <!-- <th tabindex="0" aria-controls="kt_table_1" rowspan="1" colspan="1" style="width: 40%" aria-sort="ascending" aria-label="Agent: activate to sort column descending">
                                            Title
                                            <i class="fa fa-fw fa-sort pull-right" style="cursor: pointer" @click="ListHelper.sortBy('title')"></i>
                                        </th> -->
                                        <th tabindex="0" aria-controls="kt_table_1" rowspan="1" colspan="1" style="width: 20%" aria-sort="ascending" aria-label="Agent: activate to sort column descending">
                                           Type
                                            <i class="fa fa-fw fa-sort pull-right" style="cursor: pointer" @click="ListHelper.sortBy('type')"></i>
                                        </th>
                                        <th tabindex="0" aria-controls="kt_table_1" rowspan="1" colspan="1" style="width: 15%" aria-sort="ascending" aria-label="Agent: activate to sort column descending">
                                            Status
                                        </th>
                                        <th tabindex="0" aria-controls="kt_table_1" rowspan="1" colspan="1" style="width: 15%" aria-sort="ascending" aria-label="Agent: activate to sort column descending">
                                            Visibility
                                        </th>

                                        <th tabindex="0" aria-controls="kt_table_1" rowspan="1" colspan="1" style="width: 10%" aria-sort="ascending" aria-label="Agent: activate to sort column descending">
                                            Action
                                        </th>
                                    </tr>

                                    <tr class="filter">
                                        <th>
                                            <input type="search" v-model="form.name" placeholder="" autocomplete="off" class="form-control-sm form-filter" />
                                        </th>
                                        <!-- <th>
                                            <input type="search" v-model="form.title" placeholder="" autocomplete="off" class="form-control-sm form-filter" />
                                        </th> -->
                                        <th>
                                            <select class="form-control form-control-sm form-filter kt-input" v-model="form.type" title="Select" data-col-index="2">
                                                <option value="">Select One</option>
                                                <option value="national_park">National Park</option>
                                                <option value="private_reserve">Private Reserves</option>
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
                                            <select class="form-control form-control-sm form-filter kt-input" v-model="form.is_hidden" title="Select" data-col-index="3">
                                                <option value="">Select One</option>
                                                <option value="0">Show</option>
                                                <option value="1">Hide</option>
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
                                    <tr role="row" class="odd" v-for="park in props.parksReserves.data" :key="park.id">

                                        <td>{{ park.name ?? '-' }}</td>
                                        <!-- <td>{{ park.title ?? '-' }}</td> -->
                                          <td>
                                            {{ park.type == 'national_park' ? "National Park" : "Private Reserves" }}
                                        </td>
                                        <td class="align-center">
                                            <span @click="changeStatus(park.id)" style="cursor: pointer" class="kt-badge kt-badge--inline kt-badge--pill cursor-pointer" :class=" park.status == 1? 'kt-badge--success': 'kt-badge--warning'">{{ park.status == 1 ? "Active" : "Inactive" }}</span>
                                        </td>
                                        <td class="align-center">
                                            <span @click="changeHiddenStatus(park.id)" style="cursor: pointer" class="kt-badge kt-badge--inline kt-badge--pill cursor-pointer" :class=" park.is_hidden == 0? 'kt-badge--success': 'kt-badge--danger'">{{ park.is_hidden == 0 ? "Show" : "Hide" }}</span>
                                        </td>
                                      
                                        <td nowrap="" class="align-center">
                                            <span class="dropdown">
                                                <a href="javascript:void(0)" class="btn btn-sm btn-clean btn-icon btn-icon-md" data-toggle="dropdown">
                                                    <i class="la la-ellipsis-h"></i>
                                                </a>
                                                <div class="dropdown-menu dropdown-menu-right">
                                                    <Link class="dropdown-item" :href="route('admin.national-park-reserves.edit', park.id)"><i class="la la-edit"></i> Edit</Link>
                                                    <button href="javascript:void(0)" class="dropdown-item" @click="deleteRecode(park.id)">
                                                        <i class="fa fa-trash"></i> Delete
                                                    </button>
                                                </div>
                                            </span>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        <div class="col-sm-12" v-if="parksReserves.total == 0">
                            <div class="no_data text-center">
                                <h3>No data Found</h3>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-12 col-md-5">
                        <div class="dataTables_info" id="kt_table_1_info" role="status" aria-live="polite">
                            Showing {{ parksReserves.from }} to {{ parksReserves.to }} of
                            {{ parksReserves.total }} entries
                        </div>
                    </div>
                    <div class="col-sm-12 col-md-7">
                        <div class="float-right">
                            <Bootstrap4Pagination :data="parksReserves" :limit="2" @pagination-change-page="ListHelper.setPageNum" />
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import { router } from "@inertiajs/vue3";
import {  onMounted, onUnmounted } from "vue";
import { Bootstrap4Pagination } from "laravel-vue-pagination";
import ListHelper from "../../../helpers/ListHelper";
import perPageDropdown from "@/components/PerpageDropdown.vue";
import Datepicker from "../../../components/Datepicker.vue";
import { reactive } from "vue";
import { pickBy } from "lodash";

const props = defineProps({
    parksReserves: Object,
});

const params = () => new URLSearchParams(window.location.search);

const form = reactive({
    // title: params().get("title") || null,
    name: params().get("name") || null,
    type: params().get("type") || "",
    status: params().get("status") || "",
    is_hidden: params().get("is_hidden") || "",
});

onMounted(() => {
    emit.emit("pageName", "National Park & Private Reserves Management", [
        {
            title: "National Park & Private Reserves List",
            routeName: "admin.national-park-reserves.index",
        },
    ]);

    emit.on("deleteConfirm", function (arg1) {
        deleteConfirm(arg1);
    });

    emit.on("changeStatusConfirm", function (arg1) {
        changeStatusConfirm(arg1);
    });

    emit.on("changeHiddenStatusConfirm", function (arg1) {
        changeHiddenStatusConfirm(arg1);
    });
});

onUnmounted(() => {
    emit.off("changeStatusConfirm");
    emit.off("changeHiddenStatusConfirm");
    emit.off("deleteConfirm");
});

const resetSearch = () => {
    router.visit(route("admin.national-park-reserves.index"), {
        method: "get",
    });
};

const search = () => {
    form.perPage = params().get("perPage");
    router.visit(route("admin.national-park-reserves.index"), {
        method: "get",
        data: pickBy(form),
        preserveScroll: true,
    });
};

const deleteRecode = (id) => {
    sw.confirm("deleteConfirm", id);
};

const deleteConfirm = (id) => {
    router.delete(route("admin.national-park-reserves.destroy", id));
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
    router.post(route("admin.national-park-reserves.status"), data);
};

const changeHiddenStatus = (id) => {
    sw.confirm(
        "changeHiddenStatusConfirm",
        id,
        "Are you sure?",
        "You want to change the visibility status!",
        "Yes, Change it!"
    );
};

const changeHiddenStatusConfirm = (id) => {
    let data = {
        id: id,
    };
    router.post(route("admin.national-park-reserves.hidden-status"), data);
};
</script>
<style lang=""></style>