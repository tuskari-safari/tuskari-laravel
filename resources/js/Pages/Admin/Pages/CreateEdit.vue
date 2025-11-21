<template>

    <Head title="Create CMS Page" v-if="!props.park" />

    <Head title="Edit CMS Page" v-if="props.park" />
    <div class="kt-portlet kt-portlet--mobile">
        <div class="kt-portlet__body">
            <div class="col-md-12 col-lg-12">
                <h3>What is Tuskari</h3>
                <hr>
            </div>
            <form @submit.prevent="whatIsTuskariSubmit('what-is-tuskari')">
                <div class="form-group validated row">
                    <div class="form-group col-lg-4">
                        <label for="title">Title <span class="text-danger">*</span></label>
                        <!-- <input type="text" id="title" v-model="whatIsTuskariForm.title"
                            class="form-control border-gray-200" placeholder="Title" /> -->
                        <ckeditor v-model="whatIsTuskariForm.title" :config="editorConfig">
                        </ckeditor>
                        <span class="text-danger" v-if="whatIsTuskariForm.errors.title">{{
                            whatIsTuskariForm.errors.title
                        }}</span>
                    </div>
                    <div class="form-group col-lg-4">
                        <label for="subtitle">Sub Title <span class="text-danger">*</span></label>
                        <textarea id="subtitle" v-model="whatIsTuskariForm.subtitle"
                            class="form-control border-gray-200" placeholder="Sub Title" rows="2"
                            style="resize: none;"></textarea>
                        <span class="text-danger" v-if="whatIsTuskariForm.errors.subtitle">{{
                            whatIsTuskariForm.errors.subtitle
                        }}</span>
                    </div>

                    <div class="form-group col-lg-4">
                        <label for="icon">Thumbnail</label>
                        <FileUpload @input="whatIsTuskariForm.thumbnail = $event.target.files[0]"
                            :imageurl="whatIsTuskariForm.thumbnail_full_url" accept="image/*" />
                        <span class="text-danger" v-if="whatIsTuskariForm.errors.thumbnail">
                            {{ whatIsTuskariForm.errors.thumbnail }}
                        </span>
                    </div>
                </div>
                <div class="form-group col-lg-3">
                    <submit-button :disabled="whatIsTuskariForm.processing"
                        :isLoading="whatIsTuskariForm.processing">Save</submit-button>
                </div>
            </form>
            <div class="col-md-12 col-lg-12">
                <h3>How It Works</h3>
                <hr>
            </div>
            <form @submit.prevent="howItsWorkSubmit('how-it-works')">
                <div class="form-group validated row">
                    <div class="form-group col-lg-4">
                        <label for="title">Title <span class="text-danger">*</span></label>
                        <!-- <input type="text" id="title" v-model="howItsWorkForm.title"
                            class="form-control border-gray-200" placeholder="Title" /> -->
                            <ckeditor v-model="howItsWorkForm.title" :config="editorConfig">
                            </ckeditor>
                        <span class="text-danger" v-if="howItsWorkForm.errors.title">{{
                            howItsWorkForm.errors.title
                        }}</span>
                    </div>
                    <div class="form-group col-lg-4">
                        <label for="subtitle">Sub Title <span class="text-danger">*</span></label>
                        <textarea id="subtitle" v-model="howItsWorkForm.subtitle" class="form-control border-gray-200"
                            placeholder="Sub Title" rows="2" style="resize: none;"> </textarea>
                        <span class="text-danger" v-if="howItsWorkForm.errors.subtitle">{{
                            howItsWorkForm.errors.subtitle
                        }}</span>
                    </div>

                    <div class="form-group col-lg-4">
                        <label for="icon">Thumbnail</label>
                        <FileUpload @input="howItsWorkForm.thumbnail = $event.target.files[0]"
                            :imageurl="howItsWorkForm.thumbnail_full_url" accept="image/*" />
                        <span class="text-danger" v-if="howItsWorkForm.errors.thumbnail">
                            {{ howItsWorkForm.errors.thumbnail }}
                        </span>
                    </div>
                    <div class="form-group col-lg-12">
                        <h5>Features:</h5>
                        <div class="row" v-for="(input, k) in howItsWorkForm.features" :key="k">
                            <div class="form-group col-lg-4">
                                <label for="title"> Title </label>
                                <input v-model="howItsWorkForm.features[k].title" class="form-control border-gray-200"
                                    placeholder="Title" />
                                <span class="text-danger" v-if="howItsWorkForm.errors['features.' + k + '.title']">
                                    {{ howItsWorkForm.errors['features.' + k + '.title'] }}
                                </span>
                            </div>
                            <div class="form-group col-lg-4">
                                <label for="subtitle">Sub Title</label>
                                <textarea v-model="howItsWorkForm.features[k].subtitle"
                                    class="form-control border-gray-200" placeholder="Sub Title" rows="2"
                                    style="resize: none;"></textarea>
                                <span class="text-danger" v-if="howItsWorkForm.errors['features.' + k + '.subtitle']">
                                    {{ howItsWorkForm.errors['features.' + k + '.subtitle'] }}
                                </span>
                            </div>
                            <div class="form-group col-lg-3">
                                <label for="subtitle">Logo</label>
                                <FileUpload @input="howItsWorkForm.features[k].logo = $event.target.files[0]"
                                    :imageurl="howItsWorkForm.features[k].logo" accept="image/*" />
                                <span class="text-danger" v-if="howItsWorkForm.errors['features.' + k + '.logo']">
                                    {{ howItsWorkForm.errors['features.' + k + '.logo'] }}
                                </span>
                            </div>
                            <div class="col-lg-1" style="padding-top: 19px;">
                                <div class="fldaddbutn">
                                <a v-if="howItsWorkForm.features.length < 4" href="javascript:"
                                    class="btn btn-primary" @click="addHowItsWorkInput(k)">
                                    <span>+</span>
                                </a>
                                <a href="javascript:" class="btn btn-primary" @click="removeHowItsWorkInput(k)"
                                    v-if="howItsWorkForm.features.length > 1">
                                    <span>-</span>
                                </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="form-group col-lg-3">
                    <submit-button :disabled="howItsWorkForm.processing"
                        :isLoading="howItsWorkForm.processing">Save</submit-button>
                </div>
            </form>

            <div class="col-md-12 col-lg-12">
                <h3>Why Book With Tuskari</h3>
                <hr>
            </div>
            <form @submit.prevent="whyBookWithTuskariSubmit('why-book-with-tuskari')">
                <div class="form-group validated row">
                    <div class="form-group col-lg-6">
                        <label for="title">Title <span class="text-danger">*</span></label>
                        <!-- <input type="text" id="title" v-model="whyBookWithTuskariForm.title"
                            class="form-control border-gray-200" placeholder="Title" /> -->
                            <ckeditor v-model="whyBookWithTuskariForm.title" :config="editorConfig">
                            </ckeditor>
                        <span class="text-danger" v-if="whyBookWithTuskariForm.errors.title">{{
                            whyBookWithTuskariForm.errors.title
                        }}</span>
                    </div>

                    <div class="form-group col-lg-6">
                        <label for="icon">Thumbnail</label>
                        <FileUpload @input="whyBookWithTuskariForm.thumbnail = $event.target.files[0]"
                            :imageurl="whyBookWithTuskariForm.thumbnail_full_url" accept="image/*" />
                        <span class="text-danger" v-if="whyBookWithTuskariForm.errors.thumbnail">
                            {{ whyBookWithTuskariForm.errors.thumbnail }}
                        </span>
                    </div>
                    <div class="form-group col-lg-12">
                        <h5>Features:</h5>
                        <div class="row align-items-center" v-for="(input, k) in whyBookWithTuskariForm.features"
                            :key="k">
                            <div class="form-group col-lg-4">
                                <label for="title"> Title </label>
                                <input v-model="whyBookWithTuskariForm.features[k].title"
                                    class="form-control border-gray-200" placeholder="Title" />
                                <span class="text-danger"
                                    v-if="whyBookWithTuskariForm.errors['features.' + k + '.title']">
                                    {{ whyBookWithTuskariForm.errors['features.' + k + '.title'] }}
                                </span>
                            </div>
                            <div class="form-group col-lg-4">
                                <label for="subtitle">Sub Title</label>
                                <textarea v-model="whyBookWithTuskariForm.features[k].subtitle"
                                    class="form-control border-gray-200" placeholder="Sub Title" rows="2"
                                    style="resize: none;"></textarea>
                                <span class="text-danger"
                                    v-if="whyBookWithTuskariForm.errors['features.' + k + '.subtitle']">
                                    {{ whyBookWithTuskariForm.errors['features.' + k + '.subtitle'] }}
                                </span>
                            </div>
                            <div class="form-group col-lg-3">
                                <label for="subtitle">Logo</label>
                                <FileUpload @input="whyBookWithTuskariForm.features[k].logo = $event.target.files[0]"
                                    :imageurl="whyBookWithTuskariForm.features[k].logo" accept="image/*" />
                                <span class="text-danger"
                                    v-if="whyBookWithTuskariForm.errors['features.' + k + '.logo']">
                                    {{ whyBookWithTuskariForm.errors['features.' + k + '.logo'] }}
                                </span>
                            </div>
                            <div class="col-lg-1">
                                <div class="fldaddbutn">
                                <a v-if="whyBookWithTuskariForm.features.length < 4" href="javascript:"
                                    class="btn btn-primary" @click="addWhyTuskariInput(k)">
                                    <span>+</span>
                                </a>
                                <a href="javascript:" class="btn btn-primary" @click="removeWhyTuskariInput(k)"
                                    v-if="whyBookWithTuskariForm.features.length > 1">
                                    <span>-</span>
                                </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="form-group col-lg-3">
                    <submit-button :disabled="whyBookWithTuskariForm.processing"
                        :isLoading="whyBookWithTuskariForm.processing">Save</submit-button>
                </div>
            </form>

            <div class="col-md-12 col-lg-12">
                <h3>Trip Regions</h3>
                <hr>
            </div>
            <form @submit.prevent="tripRegionSubmit('trip-regions')">
                <div class="form-group validated row">
                    <div class="form-group col-lg-6">
                        <label for="title">Title <span class="text-danger">*</span></label>
                        <!-- <input type="text" id="title" v-model="tripRegionForm.title"
                            class="form-control border-gray-200" placeholder="Title" /> -->
                            <ckeditor v-model="tripRegionForm.title" :config="editorConfig">
                            </ckeditor>
                        <span class="text-danger" v-if="tripRegionForm.errors.title">{{
                            tripRegionForm.errors.title
                        }}</span>
                    </div>
                    <div class="form-group col-lg-6">
                        <label for="subtitle">Sub Title <span class="text-danger">*</span></label>
                        <input type="text" id="subtitle" v-model="tripRegionForm.subtitle"
                            class="form-control border-gray-200" placeholder="Sub Title" />
                        <span class="text-danger" v-if="tripRegionForm.errors.subtitle">{{
                            tripRegionForm.errors.subtitle
                        }}</span>
                    </div>
                    <div class="form-group col-lg-12">
                        <h5>Features:</h5>
                        <div class="row align-items-center" v-for="(input, k) in tripRegionForm.features" :key="k">
                            <div class="form-group col-lg-4">
                                <label for="title"> Title </label>
                                <input v-model="tripRegionForm.features[k].title" class="form-control border-gray-200"
                                    placeholder="Title" />
                                <span class="text-danger" v-if="tripRegionForm.errors['features.' + k + '.title']">
                                    {{ tripRegionForm.errors['features.' + k + '.title'] }}
                                </span>
                            </div>
                            <div class="form-group col-lg-4">
                                <label for="subtitle">Sub Title</label>
                                <textarea v-model="tripRegionForm.features[k].subtitle"
                                    class="form-control border-gray-200" placeholder="Sub Title" rows="2"
                                    style="resize: none;">  </textarea>
                                <span class="text-danger" v-if="tripRegionForm.errors['features.' + k + '.subtitle']">
                                    {{ tripRegionForm.errors['features.' + k + '.subtitle'] }}
                                </span>
                            </div>
                            <div class="form-group col-lg-3">

                                <label for="subtitle">Logo</label>
                                <FileUpload @input="tripRegionForm.features[k].logo = $event.target.files[0]"
                                    :imageurl="tripRegionForm.features[k].logo" accept="image/*" />
                                <span class="text-danger" v-if="tripRegionForm.errors['features.' + k + '.logo']">
                                    {{ tripRegionForm.errors['features.' + k + '.logo'] }}
                                </span>
                            </div>
                            <div class="col-lg-1">
                                <div class="fldaddbutn">
                                <a href="javascript:" class="btn btn-primary" @click="addTripRegionInput(k)">
                                    <span>+</span>
                                </a>
                                <a href="javascript:" class="btn btn-primary" @click="removeTripRegionInput(k)"
                                    v-if="tripRegionForm.features.length > 1">
                                    <span>-</span>
                                </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="form-group col-lg-3">
                    <submit-button :disabled="tripRegionForm.processing"
                        :isLoading="tripRegionForm.processing">Save</submit-button>
                </div>
            </form>

            <div class="col-md-12 col-lg-12">
                <h3>Operator Highlights</h3>
                <hr>
            </div>
            <form @submit.prevent="operatorHighlightsSubmit('operator-highlights')">
                <div class="form-group validated row">
                    <div class="form-group col-lg-4">
                        <label for="title">Title <span class="text-danger">*</span></label>
                        <!-- <input type="text" id="title" v-model="operatorHighlightForm.title"
                            class="form-control border-gray-200" placeholder="Title" /> -->
                            <ckeditor v-model="operatorHighlightForm.title" :config="editorConfig">
                            </ckeditor>
                        <span class="text-danger" v-if="operatorHighlightForm.errors.title">{{
                            operatorHighlightForm.errors.title
                        }}</span>
                    </div>
                    <div class="form-group col-lg-4">
                        <label for="subtitle">Sub Title <span class="text-danger">*</span></label>
                        <textarea type="text" id="subtitle" v-model="operatorHighlightForm.subtitle"
                            class="form-control border-gray-200" placeholder="Sub Title" rows="2"
                            style="resize: none;"> </textarea>
                        <span class="text-danger" v-if="operatorHighlightForm.errors.subtitle">{{
                            operatorHighlightForm.errors.subtitle
                        }}</span>
                    </div>
                    <div class="form-group col-lg-4">
                        <label for="icon">Thumbnail</label>
                        <FileUpload @input="operatorHighlightForm.thumbnail = $event.target.files[0]"
                            :imageurl="operatorHighlightForm.thumbnail_full_url" accept="image/*" />
                        <span class="text-danger" v-if="operatorHighlightForm.errors.thumbnail">
                            {{ operatorHighlightForm.errors.thumbnail }}
                        </span>
                    </div>
                </div>
                <div class="form-group col-lg-3">
                    <submit-button :disabled="operatorHighlightForm.processing"
                        :isLoading="operatorHighlightForm.processing">Save</submit-button>
                </div>
            </form>

            <div class="col-md-12 col-lg-12">
                <h3>Why Travel With Us</h3>
                <hr>
            </div>
            <form @submit.prevent="whyTravelSubmit('why-travel-with-us')">
                <div class="form-group validated row">
                    <div class="form-group col-lg-4">
                        <label for="title">Title <span class="text-danger">*</span></label>
                        <!-- <input type="text" id="title" v-model="whyTravelForm.title" class="form-control border-gray-200"
                            placeholder="Title" /> -->
                            <ckeditor v-model="whyTravelForm.title" :config="editorConfig">
                            </ckeditor>
                        <span class="text-danger" v-if="whyTravelForm.errors.title">{{
                            whyTravelForm.errors.title
                        }}</span>
                    </div>
                    <div class="form-group col-lg-4">
                        <label for="title">Text Content </label>
                        <ckeditor v-model="whyTravelForm.text_content" :config="editorConfig">
                        </ckeditor>
                        <span class="text-danger" v-if="whyTravelForm.errors.text_content">{{
                            whyTravelForm.errors.text_content
                        }}</span>
                    </div>

                    <div class="form-group col-lg-4">
                        <label for="icon">Thumbnail</label>
                        <FileUpload @input="whyTravelForm.thumbnail = $event.target.files[0]"
                            :imageurl="whyTravelForm.thumbnail_full_url" accept="image/*" />
                        <span class="text-danger" v-if="whyTravelForm.errors.thumbnail">
                            {{ whyTravelForm.errors.thumbnail }}
                        </span>
                    </div>

                    <div class="form-group col-lg-12">
                        <h5>Features:</h5>
                        <div class="row align-items-center" v-for="(input, k) in whyTravelForm.features" :key="k">
                            <div class="form-group col-lg-4">
                                <label for="title"> Title </label>
                                <input v-model="whyTravelForm.features[k].title" class="form-control border-gray-200"
                                    placeholder="Title" />
                                <span class="text-danger" v-if="whyTravelForm.errors['features.' + k + '.title']">
                                    {{ whyTravelForm.errors['features.' + k + '.title'] }}
                                </span>
                            </div>
                            <div class="form-group col-lg-4">
                                <label for="subtitle">Sub Title</label>
                                <textarea v-model="whyTravelForm.features[k].subtitle"
                                    class="form-control border-gray-200" placeholder="Sub Title" rows="2"
                                    style="resize: none;"> </textarea>
                                <span class="text-danger" v-if="whyTravelForm.errors['features.' + k + '.subtitle']">
                                    {{ whyTravelForm.errors['features.' + k + '.subtitle'] }}
                                </span>
                            </div>
                            <div class="form-group col-lg-3">
                                <label for="subtitle">Logo</label>
                                <FileUpload @input="whyTravelForm.features[k].logo = $event.target.files[0]"
                                    :imageurl="whyTravelForm.features[k].logo" accept="image/*" />
                                <span class="text-danger" v-if="whyTravelForm.errors['features.' + k + '.logo']">
                                    {{ whyTravelForm.errors['features.' + k + '.logo'] }}
                                </span>
                            </div>
                            <div class="col-lg-1">
                                <div class="fldaddbutn">
                                <a href="javascript:" class="btn btn-primary" @click="addWhyTravelInput(k)">
                                    <span>+</span>
                                </a>
                                <a href="javascript:" class="btn btn-primary" @click="removeWhyTravelInput(k)"
                                    v-if="whyTravelForm.features.length > 1">
                                    <span>-</span>
                                </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="form-group col-lg-3">
                    <submit-button :disabled="whyTravelForm.processing"
                        :isLoading="whyTravelForm.processing">Save</submit-button>
                </div>
            </form>

            <div class="col-md-12 col-lg-12">
                <h3>Why Tuskari Exists</h3>
                <hr>
            </div>
            <form @submit.prevent="whyTuskariExitsSubmit('why-tuskari-exists')">
                <div class="form-group validated row">
                    <div class="form-group col-lg-6">
                        <label for="title">Title <span class="text-danger">*</span></label>
                        <!-- <input type="text" id="title" v-model="whyTuskariExitsForm.title"
                            class="form-control border-gray-200" placeholder="Title" /> -->
                            <ckeditor v-model="whyTuskariExitsForm.title" :config="editorConfig">
                            </ckeditor>
                        <span class="text-danger" v-if="whyTuskariExitsForm.errors.title">{{
                            whyTuskariExitsForm.errors.title
                        }}</span>
                    </div>

                    <div class="form-group col-lg-6">
                        <label for="subtitle">Sub Title <span class="text-danger">*</span></label>
                        <input type="text" id="subtitle" v-model="whyTuskariExitsForm.subtitle"
                            class="form-control border-gray-200" placeholder="Sub Title" rows="2"
                            style="resize: none;" />
                        <span class="text-danger" v-if="whyTuskariExitsForm.errors.subtitle">{{
                            whyTuskariExitsForm.errors.subtitle
                        }}</span>
                    </div>
                    <div class="form-group col-lg-6">
                        <label for="title">Text Content </label>
                        <ckeditor v-model="whyTuskariExitsForm.text_content" :config="editorConfig1">
                        </ckeditor>
                        <span class="text-danger" v-if="whyTuskariExitsForm.errors.text_content">{{
                            whyTuskariExitsForm.errors.text_content
                            }}</span>
                    </div>

                    <div class="form-group col-lg-6">
                        <label for="icon">Thumbnail</label>
                        <FileUpload @input="whyTuskariExitsForm.thumbnail = $event.target.files[0]"
                            :imageurl="whyTuskariExitsForm.thumbnail_full_url" accept="image/*" />
                        <span class="text-danger" v-if="whyTuskariExitsForm.errors.thumbnail">
                            {{ whyTuskariExitsForm.errors.thumbnail }}
                        </span>
                    </div>
                </div>
                <div class="form-group col-lg-3">
                    <submit-button :disabled="whyTuskariExitsForm.processing"
                        :isLoading="whyTuskariExitsForm.processing">Save</submit-button>
                </div>
            </form>

            <div class="col-md-12 col-lg-12">
                <h3>Why Join Tuskari</h3>
                <hr>
            </div>
            <form @submit.prevent="whyJoinTuskariSubmit('why-join-tuskari')">
                <div class="form-group validated row">
                    <div class="form-group col-lg-6">
                        <label for="title">Title <span class="text-danger">*</span></label>
                        <!-- <input type="text" id="title" v-model="whyJoinTuskariForm.title"
                            class="form-control border-gray-200" placeholder="Title" /> -->
                            <ckeditor v-model="whyJoinTuskariForm.title" :config="editorConfig">
                            </ckeditor>
                        <span class="text-danger" v-if="whyJoinTuskariForm.errors.title">{{
                            whyJoinTuskariForm.errors.title
                        }}</span>
                    </div>

                    <div class="form-group col-lg-6">
                        <label for="subtitle">Subtitle <span class="text-danger">*</span></label>
                        <input type="text" id="subtitle" v-model="whyJoinTuskariForm.subtitle"
                            class="form-control border-gray-200" placeholder="subtitle" />
                        <span class="text-danger" v-if="whyJoinTuskariForm.errors.subtitle">{{
                            whyJoinTuskariForm.errors.subtitle
                        }}</span>
                    </div>


                    <div class="form-group col-lg-12">
                        <h5>Features:</h5>
                        <div class="row align-items-center" v-for="(input, k) in whyJoinTuskariForm.features" :key="k">
                            <div class="form-group col-lg-4">
                                <label for="title"> Title </label>
                                <input v-model="whyJoinTuskariForm.features[k].title"
                                    class="form-control border-gray-200" placeholder="Title" />
                                <span class="text-danger" v-if="whyJoinTuskariForm.errors['features.' + k + '.title']">
                                    {{ whyJoinTuskariForm.errors['features.' + k + '.title'] }}
                                </span>
                            </div>
                            <div class="form-group col-lg-4">
                                <label for="subtitle">Sub Title</label>
                                <textarea v-model="whyJoinTuskariForm.features[k].subtitle"
                                    class="form-control border-gray-200" placeholder="Sub Title" rows="2"
                                    style="resize: none;"></textarea>
                                <span class="text-danger"
                                    v-if="whyJoinTuskariForm.errors['features.' + k + '.subtitle']">
                                    {{ whyJoinTuskariForm.errors['features.' + k + '.subtitle'] }}
                                </span>
                            </div>
                            <div class="form-group col-lg-3">
                                <label for="subtitle">Logo</label>
                                <FileUpload @input="whyJoinTuskariForm.features[k].logo = $event.target.files[0]"
                                    :imageurl="whyJoinTuskariForm.features[k].logo" accept="image/*" />
                                <span class="text-danger" v-if="whyJoinTuskariForm.errors['features.' + k + '.logo']">
                                    {{ whyJoinTuskariForm.errors['features.' + k + '.logo'] }}
                                </span>
                            </div>
                            <div class="col-lg-1">
                                <div class="fldaddbutn">
                                <a v-if="whyJoinTuskariForm.features.length < 4" href="javascript:"
                                    class="btn btn-primary" @click="addWhyJoinTuskariInput(k)">
                                    <span>+</span>
                                </a>
                                <a href="javascript:" class="btn btn-primary" @click="removeWhyJoinTuskariInput(k)"
                                    v-if="whyJoinTuskariForm.features.length > 1">
                                    <span>-</span>
                                </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="form-group col-lg-3">
                    <submit-button :disabled="whyJoinTuskariForm.processing"
                        :isLoading="whyJoinTuskariForm.processing">Save</submit-button>
                </div>
            </form>


            <div class="col-md-12 col-lg-12">
                <h3>What You Can List More than just safaris.</h3>
                <hr>
            </div>
            <form @submit.prevent="whatYouCanListSubmit('what-you-can-list')">
                <div class="form-group validated row">
                    <div class="form-group col-lg-6">
                        <label for="title">Title <span class="text-danger">*</span></label>
                        <!-- <input type="text" id="title" v-model="whatYouCanListForm.title"
                            class="form-control border-gray-200" placeholder="Title" /> -->
                            <ckeditor v-model="whatYouCanListForm.title" :config="editorConfig">
                            </ckeditor>
                        <span class="text-danger" v-if="whatYouCanListForm.errors.title">{{
                            whatYouCanListForm.errors.title
                        }}</span>
                    </div>

                    <div class="form-group col-lg-6">
                        <label for="subtitle">Subtitle <span class="text-danger">*</span></label>
                        <input type="text" id="subtitle" v-model="whatYouCanListForm.subtitle"
                            class="form-control border-gray-200" placeholder="subtitle" />
                        <span class="text-danger" v-if="whatYouCanListForm.errors.subtitle">{{
                            whatYouCanListForm.errors.subtitle
                        }}</span>
                    </div>

                    <div class="form-group col-lg-6">
                        <label for="title">Text Content </label>
                        <ckeditor v-model="whatYouCanListForm.text_content" :config="editorConfig1">
                        </ckeditor>
                        <span class="text-danger" v-if="whatYouCanListForm.errors.text_content">{{
                            whatYouCanListForm.errors.text_content
                            }}</span>
                    </div>
                    <div class="form-group col-lg-4">
                        <label for="icon">Thumbnail</label>
                        <FileUpload @input="whatYouCanListForm.thumbnail = $event.target.files[0]"
                            :imageurl="whatYouCanListForm.thumbnail_full_url" accept="image/*" />
                        <span class="text-danger" v-if="whatYouCanListForm.errors.thumbnail">
                            {{ whatYouCanListForm.errors.thumbnail }}
                        </span>
                    </div>

                </div>
                <div class="form-group col-lg-3">
                    <submit-button :disabled="whatYouCanListForm.processing"
                        :isLoading="whatYouCanListForm.processing">Save</submit-button>
                </div>
            </form>


            <div class="col-md-12 col-lg-12">
                <h3>Build For You</h3>
                <hr>
            </div>
            <form @submit.prevent="buildForYouSubmit('built-for-you')">
                <div class="form-group validated row">
                    <div class="form-group col-lg-6">
                        <label for="title">Title <span class="text-danger">*</span></label>
                        <!-- <input type="text" id="title" v-model="buildForYouForm.title"
                            class="form-control border-gray-200" placeholder="Title" /> -->
                        <ckeditor v-model="buildForYouForm.title" :config="editorConfig">
                        </ckeditor>
                        <span class="text-danger" v-if="buildForYouForm.errors.title">{{
                            buildForYouForm.errors.title
                        }}</span>
                    </div>

                    <div class="form-group col-lg-6">
                        <label for="subtitle">Subtitle <span class="text-danger">*</span></label>
                        <input type="text" id="subtitle" v-model="buildForYouForm.subtitle"
                            class="form-control border-gray-200" placeholder="subtitle" />
                        <span class="text-danger" v-if="buildForYouForm.errors.subtitle">{{
                            buildForYouForm.errors.subtitle
                        }}</span>
                    </div>

                    <div class="form-group col-lg-6">
                        <label for="title">Text Content </label>
                        <ckeditor v-model="buildForYouForm.text_content" :config="editorConfig1">
                        </ckeditor>
                        <span class="text-danger" v-if="buildForYouForm.errors.text_content">{{
                            buildForYouForm.errors.text_content
                            }}</span>
                    </div>
                    <div class="form-group col-lg-4">
                        <label for="icon">Thumbnail</label>
                        <FileUpload @input="buildForYouForm.thumbnail = $event.target.files[0]"
                            :imageurl="buildForYouForm.thumbnail_full_url" accept="image/*" />
                        <span class="text-danger" v-if="buildForYouForm.errors.thumbnail">
                            {{ buildForYouForm.errors.thumbnail }}
                        </span>
                    </div>

                </div>
                <div class="form-group col-lg-3">
                    <submit-button :disabled="buildForYouForm.processing"
                        :isLoading="buildForYouForm.processing">Save</submit-button>
                </div>
            </form>

            <div class="col-md-12 col-lg-12">
                <h3>More of the money goes </h3>
                <hr>
            </div>
            <form @submit.prevent="moreMoneyGoesSubmit('more-of-the-money-goes')">
                <div class="form-group validated row">
                    <div class="form-group col-lg-6">
                        <label for="title">Title <span class="text-danger">*</span></label>
                        <!-- <input type="text" id="title" v-model="moreMoneyGoesForm.title"
                            class="form-control border-gray-200" placeholder="Title" /> -->
                        <ckeditor v-model="moreMoneyGoesForm.title" :config="editorConfig">    
                        </ckeditor>
                        <span class="text-danger" v-if="moreMoneyGoesForm.errors.title">{{
                            moreMoneyGoesForm.errors.title
                        }}</span>
                    </div>

                    <div class="form-group col-lg-6">
                        <label for="subtitle">Subtitle <span class="text-danger">*</span></label>
                        <input type="text" id="subtitle" v-model="moreMoneyGoesForm.subtitle"
                            class="form-control border-gray-200" placeholder="subtitle" />
                        <span class="text-danger" v-if="moreMoneyGoesForm.errors.subtitle">{{
                            moreMoneyGoesForm.errors.subtitle
                        }}</span>
                    </div>
                    <div class="form-group col-lg-4">
                        <label for="icon">Thumbnail</label>
                        <FileUpload @input="moreMoneyGoesForm.thumbnail = $event.target.files[0]"
                            :imageurl="moreMoneyGoesForm.thumbnail_full_url" accept="image/*" />
                        <span class="text-danger" v-if="moreMoneyGoesForm.errors.thumbnail">
                            {{ moreMoneyGoesForm.errors.thumbnail }}
                        </span>
                    </div>

                </div>
                <div class="form-group col-lg-3">
                    <submit-button :disabled="moreMoneyGoesForm.processing"
                        :isLoading="moreMoneyGoesForm.processing">Save</submit-button>
                </div>
            </form>

            <div class="col-md-12 col-lg-12">
                <h3>Where safari was born</h3>
                <hr>
            </div>

            <form @submit.prevent="whereSafariBornSubmit('where-safari-born')">
                <div class="form-group validated row">
                    <div class="form-group col-lg-6">
                        <label for="title">Title <span class="text-danger">*</span></label>
                        <!-- <input type="text" id="title" v-model="whereSafariBornForm.title"
                            class="form-control border-gray-200" placeholder="Title" /> -->
                        <ckeditor v-model="whereSafariBornForm.title" :config="editorConfig">    
                        </ckeditor>
                        <span class="text-danger" v-if="whereSafariBornForm.errors.title">{{
                            whereSafariBornForm.errors.title
                        }}</span>
                    </div>

                    <div class="form-group col-lg-6">
                        <label for="subtitle">Subtitle <span class="text-danger">*</span></label>
                        <input type="text" id="subtitle" v-model="whereSafariBornForm.subtitle"
                            class="form-control border-gray-200" placeholder="subtitle" />
                        <span class="text-danger" v-if="whereSafariBornForm.errors.subtitle">{{
                            whereSafariBornForm.errors.subtitle
                        }}</span>
                    </div>
                    <div class="form-group col-lg-4">
                        <label for="icon">Thumbnail</label>
                        <FileUpload @input="whereSafariBornForm.thumbnail = $event.target.files[0]"
                            :imageurl="whereSafariBornForm.thumbnail_full_url" accept="image/*" />
                        <span class="text-danger" v-if="whereSafariBornForm.errors.thumbnail">
                            {{ whereSafariBornForm.errors.thumbnail }}
                        </span>
                    </div>

                </div>
                <div class="form-group col-lg-3">
                    <submit-button :disabled="whereSafariBornForm.processing"
                        :isLoading="whereSafariBornForm.processing">Save</submit-button>
                </div>
            </form>


            <div class="col-md-12 col-lg-12">
                <h3>The Mara Region</h3>
                <hr>
            </div>

            <form @submit.prevent="maraRegionSubmit('mara-region')">
                <div class="form-group validated row">
                    <div class="form-group col-lg-6">
                        <label for="title">Title <span class="text-danger">*</span></label>
                        <!-- <input type="text" id="title" v-model="maraRegionForm.title"
                            class="form-control border-gray-200" placeholder="Title" /> -->
                        <ckeditor v-model="maraRegionForm.title" :config="editorConfig">    
                        </ckeditor>
                        <span class="text-danger" v-if="maraRegionForm.errors.title">{{
                            maraRegionForm.errors.title
                        }}</span>
                    </div>

                    <div class="form-group col-lg-6">
                        <label for="subtitle">Subtitle <span class="text-danger">*</span></label>
                        <input type="text" id="subtitle" v-model="maraRegionForm.subtitle"
                            class="form-control border-gray-200" placeholder="subtitle" />
                        <span class="text-danger" v-if="maraRegionForm.errors.subtitle">{{
                            maraRegionForm.errors.subtitle
                        }}</span>
                    </div>
                    <div class="form-group col-lg-4">
                        <label for="icon">Thumbnail</label>
                        <FileUpload @input="maraRegionForm.thumbnail = $event.target.files[0]"
                            :imageurl="maraRegionForm.thumbnail_full_url" accept="image/*" />
                        <span class="text-danger" v-if="maraRegionForm.errors.thumbnail">
                            {{ maraRegionForm.errors.thumbnail }}
                        </span>
                    </div>

                </div>
                <div class="form-group col-lg-3">
                    <submit-button :disabled="maraRegionForm.processing"
                        :isLoading="maraRegionForm.processing">Save</submit-button>
                </div>
            </form>


            <div class="col-md-12 col-lg-12">
                <h3>Laikipia and Northern Kenya</h3>
                <hr>
            </div>

            <form @submit.prevent="kenyaSubmit('laikipia-and-northern-kenya')">
                <div class="form-group validated row">
                    <div class="form-group col-lg-6">
                        <label for="title">Title <span class="text-danger">*</span></label>
                        <!-- <input type="text" id="title" v-model="kenyaForm.title" class="form-control border-gray-200"
                            placeholder="Title" /> -->
                        <ckeditor v-model="kenyaForm.title" :config="editorConfig">    
                        </ckeditor>
                        <span class="text-danger" v-if="kenyaForm.errors.title">{{
                            kenyaForm.errors.title
                        }}</span>
                    </div>

                    <div class="form-group col-lg-6">
                        <label for="subtitle">Subtitle <span class="text-danger">*</span></label>
                        <input type="text" id="subtitle" v-model="kenyaForm.subtitle"
                            class="form-control border-gray-200" placeholder="subtitle" />
                        <span class="text-danger" v-if="kenyaForm.errors.subtitle">{{
                            kenyaForm.errors.subtitle
                        }}</span>
                    </div>
                    <div class="form-group col-lg-4">
                        <label for="icon">Thumbnail</label>
                        <FileUpload @input="kenyaForm.thumbnail = $event.target.files[0]"
                            :imageurl="kenyaForm.thumbnail_full_url" accept="image/*" />
                        <span class="text-danger" v-if="kenyaForm.errors.thumbnail">
                            {{ kenyaForm.errors.thumbnail }}
                        </span>
                    </div>

                </div>
                <div class="form-group col-lg-3">
                    <submit-button :disabled="kenyaForm.processing"
                        :isLoading="kenyaForm.processing">Save</submit-button>
                </div>
            </form>

            <div class="col-md-12 col-lg-12">
                <h3>Kenyan Coast</h3>
                <hr>
            </div>

            <form @submit.prevent="kenyaCoastSubmit('kenyan-coast')">
                <div class="form-group validated row">
                    <div class="form-group col-lg-6">
                        <label for="title">Title <span class="text-danger">*</span></label>
                        <!-- <input type="text" id="title" v-model="kenyaCoastForm.title"
                            class="form-control border-gray-200" placeholder="Title" /> -->
                            <ckeditor v-model="kenyaCoastForm.title" :config="editorConfig">    
                            </ckeditor>
                        <span class="text-danger" v-if="kenyaCoastForm.errors.title">{{
                            kenyaCoastForm.errors.title
                        }}</span>
                    </div>

                    <div class="form-group col-lg-6">
                        <label for="subtitle">Subtitle <span class="text-danger">*</span></label>
                        <input type="text" id="subtitle" v-model="kenyaCoastForm.subtitle"
                            class="form-control border-gray-200" placeholder="subtitle" />
                        <span class="text-danger" v-if="kenyaCoastForm.errors.subtitle">{{
                            kenyaCoastForm.errors.subtitle
                        }}</span>
                    </div>
                    <div class="form-group col-lg-4">
                        <label for="icon">Thumbnail</label>
                        <FileUpload @input="kenyaCoastForm.thumbnail = $event.target.files[0]"
                            :imageurl="kenyaCoastForm.thumbnail_full_url" accept="image/*" />
                        <span class="text-danger" v-if="kenyaCoastForm.errors.thumbnail">
                            {{ kenyaCoastForm.errors.thumbnail }}
                        </span>
                    </div>

                </div>
                <div class="form-group col-lg-3">
                    <submit-button :disabled="kenyaCoastForm.processing"
                        :isLoading="kenyaCoastForm.processing">Save</submit-button>
                </div>
            </form>

            <div class="col-md-12 col-lg-12">
                <h3>Footer</h3>
                <hr>
            </div>
            <form @submit.prevent="footerSubmit('footer')">
                <div class="form-group validated row">
                    <div class="form-group col-lg-6">
                        <label for="title">Text Content </label>
                        <ckeditor v-model="footerForm.text_content" :config="editorConfig1">
                        </ckeditor>
                        <span class="text-danger" v-if="footerForm.errors.text_content">{{
                            footerForm.errors.text_content
                            }}</span>
                    </div>
                </div>
                <div class="form-group col-lg-3">
                    <submit-button :disabled="footerForm.processing"
                        :isLoading="footerForm.processing">Save</submit-button>
                </div>
            </form>

        </div>
    </div>
