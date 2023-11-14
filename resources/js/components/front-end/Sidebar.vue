<template>
    <nav class="navbar container-fluid px-md-5 navbar-tour navbar-expand-lg navbar-light">
        <router-link class="navbar-brand" to="/">
            <img src="../../front-end-assets/logo.png" width="200" />
        </router-link>
        <button class="navbar-toggler menu-toggler-btn" type="button" data-toggle="collapse"
            data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul v-if="showCustomerMenu" class="navbar-nav ml-auto">
                <li class="nav-item" :class="$route.fullPath == '/home' ? 'active' : ''">
                    <router-link class="nav-link" to="/home">Home </router-link>
                </li>

                <li class="nav-item" :class="$route.fullPath == '/gallery' ? 'active' : ''">
                    <router-link class="nav-link" to="/gallery">Gallery </router-link>
                </li>
                <li class="nav-item d-none" v-if="isLoggedIn && userInfo.role == 'Customer'"
                    :class="$route.fullPath == '/friends-and-family' ? 'active' : ''">
                    <router-link class="nav-link" to="/friends-and-family">My Friends & Family</router-link>
                </li>

                <li class="nav-item dropdown" :class="$route.fullPath == '/notifications' ? 'active' : ''">
                    <a href="#" id="navbarDropdown" @click="readCounter()" role="button" data-toggle="dropdown"
                        aria-haspopup="true" aria-expanded="false">
                        Notifications <span v-if="showCount" class="badge badge-primary px-2 ml-1"
                            style="line-height: 17px">{{ unreadNotifications.length }}</span>
                    </a>
                    <div class="dropdown-menu notification" aria-labelledby="navbarDropdown">
                        <ul class="list-unstyled">
                            <div class="text-right">
                                <div @click="readAll()" v-if="unreadNotifications.length > 0"
                                    class="btn m-2 text-center cursor-pointer btn-sm btn-primary  text-white py-0">Mark All
                                    Read</div>
                            </div>
                            <li v-if="notifications.length > 0" class="media p-2"
                                v-for="(notification, index) in notifications" :key="index"
                                @click="navigateTo(notification.id, notification.data.url)">
                                <img class="profile-image"
                                    :src="notification.data.sender ? notification.data.sender.profile : ''"
                                    style="object-fit: cover">
                                <div class="media-body" v-if="notification.data && notification.data.sender">
                                    <p class="mt-2" v-html="notification.data.msg"></p>
                                </div>
                            </li>
                            <li v-else class="media py-3">
                                <div class="media-body text-center">
                                    <h6 class="mt-2 " style="color: gray">no notifications</h6>
                                </div>
                            </li>
                        </ul>
                        <div v-if="notifications.length > 0"
                            class="card p-0 m-0 bg-light border-top d-flex align-items-center">
                            <router-link to="/notifications" class=" text-primary show-all-notifications"
                                style="padding: 10px!important;">Show All Notifications</router-link>
                        </div>
                    </div>
                </li>

                <li class="nav-item cursor-pointer menu-icon-tablet">
                    <div
                    class="side-menu__itemw profile-nav-image-btn">
                    <div class="btn-group dropdown ">
                        <span class="cursor-pointer px-2 py-1 rounded" data-toggle="dropdown" aria-haspopup="true"
                            aria-expanded="false">
                            <img class="profile-image" :src="userInfo.profile" width="37" height="37"
                                style="object-fit: cover; border-radius: 50%">
                        </span>
                        <div class="dropdown-menu dropdown-my-tag profile-dropdown-nav pt-0">
                            <router-link to="/home" style="margin-top: 10px !important;" class="dropdown-item"><i class="fa fa-home mr-2"></i> My
                                Home</router-link>
                            <router-link to="/setting" class="dropdown-item"><i class="fa fa-cogs mr-2"></i>
                                Settings</router-link>
                            <router-link v-if="swtch" to="/switch-account" class="dropdown-item"><i
                                    class="fa fa-edit mr-2"></i>
                                Switch Account</router-link>
                            <div class="dropdown-divider"></div>
                            <a class="dropdown-item cursor-pointer" @click="logout()"><i class="fa fa-sign-out mr-2"></i>
                                Logout</a>
                        </div>
                    </div>
                </div>
                </li>

            </ul>
            <ul v-else class="navbar-nav ml-auto navbar-items-nav-only">
                <li class="nav-item active">
                    <router-link class="nav-link landing-page-ul-link"
                        :to="(isLoggedIn && (userInfo.role == 'Customer' || userInfo.role == 'Sub User')) ? '/home' : '/'">Home</router-link>
                </li>
                <li class="nav-item d-none">
                    <a class="nav-link landing-page-ul-link" href="#our-mission">Mission</a>
                </li>

                <li class="nav-item">
                    <a class="nav-link landing-page-ul-link" href="#our-mission">About</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link landing-page-ul-link" href="#book-a-session">Our Service</a>
                </li>
                <li class="nav-item  d-md-none d-block" v-if="!isLoggedIn">
                    <router-link to="/login">
                        <span class="btn btn-primary landing-page-ul-link" style="border-radius:4px">Sign In</span>
                    </router-link>
                </li>
                <li class="nav-item  d-md-none d-block" v-if="isLoggedIn && isAdmin">
                    <router-link to="/dashboard">
                        <span class="btn btn-primary landing-page-ul-link">Dashboard</span>
                    </router-link>
                </li>
                <li class="nav-item cursor-pointer" v-if="isLoggedIn && (userInfo.role == 'Customer' || userInfo.role == 'Sub User')">
                <div
                    class="side-menu__itemw profile-nav-image-btn">
                    <div class="btn-group dropdown">
                        <span class="cursor-pointer px-2 py-1 rounded" data-toggle="dropdown" aria-haspopup="true"
                            aria-expanded="false">
                            <img class="profile-image" :src="userInfo.profile" width="37" height="37"
                                style="object-fit: cover; border-radius: 50%">
                        </span>
                        <div class="dropdown-menu dropdown-my-tag profile-dropdown-nav pt-0">
                            <router-link to="/home" class="dropdown-item" style="margin-top:10px !important;"><i class="fa fa-home mr-2"></i> My
                                Home</router-link>
                            <router-link to="/setting" class="dropdown-item"><i class="fa fa-cogs mr-2"></i>
                                Settings</router-link>
                            <router-link v-if="swtch" to="/switch-account" class="dropdown-item"><i
                                    class="fa fa-edit mr-2"></i>
                                Switch Account</router-link>
                            <div class="dropdown-divider"></div>
                            <a class="dropdown-item cursor-pointer" @click="logout()"><i class="fa fa-sign-out mr-2"></i>
                                Logout</a>
                        </div>
                    </div>
                </div>
                </li>

                <li v-if="!isLoggedIn" class="nav-item">

                <router-link class="side-menu__itemw sign-in d-md-block d-none" to="/login">
                    <span class="side-menu__label py-2 px-3">Sign in</span>
                </router-link>

            </li>
            </ul>
        </div>

        <router-link v-if="isLoggedIn && isAdmin" class="side-menu__itemw sign-in d-md-block d-none" to="/dashboard">
            <span class="side-menu__label py-2 px-3 landing-page-ul-link dashboard-front-end">
                <i class="d-md-inline d-none" style="font-style: normal">Dashboard</i>
            </span>
        </router-link>
    </nav>
