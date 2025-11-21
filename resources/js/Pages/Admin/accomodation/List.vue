<template lang="">
    <div>
        <Head title="Accomodations Lists" />

        <div class="kt-portlet kt-portlet--mobile">
            <div class="kt-portlet__body">
                <div id="kt_table_1_wrapper" class="dataTables_wrapper dt-bootstrap4 no-footer">
                    <div class="row">
                        <div class="col-sm-12 col-md-6">
                            <perPageDropdown />
                        </div>
                        <div class="col-sm-12 col-md-6">
                            <div id="kt_table_1_filter" class="dataTables_filter">
                                <Link :href="route('admin.accomodation.create')" class="btn btn-primary"><i class="la la-plus"></i>Add New</Link>
                            </div>
                        </div>
                    </div>
                    <div class="row table-responsive table_fixed_width">
                        <div class="col-sm-12">
                            <table class="table table-striped- table-bordered table-hover table-checkable dataTable no-footer dtr-inline" id="kt_table_1" role="grid" aria-describedby="kt_table_1_info" style="width: 1115px">
                                <thead>
                                    <tr role="row">
                                        <th tabindex="0" aria-controls="kt_table_1" rowspan="1" colspan="1" style="width: 40%" aria-sort="ascending" aria-label="Agent: activate to sort column descending">
                                            Title
                                            <i class="fa fa-fw fa-sort pull-right" style="cursor: pointer" @click="ListHelper.sortBy('title')"></i>
                                        </th>
                                       
                                        <th tabindex="0" aria-controls="kt_table_1" rowspan="1" colspan="1" style="width: 30%" aria-sort="ascending" aria-label="Agent: activate to sort column descending">
                                            Status
                                        </th>

                                        <th tabindex="0" aria-controls="kt_table_1" rowspan="1" colspan="1" style="width: 10%" aria-sort="ascending" aria-label="Agent: activate to sort column descending">
                                            Action
                                        </th>
                                    </tr>

                                    <tr class="filter">
                                        <th>
                                            <input type="search" v-model="form.title" placeholder="" autocomplete="off" class="form-control-sm form-filter" />
                                        </th>
                                        
                                        <th>
                                            <select class="form-control form-control-sm form-filter kt-input" v-model="form.status" title="Select" data-col-index="2">
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
                                    <tr role="row" class="odd" v-for="accomodation in props.accomodations.data" :key="accomodation.id">
                                        <td>{{ accomodation.title }}</td>
                                        
                                        <td class="align-center">
                                            <span @click="changeStatus(accomodation.id)" style="cursor: pointer" class="kt-badge kt-badge--inline kt-badge--pill cursor-pointer" :class=" accomodation.status == 1? 'kt-badge--success': 'kt-badge--warning'">{{ accomodation.status == 1 ? "Active" : "Inactive" }}</span>
                                        </td>
                                        <td nowrap="" class="align-center">
                                            <span class="dropdown">
                                                <a href="javascript:void(0)" class="btn btn-sm btn-clean btn-icon btn-icon-md" data-toggle="dropdown">
                                                    <i class="la la-ellipsis-h"></i>
                                                </a>
                                                <div class="dropdown-menu dropdown-menu-right">
                                                    <Link class="dropdown-item" :href="route('admin.accomodation.edit', accomodation.id)"><i class="la la-edit"></i> Edit</Link>
                                                    <button href="javascript:void(0)" class="dropdown-item" @click="deleteRecode(accomodation.id)">
                                                        <i class="fa fa-trash"></i> Delete
                                                    </button>
                                                </div>
                                            </span>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        <div class="col-sm-12" v-if="accomodations.total == 0">
                            <div class="no_data text-center">
                                <h3>No data Found</h3>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-12 col-md-5">
                        <div class="dataTables_info" id="kt_table_1_info" role="status" aria-live="polite">
                            Showing {{ accomodations.from }} to {{ accomodations.to }} of
                            {{ accomodations.total }} entries
                        </div>
                    </div>
                    <div class="col-sm-12 col-md-7">
                        <div class="float-right">
                            <Bootstrap4Pagination :data="accomodations" :limit="2" @pagination-change-page="ListHelper.setPageNum" />
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
    accomodations: Object,
});

const params = () => new URLSearchParams(window.location.search);

const form = reactive({
    title: params().get("title") || null,
    status: params().get("status") || "",
});

onMounted(() => {
    emit.emit("pageName", "Master Management", [
        {
            title: "Accomodations List",
            routeName: "admin.accomodations.index",
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
    router.visit(route("admin.accomodations.index"), {
        method: "get",
    });
};

const search = () => {
    form.perPage = params().get("perPage");
    router.visit(route("admin.accomodations.index"), {
        method: "get",
        data: pickBy(form),
        preserveScroll: true,
    });
};

const deleteRecode = (id) => {
    sw.confirm("deleteConfirm", id);
};

const deleteConfirm = (id) => {
    router.delete(route("admin.accomodation.destroy", id));
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
    router.post(route("admin.accomodation.status"), data);
};
</script>
<style lang=""></style>