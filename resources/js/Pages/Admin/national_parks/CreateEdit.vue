<template>
    <div>
        <Head title="Create National Park & Private Reserves" v-if="!props.park" />
        <Head title="Edit National Park & Private Reserves" v-if="props.park" />

        <div class="kt-portlet kt-portlet--mobile">
            <div class="kt-portlet__body">
                <form @submit.prevent="submit">
                    <div class="form-group validated row">
                        <div class="form-group col-lg-6">
                            <label for="status">Type <span class="text-danger">*</span></label>
                            <select id="type" class="form-control border-gray-200" v-model="form.type">
                                <option value="">Select type</option>
                                <option value="national_park">National Park</option>
                                <option value="private_reserve">Private Reserves</option>
                            </select>
                            <span class="text-danger" v-if="form.errors.type">{{
                                form.errors.type
                            }}</span>
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
                            <label for="short_description">Short Description <span class="text-danger">*</span></label>
                            <textarea id="short_description" v-model="form.short_description"
                                class="form-control border-gray-200" placeholder="description"></textarea>
                            <span class="text-danger" v-if="form.errors.short_description">{{ form.errors.
                                short_description }}</span>
                        </div>

                        <div class="form-group col-lg-3">
                            <label for="icon">Banner Image</label>
                            <FileUpload @input="form.banner = $event.target.files[0]" :imageurl="imageUrl1"
                                accept="image/*" />
                            <span class="text-danger" v-if="form.errors.banner">
                                {{ form.errors.banner }}
                            </span>
                        </div>

                        <div class="form-group col-lg-6">
                            <label for="subtitle">Middle Subtitle <span class="text-danger">*</span></label>
                            <input type="text" id="subtitle" v-model="form.subtitle"
                                class="form-control border-gray-200" placeholder="Subtitle" />
                            <span class="text-danger" v-if="form.errors.subtitle">{{
                                form.errors.subtitle
                            }}</span>
                        </div>

                        <div class="form-group col-lg-6">
                            <label for="description">Middle Description <span class="text-danger">*</span></label>
                            <ckeditor v-model="form.description" :config="editorConfig"></ckeditor>
                            <span class="text-danger" v-if="form.errors.description">{{ form.errors.description
                                }}</span>
                        </div>

                        <div class="form-group col-lg-6">
                            <label for="title">Top Area Title <span class="text-danger">*</span></label>
                            <input type="text" id="title" v-model="form.title" class="form-control border-gray-200"
                                placeholder="Title" />
                            <span class="text-danger" v-if="form.errors.title">{{
                                form.errors.title
                            }}</span>
                        </div>

                        <div class="form-group col-lg-3">
                            <label for="icon">Thumbnail</label>
                            <FileUpload @input="form.photo = $event.target.files[0]" :imageurl="imageUrl2"
                                accept="image/*" />
                            <span class="text-danger" v-if="form.errors.photo">
                                {{ form.errors.photo }}
                            </span>
                        </div>
                        <!-- <div class="form-group col-lg-3">
                                <label for="icon">Middle Banner Image</label>
                                <FileUpload @input="form.middle_banner_image = $event.target.files[0]" :imageurl="imageUrl4" accept="image/*" />
                                <span class="text-danger" v-if="form.errors.middle_banner_image">
                                    {{ form.errors.middle_banner_image }}
                                </span>
                        </div> -->

                        <div class="form-group col-lg-12">
                            <label for="illness_type">Country<span class="text-danger">*</span></label>
                            <select class="form-control border-gray-200" v-model="form.country_id">
                                <option value="">Select Country</option>
                                <option v-for="country in countries" :value="country.id" :key="country.id">{{
                                    country.name }}</option>
                            </select>
                            <span class="text-danger" v-if="form.errors.country_id">{{ form.errors.country_id }}</span>
                        </div>

                        <div class="form-group serch-frmgrp col-lg-12">
                            <label for="status">Location <span class="text-danger">*</span></label>
                            <!-- <MapBoxAutoComplete :modelValue="form.location" @update:location="handleLocationUpdate($event)" /> -->
                            <MapPicker :lat="form.latitude" :lng="form.longitude" :placeName="form.location" 
                            @update:coordinates="coords => {
                                        form.latitude = coords.lat;
                                        form.longitude = coords.lng;
                                        form.location = coords.place_name;
                                    }"/>
                            <span class="text-danger" v-if="form.errors.location">{{ form.errors.location }}</span>
                        </div>

                        <!-- category -->
                        <div class="form-group col-lg-12" v-if="form.type == 'national_park'">
                            <h3>Only in park</h3>
                        </div>
                        <div class="form-group col-lg-6" v-if="form.type == 'national_park'">
                            <label for="title_2">Only in Park Title<span class="text-danger">*</span></label>
                            <input type="text" id="title_2" v-model="form.title_2" class="form-control border-gray-200"
                                placeholder="Title" />
                            <span class="text-danger" v-if="form.errors.title_2">{{
                                form.errors.title_2
                            }}</span>
                        </div>
                        <div class="form-group col-lg-6" v-if="form.type == 'national_park'">
                            <label for="subtitle_2">Only in Park Subtitle<span class="text-danger">*</span></label>
                            <input type="text" id="subtitle_2" v-model="form.subtitle_2"
                                class="form-control border-gray-200" placeholder="Title" />
                            <span class="text-danger" v-if="form.errors.subtitle_2">{{
                                form.errors.subtitle_2
                            }}</span>
                        </div>

                         <div class="form-group col-lg-12" v-if="form.type == 'national_park'">
                            <h3>Category</h3>
                        </div>
                        <div class="form-group col-lg-12" v-if="form.type == 'national_park'">
                            <div class="row align-items-center" v-for="(input, k) in form.category" :key="k">
                                <div class="form-group col-lg-6">
                                    <label for="title">Category Title </label>
                                    <input v-model="form.category[k].title" class="form-control border-gray-200"
                                        placeholder="Title" />
                                    <span class="text-danger" v-if="form.errors['category.' + k + '.title']">
                                        {{ form.errors['category.' + k + '.title'] }}
                                    </span>
                                </div>

                                <div class="form-group col-lg-6">
                                    <label for="subtitle">Subtitle </label>
                                    <input v-model="form.category[k].subtitle" class="form-control border-gray-200"
                                        placeholder="Subtitle" />
                                    <span class="text-danger" v-if="form.errors['category.' + k + '.subtitle']">
                                        {{ form.errors['category.' + k + '.subtitle'] }}
                                    </span>
                                </div>

                                <div class="form-group col-lg-6">
                                    <label for="icon">Icon </label>
                                    <FileUpload @input="form.category[k].image = $event.target.files[0]"
                                        :imageurl="form.category[k].image" accept="image/*" />

                                    <span class="text-danger" v-if="form.errors['category.' + k + '.image']">
                                        {{ form.errors['category.' + k + '.image'] }}
                                    </span>
                                </div>

                                <div class="col-lg-6">
                                    <a v-if="form.category.length < 5" href="javascript:" class="btn btn-primary m-2"
                                        @click="addInput(k)">
                                        <span>+</span>
                                    </a>
                                    <a href="javascript:" class="btn btn-primary" @click="removeInput(k)"
                                        v-if="form.category.length > 1">
                                        <span>-</span>
                                    </a>
                                </div>
                            </div>
                        </div>
                        <!-- Wildlife highlights -->

                        <div class="form-group col-lg-6">
                            <label for="wildlife_highlights_title">Wildlife Highlights Title <span
                                    class="text-danger">*</span></label>
                            <input type="text" id="wildlife_highlights_title" v-model="form.wildlife_highlights_title"
                                class="form-control border-gray-200" placeholder="wildlife highlights title" />
                            <span class="text-danger" v-if="form.errors.wildlife_highlights_title">{{
                                form.errors.wildlife_highlights_title
                            }}</span>
                        </div>

                        <div class="form-group col-lg-6">
                            <label for="wildlife_highlights_desc">Wildlife Highlights Description <span
                                    class="text-danger">*</span></label>
                            <textarea id="wildlife_highlights_desc" v-model="form.wildlife_highlights_desc"
                                class="form-control border-gray-200" placeholder="wildlife highlights desc"></textarea>
                            <span class="text-danger" v-if="form.errors.wildlife_highlights_desc">{{ form.errors.
                                wildlife_highlights_desc }}</span>
                        </div>

                        <div class="form-group col-lg-12">
                            <h3>Wildlife Highlights throughout the year</h3>
                        </div>
                        <div class="form-group col-lg-12">
                            <div class="row align-items-center" v-for="(input, k) in form.best_visit_time" :key="k">
                                <div class="form-group col-lg-6">
                                    <label for="title"> Title </label>
                                    <!-- <input v-model="form.best_visit_time[k].title" class="form-control border-gray-200" placeholder="Title" /> -->
                                    <ckeditor v-model="form.best_visit_time[k].title" :config="editorConfig">
                                    </ckeditor>
                                    <span class="text-danger" v-if="form.errors['best_visit_time.' + k + '.title']">
                                        {{ form.errors['best_visit_time.' + k + '.title'] }}
                                    </span>
                                </div>

                                <div class="form-group col-lg-6">
                                    <label for="subtitle">Subtitle </label>
                                    <!-- <input  class="form-control border-gray-200" placeholder="Subtitle" /> -->
                                    <ckeditor v-model="form.best_visit_time[k].subtitle" :config="editorConfig">
                                    </ckeditor>
                                    <span class="text-danger" v-if="form.errors['best_visit_time.' + k + '.subtitle']">
                                        {{ form.errors['best_visit_time.' + k + '.subtitle'] }}
                                    </span>
                                </div>

                                <div class="form-group col-lg-6">
                                    <label for="icon">Image </label>
                                    <FileUpload @input="form.best_visit_time[k].image = $event.target.files[0]"
                                        :imageurl="form.best_visit_time[k].image" accept="image/*" />
                                    <span class="text-danger" v-if="form.errors['best_visit_time.' + k + '.image']">
                                        {{ form.errors['best_visit_time.' + k + '.image'] }}
                                    </span>
                                </div>

                                <div class="col-lg-6">
                                    <a href="javascript:" class="btn btn-primary m-2" @click="addBestTimeInput(k)">
                                        <span>+</span>
                                    </a>
                                    <a href="javascript:" class="btn btn-primary" @click="removeBestTimeInput(k)">
                                        <span>-</span>
                                    </a>
                                </div>
                            </div>
                        </div>

                        <!-- tips -->
                        <!-- <div class="form-group col-lg-12" v-if="form.type == 'national_park'">
                           <h3>Traveler Trips</h3> 
                        </div>
                        <div class="form-group col-lg-12" v-if="form.type == 'national_park'">
                            <div class="row align-items-center" v-for="(input, k) in form.traveler_tips" :key="k">
                                <div class="form-group col-lg-6">
                                    <label for="title"> Title </label>
                                    <input v-model="form.traveler_tips[k].title" class="form-control border-gray-200" placeholder="Title" />
                                    <span class="text-danger" v-if="form.errors['traveler_tips.'+k+'.title']">
                                        {{ form.errors['traveler_tips.'+k+'.title']}}
                                    </span>
                                </div>

                                <div class="form-group col-lg-6">
                                    <label for="subtitle">Subtitle </label>
                                    <input v-model="form.traveler_tips[k].subtitle" class="form-control border-gray-200" placeholder="Subtitle" />
                                    <span class="text-danger" v-if="form.errors['traveler_tips.'+k+'.subtitle']">
                                        {{ form.errors['traveler_tips.'+k+'.subtitle']}}
                                    </span>
                                </div>

                                <div class="form-group col-lg-6">
                                    <label for="icon">Icon </label>
                                    <FileUpload @input="form.traveler_tips[k].image = $event.target.files[0]" :imageurl="form.traveler_tips[k].image" accept="image/*" />
                                    <span class="text-danger" v-if="form.errors['traveler_tips.'+k+'.image']">
                                        {{ form.errors['traveler_tips.'+k+'.image'] }}
                                    </span>
                                </div>

                                <div class="col-lg-6">
                                    <a v-if="form.traveler_tips.length < 4" href="javascript:" class="btn btn-primary m-2" @click="addTravelerTipsInput(k)"
                                        >
                                        <span>+</span>
                                    </a>
                                    <a href="javascript:" class="btn btn-primary" @click="removeTravelerTipsInput(k)"
                                    v-if="form.traveler_tips.length > 1" >
                                        <span>-</span>
                                    </a>
                                </div>
                            </div>
                        </div> -->

                        <!-- unique experience -->
                        <div class="form-group col-lg-12">
                            <h3>{{ form.type == 'national_park' ? 'Top Areas Category' : 'Signature Experience Category'}}</h3>
                        </div>
                        <div class="form-group col-lg-12">
                            <div class="row align-items-center" v-for="(input, k) in form.unique_experience" :key="k">
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
                                    <a href="javascript:" class="btn btn-primary m-2"
                                        @click="addUniqueExperienceInput(k)">
                                        <span>+</span>
                                    </a>
                                    <a href="javascript:" class="btn btn-primary"
                                        @click="removeUniqueExperienceInput(k)">
                                        <span>-</span>
                                    </a>
                                </div>
                            </div>
                        </div>

                        <!-- accommodation -->
                        <!-- <div class="form-group col-lg-6">
                            <label for="types"> Accomodations <span class="text-danger">*</span></label>
                            <br>
                               
                                <Multiselect placeholder="Select" v-model="form.accomodation_ids" mode="tags"
                                :close-on-select="true" :searchable="true" :create-option="false" :groups="true"
                                :options="[{  options: props.accomodations }]" />

                                <span class="text-danger" v-if="form.errors.accomodation_ids">{{ form.errors.accomodation_ids }}</span>
                   
                        </div> -->

                        <!-- wildlife -->
                        <!-- <div class="form-group col-lg-6">
                            <label for="types"> Wildlife highlights <span class="text-danger">*</span></label>
                            <br>
                             
                                <Multiselect placeholder="Select" v-model="form.wild_lives_ids" mode="tags"
                                :close-on-select="true" :searchable="true" :create-option="false" :groups="true"
                                :options="[{  options: props.wildlife }]" />
                                <span class="text-danger" v-if="form.errors.wild_lives_ids">{{ form.errors.wild_lives_ids }}</span>
                   
                        </div> -->

                        <!-- bottom banner -->
                        <div class="form-group col-lg-6">
                            <label for="subtitle">Bottom banner <span class="text-danger">*</span></label>
                            <input type="text" id="subtitle" v-model="form.impact" class="form-control border-gray-200"
                                placeholder="impact" />
                            <span class="text-danger" v-if="form.errors.impact">{{
                                form.errors.impact
                            }}</span>
                        </div>

                        <div class="form-group col-lg-3">
                            <label for="icon">Bottom banner Image </label>
                            <FileUpload @input="form.impact_photo = $event.target.files[0]" :imageurl="imageUrl3"
                                accept="image/*" />
                            <span class="text-danger" v-if="form.errors.impact_photo">
                                {{ form.errors.impact_photo }}
                            </span>
                        </div>

                        <div class="form-group col-lg-6">
                            <label for="status">Is most popular? <span class="text-danger">*</span></label>
                            <select id="is_most_popular" class="form-control border-gray-200"
                                v-model="form.is_most_popular">
                                <option value="">Select</option>
                                <option value="1">Yes</option>
                                <option value="0">No</option>
                            </select>
                            <span class="text-danger" v-if="form.errors.is_most_popular">{{
                                form.errors.is_most_popular
                            }}</span>
                        </div>

                        <div class="form-group col-lg-6">
                            <label for="status">Is hidden gem? <span class="text-danger">*</span></label>
                            <select id="is_hidden_gem" class="form-control border-gray-200"
                                v-model="form.is_hidden_gem">
                                <option value="">Select</option>
                                <option value="1">Yes</option>
                                <option value="0">No</option>
                            </select>
                            <span class="text-danger" v-if="form.errors.is_hidden_gem">{{
                                form.errors.is_hidden_gem
                            }}</span>
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

                        <div class="form-group col-lg-12">
                            <h3>FAQs</h3>
                        </div>
                        <div class="form-group col-lg-12">
                            <div class="row align-items-center" v-for="(input, k) in form.faqs" :key="k">
                                <div class="form-group col-lg-4">
                                    <label for="question">Question </label>
                                    <input v-model="form.faqs[k].question" class="form-control border-gray-200"
                                        placeholder="Question" />
                                    <span class="text-danger" v-if="form.errors['faqs.' + k + '.question']">
                                        {{ form.errors['faqs.' + k + '.question'] }}
                                    </span>
                                </div>

                                <div class="form-group col-lg-4">
                                    <label for="answer">Answer </label>
                                    <textarea id="answer" v-model="form.faqs[k].answer"
                                        class="form-control border-gray-200" placeholder="Answer" rows="5"></textarea>
                                    <span class="text-danger" v-if="form.errors['faqs.' + k + '.answer']">
                                        {{ form.errors['faqs.' + k + '.answer'] }}
                                    </span>
                                </div>

                                <div class="col-lg-4">
                                    <a href="javascript:" class="btn btn-primary m-2" @click="addFaqInput(k)">
                                        <span>+</span>
                                    </a>
                                    <a href="javascript:" class="btn btn-primary" @click="removeFaqInput(k)">
                                        <span>-</span>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="mr-2">
                            <submit-button :disabled="form.processing"
                                :isLoading="form.processing">Submit</submit-button>
                        </div>
                        <div class="">
                            <Link :href="route('admin.national-park-reserves.index')" class="btn btn-info">Cancel</Link>
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
import SubmitButton from "@/components/SubmitButton.vue";
import { component as ckeditor } from "@mayasabha/ckeditor4-vue3";
import FileUpload from "@/components/FileUpload.vue";
import { watch } from "vue";
import Multiselect from '@vueform/multiselect'
import MapBoxAutoComplete from '@/components/Frontend/MapBoxAutoComplete.vue';
import MapPicker from "../../../components/Frontend/MapPicker.vue";

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

