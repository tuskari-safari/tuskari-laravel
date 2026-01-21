<template>
    <div class="panel_rght_cntnt_area each_equal_cntnr">
        <div class="trips_hder trips_header">
            <div class="hdr_lft_prt hdr-lft-backarw">
                <a :href="route('frontend.safari-manage-listing')" class="backarow"><i><img
                            :src="'/frontend_assets/images/back-blck-arrow.svg'" alt="Back Arrow"></i>
                    Back</a>
            </div>
        </div>
        <div class="crtelisting-hdwrap">
            <div class="crtelisting-heading text-center">To submit your safari successfully, you need to save each section as you go. Saving also allows you to return later and complete your listing
            </div>
        </div>

        <div class="srvcrtnw-updteform">
            <div class="form_box">
                <form @submit.prevent="submitListingForm(0)">
                    <div class="srvcrtnew-list-row">
                        <div class="srvcrtnew-list-lftcol">
                            <div class="srvcrtnew-lst-lftpnl oprt-crtelstng-pnl-nw">
                                <div class="srvcrtnw-updtinputbx">
                                    <label>Write Your Safari Title</label>
                                    <!-- {{ form.errors }} -->
                                    <div class="input_hldr">
                                        <input type="text" placeholder="Enter safari name or headline…" class=""
                                            v-model="form.title" name="title">
                                        <span class="text-danger" v-if="form.errors.title">
                                            {{ form.errors.title }}
                                        </span>
                                        <CharacterCounter :text="form.title" :maxlength="80" />
                                    </div>
                                </div>

                                <div class="srvcrtnw-updtinputbx">
                                    <label>Choose a Region</label>
                                    <div class="mulslect-drpdwn">
                                        <div class="parktype-searchbox">
                                            <Multiselect placeholder="Select Region" v-model="form.region_id"
                                                class="multislect-dropdwn multislect-cmn-adj" :close-on-select="true"
                                                :searchable="true" :create-option="false" :options="regions"
                                                @change="onRegionChange" />
                                        </div>
                                        <span class="text-danger" v-if="form.errors.region_id">{{ form.errors.region_id
                                        }}</span>
                                    </div>
                                </div>

                                <div class="srvcrtnw-updtinputbx">
                                    <label>Country</label>
                                    <div class="mulslect-drpdwn">
                                        <div class="parktype-searchbox">
                                            <Multiselect
                                                :placeholder="!form.region_id ? 'Select region first' : 'Select country'"
                                                v-model="form.country_id" :key="regionform.region_id"
                                                class="multislect-dropdwn multislect-cmn-adj" :close-on-select="true"
                                                :searchable="true" :create-option="false" :options="getCountries" />
                                        </div>
                                        <span class="text-danger" v-if="form.errors.country_id">{{
                                            form.errors.country_id }}</span>
                                    </div>
                                </div>
                                <div class="srvcrtnw-updtinputbx">
                                    <label>Select National Park / Reserve</label>
                                    <div class="mulslect-drpdwn">
                                        <div class="parktype-searchbox">
                                            <Multiselect v-model="form.national_parks" placeholder="Select"
                                                class="multislect-dropdwn" mode="tags" :close-on-select="true"
                                                :searchable="true" :create-option="false"
                                                :options="allNationalParkReserves" />
                                        </div>
                                        <span class="text-danger" v-if="form.errors.national_parks">
                                            {{ form.errors.national_parks }}
                                        </span>
                                    </div>
                                </div>

                                <div class="srvcrtnw-updtinputbx">
                                    <label>Safari Type</label>
                                    <div class="ftrppup-checkbx-rw">

                                        <div class="ftrppup-checkbx-col checkbx-col6"
                                            v-for="(safariType, index) in safariTypes" :key="index">
                                            <label class="ftrppup-chkbx-lbl">
                                                <input type="checkbox" :value="safariType.id" v-model="form.safariType">
                                                <span>{{ safariType?.title }}</span>
                                            </label>
                                        </div>
                                    </div>
                                    <span class="text-danger" v-if="form.errors.safariType">{{
                                        form.errors.safariType
                                        }}</span>
                                </div>

                                <div class="srvcrtnw-updtinputbx">
                                    <label>Travel Season</label>
                                    <div class="ftrppup-checkbx-rw">
                                        <div class="ftrppup-checkbx-col">
                                            <label class="ftrppup-chkbx-lbl">
                                                <input type="checkbox" name="green season" value="Green / Wet Season"
                                                    v-model="form.seasons">
                                                <span>Green / Wet Season</span>
                                            </label>
                                        </div>
                                        <div class="ftrppup-checkbx-col">
                                            <label class="ftrppup-chkbx-lbl">
                                                <input type="checkbox" name="dry season" value="Dry Season"
                                                    v-model="form.seasons">
                                                <span>Dry Season</span>
                                            </label>
                                        </div>

                                        <div class="ftrppup-checkbx-col">
                                            <label class="ftrppup-chkbx-lbl">
                                                <input type="checkbox" name="Great Migration season"
                                                    value="Great Migration Season" v-model="form.seasons">
                                                <span>Great Migration Season</span>
                                            </label>
                                        </div>

                                        <div class="ftrppup-checkbx-col">
                                            <label class="ftrppup-chkbx-lbl">
                                                <input type="checkbox" name="shoulder season" value="Shoulder Season"
                                                    v-model="form.seasons">
                                                <span>Shoulder Season​</span>
                                            </label>
                                        </div>
                                    </div>
                                    <span class="text-danger" v-if="form.errors.seasons">{{
                                        form.errors.seasons
                                        }}</span>
                                </div>

                                <div class="srvcrtnw-updtinputbx">
                                    <label>Booking Mode</label>
                                    <div class="ftrppup-checkbx-rw">
                                        <div class="ftrppup-checkbx-col">
                                            <label class="ftrppup-chkbx-lbl">
                                                <input type="radio" name="booking_mode" value="booking"
                                                    v-model="form.booking_mode">
                                                <span>Instant Booking</span>
                                            </label>
                                            <small class="text-muted d-block ms-4">Travelers can book and pay immediately</small>
                                        </div>
                                        <div class="ftrppup-checkbx-col">
                                            <label class="ftrppup-chkbx-lbl">
                                                <input type="radio" name="booking_mode" value="enquiry"
                                                    v-model="form.booking_mode">
                                                <span>Enquiry First</span>
                                            </label>
                                            <small class="text-muted d-block ms-4">Travelers must discuss with you before booking</small>
                                        </div>
                                    </div>
                                    <span class="text-danger" v-if="form.errors.booking_mode">{{
                                        form.errors.booking_mode
                                        }}</span>
                                </div>

                                <div class="srvcrtnw-updtinputbx">
                                    <label>Upload Cover Image</label>
                                    <div class="input_hldr file-input">
                                        <file-upload @input="form.thumbnail = $event.target.files[0]" accept="image/*"
                                            :imageurl="safariThumbnailFullUrl" />
                                        <template v-if="!form.thumbnail">
                                            <img :src="'/frontend_assets/images/up-input-file.svg'" alt="img"
                                                class="up-input-file">
                                            <p>Upload your main safari image (JPG/PNG)</p>
                                        </template>

                                        <div class="file-wrap">
                                            <div class="wrap-brows">
                                                <a href="#url" class="cmn-butn">Browse file</a>
                                            </div>
                                        </div>

                                    </div>
                                    <span class="text-danger" v-if="form.errors.thumbnail">
                                        {{ form.errors.thumbnail }}
                                    </span>
                                </div>

                                <div class="srvcrtnw-updtinputbx">
                                    <div class="row crtlst-prc-row">
                                        <div class="col-md-6 crtlst-prc-col">
                                            <div class="crtlst-prc-pnl">
                                                <label>Duration (Days)</label>
                                                <div class="input_hldr">
                                                    <input type="text" class="" placeholder="Duration (Days)"
                                                        v-model="form.day_duration"
                                                        @input="form.day_duration = form.day_duration.replace(/[^0-9]/g, '').replace(/^0+/, '')">
                                                </div>
                                                <span class="text-danger" v-if="form.errors.day_duration">
                                                    {{ form.errors.day_duration }}
                                                </span>
                                            </div>
                                        </div>
                                        <div class="col-md-6 crtlst-prc-col">
                                            <div class="crtlst-prc-pnl">
                                                <label>Duration (Nights)</label>
                                                <div class="input_hldr">
                                                    <input type="text" class="" v-model="form.night_duration"
                                                        placeholder="Duration (Nights)"
                                                        @input="form.night_duration = form.night_duration.replace(/[^0-9]/g, '').replace(/^0+/, '')">
                                                </div>
                                                <span class="text-danger" v-if="form.errors.night_duration">
                                                    {{ form.errors.night_duration }}
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="srv-cretenew-lstbutn">
                                    <button type="button" class="cmn-butn" @click="submitListingForm(1)" :disabled="form.processing">{{ safari?.step_saved_status?.[1] ? 'Saved' : 'Save' }}</button>
                                </div>
                            </div>
                        </div>

                        <div class="srvcrtnew-list-rgtcol">
                            <div class="srvcrtnew-list-rgtpnl" :class="hasSafariOverviewErrors ? 'has-error' : ''">
                                <Accordion value="0" expandIcon="pi pi-plus" collapseIcon="pi pi-minus">
                                    <AccordionPanel value="0">
                                        <AccordionHeader>
                                            <div class="srvcrtlst-tppnl">
                                                <div class="srvcrtlst-hd">Safari Overview</div>
                                                <button type="button" class="crtlst-colapsebutn"></button>
                                            </div>
                                        </AccordionHeader>
                                        <AccordionContent>
                                            <div class="crtlst-vwbtmpnl">
                                                <div class="srvcrtnw-updtinputbx">
                                                    <label>Description</label>
                                                    <div class="input_hldr">
                                                        <textarea placeholder="Describe your safari experience"
                                                            v-model="form.description"></textarea>
                                                        <span class="text-danger" v-if="form.errors.description">
                                                            {{ form.errors.description }}
                                                        </span>
                                                        <CharacterCounter :text="form.description" :maxlength="1500" />
                                                    </div>
                                                </div>

                                                <div class="srvcrtnw-updtinputbx">
                                                    <label>What makes this trip special</label>
                                                    <div class="input_hldr">
                                                        <ckeditor v-model="form.special_description"
                                                            :config="editorConfig">
                                                        </ckeditor>
                                                        <span class="text-danger"
                                                            v-if="form.errors.special_description">
                                                            {{ form.errors.special_description }}
                                                        </span>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="srv-cretenew-lstbutn">
                                                <button type="button" class="cmn-butn"
                                                    @click="submitListingForm(2)" :disabled="form.processing">{{ safari?.step_saved_status?.[2] ? 'Saved' : 'Save' }}</button>
                                            </div>
                                        </AccordionContent>
                                    </AccordionPanel>
                                </Accordion>
                            </div>

                            <div class="srvcrtnew-list-rgtpnl" :class="{ 'has-error': hasDayByDayErrors }">
                                <Accordion value="1" expandIcon="pi pi-plus" collapseIcon="pi pi-minus">
                                    <AccordionPanel value="0">
                                        <AccordionHeader>
                                            <div class="srvcrtlst-tppnl">
                                                <div class="srvcrtlst-hd">Your Safari Itinerary</div>
                                                <button type="button" class="crtlst-colapsebutn"></button>
                                            </div>
                                        </AccordionHeader>
                                        <AccordionContent>
                                            <div class="crtlst-vwbtmpnl">
                                                <div v-for="(day, index) in form.days"
                                                    :key="index">
                                                    <div class="jrny-day-formwrpr">
                                                        <div class="jrny-day-form-toppnl">
                                                            <div class="srvcrtlst-hd">Day {{ index + 1 }}</div>
                                                        </div>
                                                    </div>

                                                    <div class="srvcrtnw-updtinputbx">
                                                        <label>Title</label>
                                                        <div class="input_hldr">
                                                            <input type="text"
                                                                placeholder="Day title or highlight (e.g., Arrival in Arusha)"
                                                                v-model="day.heading">
                                                            <span class="text-danger"
                                                                v-if="form.errors[`days.${index}.heading`]">
                                                                {{ form.errors[`days.${index}.heading`] }}
                                                            </span>
                                                            <CharacterCounter :text="day.heading" :maxlength="100" />
                                                        </div>
                                                    </div>

                                                    <div class="srvcrtnw-updtinputbx">
                                                        <label>Description</label>
                                                        <div class="input_hldr"><textarea v-model="day.subtext"
                                                                placeholder="Describe today’s activities or highlights"></textarea>
                                                            <span class="text-danger"
                                                                v-if="form.errors[`days.${index}.subtext`]">
                                                                {{ form.errors[`days.${index}.subtext`] }}
                                                            </span>
                                                            <CharacterCounter :text="day.subtext" :maxlength="1500" />
                                                        </div>
                                                    </div>

                                                    <div class="srvcrtnw-updtinputbx">
                                                        <label>Today's Highlights</label>
                                                        <ckeditor v-model="day.today_highlights" :config="editorConfig">
                                                        </ckeditor>
                                                        <span class="text-danger"
                                                            v-if="form.errors[`days.${index}.today_highlights`]">
                                                            {{ form.errors[`days.${index}.today_highlights`] }}
                                                        </span>
                                                    </div>
                                                    <div class="srvcrtnw-updtinputbx">
                                                        <label>Meals & Drinks</label>
                                                        <div class="input_hldr">
                                                            <ckeditor v-model="day.meals" :config="editorConfig">
                                                            </ckeditor>
                                                            <span class="text-danger"
                                                                v-if="form.errors[`days.${index}.meals`]">
                                                                {{ form.errors[`days.${index}.meals`] }}
                                                            </span>
                                                        </div>
                                                    </div>
                                                    <div class="srvcrtnw-updtinputbx no-accommodation-lbel">
                                                        <label class="ftrppup-chkbx-lbl">
                                                            <input type="checkbox" placeholder="Accommodation"
                                                                v-model="day.no_accommodation_included">
                                                            <span>
                                                                No accommodation included</span>
                                                        </label>
                                                    </div>
                                                    <slide-up-down :active="!day.no_accommodation_included"
                                                        :duration="350">
                                                        <div class="srvcrtnw-updtinputbx acmdtn-nm-inputbox">
                                                            <label>
                                                                Lodge / Camp Name
                                                            </label>
                                                            <div class="input_hldr">
                                                                <input type="text" placeholder="Accommodation"
                                                                    v-model="day.accommodation">
                                                                <span class="text-danger"
                                                                    v-if="form.errors[`days.${index}.accommodation`]">
                                                                    {{ form.errors[`days.${index}.accommodation`] }}
                                                                </span>
                                                                <CharacterCounter :text="day.accommodation"
                                                                    :maxlength="100" />
                                                            </div>
                                                        </div>

                                                        <div class="srvcrtnw-updtinputbx acmdtn-imgbox">
                                                            <label>Upload Accommodation Images</label>
                                                            <div class="img-perday-fileupld">
                                                                <div class="file-up-prevw"
                                                                    v-if="day.image && day.image.length">
                                                                    <div class="file-up-prevw-row">
                                                                        <div class="file-up-prevw-item"
                                                                            v-for="(img, imgIndex) in day.image"
                                                                            :key="imgIndex">
                                                                            <img :src="img.preview" alt=""
                                                                                class="file-up-prevw-item-img" />
                                                                            <button class="file-up-prevw-item-close"
                                                                                @click.prevent="removeImage(day, imgIndex)">
                                                                                <img :src="'/frontend_assets/images/close.svg'"
                                                                                    alt="close img" />
                                                                            </button>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="input_hldr file-input">
                                                                    <input type="file" multiple accept="image/*"
                                                                        :disabled="day.image.length >= 3"
                                                                        @change="handleDayImageUpload($event, day)" />

                                                                    <img :src="'/frontend_assets/images/up-input-file.svg'"
                                                                        alt="img" class="up-input-file">
                                                                    <p>Upload your images (max 3)</p>
                                                                    <div class="file-wrap">
                                                                        <div class="wrap-brows">
                                                                            <a href="#url" class="cmn-butn">Browse
                                                                                file</a>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <span class="text-danger"
                                                                v-if="form.errors[`days.${index}.image`]">
                                                                {{ form.errors[`days.${index}.image`] }}
                                                            </span>
                                                        </div>
                                                    </slide-up-down>

                                                    <div class="srvcrtnw-updtinputbx">
                                                        <label>Wildlife location</label>
                                                        <div class="input_hldr">
                                                            <Multiselect placeholder="Select"
                                                                v-model="day.wildlife_location"
                                                                class="multislect-dropdwn multislect-cmn-adj"
                                                                :close-on-select="true" :searchable="true"
                                                                :create-option="false"
                                                                :options="allNationalParkReserves" />
                                                            <span class="text-danger"
                                                                v-if="form.errors[`days.${index}.wildlife_location`]">
                                                                {{ form.errors[`days.${index}.wildlife_location`] }}
                                                            </span>
                                                        </div>
                                                    </div>

                                                    <div v-for="(animal, aIndex) in day.animals"
                                                        class="mb-3" :key="aIndex">
                                                        <div class="row crtlst-prc-row">
                                                            <div class="col-md-12 crtlst-prc-col">
                                                                <div class="crtlst-prc-pnl">
                                                                    <div class="input_hldr">
                                                                        <label>Wildlife species</label>
                                                                        <Multiselect placeholder="Select"
                                                                            v-model="animal.animal_id"
                                                                            class="multislect-dropdwn multislect-cmn-adj"
                                                                            :close-on-select="true" :searchable="true"
                                                                            :create-option="false"
                                                                            :options="wildlives" />
                                                                        <span class="text-danger"
                                                                            v-if="form.errors[`days.${index}.animals.${aIndex}.animal_id`]">
                                                                            {{form.errors[`days.${index}.animals.${aIndex}.animal_id`]}}
                                                                        </span>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-12 crtlst-prc-col">
                                                                <div class="crtlst-prc-pnl">
                                                                    <div class="input_hldr prbablty-prcnt-hldr">
                                                                        <label>Probability</label>
                                                                        <div class="input_hldr">
                                                                            <Multiselect placeholder="Select"
                                                                                v-model="animal.description"
                                                                                class="multislect-dropdwn"
                                                                                :close-on-select="true"
                                                                                :searchable="true"
                                                                                :create-option="false" :options="[
                                                                                    { value: 'Low Chance', label: 'Low Chance' },
                                                                                    { value: 'Medium Chance', label: 'Medium Chance' },
                                                                                    { value: 'High Chance', label: 'High Chance' },
                                                                                    { value: 'Very High Chance', label: 'Very High Chance' }
                                                                                ]" />

                                                                            <span class="text-danger"
                                                                                v-if="form.errors[`days.${index}.animals.${aIndex}.description`]">
                                                                                {{
                                                                                    form.errors[`days.${index}.animals.${aIndex}.description`]
                                                                                }}
                                                                            </span>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>

                                                        <div class="wldlife-anser-tophd mt-3">
                                                            <div class="add-wldlfe-butnbx">
                                                                <a href="javascript:void(0)"
                                                                    class="add-jrny-day-fldbutn"
                                                                    @click.prevent="addAnimal(index)">Add more</a>
                                                                <a href="javascript:void(0)"
                                                                    v-if="day.animals.length > 1" class="wldlfedlteicon"
                                                                    @click.prevent="removeAnimal(index, aIndex)"><img
                                                                        :src="'/frontend_assets/images/dlteicon.svg'"
                                                                        alt="Delete"> </a>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="jrny-day-formwrpr">
                                                        <div class="jrny-day-form-toppnl jnyday-adbutnbox">
                                                            <a href="javascript:void(0);" class="add-jrny-day-fldbutn"
                                                                @click="addDay(index)">Add
                                                                day</a>
                                                            <a href="javascript:void(0);" v-if="form.days.length > 1"
                                                                class="wldlfedlteicon" @click="removeDay(index)"><img
                                                                    :src="'/frontend_assets/images/dlteicon.svg'"
                                                                    alt="Delete"></a>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="srv-cretenew-lstbutn">
                                                <button type="button" class="cmn-butn"
                                                    @click="submitListingForm(3)" :disabled="form.processing">{{ safari?.step_saved_status?.[3] ? 'Saved' : 'Save' }}</button>
                                            </div>
                                        </AccordionContent>
                                    </AccordionPanel>
                                </Accordion>
                            </div>

                            <div class="srvcrtnew-list-rgtpnl" :class="{ 'has-error': hasIncludedNotIncludedErrors }">
                                <Accordion value="2" expandIcon="pi pi-plus" collapseIcon="pi pi-minus">
                                    <AccordionPanel value="0">
                                        <AccordionHeader>
                                            <div class="srvcrtlst-tppnl">
                                                <div class="srvcrtlst-hd">What's Included/Not Included</div>
                                                <button type="button" class="crtlst-colapsebutn"></button>
                                            </div>
                                        </AccordionHeader>
                                        <AccordionContent>
                                            <div class="srvcrtnw-updtinputbx">
                                                <label>Whats Included (Bullet format)</label>
                                                <div class="input_hldr">
                                                    <ckeditor v-model="form.safariIncluded" :config="editorConfig">
                                                    </ckeditor>

                                                    <span class="text-danger" v-if="form.errors.safariIncluded">
                                                        {{ form.errors.safariIncluded }}
                                                    </span>
                                                </div>
                                            </div>
                                            <div class="srvcrtnw-updtinputbx">
                                                <label>Whats Not Included (Bullet Format)</label>
                                                <div class="input_hldr">
                                                    <ckeditor v-model="form.safariNotIncluded" :config="editorConfig">
                                                    </ckeditor>

                                                    <span class="text-danger" v-if="form.errors.safariNotIncluded">
                                                        {{ form.errors.safariNotIncluded }}
                                                    </span>
                                                </div>
                                            </div>
                                            <div class="srv-cretenew-lstbutn">
                                                <button type="button" class="cmn-butn"
                                                    @click="submitListingForm(4)" :disabled="form.processing">{{ safari?.step_saved_status?.[4] ? 'Saved' : 'Save' }}</button>
                                            </div>
                                        </AccordionContent>
                                    </AccordionPanel>
                                </Accordion>
                            </div>

                            <div class="srvcrtnew-list-rgtpnl" :class="{ 'has-error': hasActivityErrors }">
                                <Accordion value="2" expandIcon="pi pi-plus" collapseIcon="pi pi-minus">
                                    <AccordionPanel value="0">
                                        <AccordionHeader>
                                            <div class="srvcrtlst-tppnl">
                                                <div class="srvcrtlst-hd">Activities</div>
                                                <button type="button" class="crtlst-colapsebutn"></button>
                                            </div>
                                        </AccordionHeader>
                                        <AccordionContent>
                                            <div class="crtlst-vwbtmpnl">
                                                <div class="srvcrtnw-updtinputbx animal-fldwrpr">
                                                    <label>Select Activities</label>
                                                    <div class="ftrppup-checkbx-rw actvity-checkbx">
                                                        <div class="ftrppup-checkbx-col checkbx-col6"
                                                            v-for="(activity, index) in activities" :key="index">
                                                            <label class="ftrppup-chkbx-lbl">
                                                                <input type="checkbox" :value="activity.id"
                                                                    v-model="form.activities">
                                                                <span><i class="activity-icon"><img
                                                                            :src="activity?.full_icon_url"
                                                                            alt="Icon"></i>
                                                                    {{ activity?.title }}</span>
                                                            </label>
                                                        </div>

                                                    </div>
                                                </div>
                                                <span class="text-danger" v-if="form.errors.activities">
                                                    {{ form.errors.activities }}
                                                </span>
                                            </div>
                                            <div class="srv-cretenew-lstbutn">
                                                <button type="button" class="cmn-butn"
                                                    @click="submitListingForm(5)" :disabled="form.processing">{{ safari?.step_saved_status?.[5] ? 'Saved' : 'Save' }}</button>
                                            </div>
                                        </AccordionContent>
                                    </AccordionPanel>
                                </Accordion>
                            </div>

                            <div class="srvcrtnew-list-rgtpnl" :class="{ 'has-error': hasLandscapeErrors }">
                                <Accordion value="2" expandIcon="pi pi-plus" collapseIcon="pi pi-minus">
                                    <AccordionPanel value="0">
                                        <AccordionHeader>
                                            <div class="srvcrtlst-tppnl">
                                                <div class="srvcrtlst-hd">Ecosystems / Landscapes</div>
                                                <button type="button" class="crtlst-colapsebutn"></button>
                                            </div>
                                        </AccordionHeader>
                                        <AccordionContent>
                                            <div class="crtlst-vwbtmpnl">
                                                <div class="srvcrtnw-updtinputbx animal-fldwrpr">
                                                    <label>Ecosystem type</label>
                                                    <Multiselect placeholder="Select ecosystem type"
                                                        v-model="form.environment" class="multislect-dropdwn"
                                                        mode="tags" :close-on-select="true" :searchable="true"
                                                        :create-option="false" :options="[
                                                            { value: 'Rainforest', label: 'Rainforest' },
                                                            { value: 'Savannah', label: 'Savannah' },
                                                            { value: 'Desert', label: 'Desert' },
                                                            { value: 'Wetlands', label: 'Wetlands' },
                                                            { value: 'Mountains', label: 'Mountains' },
                                                            { value: 'Rivers / Delta', label: 'Rivers / Delta' },
                                                            { value: 'Coastal', label: 'Coastal' },
                                                            { value: 'Crater / Caldera', label: 'Crater / Caldera' },
                                                            { value: 'Open Plains', label: 'Open Plains' }
                                                        ]" />
                                                    <span class="text-danger" v-if="form.errors.environment">
                                                        {{ form.errors.environment }}
                                                    </span>
                                                </div>
                                            </div>
                                            <div class="srv-cretenew-lstbutn">
                                                <button type="button" class="cmn-butn"
                                                    @click="submitListingForm(6)" :disabled="form.processing">{{ safari?.step_saved_status?.[6] ? 'Saved' : 'Save' }}</button>
                                            </div>
                                        </AccordionContent>
                                    </AccordionPanel>
                                </Accordion>
                            </div>

                            <div class="srvcrtnew-list-rgtpnl" :class="{ 'has-error': hasActivityLevelErrors }">
                                <Accordion value="2" expandIcon="pi pi-plus" collapseIcon="pi pi-minus">
                                    <AccordionPanel value="0">
                                        <AccordionHeader>
                                            <div class="srvcrtlst-tppnl">
                                                <div class="srvcrtlst-hd">Activity Level</div>
                                                <button type="button" class="crtlst-colapsebutn"></button>
                                            </div>
                                        </AccordionHeader>
                                        <AccordionContent>
                                            <div class="crtlst-vwbtmpnl">
                                                <div class="srvcrtnw-updtinputbx animal-fldwrpr">
                                                    <label>Activity Level</label>
                                                    <div class="ftrppup-checkbx-rw">
                                                        <div class="ftrppup-checkbx-col">
                                                            <label class="ftrppup-chkbx-lbl">
                                                                <input type="radio" name="name" value="Relaxed"
                                                                    v-model="form.activityLevel">
                                                                <span>Relaxed</span>
                                                            </label>
                                                        </div>
                                                        <div class="ftrppup-checkbx-col">
                                                            <label class="ftrppup-chkbx-lbl">
                                                                <input type="radio" name="name" value="Moderate"
                                                                    v-model="form.activityLevel">
                                                                <span>Moderate</span>
                                                            </label>
                                                        </div>

                                                        <div class="ftrppup-checkbx-col">
                                                            <label class="ftrppup-chkbx-lbl">
                                                                <input type="radio" name="name" value="Active"
                                                                    v-model="form.activityLevel">
                                                                <span>Active</span>
                                                            </label>
                                                        </div>
                                                    </div>
                                                </div>
                                                <span class="text-danger" v-if="form.errors.activityLevel">
                                                    {{ form.errors.activityLevel }}
                                                </span>
                                            </div>
                                            <div class="srv-cretenew-lstbutn">
                                                <button type="button" class="cmn-butn"
                                                    @click="submitListingForm(7)" :disabled="form.processing">{{ safari?.step_saved_status?.[7] ? 'Saved' : 'Save' }}</button>
                                            </div>
                                        </AccordionContent>
                                    </AccordionPanel>
                                </Accordion>
                            </div>

                            <div class="srvcrtnew-list-rgtpnl" :class="{ 'has-error': hasTravellerGroupErrors }">
                                <Accordion value="2" expandIcon="pi pi-plus" collapseIcon="pi pi-minus">
                                    <AccordionPanel value="0">
                                        <AccordionHeader>
                                            <div class="srvcrtlst-tppnl">
                                                <div class="srvcrtlst-hd">Traveller Capacity</div>
                                                <button type="button" class="crtlst-colapsebutn"></button>
                                            </div>
                                        </AccordionHeader>
                                        <AccordionContent>
                                            <div class="row crtlst-prc-row">
                                                <div class="col-md-6 crtlst-prc-col">
                                                    <div class="crtlst-prc-pnl">
                                                        <div class="input_hldr">
                                                            <label>Max Adults per Group</label>
                                                            <input type="text" name="name" placeholder="e.g. 5"
                                                                v-model="form.numberOfAdults"
                                                                @input="form.numberOfAdults = form.numberOfAdults.replace(/[^0-9]/g, '').replace(/^0+/, '')">
                                                            <span class="text-danger" v-if="form.errors.numberOfAdults">
                                                                {{ form.errors.numberOfAdults }}
                                                            </span>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-6 crtlst-prc-col">
                                                    <div class="crtlst-prc-pnl">
                                                        <div class="input_hldr">
                                                            <label>Max Children per Group</label>
                                                            <input type="text" name="name" placeholder="e.g. 5"
                                                                v-model="form.numberOfChildren"
                                                                @input="form.numberOfChildren = form.numberOfChildren.replace(/[^0-9]/g, '').replace(/^0+/, '')">
                                                            <span class="text-danger"
                                                                v-if="form.errors.numberOfChildren">
                                                                {{ form.errors.numberOfChildren }}
                                                            </span>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-12 crtlst-prc-col">
                                                    <div class="crtlst-prc-pnl">
                                                        <div class="input_hldr">
                                                            <label>Max Total Guests per Departure</label>
                                                            <input type="text" name="name" placeholder="e.g. 10"
                                                                v-model="form.numberOfGroups"
                                                                @input="form.numberOfGroups = form.numberOfGroups.replace(/[^0-9]/g, '').replace(/^0+/, '')">
                                                            <span class="text-danger" v-if="form.errors.numberOfGroups">
                                                                {{ form.errors.numberOfGroups }}
                                                            </span>
                                                        </div>
                                                    </div>
                                                </div>
                                                <!-- <div class="col-md-12 crtlst-prc-col">
                                                    <div class="crtlst-prc-pnl">
                                                        <div class="input_hldr prbablty-prcnt-hldr">
                                                            <label>Group Type</label>
                                                            <Multiselect placeholder="Select group type"
                                                                v-model="form.groupType"
                                                                class="multislect-dropdwn multislect-cmn-adj"
                                                                :close-on-select="true" :searchable="true"
                                                                :create-option="false" :options="[
                                                                    { value: 'Group', label: 'Group' },
                                                                    { value: 'Private', label: 'Private' },
                                                                    { value: 'Both', label: 'Both' },

                                                                ]" />
                                                            <span class="text-danger" v-if="form.errors.groupType">
                                                                {{ form.errors.groupType }}
                                                            </span>
                                                        </div>
                                                    </div>
                                                </div> -->
                                            </div>
                                            <div class="srv-cretenew-lstbutn">
                                                <button type="button" class="cmn-butn"
                                                    @click="submitListingForm(8)" :disabled="form.processing">{{ safari?.step_saved_status?.[8] ? 'Saved' : 'Save' }}</button>
                                            </div>
                                        </AccordionContent>
                                    </AccordionPanel>
                                </Accordion>
                            </div>

                            <div class="srvcrtnew-list-rgtpnl" :class="{ 'has-error': hasAvailabilityErrors }">
                                <Accordion value="2" expandIcon="pi pi-plus" collapseIcon="pi pi-minus">
                                    <AccordionPanel value="0">
                                        <AccordionHeader>
                                            <div class="srvcrtlst-tppnl">
                                                <div class="srvcrtlst-hd">Seasonal Availability & Pricing </div>
                                                <button type="button" class="crtlst-colapsebutn"></button>
                                            </div>
                                        </AccordionHeader>
                                        <AccordionContent>
                                            <div class="group-pricing-description">Define per-person net prices based on
                                                traveller type, group
                                                size, and season. Add multiple rows for flexible tiered pricing.</div>
                                            <div v-for="(dateRangeItem, index) in form.dateRange" :key="index"
                                                class="mb-4">
                                                <div class="jrny-day-formwrpr">
                                                    <div class="jrny-day-form-toppnl">
                                                        <div class="srvcrtlst-hd">Date Range {{ index + 1 }}</div>
                                                    </div>
                                                </div>
                                                <div class="row nw-jrny-day-formwrpr-row updtlist-avlbledterow">
                                                    <div class="col-md-6 cre-range-clndrcol">
                                                        <div
                                                            class="crtlst-vwbtmpnl cmn-caldr-adj full-width-clndr avlbledate-updt available-clndr-date">
                                                            <div class="srvcrtnw-updtinputbx animal-fldwrpr">
                                                                <span>Low Season Available Dates
                                                                </span>
                                                            </div>
                                                            <div class="bnrfrm-inputwrp date datefrmat">
                                                            <span class="fw-semibold">Selected date: {{ formatDateRange(dateRangeItem.lowSeasonAvailableDates) }}</span>
                                                                <DateRange :minDate="new Date()" :format="formatDate"
                                                                    inline
                                                                    v-model="dateRangeItem.lowSeasonAvailableDates" />
                                                            </div>
                                                            <span class="text-danger"
                                                                v-if="form.errors[`dateRange.${index}.lowSeasonAvailableDates`]">
                                                                {{
                                                                    form.errors[`dateRange.${index}.lowSeasonAvailableDates`]
                                                                }}
                                                            </span>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6 cre-range-clndrcol">
                                                        <div
                                                            class="crtlst-vwbtmpnl cmn-caldr-adj full-width-clndr avlbledate-updt block-clndr-date">
                                                            <div
                                                                class="srvcrtnw-updtinputbx animal-fldwrpr clnder-blockdate">
                                                                <span>Low Season Blocked Dates</span>
                                                            </div>
                                                            <div
                                                                class="bnrfrm-inputwrp date datefrmat unavailable-clndr-date">
                                                            <span class="fw-semibold">Selected date: {{ formatDateRange(dateRangeItem.lowSeasonBlockedDates) }}</span>
                                                                <DateRange :minDate="new Date()" :format="formatDate"
                                                                    inline
                                                                    v-model="dateRangeItem.lowSeasonBlockedDates" />
                                                            </div>
                                                            <span class="text-danger"
                                                                v-if="form.errors[`dateRange.${index}.lowSeasonBlockedDates`]">
                                                                {{
                                                                    form.errors[`dateRange.${index}.lowSeasonBlockedDates`]
                                                                }}
                                                            </span>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="row nw-jrny-day-formwrpr-row updtlist-avlbledterow">
                                                    <div class="col-md-6 cre-range-clndrcol">
                                                        <div
                                                            class="crtlst-vwbtmpnl cmn-caldr-adj full-width-clndr avlbledate-updt available-clndr-date">
                                                            <div class="srvcrtnw-updtinputbx animal-fldwrpr">
                                                                <span>High Season Available Dates
                                                                </span>
                                                            </div>

                                                            <div class="bnrfrm-inputwrp date datefrmat">
                                                                 <span class="fw-semibold">Selected date: {{ formatDateRange(dateRangeItem.highSeasonAvailableDates) }}</span>
                                                                <DateRange :minDate="new Date()" :format="formatDate"
                                                                    inline
                                                                    v-model="dateRangeItem.highSeasonAvailableDates" />
                                                            </div>
                                                            <span class="text-danger"
                                                                v-if="form.errors[`dateRange.${index}.highSeasonAvailableDates`]">
                                                                {{
                                                                    form.errors[`dateRange.${index}.highSeasonAvailableDates`]
                                                                }}
                                                            </span>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6 cre-range-clndrcol">
                                                        <div
                                                            class="crtlst-vwbtmpnl cmn-caldr-adj full-width-clndr avlbledate-updt block-clndr-date">
                                                            <div
                                                                class="srvcrtnw-updtinputbx animal-fldwrpr clnder-blockdate">
                                                                <span>High Season Blocked Dates</span>
                                                            </div>
                                                            
                                                            <div
                                                                class="bnrfrm-inputwrp date datefrmat unavailable-clndr-date">
                                                                <span class="fw-semibold">Selected date: {{ formatDateRange(dateRangeItem.highSeasonBlockedDates) }}</span>
                                                                <DateRange :minDate="new Date()" :format="formatDate"
                                                                    inline
                                                                    v-model="dateRangeItem.highSeasonBlockedDates" />
                                                            </div>
                                                            <span class="text-danger"
                                                                v-if="form.errors[`dateRange.${index}.highSeasonBlockedDates`]">
                                                                {{
                                                                    form.errors[`dateRange.${index}.highSeasonBlockedDates`]
                                                                }}
                                                            </span>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="row crtlst-prc-row crtlst-vwbtmpnl pt-3">
                                                    <div class="col-md-6 crtlst-prc-col">
                                                        <div class="crtlst-prc-pnl">
                                                            <div class="input_hldr">
                                                                <label>Price per Adult (Low Season)</label>
                                                                <input type="text" name="name" placeholder="e.g. 5"
                                                                    v-model="dateRangeItem.lowSeasonAdultPrice" @input="() => {
                                                                        dateRangeItem.lowSeasonAdultPrice = dateRangeItem.lowSeasonAdultPrice.replace(/[^0-9.]/g, '').replace(/^0+(?!\.)/, '').replace(/(\..*)\./g, '$1');
                                                                        validateAdultLowHighSeasonPrice(index);
                                                                    }">
                                                                <span class="text-danger"
                                                                    v-if="form.errors[`dateRange.${index}.lowSeasonAdultPrice`]">
                                                                    {{
                                                                        form.errors[`dateRange.${index}.lowSeasonAdultPrice`]
                                                                    }}
                                                                </span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6 crtlst-prc-col">
                                                        <div class="crtlst-prc-pnl">
                                                            <div class="input_hldr">
                                                                <label>Price per Adult (High Season)</label>
                                                                <input type="text" name="name" placeholder="e.g. 5"
                                                                    v-model="dateRangeItem.highSeasonAdultPrice" @input="() => {
                                                                        dateRangeItem.highSeasonAdultPrice = dateRangeItem.highSeasonAdultPrice.replace(/[^0-9.]/g, '').replace(/^0+(?!\.)/, '').replace(/(\..*)\./g, '$1');
                                                                        validateAdultLowHighSeasonPrice(index);
                                                                    }">
                                                                <span class="text-danger"
                                                                    v-if="form.errors[`dateRange.${index}.highSeasonAdultPrice`]">
                                                                    {{
                                                                        form.errors[`dateRange.${index}.highSeasonAdultPrice`]
                                                                    }}
                                                                </span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6 crtlst-prc-col">
                                                        <div class="crtlst-prc-pnl">
                                                            <div class="input_hldr">
                                                                <label>Price per Child (Low Season)</label>
                                                                <input type="text" name="name" placeholder="e.g. 5"
                                                                    v-model="dateRangeItem.lowSeasonChildPrice" @input="() => {
                                                                        dateRangeItem.lowSeasonChildPrice = dateRangeItem.lowSeasonChildPrice.replace(/[^0-9.]/g, '').replace(/^0+(?!\.)/, '').replace(/(\..*)\./g, '$1');
                                                                        validateChildLowHighSeasonPrice(index);
                                                                    }">
                                                                <span class="text-danger"
                                                                    v-if="form.errors[`dateRange.${index}.lowSeasonChildPrice`]">
                                                                    {{
                                                                        form.errors[`dateRange.${index}.lowSeasonChildPrice`]
                                                                    }}
                                                                </span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6 crtlst-prc-col">
                                                        <div class="crtlst-prc-pnl">
                                                            <div class="input_hldr">
                                                                <label>Price per Child (High Season)</label>
                                                                <input type="text" name="name" placeholder="e.g. 5"
                                                                    v-model="dateRangeItem.highSeasonChildPrice" @input="() => {
                                                                        dateRangeItem.highSeasonChildPrice = dateRangeItem.highSeasonChildPrice.replace(/[^0-9.]/g, '').replace(/^0+(?!\.)/, '').replace(/(\..*)\./g, '$1');
                                                                        validateChildLowHighSeasonPrice(index);
                                                                    }">
                                                                <span class="text-danger"
                                                                    v-if="form.errors[`dateRange.${index}.highSeasonChildPrice`]">
                                                                    {{
                                                                        form.errors[`dateRange.${index}.highSeasonChildPrice`]
                                                                    }}
                                                                </span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="mt-3">
                                                    <a href="javascript:void(0);" class="add-jrny-day-fldbutn "
                                                        @click="addDateRange()">Add
                                                        another season</a>
                                                    <a href="javascript:void(0);" v-if="form.dateRange.length > 1"
                                                        class="wldlfedlteicon" @click="removeDateRange(index)"><img
                                                            :src="'/frontend_assets/images/dlteicon.svg'"
                                                            alt="Delete"></a>
                                                </div>
                                            </div>

                                            <div class="row crtlst-prc-row crtlst-vwbtmpnl pt-3">
                                                <div class="col-md-12 crtlst-prc-col">
                                                    <div class="crtlst-prc-pnl">
                                                        <div class="input_hldr">
                                                            <label>Max Bookings per Date</label>
                                                            <input type="text" name="name"
                                                                placeholder="e.g. 3 groups per day"
                                                                v-model="form.perDateGroupLimit"
                                                                @input="form.perDateGroupLimit = form.perDateGroupLimit.replace(/[^0-9]/g, '').replace(/^0+/, '')">
                                                            <span class="text-danger"
                                                                v-if="form.errors.perDateGroupLimit">
                                                                {{ form.errors.perDateGroupLimit }}
                                                            </span>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="col-md-12 crtlst-prc-col">
                                                    <div class="crtlst-prc-pnl">
                                                        <div class="input_hldr">
                                                            <label>Tags</label>
                                                            <Multiselect placeholder="Select"
                                                                v-model="form.availabilityTag"
                                                                class="multislect-dropdwn multislect-cmn-adj"
                                                                :close-on-select="true" :searchable="true"
                                                                :create-option="false" :options="safariAvailableTags" />

                                                            <span class="text-danger"
                                                                v-if="form.errors.availabilityTag">
                                                                {{ form.errors.availabilityTag }}
                                                            </span>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="col-md-12 crtlst-prc-col">
                                                    <div class="crtlst-prc-pnl">
                                                        <div class="input_hldr">
                                                            <label>Tiered Pricing / Discount </label>
                                                            <div class="crtlst-prc-table table-responsive">
                                                                <table>
                                                                    <thead>
                                                                        <tr>
                                                                            <th>Person</th>
                                                                            <th>Count</th>
                                                                            <th>Season</th>
                                                                            <th>Net price (USD)</th>
                                                                            <th>Action</th>
                                                                        </tr>
                                                                    </thead>

                                                                    <tbody>
                                                                        <tr v-for="(discount, index) in form.discounts"
                                                                            :key="index">
                                                                            <td>
                                                                                <div
                                                                                    v-tooltip.top="form.errors[`discounts.${index}.person_type`] || ''">

                                                                                    <select
                                                                                        v-model="discount.person_type"
                                                                                        :class="{ 'has-error': form.errors[`discounts.${index}.person_type`] }">
                                                                                        <option value="">Select</option>
                                                                                        <option value="ADULT">Adult
                                                                                        </option>
                                                                                        <option value="CHILD">Child
                                                                                        </option>
                                                                                    </select>
                                                                                </div>
                                                                            </td>
                                                                            <td>
                                                                                <input type="text" placeholder="max"
                                                                                    v-model="discount.count"
                                                                                    @input="discount.count = discount.count.replace(/[^0-9]/g, '').replace(/^0+/, '')"
                                                                                    :class="{ 'has-error': form.errors[`discounts.${index}.count`] }"
                                                                                    v-tooltip.top="form.errors[`discounts.${index}.count`] || ''">
                                                                            </td>
                                                                            <td>
                                                                                <div
                                                                                    v-tooltip.top="form.errors[`discounts.${index}.season`] || ''">

                                                                                    <select v-model="discount.season"
                                                                                        :class="{ 'has-error': form.errors[`discounts.${index}.season`] }">
                                                                                        <option value="">Select</option>
                                                                                        <option value="high_season">
                                                                                            High Season</option>
                                                                                        <option value="low_season">Low
                                                                                            Season
                                                                                        </option>
                                                                                        <!-- <option value="shoulder_season">
                                                                                            Shoulder
                                                                                            Season</option> -->
                                                                                        <option value="all_year">All
                                                                                            Year
                                                                                        </option>
                                                                                    </select>
                                                                                </div>
                                                                            </td>
                                                                            <td>
                                                                                <input type="text" placeholder="max"
                                                                                    v-model="discount.net_price"
                                                                                    @input="discount.net_price = discount.net_price === 'Free' ? '' : discount.net_price.replace(/[^0-9]/g, '').replace(/^0+/, '')"
                                                                                    :disabled="discount.net_price === 'Free'"
                                                                                    :class="{ 'has-error': form.errors[`discounts.${index}.net_price`] }"
                                                                                    v-tooltip.top="form.errors[`discounts.${index}.net_price`] || ''">
                                                                            </td>
                                                                            <td>
                                                                                <div class="jrny-day-formwrpr">
                                                                                    <div class="jrny-day-form-toppnl">
                                                                                        <a type="button"
                                                                                            class="add-jrny-day-fldbutn"
                                                                                            @click.prevent="addDiscount()">Add</a>
                                                                                        <a href="javascript:void(0);"
                                                                                            @click.prevent="removeDiscount()"
                                                                                            v-if="form.discounts.length > 1"><img
                                                                                                :src="'/frontend_assets/images/dlteicon.svg'"
                                                                                                alt="Delete"></a>
                                                                                    </div>
                                                                                </div>
                                                                            </td>
                                                                        </tr>
                                                                    </tbody>
                                                                </table>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="srv-cretenew-lstbutn">
                                                <button type="button" class="cmn-butn"
                                                    @click="submitListingForm(9)" :disabled="form.processing">{{ safari?.step_saved_status?.[9] ? 'Saved' : 'Save' }}</button>
                                            </div>
                                        </AccordionContent>
                                    </AccordionPanel>
                                </Accordion>
                            </div>

                            <!-- <div class="srvcrtnew-list-rgtpnl" :class="{ 'has-error': hasPricingErrors }">
                                <Accordion value="2" expandIcon="pi pi-plus" collapseIcon="pi pi-minus">
                                    <AccordionPanel value="0">
                                        <AccordionHeader>
                                            <div class="srvcrtlst-tppnl">
                                                <div class="srvcrtlst-hd">Pricing & Seasons </div>
                                                <button type="button" class="crtlst-colapsebutn"></button>
                                            </div>
                                        </AccordionHeader>
                                        <AccordionContent>
                                            <div class="row nw-jrny-day-formwrpr-row">
                                                <div class="col-md-6 cre-range-clndrcol">
                                                    <div
                                                        class="crtlst-vwbtmpnl cmn-caldr-adj full-width-clndr avlbledate-updt block-clndr-date">
                                                        <div
                                                            class="srvcrtnw-updtinputbx animal-fldwrpr clnder-blockdate">
                                                            <span>Low Season Dates</span>
                                                        </div>
                                                        <div class="bnrfrm-inputwrp date datefrmat">
                                                            <DateRange :minDate="new Date()" :format="formatDate" inline
                                                                v-model="form.lowSeasonDateRange"
                                                                :disabledDates="getDateArray(form.highSeasonDateRange)" />
                                                        </div>
                                                        <span class="text-danger" v-if="form.errors.lowSeasonDateRange">
                                                            {{ form.errors.lowSeasonDateRange }}
                                                        </span>
                                                    </div>
                                                </div>
                                                <div class="col-md-6 cre-range-clndrcol">
                                                    <div
                                                        class="crtlst-vwbtmpnl cmn-caldr-adj full-width-clndr avlbledate-updt block-clndr-date">
                                                        <div
                                                            class="srvcrtnw-updtinputbx animal-fldwrpr clnder-blockdate">
                                                            <span>High Season Dates</span>
                                                        </div>
                                                        <div class="bnrfrm-inputwrp date datefrmat">
                                                            <DateRange :minDate="new Date()" :format="formatDate" inline
                                                                v-model="form.highSeasonDateRange"
                                                                :disabledDates="getDateArray(form.lowSeasonDateRange)" />
                                                        </div>
                                                        <span class="text-danger"
                                                            v-if="form.errors.highSeasonDateRange">
                                                            {{ form.errors.highSeasonDateRange }}
                                                        </span>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="row crtlst-prc-row crtlst-vwbtmpnl pt-3">
                                                <div class="col-md-6 crtlst-prc-col">
                                                    <div class="crtlst-prc-pnl">
                                                        <div class="input_hldr">
                                                            <label>Price per Adult (Low Season)</label>
                                                            <input type="text" name="name" placeholder="e.g. 5"
                                                                v-model="form.lowSeasonAdultPrice" @input="() => {
                                                                    form.lowSeasonAdultPrice = form.lowSeasonAdultPrice.replace(/[^0-9.]/g, '').replace(/^0+(?!\.)/, '').replace(/(\..*)\./g, '$1');
                                                                    validateAdultLowHighSeasonPrice(index);
                                                                }">
                                                            <span class="text-danger"
                                                                v-if="form.errors.lowSeasonAdultPrice">
                                                                {{ form.errors.lowSeasonAdultPrice }}
                                                            </span>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-6 crtlst-prc-col">
                                                    <div class="crtlst-prc-pnl">
                                                        <div class="input_hldr">
                                                            <label>Price per Adult (High Season)</label>
                                                            <input type="text" name="name" placeholder="e.g. 5"
                                                                v-model="form.highSeasonAdultPrice" @input="() => {
                                                                    form.highSeasonAdultPrice = form.highSeasonAdultPrice.replace(/[^0-9.]/g, '').replace(/^0+(?!\.)/, '').replace(/(\..*)\./g, '$1');
                                                                    validateAdultLowHighSeasonPrice(index);
                                                                }">
                                                            <span class="text-danger"
                                                                v-if="form.errors.highSeasonAdultPrice">
                                                                {{ form.errors.highSeasonAdultPrice }}
                                                            </span>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-6 crtlst-prc-col">
                                                    <div class="crtlst-prc-pnl">
                                                        <div class="input_hldr">
                                                            <label>Price per Child (Low Season)</label>
                                                            <input type="text" name="name" placeholder="e.g. 5"
                                                                v-model="form.lowSeasonChildPrice" @input="() => {
                                                                    form.lowSeasonChildPrice = form.lowSeasonChildPrice.replace(/[^0-9.]/g, '').replace(/^0+(?!\.)/, '').replace(/(\..*)\./g, '$1');
                                                                    validateChildLowHighSeasonPrice(index);
                                                                }">
                                                            <span class="text-danger"
                                                                v-if="form.errors.lowSeasonChildPrice">
                                                                {{ form.errors.lowSeasonChildPrice }}
                                                            </span>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-6 crtlst-prc-col">
                                                    <div class="crtlst-prc-pnl">
                                                        <div class="input_hldr">
                                                            <label>Price per Child (High Season)</label>
                                                            <input type="text" name="name" placeholder="e.g. 5"
                                                                v-model="form.highSeasonChildPrice" @input="() => {
                                                                    form.highSeasonChildPrice = form.highSeasonChildPrice.replace(/[^0-9.]/g, '').replace(/^0+(?!\.)/, '').replace(/(\..*)\./g, '$1');
                                                                    validateChildLowHighSeasonPrice(index);
                                                                }">
                                                            <span class="text-danger"
                                                                v-if="form.errors.highSeasonChildPrice">
                                                                {{ form.errors.highSeasonChildPrice }}
                                                            </span>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="mb-3">
                                                    <p>Enter your net price (before platform and Stripe fees)</p>
                                                </div>

                                                <div class="col-md-12 crtlst-prc-col">
                                                    <div class="crtlst-prc-pnl">
                                                        <div class="input_hldr">
                                                            <label>Optional Group Pricing Bands</label>
                                                            <div class="crtlst-prc-table table-responsive">
                                                                <table>
                                                                    <thead>
                                                                        <tr>
                                                                            <th>Min Group members</th>
                                                                            <th>Max Group members</th>
                                                                            <th>PP</th>
                                                                        </tr>
                                                                    </thead>
                                                                    <tbody>
                                                                        <tr v-for="(priceBand, index) in form.priceBands"
                                                                            :key="index">
                                                                            <td>
                                                                                <input type="text" placeholder="min"
                                                                                    v-model="priceBand.min" @input="() => {
                                                                                        priceBand.min = priceBand.min.replace(/[^0-9]/g, '').replace(/^0+/, '');
                                                                                        validateMinMax(index);
                                                                                    }"
                                                                                    :class="{ 'has-error': form.errors[`priceBands.${index}.min`] }"
                                                                                    v-tooltip.top="form.errors[`priceBands.${index}.min`] ? form.errors[`priceBands.${index}.min`] : ''" />
                                                                            </td>
                                                                            <td>
                                                                                <input type="text" placeholder="Max"
                                                                                    v-model="priceBand.max" @input="() => {
                                                                                        priceBand.max = priceBand.max.replace(/[^0-9]/g, '').replace(/^0+/, '');
                                                                                        validateMinMax(index);
                                                                                    }"
                                                                                    :class="{ 'has-error': form.errors[`priceBands.${index}.max`] }"
                                                                                    v-tooltip.top="form.errors[`priceBands.${index}.max`] ? form.errors[`priceBands.${index}.max`] : ''" />
                                                                            </td>
                                                                            <td>
                                                                                <input type="text" placeholder="pp"
                                                                                    v-model="priceBand.pp"
                                                                                    @input="priceBand.pp = priceBand.pp.replace(/[^0-9]/g, '').replace(/^0+/, '')"
                                                                                    :class="{ 'has-error': form.errors[`priceBands.${index}.pp`] }"
                                                                                    v-tooltip.top="form.errors[`priceBands.${index}.pp`] ? form.errors[`priceBands.${index}.pp`] : ''" />
                                                                            </td>
                                                                        
                                                                        </tr>
                                                                    </tbody>
                                                                </table>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-12 crtlst-prc-col">
                                                    <div class="crtlst-prc-pnl">
                                                        <div class="input_hldr">
                                                            <label>Discount Fields</label>
                                                            <div class="crtlst-prc-table table-responsive">
                                                                <table>
                                                                    <thead>
                                                                        <tr>
                                                                            <th>Person</th>
                                                                            <th>Count</th>
                                                                            <th>Discount Type</th>
                                                                            <th>Discount</th>
                                                                   
                                                                        </tr>
                                                                    </thead>

                                                                    <tbody>
                                                                        <tr v-for="(discount, index) in form.discounts"
                                                                            :key="index">
                                                                            <td>
                                                                                <div
                                                                                    v-tooltip.top="form.errors[`discounts.${index}.person`] || ''">

                                                                                    <select v-model="discount.person"
                                                                                        :class="{ 'has-error': form.errors[`discounts.${index}.person`] }">
                                                                                        <option value="">Select</option>
                                                                                        <option value="adult">Adult
                                                                                        </option>
                                                                                        <option value="child">Child
                                                                                        </option>
                                                                                    </select>
                                                                                </div>
                                                                            </td>
                                                                            <td>
                                                                                <input type="text" placeholder="max"
                                                                                    v-model="discount.count"
                                                                                    @input="discount.count = discount.count.replace(/[^0-9]/g, '').replace(/^0+/, '')"
                                                                                    :class="{ 'has-error': form.errors[`discounts.${index}.count`] }"
                                                                                    v-tooltip.top="form.errors[`discounts.${index}.count`] || ''">
                                                                            </td>
                                                                            <td>
                                                                                <div
                                                                                    v-tooltip.top="form.errors[`discounts.${index}.discount_type`] || ''">

                                                                                    <select
                                                                                        v-model="discount.discount_type"
                                                                                        :class="{ 'has-error': form.errors[`discounts.${index}.discount_type`] }">
                                                                                        <option value="">Select</option>
                                                                                        <option value="Percentage">
                                                                                            Percentage(%)</option>
                                                                                      
                                                                                    </select>
                                                                                </div>
                                                                            </td>
                                                                            <td>
                                                                                <input type="text" placeholder="max"
                                                                                    v-model="discount.discount"
                                                                                    @input="discount.discount = discount.discount_type === 'Free' ? '' : discount.discount.replace(/[^0-9]/g, '').replace(/^0+/, '')"
                                                                                    :disabled="discount.discount_type === 'Free'"
                                                                                    :class="{ 'has-error': form.errors[`discounts.${index}.discount`] }"
                                                                                    v-tooltip.top="form.errors[`discounts.${index}.discount`] || ''">
                                                                            </td>
                                                              
                                                                        </tr>
                                                                    </tbody>
                                                                </table>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="srv-cretenew-lstbutn">
                                                <button type="button" class="cmn-butn"
                                                    @click="submitListingForm(10)">Save</button>
                                            </div>
                                        </AccordionContent>
                                    </AccordionPanel>
                                </Accordion>
                            </div> -->


                            <div class="srvcrtnew-list-rgtpnl map-lcation-srchfld"
                                :class="{ 'has-error': hasMapLocationErrors }">
                                <Accordion value="3" expandIcon="pi pi-plus" collapseIcon="pi pi-minus">
                                    <AccordionPanel value="0">
                                        <AccordionHeader>
                                            <div class="srvcrtlst-tppnl">
                                                <div class="srvcrtlst-hd">Map Location</div>
                                                <button type="button" class="crtlst-colapsebutn"></button>
                                            </div>
                                        </AccordionHeader>
                                        <AccordionContent>
                                            <div class="row crtlst-prc-row">
                                                <div class="col-md-12 crtlst-prc-col">
                                                    <label>Safari Location Type</label>
                                                    <div class="mulslect-drpdwn">
                                                        <div class="parktype-searchbox">
                                                            <Multiselect placeholder="Select"
                                                                v-model="form.location_type"
                                                                class="multislect-dropdwn multislect-cmn-adj"
                                                                :close-on-select="true" :searchable="true"
                                                                :create-option="false" :options="[
                                                                    { value: 'National_Park_Reserve', label: 'National Park / Reserve' },
                                                                ]" />
                                                        </div>
                                                        <span class="text-danger" v-if="form.errors.location_type">
                                                            {{ form.errors.location_type }}
                                                        </span>
                                                    </div>
                                                </div>
                                                <div class="col-md-12 crtlst-prc-col">
                                                    <slide-up-down
                                                        :active="form.location_type == 'National_Park_Reserve'"
                                                        :duration="300">
                                                        <label>Select National Park / Reserve</label>
                                                        <div class="mulslect-drpdwn">
                                                            <div class="parktype-searchbox">
                                                                <Multiselect placeholder="Select"
                                                                    v-model="form.national_park_id"
                                                                    class="multislect-dropdwn multislect-cmn-adj"
                                                                    :close-on-select="true" :searchable="true"
                                                                    :create-option="false"
                                                                    :options="allNationalParkReserves" />
                                                            </div>
                                                            <span class="text-danger"
                                                                v-if="form.errors.national_park_id">
                                                                {{ form.errors.national_park_id }}
                                                            </span>
                                                        </div>
                                                    </slide-up-down>
                                                </div>

                                                <div class="col-md-12 crtlst-prc-col">
                                                    <slide-up-down
                                                        :active="form.location_type == 'National_Park_Reserve'"
                                                        :duration="300">
                                                        <label>Location Name</label>
                                                        <input type="text" name="name" v-model="form.location"
                                                            placeholder="e.g. “Kruger National Park”" disabled>
                                                        <span class="text-danger" v-if="form.errors.location"> {{
                                                            form.errors.location }}</span>
                                                    </slide-up-down>
                                                    <slide-up-down
                                                        :active="form.location_type == 'Main_Safari_Location'"
                                                        :duration="300">
                                                        <label>Location Name</label>
                                                        <MapBoxAutoComplete :modelValue="form.location"
                                                            @update:location="handleLocationUpdate($event)" />
                                                    </slide-up-down>

                                                </div>
                                                <div class="col-md-12">
                                                    <label>Location Marker</label>
                                                    <input type="text" name="name" disabled v-model="form.location"
                                                        placeholder="Operators can drop a pin manually or input GPS">
                                                    <span class="text-danger" v-if="form.errors.location">
                                                        {{ form.errors.location }}
                                                    </span>
                                                </div>
                                            </div>
                                            <div class="srv-cretenew-lstbutn">
                                                <button type="button" class="cmn-butn"
                                                    @click="submitListingForm(10)" :disabled="form.processing">{{ safari?.step_saved_status?.[10] ? 'Saved' : 'Save' }}</button>
                                            </div>
                                        </AccordionContent>
                                    </AccordionPanel>
                                </Accordion>
                            </div>
                            <div class="srvcrtnew-list-rgtpnl gallry-upldwrp"
                                :class="{ 'has-error': hasGalleryErrors }">
                                <Accordion value="3" expandIcon="pi pi-plus" collapseIcon="pi pi-minus">
                                    <AccordionPanel value="0">
                                        <AccordionHeader>
                                            <div class="srvcrtlst-tppnl">
                                                <div class="srvcrtlst-hd">Gallery</div>
                                                <button type="button" class="crtlst-colapsebutn"></button>
                                            </div>
                                        </AccordionHeader>
                                        <AccordionContent>
                                            <div class="crtlst-vwbtmpnl">
                                                <div class="srvcrtnw-updtinputbx">
                                                    <label>Upload Gallery Images</label>
                                                    <div class="file-up-prevw">
                                                        <div class="file-up-prevw-row">
                                                            <div class="file-up-prevw-item"
                                                                v-for="(img, index) in form?.gallery_images"
                                                                :key="index">
                                                                <img :src="img.preview" alt="preview"
                                                                    class="file-up-prevw-item-img" />
                                                                <button class="file-up-prevw-item-close"
                                                                    @click.prevent="removeGalleryImage(index)"><img
                                                                        :src="'/frontend_assets/images/close.svg'"
                                                                        alt="close img" /></button>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="file-up-prevw">
                                                        <div class="file-up-prevw-row">
                                                            <div class="file-up-prevw-item"
                                                                v-for="(img, index) in filteredGallery" :key="img.id">
                                                                <img :src="img.full_image_url" alt="preview"
                                                                    class="file-up-prevw-item-img" />
                                                                <button class="file-up-prevw-item-close"
                                                                    @click.prevent="removeDBImg(index, img.id)">
                                                                    <img :src="'/frontend_assets/images/close.svg'"
                                                                        alt="close img" />
                                                                </button>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="input_hldr file-input">
                                                        <input type="file" multiple accept="image/*"
                                                            @change="handleGalleryUpload"
                                                            :disabled="form.gallery_images.length >= maxGalleryImages" />
                                                        <img :src="'/frontend_assets/images/up-input-file.svg'"
                                                            alt="img" class="up-input-file" />
                                                        <p>Upload your images (max {{ maxGalleryImages }})</p>
                                                        <div class="file-wrap">
                                                            <div class="wrap-brows">
                                                                <a href="#url" class="cmn-butn">Browse file</a>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <span class="text-danger" v-if="form.errors.gallery_images">
                                                        {{ form.errors.gallery_images }}
                                                    </span>

                                                    <span class="text-danger" v-if="galleryImagesError">
                                                        {{ galleryImagesError }}
                                                    </span>
                                                </div>
                                            </div>
                                            <div class="srv-cretenew-lstbutn">
                                                <button type="button" class="cmn-butn"
                                                    @click="submitListingForm(11)" :disabled="form.processing">{{ safari?.step_saved_status?.[11] ? 'Saved' : 'Save' }}</button>
                                            </div>
                                        </AccordionContent>
                                    </AccordionPanel>
                                </Accordion>
                            </div>
                            <div class="srvcrtnew-list-rgtpnl" :class="{ 'has-error': hasThingsToKnowErrors }">
                                <Accordion value="3" expandIcon="pi pi-plus" collapseIcon="pi pi-minus">
                                    <AccordionPanel value="0">
                                        <AccordionHeader>
                                            <div class="srvcrtlst-tppnl">
                                                <div class="srvcrtlst-hd">Before You Go / FAQs</div>
                                                <button type="button" class="crtlst-colapsebutn"></button>
                                            </div>
                                        </AccordionHeader>
                                        <AccordionContent>
                                            <div class="crtlst-vwbtmpnl">
                                                <div class="thng-vw-reptbx"
                                                    v-for="(thingsToKnow, index) in form.thingsToKnows" :key="index">
                                                    <div class="srvcrtnw-updtinputbx">
                                                        <label>Question</label>
                                                        <div class="input_hldr">
                                                            <input type="text" placeholder="Enter question"
                                                                v-model="thingsToKnow.heading">
                                                            <span class="text-danger"
                                                                v-if="form.errors[`thingsToKnows.${index}.heading`]">
                                                                {{ form.errors[`thingsToKnows.${index}.heading`] }}
                                                            </span>
                                                            <CharacterCounter :text="thingsToKnow.heading"
                                                                :maxlength="150" />

                                                        </div>
                                                    </div>
                                                    <div class="srvcrtnw-updtinputbx">
                                                        <label>Answer</label>
                                                        <div class="input_hldr">
                                                            <ckeditor v-model="thingsToKnow.description"
                                                                :config="editorConfig" :placeholder="'Enter answer'">
                                                            </ckeditor>
                                                            <span class="text-danger"
                                                                v-if="form.errors[`thingsToKnows.${index}.description`]">
                                                                {{ form.errors[`thingsToKnows.${index}.description`] }}
                                                            </span>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="jrny-day-formwrpr">
                                                    <div class="jrny-day-form-toppnl">
                                                        <a type="button" @click.prevent="addThingsToKnow()"
                                                            class="add-jrny-day-fldbutn">Add</a>
                                                        <a href="javascript:void(0);"
                                                            v-if="form.thingsToKnows.length > 1" class="wldlfedlteicon"
                                                            @click.prevent="removeThingsToKnow()"><img
                                                                :src="'/frontend_assets/images/dlteicon.svg'"
                                                                alt="Delete"></a>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="srv-cretenew-lstbutn">
                                                <button type="button" class="cmn-butn"
                                                    @click="submitListingForm(12)" :disabled="form.processing">{{ safari?.step_saved_status?.[12] ? 'Saved' : 'Save' }}</button>
                                            </div>
                                        </AccordionContent>
                                    </AccordionPanel>
                                </Accordion>
                            </div>

                            <div class="srvcrtnew-list-rgtpnl" :class="{ 'has-error': hasOperatorInfoErrors }">
                                <Accordion value="3" expandIcon="pi pi-plus" collapseIcon="pi pi-minus">
                                    <AccordionPanel value="0">
                                        <AccordionHeader>
                                            <div class="srvcrtlst-tppnl">
                                                <div class="srvcrtlst-hd">Operator Info</div>
                                                <button type="button" class="crtlst-colapsebutn"></button>
                                            </div>
                                        </AccordionHeader>
                                        <AccordionContent>
                                            <div class="row crtlst-prc-row">
                                                <div class="col-md-12 crtlst-prc-col">
                                                    <label>Company Name</label>
                                                    <div class="input_hldr">
                                                        <input type="text" placeholder="Enter title"
                                                            v-model="form.company_name">
                                                        <span class="text-danger" v-if="form.errors.company_name">
                                                            {{ form.errors.company_name }}
                                                        </span>
                                                        <CharacterCounter :text="form.company_name" :maxlength="80" />
                                                    </div>
                                                </div>
                                                <div class="col-md-12 crtlst-prc-col">
                                                    <label>Company Description</label>
                                                    <div class="input_hldr">
                                                        <textarea name="" id="" cols="30" rows="10"
                                                            placeholder="Write here somthing"
                                                            v-model="form.operator_description"></textarea>
                                                        <span class="text-danger"
                                                            v-if="form.errors.operator_description">
                                                            {{ form.errors.operator_description }}
                                                        </span>
                                                        <CharacterCounter :text="form.operator_description"
                                                            :maxlength="400" />
                                                    </div>
                                                </div>

                                                <div class="col-md-12 crtlst-prc-col">
                                                    <label>Why choose Us</label>
                                                    <div class="input_hldr">
                                                        <ckeditor v-model="form.why_choose_us" :config="editorConfig">
                                                        </ckeditor>
                                                        <span class="text-danger" v-if="form.errors.why_choose_us">
                                                            {{ form.errors.why_choose_us }}
                                                        </span>
                                                    </div>
                                                </div>

                                                <div class="col-md-6 crtlst-prc-col">
                                                    <label>Based in</label>
                                                    <div class="input_hldr">
                                                        <input type="text" placeholder="Enter title"
                                                            v-model="form.address">
                                                        <span class="text-danger" v-if="form.errors.address">
                                                            {{ form.errors.address }}
                                                        </span>
                                                        <CharacterCounter :text="form.address" :maxlength="100" />
                                                    </div>
                                                </div>

                                                <!-- <div class="col-md-6 crtlst-prc-col">
                                                    <label>Stuff</label>
                                                    <div class="input_hldr">
                                                        <input type="text" placeholder="Enter title"
                                                            v-model="form.operator_team_size"
                                                            @input="form.operator_team_size = form.operator_team_size.replace(/[^0-9]/g, '').replace(/^0+/, '')">
                                                        <span class="text-danger" v-if="form.errors.operator_team_size">
                                                            {{ form.errors.operator_team_size }}
                                                        </span>
                                                    </div>
                                                </div> -->
                                                <div class="col-md-6 crtlst-prc-col">
                                                    <label>Years of Experience</label>
                                                    <div class="input_hldr">
                                                        <input type="text" placeholder="Enter Years of Experience"
                                                            v-model="form.business_years">
                                                        <span class="text-danger" v-if="form.errors.business_years">
                                                            {{ form.errors.business_years }}
                                                        </span>
                                                        <!-- <CharacterCounter :text="form.business_years" :maxlength="3" /> -->
                                                    </div>
                                                </div>

                                                <div class="col-md-12 crtlst-prc-col">
                                                    <div class="srvcrtnw-updtinputbx">
                                                        <label>Upload Company Logo</label>
                                                        <div class="input_hldr file-input">
                                                            <file-upload
                                                                @input="form.operator_logo = $event.target.files[0]"
                                                                accept="image/*" :imageurl="userLogoFullUrl" />
                                                            <img :src="'/frontend_assets/images/up-input-file.svg'"
                                                                alt="img" class="up-input-file">
                                                            <p>Upload company logo</p>
                                                            <div class="file-wrap">
                                                                <div class="wrap-brows">
                                                                    <a href="#url" class="cmn-butn">Browse file</a>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <span class="text-danger" v-if="form.errors.operator_logo">
                                                            {{ form.errors.operator_logo }}
                                                        </span>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="srv-cretenew-lstbutn">
                                                <button type="button" class="cmn-butn"
                                                    @click="submitListingForm(13)" :disabled="form.processing">{{ safari?.step_saved_status?.[13] ? 'Saved' : 'Save' }}</button>
                                            </div>
                                        </AccordionContent>
                                    </AccordionPanel>
                                </Accordion>
                            </div>
                        </div>
                    </div>
                    <div class="srv-cretenew-lstbutn">
                        <FrontendSubmitButton :isLoading="form.processing" :value="'Submit Safari'" />
                    </div>
                </form>
            </div>
        </div>
        <!-- safari overview sec end  -->
    </div>
