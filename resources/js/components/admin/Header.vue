<template>
    <div class="header">
        <div>
            <ul class="navbar-nav">

                <!-- begin::navigation-toggler -->
                <li class="nav-item navigation-togglers"  v-if="$route.path!='/dashboard'">
                    <a href="#" class="nav-link" title="Go back" @click="$router.go(-1)">
                        <i class="fa fa-arrow-left"></i>
                    </a>
                </li>
                <li class="nav-item navigation-toggler mobile-toggler">
                    <a href="#" class="nav-link" title="Show navigation">
                        <i class="fa-solid fa-bars"></i>
                    </a>
                </li>
                <!-- end::navigation-toggler -->

                <li class="nav-item d-none">
                    <a href="#" class="nav-link dropdown-toggle" data-toggle="dropdown">Create</a>
                    <div class="dropdown-menu">
                        <a href="#" class="dropdown-item">User</a>
                        <a href="#" class="dropdown-item">Category</a>
                        <a href="#" class="dropdown-item">Product</a>
                        <a href="#" class="dropdown-item">Report</a>
                    </div>
                </li>
                <li class="nav-item dropdown d-none">
                    <a href="#" class="nav-link dropdown-toggle" data-toggle="dropdown">Apps</a>
                    <div class="dropdown-menu dropdown-menu-big">
                        <div class="p-3">
                            <div class="row row-xs">
                                <div class="col-6">
                                    <a href="chat.html">
                                        <div class="p-3 border-radius-1 border text-center mb-3">
                                            <i class="width-23 height-23" data-feather="message-circle"></i>
                                            <div class="mt-2">Chat</div>
                                        </div>
                                    </a>
                                </div>
                                <div class="col-6">
                                    <a href="inbox.html">
                                        <div class="p-3 border-radius-1 border text-center mb-3">
                                            <i class="width-23 height-23" data-feather="mail"></i>
                                            <div class="mt-2">Mail</div>
                                        </div>
                                    </a>
                                </div>
                                <div class="col-6">
                                    <a href="app-todo.html">
                                        <div class="p-3 border-radius-1 border text-center">
                                            <i class="width-23 height-23" data-feather="check-circle"></i>
                                            <div class="mt-2">Todo</div>
                                        </div>
                                    </a>
                                </div>
                                <div class="col-6">
                                    <a href="file-manager.html">
                                        <div class="p-3 border-radius-1 border text-center">
                                            <i class="width-23 height-23" data-feather="file"></i>
                                            <div class="mt-2">File Manager</div>
                                        </div>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </li>
            </ul>
        </div>
        <div>
            <ul class="navbar-nav">
                <li class="nav-item dropdown ok">
                    <a href="#" class="nav-link" :class="(unreadNotifications.length?'nav-link-notify':'')" title="Notifications" data-toggle="dropdown" aria-expanded="false">
                       <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1" stroke-linecap="round" stroke-linejoin="round" class="feather feather-bell">
                        <path d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9"></path>
                        <path d="M13.73 21a2 2 0 0 1-3.46 0"></path>
                    </svg>
                    </a>
                    <div class="dropdown-menu dropdown-menu-right dropdown-menu-big" x-placement="top-end" style="position: absolute; will-change: transform; top: 0px; left: 0px; transform: translate3d(-252px, -11px, 0px);">
                        <div class="p-4">
                            <div class="d-flex justify-content-between">
                                <h6 class="mb-0">Notifications</h6>
                                <a v-if="unreadNotifications.length>0" class="btn cursor-pointer btn-sm btn-primary text-white py-0"  @click="readAll()">Mark All Read</a>
                            </div>
                        </div>
                        <div>
                            <ul class="list-group list-group-flush notification-div" tabindex="5" style="overflow: hidden; outline: none; touch-action: none;">
                                <li v-for="(item,index) in unreadNotifications" :key="index">
                                    <router-link :to="item.data.url" class="list-group-item d-flex hide-show-toggler">
                                        <div>
                                            <figure class="avatar avatar-sm m-r-15">
                                                    <span class="avatar-title bg-success-bright text-success rounded-circle">
                                                        <i class="ti-user"></i>
                                                    </span>
                                            </figure>
                                        </div>
                                        <div class="flex-grow-1">
                                            <p class="mb-0 line-height-20 d-flex justify-content-between">
                                                <span v-html="item.data.msg"></span>
                                                <!-- <i @click="readAll(item.id)" title="Mark as read" data-toggle="tooltip" class="hide-show-toggler-item fa fa-check font-size-11" data-original-title="Mark as read"></i> -->
                                                <!-- <i title="Delete notification" @click="deleteNotification(item.id,'new')" data-toggle="tooltip" class="hide-show-toggler-item fa fa-trash ml-1 text-danger font-size-11" data-original-title="Delete notification"></i> -->
                                            </p>
                                            <span class="text-muted small">20 min ago</span>
                                        </div>
                                    </router-link>
                                </li>
                                <li v-if="oldNotifications.length>0" class="text-divider small pb-2 pl-3 pt-3">
                                    <span>Old notifications</span>
                                </li>
                                <li v-for="(item,index) in oldNotifications" :key="index">
                                    <a href="#" class="list-group-item d-flex hide-show-toggler">
                                        <div>
                                            <figure class="avatar avatar-sm m-r-15">
                                                    <span class="avatar-title bg-warning-bright text-warning rounded-circle">
                                                        <i class="ti-package"></i>
                                                    </span>
                                            </figure>
                                        </div>
                                        <div class="flex-grow-1">
                                            <p class="mb-0 line-height-20 d-flex justify-content-between">
                                               <span v-html="item.data.msg"></span>
                                                <!-- <i title="Delete notification" @click="deleteNotification(item.id)" data-toggle="tooltip" class="hide-show-toggler-item fa fa-trash text-danger font-size-11" data-original-title="Delete notification"></i> -->
                                            </p>
                                            <span class="text-muted small">{{ item.read }}</span>
                                        </div>
                                    </a>
                                </li>
                            </ul>
                        </div>
                        <div class="p-2 d-none text-right">
                            <ul class="list-inline small">
                                <li class="list-inline-item">
                                    <a v-if="oldNotifications.length>0" href="#" @click="readAll('delete')">Delete All Read</a>
                                </li>
                            </ul>
                        </div>
                    <div id="ascrail2004" class="nicescroll-rails nicescroll-rails-vr" style="width: 8px; z-index: 1000; cursor: default; position: absolute; top: 65.6px; left: 292px; height: 296.65px; touch-action: none; display: none; opacity: 0;"><div class="nicescroll-cursors" style="position: relative; top: 0px; float: right; width: 6px; height: 0px; background-color: rgb(66, 66, 66); border: 1px solid rgb(255, 255, 255); background-clip: padding-box; border-radius: 5px; touch-action: none;"></div></div><div id="ascrail2004-hr" class="nicescroll-rails nicescroll-rails-hr" style="height: 8px; z-index: 1000; top: 354.25px; left: 0px; position: absolute; cursor: default; display: none; opacity: 0;"><div class="nicescroll-cursors" style="position: absolute; top: 0px; height: 6px; width: 0px; background-color: rgb(66, 66, 66); border: 1px solid rgb(255, 255, 255); background-clip: padding-box; border-radius: 5px; left: 0px;"></div></div></div>
                </li>
                <!-- <li class="nav-item dropdown">
                    <a href="#" class="nav-link" title="Fullscreen" data-toggle="fullscreen">
                        <i class="maximize fa-solid fa-expand"></i>
                        <i class="minimize fa-solid fa-minimize"></i>
                    </a>
                </li> -->
            </ul>
            <!-- begin::mobile header toggler -->
            <ul class="navbar-nav d-flex align-items-center">
                <li class="nav-item header-toggler">
                    <a href="#" class="nav-link">
                        <i data-feather="arrow-down"></i>
                    </a>
                </li>
            </ul>
            <!-- end::mobile header toggler -->
        </div>
    </div>
</template>

<script>
export default {
    name: 'Header',
    data(){
        return {
            oldNotifications: [],
            unreadNotifications: [],
        }
    },
    methods:{
        readAll(option='')
        {
            this.axios.post('notifications/read-all',{option:option}).then((response) => {
                this.fetchOldNotifications();
                this.fetchUnreadNotifications();
            })
        },
        fetchOldNotifications()
        {
            this.axios.post('notifications/old').then((response) => {
                this.oldNotifications = response.data.data;
            });
        },
        fetchUnreadNotifications()
        {
            this.axios.post('notifications/fetch-unread').then((response) => {
                this.unreadNotifications = response.data.data.notifications;
            })
        }
    },
    mounted()
    {
        this.fetchOldNotifications();
        this.fetchUnreadNotifications();

        setInterval(() => {
            this.fetchOldNotifications();
            this.fetchUnreadNotifications();
        }, 60000);
    }
}
</script>
