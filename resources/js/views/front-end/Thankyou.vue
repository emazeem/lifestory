<template>
    <div class="page landing-page-body">
        <div class="page-main">
            <div class="landing-top-header">
                <Sidebar></Sidebar>
            </div>
            <div class="vh-100 d-flex justify-content-center align-items-center">
                <div>
                    <div class="mb-4 text-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="text-success bi bi-check-circle-fill" width="75"
                            height="75" fill="currentColor" viewBox="0 0 16 16">
                            <path
                                d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0zm-3.97-3.03a.75.75 0 0 0-1.08.022L7.477 9.417 5.384 7.323a.75.75 0 0 0-1.06 1.06L6.97 11.03a.75.75 0 0 0 1.079-.02l3.992-4.99a.75.75 0 0 0-.01-1.05z" />
                        </svg>
                    </div>
                    <div class="text-center">
                        <h1>Thank You !</h1>
                        <p>
                            You have paid the amount successfully for booking a session,
                        </p>
                        <!-- Corrected the @click handler method name -->
                        <button @click="closeWindow()" class="btn text-white btn-primary">Close Window</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import Sidebar from "../../components/front-end/Sidebar.vue";
import Footer from "../../components/front-end/Footer.vue";

export default {
    name: "Thankyou",
    components: {
        Sidebar,
        Footer,
    },
    methods:{
        closeWindow()
        {
          window.close()
        }
    }
};
</script>
