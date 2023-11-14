<template>
    <div id="contact-us" class="container-fluid m-0 bg-primary pt-5 px-0">
        <div class="container ">
            <div class="row justify-content-around " style="margin-bottom: 40px">
                <div class="col-sm-12 col-md-4 d-none">
                    <h6 class="text-uppercase mb-3 font-weight-semibold footer-heading"><router-link to="/"
                            class="text-white">LIFE STORY</router-link></h6>
                    <p class="britannic-family" style="color:rgba(255, 255, 255, 0.5);line-height: 1.2rem;">My name is Michael Grumley. I’m a
                        professional novelist, former geek, son, and father to two wonderful girls. Interestingly, one of
                        the many things my vocation has taught me to do… is to listen, a lot. Which not too long ago led me
                        to a series of unexpected revelations…</p>
                    <ul style="display: none !important;" id="contact" class="footer-payments footer-social-link d-flex">
                        <li><a href="javascript:void(0);" class="text-white"><i class="fa-brands fa-facebook-f"></i></a>
                        </li>
                        <li><a href="javascript:void(0);" class="text-white"><i class="fa-brands fa-twitter"></i></a></li>
                        <li><a href="javascript:void(0);" class="text-white"><i class="fa-brands fa-linkedin-in"></i></a>
                        </li>
                        <li><a href="javascript:void(0);" class="text-white"><i class="fa-brands fa-instagram"></i></a></li>
                    </ul>
                </div>
                <div class="col-sm-12 col-md-4">
                    <h6 class="text-uppercase mb-2 font-weight-semibold footer-heading">Pages</h6>
                    <ul class="list-unstyled footer-ul mb-5 mb-lg-0">
                        <li><a href="#" class="britannic-family">Home</a></li>
                        <li><a href="#our-mission" class="britannic-family">About</a></li>
                        <li><a href="#book-a-session" class="britannic-family">Our Services</a></li>
                    </ul>
                </div>
                <div class="col-sm-12 col-md-4">
                    <h6 class="text-uppercase mb-2 font-weight-semibold footer-heading">How can i do it?</h6>
                    <ul class="list-unstyled footer-ul mb-5 mb-lg-0">
                        <li><a href="#book-a-session" class="britannic-family">Book a Session</a></li>
                        <li><a href="#record-your-lifestory" class="britannic-family">Record Your Lifestory Video With Us</a></li>
                        <li><a href="#share-with-family-and-freinds" class="britannic-family">Share With Family & Friends</a></li>
                    </ul>
                </div>
                <div class="col-sm-12 col-md-3 d-none">
                    <h6 class="text-uppercase mb-2 font-weight-semibold footer-heading">Follow us</h6>
                    <ul id="contact" class="footer-payments footer-social-link d-flex">
                        <li><a href="javascript:void(0);" class="text-white britannic-family"><i class="fa-brands fa-facebook-f"></i></a></li>
                        <li><a href="javascript:void(0);" class="text-white britannic-family"><i class="fa-brands fa-twitter"></i></a></li>
                        <li><a href="javascript:void(0);" class="text-white britannic-family"><i class="fa-brands fa-linkedin-in"></i></a></li>
                        <li><a href="javascript:void(0);" class="text-white britannic-family"><i class="fa-brands fa-instagram"></i></a></li>
                    </ul>
                </div>
            </div>
        </div>
        <div class="col-md-12  pb-2  mt-3" style="border-top: 1px solid #8bb9d3">
            <p class="text-center mt-1 mb-0 text-white">© {{ new Date().getFullYear() }} {{ app_name }}. Developed by <a class="text-white" target="_blank" href="https://napollo.net/">Napollo</a></p>
        </div>
    </div>
</template>

<script>

export default {
    name: 'Footer',
    data() {
        return {
            app_name:''
        }
    },
    mounted() {
        this.app_name = import.meta.env.VITE_APP_NAME;
    }
}
</script>
<style>
.footer-social-link li a {
    margin-left: 10px;
    margin-right: 10px;
}

ul.list-unstyled li {
    margin: 0;
}

ul.list-unstyled li a {
    color: white
}</style>
