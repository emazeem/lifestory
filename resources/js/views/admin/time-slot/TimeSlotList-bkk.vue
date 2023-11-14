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
                        <div class="card-body table-responsive">

                            <table class="table font-size-18">
                                <thead class="bg-dark font-size-25">
                                <tr class="text-center text-white">
                                    <th v-for="(data,index) in TimeSlot" :key="index" width="14.2%" class="font-size-15">{{ data.day }}</th>
                                </tr>
                                </thead>
                                <tbody>
                                <tr>
                                    <td v-for="(timeSlot,index) in TimeSlot" :key="index" class="border-left-0  border-bottom-0">
                                        <div  v-for="(data,i) in timeSlot.slots" :key="i" class=" px-1 text-center m-2 py-1 border rounded">
                                            <p class="mt-0 mb-0 ms-0  cursor-pointer" @click="edit(data)" data-toggle="modal" data-target="#slotModal" >
                                                <span class="top">
                                                    {{formattedTime(data.from,'h:mm A')}} - {{formattedTime(data.to,'h:mm A')}}
                                                </span>
                                            </p>
                                            <i  @click="edit(data)" data-toggle="modal" data-target="#slotModal" class="mt-2 me-2 fa cursor-pointer fa-pencil d-none text-primary"></i>
                                        </div>
                                    </td>
                                </tr>
                                </tbody>
                            </table>
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
                            <label class="col-sm-3 col-form-label">Select Day</label>
                            <div class="col-sm-9">
                                <div class="form-row" v-if="mode=='add'">
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
                                <div class="form-row" v-if="mode=='edit'">
                                    <select v-model="days"  class="form-control">
                                        <option v-for="time in TimeSlot" :value="time.id"  :checked="days==time.id">{{ time.day }}</option>
                                    </select>

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
<script lang="ts">


import Head from '../../../components/admin/Header.vue';
import Sidebar from '../../../components/admin/Sidebar.vue';
import axios from "axios";
import moment from 'moment';


export default {
    name: 'TimeSlotList',
    components: {
        Head,
        Sidebar,
    },
    data() {
        return {
            TimeSlot:[],
            formData:{
                id:'',
                start_time:'',
                end_time:'',
            },
            days: [],
            mode:'add',
            err:'',
        }
    },
    methods: {
        clearModalData()
        {
            this.formData = {
                id:'',
                start_time:'',
                end_time:'',
            };
            this.days = [];
        },
        deleteSlot(id)
        {
            axios.post('/time-slots/delete',{id:id})
            .then(function () {
                document.getElementById('modal-hide').click();
                this.$moshaToast({
                    title: 'Time slot deleted successfully',
                    },
                    {
                    type: 'success',
                    showIcon: true,
                    hideProgressBar: true,
                    });
                this.showSlots();
            }.bind(this));
        },
        formattedTime(time,format) {
            return moment(time,'HH:mm:ss').format(format);
        },
        slotModal(){
            var self=this;

            Array.from(document.getElementsByClassName("form-control")).forEach((element) => element.classList.remove("is-invalid"));
            axios.post('/time-slots/store',{
                ...this.formData,days:this.days
            }).then(function (xhr) {
                self.err='';
                document.getElementById('modal-hide').click();
                swal("Success", xhr.data.message, "success");
                self.formData = {};
                self.days = [];
                self.showSlots();
            }).catch(function (error){
                self.err=self.$flattenErrors(error);
            });
        },
        showSlots() {
            axios.post('/time-slots/fetch').then(function (res) {this.TimeSlot = res.data;}.bind(this));
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
        this.showSlots()
    }
}
</script>
<style>
.form-check-input:checked{
    background-color: #469fcc;
    border-color: #469fcc;
}
</style>
