<template>
    <div class="page landing-page-body" style="position: relative">
        <div class="page-main">
            <div class="landing-top-header">
                <Sidebar />
            </div>
        </div>
        <div class="container-fluid px-md-5 bg-light-c py-5 ">
            <div class="col-md-12 text-center" v-if="isLoading">
                <i class="fa fa-spinner fa-spin fa-5x"></i>
            </div>


            <div class="row">
                

                <div class="col-md-4 mb-4">

                    <div class="card h-100">
                        <div class="card-body text-center position-relative">
                            <span class="badge badge-warning position-absolute ct-p" style="right: 10px;top: 10px">{{
                                userInfo.role=='Customer'?'Customer':'Friend and Family' }}</span>
                            <figure class="avatar mt-5 avatar-xl m-b-20">
                                <img onclick="document.getElementById('image').click()" :src="userInfo.profile"
                                    id="profile-image" class="rounded-circle profile-image cursor-pointer" alt="...">
                                <input type="file" style="display: none" id="image" @change="readImageData($event)">
                            </figure>
                            <h5 class="mb-1 ct-card-header">{{ userInfo.fullName }}</h5>
                            <p class="ct-p">{{ userInfo.email }}</p>
                            <p class="ct-p">{{ userInfo.details.bio }}</p>

                        </div>

                        <hr class="m-0">
                        <div class="card-body text-center">
                            <div class="">
                                <h5 class="font-weight-bold mb-0 ct-p">{{ $formatDate(userInfo.created_at) }}
                                </h5>
                                <span class="ct-p">Date of Joining</span>
                            </div>
                        </div>
                        <hr class="m-0 d-none">
                        <div class="card-body d-none text-center" v-if="userInfo.role == 'Sub User'">
                            <p class="ct-p">Your Customer is <b>{{ customerOfSubUser.fullName }}</b></p>
                        </div>

                    </div>

                </div>
                <div class="col-md-8 mb-4">
                    <div class="card h-100">
                        <div class="card-header">
                            <h5 class=" mt-2 ct-card-header">Profile Settings</h5>
                        </div>
                        <div class="card-body" v-if="$checkObject(userInfo)">
                            <form @submit.prevent="updateUser(userInfo)">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="fname" class="ct-form-label">First Name</label>
                                            <input type="text" v-model="userInfo.fname"
                                                class="form-control rounded-0 form-control-sm ct-form-control" id="fname" />
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="lname" class="ct-form-label">Last Name</label>
                                            <input type="text" v-model="userInfo.lname"
                                                class="form-control rounded-0 form-control-sm ct-form-control" id="lname" />
                                        </div>
                                    </div>
                                    <div v-show="userInfo.role == 'Customer'" class="col-md-6">
                                        <div class="form-group">
                                            <label for="contact" class="ct-form-label">Phone</label>
                                            <input type="text" v-model="userInfo.contact"
                                                class="form-control rounded-0 form-control-sm ct-form-control"
                                                id="contact" />
                                        </div>
                                    </div>
                                    <div v-show="userInfo.role == 'Customer'" class="col-md-6">
                                        <div class="form-group">
                                            <label for="dob" class="ct-form-label">Date Of Birth</label>
                                            <input type="date" v-model="userInfo.details.dob"
                                                class="form-control rounded-0 form-control-sm ct-form-control" id="dob" />
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="country" class="ct-form-label">Country</label>
                                            <select name="country"
                                                @change="fetchStates($event.target.selectedOptions[0].getAttribute('data-shortname'))"
                                                id="country" class="form-control rounded-0 form-control-sm ct-form-control"
                                                v-model="userInfo.details.country">
                                                <option value="">Select country</option>
                                                <option v-for="country in countries" :value="country.name"
                                                    :data-shortname="country.iso2">{{ country.name }}</option>
                                            </select>
                                        </div>

                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="city" class="ct-form-label">City</label>
                                            <input type="text" v-model="userInfo.details.city"
                                                class="form-control rounded-0 form-control-sm ct-form-control" id="city" />
                                        </div>

                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="state" class="ct-form-label">State</label>
                                            <select class="form-control rounded-0 form-control-sm ct-form-control"
                                                v-model="userInfo.details.state">
                                                <option value="">Select state</option>
                                                <option v-for="state in states" :data-stateshortname="state.iso2"
                                                    :value="state.name">{{ state.name }}</option>
                                            </select>
                                        </div>
                                    </div>

                                </div>
                                <div class="form-group text-right mb-0">
                                    <button type="submit"
                                        class="btn  float-end btn-primary add-post-modal-btn custom-font-family ct-p">Update</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>


            </div>


            <div class="row">
                <div class="col-md-12 text-center">
                    <div v-if="errors" class="alert alert-danger alert-dismissible fade show position-relative"
                        role="alert">
                        <ul>
                            <li v-for="e in errors"> {{ e }} </li>
                        </ul>
                        <p class="position-absolute top-0 right-0 mr-2 cursor-pointer" @click="errors = ''"
                            aria-hidden="true">&times;</p>
                    </div>
                </div>
                <div class="col-lg-4 col-md-12">


                    <div class="card d-md-block d-none">
                        <div class="card-header">
                            <h5 class=" mt-2 ct-card-header">Appearance</h5>
                        </div>
                        <div class="card-body">
                            <p class="ct-p">Choose your font size preference.</p>
                            <ul class="nav nav-pills flex-column cursor-pointer ct-p flex-sm-row" role="tablist">
                                <li class="flex-sm-fill text-sm-center nav-item" @click="reChangeFont('small')">
                                    <a class="nav-link" :class="selectedFont == 'small' ? 'active' : ''">Small</a>
                                </li>
                                <li class="flex-sm-fill text-sm-center nav-item" @click="reChangeFont('medium')">
                                    <a class="nav-link" :class="selectedFont == 'medium' ? 'active' : ''">Medium</a>
                                </li>
                                <li class="flex-sm-fill text-sm-center nav-item d-lg-block d-none" @click="reChangeFont('large')">
                                    <a class="nav-link" :class="selectedFont == 'large' ? 'active' : ''">Large</a>
                                </li>
                            </ul>
                        </div>
                    </div>

                    <div class="card">
                        <div class="card-header">
                            <h5 class=" mt-2 ct-card-header">Update Password</h5>
                        </div>
                        <div class="card-body">
                            <div v-if="password_errors"
                                class="alert alert-danger alert-dismissible fade show position-relative" role="alert">
                                <ul>
                                    <li v-for="(e, index) in password_errors" :key="index"> {{ e }} </li>
                                </ul>
                                <p class="position-absolute top-0 right-0 mr-2 cursor-pointer" @click="password_errors = ''"
                                    aria-hidden="true">
                                    &times;
                                </p>
                            </div>
                            <form @submit.prevent="changePassword(formData)">
                                <div class="form-group">
                                    <label for="old_password" class="ct-form-label">Old Password</label>
                                    <div class="input-group">
                                        <input :type="passType1" v-model="formData.old_password"
                                            class="form-control rounded-0 form-control-sm ct-form-control"
                                            id="old_password" />
                                        <button type="button" @click="togglePasswordVisibility('old_password')"
                                            class="btn btn-sm btn-sm-p-0 border rounded-0 border-left-0 ct-form-control-icon">
                                            <i class="fa" :class="passType1 === 'password' ? 'fa-eye' : 'fa-eye-slash'"></i>
                                        </button>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="password" class="ct-form-label">Password</label>
                                    <div class="input-group">
                                        <input :type="passType2" v-model="formData.password"
                                            class="form-control rounded-0 form-control-sm ct-form-control" id="password" />
                                        <button type="button" @click="togglePasswordVisibility('password')"
                                            class="btn btn-sm btn-sm-p-0 border rounded-0 border-left-0 ct-form-control-icon">
                                            <i class="fa" :class="passType2 === 'password' ? 'fa-eye' : 'fa-eye-slash'"></i>
                                        </button>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="password_confirmation" class="ct-form-control">Password
                                        Confirmation</label>
                                    <div class="input-group">
                                        <input :type="passType3" v-model="formData.password_confirmation"
                                            class="form-control rounded-0 form-control-sm ct-form-control"
                                            id="password_confirmation" />
                                        <button type="button" @click="togglePasswordVisibility('password_confirmation')"
                                            class="btn btn-sm btn-sm-p-0 border rounded-0 border-left-0 ct-form-control-icon">
                                            <i class="fa" :class="passType3 === 'password' ? 'fa-eye' : 'fa-eye-slash'"></i>
                                        </button>
                                    </div>
                                </div>
                                <div class="form-group text-right mb-0">
                                    <button type="submit"
                                        class="btn float-end btn-primary add-post-modal-btn ct-p">Change</button>
                                </div>
                            </form>
                        </div>
                    </div>
                    <div v-show="userInfo.role == 'Customer'" class="card">
                        <div class="card-header">
                            <h5 class=" mt-2 ct-card-header">
                                {{ subscriptionStatus.is_on_trial ? 'Subscription Details' : 'Permanent hosting is enabled.'
                                }}
                                <i class="fa fa-check text-white bg-success p-1 rounded"
                                    v-if="!subscriptionStatus.is_on_trial"></i>
                            </h5>
                            <p class="ct-p" v-if="subscriptionStatus.is_on_trial">Your trial period will end on {{
                                subscriptionStatus.trial_end_on }}.</p>
                            <router-link class="btn pl-0 text-success ct-p" to="/subscription"
                                v-if="subscriptionStatus.is_on_trial">Enable Permanent Hosting</router-link>

                            <p class="font-weight-normal ct-p"
                                v-if="!subscriptionStatus.is_on_trial && subscriptionStatus.active && subscriptionStatus.active.ended_at">
                                Subscription will auto renew on {{ subscriptionStatus.active.ended_at }}.</p>
                        </div>
                        <div class="card-body table-responsive p-0" v-if="!subscriptionStatus.is_on_trial">
                            <table class="table table-hover table-striped w-100 table-borderless">
                                <tbody v-if="subscriptionStatus.active">
                                    <tr class="ct-p">
                                        <th>Starts on</th>
                                        <td>{{ subscriptionStatus.active.started_at }}</td>
                                    </tr>
                                    <tr class="ct-p">
                                        <th>Renewal Date</th>
                                        <td>{{ subscriptionStatus.active.ended_at }}</td>
                                    </tr>
                                </tbody>
                            </table>

                            <hr class="m-0 mt-3">
                            <div class="p-2">
                                <button class="btn btn-sm btn-danger p-2 btn-block ct-p"
                                    @click="cancelSubscription()">Cancel Subscription</button>

                            </div>
                        </div>
                    </div>


                </div>

                <div class="col-lg-8 col-md-12">



                    <div v-show="userInfo.role == 'Customer'" class="card">
                        <div class="card-header">
                            <h5 class="mt-2 ct-card-header">Transactions</h5>
                        </div>
                        <div class="card-body table-responsive p-0">
                            <table class="table table-hover table-striped w-100 table-borderless">
                                <tbody>
                                    <tr v-for="transaction in transactions">
                                        <td class="ct-p">{{ $date(transaction.created_at) }}</td>
                                        <td class="ct-p">{{ transaction.description }}</td>
                                        <td class="text-success ct-p text-right">{{ transaction.debit }} USD</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div v-show="userInfo.role == 'Customer'" class="card">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <h5 class=" mt-2 ct-card-header">Payment Method</h5>
                            <button @click="showForm = !showForm" class="btn btn-primary btn-sm ct-p ml-2"> Add new
                                Card</button>
                        </div>
                        <div class="card-header" v-show="showForm">
                            <AddPaymentMethod :refreshBanks="fetchPaymentMethods" />
                        </div>
                        <div class="card-body table-responsive p-0">
                            <table class="table table-hover w-100 ">
                                <thead>
                                    <tr>
                                        <th class="font-weight-bold ct-p">Card Type</th>
                                        <th class="font-weight-bold ct-p">Card Number</th>
                                        <th class="font-weight-bold ct-p">Expiry Date</th>
                                        <th class="font-weight-bold text-center ct-p">Preferred</th>
                                        <th class="font-weight-bold text-center ct-p">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-for="payment_method in payment_methods">
                                        <td class="font-size-12">
                                            <img src="../../public/icons/Visa.png"
                                                v-if="payment_method.card_type === 'Visa'" width="50">
                                            <img src="../../public/icons/Mastercard.png"
                                                v-if="payment_method.card_type === 'MasterCard'" width="50">
                                            <img src="../../public/icons/AmericanExpress.png"
                                                v-if="payment_method.card_type === 'American Express'" width="50">
                                        </td>
                                        <td class="font-size-12 ct-p">{{ payment_method.card_number }}</td>
                                        <td class="font-size-12 ct-p">{{ payment_method.expiry }}</td>
                                        <td class="font-size-12 ct-p text-center">
                                            <span v-if="payment_method.preferred == 1"
                                                class="cursor-pointer ct-p text-success"><i class="fa fa-check"></i></span>
                                            <span v-else class="cursor-pointer"
                                                @click="makePreferred(payment_method.id)">Make Preferred</span>
                                        </td>
                                        <td class="text-center">
                                            <span v-if="payment_method.preferred == 0"
                                                @click="deleteCard(payment_method.id)"
                                                class="fa fa-trash text-danger cursor-pointer"></span>
                                        </td>
                                    </tr>
                                </tbody>
                                <tfoot>
                                    <tr class="text-center">
                                        <td v-if="payment_methods.length == 0" colspan="100%" class="font-size-12 ct-p">No
                                            record found.</td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="">
            <div class="side-app">
                <div class="main-container">
                    <Footer></Footer>
                </div>
            </div>
        </div>
    </div>
