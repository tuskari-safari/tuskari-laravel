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

                        </div>
                        <div class="col-sm-12 col-md-6">
                            <div id="kt_table_1_filter" class="dataTables_filter">
                                <Link :href="route('admin.category.create')" class="btn btn-brand kt-btn btn-sm kt-btn--icon button-fx"><i class="la la-plus"></i>Add New</Link>
                            </div>
                        </div>
                    </div>
                    <div class="row table-responsive table_fixed_width">
                        <div class="col-sm-12">
                            <table class="table table-striped- table-bordered table-hover table-checkable dataTable no-footer dtr-inline" id="kt_table_1" role="grid" aria-describedby="kt_table_1_info" style="width: 1115px">
                                <thead>
                                    <tr role="row">
                                        <th tabindex="0" aria-controls="kt_table_1" rowspan="1" colspan="1" style="width: 30%" aria-sort="ascending" aria-label="Agent: activate to sort column descending">
                                            Title
                                            <i class="fa fa-fw fa-sort pull-right" style="cursor: pointer" @click="ListHelper.sortBy('title')"></i>
                                        </th>
                                        
                                        <th tabindex="0" aria-controls="kt_table_1" rowspan="1" colspan="1" style="width: 20%" aria-sort="ascending" aria-label="Agent: activate to sort column descending">
                                            Created At
                                            <i class="fa fa-fw fa-sort pull-right" style="cursor: pointer" @click="ListHelper.sortBy('created_at')"></i>
                                        </th>
                                        <th class="align-center" tabindex="0" aria-controls="kt_table_1" rowspan="1" colspan="1" style="width: 10%" aria-label="Status: activate to sort column ascending">
                                            Status
                                        </th>
                                        <th class="align-center" rowspan="1" colspan="1" style="width: 20%" aria-label="Actions">
                                            Actions
                                        </th>
                                    </tr>
                                    <tr class="filter">
                                        <th>
                                            <input type="search" v-model="form.title" placeholder="" autocomplete="off" class="form-control-sm form-filter" />
                                        </th>
                                        
                                        <th>
                                            <input type="date" v-model="form.date" placeholder="" autocomplete="off" class="form-control-sm form-filter">
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
                                <Sortable :list="categories" item-key="id" tag="tbody" @end="onchangeDragDrop">
                                    <template #item="{ element, index }">
                                        <tr role="row" class="draggable" :key="index">
                                            <td style='cursor: grab;'>{{ element.title }}</td>
                                         
                                            <td>{{ ListHelper.dateFormat(element.created_at,"MMM DD, YYYY") }}</td>
                                            <td class="align-center">
                                                <span @click="changeStatus(element.id)" style="cursor: pointer" class="kt-badge kt-badge--inline kt-badge--pill cursor-pointer" :class="element.status == 1? 'kt-badge--success': 'kt-badge--warning'">{{ element.status == 1 ? "Active" : "Inactive" }}</span>
                                            </td>
                                            <td nowrap="" class="align-center">
                                                <span class="dropdown">
                                                    <a href="javascript:void(0)" class="btn btn-sm btn-clean btn-icon btn-icon-md" data-toggle="dropdown">
                                                        <i class="la la-ellipsis-h"></i>
                                                    </a>
                                                    <div class="dropdown-menu dropdown-menu-right">
                                                        <Link class="dropdown-item" :href="route('admin.category.edit', element.id)">
                                                        <i class="la la-edit"></i> Edit</Link>
                                                        <button href="javascript:void(0)" class="dropdown-item" @click="deleteRecode(element.id)">
                                                            <i class="fa fa-trash"></i> Delete
                                                        </button>
                                                    </div>
                                                </span>
                                            </td>
                                        </tr>
                                    </template>
</sortable>
</table>
</div>
<div class="col-sm-12" v-if="categories.length == 0">
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
import { Sortable } from "sortablejs-vue3";
import { route } from "ziggy-js";
import { pickBy } from "lodash";

const props = defineProps({
    categories: Object,
});

const params = () => new URLSearchParams(window.location.search);

const form = reactive({
    title: params().get("title") || null,
    date: params().get("date") || null,
    status: params().get("status") || "",
});

onMounted(() => {
    emit.emit("pageName", "Blog Management", [
        {
            title: "Category List",
            routeName: "admin.category.index",
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
    router.visit(route("admin.category.index"), {
        method: "get",
    });
};


const search = () => {
    form.perPage = params().get("perPage");
    router.visit(route("admin.category.index"), {
        method: "get",
        data: pickBy(form),
        preserveScroll: true,
    });
};

const deleteRecode = (id) => {
    sw.confirm("deleteConfirm", id);
};

const deleteConfirm = (id) => {
    router.delete(route("admin.category.destroy", id));
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
    router.post(route("admin.category.status"), data);
};

const moveItemInArray = (array, fromIndex, toIndex) => {
    if (fromIndex === toIndex || fromIndex < 0 || toIndex < 0 || fromIndex >= array.length || toIndex >= array.length) {
        return; // No change if indices are the same or out of bounds
    }
    
    const [item] = array.splice(fromIndex, 1);
    array.splice(toIndex, 0, item);
};

const onchangeDragDrop = async (event) => {
    const { oldIndex, newIndex } = event;

    if (oldIndex !== newIndex) {
        moveItemInArray(props.categories, oldIndex, newIndex);

        try {
            const data = { categories: [...props.categories] }; // Ensure a new reference is sent
            await service.getData(route('admin.category.re-order'), data);
        } catch (error) {
            console.error("Error response:", error.response ? error.response.data : error.message);
        }
    }
};
</script>
<style lang=""></style>