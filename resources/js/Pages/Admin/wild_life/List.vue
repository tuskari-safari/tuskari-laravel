<template lang="">
    <div>
        <Head>
            <title>Category Lists</title>
            <meta name="description" content="Category">
        </Head>
        <div class="kt-portlet kt-portlet--mobile">
            <div class="kt-portlet__body">
                <div id="kt_table_1_wrapper" class="dataTables_wrapper dt-bootstrap4 no-footer">
                    <div class="row">
                         <div class="col-sm-12 col-md-6">
                            <perPageDropdown />
                        </div>
                        <div class="col-sm-12 col-md-6">
                            <div id="kt_table_1_filter" class="dataTables_filter">
                                <Link :href="route('admin.wild-life.create')" class="btn btn-brand kt-btn btn-sm kt-btn--icon button-fx"><i class="la la-plus"></i>Add New</Link>
                            </div>
                        </div>
                    </div>
                    <div class="row table-responsive table_fixed_width">
                        <div class="col-sm-12">
                            <table class="table table-striped- table-bordered table-hover table-checkable dataTable no-footer dtr-inline" id="kt_table_1" role="grid" aria-describedby="kt_table_1_info" style="width: 1115px">
                                <thead>
                                    <tr role="row">
                                        <th tabindex="0" aria-controls="kt_table_1" rowspan="1" colspan="1" style="width: 15%" aria-label="Logo">
                                            Logo
                                        </th>
                                        <th tabindex="0" aria-controls="kt_table_1" rowspan="1" colspan="1" style="width: 25%" aria-sort="ascending" aria-label="Agent: activate to sort column descending">
                                            Name
                                            <i class="fa fa-fw fa-sort pull-right" style="cursor: pointer" @click="ListHelper.sortBy('title')"></i>
                                        </th>
                                        
                                        <th tabindex="0" aria-controls="kt_table_1" rowspan="1" colspan="1" style="width: 20%" aria-sort="ascending" aria-label="Agent: activate to sort column descending">
                                            Created At
                                            <i class="fa fa-fw fa-sort pull-right" style="cursor: pointer" @click="ListHelper.sortBy('created_at')"></i>
                                        </th>
                                      
                                        <th class="align-center" rowspan="1" colspan="1" style="width: 20%" aria-label="Actions">
                                            Actions
                                        </th>
                                    </tr>
                                    <tr class="filter">
                                        <th></th>
                                        <th>
                                            <input type="search" v-model="form.name" placeholder="" autocomplete="off" class="form-control-sm form-filter" />
                                        </th>
                                        
                                        <th>
                                            <input type="date" v-model="form.date" placeholder="" autocomplete="off" class="form-control-sm form-filter">
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
                                    <tr role="row" class="odd" v-for="data in props.wildLives.data" :key="data.id">
                                        <td class="align-center">
                                            <div class="wildlife-logo">
                                                <img v-if="data?.full_thumbnail_url" 
                                                     :src="data.full_thumbnail_url" 
                                                     :alt="data.name" 
                                                     class="wildlife-thumbnail" />
                                                <div v-else class="no-image">
                                                    <i class="la la-image"></i>
                                                </div>
                                            </div>
                                        </td>
                                        <td>{{ data?.name }}</td>
                                        <td>{{ ListHelper.dateFormat(data?.created_at,"MMM DD, YYYY") }}</td>
                                      
                                        <td nowrap="" class="align-center">
                                            <span class="dropdown">
                                                <a href="javascript:void(0)" class="btn btn-sm btn-clean btn-icon btn-icon-md" data-toggle="dropdown">
                                                    <i class="la la-ellipsis-h"></i>
                                                </a>
                                                <div class="dropdown-menu dropdown-menu-right">
                                                    <Link class="dropdown-item" :href="route('admin.wild-life.edit', data.id)"><i class="la la-edit"></i> Edit</Link>
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
                        <div class="col-sm-12" v-if="wildLives.length == 0">
                            <div class="no_data text-center">
                                <h3>No data Found</h3>
                            </div>
                        </div>
                    </div>
                </div>
                   <div class="row">
                    <div class="col-sm-12 col-md-5">
                        <div class="dataTables_info" id="kt_table_1_info" role="status" aria-live="polite">
                            Showing {{ wildLives.from }} to {{ wildLives.to }} of
                            {{ wildLives.total }} entries
                        </div>
                    </div>
                    <div class="col-sm-12 col-md-7">
                        <div class="float-right">
                            <Bootstrap4Pagination :data="wildLives" :limit="2" @pagination-change-page="ListHelper.setPageNum" />
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
import { Sortable } from "sortablejs-vue3";
import perPageDropdown from "@/components/PerpageDropdown.vue";
import { Bootstrap4Pagination } from "laravel-vue-pagination";
import { route } from "ziggy-js";
import { pickBy } from "lodash";

const props = defineProps({
    wildLives: Object,
});

const params = () => new URLSearchParams(window.location.search);

const form = reactive({
    name: params().get("name") || null,
    date: params().get("date") || null,
    status: params().get("status") || "",
});

onMounted(() => {
    emit.emit("pageName", "Master Management", [
        {
            title: "Wild life List",
            routeName: "admin.wild-lives",
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
    router.visit(route("admin.wild-lives"), {
        method: "get",
    });
};


const search = () => {
    form.perPage = params().get("perPage");
    router.visit(route("admin.wild-lives"), {
        method: "get",
        data: pickBy(form),
        preserveScroll: true,
    });
};

const deleteRecode = (id) => {
    sw.confirm("deleteConfirm", id);
};

const deleteConfirm = (id) => {
    router.delete(route("admin.wild-life.destroy", id));
};

</script>
<style scoped>
.wildlife-logo {
    display: flex;
    justify-content: center;
    align-items: center;
}

.wildlife-thumbnail {
    width: 100px;
    height: 100px;
    border-radius: 8px;
    transition: transform 0.3s ease;
}

.wildlife-thumbnail:hover {
    transform: scale(1.1);
}

.no-image {
    width: 50px;
    height: 50px;
    background: #f8f9fa;
    border-radius: 8px;
    display: flex;
    align-items: center;
    justify-content: center;
    color: #6c757d;
    font-size: 20px;
    border: 2px dashed #dee2e6;
}
</style>