</template>
<script setup>
import { onMounted, ref, reactive, watch, computed } from 'vue'
import { homeJs } from "@/custom.js";
import Multiselect from '@vueform/multiselect';
import FileUpload from '@/components/FileUpload.vue'
import { useForm, usePage } from '@inertiajs/vue3';
import DateRange from "@/components/DateRange.vue";
import SlideUpDown from 'vue-slide-up-down'
import MapBoxAutoComplete from '@/components/Frontend/MapBoxAutoComplete.vue';
import useValidationErrors from '@/helpers/ValidationErrors.js';
import FrontendSubmitButton from '@/components/FrontendSubmitButton.vue';
import { component as ckeditor } from "@mayasabha/ckeditor4-vue3";
import CharacterCounter from '@/components/Frontend/CharacterCounter.vue';

const editorConfig = {
    height: 120,
    resize_enabled: false,
    format_tags: 'p;h1;h2;h3;h4;h5;h6',
    toolbar: [
        { name: 'basicstyles', items: ['Bold', 'Italic'] },
        { name: 'styles', items: ['Format'] },
        { name: 'colors', items: ['FontColor', 'FontBackgroundColor'] },
        { name: 'lists', items: ['NumberedList', 'BulletedList'] },
        { name: 'tools', items: ['Source'] },
    ],
};

