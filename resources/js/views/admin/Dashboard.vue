<template>
  <Header></Header>
  <div id="main">
    <Sidebar></Sidebar>
    <div class="main-content font-size-25">
      <div class="page-header">
        <div class="container-fluid d-sm-flex justify-content-between">
          <h4>Dashboard</h4>
          <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
              <li class="breadcrumb-item">
                <router-link to="/dashboard">Dashboard</router-link>
              </li>
            </ol>
          </nav>
        </div>
      </div>
      <div class="container-fluid font-size-25">
          <div class="row">
              <div class="col-md-6" v-for="(alert, index) in alerts" :key="index">
                  <div @click="gotConfig()" class="alert cursor-pointer alert-danger alert-with-border alert-dismissible" role="alert">
                      <i class="ti-help-alt mr-2"></i> {{ alert.message }}
                      <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                          <i class="ti-close"></i>
                      </button>
                  </div>
              </div>

          </div>

        <div class="row font-size-25 mb-4" v-if="$checkObject(dashboardData)">
            <div class="col-md-4">
              <div class="card">
                  <div class="card-body">
                      <div class="d-flex align-items-center justify-content-between">
                          <div>
                              <h6 class="card-title mb-3 font-size-25">Total Customers</h6>
                              <div class="d-flex d-sm-block d-lg-flex align-items-end">
                                  <h2 class="mb-0 mr-2 font-weight-bold">{{ dashboardData.totalCustomers }}</h2>
                              </div>
                          </div>
                          <div>
                              <div class="avatar avatar-lg">
                                  <div class="avatar-title bg-primary-bright text-primary rounded-circle">
                                      <i class="fa fa-users"></i>
                                  </div>
                              </div>
                          </div>
                      </div>
                  </div>
              </div>
            </div>
            <div class="col-md-4">
                <div class="card">
                <div class="card-body">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <h6 class="card-title mb-3 font-size-25">Total Sub Users</h6>
                            <div class="d-flex d-sm-block d-lg-flex align-items-end">
                                <h2 class="mb-0 mr-2 font-weight-bold">{{ dashboardData.totalSubusers }}</h2>
                            </div>
                        </div>
                        <div>
                            <div class="avatar avatar-lg">
                                <div class="avatar-title bg-success-bright text-success rounded-circle">
                                    <i class="fa fa-users"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            </div>
            <div class="col-md-4">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex align-items-center justify-content-between">
                            <div>
                                <h6 class="card-title mb-3 font-size-25">Total Revenue</h6>
                                <div class="d-flex d-sm-block d-lg-flex align-items-end">
                                    <h2 class="mb-0 mr-2 font-weight-bold">${{ dashboardData.totalRevenue }}</h2>
                                </div>
                            </div>
                            <div>
                                <div class="avatar avatar-lg">
                                    <div class="avatar-title bg-info-bright text-info rounded-circle">
                                        <i class="fa fa-money"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card mb-0">
                    <div class="card-body p-3">
                        <div class="d-flex align-items-center">
                            <div class="icon-block icon-block-floating mr-3 icon-block-lg icon-block-outline-primary text-primary">
                                <i class="fa-solid fa-video-camera"></i>
                            </div>
                            <div>
                                <h6 class="text-uppercase font-size-15">FUTURE BOOKINGS</h6>
                                <h4 class="mb-0 font-weight-bold">{{ dashboardData.totalUpcomingBookings }}</h4>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card mb-0">
                    <div class="card-body p-3">
                        <div class="d-flex align-items-center">
                            <div class="icon-block icon-block-floating mr-3 icon-block-lg icon-block-outline-success  text-success">
                                <i class="fa-solid fa-check"></i>
                            </div>
                            <div>
                                <h6 class="text-uppercase font-size-15">Completed Recordings </h6>
                                <h4 class="mb-0 font-weight-bold">{{ dashboardData.totalCompletedBookings }}</h4>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3 d-none">
              <div class="card">
                  <div class="card-body">
                      <div class="d-flex align-items-center justify-content-between">
                          <div>
                              <h6 class="card-title mb-3 font-size-25">Booked Sessions</h6>
                              <div class="d-flex d-sm-block d-lg-flex align-items-end">
                                  <h2 class="mb-0 mr-2 font-weight-bold">{{ dashboardData.bookedSessions }}</h2>
                              </div>
                          </div>
                          <div>
                              <div class="avatar avatar-lg">
                                  <div class="avatar-title bg-warning-bright text-warning rounded-circle">
                                      <i class="fa-solid fa-calendar-check-o"></i>
                                  </div>
                              </div>
                          </div>
                      </div>
                  </div>
              </div>
          </div>
      </div>
          <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <div class="card-title">
                            <h5 class="card-title mt-2 font-size-25 font-size-30">Future bookings list</h5>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="table-responsive" tabindex="1" style="max-height: 300px;overflow: auto; outline: none;">
                                    <table class="table table-striped mb-0" >
                                        <thead>
                                        <tr>
                                            <th class="font-size-20">Start Time</th>
                                            <th class="text-center font-size-20">Customer</th>
                                            <th class="text-center font-size-20">Meeting Link</th>
                                            <th class="text-center font-size-20">Details</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                          <tr v-if="dashboardData && dashboardData.upcomingBookings && !dashboardData.upcomingBookings.length">
                                              <td class="text-center" colspan="5">No Records Found</td>
                                          </tr>
                                          <tr v-else v-for="(session, index) in dashboardData.upcomingBookings" :key="index">
                                              <td>{{ $formatDateAsAmPm(session.start_time) }}</td>
                                              <td class="text-center">
                                                  <router-link class="text-danger" :to="'session/view/'+session.id">{{ session.customer.fullName }}</router-link>
                                              </td>
                                              <td class="text-center">
                                                <a :href="session.join_url" target="_blank" data-toggle="tooltip" title="" data-original-title="Detail">
                                                  <i class="fa fa-external-link"></i>
                                                </a>
                                              </td>
                                              <td class="text-center">
                                                <router-link class="text-primary" :to="'session/view/'+session.id">
                                                  <i class="fa fa-eye"></i>
                                                </router-link>
                                              </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
              <div class="col-md-12">
                  <div class="card">
                      <div class="card-header">
                          <div class="card-title">
                              <h5 class="card-title mt-2 font-size-25 font-size-30">Completed Interviews </h5>
                          </div>
                      </div>
                      <div class="card-body">
                          <div class="row">
                              <div class="col-md-12">
                                  <div class="table-responsive" tabindex="1" style="max-height: 300px;overflow: auto; outline: none;">
                                      <table class="table table-striped mb-0" >
                                          <thead>
                                          <tr>
                                              <th class="font-size-20">Start Time</th>
                                              <th class="text-center font-size-20">Customer</th>
                                              <th class="text-center font-size-20">Status</th>
                                              <th class="text-center font-size-20">Details</th>
                                          </tr>
                                          </thead>
                                          <tbody>
                                            <tr v-if="dashboardData && dashboardData.completedInterviews && !dashboardData.completedInterviews.length">
                                                <td class="text-center" colspan="5">No Records Found</td>
                                            </tr>
                                            <tr v-else v-for="(session, index) in dashboardData.completedInterviews" :key="index">
                                                <td>{{ $formatDateAsAmPm(session.start_time) }}</td>
                                                <td class="text-center">
                                                    <router-link class="text-danger" :to="'user/view/'+session.user_id">{{ session.customer.fullName }}</router-link>
                                                </td>
                                                <td class="text-center">
                                                    {{ session.status }}
                                                </td>
                                                <td class="text-center">
                                                  <router-link class="text-primary" :to="'session/view/'+session.id">
                                                    <i class="fa fa-eye"></i>
                                                  </router-link>
                                                </td>
                                              </tr>
                                          </tbody>
                                      </table>
                                  </div>
                              </div>
                          </div>
                      </div>
                  </div>
              </div>
          </div>
          <div class="row mb-5">
            <div class="col-md-12 mb-5">
                <div class="card">
                    <div class="card-header" v-if="$checkObject(dashboardData)">
                        <div class="mt-3 row">
                            <div class="col-md-6 col-12">
                                <h6 class="card-title font-size-25">{{ filterText }}.</h6>
                            </div>
                            <div class="col-md-6 text-right">
                                <div class="dropdown">
                                    <a href="#" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                        <i class="fa fa-filter fa-2x" aria-hidden="true"></i>
                                    </a>
                                    <span class="dropdown-menu dropdown-menu-right px-2 d-flex flex-column">
                                        <span @click="getDashboardData('this month')"  :class="(option=='this month'?'active text-primary':'')" class="p-2 cursor-pointer">This month</span>
                                        <span @click="getDashboardData('previous month')" :class="(option=='previous month'?'active text-primary':'')"  class="p-2 cursor-pointer">Previous month</span>
                                        <span @click="getDashboardData('this year')" :class="(option=='this year'?'active text-primary':'')" class="p-2 cursor-pointer" >This year</span>
                                        <input type="month" class="form-control form-control-sm" v-model="customDate">
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-body" v-if="$checkObject(dashboardData)">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="card bg-success-bright text-success">
                                    <div class="card-body text-center">
                                        <h2 class="font-weight-bold">{{ dashboardData.revenue.total_users }}</h2>
                                        <div>Total Customers</div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="card bg-info-bright text-info">
                                    <div class="card-body text-center">
                                        <h2 class="font-weight-bold">${{ dashboardData.revenue.total_amount }}</h2>
                                        <div>Total Amount</div>
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
import Header from "../../components/admin/Header.vue";
import Sidebar from "../../components/admin/Sidebar.vue";