</template>

<script>
export default {
    name: "Sidebar",
    data() {
        return {
            classContainer: 'container',
            showCount: false,
            loggedInUserID: '',
            isLoggedIn: false,
            userInfo: {
                id: '',
                role: '',
            },
            roles: [],
            activeAccount: '',
            notifications: [],
            unreadNotifications: [],
            showCustomerMenu: false,
            swtch: false,
            isAdmin: false,
        };
    },
    methods: {
        readAll(option = '') {
            this.axios.post('notifications/read-all', { option: option }).then((response) => {
                this.fetchUnreadNotifications();
                this.fetchNotifications();
            })
        },
        readCounter() {
            this.axios.post('notifications/read-counter').then(() => {
                this.showCount = false;
            });
        },
        getSwitch() {
            this.axios.post('user/is-switch').then(response => {
                this.swtch = response.data.data;
            })
        },
        logout() {
            this.axios.post('user/logout').then(() => { }).finally(() => {
                localStorage.removeItem('user');
                localStorage.removeItem('sanctum_token');
                this.$router.push('/login');
            });
        },
        fetchUserInfo() {
            this.axios.get('user/info').then((response) => {
                this.userInfo = response.data.data;
                localStorage.setItem('user', JSON.stringify(response.data.data));
            })
        },
        fetchNotifications() {
            this.axios.post('notifications/fetch').then((response) => {
                this.notifications = response.data.data;
            })
        },
        fetchUnreadNotifications() {
            this.axios.post('notifications/fetch-unread').then((response) => {
                this.unreadNotifications = response.data.data.notifications;
                this.showCount = response.data.data.showCount;
            })
        },
        navigateTo(id, url) {
            this.axios.post('notifications/read', { id: id }).then(() => { }).finally(() => {
                this.$router.push('/' + url);
            });
            this.fetchUnreadNotifications();
        },
        getRoles() {
            this.axios.get('/user/get-roles').then(response => {
                this.roles = response.data.data;
                this.activeAccount = response.data.data.current;
            });
        },
    },
    mounted() {
        this.$updateFont();

        if (this.$route.meta.role == 'Not Admin') this.showCustomerMenu = true;

        this.isLoggedIn = localStorage.getItem("sanctum_token") !== null;

        if (this.isLoggedIn) {
            const user = JSON.parse(localStorage.getItem("user"));
            if (user && (user.role == "Sub User" || user.role == "Customer")) {
                this.fetchUserInfo();
                this.fetchNotifications();
                this.fetchUnreadNotifications();
                this.getRoles();
                this.getSwitch();

                setInterval(() => {
                    this.fetchUnreadNotifications();
                    this.fetchNotifications();
                }, 60000);

            }
            else if (user && user.role == "Super Admin") {
                this.isAdmin = true;
            }
        }
    }
}
</script>

