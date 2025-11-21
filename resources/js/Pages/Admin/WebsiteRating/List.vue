<template>
    <div>
        <Head title="Website Rating List" />
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
                                        </th>
                                        <th class="align-center" tabindex="0" aria-controls="kt_table_1" rowspan="1"
                                            colspan="1" style="width: 15%"
                                            aria-label="Rating: activate to sort column ascending">
                                            Rating
                                        </th>
                                        <th class="align-center" tabindex="0" aria-controls="kt_table_1" rowspan="1"
                                            colspan="1" style="width: 35%"
                                            aria-label="Comment: activate to sort column ascending">
                                            Comment
                                        </th>
                                        <th class="align-center" tabindex="0" aria-controls="kt_table_1" rowspan="1"
                                            colspan="1" style="width: 15%"
                                            aria-label="Date: activate to sort column ascending">
                                            Date
                                        </th>
                                        <th class="align-center" rowspan="1" colspan="1" style="width: 10%"
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
                                            <select class="form-control form-control-sm form-filter kt-input"
                                                v-model="form.rating" title="Select" data-col-index="1">
                                                <option value="">Select Rating</option>
                                                <option value="1">1 Star</option>
                                                <option value="2">2 Stars</option>
                                                <option value="3">3 Stars</option>
                                                <option value="4">4 Stars</option>
                                                <option value="5">5 Stars</option>
                                            </select>
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
                                    <template v-for="rating in websiteRatings?.data" :key="rating?.id">
                                        <tr role="row" class="odd">
                                             <td class="align-center">{{rating.user?.full_name ?? 'Anonymous'}}</td>
                                             <td class="align-center">
                                                <span class="kt-badge kt-badge--inline kt-badge--pill"
                                                    :class="getRatingClass(rating.rating)">
                                                    {{ rating.rating ?? 'N/A' }} ⭐
                                                </span>
                                             </td>
                                             <td class="align-center">
                                                {{ rating.comment ? (rating.comment.length > 50 ? rating.comment.substring(0, 50) + '...' : rating.comment) : 'No comment' }}
                                             </td>
                                             <td class="align-center">{{ formatDate(rating.created_at) }}</td>

                                            <td nowrap="" class="align-center">
                                                <span class="dropdown">
                                                    <a href="javascript:void(0)" class="btn btn-sm btn-clean btn-icon btn-icon-md"
                                                        data-toggle="dropdown">
                                                        <i class="la la-ellipsis-h"></i>
                                                    </a>
                                                    <div class="dropdown-menu dropdown-menu-right">
                                                        <button href="javascript:void(0)" class="dropdown-item"
                                                            @click="deleteRecode(rating.id)">
                                                            <i class="fa fa-trash"></i> Delete
                                                        </button>
                                                    </div>
                                                </span>
                                            </td>
                                        </tr>
                                    </template>

                                    <tr role="row" v-if="websiteRatings?.data?.length == 0" class="odd text-center">
                                        <td colspan="5">No data Found</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="row" v-if="websiteRatings?.data?.length != 0">
                    <div class="col-sm-12 col-md-5">
                        <div class="dataTables_info" id="kt_table_1_info" role="status" aria-live="polite">
                            Showing {{ websiteRatings.from }} to {{ websiteRatings.to }} of {{ websiteRatings.total }} entries
                        </div>
                    </div>
                    <div class="col-sm-12 col-md-7">
                        <div class="float-right">
                            <Bootstrap4Pagination :data="websiteRatings" :limit="2"
                                @pagination-change-page="ListHelper.setPageNum" />
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
<script setup>
import { router } from '@inertiajs/vue3'
import { reactive, onMounted,  onUnmounted } from 'vue'
import { Bootstrap4Pagination } from 'laravel-vue-pagination'
import { pickBy } from 'lodash'
import ListHelper from '@/helpers/ListHelper'
import perPageDropdown from "@/components/PerpageDropdown.vue";



const props = defineProps({websiteRatings: Object, filters: Object })
const params = () => new URLSearchParams(window.location.search);

const form = reactive({
    user_name: params().get("user_name") || null,
    rating: params().get("rating") || "",
});

onMounted(() => {
    emit.emit('pageName', 'Content Management', 
    [{ title: 'Website Rating List', routeName: 'admin.website-rating.list' }])

    emit.on('deleteConfirm', function (arg1) {
        deleteConfirm(arg1)
    })
})

onUnmounted(() => {
    emit.off('deleteConfirm')
})

const resetSearch = () => {
    router.visit(route("admin.website-rating.list"), {
        method: "get",
    });
};

const search = () => {
    form.perPage = params().get("perPage");
    router.visit(route("admin.website-rating.list"), {
        method: "get",
        data: pickBy(form),
        preserveScroll: true,
    });
};

const deleteRecode = id => {
        sw.confirm(
            'deleteConfirm',
            id,
            'You want to delete this website rating!',
            "You won't be able to revert this again!",
        )
}

const deleteConfirm = id => {
    router.delete(route('admin.website-rating.delete', id), {
        preserveScroll: true,
    })
}

const getRatingClass = (rating) => {
    if (rating >= 4) return 'kt-badge--success';
    if (rating >= 3) return 'kt-badge--warning';
    return 'kt-badge--danger';
}

const formatDate = (dateString) => {
    return new Date(dateString).toLocaleDateString('en-US', {
        year: 'numeric',
        month: 'short',
        day: 'numeric'
    });
}
</script>