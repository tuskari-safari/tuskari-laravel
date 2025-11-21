<template lang="">
    <div>
        <Head title="Available tag Lists" />
        <div class="kt-portlet kt-portlet--mobile">
            <div class="kt-portlet__body">
                <div id="kt_table_1_wrapper" class="dataTables_wrapper dt-bootstrap4 no-footer">
                    <div class="row">
                        <div class="col-sm-12 col-md-6"></div>
                        <div class="col-sm-12 col-md-6">
                            <div id="kt_table_1_filter" class="dataTables_filter">
                                <Link :href="route('admin.available-tags.create')" class="btn btn-brand kt-btn btn-sm kt-btn--icon button-fx">
                                    <i class="la la-plus"></i>Add New
                                </Link>
                            </div>
                        </div>
                    </div>
                    <div class="row table-responsive table_fixed_width">
                        <div class="col-sm-12">
                            <table class="table table-striped- table-bordered table-hover table-checkable dataTable no-footer dtr-inline" id="kt_table_1" role="grid" aria-describedby="kt_table_1_info" style="width: 1115px">
                                <thead>
                                    <tr role="row">
                                        <th tabindex="0" aria-controls="kt_table_1" rowspan="1" colspan="1" style="width: 30%" aria-sort="ascending" aria-label="Agent: activate to sort column descending">
                                            Name
                                            <i class="fa fa-fw fa-sort pull-right" style="cursor: pointer" @click="ListHelper.sortBy('name')"></i>
                                        </th>
                                        <th tabindex="0" aria-controls="kt_table_1" rowspan="1" colspan="1" style="width: 20%" aria-sort="ascending" aria-label="Agent: activate to sort column descending">
                                            Show in Frontend
                                        </th>
                                        <th tabindex="0" aria-controls="kt_table_1" rowspan="1" colspan="1" style="width: 20%" aria-sort="ascending" aria-label="Agent: activate to sort column descending">
                                            Safari Available Tags
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
                                        <th>
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
                                    <tr role="row" class="odd" v-for="data in props.tags" :key="data.id">
                                        <td>{{ data.name }}</td>
                                        <td class="align-center">
                                            <span @click="changeStatus(data.id)" style="cursor: pointer" class="kt-badge kt-badge--inline kt-badge--pill cursor-pointer" :class="data.show_in_frontend ? 'kt-badge--success': 'kt-badge--warning'">
                                                {{ data.show_in_frontend ? "SHOW" : "HIDDEN" }}
                                            </span>
                                        </td>
                                        <td class="safari-collection-cell">
                                            <Multiselect placeholder="Select"  v-model="safariSelections[data.id]" mode="tags"
                                            :close-on-select="true" :searchable="true" :create-option="false" 
                                            :options="safaris" />
                                            <button class="btn btn-brand btn-sm" @click="saveSafariCollection(data.id)">
                                                <span>
                                                    <span>Submit</span>
                                                </span>
                                            </button>
                                        </td>
                                        <td nowrap="" class="align-center">
                                            <span class="dropdown">
                                                <a href="javascript:void(0)" class="btn btn-sm btn-clean btn-icon btn-icon-md" data-toggle="dropdown">
                                                    <i class="la la-ellipsis-h"></i>
                                                </a>
                                                <div class="dropdown-menu dropdown-menu-right">
                                                    <Link class="dropdown-item" :href="route('admin.available-tags.edit', data.id)">
                                                        <i class="la la-edit"></i> Edit
                                                    </Link>
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
                        <div class="col-sm-12" v-if="tags.length == 0">
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
import ListHelper from "@/helpers/ListHelper";
import { pickBy } from "lodash";
import Multiselect from '@vueform/multiselect'

const props = defineProps({
    tags: Object,
    safaris: Object
});

const params = () => new URLSearchParams(window.location.search);
const safariSelections = reactive({});

const form = reactive({
    name: params().get("name") || null,
    date: params().get("date") || null,
    safari_ids: [],
});

onMounted(() => {
    emit.emit("pageName", "Available Tag Management", [
        {
            title: "Available Tags List",
            routeName: "admin.available-tags.index",
        },
    ]);

    props.tags.forEach(tag => {
        safariSelections[tag.id] = tag.safaris.map(s => s.id);
    });

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
    router.visit(route("admin.available-tags.index"), {
        method: "get",
    });
};

const search = () => {
    form.perPage = params().get("perPage");
    router.visit(route("admin.available-tags.index"), {
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
    router.post(route("admin.available-tags.status"), data);
};

const deleteRecode = (id) => {
    sw.confirm("deleteConfirm", id);
};

const deleteConfirm = (id) => {
    router.delete(route("admin.available-tags.destroy", id));
};

const saveSafariCollection = (id) => {
    let payload = {
        tag_id: id,
        safari_ids: safariSelections[id] || []
    };
    if (payload.safari_ids.length == 0) {
        toaster.error("Available tag safari is required");
        
    }
    router.post(route("admin.safari-available-tags"), payload);
};

</script>
<style src="@vueform/multiselect/themes/default.css"></style>
<style scoped>
.safari-collection-cell {
    display: flex;
    flex-direction: column;
    gap: 8px;
    align-items: stretch;
}

.safari-collection-cell .multiselect {
    min-height: 38px;
}

.safari-collection-cell .btn {
    align-self: flex-start;
    margin-top: 4px;
    padding: 0.4rem 0.8rem;
    border-radius: 4px;
    font-weight: 500;
    font-size: 0.8rem;
    border: none;
    transition: background-color 0.2s ease;
}

.btn-brand {
    background: #2f3a2f;
    color: white;
}

.btn-brand:hover {
    background: #4a5a4a;
    color: white;
}

.btn-secondary {
    background: #6c757d;
    color: white;
}

.btn-secondary:hover {
    background: #868e96;
    color: white;
}
</style>