</template>

<script setup>
import { useForm } from "@inertiajs/vue3";
import SubmitButton from "@/components/SubmitButton.vue";
import { component as ckeditor } from "@mayasabha/ckeditor4-vue3";
import FileUpload from "@/components/FileUpload.vue";
import { onMounted } from "vue";


const editorConfig = {
    height: 120,
    resize_enabled: false,
    format_tags: 'p;h1;h2;h3;h4;h5;h6',
    toolbar: [
        { name: 'basicstyles', items: ['Bold', 'Italic'] },
        { name: 'styles', items: ['Format'] },
    ],
};
const editorConfig1 = {
    height: 220,
    resize_enabled: false,
    format_tags: 'p;h1;h2;h3;h4;h5;h6',
    toolbar: [
        { name: 'basicstyles', items: ['Bold', 'Italic'] },
        { name: 'styles', items: ['Format'] },
        { name: 'colors', items: ['FontColor', 'FontBackgroundColor'] }, // ✅ Add colors
        { name: 'lists', items: ['NumberedList', 'BulletedList'] },
    ],
};

const props = defineProps({
    errors: Object,
    pages: Object,
});

const whatIsTuskariForm = useForm({
    ...getPageData("what-is-tuskari"),
    thumbnail: null,
});

const howItsWorkForm = useForm({
    ...getPageData("how-it-works"),
    thumbnail: null,
    features: (() => {
        try {
            return JSON.parse(getPageData("how-it-works")?.features) || [{
                title: "",
                subtitle: "",
                logo: "",
            }];
        } catch {
            return [{
                title: "",
                subtitle: "",
                logo: "",
            }];
        }
    })(),
});

