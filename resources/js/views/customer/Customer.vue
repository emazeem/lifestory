<template>
    <div class="page landing-page-body">
        <AddPost :showAddPostBtn="false" :fetchPosts1="fetchPosts" />
        <div class="page-main">
            <div class="landing-top-header">
                <Sidebar />
                <div class="container-fluid mt-5 " style="width: 90%">
                    <div class="row mt-5">
                        <div class="col-lg-3 col-lg-4-customer" style="margin-bottom: 4px ">
                            <CustomerProfile :fetchCustomer1="fetchCustomer" :bio1="bio" :fetchUserInfo1="fetchUserInfo"
                                :customer="customer" :userInfo1="userInfo" />
                        </div>
                        <div class="col-lg-9 customer-video-tour">
                            <CustomerVideo :customer="customer" />
                        </div>
                    </div>
                </div>
            </div>
            <!-- Our Services-->
            <div class="container section customer-profile-tour customizable" id="our-services">
                <div v-if="errors" class="alert col-md-8 mx-auto alert-danger alert-dismissible fade show position-relative"
                    role="alert">
                    <ul>
                        <li v-for="(e, index) in errors" :key="index"> {{ e }} </li>
                    </ul>
                    <p class="position-absolute top-0 right-0 mr-2 cursor-pointer" @click="errors = ''" aria-hidden="true">
                        &times;
                    </p>
                </div>

            </div>

            <div class="container-fluid pb-5">
                <div class="row text-center justify-content-center align-items-center">
                    <div class="col-md-12 all-memories-btns mb-4 mt-5 mt-2" style="margin-right:175px">
                        <div v-if="userInfo.role == 'Sub User'" class="btn-group mr-2 float-right" role="group"
                            aria-label="First group">
                            <button type="button" class="btn "
                                :class="(toggleValue == 'all' ? 'btn-primary' : 'bg-white border')"
                                @click="togglePosts('all')"><i class="fa fa-bars"></i> All Memories</button>
                            <button type="button" class="btn "
                                :class="(toggleValue == 'my' ? 'btn-primary' : 'bg-white border')"
                                @click="togglePosts('my')"><i class="fa fa-bars"></i> My Memories</button>
                        </div>
                    </div>
                </div>
                <div class="container-fluid" style="width: 90%">
                    <!--Posts section-->
                    <RecentPosts :posts="posts" :is-loading="isLoading" />
                </div>
            </div>

            <div class="container-fluid  py-5 customer-fnf" id="our-services">
                <FriendFamily :subUsers="subUsers" :customer="customer" :getSubUsers="getSubUsers" :userInfo="userInfo">
                </FriendFamily>
            </div>

            <div class="container-fluid px-md-5 py-5 " v-if="switches.length > 1">
                <CustomerSwitch :customer="customer" :userInfo="userInfo" :initCustomer="initCustomer"
                    :switches="switches" />
            </div>
        </div>
        <div class="mt-3">
            <Footer />
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
    data() {
        return {
            switches: [],
            btnDisabled: false,
            pusher_data: '',
            customer: {},
            activeCustomer: {},
            isLoading: true,
            err: '',
            errors: '',
            userInfo: {},
            posts: [],
            backupPosts: [],
            bio: "",
            video: '',
            toggleValue: 'all',
            subUsers: [],
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
    methods: {
        async fetchCustomer() {
            await this.axios.post('user/fetch-customer-switch').then((response) => {
                this.customer = response.data.data;
            });
        },
        togglePosts(filter) {
            this.toggleValue = filter;
            if (filter == 'all') {
                this.posts = this.backupPosts;
            }
            if (filter == 'my') {
                this.posts = this.backupPosts.filter((item) => {
                    return item.user_id == this.userInfo.id;
                })
            }
        },
        getSubUsers() {
            this.axios.post('user/fetch-sub-users').then((response) => {
                this.subUsers = response.data.data;
            }).finally(() => { this.$updateFont(); });
        },

        async fetchUserInfo() {
            await this.axios.get('user/info').then((response) => {
                this.bio = response.data.data.details.bio;
                this.userInfo = response.data.data;
            });
        },
        fetchPosts() {
            this.isLoading = true;
            this.axios.post('post/fetch').then((response) => {
                this.posts = response.data.data;
                this.backupPosts = response.data.data;
            }).finally(() => { this.$updateFont(); });
            this.isLoading = false;
        },

        startTour() {
            var enjoyhint_instance = new EnjoyHint({});

            if (window.innerWidth < 1024) {
                enjoyhint_instance.set([
                    { 'next .menu-toggler-btn': 'Website Mobile menu.' },
                    { 'next .customer-video-tour-new': 'Customer life story video.' },
                    { 'next .custome-image-left': 'Customer profile photo' },
                    { 'next .customer-name-tour-new': 'Customer Name.' },
                    { 'next .video-recording-date-tour': 'Customer video recording date.' },
                    { 'next .recent-posts-tour': 'Recent uploaded photos will appear here.' },
                    { 'next .fnf-tour': 'Your friends and family list.' }
                ]);
                enjoyhint_instance.run();
            } else {
                enjoyhint_instance.set([
                    { 'next .navbar-tour': 'Website menu.' },
                    { 'next .customer-video-tour-new': 'Customer life story video.' },
                    { 'next .custome-image-left': 'Customer profile photo' },
                    { 'next .customer-name-tour-new': 'Customer Name.' },
                    { 'next .video-recording-date-tour': 'Customer video recording date.' },
                    { 'next .recent-post-single-tour': 'Recent uploaded photos will appear here.' },
                    { 'next .fnf-tour': 'Your friends and family list.' }
                ]);
                enjoyhint_instance.run();
            }

            localStorage.setItem("tour-viewed", true);
        },
        async getSwitches() {
            await this.axios.get('user/get-switches').then(response => {
                this.switches = response.data.data;
            }).catch(() => {
                return null;
            }).finally(() => { this.$updateFont(); });
            // if (!localStorage.getItem("tour-viewed"))
            //     setTimeout(() => { this.startTour();}, 0);
        },

        initCustomer() {
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
    -webkit-box-shadow: none !important;
    -moz-box-shadow: none !important;
    box-shadow: none !important;
}

.switch-div:hover {
    background: #f5f5f5 !important;
}

.switch-div {
    cursor: pointer;
}
</style>