<style scoped>

/* Position the dropdown to the left */
.dropdown-menu.notification {
    right: 98%;
    left: auto;
    top: 60px;
  }

  /* Adjust the arrow position */
  .dropdown-menu.notification::before {
    right: auto;
    left: 100%;
    border-left: 0.375rem solid transparent;
    border-right: 0;
  }

  .dropdown-menu.notification {
    right: 10% !important;
}

li.nav-item.active a.nav-link::after,
li.nav-item:hover a.nav-link::after {
    content: "";
    position: absolute;
    top: 45px !important;
    height: 3px !important;
    width: 50%;
    border-radius: 50px;
    background: #6096b4 !important;
    transition: transform 0.3s cubic-bezier(0.5, 0.7, 0.8, 1) !important;

}

.nav-item.active a {
    color: #6096b4 !important;
    font-weight: bold;
}

.nav-item a {
    display: flex;
    margin: -3px -4px !important;
    text-decoration: none;
    position: relative;
    color: #7b8191 !important;
    padding: 20px 16px !important;
    font-weight: 400;
}

.profile-dropdown-nav {
    margin-right: 10px;
    left: -130px;
    margin-top: 5px;
}




ul.navbar-nav li .dropdown-toggle::after {
    display: none;
}

ul.navbar-nav li ul li {
    cursor: pointer;
    border-bottom: 1px solid #d7d5d5;
}

ul.navbar-nav li ul li:hover {
    background: #f3f0f0;
}

ul.navbar-nav li ul {
    max-height: 300px;
    overflow-y: scroll;
}


ul.navbar-nav li .notification {
    width: 350px;
    padding: 0px;
}

ul.navbar-nav li .notification .card {
    border: 0px;
}

ul.navbar-nav li .notification .card .card-header {
    text-transform: uppercase;
}

ul.navbar-nav li .notification .media img {
    border-radius: 50%;
    width: 30px;
    height: 30px;
    margin-right: 15px;
    margin-top: 5px;
}

ul.navbar-nav li .notification .media .media-body {
    font-size: 12px;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}

ul.navbar-nav li .notification .media .media-body h5 {
    font-size: 15px;
}

.custom-nav-item-active {
    background: #e8e8e8;
}

.dropdown-my-tag a {
    padding-top: 5px !important;
    padding-bottom: 5px !important;
}

</style>
