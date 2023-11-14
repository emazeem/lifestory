<template>
    <Header/>
    <div id="main">
        <Sidebar/>
        <div class="main-content">
            <div class="page-header">
                <div class="container-fluid d-sm-flex justify-content-between">
                    <h4 class="font-size-25">{{user.role}} View</h4>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb font-size-25">
                            <li class="breadcrumb-item">
                                <router-link to="/dashboard">Dashboard</router-link>
                            </li>
                            <li class="breadcrumb-item active" aria-current="page">{{user.role}} Details</li>
                        </ol>
                    </nav>
                </div>
            </div>

            <div class="container-fluid">
                <div class="row">

                    <div class="col-md-8">
                        <div class="card shadow-lg">
                            <div class="card-header d-flex justify-content-between">
                                <h5 class="font-size-25">{{user.role}} Details</h5>
                                <button class="btn btn-primary" @click="changeOption()"><i class="fa fa-edit"></i></button>
                            </div>
                            <div class="card-body">
                                <div v-if="update_errors" class="alert alert-danger alert-dismissible fade show position-relative" role="alert">
                                    <ul>
                                        <li v-for="(e,index) in update_errors" :key="index"> {{ e }} </li>
                                    </ul>
                                    <p class="position-absolute top-0 right-0 mr-2 cursor-pointer" @click="update_errors = ''" aria-hidden="true">
                                        &times;
                                    </p>
                                </div>
                            <div class="table-responsive">
                                <table class="table font-size-20 table-sm table-bordered  table-hover">
                                    <tbody v-if="user && !editUser">
                                        <tr>
                                            <th class="font-weight-bold">Name</th>
                                            <td>{{user.fullName}}</td>
                                        </tr>
                                        <tr>
                                            <th class="font-weight-bold">Email</th>
                                            <td>{{user.email}}</td>

                                        </tr>
                                        <tr>
                                            <th class="font-weight-bold">Phone</th>
                                            <td>{{user.contact}}</td>

                                        </tr>
                                        <tr v-if="user.role!='Super Admin'">
                                            <th class="font-weight-bold">City</th>
                                            <td>{{user.details.city}}</td>
                                        </tr>
                                        <tr v-if="user.role!='Super Admin'">
                                            <th class="font-weight-bold">State</th>
                                            <td >{{user.details.state}}</td>
                                        </tr>
                                        <tr v-if="user.role!='Super Admin'">
                                            <th class="font-weight-bold">Country</th>
                                            <td>{{user.details.country}}</td>
                                        </tr>
                                        <tr v-if="user.role=='Customer'">
                                            <th class="font-weight-bold">Zip Code</th>
                                            <td v-if="user.details && user.details.zip_code">{{user.details.zip_code}}</td>
                                        </tr>
                                        <tr v-if="user.role=='Customer'">
                                            <th class="font-weight-bold">Date of Birth</th>
                                            <td v-if="user.details && user.details.dob">{{ $date(user.details.dob) }}</td>
                                        </tr>
                                    </tbody>
                                    <tbody v-show="user && editUser">
                                        <tr>
                                            <th class="font-weight-bold">First Name</th>
                                            <td>
                                                <input id="fname" v-model="user.fname" type="text" placeholder="First Name" class="form-control mb-0 rounded-0">
                                            </td>
                                        </tr>
                                        <tr>
                                            <th class="font-weight-bold">Last Name</th>
                                            <td>
                                                <input id="lname" v-model="user.lname" type="text" placeholder="Last Name" class="form-control mb-0 rounded-0">
                                            </td>
                                        </tr>
                                        <tr>
                                            <th class="font-weight-bold">Email</th>
                                            <td>
                                                <input id="email" readonly v-model="user.email" type="text" placeholder="Email" class="form-control mb-0 rounded-0">
                                            </td>
                                        </tr>
                                        <tr>
                                            <th class="font-weight-bold">Country</th>
                                            <td>
                                                <select name="country" @change="fetchStates($event.target.selectedOptions[0].getAttribute('data-shortname'))" id="fetch-state" class="form-control" v-model="user.details.country">
                                                    <option value="''">-- Select Country</option>
                                                    <option v-for="country in countries" :value="country.name" :data-shortname="country.iso2">{{ country.name }}</option>
                                                </select>
                                            </td>
                                        </tr>
                                        <tr>
                                            <th class="font-weight-bold">City</th>
                                            <td>
                                                <input type="text" v-model="user.details.city" class="form-control">
                                            </td>
                                        </tr>
                                        <tr>
                                            <th class="font-weight-bold">State</th>
                                            <td>
                                                <select name="state" id="state" class="form-control" v-model="user.details.state">
                                                    <option value="">-- Select State</option>
                                                    <option v-for="state in states" :data-stateshortname="state.iso2" :value="state.name">{{ state.name }}</option>
                                                </select>
                                            </td>
                                        </tr>
                                        <tr>
                                            <th class="font-weight-bold">Phone</th>
                                            <td>
                                                <input id="contact" v-model="user.contact" type="text" placeholder="Contact" class="form-control mb-0 rounded-0">
                                            </td>
                                        </tr>
                                        <tr v-if="user.role == 'Customer'">
                                            <th class="font-weight-bold">Zip code</th>
                                            <td>
                                                <input id="zip_code" min="1" v-model="user.details.zip_code" type="text" placeholder="Zip code " class="form-control mb-0 rounded-0">
                                            </td>
                                        </tr>
                                        <tr v-if="user.role == 'Customer'">
                                            <th class="font-weight-bold">Date of birth</th>
                                            <td>
                                                <input id="dob" v-model="user.details.dob" type="date" class="form-control mb-0 rounded-0">
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                                <button v-if="editUser" @click="updateUser()" class="btn btn-primary float-right">Submit</button>
                            </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4 float-end">
                    <div class="card shadow-lg" v-if="user.role=='Sub User' || (user.role=='Customer' && user.session.payment)">
                        <div class="card-header d-flex justify-content-between">
                            <h5 :class="(user.account_created?'text-success':'text-danger')" class="font-size-25">{{ user.account_created?'Change Password':'Create Account' }}</h5>
                            <button class="btn btn-primary" @click="updateCustomer()">Save</button>
                        </div>
                        <div v-show="user.account_created" class="card-header">
                            <h6>Password is created and saved successfully</h6>
                        </div>
                            <div class="card-body font-size-25">
                                <div v-if="password_errors" class="alert alert-danger alert-dismissible fade show position-relative" role="alert">
                                    <ul>
                                        <li v-for="(e,index) in password_errors" :key="index"> {{ e }} </li>
                                    </ul>
                                    <p class="position-absolute top-0 right-0 mr-2 cursor-pointer" @click="password_errors = ''" aria-hidden="true">
                                        &times;
                                    </p>
                                </div>
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                        <label>Password</label>
                                        <input autocomplete="off" :type="passType" class="form-control" v-model="formData.password" :placeholder="(user.account_created?'*******':'Password')">
                                        </div>
                                    </div>
                                    <div class="col-md-12 mt-2">
                                        <div class="form-group">
                                        <label for="">Confirm Password</label>
                                        <input autocomplete="off" :type="passType" class="form-control" v-model="formData.password_confirmation" :placeholder="(user.account_created?'*******':'Confirm Password')">
                                        </div>
                                    </div>
                                    <div class="col-md-12 d-flex justify-content-between mt-3">
                                        <button @click="generatePassword()" class="btn btn-dark">Generate Password</button>
                                        <button @click="ank()" class="btn btn-dark">{{ passType=='password'?'Show':'Hide'  }}</button>
                                    </div>
                                    <div class="col-md-12 mt-2">

                                    </div>
                                </div>
                           </div>
                    </div>
                    <div v-else class="mb-2">
                        <img class="w-50" src="../../../public/blur_account_create.png" alt="payment required">
                    </div>
                </div>

                </div>
                <div class="row">
                <div v-if="user.role=='Customer'" class="col-md-12">
                    <div class="card shadow-lg">
                        <div class="card-header d-flex justify-content-between ">
                            <h5 class="font-size-25">Invited Friends and Family</h5>
                        </div>
                    <div class="card-body">
                        <div v-if="errors" class="alert alert-danger alert-dismissible fade show position-relative" role="alert">
                            <ul>
                                <li v-for="(e,index) in errors" :key="index"> {{ e }} </li>
                            </ul>
                            <p class="position-absolute top-0 right-0 mr-2 cursor-pointer" @click="errors = ''" aria-hidden="true">
                                &times;
                            </p>
                        </div>
                        <div class="table-responsive font-size-20">
                            <table class="table table-bordered table-hover">
                                <thead class="bg-dark ">
                                <tr class="text-white">
                                    <th>Name</th>
                                    <th>Email</th>
                                    <th>Phone <small>(Optional)</small></th>
                                    <th class="d-none">Relationship</th>
                                    <th>Status</th>
                                    <th>Action</th>
                                </tr>
                                </thead>
                                <tbody>
                                    <tr  v-for="(row, index) in rows" :key="index">
                                        <td>
                                            <div class="row">
                                                <div class="col-md-6 col-12">
                                                    <div class="d-flex justify-content-start">
                                                    <input type="text" class="form-control form-control-sm-view"  v-model="row.fname" placeholder="First Name">
                                                    <span class="text-red"> *</span>
                                                    </div>
                                                </div>
                                                <div class="col-md-6 col-12">
                                                    <div class="d-flex justify-content-start">
                                                    <input type="text" class="form-control " v-model="row.lname" placeholder="Last Name">
                                                    <span class="text-red"> *</span>
                                                    </div>
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
                                            <input type="text" autocomplete="off" class="form-control form-control-sm-view" v-model="row.phone" placeholder="Phone">
                                        </td>
                                        <td></td>
                                        <td class="d-none">
                                          <select class="form-control  form-select" v-model="row.relationship">
                                            <option value="">Select Relationship</option>
                                            <option v-for="item in $relationships()" :key="item" :value="item">{{ item }}</option>
                                          </select>
                                        </td>
                                        <td>
                                            <button v-if="index==0" class="btn btn-sm btn-primary" @click="addRow"><i class="fa fa-plus"></i></button>
                                            <button v-else class="btn btn-sm btn-danger" @click="removeRow(index)"><i class="fa fa-minus"></i></button>
                                        </td>
                                    </tr>
                                <tr v-for="(user,index) in user.sub_users" :key="index">
                                    <td>{{user.fullName}}</td>
                                    <td>{{user.email}}</td>
                                    <td>{{user.contact}}</td>
                                    <td class="d-none" v-if="user.pivot.relationship">{{user.pivot.relationship}}</td>
                                    <td>{{user.is_active?'Created':'Invited'}}</td>
                                    
                                </tr>
                                </tbody>
                                <tfoot>
                                <tr>
                                    <td colspan="6">
                                        <button @click="addRelatives()" class="btn btn-primary float-right" :disabled="btnDisabled">{{ btnDisabled?'Loading...':'Submit' }}</button>
                                    </td>
                                </tr>
                                </tfoot>
                            </table>
                       </div>
                   </div>
                    </div>
                </div>
            </div>
            </div>
        </div>
    </div>