const whyBookWithTuskariForm = useForm({
    ...getPageData("why-book-with-tuskari"),
    thumbnail: null,
    features: (() => {
        try {
            return JSON.parse(getPageData("why-book-with-tuskari")?.features) || [{
                title: "",
                subtitle: "",
                logo: "",
            }];
        } catch {
            return [{
                title: "",
                subtitle: "",
                logo: "",
            }];
        }
    })(),
});

const tripRegionForm = useForm({
    ...getPageData("trip-regions"),
    thumbnail: null,
    features: (() => {
        try {
            return JSON.parse(getPageData("trip-regions")?.features) || [{
                title: "",
                subtitle: "",
                logo: "",
            }];
        } catch {
            return [{
                title: "",
                subtitle: "",
                logo: "",
            }];
        }
    })(),
});
const operatorHighlightForm = useForm({
    ...getPageData("operator-highlights"),
    thumbnail: null,
});

const whyTravelForm = useForm({
    ...getPageData("why-travel-with-us"),
    thumbnail: null,
    features: (() => {
        try {
            return JSON.parse(getPageData("why-travel-with-us")?.features) || [{
                title: "",
                subtitle: "",
                logo: "",
            }];
        } catch {
            return [{
                title: "",
                subtitle: "",
                logo: "",
            }];
        }
    })(),
});
const whyTuskariExitsForm = useForm({
    ...getPageData("why-tuskari-exists"),
    thumbnail: null,
});

