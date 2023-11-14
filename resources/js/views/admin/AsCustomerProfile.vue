<template>
    <div id="main">
        <Sidebar />
        <div class="main-content">
            <div class="container-fluid">

                <div class="row my-3">
                    <div class="col-md-4 col-12">
                        <CustomerProfile :isAdmin="true" :fetchCustomer1="fetchCustomer" bio1="emazeem"
                            :fetchUserInfo1="fetchUserInfo" :customer="customer" :userInfo1="userInfo" />
                    </div>
                    <div class="col-md-8 col-12">
                        <CustomerVideo :customer="customer" />
                    </div>
                </div>

                <div class="mt-5 mb-0">
                    <div class="col-12 mx-md-3">
                        <RecentPosts :isAdmin="true" :showAll="'/customer-gallery/' + userId" :posts="posts"
                        :is-loading="isLoading" />
                    </div>
                </div>

                <div class="mt-5 mt-md-0 mb-0 asCustomer-fnf">
                    <FriendFamily :isAdmin="true" :subUsers="subUsers" :customer="customer" :getSubUsers="getSubUsers"
                        :userInfo="userInfo" />
                </div>

                <div class="mt-2 mb-0">
                    <CustomerSwitch :isAdmin="true" :customer="customer" :userInfo="userInfo" :switches="switches" />
                </div>

            </div>
        </div>
    </div>
</template>

<script>
import Sidebar from '../../components/admin/Sidebar.vue';
import Header from '../../components/admin/Header.vue';
import CustomerVideo from "../../components/front-end/customer/CustomerVideo.vue";
import CustomerProfile from "../../components/front-end/customer/CustomerProfile.vue";
import CustomerSwitch from "../../components/front-end/customer/CustomerSwitch.vue";
import RecentPosts from "../../components/front-end/customer/RecentPosts.vue";
import FriendFamily from '../../components/front-end/customer/FriendFamily.vue';

export default {
    name: "AsCustomerProfile",
    components: {
        RecentPosts,
        CustomerProfile,
        CustomerSwitch,
        CustomerVideo,
        FriendFamily,
        Header,
        Sidebar,
    },
    data() {
        return {
            userId: '',
            bio: '',
            userInfo: {},
            customer: {},
            posts: [],
            isLoading: false,
            subUsers: [],
            switches: [],
        };
    },
    methods: {
        fetchUserInfo() {
            this.axios.get('user/info?id=' + this.userId).then((response) => {
                this.bio = response.data.data.details.bio;
                this.userInfo = response.data.data;
            });
        },
        fetchCustomer() {
            this.axios.post('user/fetch-customer-switch?id=' + this.userId).then((response) => {
                this.customer = response.data.data;
            });
        },
        fetchPosts() {
            this.isLoading = true;
            this.axios.post('post/fetch', { 'id': this.userId }).then((response) => {
                this.posts = response.data.data;
            }).finally(() => { this.$updateFont(); });
            this.isLoading = false;
        },
        getSubUsers() {
            this.axios.post('user/fetch-sub-users', { id: this.userId }).then((response) => {
                this.subUsers = response.data.data;
            }).finally(() => { this.$updateFont(); });
        },
        getSwitches() {
            this.axios.get('user/get-switches?id=' + this.userId).then(response => {
                this.switches = response.data.data;
            }).catch(() => {
                return null;
            }).finally(() => { this.$updateFont(); });
        },
    },
    created() {
        this.userId = this.$route.params.id;
        this.fetchUserInfo();
        this.fetchCustomer();
        this.fetchPosts();
        this.getSubUsers();
        this.getSwitches();
    },
};
</script>