const props = defineProps({
    regions: Object,
    wildlives: Array,
    allNationalParkReserves: Object,
    safariTypes: Array,
    activities: Array,
    safari: Object,
    safariAvailableTags: Array
});
const page = usePage();
const location = ref({ name: 'South Africa', lat: -24.7828986, lng: 26.1870993, description: 'This is a beautiful safari location.', contact: '+27 123 4567' })
const maxGalleryImages = 10;
const getCountries = ref([]);
const safariThumbnailFullUrl = ref(props.safari?.full_thumbnail_url);
const userLogoFullUrl = ref(page.props?.auth?.user?.business_logo);


const form = useForm({
    formStep: 0,
    /** File Upload */
    thumbnail: null,
    operator_logo: null,
    /** String Type Input */
    title: props.safari?.title ?? '',
    region_id: props.safari?.region_id ?? '',
    national_park_id: props.safari?.national_park_id ?? '',
    country_id: props.safari?.country_id ?? '',
    activityLevel: props.safari?.activity_level ?? '',
    description: props.safari?.description ?? '',
    special_description: props.safari?.short_description ?? '',
    seasons: Array.isArray(props?.safari?.travel_season) ? props?.safari?.travel_season : [],
    safariIncluded: props?.safari?.safari_included ?? '',
    safariNotIncluded: props?.safari?.safari_not_included ?? '',
    // addOns: props?.safari?.add_ons ?? '',
    groupType: props?.safari?.group_type ?? '',
    latitude: props?.safari?.lat ?? '',
    longitude: props?.safari?.long ?? '',
    location_type: props?.safari ? (props?.safari?.national_park_id ? 'National_Park_Reserve' : 'Main_Safari_Location') : '',
    location: props?.safari?.location ?? '',
    company_name: page?.props?.auth?.user?.business_name,
    operator_description: page?.props?.auth?.user?.about_operation,
    operator_team_size: page?.props?.auth?.user?.no_of_employees,
    operator_type: page?.props?.auth?.user?.business_type,
    business_years: page?.props?.auth?.user?.business_years,
    address: page?.props?.auth?.user?.address,
    why_choose_us: page?.props?.auth?.user?.why_choose_us ?? '',
    /** Number type Input */
    numberOfAdults: props?.safari?.no_of_adult ?? '',
    numberOfChildren: props?.safari?.no_of_child ?? '',
    numberOfGroups: props?.safari?.number_of_groups ?? '',
    day_duration: props?.safari?.day_duration ?? '',
    night_duration: props?.safari?.night_duration ?? '',
    lowSeasonAdultPrice: props?.safari?.low_season_adult_price ?? '',
    highSeasonAdultPrice: props?.safari?.high_season_adult_price ?? '',
    lowSeasonChildPrice: props?.safari?.low_season_child_price ?? '',
    highSeasonChildPrice: props?.safari?.high_season_child_price ?? '',
    perDateGroupLimit: props?.safari?.per_date_group_limit ?? '',
    /**totalSlots: props?.safari?.total_slots ?? '',*/
    availabilityTag: props?.safari?.tags[0]?.id ?? '',
    booking_mode: props?.safari?.booking_mode ?? 'booking',

    /** Array Type Input */
    national_parks: props?.safari?.safari_parks
        ? props.safari.safari_parks.map(item => item.national_park_reserve_id)
        : [],
    safariType: props?.safari?.create_safari_type
        ? props.safari.create_safari_type.map(item => item.safari_type_id)
        : [],
    activities: props?.safari?.activity
        ? props.safari.activity.map(item => item.id)
        : [],

    environment: Array.isArray(props?.safari?.environment) ? props.safari.environment : [],
    gallery_images: [],
    lowSeasonDateRange: [props?.safari?.low_season_start_date, props?.safari?.low_season_end_date] ?? [],
    highSeasonDateRange: [props?.safari?.high_season_start_date, props?.safari?.high_season_end_date] ?? [],
    // availableDateRange: [props?.safari?.available_start_date, props?.safari?.available_end_date] ?? [],
    // blockedDateRange: [props?.safari?.blocked_start_date, props?.safari?.blocked_end_date] ?? [],
    removeGalleryImageIds: [],


    dateRange: [
        {
            lowSeasonAvailableDates: [],
            lowSeasonBlockedDates: [],
            highSeasonAvailableDates: [],
            highSeasonBlockedDates: [],
            lowSeasonAdultPrice: "",
            highSeasonAdultPrice: "",
            lowSeasonChildPrice: "",
            highSeasonChildPrice: "",
        },
    ],

    /** Add More Input */
    days: (props.safari?.days && props.safari.days.length
        ? props.safari.days
        : [
            {
                heading: '',
                subtext: '',
                today_highlights: '',
                image: [],
                no_accommodation_included: false,
                accommodation: '',
                meals: '',
                wildlife_location: '',
                animals: [
                    {
                        animal_id: '',
                        description: '',
                    },
                ],
            },
        ]
    ).map(day => ({
        ...day,
        animals:
            Array.isArray(day.animals) && day.animals.length
                ? day.animals
                : [
                    {
                        animal_id: '',
                        description: '',
                    },
                ],
    })),
    // animals: props?.safari?.days?.wildlife_highlights || [{
    //     animal_id: '',
    //     probability: '',
    //     description: '',
    // }],
    thingsToKnows: (Array.isArray(props.safari?.things_to_know) && props.safari.things_to_know.length
        ? props.safari.things_to_know
        : [
            {
                heading: '',
                description: '',
            },
        ]
    ).map(item => ({
        heading: item.heading ?? '',
        description: item.description ?? '',
    })),


    discounts: props.safari?.group_pricing?.length ? props.safari.group_pricing : [{
        person_type: '',
        count: '',
        season: '',
        net_price: 0
    }]
});

