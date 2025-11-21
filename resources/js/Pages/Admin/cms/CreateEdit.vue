<template lang="">
   <div>
        <Head title="Create Cms" v-if="!props.cms" />
        <Head title="Edit Cms" v-if="props.cms" />

        <div class="kt-portlet kt-portlet--mobile">
            <div class="kt-portlet__body">
                <form @submit.prevent="submit">
                    <div class="form-group validated row">
                        <div class="form-group col-lg-12">
                            <label for="title">Title <span class="text-danger">*</span></label>
                            <input type="text" id="title" v-model="form.title" class="form-control border-gray-200" placeholder="Title">
                            <span class="text-danger" v-if="form.errors.title">{{ form.errors.title }}</span>
                        </div>

                        <div class="form-group col-lg-12">
                            <label for="content">Content</label>
                            <ckeditor v-model="form.content" ></ckeditor>

                            <span class="text-danger" v-if="form.errors.content">{{ form.errors.content }}</span>
                        </div>
                    </div>
                    <div class="kt-portlet__foot">
                        <div class="kt-form__actions">
                            <div class="row">
                                <div class="mr-2">
                                    <submit-button :disabled="form.processing" :isLoading="form.processing">Submit</submit-button>
                                </div>
                                <div>
                                    <Link href="/admin/cms" class="btn btn-info">Cancel</Link>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
   </div>
</template>
<script setup>
import { onMounted, reactive, ref } from "vue";
import { useForm, router } from "@inertiajs/vue3";
import { component as ckeditor } from "@mayasabha/ckeditor4-vue3";
import Datepicker from "../../../components/Datepicker.vue";
import SubmitButton from "../../../components/SubmitButton.vue";

const props = defineProps({
  errors: Object,
  cms: Object,
});
const form = useForm({
  title: props.cms?.title || null,
  content: props.cms?.text_content || null,
});

onMounted(() => {
  emit.emit("pageName", "Content Management", [
    {
      title: "CMS",
      routeName: "admin.cms.index",
    },
    {
      title: "Edit CMS",
      routeName: "",
    },
  ]);
});

function submit() {
  if (props.cms) {
    form.post("/admin/cms/" + props.cms.id);
  } else {
    form.post("/admin/cms");
  }
}
</script>

<style>
.ck-editor__editable {
  min-height: 200px;
}
</style>