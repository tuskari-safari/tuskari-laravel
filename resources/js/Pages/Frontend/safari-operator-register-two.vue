<template>
  <div class="main_wrppr_alt">
    <div class="register_section cmn-gap pt-0">
      <div class="container ful-container">
        <div class="rgstrtn_box">
          <div class="row regstrtn_row gx-0">
            <div class="col-lg-5 rgstrtn_lft_col align-self-center">
              <div class="rgstr_frm_box form_box">
                <h1>Start exploring</h1>
                <p>Join Tuskari and share your safaris with the world.</p>

                <form @submit.prevent="submitFinalForm">
                  <div class="row form_row">
                    <!-- Main Destination -->
                    <div class="col-12 form_col">
                      <label
                        >Main Destination Countries You Offer<span class="text-danger"
                          >*</span
                        ></label
                      >
                      <div class="input_hldr">
                        <div
                          class="form_input_check input-check-extra"
                          style="margin-bottom: 7px"
                        >
                          <Multiselect
                            placeholder="Select"
                            v-model="form.main_destination"
                            class="multislect-dropdwn multislect-cmn-adj"
                            :close-on-select="true"
                            mode="tags"
                            :searchable="true"
                            :options="destinations"
                            :create-option="false"
                          />
                        </div>
                        <span class="text-danger" v-if="form.errors.main_destination">{{
                          form.errors.main_destination
                        }}</span>
                      </div>
                    </div>

                    <!-- Business Type -->
                    <div class="col-12 form_col">
                      <label
                        >How Many Employees Do You Currently Have?
                        <span class="text-danger">*</span></label
                      >
                      <div class="input_hldr input-extra">
                        <Multiselect
                          placeholder="Select"
                          v-model="form.business_type"
                          class="multislect-dropdwn multislect-cmn-adj"
                          :close-on-select="true"
                          mode="single"
                          :searchable="true"
                          :options="[
                            { value: '1-5 Staff', label: '1-5 Staff' },
                            { value: '6-15 Staff', label: '6-15 Staff' },
                            { value: '16+ Staff', label: '16+ Staff' },
                          ]"
                          :create-option="false"
                        />
                        <span class="text-danger" v-if="form.errors.business_type">{{
                          form.errors.business_type
                        }}</span>
                      </div>
                    </div>

                    <!-- Operate Directly -->
                    <div class="col-12 form_col">
                      <label
                        >How Many Years Have You Been Operating?
                        <span class="text-danger">*</span></label
                      >
                      <div
                        class="form_input_radio input-check-extra"
                        style="margin-bottom: 7px"
                      >
                        <label>
                          <input
                            type="radio"
                            value="Under 2 Years"
                            v-model="form.is_operate_directly"
                          />
                          <span>Under 2 Years</span>
                        </label>
                        <label>
                          <input
                            type="radio"
                            value="2-5 Years"
                            v-model="form.is_operate_directly"
                          />
                          <span>2-5 Years</span>
                        </label>
                        <label>
                          <input
                            type="radio"
                            value="5-10 Years"
                            v-model="form.is_operate_directly"
                          />
                          <span>5-10 Years</span>
                        </label>
                        <label>
                          <input
                            type="radio"
                            value="10+ Years"
                            v-model="form.is_operate_directly"
                          />
                          <span>10+ Years</span>
                        </label>
                      </div>
                      <span class="text-danger" v-if="form.errors.is_operate_directly">{{
                        form.errors.is_operate_directly
                      }}</span>
                    </div>

                    <!-- Website -->
                    <div class="col-12 form_col">
                      <label>Website Link</label>
                      <input
                        type="text"
                        placeholder="Enter website link"
                        v-model="form.website_link"
                      />
                      <span class="text-danger" v-if="form.errors.website_link">{{
                        form.errors.website_link
                      }}</span>
                    </div>

                    <!-- Instagram -->
                    <div class="col-12 form_col">
                      <label>Instagram Link</label>
                      <input
                        type="text"
                        placeholder="Enter instagram link"
                        v-model="form.instagram_link"
                      />
                      <span class="text-danger" v-if="form.errors.instagram_link">{{
                        form.errors.instagram_link
                      }}</span>
                    </div>

                    <!-- About -->
                    <div class="col-12 form_col">
                      <label>Briefly Tell Us About Your Operation</label>
                      <textarea
                        placeholder="Type here"
                        v-model="form.about_operation"
                      ></textarea>
                      <span class="text-danger" v-if="form.errors.about_operation">{{
                        form.errors.about_operation
                      }}</span>
                    </div>

                    <!-- Password -->
                    <div class="col-md-6 form_col">
                      <label>Password <span class="text-danger">*</span></label>
                      <input
                        type="password"
                        placeholder="**********"
                        v-model="form.password"
                      />
                      <span class="text-danger" v-if="form.errors.password">{{
                        form.errors.password
                      }}</span>
                    </div>

                    <!-- Confirm Password -->
                    <div class="col-md-6 form_col">
                      <label>Confirm Password <span class="text-danger">*</span></label>
                      <input
                        type="password"
                        placeholder="**********"
                        v-model="form.confirm_password"
                      />
                      <span class="text-danger" v-if="form.errors.confirm_password">{{
                        form.errors.confirm_password
                      }}</span>
                    </div>

                    <!-- Terms -->
                    <div class="col-12 form_col">
                      <div class="form_input_check">
                        <label>
                          <input type="checkbox" v-model="form.terms" />
                          <span>Agree to the <a href="javascript:void(0)">Terms & Conditions </a> </span>
                          <span class="text-danger">*</span>
                        </label>
                        <span class="text-danger" v-if="form.errors.terms">
                          {{ form.errors.terms }}
                        </span>
                      </div>
                    </div>

                    <!-- Submit Button -->
                    <div class="col-12 form_col">
                      <button type="submit" class="cmn-butn com-btn">Sign up now</button>
                    </div>
                    <span style="margin-top: 20px">
                      <strong> Before You're Fully Live on Tuskari: </strong> <br />
                      Once you’ve signed up and entered your dashboard, you’ll be asked to
                      upload:
                      <ul>
                        <li>A copy of your <strong>Tourism Operating License</strong></li>
                        <li>Proof of <strong>Business Insurance</strong></li>
                        <li>Your <strong>Company Registration Certificate</strong></li>
                      </ul>
                      These documents are required for verification. Once approved, you’ll
                      be able to list safaris instantly and start connecting with
                      travellers.
                    </span>

                    <div class="col-12 form_col">
                      <p>
                        Already have an account?
                        <a :href="route('frontend.login')">Login</a>
                      </p>
                    </div>
                  </div>
                </form>
              </div>
            </div>

            <!-- Image Side -->
            <div class="col-lg-7 rgstrtn_rght_col">
              <div class="rght_box">
                <svg width="100%" viewBox="0 0 818 708">
                  <clipPath id="svgClipping">
                    <path
                      d="M773 0C797.853 0 818 20.1472 818 45V638C818 676.66 786.66 708 748 708H429C390.34 708 359 676.66 359 638V424C359 399.147 338.853 379 314 379H70C31.34 379 0 347.66 0 309V39C0 17.46 17.46 0 39 0H773Z"
                      fill="white"
                    />
                  </clipPath>
                  <image
                    :xlink:href="'/frontend_assets/images/right-tiger.jpg'"
                    width="100%"
                    height="100%"
                    style="clip-path: url(#svgClipping)"
                  />
                </svg>
                <div class="small_corner_img_box">
                  <img :src="'/frontend_assets/images/two-tiger.jpg'" alt="Tiger" />
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { onMounted } from "vue";
import { homeJs } from "@/custom.js";
import { router, useForm } from "@inertiajs/vue3";
import axios from "axios";
import Multiselect from "@vueform/multiselect";

