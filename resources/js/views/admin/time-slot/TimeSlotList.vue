<template>
    <Head></Head>
    <div id="main">
        <Sidebar></Sidebar>
        <div class="main-content">
            <div class="page-header font-size-25">
                <div class="container-fluid d-sm-flex justify-content-between">
                    <h4>Time Slots</h4>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item">
                                <router-link to="/dashboard">Dashboard</router-link>
                            </li>
                            <li class="breadcrumb-item active" aria-current="page">Time Slots</li>
                        </ol>
                    </nav>
                </div>
            </div>

            <div class="container-fluid font-size-25">
                <div class="card shadow-lg">
                    <div class="card-header mt-3">
                        <div class="d-flex justify-content-end">
                            <button id="edit_modal_btn" class="btn btn-primary font-size-25 mb-4" data-toggle="modal" data-target="#slotModal" @click="mode='add';formData={};">
                                Add new Time Slot
                            </button>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="row slot-legends">
                            <div class="col-md-6 col-12">
                                <p >All timeslots are in PST Timezone.</p>
                            </div>
                            <div class="col-md-6 col-12 d-flex justify-content-md-end justify-content-start">
                                <div>
                                    <p><span style="color: green;white-space: nowrap">⬤</span> - Available Slots</p>
                                    <p><span style="color: red;white-space: nowrap">⬤</span> - Booked Slots</p>
                                </div>
                            </div>
                        </div>


                    </div>
                    <div class="card-body" style="overflow-x: scroll">
                        <FullCalendar class="demo-app-calendar" :options="calendarOptions">
                            <template v-slot:eventContent="arg">
                                <div class="d-flex flex-column p-2" style="width: 100%">
                                    <div class="font-size-18 calendar-time-slot">
                                        <span style="white-space: break-spaces" :class="arg.event.extendedProps.class">{{ calenderTime(arg.event) }}</span>
                                        <p style="white-space: break-spaces" v-if="arg.event.extendedProps.para">{{ arg.event.extendedProps.para }}</p>
                                    </div>
                                    <div v-if="!arg.event.extendedProps.session_id" class="font-size-18 mt-2 calendar-time-slot d-flex justify-content-between mb-0 flex-row">
                                        <p @click="editEvent(arg.event.extendedProps.slot)">Edit</p>
                                        <p @click="deleteEvent(arg.event.id)">Delete</p>
                                    </div>
                                </div>
                            </template>
                        </FullCalendar>

                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="modal font-size-20 fade" id="slotModal" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">
                        {{mode=='add'?'Add Slot':'Edit Slot'}}
                    </h5>
                    <button type="button" class="close" id="modal-hide" data-dismiss="modal" @click="clearModalData()" aria-label="Close">
                        <i class="ti-close"></i>
                    </button>
                </div>
                <div class="modal-body">

                    <div v-if="err" class="alert alert-danger alert-dismissible fade show position-relative" role="alert">
                        <ul>
                            <li v-for="e in err">{{e}}</li>
                        </ul>
                        <p class=" position-absolute top-0 right-0 mr-2 cursor-pointer" @click="err=''" aria-hidden="true">&times;</p>
                    </div>

                    <form id="addTimeSlotForm" method="post" @submit.prevent="addSlots()">

                        <div class="form-group row" v-if="mode=='add'">
                            <label class="col-sm-3 col-form-label">Select Month</label>
                            <div class="col-sm-9">
                                <select class="form-control" v-model="formData.month">
                                    <option>-- Select Month</option>
                                    <option v-for="month in currentYearMonths" :value="month">{{ month }}</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group row" v-else>
                            <label class="col-sm-3 col-form-label">Select Month</label>
                            <div class="col-sm-9">
                                <input type="date" v-model="formData.slot_date" class="form-control">
                            </div>
                        </div>

                        <div class="form-group row" v-if="mode=='add'">
                            <label class="col-sm-3 col-form-label">Select Day</label>
                            <div class="col-sm-9">
                                <div class="form-row">

                                    <div v-for="time in TimeSlot" class=" col-md-4 my-0">
                                        <div class="custom-control custom-checkbox">
                                            <input class="custom-control-input" type="checkbox"
                                                :value="time.id"
                                                v-model="days"
                                                :id="'check'+time.id">
                                            <label class="custom-control-label" :for="'check'+time.id">{{ time.day }}</label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-sm-3 col-form-label">Start Time</label>
                            <div class="col-sm-9">
                                <input id="start_time" type="time" v-model="formData.start_time" class="form-control" autocomplete="off" name="start_time">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-sm-3 col-form-label">End Time</label>
                            <div class="col-sm-9">
                                <input id="end_time" type="time" v-model="formData.end_time" autocomplete="off" class="form-control" name="end_time">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-sm-3"></label>
                            <div class="col-sm-9 text-right">
                                <!-- <button v-if="mode!='add'" type="button" @click="deleteSlot(formData.id)" id="btn-save" class="btn me-2 btn-danger">Delete</button> -->
                                <button :disabled="btnDisabled" type="submit" id="btn-save" class="btn btn-primary">
                                    {{mode=='add'?'Create':'Update'}}
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</template>
<script>


