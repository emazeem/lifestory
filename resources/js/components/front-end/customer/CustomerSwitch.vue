<template>
    <div v-if="switches.length>0" class="d-flex flex-column align-items-center" >
        <h1 class="feed-title font-size-13 ct-card-header mt-5 mb-4">Lifestories you are invited to</h1>
        <div class="col-md-5 bg-light border mt-3 switch-div" v-for="user in switches">
            <div class="col-md-12 d-flex align-items-center cursor-pointer justify-content-between py-4 px-2" @click="loginAs(user)">
                <div>
                    <p class="ct-pl mt-2 mb-0" >
                        <h4>{{ userInfo.id==user.id? 'My Lifestory' : user.fullName }}</h4>
                    </p>
                    <p class="text-danger ct-pl font-italic" v-if="user.disabled">This account is in-active</p>
                </div>
                <img class="img-fluid" style="object-fit: cover;width: 80px;height: 80px;border-radius: 50%" :src="user.profile" alt="" v-if="customer.id!=user.id">
                <div v-else style="object-fit: cover;width: 80px;height: 80px;border-radius: 50%;" class="bg-success d-flex align-items-center justify-content-center">
                    <i class="fa fa-check fa-2x"></i>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    name:'CustomerSwitch',
    props: {
        isAdmin: {
            type: Boolean,
            required: false,
            default: false
        },
        customer: {
            type: undefined,
            required: true,
        },
        switches: {
            type: undefined,
            required: true,
        },
        userInfo: {
            type: undefined,
            required: true,
        },
        initCustomer: {
            type: undefined,
            required: false,
        },
    },
    methods:{
        loginAs(subscriber)
            {
                if (!this.isAdmin && (subscriber.disabled=='' || subscriber.disabled==null))
                {
                    this.axios.post('/user/update-switch',{'id':subscriber.id}).then(() => {
                        this.initCustomer();
                    }).catch(() => { return null; });
                }
        },
    }
}
</script>