const footerForm = useForm({
    ...getPageData("footer"),
    thumbnail: null,
});

const whyJoinTuskariForm = useForm({
    ...getPageData("why-join-tuskari"),
    features: (() => {
        try {
            return JSON.parse(getPageData("why-join-tuskari")?.features) || [{
                title: "",
                subtitle: "",
                logo: "",
            }];
        } catch {
            return [{
                title: "",
                subtitle: "",
                logo: "",
            }];
        }
    })(),
});

const whatYouCanListForm = useForm({
    ...getPageData("what-you-can-list"),
    thumbnail: null,
});

const buildForYouForm = useForm({
    ...getPageData("built-for-you"),
    thumbnail: null,
});

const moreMoneyGoesForm = useForm({
    ...getPageData("more-of-the-money-goes"),
    thumbnail: null,
});

const whereSafariBornForm = useForm({
    ...getPageData("where-safari-born"),
    thumbnail: null,
});

const maraRegionForm = useForm({
    ...getPageData("mara-region"),
    thumbnail: null,
});

const kenyaForm = useForm({
    ...getPageData("laikipia-and-northern-kenya"),
    thumbnail: null,
});

const kenyaCoastForm = useForm({
    ...getPageData("kenyan-coast"),
    thumbnail: null,
})

const addHowItsWorkInput = (index) => {
    howItsWorkForm.features.push({
        title: "",
        subtitle: "",
        logo: "",
    });
};

