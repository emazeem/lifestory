<template>
    <div class="page bg-gray-200">
        <div class="page-main">
            <Sidebar />
            <div class="col-lg-8 login-page-section mx-auto align-items-center">
                <div class="card shadow-lg bg-white login-page-card">
                    <div class="card-body p-md-0">
                        <div class="d-flex">
                            <div class="col-lg-4 d-none d-md-block d-lg-block login-form-img-div"
                                style="background-image: url('../front-end-assets/forgot-password.png');">
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
                                    <h5 class="login-page-heading text-center">Forgot Password?</h5>
                                    <div class="col-lg-12">
                                        <hr class="loging-page-hr">
                                    </div>
                                    <p class="forget-pass-para-two">Enter your email address to receive a password reset
                                        link.</p>
                                    <div class="col-lg-10 mx-auto">
                                        <form class="mt-5" method="POST" @submit.prevent="resetPassword(form)">
                                            <div class="form-group">
                                                <input id="email" v-model="form.email" type="email" placeholder="Email"
                                                    class="form-control login-form-control" name="email" autocomplete="off"
                                                    autofocus>
                                            </div>

                                            <div class="text-center sign-in-button-wrapper">
                                                <button class="btn btn-primary sign-in-button mt-2">Reset password</button>
                                            </div>
                                            <div class="text-center back-button-wrapper mt-3">
                                                <button type="button" class="btn btn-primary back-button sign-in-button"
                                                    @click="$router.go(-1)">Back</button>
                                            </div>
                                        </form>
                                        <p class="already-account">Already have an Account? <router-link class="sign-in-link" to="/login">Sign in</router-link></p>
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

import Footer from '../../components/front-end/Footer.vue';
import Sidebar from '../../components/front-end/Sidebar.vue';
import FormErrors from "../../components/front-end/FormErrors.vue";

export default {
    name: 'ForgetPassword',
    components: {
        FormErrors,
        Sidebar,
        Footer,
    },
    data() {
        return {
            form: {
                email: '',
            },
            errors: '',
            isLoading: false,
        }
    },
    methods: {
        resetPassword(data) {
            this.errors = '';
            this.isLoading = true;
            this.axios.post('reset/password', data).then((response) => {
                this.$moshaToast({ title: 'Success', description: response.data.message }, { type: 'success', showIcon: true, hideProgressBar: true });
                this.$router.push('/login');
            }).catch((error) => {
                this.errors = this.$flattenErrors(error);
            }).finally(() => {
                this.isLoading = false;
            });
        }
    }
}

</script>

<style>
p.forget-pass-para-two {
    font-family: "Oswald", serif;
    font-size: 18px;
    font-weight: 300;
    line-height: 18px;
    letter-spacing: 0em;
    height: 20px;
    text-align: center;
    color: #000000;
}

p.already-account {
    font-family: "Oswald", serif;
    font-size: 18px;
    font-weight: 300;
    line-height: 16px;
    letter-spacing: 0em;
    text-align: center;
}


p.already-account {
    font-family: "Oswald", serif;
    font-size: 18px;
    font-weight: 300;
    line-height: 16px;
    letter-spacing: 0em;
    text-align: center;
    margin-top: 30px;
    color: #000000 !important;
}

.sign-in-link {
    color: #6096B4 !important;
}



</style>
