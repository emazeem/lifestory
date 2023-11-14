<template>
    <div class="page landing-page-body">
        <div class="page-main">
            <Sidebar/>
            <div class="container-fluid my-5 pb-0" style="margin-bottom:75px !important">
                <div class="col-md-10 mx-auto">
                <div class="card shadow-lg">
                    <div class="card-body schedule-story-card-body">
                        <div class="row">
                            
                            
                            <div class="col-12 col-lg-6 create-stoy-form-section">
                                <span class="create-story-hrdd"></span>

                                <div class="col-10 mt-5 mx-auto position-relative p-0 m-0">
                                    <div class="d-flex mt-5 justify-content-between col-12 p-0">
                                        <div class="dot-container">
                                            <div class="dot active"></div>
                                        </div>
                                        <div class="dot-container">
                                            <div class="dot" :class="isActiveSecond"></div>
                                        </div>
                                        <div class="dot-container d-flex justify-content-end flex-column">
                                            <div class="dot" :class="isActiveThree"></div>
                                        </div>
                                    </div>
                                
                                    <div class="d-flex justify-content-between col-12 p-0">
                                        <p class="row">Contact Info</p>
                                        <p>Select Time Slot</p>
                                        <p class="row">Finished</p>
                                    </div>
                                    <div class="line"></div>
                                    <div class="line line-active" :style="lineStyle"></div>
                                </div>
                                

                                <form-wizard color="#6096b4 !important" class="main-form-wizard" finishButtonText="Back to Home">
                                    <tab-content :before-change="validateFormData" title="Contact Info" >
                                        
                                        <form id="msform">
                                            <div class="form-card">
                                                <div class="col-md-12 book-session-form-top-text"><p class="mt-0">Please complete the form below to schedule your Lifestory recording. All contact information is private and will never be shared outside of Project Lifestory. You can also help fill out this form on behalf of others.</p></div>
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
                                                <div class="col-md-12 mt-4">    
                                                    <div class="row" id="account_holder">
                                                        <div class="col-md-6 font-size-20 mt-1 font-size-20">
                                                            <div class="form-group">
                                                                <!-- <label for="fname">First Name<span class="text-red"> *</span></label> -->
                                                                <input type="text" placeholder="First Name" name="fname" id="fname" class="form-control form-control-book-session" v-model="formData.first_name"/>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6 font-size-20 mt-1">
                                                            <div class="form-group">
                                                                <!-- <label for="lname">Last Name<span class="text-red"> *</span></label> -->
                                                                <input type="text" name="lname" placeholder="Last Name" id="lname" class="form-control form-control-book-session" v-model="formData.last_name"/>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6 font-size-20 mt-1">
                                                            <div class="form-group">
                                                                <!-- <label for="email" >Email<span class="text-red"> *</span></label> -->
                                                                <input type="text" name="email" placeholder="Email" id="email" class="form-control form-control-book-session" v-model=" formData.email"/>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6 font-size-20 mt-1">
                                                            <div class="form-group">
                                                                <!-- <label for="confirmation_email" >Confirm Email<span class="text-red"> *</span></label> -->
                                                                <input type="text" placeholder="Confirm Email" name="confirmation_email" id="confirmation_email" class="form-control form-control-book-session" v-model=" formData.confirmation_email"/>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6 font-size-20 mt-1">
                                                            <div class="form-group">
                                                                <!-- <label for="contact">Phone<span class="text-red"> *</span></label> -->
                                                                <input type="text" placeholder="Phone" name="contact" id="contact" class="form-control form-control-book-session" v-model="formData.contact"/>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6 font-size-20 mt-1">
                                                            <div class="form-group">
                                                                <!-- <label for="country">Country<span class="text-red"> *</span></label> -->
                                                                <select name="country" @change="fetchStates('states1', $event.target.selectedOptions[0].getAttribute('data-shortname'))" id="country" class="form-control form-control-book-session" v-model="formData.country">
                                                                    <option value="">-- Select Country</option>
                                                                    <option v-for="country in countries" :value="country.name" :data-shortname="country.iso2">{{ country.name }}</option>
                                                                </select>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6 font-size-20 mt-1">
                                                            <div class="form-group">
                                                                <!-- <label for="city">City<span class="text-red"> *</span></label> -->
                                                                <input type="text" placeholder="City" class="form-control form-control-book-session" v-model="formData.city">
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6 font-size-20 mt-1">
                                                            <div class="form-group">
                                                                <!-- <label for="state" >State<span class="text-red"> *</span></label> -->
                                                                <select name="state" id="state" class="form-control form-control-book-session" v-model="formData.state">
                                                                    <option value="">Select State</option>
                                                                    <option v-for="state in states1" :value="state.name">{{ state.name }}</option>
                                                                </select>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6 font-size-20 mt-1">
                                                            <div class="form-group">
                                                                <!-- <label for="zip_code">Zip Code<span class="text-red"> *</span></label> -->
                                                                <input type="text" placeholder="Zip Code" name="zip_code" id="zip_code" class="form-control form-control-book-session" v-model="formData.zip_code"/>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-12 col-12 font-size-20">
                                                            <div class="row p-0 date-of-birth-row">
                                                                <div class="col-12">
                                                                    <label>Date Of Birth<span class="text-red"> *</span></label>
                                                                </div>
                                                                <div class="form-group col-md-4 col-12">
                                                                    <div class="btn-group w-100">
                                                                        <button type="button" class="btn  dropdown-toggle search-select-button" data-bs-toggle="dropdown" aria-expanded="false">{{ month?month:'Select Month' }}</button>
                                                                        <ul class="dropdown-menu">
                                                                        <li><input @keyup="search('month',$event)" type="text" placeholder="Search month" class="text form-control"></li>
                                                                        <li v-for="month in filteredMonths"><button type="button" class="dropdown-item" @click="setFieldValue('month',month)">{{ month }}</button></li>
                                                                        </ul>
                                                                    </div>
                                                                </div>
                                                                <div class="form-group col-md-4 col-12">
                                                                    <div class="btn-group w-100">
                                                                        <button type="button" class="btn  dropdown-toggle search-select-button" data-bs-toggle="dropdown" aria-expanded="false">{{ day?day:'Select Day' }}</button>
                                                                        <ul class="dropdown-menu">
                                                                        <li><input @keyup="search('day',$event)" type="text" placeholder="Search day" class="text form-control"></li>
                                                                        <li v-for="day in filteredDays"><button type="button" class="dropdown-item" @click="setFieldValue('day',day)">{{ day }}</button></li>
                                                                        </ul>
                                                                    </div>
                                                                </div>
                                                                <div class="form-group col-md-4 col-12">
                                                                    <div class="btn-group w-100">
                                                                        <button type="button" class="btn  dropdown-toggle search-select-button" data-bs-toggle="dropdown" aria-expanded="false">{{ year?year:'Select Year' }}</button>
                                                                        <ul class="dropdown-menu">
                                                                        <li><input @keyup="search('year',$event)" type="text" placeholder="Search year" class="text form-control"></li>
                                                                        <li v-for="year in filteredYears"><button type="button" class="dropdown-item" @click="setFieldValue('year',year)">{{ year }}</button></li>
                                                                        </ul>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <hr class="my-0" />
                                                </div>
                                            </div>
                                        </form>
                                    </tab-content>
                                    <tab-content :before-change="bookSession" title="Select Time Slot">
                                        <div class="col-md-12 book-session-form-top-text">Please select an available date and time slot for your recording. Recordings are conducted through Zoom using your smart phone, tablet, or personal computer.</div>
                                        <h4 class="mb-3 px-lg-2 mt-2 mb-2 available-time-slote-heading">Select Available Time Slot</h4>
                                        <div class="row">
                                            <div class="col-md-12 px-md-4 calendar-div-create-story mb-3">
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
                                                   <div class="table-responsive slots-table">
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
                                                                           <span class="badge bg-primary single-slot-option">{{ formatTime(slot.from) }}(PST)  &nbsp;&nbsp;-&nbsp;&nbsp;  {{ formatTime(slot.to) }}(PST)</span>
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
                                        <div class="row mt-1 text-center justify-content-center">
                                            <div class="col-3 mt-2">
                                                <img src="https://img.icons8.com/color/96/000000/ok--v2.png" class="fit-image mt-5">
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
                                                <p style="font-style: italic; margin-bottom:100px">* Note: if you do not receive your welcome email within an hour, be sure to check your spam or junk folder.</p>
                                            </div>
                                        </div>
                                    </tab-content>
                                </form-wizard>
                                <h3 class="lifestory-cost" id="lifestory-cost" v-show="cost">Lifestory Recording: <span class="font-size-25">${{ cost }}</span></h3>
                            </div>
                            <div class="col-md-6 d-none d-md-block d-lg-block book-session-form-img position-relative">
                                <img class="h-100 w-100" height="100vh" src="../../front-end-assets/book-session-form-img.png" alt="">
                                <i @click="$router.go(-1)" class="fa text-white fa-remove cross-story-page"></i>
                            </div>
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
            isActiveSecond:'',
            isActiveThree:'',
            days: [1,2,3,4,5,6,7,8,9,10,11,12,13,14,15,16,17,18,19,20,21,22,23,24,25,26,27,28,29,30,31],
            months: ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'],
            years: [...Array.from({ length: 100 }, (_, index) => new Date().getFullYear() - index)],
            filteredMonths: ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'],
            filteredDays: [1,2,3,4,5,6,7,8,9,10,11,12,13,14,15,16,17,18,19,20,21,22,23,24,25,26,27,28,29,30,31],
            filteredYears: [...Array.from({ length: 100 }, (_, index) => new Date().getFullYear() - index)],
            monthMapping : {'January': 1,'February': 2,'March': 3,'April': 4,'May': 5,'June': 6,'July': 7,'August': 8,'September': 9,'October': 10,'November': 11,'December': 12},
            month:'',
            day:'',
            year:'',
            cost:'',
            countries: [],
            states1: [],
            states2: [],
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
    computed: {
    lineStyle() {
      if (this.isActiveThree) {
        return { width: '100%' };
      } else if (this.isActiveSecond) {
        return { width: '50%' };
      } else {
        return { width: '0%' };
      }
    },
  },
    components: {
        Sidebar,
        Footer,
        FormWizard,
        TabContent,
    },
    methods: {
        search(type,event)
        {
            if(type =="month")
            {
                this.filteredMonths = this.months.filter(month =>
                    month.toLowerCase().includes(event.target.value.toLowerCase())
                );
            }
            else if (type == "day") 
            {
                const searchValue = parseInt(event.target.value, 10);

                if (!isNaN(searchValue)) {
                    this.filteredDays = this.days.filter(day =>
                    day === searchValue
                    );
                } else {
                    this.filteredDays = []; // Clear the filtered list when the input is not a valid integer.
                }
            }

            else if (type === "year") {
                const searchValue = event.target.value.toLowerCase();
                this.filteredYears = this.years
                    .filter(year => {
                    if (typeof year === 'number') {
                        return year.toString().toLowerCase().includes(searchValue);
                    }
                    return false;
                    });
                }
        },
        setFieldValue(type,value)
        {
            if(type == "month")
            {
                this.month = value
            }
            else if(type == "day")
            {
                this.day = value;
            }
            else if(type == "year")
            {
                this.year = value
            }
        },

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
        async validateFormData() {

            this.isActiveSecond = '',
            this.isActiveThree = '',
            this.errors = '';
            let moveNext = true;

            if (this.day) {
                this.formData.day = this.day;
            }
            if (this.month) {
                const selectedMonthIndex = this.monthMapping[this.month];
                this.formData.month = selectedMonthIndex;
            }
            if (this.year) {
                this.formData.year = this.year;
            }

            try {
                const response = await this.axios.post("session/validation", this.formData);
                if (!response.data) {
                moveNext = false;
                } else {
                    this.isActiveSecond = 'active'
                moveNext = true;
                }
            } catch (error) {
                this.errors = this.$flattenErrors(error);
                moveNext = false;
                window.scrollTo({ top: 0, behavior: 'smooth' });
            }

            return moveNext;
        },

        async bookSession()
        {
            this.isActiveThree = '';

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

                        this.isActiveSecond = 'active';
                        this.isActiveThree = 'active';
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
        const calendarDates = document.querySelectorAll('.vc-day.is-today > .vc-day-content');

        calendarDates.forEach(date => {
        date.classList.add('selectedCalendarDate');
        });

        // Function to apply styling to parent anchors
        const applyStylingToParentAnchors = () => {
            const buttons = document.querySelectorAll('.wizard-btn');

            buttons.forEach(button => {
                if (button.textContent === 'Next' || button.textContent === 'Back') {
                    button.addEventListener('click', () => {
                        // Scroll to the top of the page
                        window.scrollTo({
                            top: 0,
                            behavior: 'smooth'
                        });
                    });
                }
            });
        };

        // Create a MutationObserver instance
        const observer = new MutationObserver(mutationsList => {
            for (const mutation of mutationsList) {
                if (mutation.type === 'childList') {
                    applyStylingToParentAnchors();
                }
            }
        });

        // Observe changes to the document's body
        observer.observe(document.body, {
            childList: true,
            subtree: true
        });

  },
};
</script>

