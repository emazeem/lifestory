<template>
    <div class="page" style="position: relative">
        <div class="page-main">
            <div class="landing-top-header">
                <Sidebar />
            </div>

            <div class="container-fluid px-md-5 bg-light-c py-5 ">
                <div class="col-md-12 text-right mr-0 pr-0">
                        <span class="font-size-20 btn ct-p btn-primary mb-2 box-shadow-0 cursor-pointer border-0" @click="show=!show">
                            Invite Friends & Family <b>{{!show?'+':'-'}}</b>
                        </span>
                </div>
                <div class="card shadow-lg" v-if="show">
                    <div class="card-body"  >
                        <div v-if="errors" class="alert alert-danger alert-dismissible fade show position-relative" role="alert">
                            <ul>
                                <li v-for="(e,index) in errors" :key="index"> {{ e }} </li>
                            </ul>
                            <p class="position-absolute top-0 right-0 mr-2 cursor-pointer" @click="errors = ''" aria-hidden="true">
                                &times;
                            </p>
                        </div>

                        <div class="table-responsive">
                            <table class="table table-bordered table-hover">
                                <thead class="bg-dark ">
                                <tr class="ct-p">
                                    <th>Name</th>
                                    <th>Email</th>
                                    <th>Phone <small>(optional)</small></th>
                                    <th class="d-none">Relationship</th>
                                    <th>Action</th>
                                </tr>
                                </thead>
                                <tbody>
                                <tr v-for="(row, index) in rows" :key="index">
                                    <td >
                                        <div class="row">
                                            <div class="col-md-6 col-12">
                                                <input type="text" class="form-control form-control-sm-view ct-form-control" v-model="row.fname" placeholder="First Name">
                                            </div>
                                            <div class="col-md-6 col-12">
                                                <input type="text" class="form-control form-control-sm ct-form-control" v-model="row.lname" placeholder="Last Name">
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="row">
                                            <div class="col-md-6 col-12 ">
                                                <div class="d-flex justify-content-start">
                                                    <input type="email" class="form-control form-sm" v-model="row.email" placeholder="Email">
                                                <span class="text-red"> *</span>
                                                </div>
                                            </div>
                                            <div class="col-md-6 col-12">
                                                <div class="d-flex justify-content-start">
                                                <input type="email" class="form-control form-sm" v-model="row.confirmation_email" placeholder="Confirm Email">
                                                <span class="text-red"> *</span>
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <input type="text" autocomplete="off" class="form-control form-control-sm-view ct-form-control" v-model="row.phone" placeholder="Phone">
                                    </td>
                                    <td class="d-none">
                                        <select class="form-control form-control-sm form-select ct-form-control" v-model="row.relationship">
                                            <option value="">Select Relationship</option>
                                            <option v-for="item in $relationships()" :key="item" :value="item">{{ item }}</option>
                                        </select>
                                    </td>
                                    <td>

                                        <button v-if="index==0" title="Add new row" class="btn btn-sm btn-primary ct-form-control-icon" @click="addRow"><i class="fa fa-plus"></i></button>
                                        <button v-else  class="btn btn-sm btn-danger" @click="removeRow(index)"><i class="fa fa-minus"></i></button>
                                    </td>
                                </tr>
                                </tbody>
                            </table>
                            <button @click="addRelatives()" class="btn btn-primary float-right ct-p" :disabled="btnDisabled">{{ btnDisabled?'Loading...':'Submit'}}</button>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div v-for="(user, index) in subUsers" :key="index" class="col-md-3" v-if="subUsers.length>0">
                        <div class="card py-5">
                            <div class="card-body text-center position-relative">
                                <span class="d-none badge badge-warning position-absolute" style="right: 10px;top: 10px">{{ user.pivot.relationship }}</span>
                                <figure class="avatar avatar-xl m-b-20">
                                    <img :src="user.profile" id="profile-image"  class="rounded-circle profile-image cursor-pointer" alt="...">
                                    <input type="file" style="display: none" id="image" @change="readImageData($event)">
                                </figure>
                                <hr>
                                <h5 class="mb-1 ct-card-header">{{user.fullName}}</h5>
                                <p class="text-muted ct-p">{{ user.email }}</p>
                                <p>{{ user.bio }}</p>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-12" v-else>
                        <div class="card py-5">
                            <div class="col-md-12 text-center w-100 py-5">
                                <img src="../../public/no_post.png" alt="" class="img-fluid py-5">
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
        <div class="">
            <div class="side-app">
                <!-- CONTAINER -->
                <div class="main-container">
                    <!-- </Footer> -->
                    <Footer></Footer>
                    <!-- Footer close -->

                </div>
            </div>
            <!-- CONTAINER CLOSED -->
        </div>
    </div>

</template>

<script>

    import Sidebar from '../../components/front-end/Sidebar.vue';
    import Footer from '../../components/front-end/Footer.vue';
    import axios from "axios";
    import moment from 'moment';
    import Loader from "../front-end/Loader.vue";


    export default {
        name: 'FriendsAndFamily',
        data(){
            return {
                btnDisabled:false,
                show:false,
                id:'',
                isLoading:true,
                err: '',
                errors: '',
                subUsers:[],
                rows: [
                    {
                        name: '',
                        email: '',
                        confirmation_email: '',
                        phone: '',
                        relationship: 'Unknown'
                    }
                ],
            }
        },
        components: {
            Sidebar,
            Footer,
            Loader
        },
        methods:{

            addRow() {
                this.rows.push({
                    fname: '',
                    lname: '',
                    email: '',
                    confirmation_email: '',
                    phone: '',
                    relationship: 'Unknown'
                });
            },
            removeRow(index) {
                if (this.rows.length!=1){
                    this.rows.splice(index, 1);
                }
            },
            getSubUsers() {
                this.axios.post('/user/fetch-sub-users').then(function (res) {
                    this.subUsers = res.data.data;
                }.bind(this)).finally(()=>{ this.$updateFont(); });

            },
            async addRelatives()
            {
                this.errors = '';
                this.btnDisabled = true;
                await this.axios.post("/user/add-relatives/0", this.rows).then((data) => {
                    this.$moshaToast({
                            title:data.data.message,
                        },
                        {
                            type: 'success',
                            showIcon: true,
                            hideProgressBar: true
                        });
                    this.rows = [
                        {
                            fname: '',
                            lname: '',
                            email: '',
                            phone: '',
                            relationship: 'Unknown'
                        }
                    ];
                    this.getSubUsers();
                }).catch(function (error) {
                        this.errors = error.response.data.message;
                    }.bind(this)).finally(()=>{
                        this.btnDisabled = false;
                    });
            },

        },
        mounted() {
            this.getSubUsers();

        }
    }

</script>
<style scoped>
@media (max-width: 400px) {
    .form-control-sm-view{
        width: 200px;
    }

}
</style>