onMounted(() => {
    homeJs();
    fetchCountries(props?.safari?.region_id);
    location.value = {
        name: props?.safari?.location,
        lat: props?.safari?.lat,
        lng: props?.safari?.long,
        description: props?.safari?.location,
    };
    emit.emit("pageName", "Your Safari");
    emit.emit("pageSubTitle", "Manage and update your trips");

    const seasonalPricings = props.safari?.seasonal_pricings ?? [];

    if (seasonalPricings.length) {
        // Separate by season
        const lowSeasons = seasonalPricings.filter((s) => s.season?.toLowerCase().trim() === "low");
        const highSeasons = seasonalPricings.filter((s) => s.season?.toLowerCase().trim() === "high");

        // Determine max number of rows
        const maxLength = Math.max(lowSeasons.length, highSeasons.length);

        const dateRange = Array.from({ length: maxLength }, (_, i) => {
            const low = lowSeasons[i];
            const high = highSeasons[i];

            // Helper to make sure only valid dates are included
            const formatRange = (start, end) => {
                if (start && end) return [start, end];
                if (start && !end) return start; // only start date if end is null
                return []; // empty if no start
            };

            return {
                // LOW
                lowSeasonAvailableDates: formatRange(low?.available_start_date,low?.available_end_date),
                lowSeasonBlockedDates: formatRange(low?.blocked_start_date,low?.blocked_end_date),
                lowSeasonAdultPrice: low?.adult_price || "",
                lowSeasonChildPrice: low?.child_price || "",

                // HIGH
                highSeasonAvailableDates: formatRange(high?.available_start_date,high?.available_end_date),
                highSeasonBlockedDates: formatRange(high?.blocked_start_date,high?.blocked_end_date),
                highSeasonAdultPrice: high?.adult_price || "",
                highSeasonChildPrice: high?.child_price || "",
            };
        });

        form.dateRange = dateRange;
    }
});


