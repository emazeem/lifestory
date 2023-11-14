<template>
    <Header></Header>
    <div id="main">
        <Sidebar></Sidebar>
        <div class="main-content">
            <div class="page-header">
                <div class="container-fluid d-sm-flex justify-content-between">
                    <h4 class="font-size-25">Profile</h4>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb font-size-25">
                            <li class="breadcrumb-item">
                                <a href="#">User</a>
                            </li>
                            <li class="breadcrumb-item active" aria-current="page">Profile</li>
                        </ol>
                    </nav>
                </div>
            </div>
            <div class="container-fluid font-size-25">
                <div class="row">
                    <div class="col-md-4">
                        <div class="card shadow-lg">
                            <div class="card-body text-center">
                                <figure class="avatar avatar-lg m-b-20">
                                    <img :src="userInfo.profile" class="rounded-circle" alt="...">
                                </figure>
                                <h5 class="mb-1">{{ userInfo.fname+' '+userInfo.lname }}</h5>
                            </div>
                            <hr class="m-0">
                            <div class="card-footer font-size-20 py-5">
                                <div class="row mb-2">
                                    <div class="col-6 text-muted">First Name:</div>
                                    <div class="col-6">{{ userInfo.fname }}</div>
                                </div>
                                <div class="row mb-2">
                                    <div class="col-6 text-muted">Last Name:</div>
                                    <div class="col-6">{{ userInfo.lname }}</div>
                                </div>
                                <div class="row mb-2">
                                    <div class="col-6 text-muted">Role:</div>
                                    <div class="col-6">{{ userInfo.role }}</div>
                                </div>
                                <div class="row mb-2">
                                    <div class="col-6 text-muted">Email:</div>
                                    <div class="col-6">{{ userInfo.email }}</div>
                                </div>
                            </div>
                        </div>

                    </div>
                    <div class="col-md-8">
                        <div class="card shadow-lg">
                            <div class="card-header">
                                <h6 class="font-size-25 mt-2">Change Password</h6>
                            </div>
                            <div class="card-body">

                                <div v-if="errors" class="alert alert-danger alert-dismissible fade show position-relative" role="alert">
                                    <ul>
                                        <li v-for="e in errors"> {{ e }} </li>
                                    </ul>
                                    <p class="position-absolute top-0 right-0 mr-2 cursor-pointer" @click="errors = ''" aria-hidden="true">
                                        &times;
                                    </p>
                                </div>
                                <form @submit.prevent="changePassword(formData)">
                                    <div class="form-group">
                                        <label for="old_password">Old Password</label>
                                        <div class="input-group">
                                            <input
                                                :type="passType1"
                                                v-model="formData.old_password"
                                                class="form-control"
                                                id="old_password"
                                            />
                                            <button type="button" @click="togglePasswordVisibility('old_password')" class="btn border border-left-0">
                                                <i class="fa" :class="passType1 === 'password' ? 'fa-eye' : 'fa-eye-slash'"></i>
                                            </button>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="password">Password</label>
                                        <div class="input-group">
                                            <input
                                                :type="passType2"
                                                v-model="formData.password"
                                                class="form-control"
                                                id="password"
                                            />
                                            <button type="button" @click="togglePasswordVisibility('password')" class="btn border border-left-0">
                                                <i class="fa" :class="passType2 === 'password' ? 'fa-eye' : 'fa-eye-slash'"></i>
                                            </button>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="password_confirmation">Password Confirmation</label>
                                        <div class="input-group">
                                            <input
                                                :type="passType3"
                                                v-model="formData.password_confirmation"
                                                class="form-control"
                                                id="password_confirmation"
                                            />
                                            <button type="button" @click="togglePasswordVisibility('password_confirmation')" class="btn border border-left-0">
                                                <i class="fa" :class="passType3 === 'password' ? 'fa-eye' : 'fa-eye-slash'"></i>
                                            </button>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for=""></label>
                                        <button type="submit" class="btn float-end btn-success">Change</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-12">

                    </div>
                </div>

            </div>
        </div>
    </div>
</template>

<script>
import Header from '../../components/admin/Header.vue';
import Sidebar from '../../components/admin/Sidebar.vue';

export default {
    name:'Profile',
    data(){
        return {
            passType1:'password',
            passType2:'password',
            passType3:'password',
            userInfo:{},
            errors: '',
            formData:{
                old_password:'',
                password:'',
                password_confirmation:''
            }
        }
    },
    components:{
        Header,
        Sidebar,
    },
    methods:{
        togglePasswordVisibility(field)
        {
            if (field === 'old_password') {
                this.passType1 = this.passType1 === 'password' ? 'text' : 'password';
            } else if (field === 'password') {
                this.passType2 = this.passType2 === 'password' ? 'text' : 'password';
            } else if (field === 'password_confirmation') {
                this.passType3 = this.passType3 === 'password' ? 'text' : 'password';
            }
        },

        changePassword(formData)
        {
            this.errors = '';
            this.axios.post("user/profile/update", formData).then((data) => {
                    this.$moshaToast({
                    title: 'Password changed successfully',
                    },
                    {
                    type: 'success',
                    showIcon: true,
                    hideProgressBar: true,
                    });
                    this.formData = {
                        old_password:'',
                        password:'',
                        password_confirmation:''
                    };
                })
                .catch(function (error) {
                    this.errors = this.$flattenErrors(error);
                }.bind(this));
        }
    },
    mounted()
    {
        this.userInfo = JSON.parse(localStorage.getItem("user"));
    }
}
</script>