</template>

<script>
import Header from "../../../components/admin/Header.vue";
import Sidebar from "../../../components/admin/Sidebar.vue";

export default {
    name: "UserView",
    components: {
        Header,
        Sidebar,
    },
    data() {
        return {
            passType:'text',
            user_id:'',
            formData:{
                password:'',
                password_confirmation:'',
            },
            user: {
                details:{
                    country:'',
                    state:'',
                    city:''
                }
            },
            id:'',
            errors:'',
            editUser:false,
            password_errors:'',
            rows: [
                {
                    name: '',
                    email: '',
                    confirmation_email: '',
                    phone: '',
                    relationship: 'Unknown'
                }
            ],
            btnDisabled:false,
            countries:[],
            states:[],
            cities:[],
            update_errors:''
        };
    },
    methods: {
        changeOption()
        {
            this.editUser=!this.editUser;
            if(this.editUser)
            {
                const selectElement  = document.getElementById("fetch-state");
                const selectedOption = selectElement.selectedOptions[0];
                const country        = selectedOption.getAttribute('data-shortname');
                this.fetchStates(country);
            }
        },
        fetchCountries(){
            let config = {
            method: 'get',
            url: 'https://api.countrystatecity.in/v1/countries',
            headers: {
                'X-CSCAPI-KEY': import.meta.env.VITE_COUNTRY_API_KEY,
            }
            };

            this.axios(config).then((response) => {
                this.countries = response.data;
            });
        },
        fetchStates(country)
        {
            let config = {
            method: 'get',
            url: 'https://api.countrystatecity.in/v1/countries/'+country+'/states',
            headers: {
                'X-CSCAPI-KEY': import.meta.env.VITE_COUNTRY_API_KEY,
            }
            };

            this.axios(config).then((response) => {
                this.states = response.data.sort((a, b) => a.name.localeCompare(b.name));
            });
        },
        updateUser()
        {
            this.update_errors = '';
            this.axios.post("/user/update-user", this.user).then((data) => {
                this.$moshaToast({title:data.data.message,}, {type: 'success', showIcon: true, hideProgressBar: true});
                    this.formData = [
                        {
                            password: '',
                            password_confirmation: '',
                        }
                    ];
                    this.getUser();
                    this.editUser=false;
                }).catch((error) => {
                    this.update_errors = this.$flattenErrors(error);
                });
        },
        ank()
        {
            if(this.passType=='password') this.passType = 'text';
            else this.passType = 'password';
        },
        generatePassword() {
            let result = "";
            let characters = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789!@#$%^&*()_+-=[]{}|;':\"<>,.?/\\";
            let charactersLength = characters.length;
            for (let i = 0; i < 20; i++) {
                result += characters.charAt(Math.floor(Math.random() * charactersLength));
            }
            this.formData.password              = result;
            this.formData.password_confirmation = result;
            this.copyLink(result,'Password generated and copied to clipboard');
        },
        updateCustomer()
        {
            this.axios.post("/user/update-customer", {...this.formData,user_id:this.user.id}).then((data) => {
                this.$moshaToast({title:data.data.message,}, {type: 'success', showIcon: true, hideProgressBar: true});
                    this.formData = [
                        {
                            password: '',
                            password_confirmation: '',
                        }
                    ];
                    this.password_errors = '';
                    this.getUser();
                })
                .catch((error) => {
                    this.password_errors = this.$flattenErrors(error);
                });
        },
        copyLink(link,message='') {
            navigator.clipboard.writeText(link)
                .then(() => {
                    this.$moshaToast({
                    title: message==''?'Payment link copied successfully':message,
                    },
                    {
                    type: 'success',
                    showIcon: true,
                    hideProgressBar: true,
                    });
                });
        },
        async addRelatives()
        {
            this.btnDisabled = true;
            this.errors = '';
            await this.axios.post("/user/add-relatives/"+this.id, this.rows).then((data) => {
                this.$moshaToast({
                    title:data.data.message,
                    },
                    {
                    type: 'success',
                    showIcon: true,
                    hideProgressBar: true,
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
                    this.getUser();
                })
                .catch((error) => {
                    this.errors = error.response.data.message;
                }).finally(()=>{
                    this.btnDisabled = false;
                });
        },
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
        async getUser() {
            await this.axios.post('/user/fetch',{'id':this.id}).then((response) => {
                this.user = response.data;
                this.user_id = this.user.user_id;
            });
        },
    },
    created() {
        this.id=this.$route.params.id;
        this.getUser();
        this.fetchCountries();
    }
};
</script>

<style scoped>
@media (max-width: 400px) {
    .form-control-sm-view{
        width: 200px;
    }

}
</style>
