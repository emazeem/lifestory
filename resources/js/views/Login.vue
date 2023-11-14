<template>
    <div class="page bg-gray-200">
        <div class="page-main">
            <Sidebar />
            <div class="col-lg-8 login-page-section mx-auto align-items-center">
                <div class="card shadow-lg bg-white login-page-card">
                    <div class="card-body p-md-0">
                        <div class="d-flex">
                            <div class="col-lg-4 d-none d-md-block d-lg-block login-form-img-div" style="background-image: url('../front-end-assets/login-page-img.png');">
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
                                    <form-errors :errors="errors"/>
                                    <h5 class="login-page-heading">Please enter your email address and password to login.
                                    </h5>
                                    <div class="col-lg-12">
                                        <hr class="loging-page-hr">
                                    </div>
                                    <div class="col-lg-10 mx-auto">
                                        <form class="mt-5" method="POST" @submit.prevent="login(form)">
                                            <div class="form-group">
                                                <input id="email" v-model="form.email" type="email" placeholder="Email"
                                                    class="form-control login-form-control" name="email" autocomplete="off"
                                                    autofocus>
                                            </div>
                                            <div class="input-group input-with-i">
                                                <input id="password" v-model="form.password" placeholder="Password"
                                                    :type="passType" class="form-control login-form-control" name="password">
    
                                                <i class="fa" @click="togglePasswordVisibility()"
                                                    :class="passType === 'password' ? 'fa-eye' : 'fa-eye-slash'"></i>
    
                                            </div>
    
                                            <div class="form-group d-flex justify-content-end mt-3 mb-4">
                                                <div>
                                                    <router-link to="/forget-password" class="forget-link">Forget
                                                        Password?</router-link>
                                                </div>
                                            </div>
                                            <div class="text-center sign-in-button-wrapper">
                                                <button class="btn btn-primary sign-in-button">Sign in</button>
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
<script>
import Sidebar from "../components/front-end/Sidebar.vue";
import Footer from "../components/front-end/Footer.vue";
import formErrors from "../components/front-end/FormErrors.vue";
export default {
    name: 'Login',
    components: {
        formErrors,
        Footer,
        Sidebar,
    },
    data() {
        return {
            passType: 'password',
            form: {
                email: '',
                password: '',
            },
            errors: '',
        }
    },
    methods: {
        togglePasswordVisibility() {
            this.passType = this.passType === 'password' ? 'text' : 'password';
        },
        login(data) {
            this.errors = '';
            this.axios.post('login', data).then((response) => {
                localStorage.removeItem('user');
                localStorage.setItem('user', JSON.stringify(response.data.user));
                localStorage.setItem('sanctum_token', response.data.token);
                const expirationDate = new Date().getTime() + 7 * 24 * 60 * 60 * 1000; // 7 days in milliseconds
                localStorage.setItem('expiration_date', expirationDate);
                if (response.data.user.role == 'Super Admin' || response.data.user.role == 'Developer') {
                    this.$router.push('/dashboard');
                } else {
                    if (response.data.user.role == 'Sub User') {
                        this.$router.push('/switch-account')
                    } else {
                        if (response.data.switch == true) {
                            this.$router.push('/switch-account')
                        } else {
                            this.$router.push('/home')
                        }
                    }
                    z
                }
            }).catch((error) => {
                this.errors = this.$flattenErrors(error);
            });
        }
    }
}
</script>