const {
    hasSafariOverviewErrors,
    // hasAnyAnimalErrors,
    hasDayByDayErrors,
    hasActivityErrors,
    hasLandscapeErrors,
    hasActivityLevelErrors,
    hasTravellerGroupErrors,
    hasAvailabilityErrors,
    hasPricingErrors,
    hasMapLocationErrors,
    hasGalleryErrors,
    hasThingsToKnowErrors,
    hasOperatorInfoErrors,
    hasIncludedNotIncludedErrors
} = useValidationErrors(form);

const addDay = () => {
    form.days.push({
        heading: '',
        subtext: '',
        no_accommodation_included: false,
        accommodation: '',
        today_highlights: '',
        meals: '',
        wildlife_location: '',
        image: [],
        animals: [
            {
                animal_id: '',
                description: '',
            }
        ]
    });
};

const removeDay = (index) => {
    if (form.days.length > 1) {
        form.days.splice(index, 1);
    }
};

// const addAnimal = () => {
//     form.animals.push({
//         animal_id: '',
//         probability: '',
//         description: '',
//     });
// };

const addPriceBands = () => {
    form.priceBands.push({
        min: '',
        max: '',
        pricePerPerson: '',
    });
};

const removePriceBands = (index) => {
    if (form.priceBands.length > 1) {
        form.priceBands.splice(index, 1);
    }
};

