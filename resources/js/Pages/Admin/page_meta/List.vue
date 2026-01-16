<template lang="">
    <div class="kt-portlet kt-portlet--mobile">
        <Head title="Page Meta Lists" />
        <div class="kt-portlet__body">
            <div id="kt_table_1_wrapper" class="dataTables_wrapper dt-bootstrap4 no-footer">
                <div class="row">
                    <div class="col-sm-12 col-md-6">
                        <perPageDropdown />
                    </div>
                    <div class="col-sm-12 col-md-6">
                        <div id="kt_table_1_filter" class="dataTables_filter ">                            
                            <button class="btn btn-primary btn-brand kt-btn kt-btn--icon button-fx mr-3" @click="search">
                                <span>
                                    <i class="la la-search"></i>
                                    <span>Search</span>
                                </span>
                            </button>
                            <button class="btn btn-primary btn-secondary kt-btn kt-btn--icon button-fx mr-3" @click="resetSearch">
                                <span>
                                    <i class="la la-close"></i>
                                    <span>Reset</span>
                                </span>
                            </button>
                            <Link href="/admin/page-meta/create" class="btn btn-primary"><i class="la la-plus"></i>Add New</Link>
                        </div>
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
                                        style="width: 20%" aria-sort="ascending"
                                        aria-label="Page Name: activate to sort column descending">
                                        Page Name
                                        <i class="fa fa-fw fa-sort pull-right" style="cursor: pointer"
                                            @click="ListHelper.sortBy('page_name')"></i>
                                    </th>

                                    <th tabindex="0" aria-controls="kt_table_1" rowspan="1" colspan="1"
                                        style="width: 30%" aria-sort="ascending"
                                        aria-label="Meta Title: activate to sort column descending">
                                        Meta Title
                                        <i class="fa fa-fw fa-sort pull-right" style="cursor: pointer"
                                            @click="ListHelper.sortBy('meta_title')"></i>
                                    </th>

                                    <th tabindex="0" aria-controls="kt_table_1" rowspan="1" colspan="1"
                                        style="width: 30%" aria-label="Meta Description: activate to sort column ascending">
                                        Meta Description
                                    </th>

                                    <th tabindex="0" aria-controls="kt_table_1" rowspan="1" colspan="1"
                                        style="width: 10%" aria-label="Index/Follow: activate to sort column ascending">
                                        Index/Follow
                                    </th>

                                    <th class="align-center" rowspan="1" colspan="1" style="width: 10%"
                                        aria-label="Actions">
                                        Actions
                                    </th>
                                </tr>

                                <tr class="filter">
                                    <th>
                                        <input type="search" v-model="form.page_name" placeholder=""
                                            autocomplete="off" class="form-control-sm form-filter" />
                                    </th>
                                    <th>
                                        <input type="search" v-model="form.meta_title" placeholder=""
                                            autocomplete="off" class="form-control-sm form-filter" />
                                    </th>
                                    <th>
                                        
                                    </th>
                                    <th>
                                        <select class="form-control form-control-sm form-filter kt-input"
                                            v-model="form.index_follow" title="Select" data-col-index="2">
                                            <option value="">Select One</option>
                                            <option value="1">Yes</option>
                                            <option value="0">No</option>
                                        </select>
                                    </th>
                                    <th>
                                        
                                    </th>
                                </tr>
                            </thead>
                            <tbody v-auto-animate>
                                <tr role="row" class="odd" v-for="pageMeta in pageMetas.data" :key="pageMeta.id">
                                    <td class="sorting_1" tabindex="0">
                                        {{ pageMeta.page_name }}
                                    </td>
                                    <td>
                                        {{ pageMeta.meta_title?.length > 50 ? pageMeta.meta_title.substring(0, 50) + '..' : pageMeta.meta_title }}
                                    </td>
                                    <td>
                                        {{ pageMeta.meta_description?.length > 80 ? pageMeta.meta_description.substring(0, 80) + '..' : pageMeta.meta_description }}
                                    </td>
                                    <td>
                                        <span class="kt-badge kt-badge--inline kt-badge--pill"
                                            :class="pageMeta.index_follow ? 'kt-badge--success' : 'kt-badge--warning'">
                                            {{ pageMeta.index_follow ? 'Yes' : 'No' }}
                                        </span>
                                    </td>
                                    <td nowrap="" class="align-center">
                                        <span class="dropdown">
                                            <a href="javascript:void(0)" class="btn btn-sm btn-clean btn-icon btn-icon-md"
                                                data-toggle="dropdown" aria-expanded="true">
                                                <i class="la la-ellipsis-h"></i>
                                            </a>
                                            <div class="dropdown-menu dropdown-menu-right">
                                                <Link class="dropdown-item" :href="`page-meta/${pageMeta.id}/edit`"><i
                                                    class="la la-edit"></i>
                                                Edit</Link>
                                                <button class="dropdown-item"
                                                    @click="deleteRecode(pageMeta.id)">
                                                    <i class="fa fa-trash"></i>
                                                    Delete
                                                </button>
                                            </div>
                                        </span>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <div class="col-sm-12" v-if="pageMetas.total == 0">
                        <div class="no_data text-center">
                            <h3>No Data Found</h3>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-12 col-md-5">
                    <div v-if="pageMetas.total != 0" class="dataTables_info" id="kt_table_1_info" role="status" aria-live="polite">
                        Showing {{ pageMetas.from ?? 0 }} to {{ pageMetas.to ?? 0 }} of
                        {{ pageMetas.total }} entries
                    </div>
                </div>
                <div class="col-sm-12 col-md-7">
                    <div class="float-right">
                        <Bootstrap4Pagination :data="pageMetas" :limit="2"
                            @pagination-change-page="ListHelper.setPageNum" />
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import { Bootstrap4Pagination } from "laravel-vue-pagination";
import { router, useForm } from "@inertiajs/vue3";
import { ref, onMounted, onUnmounted } from "vue";
import ListHelper from "../../../helpers/ListHelper";
import perPageDropdown from "@/components/PerpageDropdown.vue";
import { reactive } from "vue";
import { pickBy } from "lodash";

const props = defineProps({
  pageMetas: Object,
  shortBy: String,
});

const params = () => new URLSearchParams(window.location.search);

const form = reactive({
    page_name: params().get("page_name") || null,
    meta_title: params().get("meta_title") || null,
    index_follow: params().get("index_follow") || "",
});

onMounted(() => {
  emit.emit("pageName", "Content Management", [
    {
      title: "Page Meta List",
      routeName: "admin.page-meta.index",
    },
  ]);

  emit.on("deleteConfirm", function (arg1) {
    deleteConfirm(arg1);
  });
});

onUnmounted(() => {
  emit.off("deleteConfirm");
});

const resetSearch = () => {
  router.visit("/admin/page-meta", {
    method: "get",
  });
};

const search = () => {
    form.perPage = params().get("perPage");
    router.visit(route("admin.page-meta.index"), {
        method: "get",
        data: pickBy(form),
        preserveScroll: true,
    });
};

const deleteRecode = (id) => {
  sw.confirm("deleteConfirm", id);
};

const deleteConfirm = (id) => {
  let data = {
    id: id,
  };
  router.delete(`/admin/page-meta/${id}`);
};
</script>