const form = useForm({
  main_destination: [],
  business_type: "",
  is_operate_directly: "",
  website_link: "",
  instagram_link: "",
  about_operation: "",
  password: "",
  confirm_password: "",
  terms: false,
});

import { ref } from "vue";

const destinations = ref([
  { value: "South Africa", label: "South Africa" },
  { value: "Botswana", label: "Botswana" },
  { value: "Namibia", label: "Namibia" },
  { value: "Zambia", label: "Zambia" },
  { value: "Zimbabwe", label: "Zimbabwe" },
  { value: "Madagascar", label: "Madagascar" },
  { value: "Mozambique", label: "Mozambique" },
  { value: "Malawi", label: "Malawi" },
  { value: "Tanzania", label: "Tanzania" },
  { value: "Kenya", label: "Kenya" },
  { value: "Uganda", label: "Uganda" },
  { value: "Rwanda", label: "Rwanda" },
  { value: "Burundi", label: "Burundi" },
  { value: "Republic of Congo", label: "Republic of Congo" },
  {
    value: "Democratic Republic of Congo (DRC)",
    label: "Democratic Republic of Congo (DRC)",
  },
  { value: "Cameroon", label: "Cameroon" },
  { value: "Gabon", label: "Gabon" },
  { value: "Central African Republic", label: "Central African Republic" },
]);

const submitFinalForm = () => {
  const stepOneData = JSON.parse(sessionStorage.getItem("stepOneForm")) || {};
  const finalData = {
    ...stepOneData,
    ...form.data(),
  };

  form
    .transform(() => finalData)
    .post(route("frontend.safari-operator-register-step-two"), {
      preserveScroll: true,
      onSuccess: () => {
        sessionStorage.removeItem("stepOneForm");
      },
    });
};
onMounted(() => {
  homeJs();
});
</script>

<style src="@vueform/multiselect/themes/default.css"></style>
