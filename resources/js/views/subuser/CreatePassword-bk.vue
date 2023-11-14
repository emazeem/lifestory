<template>
    <div class="page bg-gray-200">
        <div class="page-main">
            <Sidebar />
            <div class="container py-5">
                <div class="row align-items-center justify-content-center">
                    <div class="col-md-6">
                        <div class="card shadow-lg py-5">
                            <div class="card-body my-5 px-5">
                                <center>
                                    <div class="col-md-4 col-8 mb-4">
                                        <img src='../../public/icons/password.png' class="create-password-img" alt="" >
                                    </div>
                                </center>
                                <h5 class="text-center mb-3">Please create a password for your new account.</h5>
                                <FormErrors :errors="errors"></FormErrors>
                                <form method="POST" @submit.prevent="resetPassword(form)">
                                    <div class="input-group">
                                        <input  v-model="form.password" :type="passType1" placeholder="Password" class="form-control mb-0">
                                        <button type="button" @click="togglePasswordVisibility('password')" class="btn border border-left-0">
                                            <i class="fa" :class="passType1 === 'password' ? 'fa-eye' : 'fa-eye-slash'"></i>
                                        </button>
                                    </div>
                                    <div class="input-group mt-2">
                                        <input id="email" v-model="form.password_confirmation" :type="passType2" placeholder="Confirm Password" class="form-control mb-0">
                                        <button type="button" @click="togglePasswordVisibility('password_confirmation')" class="btn border border-left-0">
                                            <i class="fa" :class="passType2 === 'password' ? 'fa-eye' : 'fa-eye-slash'"></i>
                                        </button>
                                    </div>
                                    <div class="text-center mt-3">
                                        <button class="btn btn-block btn-primary">Create</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="">
            <div class="side-app">
                <Footer></Footer>
            </div>
        </div>
    </div>
</template>

<script >

import Sidebar from '../../components/front-end/Sidebar.vue';
import Footer from '../../components/front-end/Footer.vue';
import FormErrors from "../../components/front-end/FormErrors.vue";

export default {
    name: 'CreatePassword',
    components:{
        FormErrors,
        Sidebar,
        Footer,
    },
    data(){
        return {
            token:'',
            subuser_id: '',
            passType1:'password',
            passType2:'password',
            form:{
                password:'',
                password_confirmation:'',
                id:''
            },
            errors:''
        }
    },
    methods:{
        togglePasswordVisibility(field)
        {
            if (field === 'password') {
                this.passType1 = this.passType1 === 'password' ? 'text' : 'password';
            } else if (field === 'password_confirmation') {
                this.passType2 = this.passType2 === 'password' ? 'text' : 'password';
            }
        },
        resetPassword(data)
        {
            this.errors='';
            this.axios.post('create-new-password',data).then(() => {
                this.$moshaToast({
                        title: 'Success',
                        description: 'Password changed successfully. Try login now',
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
        checkToken(token)
        {
            this.axios.get('user/check-token/'+token).then((response) => {
                this.form.id=response.data.data.subuser_id;
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
