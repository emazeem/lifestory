<template>
    <div class="page bg-gray-200">
        <div class="page-main">
            <Sidebar />
            <div class="col-lg-8 login-page-section mx-auto align-items-center">
                <div class="card shadow-lg bg-white login-page-card">
                    <div class="card-body p-md-0">
                        <div class="d-flex">
                            <div class="col-lg-4 d-none d-md-block d-lg-block login-form-img-div"
                                style="background-image: url('../front-end-assets/login-page-img.png');">
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
                                    <form-errors :errors="errors" />
                                    <div class="col-md-12">
                                        <h5 class="login-page-heading">Create password for your new account.</h5>
                                    </div>
                                    <div class="col-lg-12">
                                        <hr class="loging-page-hr">
                                    </div>
                                    <div class="col-lg-10 mx-auto">
                                        <form class="mt-5" method="POST" @submit.prevent="createPassword(form)">

                                            <div class="input-group input-with-i">
                                                <input id="password" v-model="form.password" :type="passType1"
                                                    placeholder="Password" class="form-control login-form-control"
                                                    name="password">

                                                <i class="fa" @click="togglePasswordVisibility('password')"
                                                    :class="passType1 === 'password' ? 'fa-eye' : 'fa-eye-slash'"></i>

                                            </div>

                                            <div class="input-group input-with-i mt-3">
                                                <input id="password" v-model="form.password_confirmation" :type="passType2"
                                                    placeholder="Confirm Password" class="form-control login-form-control"
                                                    name="password">

                                                <i class="fa" @click="togglePasswordVisibility('password_confirmation')"
                                                    :class="passType2 === 'password' ? 'fa-eye' : 'fa-eye-slash'"></i>

                                            </div>
                                            <div class="text-center mt-3 sign-in-button-wrapper">
                                                <button type="submit" class="btn btn-primary sign-in-button">Create</button>
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
    name: 'CreatePassword',
    components: {
        FormErrors,
        Sidebar,
        Footer,
    },
    data() {
        return {
            token: '',
            subuser_id: '',
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
        createPassword(data) {
            this.errors = '';
            this.axios.post('create-new-password', data).then(() => {
                this.$moshaToast({
                    title: 'Success',
                    description: 'Password Created successfully. Try login now',
                },
                    {
                        type: 'success',
                        showIcon: true,
                        hideProgressBar: true,
                    });
                this.$router.push('/login');
            }).catch((error) => {
                this.errors = this.$flattenErrors(error);
            });
        },
        checkToken(token) {
            this.axios.get('user/check-token/' + token).then((response) => {
                this.form.id = response.data.data.subuser_id;
            }).catch(() => {
                this.$router.push('/error');
            });
        }
    },
    mounted() {
        this.token = this.$route.params.token;
        this.checkToken(this.token);
    }
}
</script>
