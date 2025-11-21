<template lang="">
    <div class="kt-portlet kt-portlet--mobile">
        <Head title="Faq Lists" />
        <div class="kt-portlet__body">
            <div id="kt_table_1_wrapper" class="dataTables_wrapper dt-bootstrap4 no-footer">
                <div class="row">
                    <div class="col-sm-12 col-md-6">
                        <perPageDropdown />
                    </div>
                    <div class="col-sm-12 col-md-6">
                        <div id="kt_table_1_filter" class="dataTables_filter ">                            
                           
                            <Link href="/admin/create-help-support" class="btn btn-primary"><i class="la la-plus"></i>Add New</Link>
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
                                        style="width: 25%" aria-label="Status: activate to sort column ascending">
                                        Tag
                                    </th>
                                    <th tabindex="0" aria-controls="kt_table_1" rowspan="1" colspan="1"
                                        style="width: 25%" aria-sort="ascending"
                                        aria-label="Agent: activate to sort column descending">
                                        Question
                                        <i class="fa fa-fw fa-sort pull-right" style="cursor: pointer"
                                            @click="
                                                ListHelper.sortBy('question')
                                            "></i>
                                    </th>


                                    <th tabindex="0" aria-controls="kt_table_1" rowspan="1" colspan="1"
                                        style="width: 25%" aria-label="Status: activate to sort column ascending">
                                        Status
                                    </th>

                                    <th class="align-center" rowspan="1" colspan="1" style="width: 25%"
                                        aria-label="Actions">
                                        Actions
                                    </th>
                                </tr>

                                <tr class="filter">
                                     <th>
                                        <input type="search" v-model="form.tag" placeholder=""
                                            autocomplete="off" class="form-control-sm form-filter" />
                                    </th>
                                    <th>
                                        <input type="search" v-model="form.question" placeholder=""
                                            autocomplete="off" class="form-control-sm form-filter" />
                                    </th>
                                   
                                    <th>
                                        <select class="form-control form-control-sm form-filter kt-input"
                                            v-model="form.active" title="Select" data-col-index="2">
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
                                <tr role="row" class="odd" v-for="support in supports.data" :key="support.id">
                                     <td>
                                        {{ support . tag }}
                                    </td>
                                    <td class="sorting_1" tabindex="0">
                                        {{ support . question . length > 100 ? support . question . substring(0, 100) + '..' : support . question }}
                                    </td>
                                   
                                    <td>
                                        <span @click="changeStatus(support.id)" style="cursor: pointer"
                                            class="kt-badge kt-badge--inline kt-badge--pill cursor-pointer"
                                            :class="support.status == 1 ?
                                                'kt-badge--success' :
                                                'kt-badge--warning'">{{ support . status == 1 ? 'Active' : 'Inactive' }}</span>
                                    </td>
                                    <td nowrap="" class="align-center">
                                        <span class="dropdown">
                                            <a href="javascript:void(0)" class="btn btn-sm btn-clean btn-icon btn-icon-md"
                                                data-toggle="dropdown" aria-expanded="true">
                                                <i class="la la-ellipsis-h"></i>
                                            </a>
                                            <div class="dropdown-menu dropdown-menu-right">
                                                <Link class="dropdown-item" :href="`edit-help-support/${support.id}/edit`"><i
                                                    class="la la-edit"></i>
                                                Edit</Link>
                                                <button class="dropdown-item"
                                                    @click="
                                                        deleteRecode(support.id)
                                                    ">
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
                    <div class="col-sm-12" v-if="supports.total == 0">
                        <div class="no_data text-center">
                            <h3>No Data Found</h3>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-12 col-md-5">
                    <div v-if="supports.total != 0" class="dataTables_info" id="kt_table_1_info" role="status" aria-live="polite">
                        Showing {{ supports.from ?? 0 }} to {{ supports.to ?? 0 }} of
                        {{ supports.total }} entries
                    </div>
                </div>
                <div class="col-sm-12 col-md-7">
                    <div class="float-right">
                        <Bootstrap4Pagination :data="supports" :limit="2"
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
import Datepicker from "../../../components/Datepicker.vue";
import ListHelper from "../../../helpers/ListHelper";
import perPageDropdown from "@/components/PerpageDropdown.vue";
import { reactive } from "vue";
import { pickBy } from "lodash";

const props = defineProps({
  supports: Object,
  shortBy: String,
});

const params = () => new URLSearchParams(window.location.search);

const form = reactive({
    question: params().get("question") || null,
    tag: params().get("tag") || null,
    active: params().get("active") || "",
});

onMounted(() => {


  emit.emit("pageName", "Help & Support Management", [
    {
      title: "Help & Support List",
      routeName: "admin.help-support.index",
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
  router.visit("/admin/help-support", {
    method: "get",
  });
};

const search = () => {
    form.perPage = params().get("perPage");
    router.visit(route("admin.help-support.index"), {
        method: "get",
        data: pickBy(form),
        preserveScroll: true,
    });
};

const deleteRecode = (id) => {
    sw.confirm("deleteConfirm", id);
};

const deleteConfirm = (id) => {
    router.delete(route("admin.help-support.destroy", id));
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
    router.post(route("admin.help-support.status"), data);
};
</script>


