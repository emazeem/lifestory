<template>
    <Head></Head>
    <div id="main">
        <Sidebar></Sidebar>
        <div class="main-content">
            <div class="page-header font-size-25">
                <div class="container-fluid d-sm-flex justify-content-between">
                    <h4>Select Time Slots</h4>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item">
                                <router-link to="/dashboard">Dashboard</router-link>
                            </li>
                            <li class="breadcrumb-item active" aria-current="page">Time Slot</li>
                        </ol>
                    </nav>
                </div>
            </div>

            <div class="container-fluid font-size-25">
                    <div class="card shadow-lg">
                        <div class="card-header mt-3">
                            <div class="d-flex justify-content-end">
                                <button class="btn btn-primary font-size-25 mb-4" data-toggle="modal" data-target="#slotModal" @click="mode='add';formData={};">
                                    Add new Time Slot
                                </button>
                            </div>
                        </div>
                        <div class="card-body">
                            <FullCalendar class="demo-app-calendar" :options="calendarOptions">
                                <template class="okk" v-slot:eventContent="arg">
                                    <div :class="(arg.event.title!='notok'?'booked':'')" class="font-size-25 calendar-time-slot">{{ calenderTime(arg.event.start) }}</div>
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
                    <form id="addTimeSlotForm" method="post" @submit.prevent="slotModal()">
                        <div class="form-group row">
                            <label class="col-sm-3 col-form-label">Select Date</label>
                            <div class="col-sm-9">
                                <input id="date" type="date" v-model="formData.date" class="form-control" autocomplete="off" name="date">
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
                                <button v-if="mode!='add'" type="button" @click="deleteSlot(formData.id)" id="btn-save" class="btn me-2 btn-danger">Delete</button>
                                <button type="submit" id="btn-save" class="btn btn-primary">
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
    data() {
        return {
            calendarOptions: {
            plugins: [
            dayGridPlugin,
            timeGridPlugin,
            interactionPlugin // needed for dateClick
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
            eventClick: this.viewSlot,
            eventsSet: this.handleEvents
        },
        currentEvents: [],
            TimeSlot:[],
            formData:{
                id:'',
                date:'',
                start_time:'',
                end_time:'',
            },
            days: [],
            mode:'add',
            err:'',
        }
    },
    methods: {
        viewSlot(clickInfo) 
        {
            if(clickInfo.event.title!='notok')
            this.$router.push('/session/view/'+clickInfo.event.title);
        },
        calenderTime(time){
            return moment(time).format('h:mm A');
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
        clearModalData()
        {
            this.formData = {
                id:'',
                start_time:'',
                end_time:'',
            };
        },
        deleteSlot(id)
        {
            this.axios.post('/time-slots/delete',{id:id})
            .then( () => {
                document.getElementById('modal-hide').click();
                this.$moshaToast({
                    title: 'Time slot deleted successfully',
                    },
                    {
                    type: 'success',
                    showIcon: true,
                    hideProgressBar: true,
                    });
            });
        },
        formattedTime(time,format) {
            return moment(time,'HH:mm:ss').format(format);
        },
        slotModal()
        {
            var self=this;

            Array.from(document.getElementsByClassName("form-control")).forEach((element) => element.classList.remove("is-invalid"));
            self.axios.post('/time-slots/store',{
                ...this.formData,days:this.days
            }).then(function (xhr) {
                self.err='';
                document.getElementById('modal-hide').click();
                swal("Success", xhr.data.message, "success");
                self.formData = {};
                self.days = [];
                self.timeSlots();
            }).catch(function (error){
                self.err=self.$flattenErrors(error);
            });
        },
        edit(data) {
            this.mode='edit';
            this.formData.id=data.id;
            this.days=data.parent_id;
            this.formData.start_time=data.from;
            this.formData.end_time=data.to;
        }

    },
    created() {
        this.timeSlots()
    }
}
</script>
<style>
.form-check-input:checked{
    background-color: #469fcc;
    border-color: #469fcc;
}
</style>
