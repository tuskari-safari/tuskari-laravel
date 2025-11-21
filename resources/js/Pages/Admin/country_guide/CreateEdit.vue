<template>
    <div>
        <Head title="Create Blog" v-if="!props.park" />
        <Head title="Edit Blog" v-if="props.park" />
        <div class="kt-portlet kt-portlet--mobile">
            <div class="kt-portlet__body">
                <form @submit.prevent="submit">
                    <div class="form-group validated row">
                        <div class="form-group col-lg-6">
                            <label for="illness_type">Region<span class="text-danger">*</span></label>
                            <select class="form-control border-gray-200" v-model="form.region">
                                <option value="">Select Region</option>
                                <option v-for="region in regions" :value="region.id" :key="region.id">{{ region.name }}</option>
                            </select>
                            <span class="text-danger" v-if="form.errors.region">{{ form.errors.region }}</span>
                        </div>
                        
                        <div class="form-group col-lg-6">
                            <label for="name">Name <span class="text-danger">*</span></label>
                            <input type="text" id="name" v-model="form.name" class="form-control border-gray-200"
                                placeholder="name" />
                            <span class="text-danger" v-if="form.errors.name">{{
                                form.errors.name
                            }}</span>
                        </div>

                        <div class="form-group col-lg-6">
                            <label for="subtitle">Subtitle <span class="text-danger">*</span></label>
                            <input type="text" id="subtitle" v-model="form.subtitle"
                                class="form-control border-gray-200" placeholder="Subtitle" />
                            <span class="text-danger" v-if="form.errors.subtitle">{{
                                form.errors.subtitle
                            }}</span>
                        </div>

                        <div class="form-group col-lg-3">
                            <label for="icon">Banner Image</label>
                            <FileUpload @input="form.banner_image = $event.target.files[0]" :imageurl="imageUrl1"
                                accept="image/*" />
                            <span class="text-danger" v-if="form.errors.banner_image">
                                {{ form.errors.banner_image }}
                            </span>
                        </div>

                        <div class="form-group col-lg-3">
                            <label for="icon">Thumbnail</label>
                            <FileUpload @input="form.thumbnail = $event.target.files[0]" :imageurl="imageUrl2"
                                accept="image/*" />
                            <span class="text-danger" v-if="form.errors.thumbnail">
                                {{ form.errors.thumbnail }}
                            </span>
                        </div>

                                
                        <div class="form-group col-lg-6">
                            <label for="middle_sec_title">Middile Section Title <span class="text-danger">*</span></label>
                            <input type="text" id="middle_sec_title" v-model="form.middle_sec_title" class="form-control border-gray-200"
                                placeholder="middle_sec_title" />
                            <span class="text-danger" v-if="form.errors.middle_sec_title">{{
                                form.errors.middle_sec_title
                            }}</span>
                        </div>
                                
                        <div class="form-group col-lg-6">
                            <label for="middle_sec_subtitle">Middle Section Subtitle <span class="text-danger">*</span></label>
                            <ckeditor v-model="form.middle_sec_subtitle" :config="editorConfig"></ckeditor>
                            <span class="text-danger" v-if="form.errors.middle_sec_subtitle">{{
                                form.errors.middle_sec_subtitle
                            }}</span>
                        </div>
            
                        <div class="form-group col-lg-12">
                            <h3>Content Details</h3>
                        </div>
                        <div class="form-group col-lg-12">
                            <div class="row" v-for="(input, k) in form.content_details" :key="k">
                                <div class="form-group col-lg-6">
                                    <label for="title">Title </label>
                                    <input v-model="form.content_details[k].title" class="form-control border-gray-200"
                                        placeholder="Title" />
                                    <span class="text-danger" v-if="form.errors['content_details.' + k + '.title']">
                                        {{ form.errors['content_details.' + k + '.title'] }}
                                    </span>
                                </div>

                                <div class="form-group col-lg-6">
                                    <label for="subtitle">Subtitle </label>
                                    <input v-model="form.content_details[k].subtitle" class="form-control border-gray-200"
                                        placeholder="Subtitle" />
                                    <span class="text-danger" v-if="form.errors['content_details.' + k + '.subtitle']">
                                        {{ form.errors['content_details.' + k + '.subtitle'] }}
                                    </span>
                                </div>

                                <div class="form-group col-lg-6">
                                    <label for="icon">Image </label>
                                    <FileUpload @input="form.content_details[k].image = $event.target.files[0]"
                                        :imageurl="form.content_details[k].image" accept="image/*" />
                                    <span class="text-danger" v-if="form.errors['content_details.' + k + '.image']">
                                        {{ form.errors['content_details.' + k + '.image'] }}
                                    </span>
                                </div>

                                <!-- <div class="form-group col-lg-6">
                                    <label for="title">Align </label>
                                    <input v-model="form.content_details[k].align" class="form-control border-gray-200"
                                        placeholder="align" />
                                    <span class="text-danger" v-if="form.errors['content_details.' + k + '.align']">
                                        {{ form.errors['content_details.' + k + '.align'] }}
                                    </span>
                                </div> -->

                                <div class="col-lg-6">
                                    <div class="contnt-admore-butn">
                                    <a v-if="form.content_details.length < 5" href="javascript:" class="btn btn-primary m-2"
                                        @click="addInput(k)">
                                        <span>+</span>
                                    </a>
                                    <a href="javascript:" class="btn btn-primary" @click="removeInput(k)"
                                        v-if="form.content_details.length > 1">
                                        <span>-</span>
                                    </a>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="form-group col-lg-12">
                            <h3>Unique Experience</h3>
                        </div>
                               
                        <div class="form-group col-lg-6">
                            <label for="unique_experience_title">Unique Experience Title <span class="text-danger">*</span></label>
                            <input type="text" id="unique_experience_title" v-model="form.unique_experience_title" class="form-control border-gray-200"
                                placeholder="Title" />
                            <span class="text-danger" v-if="form.errors.unique_experience_title">{{
                                form.errors.unique_experience_title
                            }}</span>
                        </div>
       
                        <div class="form-group col-lg-6">
                            <label for="unique_experience_desc">Unique Experience Subtitle <span class="text-danger">*</span></label>
                            <input type="text" id="unique_experience_desc" v-model="form.unique_experience_desc" class="form-control border-gray-200"
                                placeholder="Subtitle" />
                            <span class="text-danger" v-if="form.errors.unique_experience_desc">{{
                                form.errors.unique_experience_desc
                            }}</span>
                        </div>

                        <div class="form-group col-lg-12">
                            <div class="row" v-for="(input, k) in form.unique_experience" :key="k">
                                <div class="form-group col-lg-6">
                                    <label for="title"> Title </label>
                                    <input v-model="form.unique_experience[k].title"
                                        class="form-control border-gray-200" placeholder="Title" />
                                    <span class="text-danger" v-if="form.errors['unique_experience.' + k + '.title']">
                                        {{ form.errors['unique_experience.' + k + '.title'] }}
                                    </span>
                                </div>

                                <div class="form-group col-lg-6">
                                    <label for="subtitle">Subtitle </label>
                                    <input v-model="form.unique_experience[k].subtitle"
                                        class="form-control border-gray-200" placeholder="Subtitle" />
                                    <span class="text-danger" v-if="form.errors['unique_experience.' + k + '.subtitle']">
                                        {{ form.errors['unique_experience.' + k + '.subtitle'] }}
                                    </span>
                                </div>

                                <div class="form-group col-lg-6">
                                    <label for="icon">Icon </label>
                                    <FileUpload @input="form.unique_experience[k].image = $event.target.files[0]"
                                        :imageurl="form.unique_experience[k].image" accept="image/*" />
                                    <span class="text-danger" v-if="form.errors['unique_experience.' + k + '.image']">
                                        {{ form.errors['unique_experience.' + k + '.image'] }}
                                    </span>
                                </div>

                                <div class="col-lg-6">
                                    <div class="contnt-admore-butn">
                                    <a v-if="form.unique_experience.length < 4" href="javascript:"
                                        class="btn btn-primary m-2" @click="addUniqueExperienceInput(k)">
                                        <span>+</span>
                                    </a>
                                    <a href="javascript:" class="btn btn-primary"
                                        @click="removeUniqueExperienceInput(k)"
                                        v-if="form.unique_experience.length > 1">
                                        <span>-</span>
                                    </a>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="form-group col-lg-12">
                            <h3>Bottom Banner</h3>
                        </div>
                        <div class="form-group col-lg-12">
                            <div class="row" v-for="(input, k) in form.bottom_banner" :key="k">
                                <div class="form-group col-lg-6">
                                    <label for="title"> Title </label>
                                    <input v-model="form.bottom_banner[k].title" class="form-control border-gray-200"
                                        placeholder="Title" />
                                    <span class="text-danger" v-if="form.errors['bottom_banner.' + k + '.title']">
                                        {{ form.errors['bottom_banner.' + k + '.title'] }}
                                    </span>
                                </div>

                                <div class="form-group col-lg-6">
                                    <label for="subtitle">Subtitle </label>
                                    <input v-model="form.bottom_banner[k].subtitle" class="form-control border-gray-200"
                                        placeholder="Subtitle" />
                                    <span class="text-danger" v-if="form.errors['bottom_banner.' + k + '.subtitle']">
                                        {{ form.errors['bottom_banner.' + k + '.subtitle'] }}
                                    </span>
                                </div>

                                <div class="form-group col-lg-6">
                                    <label for="icon">Image </label>
                                    <FileUpload @input="form.bottom_banner[k].image = $event.target.files[0]"
                                        :imageurl="form.bottom_banner[k].image" accept="image/*" />
                                    <span class="text-danger" v-if="form.errors['bottom_banner.' + k + '.image']">
                                        {{ form.errors['bottom_banner.' + k + '.image'] }}
                                    </span>
                                </div>

                                <!-- <div class="col-lg-6">
                                    <a v-if="form.bottom_banner.length < 4" href="javascript:"
                                        class="btn btn-primary m-2" @click="addBottomBannerInput(k)">
                                        <span>+</span>
                                    </a>
                                    <a href="javascript:" class="btn btn-primary" @click="removeBottomBannerInput(k)"
                                        v-if="form.bottom_banner.length > 1">
                                        <span>-</span>
                                    </a>
                                </div> -->
                            </div>
                        </div>

                        <div class="form-group col-lg-12" >
                           <h3>FAQs</h3> 
                        </div>
                        <div class="form-group col-lg-12" >
                            <div class="row align-items-center" v-for="(input, k) in form.faqs" :key="k">
                                <div class="form-group col-lg-4">
                                    <label for="question">Question </label>
                                    <input v-model="form.faqs[k].question" class="form-control border-gray-200" placeholder="Question" />
                                    <span class="text-danger" v-if="form.errors['faqs.'+k+'.question']">
                                        {{ form.errors['faqs.'+k+'.question']}}
                                    </span>
                                </div>

                                <div class="form-group col-lg-4">
                                    <label for="answer">Answer </label>
                                 
                                    <textarea id="answer" v-model="form.faqs[k].answer" class="form-control border-gray-200" placeholder="Answer"></textarea>
                                    <span class="text-danger" v-if="form.errors['faqs.'+k+'.answer']">
                                        {{ form.errors['faqs.'+k+'.answer']}}
                                    </span>
                                </div>

                                <div class="col-lg-4">
                                    <a href="javascript:" class="btn btn-primary m-2" @click="addFaqInput(k)"
                                        >
                                        <span>+</span>
                                    </a>
                                    <a href="javascript:" class="btn btn-primary" @click="removeFaqInput(k)"
                                     >
                                        <span>-</span>
                                    </a>
                                </div>
                            </div>
                        </div>

                        <div class="form-group col-lg-6">
                            <label for="status">Status <span class="text-danger">*</span></label>
                            <select id="status" class="form-control border-gray-200" v-model="form.status">
                                <option value="">Select Status</option>
                                <option value="1">Active</option>
                                <option value="0">Inactive</option>
                            </select>
                            <span class="text-danger" v-if="form.errors.status">{{
                                form.errors.status
                            }}</span>
                        </div>
                    </div>
                    <div class="row">
                        <div class="mr-2">
                            <submit-button :disabled="form.processing"
                                :isLoading="form.processing">Submit</submit-button>
                        </div>
                        <div class="">
                            <Link :href="route('admin.country-guides')" class="btn btn-info">Cancel</Link>
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
import SubmitButton from "../../../components/SubmitButton.vue";
import FileUpload from "../../../components/FileUpload.vue";
import { component as ckeditor } from "@mayasabha/ckeditor4-vue3";

