<template>
    <div>

        <Head title="Operator Review List" />
        <div class="kt-portlet kt-portlet--mobile">
            <div class="kt-portlet__body">
                <div id="kt_table_1_wrapper" class="dataTables_wrapper dt-bootstrap4 no-footer">
                    <div class="row">
                        <div class="col-sm-12 col-md-6">
                            <perPageDropdown />
                        </div>
                        <div class="col-sm-12 col-md-6">
                            <div id="kt_table_1_filter" class="dataTables_filter">
                                <Link :href="route('admin.operator-review.create')" class="btn btn-primary"><i
                                    class="la la-plus"></i>Add New</Link>
                            </div>
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
                                            aria-label="Operator: activate to sort column descending">
                                            Operator
                                            
                                        </th>
                                        <th class="align-center" tabindex="0" aria-controls="kt_table_1" rowspan="1"
                                            colspan="1" style="width: 30%"
                                            aria-label="Review Text: activate to sort column ascending">
                                            Review Text
                                        </th>
                                        <th class="align-center" tabindex="0" aria-controls="kt_table_1" rowspan="1"
                                            colspan="1" style="width: 15%"
                                            aria-label="Source: activate to sort column ascending">
                                            Source
                                        </th>
                                        <th class="align-center" tabindex="0" aria-controls="kt_table_1" rowspan="1"
                                            colspan="1" style="width: 10%"
                                            aria-label="Status: activate to sort column ascending">
                                            Status
                                        </th>
                                        <th class="align-center" rowspan="1" colspan="1" style="width: 20%"
                                            aria-label="Actions">
                                            Actions
                                        </th>
                                    </tr>

                                </thead>
                                <tbody v-auto-animate>
                                    <template v-for="review in reviews?.data" :key="review?.id">
                                        <tr role="row" class="odd">
                                            <td class="align-center"><Link :href="route('admin.vendors', {name:review.operator?.first_name})">{{ review.operator?.business_name
                                                ??review.operator?.full_name }}</Link></td>
                                            <td class="align-center">{{ review.review_text?.substring(0, 100) +
                                                (review.review_text?.length > 100 ? '...' : '') ?? '---'}}</td>
                                            <td class="align-center">{{ review.source ?? '---' }}</td>
                                            <td class="align-center">
                                                <span @click="changeStatus(review.id)" style="cursor: pointer"
                                                    class="kt-badge kt-badge--inline kt-badge--pill cursor-pointer"
                                                    :class="review.status == 1 ? 'kt-badge--success' : 'kt-badge--warning'">{{
                                                        review.status == 1 ?
                                                            'Active' : 'Inactive' }}</span>
                                            </td>

                                            <td nowrap="" class="align-center">
                                                <span class="dropdown">
                                                    <a href="javascript:void(0)"
                                                        class="btn btn-sm btn-clean btn-icon btn-icon-md"
                                                        data-toggle="dropdown">
                                                        <i class="la la-ellipsis-h"></i>
                                                    </a>
                                                    <div class="dropdown-menu dropdown-menu-right">
                                                        <Link class="dropdown-item"
                                                            :href="route('admin.operator-review.edit', review.id)">
                                                        <i class="la la-edit"></i> Edit</Link>
                                                        <button href="javascript:void(0)" class="dropdown-item"
                                                            @click="deleteRecode(review.id)">
                                                            <i class="fa fa-trash"></i> Delete
                                                        </button>
                                                    </div>
                                                </span>
                                            </td>
                                        </tr>
                                    </template>

                                    <tr role="row" v-if="reviews?.data?.length == 0" class="odd text-center">
                                        <td colspan="5">No data Found</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="row" v-if="reviews?.data?.length != 0">
                    <div class="col-sm-12 col-md-5">
                        <div class="dataTables_info" id="kt_table_1_info" role="status" aria-live="polite">
                            Showing {{ reviews.from }} to {{ reviews.to }} of {{ reviews.total }} entries
                        </div>
                    </div>
                    <div class="col-sm-12 col-md-7">
                        <div class="float-right">
                            <Bootstrap4Pagination :data="reviews" :limit="2"
                                @pagination-change-page="ListHelper.setPageNum" />
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import { router, usePage } from '@inertiajs/vue3';
import { watch, reactive, onMounted, computed, ref, onUnmounted } from 'vue';
import { Bootstrap4Pagination } from 'laravel-vue-pagination';
import { debounce, pickBy } from 'lodash';
import ListHelper from '@/helpers/ListHelper';
import perPageDropdown from "@/components/PerpageDropdown.vue";

const props = defineProps({ reviews: Object, operators: Array, filters: Object })
const params = () => new URLSearchParams(window.location.search);

const form = reactive({
    operator_id: params().get("operator_id") || "",
    review_text: params().get("review_text") || null,
    source: params().get("source") || null,
    status: params().get("status") || "",
});

onMounted(() => {
    emit.emit('pageName', 'Content Management',
        [{ title: 'Operator Review List', routeName: 'admin.operator-review.list' }])

    emit.on('deleteConfirm', function (arg1) {
        deleteConfirm(arg1)
    })

    emit.on('changeStatusConfirm', function (arg1) {
        changeStatusConfirm(arg1)
    })
})

onUnmounted(() => {
    emit.off('changeStatusConfirm')
    emit.off('deleteConfirm')
})

const resetSearch = () => {
    router.visit(route("admin.operator-review.list"), {
        method: "get",
    });
};

const search = () => {
    form.perPage = params().get("perPage");
    router.visit(route("admin.operator-review.list"), {
        method: "get",
        data: pickBy(form),
        preserveScroll: true,
    });
};

const changeStatus = id => {
    sw.confirm('changeStatusConfirm', id, 'Are you sure?', 'You want to change the status!', 'Yes, Change it!')
}

const changeStatusConfirm = (id) => {
    let data = {
        id: id,
    };
    router.post(route("admin.operator-review.status"), data);
};

const deleteRecode = id => {
    sw.confirm(
        'deleteConfirm',
        id,
        'You want to delete this review!',
        "You won't be able to revert this again!",
    )
}

const deleteConfirm = id => {
    router.delete(route('admin.operator-review.delete', id), {
        preserveScroll: true,
    })
}
</script>