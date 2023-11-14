<template>
    <div class="page landing-page-body">
        <div class="page-main">
            <Sidebar/>
            <div class="container my-5">
                <div class="card shadow-lg">
                    <div class="card-body schedule-story-card-body">
                        <div class="row">
                            
                            <div class="col-12 col-lg-6 create-stoy-form-section">
                                <span class="create-story-hr"></span>
                                
                                <form-wizard color="#6096b4 !important" class="main-form-wizard" finishButtonText="Back to Home">
                                    <tab-content :before-change="validateFormData" title="Contact Info" >
                                        
                                        <form id="msform">
                                            <div class="form-card">
                                                <div class="col-md-12 book-session-form-top-text"><p>Please complete the form below to schedule your Lifestory recording. All contact information is private and will never be shared outside of Project Lifestory. You can also help fill out this form on behalf of others.</p></div>
                                                <div class="col-md-12 pr-0">
                                                    <div v-if="errors" class="alert alert-danger alert-dismissible mt-3 fade show position-relative" role="alert">
                                                        <ul>
                                                            <li v-for="(e,index) in errors" :key="index"> {{ e }} </li>
                                                        </ul>
                                                        <p class="position-absolute top-0 right-0 mr-2 cursor-pointer" @click="errors = ''" aria-hidden="true">
                                                            &times;
                                                        </p>
                                                    </div>
                                                </div>
                                                <div class="col-md-12">    
                                                    <div class="row mt-5" id="account_holder">
                                                        <div class="col-md-6 font-size-20 font-size-20">
                                                            <div class="form-group">
                                                                <label for="fname">First Name<span class="text-red"> *</span></label>
                                                                <input type="text" name="fname" id="fname" class="form-control form-control-book-session" v-model="formData.first_name"/>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6 font-size-20">
                                                            <div class="form-group">
                                                                <label for="lname">Last Name<span class="text-red"> *</span></label>
                                                                <input type="text" name="lname" id="lname" class="form-control form-control-book-session" v-model="formData.last_name"/>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6 font-size-20">
                                                            <div class="form-group">
                                                                <label for="email" >Email<span class="text-red"> *</span></label>
                                                                <input type="text" name="email" id="email" class="form-control form-control-book-session" v-model=" formData.email"/>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6 font-size-20">
                                                            <div class="form-group">
                                                                <label for="confirmation_email" >Confirm Email<span class="text-red"> *</span></label>
                                                                <input type="text" name="confirmation_email" id="confirmation_email" class="form-control form-control-book-session" v-model=" formData.confirmation_email"/>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6 font-size-20">
                                                            <div class="form-group">
                                                                <label for="contact">Phone<span class="text-red"> *</span></label>
                                                                <input type="text" name="contact" id="contact" class="form-control form-control-book-session" v-model="formData.contact"/>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6 font-size-20">
                                                            <div class="form-group">
                                                                <label for="country">Country<span class="text-red"> *</span></label>
                                                                <select name="country" @change="fetchStates('states1', $event.target.selectedOptions[0].getAttribute('data-shortname'))" id="country" class="form-control form-control-book-session" v-model="formData.country">
                                                                    <option value="">-- Select Country</option>
                                                                    <option v-for="country in countries" :value="country.name" :data-shortname="country.iso2">{{ country.name }}</option>
                                                                </select>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6 font-size-20">
                                                            <div class="form-group">
                                                                <label for="city">City</label>
                                                                <input type="text" class="form-control form-control-book-session" v-model="formData.city">
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6 font-size-20">
                                                            <div class="form-group">
                                                                <label for="state" >State<span class="text-red"> *</span></label>
                                                                <select name="state" id="state" class="form-control form-control-book-session" v-model="formData.state">
                                                                    <option value="">Select State</option>
                                                                    <option v-for="state in states1" :value="state.name">{{ state.name }}</option>
                                                                </select>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6 font-size-20">
                                                            <div class="form-group">
                                                                <label for="zip_code">Zip Code<span class="text-red"> *</span></label>
                                                                <input type="number" min="1" name="zip_code" id="zip_code" class="form-control form-control-book-session" v-model="formData.zip_code"/>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-12 col-12 font-size-20">
                                                            <div class="row p-0">
                                                                <div class="col-12">
                                                                    <label>Date Of Birth<span class="text-red"> *</span></label>
                                                                </div>
                                                                <div class="form-group col-md-4 col-12">
                                                                    <select id="monthSelect" v-model="formData.month" class="form-control form-control-book-session"></select>
                                                                </div>
                                                                <div class="form-group col-md-4 col-12">
                                                                    <select id="daySelect" v-model="formData.day" class="form-control form-control-book-session"></select>
                                                                </div>
                                                                <div class="form-group col-md-4 col-12">
                                                                    <select id="yearSelect" v-model="formData.year" class="form-control form-control-book-session"></select>
                                                                </div>
    
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <hr />
                                                </div>
                                            </div>
                                        </form>
                                    </tab-content>
                                    <tab-content :before-change="bookSession" title="Select Time Slot">
                                        <div class="col-md-12 book-session-form-top-text">Please select an available date and time slot for your recording. Recordings are conducted through Zoom using your smart phone, tablet, or personal computer.</div>
                                        <h4 class="mb-3 px-md-2 mt-2 available-time-slote-heading">Select Available Time Slot</h4>
                                        <div class="row">
                                            <div class="col-md-12 px-md-4 calendar-div-create-story">
                                            <VCalendar :disabled-dates="disabledDates" :min-date="currentDate" expanded @click="handleDayClick">
                                                <template #day-component="{ date }">
                                                    <div class="vc-day" :class="{ 'is-today': isToday(date) }" :data-date="date.toISOString()">{{ date.getDate() }}</div>
                                                </template>
                                            </VCalendar>
                                            </div>
                                            <div class="col-md-12 p-md-0">
                                                <div v-if="slots.length > 0" class="slot-table">
                                                    <div class="slot-date bg-dark text-center text-white font-size-20 pt-md-3 pb-md-1 mt-md-0 mt-4 my-md-4 mx-md-4">
                                                        <p>
                                                            {{ formatDate(formData.date) }} <span class="font-size-15"></span>
                                                        </p>
                                                    </div>
                                                   <div class="table-responsive">
                                                       <table class="table border-0">
                                                           <tbody>
                                                           <tr v-for="(slot, index) in slots" :key="index" class="text-center">
                                                               <td class="font-size-20 border-0">
                                                                   <div class="form-check">
                                                                       <input
                                                                           style="height: 18px;width: 18px; cursor:pointer !important"
                                                                           :id="'radio'+slot.id"
                                                                           :checked="index === 0"
                                                                           class="form-check-input"
                                                                           role="button"
                                                                           type="radio"
                                                                           name="time_slot_id"
                                                                           :value="slot.id"
                                                                           :data-id="slot.id"
                                                                           :data-from="slot.from"
                                                                           :data-to="slot.to" v-model="formData.slot_id"
    
                                                                       >
                                                                       <label class="form-check-label cursor-pointer ml-3" :for="'radio'+slot.id">
                                                                           <span class="badge bg-primary">{{ formatTime(slot.from) }}(PST)  &nbsp;&nbsp;-&nbsp;&nbsp;  {{ formatTime(slot.to) }}(PST)</span>
                                                                       </label>
                                                                   </div>
                                                               </td>
                                                           </tr>
                                                           </tbody>
                                                       </table>
                                                   </div>
                                                </div>
                                                <div v-else class="col-md-12 mt-3 text-center no-slots-available">
                                                    <h2 class="text-center badge bg-warning font-size-25" >No Slots Available</h2>
                                                </div>
                                            </div>
                                        </div>
                                    </tab-content>
                                    <tab-content :before-change="goHome" title="Finished">
                                        <div class="row mt-5 text-center justify-content-center">
                                            <div class="col-3">
                                                <img src="https://img.icons8.com/color/96/000000/ok--v2.png" class="fit-image">
                                            </div>
                                        </div>
                                        <br><br>
                                        <div class="row mb-5 justify-content-center">
                                            <div class="col-md-10 col-12 text-center create-story-message-section">
                                                <h5 id="success_message" style="font-weight: 900;">Success!</h5>
                                                <h5 id="success_message">Your Lifestory recording has been booked.</h5>
                                                <h5 id="success_message">You will receive a confirmation email shortly with details about your session, including an accompanying Zoom link.</h5>
                                                <br>
                                                <h5 id="success_message">Thanks for participating in Project Lifestory!</h5>
                                                <br>
                                                <br>
                                                <p style="font-style: italic;">* Note: if you do not receive your welcome email within an hour, be sure to check your spam or junk folder.</p>
                                            </div>
                                        </div>
                                    </tab-content>
                                </form-wizard>
                                <h3 class="lifestory-cost" id="lifestory-cost" v-show="cost">Lifestory Recording: ${{ cost }}</h3>
                            </div>
                            <div class="col-md-6 d-none d-md-block d-lg-block book-session-form-img position-relative">
                                <img class="h-100 w-100" height="100vh" src="../../front-end-assets/book-session-form-img.png" alt="">
                                <router-link to="/"><i class="fa fa-remove cross-story-page"></i></router-link>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <Footer/>
        </div>
    </div>
