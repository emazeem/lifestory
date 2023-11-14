<template>
  <Header></Header>
  <div id="main">
    <Sidebar/>
    <div class="main-content font-size-20">
      <div class="page-header">
        <div class="container-fluid d-sm-flex justify-content-between">
          <h4 class="font-size-25">Calendar</h4>
          <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
              <li class="breadcrumb-item">
                <router-link to="/dashboard" class="font-size-20">Dashboard</router-link>
              </li>
              <li class="breadcrumb-item font-size-20 active" aria-current="page">
                Calendar
              </li>
            </ol>
          </nav>
        </div>
      </div>
      <div class="container-fluid">

         <div class="row">

             <div class="col-md-12">
                 <div class="card shadow-lg">
                     <div class="card-body">
                         <FullCalendar class="demo-app-calendar" :options="calendarOptions">
                             <template v-slot:eventContent="arg">
                                 <i class="font-size-15 calendar-session">{{ calenderTime(arg.event.start) }}</i>
                             </template>
                         </FullCalendar>
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
  name: "Calendar",
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
      alerts: [],
      dashboardData: {},
      option:'this month',
      customDate:''
    }
  },
  watch: {
    customDate(newValue, oldValue) {
        if (newValue !== oldValue)
        {
            this.getDashboardData('custom',newValue);
        }
      }
  },
  methods: {
    getDashboardData(option)
    {
      this.option = option
      this.axios.post("dashboard/data",{option:option,date:this.customDate}).then((response) => {
        this.dashboardData = response.data.data;
        })
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
