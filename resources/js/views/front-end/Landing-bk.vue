<template>
    <div class="page landing-page-body">
        <div class="page-main">
            <div class="landing-top-header">
                <Sidebar></Sidebar>
                <div style="background-image: url(../../front-end-assets/banner-01.png);background-size: contain"
                    class="demo-screen-headline main-demo main-demo-1 overflow-hidden pb-0 main-banner" id="home">
                    <div class="container">
                        <div class="overflow-hidden">
                            <div class="row">
                                <div class="col-lg-12 text-center ">
                                    <h1 class="text-shadow text-white main-banner-text">It’s been said that when a person dies the
                                        world loses a library. <br>
                                        Not Anymore.
                                    </h1>
                                    <router-link to="schedule-lifestory" class="btn text-white btn-pill btn-create-your-story btn-w-md py-2 me-2">Schedule a Lifestory Recording<i class="fe fe-activity ms-2"></i></router-link>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Our Services-->
            <div class="section customizable pb-6 p-md-5 p-2" id="our-services">
                <div class="container">
                    <div class="row text-center justify-content-center">
                        <div class="col-lg-8 mb-4 mt-3">
<!--                            <h3>Who we are</h3>-->
                            <h3 id="our-mission">Our mission</h3>
                            <h5>Save as may libraries as possible.</h5>
                        </div>
                    </div>
                    <div class="row align-items-center">

                        <div class="col-md-8">
                            <div style="text-align: justify">
                                <p>We are all on the same path. Every one of us. Some further along than others, but in the end we all reach the
                                    same destination. Eventually we all must leave the incredible game called Life. But ‘absent’ does not have to
                                    mean ‘forgotten’.
                                </p>
                                <p>The sad truth is that once we leave, most of us will only be remembered for a generation or two. Rich and
                                    fascinating lives lost forever to history, as well as our families. And to generations who will in time wonder
                                    not just where they came from, but from whom.
                                </p>
                                <p>This is Project Lifestory.</p>
                                <p>Never before in history have we possessed the ability to exist far beyond our own lives. To share with future
                                    generations who we are and what we’ve done. The lives we’ve led, what we have learned, and important
                                    lessons that should be passed on to future generations. Life stories told in our own words, in our own voices,
                                    and through our own personalities. Life stories which our families can keep forever.
                                </p>
                                <p>
                                    Project Lifestory is on a mission to change the concept of ‘family ancestry’ forever. With high resolution life
                                    story videos that can be kept in perpetuity. Because the most important thing we can do for ourselves and
                                    our future families… is not to let our libraries disappear with us.
                                </p>
                            </div>
                        </div>
                        <div class="col-md-4 customize-image text-center">
                            <img class="img-fluid p-2" src="../../front-end-assets/service-01.png" alt="">
                        </div>
                        <div class=" col-12 text-center my-3">
                            <router-link to="schedule-lifestory" class="btn text-white btn-pill btn-create-your-story btn-w-md py-2 me-2">Schedule a Lifestory Recording</router-link>
                        </div>
                    </div>
                </div>
            </div>

            <div class="section customizable pb-6 p-md-5 p-2" id="how">
                <div class="container">
                    <div class="row text-center justify-content-center">
                        <div class="col-lg-8">
                            <h3 id="how-can-i-do">How can I do it?</h3>
                        </div>
                    </div>
                    <div class="row d-flex justify-content-center align-items-center mb-4">
                        <div class="col-md-8 col-12 text-center ">
                            <div style="text-align: justify">
                                <p>Let Project Lifestory help you preserve your library through an easy one-hour conversation – right from your own phone or computer – producing a high-quality video of your life story. Your own library to keep forever as part of your family’s permanent genealogy. A personal and interactive history of the wholly unique life you have lived and experienced during your time here on good ‘ole planet Earth.</p>
                                <p>Just click on the button below and send us your details. We will contact you to explain our surprisingly simple and enjoyable process. Trust us, you’ll be glad you did.</p>
                            </div>
                            <router-link to="schedule-lifestory" class="btn text-white btn-pill btn-create-your-story btn-w-md py-2 me-2">Schedule a Lifestory Recording</router-link>
                        </div>
                    </div>
                </div>
            </div>

        </div>
        <div class="pb-7 bg-white p-5 d-none" id="Team">
            <div class="container">
                <div class="row text-center justify-content-center">
                    <div class="col-lg-8 ps-3 mb-5">
                        <h3>How it Works</h3>
                    </div>
                </div>
                <div class="row text-center team">
                    <div class="col-lg-3 col-sm-6 mb-5">
                        <div class="bg-white shadow-sm p-5 team-card">
                            <img src="../../front-end-assets/book-section.png" class="img-fluid mb-3 img-thumbnail how-it-works-img" width="100">
                            <h5 class="mb-1 how-it-works-title">Book a Section</h5>
                            <p class="text">Lorem Ipsum is simply dummy text of the printing and typesetting industry.
                                Lorem Ipsum has been the industry's standard dummy text</p>
                            <a href="#" class="more">More</a>
                        </div>
                    </div>
                    <div class="col-lg-3 col-sm-6 mb-5">
                        <div class="bg-white shadow-sm p-5 team-card"><img src="../../front-end-assets/record-video.png" width="100" class="img-fluid mb-3 img-thumbnail how-it-works-img">
                            <h5 class="mb-1 how-it-works-title">Record your Video</h5>
                            <p class="text">Lorem Ipsum is simply dummy text of the printing and typesetting industry.
                                Lorem Ipsum has been the industry's standard dummy text</p>
                            <a href="#" class="more">More</a>
                        </div>
                    </div>
                    <div class="col-lg-3 col-sm-6 mb-5">
                        <div class="bg-white shadow-sm p-5 team-card">
                            <img src="../../front-end-assets/share-with-people.png" width="100" class="img-fluid mb-3 img-thumbnail how-it-works-img">
                            <h5 class="mb-1 how-it-works-title">Shared with People</h5>
                            <p class="text">Lorem Ipsum is simply dummy text of the printing and typesetting industry.
                                Lorem Ipsum has been the industry's standard dummy text</p>
                            <a href="#" class="more">More</a>
                        </div>
                    </div>
                    <div class="col-lg-3 col-sm-6 mb-5">
                        <div class="bg-white shadow-sm p-5 team-card">
                            <img src="../../front-end-assets/lifestory-memories.png" width="100" class="img-fluid mb-3 img-thumbnail how-it-works-img">
                            <h5 class="mb-1 how-it-works-title">Collective life story</h5>
                            <p class="text">Lorem Ipsum is simply dummy text of the printing and typesetting industry.
                                Lorem Ipsum has been the industry's standard dummy text</p>
                            <a href="#" class="more">More</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>


        <div class="side-app d-none">
            <!-- CONTAINER -->
            <div class="main-container">
                <div class="section bg-white" style="background-color: #eee9da66 !important" id="Clients">
                    <div class="container">
                        <div class="row text-center justify-content-center">
                            <div class="col-lg-8 ps-3 my-5">
                                <h3>Client Testimonials</h3>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-4 col-sm-6 mb-5">
                                <div class="d-flex client-section">
                                    <div class="client-img">
                                        <img src="../../front-end-assets/jane-smith.png" width="100%" class="img-fluid" alt="img missing">
                                    </div>
                                    <div class="client-info">
                                        <p>Lorem Ipsum is simply dummy text of the printing and typesetting
                                            industry.
                                            Lorem Ipsum has been the industry's standard dummy text Lorem Ipsum is
                                            simply dummy text .</p>
                                        <i class="fa-solid fa-star"></i>
                                        <i class="fa-solid fa-star"></i>
                                        <i class="fa-solid fa-star"></i>
                                        <i class="fa-solid fa-star"></i>
                                        <i class="fa-solid fa-star"></i>
                                        <h4 class="cleint-name">Jane Smith</h4>
                                        <p class="designation">Freelance Designer</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-4 col-sm-6 mb-5">
                                <div class="d-flex client-section">
                                    <div class="client-img">
                                        <img src="../../front-end-assets/tom-williams.png" width="100%" class="img-fluid" alt="img missing">
                                    </div>
                                    <div class="client-info">
                                        <p>Lorem Ipsum is simply dummy text of the printing and typesetting
                                            industry.
                                            Lorem Ipsum has been the industry's standard dummy text Lorem Ipsum is
                                            simply dummy text .</p>
                                        <i class="fa-solid fa-star"></i>
                                        <i class="fa-solid fa-star"></i>
                                        <i class="fa-solid fa-star"></i>
                                        <i class="fa-solid fa-star"></i>
                                        <i class="fa-solid fa-star"></i>
                                        <h4 class="cleint-name">Tom Williams</h4>
                                        <p class="designation">Software Developer</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-4 col-sm-6 mb-5">
                                <div class="d-flex client-section">
                                    <div class="client-img">
                                        <img src="../../front-end-assets/michael-brown.png" width="100%" class="img-fluid"  alt="img missing">
                                    </div>
                                    <div class="client-info">
                                        <p>Lorem Ipsum is simply dummy text of the printing and typesetting
                                            industry.
                                            Lorem Ipsum has been the industry's standard dummy text Lorem Ipsum is
                                            simply dummy text .</p>
                                        <i class="fa-solid fa-star"></i>
                                        <i class="fa-solid fa-star"></i>
                                        <i class="fa-solid fa-star"></i>
                                        <i class="fa-solid fa-star"></i>
                                        <i class="fa-solid fa-star"></i>
                                        <h4 class="cleint-name">Michael Brown</h4>
                                        <p class="designation">Online Entrepreneur</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="section customizable pb-6 p-md-5 p-2 pb-3" id="our-services">
            <div class="container">
                <div class="row text-center mt-4 justify-content-center">
                    <div class="col-lg-8">
                        <h3 id="about">About Project Lifestory</h3>
                        <img src="../../front-end-assets/micheal.png" class="owner-image mt-3 mb-5" alt="Michael Profile">
                    </div>
                </div>
                <div class="row d-flex justify-content-center align-items-center mb-4">
                    <div class="col-md-8 col-12 text-center ">
                        <div style="text-align: justify">
                            <p> 
                                My name is Michael Grumley. I’m a professional novelist, former geek, son, and father to two wonderful
                                girls. Interestingly, one of the many things my vocation has taught me to do… is to listen, a lot. Which not too
                                long ago led me to a series of unexpected revelations…
                            </p>
                        </div>
                        <router-link to="schedule-lifestory" class="btn text-white btn-pill btn-create-your-story btn-w-md py-2 me-2">Schedule a Lifestory Recording</router-link>
                    </div>
                </div>
            </div>
        </div>

        <div class="">
            <div class="side-app">
                <div class="main-container">
                    <Footer></Footer>
                </div>
            </div>
        </div>
    </div>
</template>

<script>

    import Sidebar from '../../components/front-end/Sidebar.vue';
    import Footer from '../../components/front-end/Footer.vue';

    export default {
        name: 'Landing',
        components: {
            Sidebar,
            Footer
        },
        created(){
            if (this.$route.query.refresh) {
                this.$router.go(-1);
            }
        }
    }
</script>
