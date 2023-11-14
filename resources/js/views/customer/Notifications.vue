<template>
    <div class="page" style="position: relative">
        <div class="page-main">
            <div class="landing-top-header">
                <Sidebar />
            </div>
            <div class="container-fluid main-notification px-5 bg-light-c py-5">
                <div class="col-md-12 my-5 top_div ">
                    <div class="card">
                        <div class="card-header bg-light align-items-center">
                            <h4 class="ct-card-header float-left" style="color: #4f4e4e">All Notifications</h4>
                            <h4 v-show="notifications.length>0" class=" btn text-center cursor-pointer btn-sm btn-primary text-white float-right" @click="readAll()">Mark all as read</h4>
                        </div>

                        <div  class="card-body notification-card px-4  py-1 border-bottom " :class="!notification.read_at?'unread-notification-bg':''"  v-for="notification in notifications" @click="navigateTo(notification.data.url)">
                            <div class="col-md-12 d-flex justify-content-between notify align-items-center flex-column flex-md-row " >
                                <div class="d-flex align-items-center">
                                    <img class="profile-image" :src="notification.data.sender.profile" style="height: 50px;width: 50px;border-radius: 50%;object-fit: cover; border:1px solid #6096b4">
                                    <p class="ct-p mt-3 ml-2" v-html="notification.data.msg"></p>
                                </div>
                                <p class="ct-p text-md-right text-right m-0" style="color:grey;width: 100%;">
                                    {{ notification.created }}
                                </p>
                            </div>
                        </div>
                        <div v-show="notifications.length==0 && !isLoading" class="text-center py-5 my-5">
                            <p class="mt-3 ct-p">No notifications</p>
                        </div>
                        <div class="card-body" v-show="isLoading" style="display: flex;justify-content: center;align-items: center">
                            <loader :show="isLoading"></loader>
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
    import Loader from "../front-end/Loader.vue";

    export default {
        name: 'Notifications',
        data(){
            return {
                notifications:[],
                isLoading: true
            }
        },
        components: {
            Sidebar,
            Footer,
            Loader
        },
        methods:{
            readAll()
            {
                this.axios.post('notifications/read-all').then(() => {
                    this.fetchNotifications();
                });
            },
            fetchNotifications()
            {
                var self=this;
                this.axios.post('notifications/fetch').then((response) => {
                    this.notifications = response.data.data;
                    this.isLoading = false;
                }).finally(()=>{ this.$updateFont(); });
            },
            navigateTo(url){
                this.$router.push(url);
            }
        },
        mounted()
        {
            this.fetchNotifications();
        }
    }
</script>
<style scoped>
.notification-card:hover{
    background: whitesmoke;
}
.unread-notification-bg{
    background: #ecf8fe
}

@media screen and (max-width: 391px) {
    .main-notification {
        margin-top: 0 !important;
        padding-left: 0 !important;
        padding-right: 0 !important;
        padding-top: 10px !important;
    }
    .main-notification .top_div {
        padding-left: 0 !important;
        padding-right: 0 !important;
    }

    .notify {
        padding-left: 0 !important;
        padding-right: 0 !important;
        justify-content: center !important;
        align-items: center !important;
    }

}
</style>
