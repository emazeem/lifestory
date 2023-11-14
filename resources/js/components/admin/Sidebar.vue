<template>
<div class="navigation">
    <div class="navigation-menu-tab font-size-25">
        <div>
            <div class="navigation-menu-tab-header" data-toggle="tooltip" title="Login user profile" data-placement="right">
                <a href="#" class="nav-link" data-toggle="dropdown" aria-expanded="false">
                    <figure class="avatar avatar-sm">
                        <img v-if="userInfo.profile" :src="userInfo.profile" class="rounded-circle" alt="avatar">
                    </figure>
                </a>
                <div class="dropdown-menu dropdown-menu-right dropdown-menu-big">
                    <div class="p-3 text-center" data-backround-image="https://via.placeholder.com/1000X563">
                        <figure class="avatar mb-3">
                            <img :src="userInfo.profile" class="rounded-circle" alt="image">
                        </figure>
                        <h6 class="d-flex align-items-center justify-content-center">
                            Super Admin
                        </h6>
                    </div>
                    <div class="dropdown-menu-body">
                        <div class="list-group list-group-flush">
                            <router-link to="/profile" class="list-group-item">Profile</router-link>
                            <a href="#" @click="logout" data-toggle="tooltip" data-placement="right" title="Logout" class="list-group-item text-danger">Sign Out!</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="flex-grow-1">
            <ul class="navigation-ul">
                <li>
                    <router-link to="/dashboard" exact :class="{ 'active': $route.path == '/dashboard' }" data-toggle="tooltip" data-placement="right" title="Dashboard">
                        <i class="fa-solid fa-chart-column"></i>
                    </router-link>
                </li>
                <li>
                    <router-link to="/time-slots" :class="{ 'active': $route.path == '/time-slots' }" data-toggle="tooltip" data-placement="right" title="Time Slots">
                        <i class="fa-solid fa-clock"></i>
                    </router-link>
                </li>
                <!-- <li>
                    <router-link to="/calendar" :class="{ 'active': $route.path == '/calendar' }" data-toggle="tooltip" data-placement="right" title="Calendar">
                        <i class="fa-solid fa-calendar"></i>
                    </router-link>
                </li> -->
                <li>
                    <router-link to="/sessions" :class="{ 'active': $route.path == '/sessions' }" data-toggle="tooltip" data-placement="right" title="Sessions">
                        <i class="fa-solid fa-video-camera"></i>
                    </router-link>
                </li>
                <li>
                    <router-link to="/customers" :class="{ 'active': $route.path == '/customers' }" data-toggle="tooltip" data-placement="right" title="Customers">
                        <i class="fa-solid fa-users"></i>
                    </router-link>
                </li>
                <li>
                    <router-link to="/subusers" :class="{ 'active': $route.path == '/subusers' }" data-toggle="tooltip" data-placement="right" title="Subusers">
                        <i class="fa fa-users"></i>
                    </router-link>
                </li>
                <li>
                    <router-link to="/transactions" :class="{ 'active': $route.path == '/transactions' }" data-toggle="tooltip" data-placement="right" title="Transactions">
                        <i class="fa-solid fa-money-bill"></i>
                    </router-link>
                </li>
                <li>
                    <router-link to="/Settings" :class="{ 'active': $route.path == '/Settings' }" data-toggle="tooltip" data-placement="right" title="Settings">
                        <i class="fa-solid fa-gear"></i>
                    </router-link>
                </li>
            </ul>
        </div>
        <div>
            <ul>
                <li>
                    <a href="#" @click="logout" data-toggle="tooltip" data-placement="right" title="Logout">
                        <i class="fa fa-sign-out"></i>
                    </a>
                </li>
            </ul>
        </div>
    </div>
    <!-- begin::navigation menu -->
    <div class="navigation-menu-body d-none">
        <!-- begin::navigation-logo -->
        <div>
            <div id="navigation-logo">
                <a class="life-story-logo" href="/dashboard">
                    <img class="logo" src="../../front-end-assets/logo.png" alt="logo">
                    <img class="logo-light" src="../../front-end-assets/logo.png" alt="light logo">
                </a>
            </div>
        </div>
        <!-- end::navigation-logo -->
        <div class="navigation-menu-group">
            <div id="dashboards">
                <ul>
                    <li class="navigation-divider">Dashboard</li>
                    <li><a href="dashboard">Dashboard</a></li>
                </ul>
            </div>
        </div>
    </div>

    <!-- end::navigation menu -->
</div>
</template>

<script>

export default {
    name:'Sidebar',
    data(){
        return {
            userInfo:{}
        }
    },
    methods: {
        async logout()
        {
            await this.axios.post('user/logout').then(() => {
                localStorage.removeItem('sanctum_token');
            }).catch(() => {
                localStorage.removeItem('sanctum_token');
            }).finally(() => {
                this.$router.push('/login');
            });
        },
        fetchUserInfo()
        {
            this.axios.get('user/info').then((response) => {
                this.userInfo = response.data.data;
            });
        },
    },
    mounted()
    {
        this.fetchUserInfo();
        const listItems = document.querySelectorAll(".navigation-ul li");
        listItems.forEach(function(li) {
            li.addEventListener("click", function() {
                $('.overlay').remove();
                $('body').removeClass('no-scroll');
            });
        });
        const profileBtnOfAdmin = document.querySelectorAll(".list-group-flush .list-group-item");
        profileBtnOfAdmin.forEach(function(li) {
            li.addEventListener("click", function() {
                $('.overlay').remove();
                $('body').removeClass('no-scroll');
            });
        });
    }
}
</script>