// const removeAnimal = (index) => {
//     if (form.animals.length > 1) {
//         form.animals.splice(index, 1);
//     }
// };

const addThingsToKnow = () => {
    form.thingsToKnows.push({
        title: '',
        description: '',
    });
};

const removeThingsToKnow = (index) => {
    if (form.thingsToKnows.length > 1) {
        form.thingsToKnows.pop();
    }
};

const addDiscount = () => {
    form.discounts.push({
        person_type: '',
        count: '',
        season: '',
        net_price: 0,
    });
};

const removeDiscount = (index) => {
    if (form.discounts.length > 1) {
        form.discounts.pop();
    }
};

const addAnimal = (dayIndex) => {
    form.days[dayIndex].animals.push({
        animal_id: '',
        description: '',
    });
};

const removeAnimal = (dayIndex, animalIndex) => {
    if (form.days[dayIndex].animals.length > 1) {
        form.days[dayIndex].animals.splice(animalIndex, 1);
    }
};

const addDateRange = () => {
    form.dateRange.push({
        lowSeasonAvailableDates: ['', ''],
        lowSeasonBlockedDates: ['', ''],
        highSeasonAvailableDates: ['', ''],
        highSeasonBlockedDates: ['', ''],
        lowSeasonAdultPrice: '',
        highSeasonAdultPrice: '',
        lowSeasonChildPrice: '',
        highSeasonChildPrice: ''
    });
};

