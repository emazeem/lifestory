<template>
    <div class="page landing-page-body">
        <div class="page-main">
            <div class="landing-top-header">
                <Sidebar></Sidebar>
            </div>
            <div class="container-fluid px-md-5 p-1 bg-light-c py-5 ">
                <div class="container py-5">
                    <div class="d-flex flex-column align-items-center">
                        <div class="col-md-5 bg-light mb-2 b" v-for="user in switches">
                            <div class="col-md-12 d-flex cursor-pointer align-items-center justify-content-between py-4 px-2" @click="loginAs(user)" v-if="userInfo.id==user.id">
                                <div>
                                    <p class=" ct-pl mt-2 mb-0"  >
                                        View My Lifestory
                                    </p>
                                    <p class="text-danger ct-pl font-italic" v-if="user.disabled">This account is in-active</p>
                                </div>
                                <img class="img-fluid" style="object-fit: cover;width: 50px;height: 50px;border-radius: 50%" :src="user.profile" alt="" >
                            </div>

                        </div>


                    </div>

                    <div class="row text-center mb-4">
                        <div class="col-md-12">
                            <router-link v-if="userInfo.role=='Sub User'" to="schedule-lifestory"
                                class="btn btn-primary text-light btn-lg col-md-5 py-4 me-2 mb-5">
                                <h4>Schedule my Lifestory</h4>
                            </router-link>
                            <h1 class="ct-card-header mt-5">Lifestories you are invited to</h1>
                        </div>
                    </div>
                    <div class="d-flex flex-column align-items-center">
                        <div class="col-md-5 bg-light mb-2 b" v-for="user in switches">
                            <div class="col-md-12 d-flex cursor-pointer align-items-center justify-content-between py-4 px-2" @click="loginAs(user)" v-if="userInfo.id!=user.id">
                                <div>
                                    <p class="ct-pl mt-2 mb-0"  >
                                        <h3>{{user.fullName}}</h3>
                                    </p>
                                    <p class="text-danger ct-pl font-italic" v-if="user.disabled">This account is in-active</p>
                                </div>
                                <img class="img-fluid" style="object-fit: cover;width: 80px;height: 80px;border-radius: 50%" :src="user.profile" alt="" >
                            </div>

                        </div>


                    </div>

                </div>
            </div>

        </div>
        <div class="">
            <div class="side-app">
                <Footer></Footer>
            </div>
        </div>
    </div>
</template>

<script>
import Sidebar from "../../components/front-end/Sidebar.vue";
import Footer from "../../components/front-end/Footer.vue";

export default {
    name: "SwitchAccount",
    data() {
        return {
            switches : [],
            userInfo:{}
        };
    },
    components: {
        Sidebar,
        Footer,
    },

    mounted() {
        this.getSwitches();
        this.fetchUserInfo();
    },
    methods: {
         fetchUserInfo()
        {
            this.axios.get('user/info').then((response) => {
                this.userInfo = response.data.data;
                localStorage.setItem('user',JSON.stringify(response.data.data));
            })
        },
        loginAs(subscriber)
        {
            if (subscriber.disabled=='' || subscriber.disabled==null){
                this.axios.post('/user/update-switch',{'id':subscriber.id}).then(response => {
                    this.$router.push('/home');
                }).catch(error => {
                    return null;
                });
            }
        },
        getSwitches(){
            this.axios.get('/user/get-switches').then(response => {
                this.switches= response.data.data;
            })
            .catch(error => {
                return null;
            });
        }
    },
};
</script>