const editorConfig = {
    height: 200,
    resize_enabled: false,
    format_tags: 'p;h1;h2;h3;h4;h5;h6',
    toolbar: [
        { name: 'basicstyles', items: ['Bold', 'Italic'] },
        { name: 'styles', items: ['Format'] },
        { name: 'colors', items: ['FontColor', 'FontBackgroundColor'] }, // ✅ Add colors
        { name: 'lists', items: ['NumberedList', 'BulletedList'] },
        { name: 'tools', items: ['Source'] },
    ],
};
const props = defineProps({
    errors: Object,
    country: Object,
    regions: Array
});

const imageUrl1 = ref("");
const imageUrl2 = ref("");

const form = useForm({
    name: props.country?.name || null,
    subtitle: props.country?.subtitle || null,
    region: props.country?.region || "",
    status: props.country?.status || 1,
    middle_sec_title: props.country?.middle_sec_title || "",
    middle_sec_subtitle: props.country?.middle_sec_subtitle || "",
    unique_experience_title: props.country?.unique_experience_title || "",
    unique_experience_desc: props.country?.unique_experience_desc || "",
    content_details: [
        {
            image: null,
            title: "",
            subtitle: "",
            align: ""
        },
    ],
    bottom_banner: [
        {
            image: null,
            title: "",
            subtitle: "",
        },
    ],
    unique_experience: [
        {
            image: null,
            title: "",
            subtitle: "",
        },
    ],
    banner_image: null,
    thumbnail: null,

    faqs: [
        {
            question: "",
            answer: ""
        }
    ]
});