const removeHowItsWorkInput = (index) => {
    if (howItsWorkForm.features.length > 1) {
        howItsWorkForm.features.splice(index, 1);
    }
};

const addWhyTuskariInput = (index) => {
    whyBookWithTuskariForm.features.push({
        title: "",
        subtitle: "",
        logo: "",
    });
};

const removeWhyTuskariInput = (index) => {
    if (whyBookWithTuskariForm.features.length > 1) {
        whyBookWithTuskariForm.features.splice(index, 1);
    }
};

const addWhyJoinTuskariInput = (index) => {
    whyJoinTuskariForm.features.push({
        title: "",
        subtitle: "",
        logo: "",
    });
};
const removeWhyJoinTuskariInput = (index) => {
    if (whyJoinTuskariForm.features.length > 1) {
        whyJoinTuskariForm.features.splice(index, 1);
    }
}

const addTripRegionInput = (index) => {
    tripRegionForm.features.push({
        title: "",
        subtitle: "",
        image: "",
    });
};

const removeTripRegionInput = (index) => {
    if (tripRegionForm.features.length > 1) {
        tripRegionForm.features.splice(index, 1);
    }
};
const addWhyTravelInput = (index) => {
    whyTravelForm.features.push({
        title: "",
        subtitle: "",
        image: "",
    });
};

