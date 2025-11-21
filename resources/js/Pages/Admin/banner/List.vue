<template>
    <div class="kt-portlet kt-portlet--mobile">
        <div class="kt-portlet__body">
            <div id="kt_table_1_wrapper" class="dataTables_wrapper dt-bootstrap4 no-footer">
                <div class="row">
                    <div class="col-sm-12 col-md-6">
                        <perPageDropdown />
                    </div>
                    <div class="col-sm-12 col-md-6">
                        <div id="kt_table_1_filter" class="dataTables_filter">
                            <Link :href="route('admin.create_banner')"
                                class="btn btn-brand kt-btn btn-sm kt-btn--icon button-fx"><i class="la la-plus"></i>Add
                            New</Link>
                        </div>
                    </div>
                </div>
                <div class="row table-responsive table_fixed_width">
                    <div class="col-sm-12">
                        <table
                            class="table table-striped- table-bordered table-hover table-checkable dataTable no-footer dtr-inline"
                            id="kt_table_1" role="grid" aria-describedby="kt_table_1_info" style="width: 1115px;">
                            <thead>
                                <tr role="row">
                                    <th tabindex="0" aria-controls="kt_table_1" rowspan="1" colspan="1"
                                        style="width: 20%;" aria-sort="ascending"
                                        aria-label="Agent: activate to sort column descending">Title
                                    </th>
                                    <th tabindex="0" aria-controls="kt_table_1" rowspan="1" colspan="1"
                                        style="width: 15%;" aria-sort="ascending"
                                        aria-label="Agent: activate to sort column descending">Page Name
                                    </th>
                                    <!-- <th tabindex="0" aria-controls="kt_table_1" rowspan="1" colspan="1" style="width:25%;" aria-sort="ascending" aria-label="Agent: activate to sort column descending">Short Description
                                    </th> -->

                                    <th class="align-center" rowspan="1" colspan="1" style="width: 20%;"
                                        aria-label="Actions">Actions</th>
                                </tr>
                                <tr class="filter">
                                    <th>
                                        <input type="search" v-model="form.title" placeholder="" autocomplete="off"
                                            class="form-control-sm form-filter" />
                                    </th>
                                    <th>
                                        <input type="search" v-model="form.page_name" placeholder="" autocomplete="off"
                                            class="form-control-sm form-filter" />
                                    </th>
                                    <th>
                                        <div class="row justify-content-center align-items-center">
                                            <div class="col-md-6">
                                            </div>
                                            <div class="col-md-6">
                                                <button class="mt-2 btn btn-brand kt-btn btn-sm kt-btn--icon button-fx"
                                                    @click="search">
                                                    <span>
                                                        <i class="la la-search"></i>
                                                        <span>Search</span>
                                                    </span>
                                                </button>

                                                <button
                                                    class="ml-2  btn btn-secondary kt-btn btn-sm kt-btn--icon button-fx"
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
                                <tr role="row" class="odd" v-for="banner in banners.data" :key=banner.id>
                                    <td class="sorting_1" tabindex="0">
                                        {{ banner?.title }}
                                    </td>
                                    <td class="sorting_1" tabindex="0">
                                        {{ banner?.page_name }}
                                    </td>
                                  
                                    <td nowrap="" class="align-center">
                                        <span class="dropdown">
                                            <a href="javascript:void(0)" class="btn btn-sm btn-clean btn-icon btn-icon-md"
                                                data-toggle="dropdown" aria-expanded="true">
                                                <i class="la la-ellipsis-h"></i>
                                            </a>
                                            <div class="dropdown-menu dropdown-menu-right">
                                                <Link class="dropdown-item" :href="`edit-banner/${banner.id}`"><i
                                                    class="la la-edit"></i> Edit</Link>
                                               
                                            </div>

                                        </span>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <div class="col-sm-12" v-if="banners.total == 0">
                        <div class="no_data text-center">
                            <h3>No data Found</h3>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-12 col-md-5">
                    <div class="dataTables_info" id="kt_table_1_info" role="status" aria-live="polite">
                        Showing {{ banners.from }} to {{ banners.to }} of {{ banners.total }} entries
                    </div>
                </div>
                <div class="col-sm-12 col-md-7">
                    <div class="float-right">
                        <Bootstrap4Pagination :data="banners" :limit="2"
                            @pagination-change-page="ListHelper.setPageNum" />
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import ListHelper from "../../../helpers/ListHelper";
import { Bootstrap4Pagination } from "laravel-vue-pagination";
import { router } from "@inertiajs/vue3";
import { ref, onMounted, onUnmounted } from "vue";
import perPageDropdown from "@/components/PerpageDropdown.vue";
import { route } from "ziggy-js";
import { reactive } from "vue";
import { pickBy } from "lodash";


const props = defineProps({
    banners: Object,
    shortBy: String,
});
const listData = ref(props.banner);
const params = () => new URLSearchParams(window.location.search);

const form = reactive({
    title: params().get("title") || null,
    page_name: params().get("page_name") || "",
});

onMounted(() => {
    emit.emit("pageName", "Content Management", [
        {
            title: "Banner List",
            routeName: "admin.banner_list",
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

const search = () => {
    form.perPage = params().get("perPage");
    router.visit(route("admin.banner_list"), {
        method: "get",
        data: pickBy(form),
        preserveScroll: true,
    });
};

const resetSearch = () => {
    router.visit(route('admin.banner_list'), {
        method: "get",
    });
};

const deleteRecode = (id) => {
    sw.confirm("deleteConfirm", id);
};

const deleteConfirm = (id) => {
    router.delete(route("admin.banner.delete", { banner_id: id }));

};

</script>