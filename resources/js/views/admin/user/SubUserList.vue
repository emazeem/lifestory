<template>
    <Head></Head>
    <div id="main">
        <Sidebar></Sidebar>
        <div class="main-content">
            <div class="page-header">
                <div class="container-fluid d-sm-flex justify-content-between">
                    <h4 class="font-size-25">Sub Users</h4>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb font-size-25">
                            <li class="breadcrumb-item">
                                <router-link to="/dashboard">Dashboard</router-link>
                            </li>
                            <li class="breadcrumb-item active" aria-current="page">
                                Sub Users
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
                            <table class="table table-bordered table-striped table-hover table-sm">
                                <thead class="bg-dark">
                                    <tr class="text-white">
                                        <th>Name</th>
                                        <th>Email</th>
                                        <th>Role</th>
                                        <th class="d-none">Status</th>
                                        <th style="white-space: nowrap">Profile Link</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-if="users.data && users.data.length > 0" v-for="(user,index) in users.data" :key="index">
                                        <td>
                                            <router-link :to="'user/view/'+user.id" class="text-primary d-flex align-items-center" >
                                                <img style="width: 30px;height: 30px;object-fit: cover;border-radius: 50%"  class="d-md-block d-none "  :src="user.profile" />
                                                <span style="white-space: nowrap">{{ user.fullName }}</span>
                                            </router-link>
                                        </td>
                                        <td>{{ user.email }}</td>
                                        <td style="white-space: nowrap">{{ user.role }}</td>
                                        <td class="text-center d-none">
                                            <span class="badge mt-2 " :class="[user.is_active ? 'bg-success' : 'bg-danger']">{{ user.is_active?'Active':'InActive' }}</span>
                                        </td>
                                        <td class="text-center d-none">
                                            <div class="btn-group dropleft">
                                                <button type="button" class=" dropdown-toggle-split btn" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                    <i class="fa fa-ellipsis-v"></i>
                                                </button>
                                                <div class="dropdown-menu">
                                                    <router-link :to="'user/view/'+user.id" class="dropdown-item"><i class="fa fa-eye"></i> View Details</router-link>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="text-center">
                                            <router-link :to="'/customer-profile/'+user.id"><i class="fa fa-external-link"></i></router-link>
                                        </td>
                                        <td class="text-center">
                                            <button @click="deleteSubuser(user.id)" class="btn cursor-pointer btn-danger" title="Delete customer">
                                                <i class="fa fa-trash"></i>
                                            </button>
                                        </td>
                                    </tr>
                                    <tr v-else>
                                        <td align="center" colspan="100%    ">No record found.</td>
                                    </tr>
                                </tbody>
                            </table>
                       </div>
                      <Pagination :links="users.links" :prev="users.prev_page_url" :next="users.next_page_url" :currentPage="currentPage" :list="list" />
                   </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import Head from "../../../components/admin/Header.vue";
import Sidebar from "../../../components/admin/Sidebar.vue";
import Pagination from "../../../components/admin/Pagination.vue";

export default {
    name: "SubUserList",
    components: {
        Head,
        Sidebar,
        Pagination
    },
    data() {
        return {
            search:'',
            currentPage:1,
            users: [],
        };
    },
    methods: {
        deleteSubuser(id)
        {
            swal({
                    title: 'Are you sure to delete the subuser?',
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
                    }).then((result) => {
                    if (result) {
                        this.axios.post('user/delete', { 'id': id }).then(() => {
                            this.$moshaToast({ title: 'Subuser deleted successfully' }, { type: 'success', showIcon: true, hideProgressBar:true });
                            this.list(1);
                        }).catch(()=>{
                            this.$moshaToast({ title: 'Something went wrong' }, { type: 'danger', showIcon: true, hideProgressBar:true });
                        });
                    }
                });
        },
        searchData()
        {
            this.list();
        },
        async list(page = 1) {
            await this.axios.get(`user/fetch-all?role=sub-user&page=${page}&search=${this.search}`)
                .then(({ data }) => {
                    this.currentPage = data.current_page
                    this.users = data;
                })
                .catch((response) => {
                    console.error(response);
                });
        },
    },
    created() {
        this.list();
    },
};
</script>

<style scoped></style>