</template>

<script>
import Sidebar from "../../components/front-end/Sidebar.vue";
import Footer from "../../components/front-end/Footer.vue";

import { FormWizard, TabContent } from "vue3-form-wizard";
import "vue3-form-wizard/dist/style.css";

import moment from 'moment';

export default {
    name: "CreateYourLifestory",
    data() {

        return {
            days: ['',1,2,3,4,5,6,7,8,9,10,11,12,13,14,15,16,17,18,19,20,21,22,23,24,25,26,27,28,29,30,31],
            months: ['','January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'],
            monthMapping : {'January': 1,'February': 2,'March': 3,'April': 4,'May': 5,'June': 6,'July': 7,'August': 8,'September': 9,'October': 10,'November': 11,'December': 12},
            years: ['',...Array.from({ length: 80 }, (_, index) => new Date().getFullYear() - index)],
            cost:'',
            countries: [],
            states1: [],
            states2: [],
            cities1: [],
            cities2: [],
            disabledDates: [],
            currentDate: null,
            successMessage:'',
            errors: '',
            slots:[],
            formData: {
                day:'',
                month:'',
                year:'',
                slot_id: '',
                account_for: "my_self",
                first_name: "",
                last_name: "",
                contact: "",
                email: "",
                confirmation_email: "",
                country: "United States",
                state: "",
                city: "",
                zip_code: "",
            },
        };
    },
    components: {
        Sidebar,
        Footer,
        FormWizard,
        TabContent,
    },
    methods: {
        
        isToday(date) {
            return (
                date.getFullYear() === this.currentDate.getFullYear() &&
                date.getMonth() === this.currentDate.getMonth() &&
                date.getDate() === this.currentDate.getDate()
            );
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
                this.countries = response.data.sort((a, b) => a.name.localeCompare(b.name));
            });
        },
        fetchStates(state,country)
        {
            let config = {
            method: 'get',
            url: 'https://api.countrystatecity.in/v1/countries/'+country+'/states',
            headers: {
                'X-CSCAPI-KEY': import.meta.env.VITE_COUNTRY_API_KEY,
            }
            };

            this.axios(config).then((response) => {
                if (state == 'states1')
                {
                this.states1 = response.data.sort((a, b) => a.name.localeCompare(b.name));
                }
                else this.states2 = response.data.sort((a, b) => a.name.localeCompare(b.name));
            });
        },
        fetchCities(id,city,state)
        {
            const selectElement  = document.getElementById(id);
            const selectedOption = selectElement.selectedOptions[0];
            const country        = selectedOption.getAttribute('data-shortname');
            let config = {
            method: 'get',
            url: 'https://api.countrystatecity.in/v1/countries/'+country+'/states/'+state+'/cities',
            headers: {
                'X-CSCAPI-KEY': import.meta.env.VITE_COUNTRY_API_KEY,
                }
            };

            this.axios(config).then((response) => {
                if(city=='cities1')
                     this.cities1 = response.data.sort((a, b) => a.name.localeCompare(b.name));
                else this.cities2 = response.data.sort((a, b) => a.name.localeCompare(b.name));
            });
        },
        goHome()
        {
            this.$router.push('/');
        },
        formatDate(date) {
            return moment(date).format('MMMM D, YYYY');
        },
        formatTime(time) {
            return moment(time,'HH:mm:ss').format('hh:mm A');
        },
        fetchSlots(date)
        {
            this.formData.slot_id = '',
            this.axios.post('time-slots',{date:date})
            .then((response) => {
                if(response.data.success)
                {
                //this.slots = response.data.slots;
                this.slots = response.data.slots.sort((a, b) => a.start.localeCompare(b.start));
                if(response.data.slots[0])
                this.formData.slot_id = response.data.slots[0].id;
                this.formData.date = response.data.date;
                }
            });
        },
        handleDayClick(event)
        {
            const ariaLabel = event.target.getAttribute('aria-label');
            if (ariaLabel == null) return;
            const clickedDate = moment(ariaLabel, 'dddd, MMM DD, YYYY');
            const currentDate = moment().startOf('day');

            if (clickedDate.isBefore(currentDate)) {
                this.$moshaToast({title: 'Please select the current date or after the current date'},
                    {type: 'danger',showIcon: true,hideProgressBar: true});
                return;
            }
            {
                const allElements = document.querySelectorAll('.selectedCalendarDate');
                allElements.forEach(element => {
                    element.classList.remove('selectedCalendarDate');
                });
                event.target.classList.add('selectedCalendarDate');
                this.fetchSlots(ariaLabel);
            }

        },
        async validateFormData()
        {
            this.errors = '';
            var moveToNextTab = true;

            const selectedDay = $('#daySelect').val();
            this.formData.day = selectedDay;
            const selectedMonth = $('#monthSelect').val();
            if(selectedMonth)
            {
                const selectedMonthIndex = this.monthMapping[selectedMonth];
                this.formData.month = selectedMonthIndex;
            }
            const selectedYear = $('#yearSelect').val();
            this.formData.year = selectedYear;

            await this.axios.post("/session/validation", this.formData).then((data) => {
                    if (!data.data) {
                        moveToNextTab = false;
                    }
                }).catch((error) => {
                    this.errors = this.$flattenErrors(error);
                    moveToNextTab = false;
                    $("#daySelect").val(null).trigger("change");
                    $("#monthSelect").val(null).trigger("change");
                    $("#yearSelect").val(null).trigger("change");
                    window.scrollTo({top: 0,behavior: 'smooth'});
                });

            return moveToNextTab;
        },
        async bookSession()
        {
            if(this.formData.slot_id == '')
            {
                this.$moshaToast({
                    title: 'Please select a slot to book session',
                    description: 'it is mandatory to book session'
                    },
                    {
                    type: 'danger',
                    showIcon: true,
                    hideProgressBar: true,
                    });
                return false;
            }
            var moveToNextTab = true;
            await this.axios.post("/session/store", this.formData).then((data) => {
                    if (!data.data.success) {
                        moveToNextTab = false;
                    }
                    if (!data.data) {
                        moveToNextTab = false;
                    }

                    else if(data.data.success) {
                        this.successMessage = data.data.message;
                        this.formData = {};

                        const backButton = document.querySelector('.wizard-footer-left .wizard-btn');
                        backButton.style.display = 'none';
                        const lifeStoryCost = document.getElementById('lifestory-cost');
                        lifeStoryCost.style.display = 'none';
                    }
                    else
                    {
                        moveToNextTab = false;
                        this.$moshaToast({
                        title: 'Something went wrong',
                        },
                        {
                        type: 'danger',
                        showIcon: true,
                        hideProgressBar: true,
                        });
                    }
                })
                .catch( (error)=> {
                    moveToNextTab = false;
                    if(error.response.data.data.booked)
                    {
                        this.$moshaToast({title: error.response.data.message},
                        {type: 'danger',showIcon: true,hideProgressBar: true});
                        var linkElement = document.getElementById('step-Selecttimeslots1');
                        linkElement.click();
                        const formattedDate1 = new Date().toLocaleDateString('en-US', { weekday: 'long', month: 'short', day: 'numeric', year: 'numeric' });
                        this.fetchSlots(formattedDate1);
                    }else{

                        this.errors = this.$flattenErrors(error);
                        var linkElement = document.getElementById('step-Persondetails0');
                        linkElement.click();
                    }
                });

            return moveToNextTab;
        },
        fetchStoryCost()
        {
            this.axios.get("story-cost", this.formData).then((data) => {
                   this.cost = data.data.data;
                });
        },
        initializeSelect2()
        {
            setTimeout(() => {
            $('#daySelect').select2({
            placeholder: 'Day',
            allowClear: false,
            data: this.days,
            });
            $('#monthSelect').select2({
            placeholder: 'Month',
            allowClear: false,
            data: this.months,
            });
            $('#yearSelect').select2({
            placeholder: 'Year',
            allowClear: false,
            data: this.years,
            });
        }, 0);
        }
    },
    created()
    {
        window.scrollTo({top: 0,behavior: 'smooth'});

        this.axios.get('fetch-dates').then(response => {
            this.currentDate = response.data.currentDate;
            
            this.disabledDates = response.data.disabledDates;
            const formattedDate = response.data.formattedDate;
            this.fetchSlots(formattedDate);
            const element = document.querySelector('[aria-label="'+formattedDate+'"]');
            if (element) element.classList.add('selectedCalendarDate');
            if (element) element.classList.add('is-today');
        })

        this.fetchCountries();
        this.fetchStates('states1','US');
        this.fetchStoryCost();
    },
    mounted() {
        
        this.initializeSelect2();
        const calendarDates = document.querySelectorAll('.vc-day.is-today > .vc-day-content');

        calendarDates.forEach(date => {
        date.classList.add('selectedCalendarDate');
        });
  },
};
</script>

<style scoped>

ul.wizard-nav-pills li {
pointer-events: none;
}
ul.wizard-nav-pills li a {
pointer-events: none;
}

</style>