const removeDateRange = (index) => {
    if (form.dateRange.length > 1) {
        form.dateRange.splice(index, 1);
    }
};

const regionform = reactive({
    country_id: '',
    region_id: '',
    errors: {}
});

const onRegionChange = async (regionId) => {
    regionform.country_id = '';
    regionform.region_id = regionId;
    fetchCountries(regionId);
}

const fetchCountries = async (regionId) => {
    try {
        const response = await axios.get(`/get-countries`, {
            params: { region_id: regionId }
        });
        getCountries.value = response.data.map(country => ({
            value: country.id,
            label: country.name
        }));
    } catch (error) {
        console.error('Error loading countries', error);
    }
}

const handleDayImageUpload = (event, day) => {
    if (!day.image) {
        day.image = []
    }

    const selectedFiles = Array.from(event.target.files)
    const remainingSlots = 3 - day?.image?.length

    if (selectedFiles.length > remainingSlots) {
        toaster.error(`You can only upload ${remainingSlots} more image${remainingSlots > 1 ? 's' : ''}.`)
        event.target.value = ''
        return
    }

    const newImages = selectedFiles.map(file => ({
        file,
        preview: URL.createObjectURL(file)
    }))

    day.image.push(...newImages)
    event.target.value = ''
};

const removeImage = (day, index) => {
    day.image.splice(index, 1)
};