import FullCalendar from "@fullcalendar/vue3";
import interactionPlugin from '@fullcalendar/interaction';
import dayGridPlugin from "@fullcalendar/daygrid";
import timeGridPlugin from "@fullcalendar/timegrid";
import moment from 'moment';

export default {
  name: "Dashboard",
  components: {
    Header,
    Sidebar,
    FullCalendar,
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
          right: 'dayGridMonth'
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
      alerts: [],
      dashboardData: {},
      option:'this month',
      customDate:'',
      filterText:'New Customers This Month',
    }
  },
  watch: {
    customDate(newValue, oldValue) {
        if (newValue !== oldValue)
        {
            this.getDashboardData('custom');
        }
      }
    },
    methods: {
    getDashboardData(option)
    {

      this.option = option;
      if(option=='custom') this.filterText="New Customers in "+moment(this.customDate,'YYYY-MM').format('MMMM, YY');
      else if(option=='this month') this.filterText = "New Customers This Month";
      else if(option == 'previous month') this.filterText = "New Customers In The Previous Month";
      else if(option == 'this year') this.filterText = "New Customers In This Year";
      this.axios.post("dashboard/data",{option:option,date:this.customDate}).then((response) => {
        this.dashboardData = response.data.data;
      })
    },
    gotConfig()
    {
      this.$router.push('/settings')
    },
    calenderTime(time){
      return moment(time).format('h:mm A');
    },
    handleWeekendsToggle() {
      this.calendarOptions.weekends = !this.calendarOptions.weekends // update a property
    },
    handleEventClick(clickInfo) {
      this.$router.push('/session/view/'+clickInfo.event.id);
    },
    handleEvents(events) {
      this.currentEvents = events
    },
    async fetchSessionAsEvents()
    {
      await this.axios.get("sessions/as-events").then((response) => {
          if (response.data.success) {
            this.calendarOptions.initialEvents = response.data.events;
            this.calendarOptions.events = response.data.events;
          }
        });
    },
    async fetchAlerts()
    {
      await this.axios.get("dashboard/alerts").then((response) => {
          if (response.data.success) {
            this.alerts = response.data.alerts;
          }
        });
    }
  },
  created() {
    this.fetchSessionAsEvents();
    this.fetchAlerts();
    this.getDashboardData(this.option);
  },
};
</script>

<style scoped>
.demo-app-main {
  flex-grow: 1;
  padding: 3em;
}

.fc {
  /* the calendar root */
  max-width: 1100px;
  margin: 0 auto;
}
</style>