import Head from '../../../components/admin/Header.vue';
import Sidebar from '../../../components/admin/Sidebar.vue';
import FullCalendar from "@fullcalendar/vue3";
import interactionPlugin from '@fullcalendar/interaction';
import dayGridPlugin from "@fullcalendar/daygrid";
import timeGridPlugin from "@fullcalendar/timegrid";
import moment from 'moment';


export default {
    name: 'TimeSlotList',
    components: {
        Head,
        Sidebar,
        FullCalendar
    },
    computed: {
        currentYearMonths()
        {
        const currentMonth = new Date().getMonth();
        const months = [
            "January", "February", "March", "April", "May", "June",
            "July", "August", "September", "October", "November", "December"
        ];

        return months.slice(currentMonth).map(month => `${month}`);
        }
    },
    data() {
        return {
            btnDisabled:false,
            calendarOptions: {
            plugins: [
            dayGridPlugin,
            timeGridPlugin,
            interactionPlugin
            ],
            headerToolbar: {
            left: 'prev,next today',
            center: 'title',
            right: ''
            },
            initialView: 'dayGridMonth',
            initialEvents: [],
            editable: true,
            selectable: true,
            selectMirror: true,
            dayMaxEvents: true,
            weekends: true,
            select: this.handleDateSelect,
            eventClick: this.handleEventClick,
            eventsSet: this.handleEvents
            },
            currentEvents: [],
            TimeSlot:[],
            formData:{
                id:'',
                start_time:'',
                end_time:'',
                month:''
            },
            days: [],
            mode:'add',
            err:'',
        }
    },
    methods: {
        editEvent()
        {
            const modalEdit = document.getElementById("edit_modal_btn");
            modalEdit.click();
        },
        deleteEvent(id)
        {
            swal({
                    title: 'Are you sure to this time slot?',
                    text: 'It will be permanently deleted!',
                    icon: "warning",
                    buttons: {
                    cancel: {
                        text: 'Cancel',
                        value: null,
                        visible: true,
                        className: 'swal-button--cancel bg-danger',
                        closeModal: true,
                    },
                    confirm: {
                        text: 'Yes, delete it!',
                        value: true,
                        visible: true,
                        className: 'swal-button--confirm',
                        closeModal: true,
                    }},}).then((result) => {
                    if (result)
                    {
                        this.axios.post('/time-slots/delete',{id:id}).then(() => {
                                document.getElementById('modal-hide').click();
                                this.$moshaToast({title: 'Time slot deleted successfully'},{
                                    type: 'success',
                                    showIcon: true,
                                    hideProgressBar: true,
                                    });
                                    window.location.reload();
                            }).catch(() => {
                            this.$moshaToast({ title: 'Something went wrong' }, { type: 'danger', showIcon: true, hideProgressBar:true });
                        });
                    }
                });
        },
        handleEventClick(clickInfo)
        {
            const session_id = clickInfo.event.extendedProps.session_id;
            if(session_id) this.$router.push('/session/view/'+session_id);
            else {
                this.days          = clickInfo.event.extendedProps.slot.parent_id;
                this.formData.id   = clickInfo.event.extendedProps.slot.id,
                this.formData.start_time = clickInfo.event.extendedProps.slot.from,
                this.formData.end_time   = clickInfo.event.extendedProps.slot.to,
                this.formData.slot_date  = clickInfo.event.extendedProps.slot.slot_date
                this.formData.month      = clickInfo.event.extendedProps.slot.month
                this.mode = 'edit';
            }
        },
        calenderTime(event){
            return moment(event.start).format('h:mm A')+" - "+moment(event.end).format('h:mm A');
        },
        clearModalData()
        {
            this.formData = {
                id:'',
                start_time:'',
                end_time:'',
            };
            this.days = [];
        },
        formattedTime(time,format) {
            return moment(time,'HH:mm:ss').format(format);
        },
        addSlots()
        {
            this.btnDisabled = true;

            Array.from(document.getElementsByClassName("form-control")).forEach((element) => element.classList.remove("is-invalid"));
            this.axios.post('/time-slots/store',{
                ...this.formData,days:this.days
            }).then((response) => {
                this.err='';
                document.getElementById('modal-hide').click();
                this.$moshaToast({title: 'Time slots added successfully'},{
                                    type: 'success',
                                    showIcon: true,
                                    hideProgressBar: true,
                                    });
                this.formData = {};
                this.days = [];
                window.location.reload();
            }).catch((error) => {
                this.err=this.$flattenErrors(error);
            }).finally(()=>{
                this.btnDisabled = false;
            });
        },
        async timeSlots()
        {
            await this.axios.get("time-slots/fetch-all").then((response) => {
                if (response.data.success) {
                    this.calendarOptions.initialEvents = response.data.events;
                    this.calendarOptions.events = response.data.events;
                }
                });
            },
        showSlots() {
            this.axios.post('/time-slots/fetch').then((res) => {this.TimeSlot = res.data;});
        },
    },
    created() {
        this.timeSlots();
        this.showSlots();
    }
}
</script>
<style>
.form-check-input:checked{
    background-color: #469fcc;
    border-color: #469fcc;
}
</style>