</template>

<script>

import Sidebar from '../../components/front-end/Sidebar.vue';
import Footer from '../../components/front-end/Footer.vue';
import moment from 'moment';
import Loader from "../front-end/Loader.vue";
import AddPaymentMethod from '../../components/front-end/AddPaymentMethod.vue';


export default {
    name: 'Settings',
    data() {
        return {
            selectedFont: '',
            showForm: false,
            isLoading: false,
            transactions: [],
            payment_methods: [],
            countries: [],
            states: [],
            passType1: 'password',
            passType2: 'password',
            passType3: 'password',
            subscriptionStatus: [],
            userInfo: {
                details: {
                    bio: '',
                    city: '',
                    dob: '',
                }
            },
            errors: '',
            customerOfSubUser: '',
            formData: {
                old_password: '',
                password: '',
                password_confirmation: ''
            },
            password_errors: ''
        }
    },
    components: {
        Sidebar,
        Footer,
        Loader,
        AddPaymentMethod
    },
    methods: {

        async updateUser(formData) {
            this.errors = '';
            await this.axios.post('user/update-details', formData)
                .then(response => {
                    this.fetchUserInfo();
                    this.$moshaToast({ title: "Profile settings updated successfully" },
                        { type: 'success', showIcon: true, hideProgressBar: true });
                }).catch(error => {
                    this.$moshaToast({ title: 'Something went wrong', }, { type: 'danger', showIcon: true, hideProgressBar: true, });
                    this.errors = this.$flattenErrors(error);
                });
        },
        async fetchCountries(first_time = false) {
            let config = {
                method: 'get',
                url: 'https://api.countrystatecity.in/v1/countries',
                headers: {
                    'X-CSCAPI-KEY': import.meta.env.VITE_COUNTRY_API_KEY,
                }
            };
            await this.axios(config).then((response) => {
                this.countries = response.data;
                if (first_time) {
                    if (this.userInfo && this.userInfo.details && this.userInfo.details.country) {
                        let selectedCountry = null;
                        for (let i = 0; i < this.countries.length; i++) {
                            if (this.countries[i].name == this.userInfo.details.country) {
                                selectedCountry = this.countries[i];
                                break;
                            }
                        }
                        if (selectedCountry) {
                            this.fetchStates(selectedCountry.iso2, true);
                        }
                    }
                }
            });
        },
        fetchStates(country) {
            this.states = [];
            let config = {
                method: 'get',
                url: 'https://api.countrystatecity.in/v1/countries/' + country + '/states',
                headers: {
                    'X-CSCAPI-KEY': import.meta.env.VITE_COUNTRY_API_KEY,
                }
            };

            this.axios(config).then((response) => {
                this.states = response.data.sort((a, b) => a.name.localeCompare(b.name));
            });
        },
        async readImageData(event) {
            this.errors = '';
            const file = event.target.files[0];
            const reader = new FileReader();

            reader.onload = function (event) {
                const imageUrl = event.target.result;
                const images = document.getElementsByClassName('profile-image');
                for (let i = 0; i < images.length; i++) {
                    images[i].src = imageUrl;
                }
            };

            reader.readAsDataURL(file);

            const formData = new FormData();
            formData.append('profile', file);

            await this.axios.post('user/update-profile-image', formData)
                .then(() => {
                    this.$moshaToast({ title: "Profile image updated successfully" },
                        { type: 'success', showIcon: true, hideProgressBar: true });
                }).catch(error => {
                    this.$moshaToast({ title: 'Somethig went wrong', }, { type: 'danger', showIcon: true, hideProgressBar: true });
                    this.errors = this.$flattenErrors(error);
                });
        },
        formattedDate(stamp) {
            return moment(stamp).format("DD-MM-YYYY");
        },
        togglePasswordVisibility(field) {
            if (field === 'old_password') {
                this.passType1 = this.passType1 === 'password' ? 'text' : 'password';
            } else if (field === 'password') {
                this.passType2 = this.passType2 === 'password' ? 'text' : 'password';
            } else if (field === 'password_confirmation') {
                this.passType3 = this.passType3 === 'password' ? 'text' : 'password';
            }
        },
        changePassword(formData) {
            this.password_errors = '';
            this.axios.post("user/profile/update", formData).then(() => {
                this.$moshaToast({ title: 'Password changed successfully' },
                    { type: 'success', showIcon: true, hideProgressBar: true });
                this.formData = {
                    old_password: '',
                    password: '',
                    password_confirmation: ''
                };
                this.fetchUserInfo();
            }).catch((error) => {
                this.password_errors = this.$flattenErrors(error);
            });
        },
        fetchUserInfo() {
            this.axios.get('user/info').then((response) => {
                this.userInfo = response.data.data;
            });
        },
        deleteCard(id) {
            this.axios.post('user/delete-payment-method', { id: id }).then((response) => {
                this.fetchPaymentMethods();
            });
        },
        makePreferred(id) {
            this.axios.post('user/payment-method/make-preferred', { id: id }).then((response) => {
                this.fetchPaymentMethods();
            });
        },
        cancelSubscription() {
            swal({
                title: "Are you sure to cancel subscription?",
                text: "Once cancelled, your account access will be restricted!",
                icon: "warning",
                buttons: true,
                dangerMode: true,
            }).then((willDelete) => {
                if (willDelete) {
                    swal({
                        title: "Enter reason to cancel subscription.",
                        content: "input",
                        icon: "warning",
                    }).then((value) => {
                        if (value != null) {
                            this.axios.post('user/cancel-subscription', { reason: value }).then((response) => {
                                this.$router.push('/in-active');
                            });
                        }
                    });
                }
            });
        },
        fetchCustomerOfSubUser() {
            this.axios.post('user/fetch-customer-switch').then((response) => {
                this.customerOfSubUser = response.data.data;
            });
        },
        fetchTransactions() {
            this.axios.get('user/transactions').then((response) => {
                this.transactions = response.data.data;
            }).finally(() => { this.$updateFont(); });
        },
        fetchPaymentMethods() {
            this.axios.post('user/payment-methods').then((response) => {
                this.payment_methods = response.data.data;
            }).finally(() => { this.$updateFont(); });
        },
        fetchSubscriptionStatus() {
            this.axios.post('user/subscription-status').then((response) => {
                this.subscriptionStatus = response.data.data;
            }).finally(() => { this.$updateFont(); });
        },
        reChangeFont(value) {
            this.selectedFont = value;
            this.$changeFont(value);
        }
    },
    mounted() {
        const font_size = localStorage.getItem('font_size_class');
        if (font_size == 'small' || font_size == 'medium' || font_size == 'large') {
            this.selectedFont = font_size;
        }
        else this.selectedFont = 'medium';
    },
    created() {
        this.fetchUserInfo();
        this.fetchCountries(true);
        this.fetchTransactions();
        this.fetchCustomerOfSubUser();
        this.fetchPaymentMethods();
        this.fetchSubscriptionStatus();
    }
}
</script>