const handleGalleryUpload = (event) => {
    const selectedFiles = Array.from(event.target.files)
    const remainingSlots = maxGalleryImages - form.gallery_images.length

    if (remainingSlots <= 0) {
        toaster.error(`Maximum ${maxGalleryImages} images allowed.`);
        return
    }

    if (selectedFiles.length > remainingSlots) {
        toaster.error(`You can only upload ${remainingSlots} more image${remainingSlots > 1 ? 's' : ''}.`);
        return
    }

    const newImages = selectedFiles.map(file => ({
        file,
        preview: URL.createObjectURL(file)
    }))

    form.gallery_images.push(...newImages)
    event.target.value = ''
};

const removeGalleryImage = (index) => {
    URL.revokeObjectURL(form?.gallery_images[index]?.preview);
    form?.gallery_images?.splice(index, 1);
};

const filteredGallery = computed(() => {
    return props.safari?.gallery?.filter(img => !form.removeGalleryImageIds.includes(img.id))
})

const removeDBImg = (index, imgId) => {
    if (!form.removeGalleryImageIds.includes(imgId)) {
        form.removeGalleryImageIds.push(imgId)
    }
}

function formatDate(dates) {
    if (!dates) return '';
    if (Array.isArray(dates)) {
        const validDates = dates.filter(d => d && !isNaN(new Date(d)));
        if (validDates.length === 0) return '';
        if (validDates.length === 1) return singleFormat(validDates[0]);
        return `${singleFormat(validDates[0])} - ${singleFormat(validDates[1])}`;
    }
    return singleFormat(dates);
}

function singleFormat(date) {
    if (!date) return '';
    date = new Date(date); // make sure it's a Date object

    const day = date.getDate();
    const month = date.toLocaleString('default', { month: 'short' }); // 'Oct', 'Nov'
    const year = date.getFullYear();

    let suffix = 'th';
    if (day % 10 === 1 && day !== 11) suffix = 'st';
    else if (day % 10 === 2 && day !== 12) suffix = 'nd';
    else if (day % 10 === 3 && day !== 13) suffix = 'rd';

    return `${day}${suffix} ${month} ${year}`;
};

const scrollToSaveButton = (step) => {
    const buttonSelectors = {
        1: '.srvcrtnew-list-lftcol .srv-cretenew-lstbutn button',
        2: '.srvcrtnew-list-rgtpnl:nth-child(1) .srv-cretenew-lstbutn button',
        3: '.srvcrtnew-list-rgtpnl:nth-child(2) .srv-cretenew-lstbutn button',
        4: '.srvcrtnew-list-rgtpnl:nth-child(3) .srv-cretenew-lstbutn button',
        5: '.srvcrtnew-list-rgtpnl:nth-child(4) .srv-cretenew-lstbutn button',
        6: '.srvcrtnew-list-rgtpnl:nth-child(5) .srv-cretenew-lstbutn button',
        7: '.srvcrtnew-list-rgtpnl:nth-child(6) .srv-cretenew-lstbutn button',
        8: '.srvcrtnew-list-rgtpnl:nth-child(7) .srv-cretenew-lstbutn button',
        9: '.srvcrtnew-list-rgtpnl:nth-child(8) .srv-cretenew-lstbutn button',
        10: '.srvcrtnew-list-rgtpnl:nth-child(9) .srv-cretenew-lstbutn button',
        11: '.srvcrtnew-list-rgtpnl:nth-child(10) .srv-cretenew-lstbutn button',
        12: '.srvcrtnew-list-rgtpnl:nth-child(11) .srv-cretenew-lstbutn button',
        13: '.srvcrtnew-list-rgtpnl:nth-child(12) .srv-cretenew-lstbutn button'
    };
    
    const buttonSelector = buttonSelectors[step];
    if (buttonSelector) {
        const button = document.querySelector(buttonSelector);
        if (button) {
            button.scrollIntoView({ behavior: 'smooth', block: 'center' });
        }
    }
};

const submitListingForm = (step) => {
    form.formStep = step;
    const plainDateRange = JSON.parse(JSON.stringify(form.dateRange));

    form.transform((data) => {
        const payload = { ...data };

        if (plainDateRange && typeof plainDateRange === 'object') {
            payload.dateRange = JSON.stringify(plainDateRange);
        }
        return payload;
    });

    const submitOptions = {
        forceFormData: true,
        onSuccess: () => {
            // Reset file uploads after successful save
            resetFileUploads(step);
            
            // Scroll to the same save button after successful save
            if (step > 0) {
                setTimeout(() => {
                    scrollToSaveButton(step);
                }, 300);
            }
        },
        onError: (errors) => {
            console.error('Form submission errors:', errors);
        }
    };

    if (props?.safari) {
        form.post(route('frontend.safari-edit-listing', props.safari.id), submitOptions);
    } else {
        form.post(route('frontend.safari-create-listing'), submitOptions);
    }
};


const handleLocationUpdate = (loc) => {
    location.value = {
        name: loc.place_name,
        lat: loc.latitude,
        lng: loc.longitude,
        description: loc.place_name,
    }
    form.latitude = loc.latitude;
    form.longitude = loc.longitude;
    form.location = loc.place_name;
};


watch(() => form.location_type, (newVal) => {
    form.location = '';
    form.latitude = '';
    form.longitude = '';
    form.national_park_id = null;
});

watch(() => form.national_park_id, (newVal) => {
    if (newVal) {
        form.location = '';
        form.latitude = '';
        form.longitude = '';
    }

    const getLoc = async () => {
        try {
            const response = await axios.get(route('frontend.get-nation-park-locations'), {
                params: { park_id: newVal }
            });
            location.value = {
                name: response.data.location,
                lat: response.data.lat,
                lng: response.data.long,
                description: response.data.location,
            }
            form.latitude = response.data.lat;
            form.longitude = response.data.long;
            form.location = response.data.location;
        } catch (error) {
            console.error('Error loading countries', error);
        }
    }
    getLoc();
});

// const validateCommaSeparated = (field) => {
//     const value = form[field]?.trim();

//     const regex = /^([a-zA-Z0-9]+(?: [a-zA-Z0-9]+)*)(,[a-zA-Z0-9]+(?: [a-zA-Z0-9]+)*)*$/;

//     if (!value) {
//         form.errors[field] = '';
//         return;
//     }

//     if (!regex.test(value)) {
//         form.errors[field] = 'Please enter comma-separated values without spaces (e.g. Item 1,Item 2)';
//     } else {
//         form.errors[field] = '';
//     }
// };

const validateMinMax = (index) => {
    const min = parseInt(form.priceBands[index].min, 10)
    const max = parseInt(form.priceBands[index].max, 10)

    const minKey = `priceBands.${index}.min`
    const maxKey = `priceBands.${index}.max`

    delete form.errors[minKey]
    delete form.errors[maxKey]

    if (!isNaN(min) && !isNaN(max)) {
        if (max <= min) {
            form.errors[maxKey] = 'Max must be greater than Min'
        }
    }
};

const validateAdultLowHighSeasonPrice = () => {
    const low = parseFloat(form.lowSeasonAdultPrice)
    const high = parseFloat(form.highSeasonAdultPrice)

    delete form.errors.highSeasonAdultPrice

    if (!isNaN(low) && !isNaN(high)) {
        if (high < low) {
            form.errors.highSeasonAdultPrice = 'High Season Adult Price should not be less than Low Season Adult Price'
        }
    }
}

const validateChildLowHighSeasonPrice = () => {
    const low = parseFloat(form.lowSeasonChildPrice)
    const high = parseFloat(form.highSeasonChildPrice)

    delete form.errors.highSeasonChildPrice

    if (!isNaN(low) && !isNaN(high)) {
        if (high < low) {
            form.errors.highSeasonChildPrice = 'High Season Child Price should not be less than Low Season Child Price'
        }
    }
}

const getDateArray = (range) => {
    const dates = []
    if (!Array.isArray(range) || range.length !== 2) return dates

    const [start, end] = range.map(d => new Date(d))
    const date = new Date(start)

    while (date <= end) {
        dates.push(new Date(date))
        date.setDate(date.getDate() + 1)
    }
    return dates
};

const galleryImagesError = computed(() => {
    const messages = new Set(
        Object.entries(form.errors)
            .filter(([key]) => key.startsWith('gallery_images'))
            .map(([, value]) => value)
    )
    return messages.size ? Array.from(messages).join(' ') : null
});



const formatDateRange = (val) => {
  if (Array.isArray(val)) return val.map(formatDate).join(" - ");
  return formatDate(val);
};

const resetFileUploads = (step) => {
    // Reset thumbnail after step 1 save
    if (step === 1 && form.thumbnail) {
        form.thumbnail = null;
        // Reset file input
        const thumbnailInput = document.querySelector('input[type="file"][accept="image/*"]:not([multiple])');
        if (thumbnailInput) {
            thumbnailInput.value = '';
        }
    }
    
    // Reset gallery images after step 11 save
    if (step === 11 && form.gallery_images.length > 0) {
        // Clean up object URLs to prevent memory leaks
        form.gallery_images.forEach(img => {
            if (img.preview) {
                URL.revokeObjectURL(img.preview);
            }
        });
        form.gallery_images = [];
        // Reset file input
        const galleryInput = document.querySelector('input[type="file"][multiple][accept="image/*"]');
        if (galleryInput) {
            galleryInput.value = '';
        }
    }
    
    // Reset operator logo after step 13 save
    if (step === 13 && form.operator_logo) {
        form.operator_logo = null;
        // Reset file input for operator logo
        const operatorLogoInputs = document.querySelectorAll('input[type="file"][accept="image/*"]:not([multiple])');
        operatorLogoInputs.forEach(input => {
            if (input.closest('.srvcrtnew-list-rgtpnl:last-child')) {
                input.value = '';
            }
        });
    }
    
    // Reset all file uploads after final submission (step 0)
    if (step === 0) {
        form.thumbnail = null;
        form.operator_logo = null;
        
        // Clean up gallery images
        form.gallery_images.forEach(img => {
            if (img.preview) {
                URL.revokeObjectURL(img.preview);
            }
        });
        form.gallery_images = [];
        
        // Reset all file inputs
        const allFileInputs = document.querySelectorAll('input[type="file"]');
        allFileInputs.forEach(input => {
            input.value = '';
        });
    }
};


</script>

<style src="@vueform/multiselect/themes/default.css"></style>

<style scoped>
.has-error {
    border: 1px solid #ff0000;
}

input:disabled {
    background: #ede9e9 !important;
}

.group-pricing-description {
    color: grey;
    font-size: 16px;
    margin-bottom: 18px;
}

/* Smooth scrolling for the entire page */
html {
    scroll-behavior: smooth;
}

/* Highlight effect for the section being scrolled to */
.srvcrtnew-list-rgtpnl {
    transition: all 0.3s ease;
}

.srvcrtnew-list-rgtpnl:target {
    background-color: #f8f9fa;
    border-left: 4px solid #007bff;
    padding-left: 16px;
}

/* Loading state for save buttons */
button:disabled {
    opacity: 0.6;
    cursor: not-allowed;
}

/* Scroll margin to account for fixed headers */
.srvcrtnew-list-rgtpnl {
    scroll-margin-top: 20px;
}
</style>