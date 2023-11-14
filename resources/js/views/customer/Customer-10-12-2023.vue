<template>
    <div class="page" style="position: relative">
        <AddPost :showAddPostBtn="false" :fetchPosts1="fetchPosts"/>
        <div class="page-main">
            <div class="landing-top-header">
                <Sidebar />
                <div class="customer-story">
                    <div class="container">
                        <div class="row m-0">
                            <div class="col-lg-12 customer-video-tour text-center p-0">
                                <CustomerVideo :customer="customer"/>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Our Services-->
            <div class="container section customer-profile-tour customizable" id="our-services">
                <div v-if="errors" class="alert col-md-8 mx-auto alert-danger alert-dismissible fade show position-relative" role="alert">
                    <ul>
                        <li v-for="(e,index) in errors" :key="index"> {{ e }} </li>
                    </ul>
                    <p class="position-absolute top-0 right-0 mr-2 cursor-pointer" @click="errors = ''" aria-hidden="true">
                        &times;
                    </p>
                </div>
                <CustomerProfile :fetchCustomer1="fetchCustomer" :bio1="bio" :fetchUserInfo1="fetchUserInfo" :customer="customer" :userInfo1="userInfo"/>
            </div>
            <div class="container mt-5">
                <div class="row">
                    <div class="col-12">
                        <h3 class="feed-title ct-card-header" style="text-align: left!important;">Recent Gallery Activity and Uploads</h3>
                    </div>
                </div>

            </div>

            <div class="container-fluid px-md-5  pb-5 ">
                <div class="text-center mb-5">
                    <button class="btn add-photo-tour btn-dark px-4 py-2 add-post-modal-btn" title="Add Photo" data-toggle="modal" data-target="#addPostModal">
                        <span class="ct-p ">Add Photo</span>
                    </button>
                </div>

                <div class="row text-center justify-content-center align-items-center">

                    <div class="col-md-12 mb-4 mt-2">
                        <div v-if="userInfo.role=='Sub User'" class="btn-group mr-2 float-right" role="group" aria-label="First group">
                            <button type="button" class="btn " :class="(toggleValue=='all'?'btn-primary':'bg-white border')" @click="togglePosts('all')"><i class="fa fa-bars"></i> All Memories</button>
                            <button type="button" class="btn " :class="(toggleValue=='my'?'btn-primary':'bg-white border')" @click="togglePosts('my')"><i class="fa fa-bars"></i> My Memories</button>
                        </div>
                    </div>
                </div>
                <div class="container">
                    <!--Posts section-->
                   <RecentPosts :posts="posts" :is-loading="isLoading"/>
                </div>
            </div>

            <div class="container-fluid px-md-5 py-5" id="our-services">
                    <!--Friend and family section-->
                <FriendFamily :subUsers="subUsers" :customer="customer" :getSubUsers="getSubUsers" :userInfo="userInfo"></FriendFamily>
            </div>

            <div class="container-fluid border-top bg-light-c px-md-5 py-5 " v-if="switches.length>1">
                <CustomerSwitch :customer="customer" :userInfo="userInfo" :initCustomer="initCustomer" :switches="switches"/>
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
    import CustomerVideo from '../../components/front-end/customer/CustomerVideo.vue';
    import CustomerProfile from '../../components/front-end/customer/CustomerProfile.vue';
    import RecentPosts from '../../components/front-end/customer/RecentPosts.vue';
    import AddPost from '../../components/front-end/customer/AddPost.vue';
    import FriendFamily from '../../components/front-end/customer/FriendFamily.vue';
    import CustomerSwitch from '../../components/front-end/customer/CustomerSwitch.vue';
    import Loader from "../front-end/Loader.vue";

    export default {
        name: 'Customer',
        data(){
            return {
                switches : [],
                btnDisabled:false,
                pusher_data:'',
                customer:{},
                activeCustomer:{},
                isLoading:true,
                err: '',
                errors: '',
                userInfo:{},
                posts:[],
                backupPosts:[],
                bio:"",
                video:'',
                toggleValue:'all',
                subUsers:[],
            }
        },
        components: {
            CustomerProfile,
            CustomerVideo,
            CustomerSwitch,
            FriendFamily,
            RecentPosts,
            AddPost,
            Sidebar,
            Footer,
            Loader,
        },
        methods:{
            fetchCustomer(){
                this.axios.post('user/fetch-customer-switch').then((response) => {
                    this.customer = response.data.data;
                });
            },
            togglePosts(filter){
                this.toggleValue=filter;
                if (filter=='all'){
                    this.posts=this.backupPosts;
                }
                if (filter=='my'){
                    this.posts=this.backupPosts.filter((item)=>{
                        return item.user_id==this.userInfo.id;
                    })
                }
            },
            getSubUsers() {
                this.axios.post('user/fetch-sub-users').then((response) => {
                    this.subUsers = response.data.data;
                }).finally(()=>{ this.$updateFont(); });
            },

            fetchUserInfo()
            {
                this.axios.get('user/info').then((response) => {
                    this.bio = response.data.data.details.bio;
                    this.userInfo = response.data.data;
                });
            },
            fetchPosts()
            {
                this.isLoading=true;
                this.axios.post('post/fetch').then((response) => {
                    this.posts       = response.data.data;
                    this.backupPosts = response.data.data;
                }).finally(()=>{ this.$updateFont(); });
                this.isLoading=false;
            },

            startTour()
            {
                var enjoyhint_instance = new EnjoyHint({});

                enjoyhint_instance.set([
                    { 'next .navbar-tour': 'Website menu.' },
                    { 'next .customer-video-tour': 'Customer life story video.' },
                    { 'next .customer-profile-image': 'Customer profile photo' },
                    { 'next .customer-name-tour': 'Customer Name.' },
                    { 'next .video-recording-date-tour': 'Customer video recording date.' },
                    { 'next .add-photo-tour': 'Here you can add new photo'},
                    { 'next .recent-posts-tour': 'Recent uploaded photos will appear here.' },
                    { 'next .fnf-tour': 'Your friends and family list.' }
                ]);

                enjoyhint_instance.run();
                localStorage.setItem("tour-viewed", true);
            },
            getSwitches(){
                this.axios.get('user/get-switches').then(response => {
                    this.switches= response.data.data;
                }).catch(() => {
                        return null;
                }).finally(()=>{ this.$updateFont(); });
                if(!localStorage.getItem("tour-viewed"))
                this.startTour();
            },

            initCustomer(){
                this.fetchUserInfo();
                this.fetchPosts();
                this.fetchCustomer();
                this.getSubUsers();
                this.getSwitches();
            },
        },
        mounted() {
            this.initCustomer();
        }
    }

</script>

<style scoped>
.btn.btn-primary:not(:disabled):not(.disabled):focus {
     -webkit-box-shadow:none!important;
    -moz-box-shadow: none!important;
     box-shadow: none!important;
}

.switch-div:hover{
    background: #f5f5f5 !important;
}
.switch-div{
    cursor: pointer;
}


</style>
