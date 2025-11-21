<template lang="">
   <div>
        <Head title="Admin Login" />
        <div class="welcome_scr">
            <div class="container">
                <div class="scr_body">
                    <div class="src_logo_area">
                        <!-- <a href="/admin/login" class=""><img :src="'/admin_assets/logo/logo1.png'" alt=""></a> -->
                        <a href="/admin/login" class=""><img :src="'/frontend_assets/Final Tuskari Logo White.svg'" alt=""></a>

                    <!-- <h2>{{ $page.props.appName }}</h2> -->
                    </div>
                    <h1 class="card-title text-center">Admin Login</h1>

                    <form @submit.prevent="submit">
                        <div class="form-group">
                            <label for="email">Email <span class="text-danger">*</span></label>
                            <input type="email" id="email" v-model="form.email" class="form-control" placeholder="Email" >
                            <span class="text-danger" v-if="form.errors.email">{{ form.errors.email }}</span>
                        </div>
            
                        <div class="form-group">
                            <label for="password">Password <span class="text-danger">*</span></label>
                            <div class="password_box">
                            <input :type="showPassword ? 'text' : 'password'" id="password" v-model="form.password" class="form-control" placeholder="Password">
                            <div class="control">                        
                                <span class="icon is-small is-right">
                                        <i @click="toggleShow" class="fas" :class="{ 'fa-eye-slash': showPassword, 'fa-eye': !showPassword }"></i>
                                </span>
                            </div>
                            </div>
                            <span class="text-danger" v-if="form.errors.password">{{ form.errors.password }}</span>
                        </div>

                        <div class="scr_footer">
                            <Link :href="route('admin.forgotPassword')">Forgot Password?</Link>
                            <button type="submit" class="btn btn-primary"  :disabled="form.processing">{{ form.processing ? 'Logging in...' : 'Login'}}</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
   </div>
  </template>

<script setup>
import { reactive, ref } from 'vue';
import { useForm } from '@inertiajs/vue3';
import SubmitButton from '../../components/SubmitButton.vue';

const form = useForm({
    email: null,
    password: null,
});

defineProps({
    errors: Object
});

function submit() {
    form.post(route("admin.login-submit"));
}

const showPassword = ref(false);

const toggleShow = async () => {
    showPassword.value = !showPassword.value;
}
</script>

<style>
@import "/public/admin_assets/custom.css";
</style>