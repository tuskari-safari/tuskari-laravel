<template>
    <div>
        <Head title="Report A Problem" />
        <div class="kt-portlet kt-portlet--mobile">
            <div class="kt-portlet__body">
                <div id="kt_table_1_wrapper" class="dataTables_wrapper dt-bootstrap4 no-footer">
                    <div class="row">
                        <div class="col-sm-12 col-md-6">
                            <perPageDropdown />
                        </div>
                    </div>
                    <div class="row table-responsive">
                        <div class="col-sm-12">
                            <table
                                class="table table-striped- table-bordered table-hover table-checkable dataTable no-footer dtr-inline"
                                id="kt_table_1" role="grid" aria-describedby="kt_table_1_info" style="width: 1115px">
                                <thead>
                                    <tr role="row">
                                        <th tabindex="0" aria-controls="kt_table_1" rowspan="1" colspan="1"
                                            style="width:25%" aria-sort="ascending"
                                            aria-label="User: activate to sort column descending">
                                          User Name
                                            <i class="fa fa-fw fa-sort pull-right" style="cursor: pointer"
                                                @click="ListHelper.sortBy('user_name')"></i>
                                        </th>
                                        <th class="align-center" tabindex="0" aria-controls="kt_table_1" rowspan="1"
                                            colspan="1" style="width: 20%"
                                            aria-label="Issue Type: activate to sort column ascending">
                                            Issue Type
                                        </th>
                                        <th class="align-center" tabindex="0" aria-controls="kt_table_1" rowspan="1"
                                            colspan="1" style="width: 25%"
                                            aria-label="Description: activate to sort column ascending">
                                            Description
                                        </th>
                                        <th class="align-center" tabindex="0" aria-controls="kt_table_1" rowspan="1"
                                            colspan="1" style="width: 12%"
                                            aria-label="Safari: activate to sort column ascending">
                                            Safari
                                        </th>
                                        <th class="align-center" tabindex="0" aria-controls="kt_table_1" rowspan="1"
                                            colspan="1" style="width: 13%"
                                            aria-label="Status: activate to sort column ascending">
                                            Status
                                        </th>
                                        <th class="align-center" rowspan="1" colspan="1" style="width: 15%"
                                            aria-label="Actions">
                                            Actions
                                        </th>
                                    </tr>

                                    <tr class="filter">
                                        <th>
                                            <input type="search" v-model="form.user_name" placeholder="Search..." autocomplete="off"
                                                class="form-control-sm form-filter" />
                                        </th>
                                        <th>
                                            <input type="search" v-model="form.issue_type" placeholder="Search..." autocomplete="off"
                                                class="form-control-sm form-filter" />
                                        </th>
                                        <th></th>
                                        <th></th>
                                        <th>
                                            <select class="form-control form-control-sm form-filter kt-input"
                                                v-model="form.resolve" title="Select" data-col-index="4">
                                                <option value="">Select One</option>
                                                <option value="1">Resolved</option>
                                                <option value="0">Pending</option>
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
                                    <template v-for="report in reports?.data" :key="report?.id">
                                        <tr role="row" class="odd">
                                             <td class="align-center">{{report.user?.full_name ?? 'NA'}}</td>
                                             <td class="align-center">{{report.issue_type ?? '---'}}</td>
                                             <td class="align-center">{{report.description ? report.description.substring(0, 40) + '...' : '---'}}</td>
                                             <td class="align-center">{{report.safari?.title ?? '---'}}</td>
                                            <td class="align-center">
                                                <span @click="changeStatus(report.id)" style="cursor: pointer"
                                                    class="kt-badge kt-badge--inline kt-badge--pill cursor-pointer"
                                                    :class="report.resolve == 1 ? 'kt-badge--success' : 'kt-badge--warning'">{{
                                                        report.resolve == 1 ?
                                                        'Resolved' : 'Pending' }}</span>
                                            </td>

                                            <td nowrap="" class="align-center">
                                                <span class="dropdown">
                                                    <a href="javascript:void(0)" class="btn btn-sm btn-clean btn-icon btn-icon-md"
                                                        data-toggle="dropdown">
                                                        <i class="la la-ellipsis-h"></i>
                                                    </a>
                                                    <div class="dropdown-menu dropdown-menu-right">
                                                        <Link class="dropdown-item" :href="route('admin.report-issue.view', report.id)"><i class="la la-eye"></i> View</Link>
                                                    </div>
                                                </span>
                                            </td>
                                        </tr>
                                    </template>

                                    <tr role="row" v-if="reports?.data?.length == 0" class="odd text-center">
                                        <td colspan="6">No data Found</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="row" v-if="reports?.data?.length != 0">
                    <div class="col-sm-12 col-md-5">
                        <div class="dataTables_info" id="kt_table_1_info" role="status" aria-live="polite">
                            Showing {{ reports.from }} to {{ reports.to }} of {{ reports.total }} entries
                        </div>
                    </div>
                    <div class="col-sm-12 col-md-7">
                        <div class="float-right">
                            <Bootstrap4Pagination :data="reports" :limit="2"
                                @pagination-change-page="ListHelper.setPageNum" />
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
    
    
<script setup>
import { router, usePage } from '@inertiajs/vue3'
import { watch, reactive, onMounted, computed, ref, onUnmounted } from 'vue'
import { Bootstrap4Pagination } from 'laravel-vue-pagination'
import { debounce, pickBy } from 'lodash'
import ListHelper from '@/helpers/ListHelper'
import perPageDropdown from "@/components/PerpageDropdown.vue";



const props = defineProps({reports: Object, filters: Object })
const params = () => new URLSearchParams(window.location.search);

const form = reactive({
    user_name: params().get("user_name") || null,
    issue_type: params().get("issue_type") || null,
    resolve: params().get("resolve") || "",
});

onMounted(() => {
    emit.emit('pageName', 'Customer Support Management', 
    [{ title: 'Report A Problem', routeName: 'admin.report-issue.index' }])

    emit.on('changeStatusConfirm', function (arg1) {
        changeStatusConfirm(arg1)
    })
})

onUnmounted(() => {
    emit.off('changeStatusConfirm')
})

const changeStatus = id => {
    sw.confirm('changeStatusConfirm', id, 'Are you sure?', 'You want to change the status!', 'Yes, Change it!')
}

const changeStatusConfirm = (id) => {
    let data = {
        id: id,
    };
    router.post(route("admin.report-issue.status"), data);
};

const resetSearch = () => {
    router.visit(route("admin.report-issue.index"), {
        method: "get",
    });
};

const search = () => {
    form.perPage = params().get("perPage");
    router.visit(route("admin.report-issue.index"), {
        method: "get",
        data: pickBy(form),
        preserveScroll: true,
    });
};
</script>