const removeWhyTravelInput = (index) => {
    if (whyTravelForm.features.length > 1) {
        whyTravelForm.features.splice(index, 1);
    }
};

/** Completed */
const whatIsTuskariSubmit = (slug) => {
    whatIsTuskariForm.post(route("admin.create-edit-pages", { slug: slug }), {
        preserveScroll: true,
    });
};

/** Completed */
const howItsWorkSubmit = (slug) => {
    howItsWorkForm.post(route("admin.create-edit-pages", { slug: slug }), {
        preserveScroll: true,
    });
};

const whyBookWithTuskariSubmit = (slug) => {
    whyBookWithTuskariForm.post(route("admin.create-edit-pages", { slug: slug }), {
        preserveScroll: true,
    });
};

const tripRegionSubmit = (slug) => {
    tripRegionForm.post(route("admin.create-edit-pages", { slug: slug }), {
        preserveScroll: true,
    });
};

const operatorHighlightsSubmit = (slug) => {
    operatorHighlightForm.post(route("admin.create-edit-pages", { slug: slug }), {
        preserveScroll: true,
    });
};

const whyTravelSubmit = (slug) => {
    whyTravelForm.post(route("admin.create-edit-pages", { slug: slug }), {
        preserveScroll: true,
    });
};

const whyTuskariExitsSubmit = (slug) => {
    whyTuskariExitsForm.post(route("admin.create-edit-pages", { slug: slug }), {
        preserveScroll: true,
    });
};

