<template>
<div class="container-fluid m-0 bg-primary">
    <div class="container py-5">
        <div class="row">
                        <div class="col-lg-4 col-sm-12 col-md-12">
                            <h6 class="text-uppercase mb-3 font-weight-semibold footer-heading"><router-link to="/" class="text-white">LIFE STORY</router-link></h6>
                            <p style="color:rgba(255, 255, 255, 0.5);line-height: 1.2rem;">My name is Michael Grumley. I’m a professional novelist, former geek, son, and father to two wonderful girls. Interestingly, one of the many things my vocation has taught me to do… is to listen, a lot. Which not too long ago led me to a series of unexpected revelations…</p>
                            <ul style="display: none !important;" id="contact" class="footer-payments footer-social-link d-flex">
                                <li><a href="javascript:void(0);" class="text-white"><i class="fa-brands fa-facebook-f"></i></a></li>
                                <li><a href="javascript:void(0);" class="text-white"><i class="fa-brands fa-twitter"></i></a></li>
                                <li><a href="javascript:void(0);" class="text-white"><i class="fa-brands fa-linkedin-in"></i></a></li>
                                <li><a href="javascript:void(0);" class="text-white"><i class="fa-brands fa-instagram"></i></a></li>   
                            </ul>
                        </div>
                        <div class="col-lg-2 col-sm-6 col-md-4">
                            <h6 class="text-uppercase mb-2 font-weight-semibold footer-heading">Pages</h6>
                            <ul class="list-unstyled footer-ul mb-5 mb-lg-0">
                                <li><a href="#">How it works</a></li>
                                <li><a href="#">packages</a></li>
                                <li><a href="#">Blog</a></li>
                                <li><a href="#">Demo</a></li>
                            </ul>
                        </div>
                        <div class="col-lg-2 col-sm-6 col-md-4">
                            <h6 class="text-uppercase mb-2 font-weight-semibold footer-heading">Service
                            </h6>
                            <ul class="list-unstyled mb-5 footer-ul mb-lg-0">
                                <li><router-link to="/session">Book a section</router-link></li>
                                <li><a href="#">Record your video</a></li>
                                <li><a href="#">Share with family & friends</a></li>
                            </ul>
                        </div>
                        <div class="col-lg-4 col-sm-12 col-md-4">
                            <h6 class="text-uppercase mb-2 font-weight-semibold footer-heading">Map Location</h6>
                            <div class="footer-map">
                                <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d387194.0622843933!2d-74.30932844391306!3d40.6970192842449!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x89c24fa5d33f083b%3A0xc80b8f06e177fe62!2sNew%20York%2C%20NY!5e0!3m2!1sen!2sus!4v1686683244937!5m2!1sen!2sus" width="300" height="200" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
                            </div>
                            <hr>
                        </div>
                    </div>
    </div>

</div>
  
</template>

<script>

export default {
    name: 'Footer',
}
</script>
<style>
    .footer-social-link li a{
        margin-left: 10px;
        margin-right: 10px;
    }
    ul.list-unstyled li{
        margin: 0;
    }
    ul.list-unstyled li a{
        color:rgba(255, 255, 255, 0.5) !important;
    }
    
</style>