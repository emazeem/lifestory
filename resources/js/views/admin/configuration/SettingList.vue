<template>
    <Head></Head>
    <div id="main">
        <Sidebar></Sidebar>
        <div class="main-content">
            <div class="page-header">
                <div class="container-fluid d-sm-flex justify-content-between">
                    <h4 class="font-size-25">Settings</h4>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb font-size-25">
                            <li class="breadcrumb-item">
                                <router-link to="/dashboard">Dashboard</router-link>
                            </li>
                            <li class="breadcrumb-item active" aria-current="page">Settings</li>
                        </ol>
                    </nav>
                </div>
            </div>

            <div class="container-fluid">
                <div class="row justify-content-center">
                    <div class="col-md-10">
                        <div class="card">
                            <div class="card-header">
                                <div class="d-lg-flex  justify-content-between align-items-center p-3">
                                    <div>
                                        <h5 class="font-size-25" :class="zoom.authorize?'text-success':'text-danger'">Zoom Authentication <i v-if="!zoom.authorize" class="fa fa-warning"></i></h5>
                                        <p v-if="!zoom.authorize" class="font-size-25 font-weight-lighter">Authorization is required from <span class="text-primary">{{zoom.email_acc}}</span></p>
                                        <span v-else>
                                        <p class="font-size-25 font-weight-lighter">Authorized email <span class="text-primary">{{zoom.email_acc}}</span></p>
                                        <p class="font-size-25 font-weight-lighter">Zoom is authorized for {{ $formatDateAsAmPm(zoom.expire_at) }} after this it will authorize automatically</p>
                                        </span>
                                    </div>
                                    <a style="white-space: nowrap !important;" class="btn btn-success text-white font-size-20"
                                        :href="'https://zoom.us/oauth/authorize?response_type=code&client_id='+client_id+'&redirect_uri='+redirect_uri">
                                        <i class="fa fa-external-link"></i> Authorize Zoom
                                    </a>

                                </div>
                            </div>

                            <div class="card-header">
                                <div class="d-lg-flex justify-content-between p-3 align-items-center">
                                    <div>
                                        <h5 class="font-size-25">Fee charged for each Lifestory recording.</h5>
                                        <p class="font-size-20 font-weight-lighter">The designated amount here will be the sum that the customer will be charged following the completion of the meeting or session.</p>
                                    </div>
                                    <div class="col-lg-3 col-md-6 col-12 pl-md-1 pl-0">
                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text" id="basic-addon2">$</span>
                                            </div>
                                            <input class="form-control" type="text" v-model="amount_for_booking_session">
                                            <div class="input-group-append">
                                                <span class="input-group-text btn btn-primary  text-white cursor-pointer"  @click="updateColumn('amount-for-booking-session',amount_for_booking_session)" id="basic-addon2"><i class="fa fa-check"></i></span>
                                            </div>
                                        </div>

                                    </div>
                                </div>
                            </div>
                            <div class="card-header">
                                <div class="d-lg-flex justify-content-between p-3 align-items-center">
                                    <div>
                                        <h5 class="font-size-25">Number of days a customer’s page is accessible without monthly subscription.</h5>
                                        <p class="font-size-20 font-weight-lighter">For how many days a customer can use system without subscription?</p>

                                    </div>
                                    <div class="col-lg-3 col-md-6 col-12 pl-md-1 pl-0">
                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text" id="basic-addon2">Days</span>
                                            </div>
                                            <input class="form-control" type="number" v-model="trial_period_of_customer">
                                            <div class="input-group-append">
                                                <span class="input-group-text btn text-white btn-primary cursor-pointer"  @click="updateColumn('trial-period-of-customer-in-days',trial_period_of_customer)" id="basic-addon2"><i class="fa fa-check"></i></span>
                                            </div>
                                        </div>

                                    </div>
                                </div>
                            </div>

                            <div class="card-header">
                                <div class="d-lg-flex justify-content-between p-3 align-items-center">
                                    <div>
                                        <h5 class="font-size-25">Monthly fee for maintaining customer pages and video/data copies.</h5>
                                        <p class="font-size-20 font-weight-lighter">The customer will be billed a monthly sum equal to the designated amount.</p>
                                    </div>
                                    <div class="col-lg-3 col-md-6 col-12 pl-md-1 pl-0">

                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text">$</span>
                                            </div>
                                            <input class="form-control" type="text" v-model="amount_for_booking_subscription">
                                            <div class="input-group-append">
                                                <span class="input-group-text text-white btn btn-primary cursor-pointer"  @click="updateColumn('amount-for-booking-subscription', amount_for_booking_subscription)" id="basic-addon2"><i class="fa fa-check"></i></span>
                                            </div>
                                        </div>

                                    </div>
                                </div>
                            </div>
                            <div class="card-header">
                                <div class="d-lg-flex justify-content-between p-3 align-items-center">
                                    <div>
                                        <h5 class="font-size-25">Meeting Email Notification to Customer</h5>
                                        <p class="font-size-20 font-weight-lighter">How many hours before the alert should notify the customer for the meeting?</p>
                                    </div>
                                    <div class="col-lg-3 col-md-6 col-12 pl-md-1 pl-0">

                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text" id="basic-addon2">Hours</span>
                                            </div>
                                            <input class="form-control" type="number" v-model="day_before_meeting_to_send_alert">
                                            <div class="input-group-append">
                                                <span class="input-group-text cursor-pointer btn btn-primary text-white"  @click="updateColumn('hours-before-meeting-to-send-alert',day_before_meeting_to_send_alert)" id="basic-addon2"><i class="fa fa-check"></i></span>
                                            </div>
                                        </div>
                                    </div>
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
import Head from "../../../components/admin/Header.vue";
import Sidebar from "../../../components/admin/Sidebar.vue";

export default {
    name: "ConfigurationList",
    components: {
        Head,
        Sidebar
    },
    data() {
        return {
            errors:'',
            paymentForSession:{},
            amount_for_booking_session:"",
            trial_period_of_customer:"",
            amount_for_booking_subscription:"",
            day_before_meeting_to_send_alert:"",
            zoom:'',
            product:{
                name:'',
                price:''
            },
            redirect_uri:'',
            client_id:'',
        };
    },
    methods: {

        updateColumn(column,value)
        {

            if (value == "") {
                return;
            }
            else {
                if (isNaN(value)) {
                    this.$moshaToast({title:'Please input a numeric value'},{type:'danger',showIcon:'true',hideProgressBar: true});
                    return;
                }
                else {
                    this.axios.post('configuration/update',{key:column,value:value}).then((data) => {
                        this.$moshaToast({title: 'Setting updated successfully'},{
                            type: 'success',
                            showIcon: true,
                            hideProgressBar: true,
                        });
                        this.fetchDetails();
                    })
                }
            }
        },
        async fetchDetails() {
            await this.axios.get('configuration/fetch-all')
                .then((data) => {
                    this.redirect_uri  = data.data.zoom.redirect_uri;
                    this.paymentForSession = data.data.paymentForSession;
                    this.amount_for_booking_session = data.data.paymentForSession.value;
                    this.day_before_meeting_to_send_alert = data.data.day_before_meeting_to_send_alert;
                    this.trial_period_of_customer = data.data.trialPeriod.value;
                    this.amount_for_booking_subscription = data.data.paymentForSubscription.value;
                    this.zoom = data.data.zoom;
                })
        },
    },

    mounted() {
        this.client_id = import.meta.env.VITE_ZOOM_CLIENT_ID;
    },

    created() {
        this.fetchDetails();
    },
};
</script>

<style scoped></style>