let data = reactive({
    multiselect: [],
});

const props = defineProps({
    errors: Object,
    park: Object,
    accomodations: Array,
    wildlife: Array,
    safari: Array,
    selected_accomodations: Array,
    selected_wildlife: Array,
    countries: Object,
});
const imageUrl1 = ref("");
const imageUrl2 = ref("");
const imageUrl3 = ref("");
const imageUrl4 = ref("");

const form = useForm({
    title: props.park?.title || null,
    title_2: props.park?.title_2 || null,
    name: props.park?.name || null,
    subtitle: props.park?.subtitle || null,
    subtitle_2: props.park?.subtitle_2 || null,
    type: props.park?.type || "",
    description: props.park?.description || null,
    location: props.park?.location || null,
    latitude: props.park?.lat || null,
    longitude: props.park?.long || null,
    short_description: props.park?.short_description || null,
    status: props.park?.status || 1,
    category: [
        {
            image: null,
            title: "",
            subtitle: "",
        },
    ],
    best_visit_time: [
        {
            image: null,
            title: "",
            subtitle: "",
        },
    ],
    traveler_tips: [
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
    accomodation_ids: props.selected_accomodations || [],
    wild_lives_ids: props.selected_wildlife || [],
    impact: props.park?.impact || null,
    impact_photo: null,
    banner: null,
    photo: null,
    middle_banner_image: null,
    is_most_popular: props.park?.is_most_popular || 0,
    is_hidden_gem: props.park?.is_hidden_gem || 0,
    country_id: props.park?.country_id || "",
    wildlife_highlights_title: props.park?.wildlife_highlights_title || "",
    wildlife_highlights_desc: props.park?.wildlife_highlights_desc || "",
    faqs: props.park?.faqs || [
        {
            question: "",
            answer: "",
        },
    ],
});

onMounted(() => {
    
    imageUrl1.value = props.park?.full_banner_url || "";
    imageUrl2.value = props.park?.full_thumbnail_url || "";
    imageUrl3.value = props.park?.full_impact_image_url || "";
    // imageUrl4.value = props.park?.full_middle_banner_image || "";
    const pageTitle = props.park ? "Edit National Park & Private Reserves" : "Create National Park & Private Reserves";
    emit.emit("pageName", "National Park & Private Reserves Management", [
        {
            title: "National Park & Private Reserves List",
            routeName: "admin.national-park-reserves.index",
        },
        {
            title: pageTitle,
            routeName: "",
        },
    ]);
    if (props.park?.category) {
        form.category = JSON.parse(props.park?.category);
    }

    if (props.park?.best_visit_time) {
        form.best_visit_time = JSON.parse(props.park.best_visit_time);
    }
    if (props.park?.traveler_tips) {
        form.traveler_tips = JSON.parse(props.park.traveler_tips);
    }
    if (props.park?.unique_experience) {
        form.unique_experience = JSON.parse(props.park.unique_experience);
    }

    if (props.park?.faqs) {
        form.faqs = JSON.parse(props.park.faqs);
    }
});


const addInput = (index) => {
    form.category.push({
        image: null,
        title: "",
        subtitle: "",
    });
};

const removeInput = (index) => {
    if (form.category.length > 1) {
        form.category.splice(index, 1);
    }
};

const addBestTimeInput = (index) => {
    form.best_visit_time.push({
        image: null,
        title: "",
        subtitle: "",
    });
};

const removeBestTimeInput = (index) => {
    if (form.best_visit_time.length > 1) {
        form.best_visit_time.splice(index, 1);
    }
};

const addTravelerTipsInput = (index) => {
    form.traveler_tips.push({
        image: null,
        title: "",
        subtitle: "",
    });
};

const removeTravelerTipsInput = (index) => {
    if (form.traveler_tips.length > 1) {
        form.traveler_tips.splice(index, 1);
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
const handleLocationUpdate = (loc) => {
    form.latitude = loc.latitude;
    form.longitude = loc.longitude;
    form.location = loc.place_name;
};

function submit() {
    if (props.park) {
        form.post(route("admin.national-park-reserves.edit", props.park.id));
    } else {
        form.post(route("admin.national-park-reserves.create"));
    }
}
</script>
<style src="@vueform/multiselect/themes/default.css"></style>