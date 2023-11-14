<template>
    <Header/>
    <div id="main">
        <Sidebar></Sidebar>
        <div class="main-content">
            <div class="page-header">
                <div class="container-fluid d-sm-flex justify-content-between">
                    <h4 class="font-size-25">Booked Sessions</h4>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb font-size-25">
                            <li  class="font-size-25 breadcrumb-item">
                                <router-link to="/dashboard">Dashboard</router-link>
                            </li>
                            <li  class="font-size-25 breadcrumb-item active" aria-current="page">
                                Sessions
                            </li>
                        </ol>
                    </nav>
                </div>
            </div>

            <div class="container-fluid font-size-25">
                <div class="card shadow-lg">
                    <div class="card-body">
                        <div class="float-right">
                            <div class="form-group">
                                <input type="text" placeholder='Search' v-model="search" @input="searchData" class="form-control">
                            </div>
                        </div>
                        <div class="table-responsive">
                            <table class="table table-bordered text-center table-striped table-hover table-sm">
                                <thead class="bg-dark">
                                    <tr class="text-white">
                                        <th class="text-left" style="white-space: nowrap">Customer Name</th>
                                        <th >Start Time</th>
                                        <th>Payment</th>
                                        <th style="white-space: nowrap">Meeting Video Status</th>
                                        <th style="white-space: nowrap">Profile Link</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody v-if="sessions && sessions.data && sessions.data.length > 0">
                                    <tr v-for="(session,index) in sessions.data" :key="index">
                                        <td class="text-left"><router-link :to="'user/view/'+session.customer.id" class="text-primary">{{ session.customer.fullName }}</router-link></td>
                                        <td class="tbl-h-sm" style="white-space: nowrap">
                                            {{ $formatDateAsAmPm(session.start_time) }} (PST)

                                        </td>
                                        <td class="d-none">{{ session.duration }}</td>
                                        <td>
                                            <span class="badge" :class="{'bg-success': session.payment, 'bg-danger': !session.payment}">
                                            {{ session.payment ? 'Completed' : 'Pending' }}
                                        </span>
                                        </td>
                                        <td>{{ session.status }}</td>
                                        <td>
                                            <router-link :to="'/customer-profile/'+session.customer.id"><i class="fa fa-external-link"></i></router-link>
                                        </td>
                                        <td>
                                            <div class="btn-group dropleft">
                                                <button type="button" class=" dropdown-toggle-split btn" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                    <i class="fa fa-ellipsis-v"></i>
                                                </button>
                                                <div class="dropdown-menu">
                                                    <a :href="session.join_url" class="dropdown-item" target="_blank">Join Meeting</a>
                                                    <a class="dropdown-item" href="#" @click="copyLink(session.link)">Copy Payment Link</a>
                                                    <!-- <router-link :to="'upload-video/'+session.user_id" class="dropdown-item" >Upload Video</router-link> -->
                                                    <a v-if="session.status=='waiting'" class="dropdown-item text-danger cursor-pointer" @click="deleteSession(session.id)">Delete</a>
                                                    <router-link :to="'session/view/'+session.id" class="dropdown-item" >View Details</router-link>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                </tbody>
                                <tbody v-else>
                                    <tr>
                                        <td align="center" colspan="100%">No record found.</td>
                                    </tr>
                                </tbody>
                            </table>
                       </div>
                      <Pagination :links="sessions.links" :prev="sessions.prev_page_url" :next="sessions.next_page_url" :currentPage="currentPage" :list="list" />
                   </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import Header from "../../../components/admin/Header.vue";
import Sidebar from "../../../components/admin/Sidebar.vue";
import Pagination from "../../../components/admin/Pagination.vue";

export default {
    name: "SessionList",
    components: {
        Header,
        Sidebar,
        Pagination
    },
    data() {
        return {
            search:'',
            currentPage:1,
            sessions: [],
            err: "",
        };
    },
    methods: {
        deleteSession(id)
        {
            swal({
                    title: 'Are you sure to delete the session?',
                    text: 'It will be soft deleted!',
                    icon: "warning",
                    buttons: {
                    cancel: {
                        text: 'Cancel',
                        value: null,
                        visible: true,
                        className: 'swal-button--cancel bg-danger',
                        closeModal: true,
                    },
                    confirm: {
                        text: 'Yes, delete it!',
                        value: true,
                        visible: true,
                        className: 'swal-button--confirm',
                        closeModal: true,
                    },
                },
                    })
                    .then((result) => {
                    if (result) {
                        this.axios.post('sessions/delete', { 'id': id }).then(() => {
                            this.$moshaToast({ title: 'Session deleted successfully' }, { type: 'success', showIcon: true, hideProgressBar:true });
                            this.list(1);
                        }).catch((error)=>{
                            this.$moshaToast({ title: 'Something went wrong' }, { type: 'danger', showIcon: true, hideProgressBar:true });
                        });
                    }
                });
        },
        copyLink(link) {
            navigator.clipboard.writeText(link)
                .then(() => {
                    this.$moshaToast({
                    title: 'Payment link copied successfully',
                    },
                    {
                    type: 'success',
                    showIcon: true,
                    hideProgressBar: true,
                    });
                });
        },
        searchData()
        {
            this.list();
        },
        async list(page = 1) {
            await this.axios
                .get(`sessions/fetch-all?page=${page}&search=${this.search}`)
                .then(({ data }) => {
                    this.currentPage = data.current_page
                    this.sessions = data;
                })
                .catch(({ response }) => {
                    console.error(response);
                });
        },
    },
    created() {
        this.list();
    },
};
</script>
<style scoped>
@media (max-width: 400px) {
    .tbl-h-sm{
        width: 200px;
    }
}

</style>