<style scoped>

.vue-form-wizard .wizard-card-footer .wizard-footer-left button {
    color: white !important;
}



.dot-container {
    width: 15px;
    height: 15px;
    display: flex;
    align-items: center;
}

.dot {
    width: 100%;
    height: 100%;
    background: #C7C7C7;
    border-radius: 50%;
}

.dot.active {
    background: #6096b4 !important;
    z-index: 9;
}
.line-active {
    background: #6096b4 !important;
}

.line {
    width: 100%;
    height: 3px;
    background: #C7C7C7;
    position: absolute;
    top: 6px;
}

ul.wizard-nav-pills li {
pointer-events: none;
}
ul.wizard-nav-pills li a {
pointer-events: none;
}

.search-select-button {
    color: #484040;
    background-color: #f1f1f1;
    border: 0.5px solid rgba(200, 200, 200, 1);
    border-radius: 4px;
}

.table-responsive.slots-table {
    max-width: 644px;
    max-height: 148px;
    overflow: scroll; /* Use "scroll" to make the scrollbar always visible */
    overflow-x: hidden;
}

/* Style the scrollbar for webkit browsers */
.table-responsive.slots-table::-webkit-scrollbar {
    width: 12px; /* Adjust the width as needed */
}

.table-responsive.slots-table::-webkit-scrollbar-thumb {
    background-color: #888; /* Color of the scrollbar thumb */
    border-radius: 6px; /* Round the thumb for aesthetics */
}

.table-responsive.slots-table::-webkit-scrollbar-track {
    background-color: #f1f1f1; /* Color of the scrollbar track */
}


</style>
