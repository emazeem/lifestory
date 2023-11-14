<template>
    <div class="page bg-gray-200">
        <div class="page-main">
            <Sidebar />
            <div class="col-lg-8 login-page-section mx-auto align-items-center">
                <div class="card shadow-lg bg-white login-page-card">
                    <div class="card-body p-md-0">
                        <div class="d-flex">
                            <div class="col-lg-4 d-none d-md-block d-lg-block login-form-img-div"
                                style="background-image: url('../../front-end-assets/change-password.png');">
                                <div class="background-with-text">
                                    <div class="centered-text">
                                        <h1>New Here?</h1>
                                        <p>Schedule your lifestory now</p>
                                        <router-link to="/schedule-lifestory"
                                            class="btn text-white login-book-session btn-w-md py-2">Schedule
                                            Lifestory</router-link>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-8 login-input-form-section col-12 text-center d-flex align-items-center">
                                <div class="col-lg-10 col-md-10 col-12 mx-md-auto login-form-wrapper">
                                    <FormErrors :errors="errors" />
                                    <h5 class="login-page-heading text-center">Create New Password</h5>
                                    <div class="col-lg-12">
                                        <hr class="loging-page-hr">
                                    </div>
                                    <div class="col-lg-10 mx-auto">
                                        <form class="mt-5" method="POST" @submit.prevent="changePassword(form)">
                                            <div class="input-group input-with-i">
                                                <input id="password" v-model="form.password" placeholder="Password"
                                                    :type="passType1" class="form-control login-form-control"
                                                    name="password">

                                                <i class="fa" @click="togglePasswordVisibility('password')"
                                                    :class="passType1 === 'password' ? 'fa-eye' : 'fa-eye-slash'"></i>

                                            </div>
                                            <div class="input-group mt-3 input-with-i">
                                                <input id="password_confirmation" v-model="form.password_confirmation" :type="passType2"
                                                    placeholder="Confirm Password" class="form-control login-form-control"
                                                    name="password">

                                                <i class="fa" @click="togglePasswordVisibility('password_confirmation')"
                                                    :class="passType2 === 'password' ? 'fa-eye' : 'fa-eye-slash'"></i>

                                            </div>

                                            <div class="text-center sign-in-button-wrapper mt-5">
                                                <button type="submit" class="btn btn-primary sign-in-button">Change</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div>
            <Footer />
        </div>
    </div>
</template>

<script >

import Sidebar from '../../components/front-end/Sidebar.vue';
import Footer from '../../components/front-end/Footer.vue';
import FormErrors from "../../components/front-end/FormErrors.vue";

export default {
    name: 'CreateNewPassword',
    components: {
        FormErrors,
        Sidebar,
        Footer,
    },
    data() {
        return {
            passType1: 'password',
            passType2: 'password',
            form: {
                password: '',
                password_confirmation: '',
                id: ''
            },
            errors: ''
        }
    },
    methods: {
        togglePasswordVisibility(field) {
            if (field === 'password') {
                this.passType1 = this.passType1 === 'password' ? 'text' : 'password';
            } else if (field === 'password_confirmation') {
                this.passType2 = this.passType2 === 'password' ? 'text' : 'password';
            }
        },
        changePassword(data) {
            console.log(data);
            this.errors = '';
            if (data.id) {
                this.axios.post('create-new-password', data).then(() => {
                    this.$moshaToast({ title: 'Success', description: 'Password changed successfully. Try login now' },
                        { type: 'success', showIcon: true, hideProgressBar: true });
                    this.$router.push('/login');
                }).catch((error) => {
                    this.errors = this.$flattenErrors(error);
                });
            } else {
                this.$moshaToast({ title: 'Success', description: 'Reset password link is invalid or expired' }, { type: 'danger', showIcon: true, hideProgressBar: true });
            }
        },
        checkRememberToken(id, token) {
            this.axios.post('check-remember-token-reset-password', { id: id, token: token }).then((response) => {
                this.form.id = id;
            }).catch(() => {
                this.$moshaToast({ title: 'Success', description: 'Reset password link is invalid or expired' }, { type: 'danger', showIcon: true, hideProgressBar: true });
            });
        }
    },
    mounted() {
        const id = this.$route.params.id;
        const token = this.$route.params.token;
        this.checkRememberToken(id, token);
    }
}




</script>
