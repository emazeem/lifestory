<template>
    <div class="page landing-page-body">
        <div class="page-main">
            <div class="landing-top-header">
                <Sidebar></Sidebar>
            </div>
            <div class="vh-100 d-flex justify-content-center align-items-center">
                <div>
                    <div class="mb-4 text-center">
                        <svg
                        xmlns="http://www.w3.org/2000/svg"
                        class="text-danger bi bi-exclamation-circle-fill"
                        width="75"
                        height="75"
                        fill="currentColor"
                        viewBox="0 0 16 16"
                        >
                        <path
                            d="M8 16A8 8 0 1 0 8 0a8 8 0 0 0 0 16zm-.49-11a.5.5 0 0 1 .49.51v5a.5.5 0 0 1-1 0v-5A.5.5 0 0 1 7.51 5h1a.5.5 0 0 1 0 1h-.5v1.5a.5.5 0 0 1-1 0V6h-.5a.5.5 0 0 1 0-1h1zm0 7a.5.5 0 0 1 .5.5v.5a.5.5 0 0 1-1 0v-.5a.5.5 0 0 1 .5-.5zm0-9a1 1 0 0 1 .993.883L8.5 5.5V12a1 1 0 0 1-1.993.117L6.5 12V5.5A1.5 1.5 0 0 1 8 4zm0-2a2.5 2.5 0 1 0 0 5 2.5 2.5 0 0 0 0-5z"
                        />
                        </svg>

                    </div>
                    <div class="text-center">
                        <h1>Subscription failed !</h1>
                        <p>Your subscription is failed with life story project.</p>
                        <router-link to="/subscription" class="btn text-white btn-primary">Back to subscription</router-link>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import Sidebar from "../../components/front-end/Sidebar.vue";
export default {
    name: "FailedSubscription",
    components:{
        Sidebar
    }
};
</script>