onMounted(() => {
    imageUrl1.value = props.country?.banner_image_url || "";
    imageUrl2.value = props.country?.thumbnail_url || "";
  
    const pageTitle = props.country ? "Edit Country Guide" : "Create Country Guide";
    emit.emit("pageName", "Country Guide Management", [
        {
            title: "Country Guide List",
            routeName: "admin.country-guides",
        },
        {
            title: pageTitle,
            routeName: "",
        },
    ]);
    if (props.country?.content_details) {
        form.content_details = JSON.parse(props.country?.content_details);
    }

    if (props.country?.bottom_banner) {
        form.bottom_banner = JSON.parse(props.country?.bottom_banner);
    }

    if (props.country?.unique_experience) {
        form.unique_experience = JSON.parse(props.country.unique_experience);
    }

    if (props.country?.faqs) {
        form.faqs = JSON.parse(props.country.faqs);
    }
});

const addInput = (index) => {
    form.content_details.push({
        image: null,
        title: "",
        subtitle: "",
        align: ""
    });
};

const removeInput = (index) => {
    if (form.content_details.length > 1) {
        form.content_details.splice(index, 1);
    }
};

const addUniqueExperienceInput = (index) => {
    form.unique_experience.push({
        image: null,
        title: "",
        subtitle: "",
    });
};
const removeUniqueExperienceInput = (index) => {
    if (form.unique_experience.length > 1) {
        form.unique_experience.splice(index, 1);
    }
};

const addFaqInput = (index) => {
    form.faqs.push({
        question: "",
        answer: "",
    });
};

const removeFaqInput = (index) => {
    if (form.faqs.length > 1) {
        form.faqs.splice(index, 1);
    }
};


function submit() {
    if (props.country) {
        form.post(route("admin.country-guide.edit", props.country.id));
    } else {
        form.post(route("admin.country-guide.create"));
    }
}
</script>
<style src="@vueform/multiselect/themes/default.css"></style>