const footerSubmit = (slug) => {
    footerForm.post(route("admin.create-edit-pages", { slug: slug }), {
        preserveScroll: true,
    });
};

const whyJoinTuskariSubmit = (slug) => {
    whyJoinTuskariForm.post(route("admin.create-edit-pages", { slug: slug }), {
        preserveScroll: true,
    });
};

const whatYouCanListSubmit = (slug) => {
    whatYouCanListForm.post(route("admin.create-edit-pages", { slug: slug }), {
        preserveScroll: true,
    });
};

const buildForYouSubmit = (slug) => {
    buildForYouForm.post(route("admin.create-edit-pages", { slug: slug }), {
        preserveScroll: true,
    });
};

const moreMoneyGoesSubmit = (slug) => {
    moreMoneyGoesForm.post(route("admin.create-edit-pages", { slug: slug }), {
        preserveScroll: true,
    });
};

const whereSafariBornSubmit = (slug) => {
    whereSafariBornForm.post(route("admin.create-edit-pages", { slug: slug }), {
        preserveScroll: true,
    });
};

const maraRegionSubmit = (slug) => {
    maraRegionForm.post(route("admin.create-edit-pages", { slug: slug }), {
        preserveScroll: true,
    });
};

const kenyaSubmit = (slug) => {
    kenyaForm.post(route("admin.create-edit-pages", { slug: slug }), {
        preserveScroll: true,
    });
};

const kenyaCoastSubmit = (slug) => {
    kenyaCoastForm.post(route("admin.create-edit-pages", { slug: slug }), {
        preserveScroll: true,
    });
};

function getPageData(slug) {
    return props.pages?.find((page) => page.slug === slug) || {};
}

onMounted(() => {
    emit.emit("pageName", "Content Management", [
        {
            title: "Create Edit CMS Page",
            routeName: "admin.create-edit-pages",
        },
    ]);
})

</script>