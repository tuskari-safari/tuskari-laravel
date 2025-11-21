<template>
    <div>
        <Head>
            <title>Region Lists</title>
            <meta name="description" content="Region">
        </Head>
        <div class="kt-portlet kt-portlet--mobile">
            <div class="kt-portlet__body">
                <div id="kt_table_1_wrapper" class="dataTables_wrapper dt-bootstrap4 no-footer">
                    <div class="row">
                        <div class="col-sm-12 col-md-6">

                        </div>
                        <div class="col-sm-12 col-md-6">
                            <div id="kt_table_1_filter" class="dataTables_filter">
                                <Link :href="route('admin.region.create')" class="btn btn-brand kt-btn btn-sm kt-btn--icon button-fx"><i class="la la-plus"></i>Add New</Link>
                            </div>
                        </div>
                    </div>
                    <div class="row table-responsive table_fixed_width">
                        <div class="col-sm-12">
                            <table class="table table-striped- table-bordered table-hover table-checkable dataTable no-footer dtr-inline" id="kt_table_1" role="grid" aria-describedby="kt_table_1_info" style="width: 1115px">
                                <thead>
                                    <tr role="row">
                                        <th tabindex="0" aria-controls="kt_table_1" rowspan="1" colspan="1" style="width: 30%" aria-sort="ascending" aria-label="Name: activate to sort column descending">
                                            Name
                                            <i class="fa fa-fw fa-sort pull-right" style="cursor: pointer" @click="ListHelper.sortBy('name')"></i>
                                        </th>
                                        <th tabindex="0" aria-controls="kt_table_1" rowspan="1" colspan="1" style="width: 30%" aria-sort="ascending" aria-label="Image: activate to sort column descending">
                                            Image
                                        </th>
                                        <th tabindex="0" aria-controls="kt_table_1" rowspan="1" colspan="1" style="width: 20%" aria-sort="ascending" aria-label="Status: activate to sort column descending">
                                            Status
                                        </th>
                                        <th class="align-center" rowspan="1" colspan="1" style="width: 20%" aria-label="Actions">
                                            Actions
                                        </th>
                                    </tr>
                                    <tr class="filter">
                                        <th>
                                            <input type="search" v-model="form.name" placeholder="" autocomplete="off" class="form-control-sm form-filter" />
                                        </th>
                                        <th></th>
                                        <th></th>
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
                                    <tr role="row" class="odd" v-for="data in props.regions" :key="data.id">
                                        <td>{{ data.name }}</td>
                                        <td>
                                            <img :src="data.image_url" alt="Region Image" style="width: 50px; height: 50px; object-fit: cover; border-radius: 5px;">
                                        </td>
                                        <td class="align-center">
                                            <span @click="changeStatus(data.id)" style="cursor: pointer" class="kt-badge kt-badge--inline kt-badge--pill cursor-pointer" :class="data.status == 1? 'kt-badge--success': 'kt-badge--warning'">{{ data.status == 1 ? "Active" : "Inactive" }}</span>
                                        </td>
                                        <td nowrap="" class="align-center">
                                            <span class="dropdown">
                                                <a href="javascript:void(0)" class="btn btn-sm btn-clean btn-icon btn-icon-md" data-toggle="dropdown">
                                                    <i class="la la-ellipsis-h"></i>
                                                </a>
                                                <div class="dropdown-menu dropdown-menu-right">
                                                    <Link class="dropdown-item" :href="route('admin.region.edit', data.id)"><i class="la la-edit"></i> Edit</Link>
                                                    <button href="javascript:void(0)" class="dropdown-item" @click="deleteRecode(data.id)">
                                                        <i class="fa fa-trash"></i> Delete
                                                    </button>
                                                </div>
                                            </span>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        <div class="col-sm-12" v-if="regions.length == 0">
                            <div class="no_data text-center">
                                <h3>No data Found</h3>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import { router } from "@inertiajs/vue3";
import { onMounted, onUnmounted, reactive } from "vue";
import ListHelper from "../../../helpers/ListHelper";
import { route } from "ziggy-js";
import { pickBy } from "lodash";

const props = defineProps({
    regions: Object,
});

const params = () => new URLSearchParams(window.location.search);

const form = reactive({
    name: params().get("name") || null,
});

onMounted(() => {
    emit.emit("pageName", "Master Management", [
        {
            title: "Region List",
            routeName: "admin.region.index",
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
    emit.off("deleteConfirm");
    emit.off("changeStatusConfirm");
});

const resetSearch = () => {
    router.visit(route("admin.region.index"), {
        method: "get",
    });
};

const search = () => {
    form.perPage = params().get("perPage");
    router.visit(route("admin.region.index"), {
        method: "get",
        data: pickBy(form),
        preserveScroll: true,
    });
};

const deleteRecode = (id) => {
    sw.confirm("deleteConfirm", id);
};

const deleteConfirm = (id) => {
    router.delete(route("admin.region.destroy", id));
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
    router.post(route("admin.region.status"), data, {
        preserveScroll: true,
    });
};
</script>
<style lang=""></style>