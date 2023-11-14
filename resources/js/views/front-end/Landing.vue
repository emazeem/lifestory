<template>
    <div class="page landing-page-body landing-page-font">
        <div class="page-main">
            <div class="landing-top-header">
                <Sidebar />
                <div style="background-size: contain" class="main-banner" id="home">
                    <div class="container mt-5">
                        <div class="row">
                            <div class="col-lg-12 text-center mt-lg-5">
                                <h1 class="text-shadow main-banner-text">It’s been said that when a person dies, the
                                    world loses a library.
                                </h1>
                                <h1 class="text-shadow main-banner-text mt-5">Not Anymore.</h1>
                                
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="section pb-6 py-md-3 p-2 mt-5" id="our-services">
                <div class="container">
                    <div class="row text-center justify-content-center">
                        <div class="col-lg-8 mb-4 our-mission-header mt-3">
                            <h3 id="our-mission">OUR MISSION:</h3>
                            <h5>Save as many libraries as possible.</h5>
                        </div>
                    </div>
                    <div class="row">

                        <div class="col-md-6">
                            <div class="text-left mt-4 landing-page-paragraphs">
                                <p>We live on one amazing planet where life is not just precious but unimaginably unique.
                                    And yet, the one thing we all have in common is that we are on the same path. Every one
                                    of us. Some further along than others, but ultimately, we all reach the same final
                                    destination. Eventually, we all must leave this amazing experience called Life. But
                                    ‘absent’ does not have to mean ‘forgotten’.</p>

                                <p>The sad truth is that once we leave, most of us will only be remembered for a generation
                                    or two, with our unique lives lost forever to both history and our families. To numerous
                                    generations who will one day wonder not just where they came from, but from <i>whom</i>.
                                </p>

                                <p>Enter Project Lifestory.</p>

                                <p>Never before in history have we possessed the ability to exist beyond our own lives, far
                                    beyond. Along with the ability to share who we are and what we’ve done with future
                                    generations. The lives we’ve led, what we have learned, and the critical lessons that
                                    should be passed on for the betterment of future generations. Life stories told in our
                                    own words, in our own voices, and through our own personalities. High-resolution life
                                    story videos that can be saved forever in perpetuity. For our families, our friends, and
                                    everyone who comes after us. Because the most important thing we can do for our future…
                                    is not to let our pasts disappear with us. To save as many of our <i>libraries</i> as we
                                    can.</p>
                            </div>
                            <router-link to="/schedule-lifestory"
                                class="btn text-white btn-create-your-story btn-w-md py-2">Schedule Your Lifestory Interview</router-link>
                        </div>
                        <div class="col-md-6">
                            <!-- <img class="img-fluid our-mission-img" src="../../front-end-assets/screenshot192.png" alt=""> -->
                            <img class="img-fluid our-mission-img" src="../../front-end-assets/new-mission-z.png" alt="">
                        </div>
                    </div>
                </div>
            </div>

            <div class="section pb-6 py-md-3 mt-5 p-2 how-can-i-do-img-shadow" id="how-can-i-do-section">
                <div class="full-size-div">
                    <div class="row align-items-center">
                        <div class="col-md-6 sm-second px-0">
                            <img class="img-fluid how-can-i-do-img" src="../../front-end-assets/black-family-new.jpg" alt="">
                        </div>
                        <div class="col-lg-4 col-md-6 sm-fisrt">
                            <div class="how-can-i-do-header text-center mt-3">
                                <h3>How Can I Do It?</h3>
                            </div>
                            <div class="text-left landing-page-paragraphs">
                                <p>Let Project Lifestory help you preserve your own library, your story, through an easy
                                    one-hour conversation – right from your own phone or computer – producing a high-quality
                                    video of your life story. To keep forever as part of your family’s permanent genealogy.
                                    A personal history of the wholly unique life you have lived and experienced during your
                                    time here on good ‘ole planet Earth.</p>
                                <br>
                                <p>Just click on the button below and schedule your own life story recording. Through a
                                    surprisingly simple and enjoyable process right from your own phone or computer. And
                                    perhaps one of the most important things you can give your family. Trust us, you’ll be
                                    glad you did.</p>
                            </div>
                            <router-link to="/schedule-lifestory"
                                class="btn text-white btn-create-your-story btn-w-md py-2">Schedule Your Lifestory Interview</router-link>
                        </div>
                    </div>
                </div>
            </div>

            <div class="section pb-6 py-md-3 mt-5 p-2" id="book-a-session">
                <div class="container">
                    <h1 class="number-heading">1</h1>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="book-session-header">
                                <h3>Book A Session</h3>
                            </div>

                            <div class="text-left landing-page-paragraphs" style="font-family: Britannic">
                                <p class="d-none">Immortalize your life story with Project Lifestory, preserving memories for generations.
                                    Book a session now.</p>
                                <br>

                                <ol class="list-dots">
                                    <li>Click on the "Schedule Your Life Story" button and complete the form with your
                                        personal information.</li>
                                    <li>Choose a date from the 'Calendar' to view our available time slots.</li>
                                    <li>You’re all done! Scheduling your interview is that simple.</li>
                                </ol>
                            </div>
                        </div>
                        <div class="col-md-6 pr-0">
                            <img class="img-fluid book-session-img" src="../../front-end-assets/new-session.png" alt="">
                        </div>
                    </div>
                </div>
            </div>

            <div class="section pb-6 mt-5 py-md-3 p-2" id="record-your-lifestory">
                <div class="container">
                    <div class="row book-session-shadow">
                        <div class="col-md-6 sm-fourth">
                            <img class="img-fluid mt-5 pt-5 record-story-img" src="../../front-end-assets/new.png" alt="">
                        </div>
                        <div class="col-lg- col-md-6 sm-third">
                            <div class="record-story-header">
                                <h1 class="number-heading">2</h1>
                                <h3>Record Your Lifestory Video With Us</h3>
                            </div>
                            <div class="text-left landing-page-paragraphs">
                                <p class="d-none">Preserve your unique stories and messages through personalized videos and narratives with
                                    Project Lifestory, ensuring your legacy lives on for generations.</p>
                                <br>

                                <ol class="list-dots">
                                    <li>We'll record the session and upload the video for you.</li>
                                    <li>You can then access your account and view your video.</li>
                                    <li>Download as many copies as you like!</li>
                                </ol>
                            </div>
                            
                        </div>
                    </div>
                </div>
            </div>

            <div class="section pb-6 py-md-3 mt-5 p-2" id="share-with-family-and-freinds">
                <div class="full-size-div">
                    <div class="row align-items-centers share-with-family-shadow">
                        <div class="left-margins d-lg-block d-md-block d-none"></div>
                        <div class="col-lg-4 col-md-6 pl-md-0">
                            <h1 class="number-heading">3</h1>
                            <div class="book-session-header">
                                <h3>Share With Family & Friends</h3>
                            </div>
                            <div class="text-left landing-page-paragraphs">
                                <p class="d-none">Discover Project Lifestory, your gateway to preserving your life's narrative for
                                    generations to come. Our intuitive platform enables you to share your journey
                                    effortlessly with loved ones and future family members, ensuring your legacy endures
                                    through time.</p>
                                <br>

                                <ol class="list-dots">
                                    <li>When your life story is created, you can share with your family and friends.</li>
                                    <li>Recipients will receive a simple invitation email to view your story.</li>
                                </ol>
                            </div>
                            <router-link to="schedule-lifestory"
                                class="btn text-white btn-create-your-story btn-w-md py-2">Schedule Your Lifestory Interview</router-link>
                        </div>
                        <div class="col-md-4 col-lg-6 pr-0">
                            <img class="img-fluid fnf-img" src="../../front-end-assets/fnf-img.png" alt="">
                        </div>
                    </div>
                </div>
            </div>

           
            <div class="section pb-6 py-md-3 p-2 mb-5 mt-5" id="client-testimonials">
                <div class="container-fluid">
                    <div class="row align-items-center">
                        <div class="col-md-12">
                            <div class="record-story-header mt-3 ">
                                <h3 class="text-center">Client Testimonials</h3>
                            </div>
                        </div>
                    </div>
                    <div class="row justify-content-center pb-5 mb-5">
                        <div class="col-md-8 pb-5">
                            <div id="carouselExampleIndicators" class="carousel slide " data-ride="carousel">
                                <ol class="carousel-indicators">
                                    <li data-target="#carouselExampleIndicators" data-slide-to="0"
                                        class="testinomials-item">
                                        <div class="d-flex flex-column">
                                            <img class="testimonial-single-image" src="../../front-end-assets/greg-s.png"
                                                alt="">
                                            <div class="testimonial-data">
                                                <div class="d-flex flex-column">
                                                    <h2 class="testimonial-single-name">&nbsp;&nbsp;&nbsp;Greg S</h2>
                                                    <div class="testimonial-single-rating">
                                                        <i v-for="star in 5" :key="star"
                                                            :style="{ left: `${100 + (star - 1) * 25}px` }"
                                                            style="position: absolute; top: 30px;"
                                                            class="fa-solid fa-star"></i>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </li>
                                    <li data-target="#carouselExampleIndicators" data-slide-to="1"
                                        class="testinomials-item active">
                                        <div class="d-flex flex-column">
                                            <img class="testimonial-single-image" src="../../front-end-assets/connie-g.jpeg"
                                                alt="">
                                            <div class="testimonial-data">
                                                <div class="d-flex flex-column justify-content-start">
                                                    <h2 class="testimonial-single-name">&nbsp;Connie G</h2>
                                                    <div class="testimonial-single-rating">
                                                        <i v-for="star in 5" :key="star"
                                                            :style="{ left: `${100 + (star - 1) * 23}px` }"
                                                            style="position: absolute; top: 30px;"
                                                            class="fa-solid fa-star"></i>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </li>
                                    <li data-target="#carouselExampleIndicators" data-slide-to="2"
                                        class="testinomials-item">
                                        <div class="d-flex flex-column">
                                            <img class="testimonial-single-image" src="../../front-end-assets/kathie-a.png"
                                                alt="">
                                            <div class="testimonial-data">
                                                <div class="d-flex flex-column">
                                                    <h2 class="testimonial-single-name">&nbsp;&nbsp;Kathy A</h2>
                                                    <div class="testimonial-single-rating">
                                                        <i v-for="star in 5" :key="star"
                                                            :style="{ left: `${100 + (star - 1) * 23}px` }"
                                                            style="position: absolute; top: 30px;"
                                                            class="fa-solid fa-star"></i>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </li>
                                </ol>
                                <div class="carousel-inner testimonial-carousel-inner">
                                    <div class="carousel-item carousel-item-desing  ">
                                        <div class="row justify-content-center">
                                            <div class="col-md-10 position-relative pt-2 ">
                                                <img src="../../public/icons/inverted.png" width="50" alt=""
                                                    class="position-absolute img-fluid"
                                                    style=";z-index: 1000;left: 30px;top:0px">
                                                <div class="card bg-light shadow testimonial-card border-0 mt-2 pt-4 pb-2">
                                                    <div class="card-body px-5 testimonial-card-body">
                                                        <p class="text-dark custom-font-family">“What a great experience. I did this at the request of one of my kids and they were all thrilled to get a copy.”</p>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                    </div>
                                    <div class="carousel-item carousel-item-desing  active">
                                        <div class="row justify-content-center">
                                            <div class="col-md-10 position-relative pt-2 ">
                                                <img src="../../public/icons/inverted.png" width="50" alt=""
                                                    class="position-absolute img-fluid"
                                                    style=";z-index: 1000;left: 30px;top:0px">
                                                <div class="card bg-light shadow testimonial-card border-0 mt-2 pt-4 pb-2">
                                                    <div class="card-body px-5 testimonial-card-body">
                                                        <p class="text-dark custom-font-family">“I’m so glad I did this! And what fun experience. The time absolutely flew by, and my kids will have this forever.”</p>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                    </div>
                                    <div class="carousel-item carousel-item-desing  ">
                                        <div class="row justify-content-center">
                                            <div class="col-md-10 position-relative pt-2 ">
                                                <img src="../../public/icons/inverted.png" width="50" alt=""
                                                    class="position-absolute img-fluid"
                                                    style=";z-index: 1000;left: 30px;top:0px">
                                                <div class="card bg-light shadow testimonial-card border-0 mt-2 pt-4 pb-2">
                                                    <div class="card-body px-5 testimonial-card-body">
                                                        <p class="text-dark custom-font-family">“I asked my father to do this to capture his life and service history. He had a great time and now I have his lifestory forever!”</p>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                    </div>

                                </div>
                                <a class="carousel-control-prev position-absolute langing-page-indicators btn-shadow"
                                    style="top: 25%" href="#carouselExampleIndicators" role="button" data-slide="prev">
                                    <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                                    <span class="sr-only">Previous</span>
                                </a>
                                <a class="carousel-control-next position-absolute langing-page-indicators btn-shadow"
                                    style="top: 25%" href="#carouselExampleIndicators" role="button" data-slide="next">
                                    <span class="carousel-control-next-icon" aria-hidden="true"></span>
                                    <span class="sr-only">Next</span>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="section pb-6 py-md-3 p-2 mt-5 mb-5" id="about">
                <div class="container">
                    <div class="row">
                        <div class="col-md-4 sticky">
                            <img class="img-fluid owener-img d-none d-lg-block d-md-block "
                                src="../../front-end-assets/michael-latest.png" alt="">
                        </div>
                        <div class="col-md-8 text-center">
                            <div class="col-md-12">
                                <div class="lifestory-history-header mt-3 mb-4 ">
                                    <h3>The History of Project Lifestory</h3>
                                </div>
                                <div class="container d-md-none d-lg-none d-block my-0">
                                    <img class="img-fluid owener-img " src="../../front-end-assets/michael-mobile.png"
                                        alt="">
                                </div>
                                <div class="landing-page-paragraphs">
                                    <p style="text-align: left">My name is Michael Grumley. I’m a professional novelist,
                                        former geek, son, and father to two wonderful girls. Interestingly, one of the many
                                        things my vocation has taught me to do… is to listen, a lot. Which not too long ago
                                        led me to a series of unexpected revelations…</p>
                                    <span v-show="show_more_text" class="read-more-text">
                                        <p>The first was during a conversation with my elderly mother, with whom I have
                                            shared many deep discussions over the years. One particular and relatively
                                            recent conversation revolved around life, death, and the meaning of it all when
                                            my mother said something simple but very profound. She said, “You know, Mike.
                                            The one thing that all old people have in common is the fear of being
                                            forgotten.”</p>
                                        <p>While that comment might not seem profound to everyone, it hit me like a board
                                            over the head, and it stayed with me for several weeks until the second
                                            revelation occurred. This time, while having lunch with a good friend. He and I
                                            were discussing family and ancestry when my friend also said something profound.
                                            “It’s funny,” he told me, “as children, we tend to think we know a lot about our
                                            parent’s history, but we really don’t.”</p>
                                        <p>Another simple comment that made me stop and think. And one that took me back to
                                            when I lost my father many years before, and shortly before his death when my
                                            brother, sisters, and I paid him a surprise visit in Southern California. While
                                            preparing to leave, I just happened to grab my old camcorder on the way out of
                                            my house, and when we arrived several hours later, I set it up in the corner of
                                            his room and turned it on.</p>
                                        <p>What happened next would ultimately prove to be an astonishing surprise. We all
                                            had a great visit and talked for hours. We told jokes, sang old songs, and
                                            recounted stories, all while laughing and eating together. And then, about
                                            halfway through our visit, I turned and said, “Dad, tell us about your life.
                                            Where you were raised. Who your friends were. What it was like growing up with
                                            Uncle Chuck and Aunt Ruth, and when you met mom…”</p>
                                        <p>And he did. For the next hour while I occasionally asked questions. And I have to
                                            say, it was fascinating. Revealing so many things and so many surprising
                                            details, we children were completely unaware of.</p>
                                        <p>We didn’t know that my dad would abruptly pass away less than a month later.</p>
                                        <p>Of course, devastated. And as you might guess, that recording of our visit became
                                            invaluable. Not only during our grieving process but because it captured
                                            something even more unique than I expected. You see, It wasn’t just the visit…
                                            it was the way the visit was recorded. Meaning that it wasn’t staged and it
                                            wasn’t rehearsed. It was more natural. And comfortable. Because it was simply a
                                            conversation with my dad.</p>
                                        <p>And because it felt like a conversation, it felt real. So real that even when I
                                            watch the recording all these years later, <u>it actually feels like my father
                                                is still alive</u>. Because the style of the recordings makes it feel as if
                                            the conversations just happened yesterday. Bizarre, I know, but it’s true. And
                                            this was my third revelation.</p>
                                        <p>Upon losing my father, I suddenly realized there was so much history still locked
                                            up in my mother’s memories. In her life story. And I was not about to lose any
                                            of it! So out came the camcorder again, where she and I had much longer and much
                                            more detailed conversations. And once I saw how important this was, it was
                                            followed a short time later by the recording of my wife’s grandfather and
                                            grandmother not long before we lost them.</p>
                                        <p>These successive revelations helped me realize that we all have life stories
                                            inside of us. Invaluable testaments to who we are and what we’ve done. And we
                                            owe it to ourselves and our families to preserve them. It’s been said when a
                                            person dies, the world loses a library. But we don’t have to. Not in this day
                                            and age. Not anymore.</p>
                                        <p>By preserving these life stories, these libraries, imagine what we can pass on.
                                            The things we’ve seen. The experiences we’ve had. The lessons we’ve learned.
                                            We’ve all been through so much, and each individual story is uniquely ours and
                                            ours alone. One that will never be repeated in the same way again. Never to be
                                            lived or viewed from the same vantage point ever again. Human libraries are
                                            being lost forever as we speak.</p>
                                        <p>But we can change that. We can literally change ancestry in a way that was never
                                            possible before. Imagine if you and I could go back dozens of generations and
                                            learn all about our own ancestors. Not just who they were but what they were
                                            like. Not just names but who they really were. What they experienced. What they
                                            felt. Their struggles, their achievements, their personalities. Their voices.
                                            Their laughs. What their smiles looked like. But alas, we cannot. The technology
                                            did not exist back then. But it does now. We may not be able to have it, but we
                                            can give that benefit to our descendants. To our great-grandchildren. Even our
                                            great-great-great-grandchildren and beyond, before it’s too late.</p>
                                        <p>So here I am. On a mission. A quest to advance the idea of ‘human ancestry’ as we
                                            know it. Into something deeper and much more profound. Not just for us but all
                                            our family members that will follow. <u>And I would love it if you would join
                                                me</u>.</p>
                                        <p>Recall in the beginning when I said I was a former geek. It’s true; I spent
                                            thirty years of my career in the technology industry, leaving me uniquely
                                            positioned to do something cool here. And that’s what I have done.</p>
                                        <p>As part of my mission, I have spent the last year creating a very cool and simple
                                            system to make capturing a person’s life story as easy as possible. Recording
                                            over a Zoom call with yours truly (me) from the convenience of a person’s phone
                                            or computer. A remote and fun conversation covering your own life, which can
                                            then be saved and easily distributed to you and your family members.</p>
                                        <p>I’m a novelist, and truth be told, I’m pretty good at getting interesting stories
                                            out of people. So, if you think this is as cool a mission as I do, please click
                                            the button on this page and schedule your life story recording. I’ll show you
                                            how fun and easy it can be.</p>
                                        <h4 cl>Michael</h4>
                                    </span>
                                </div>
                                <a class="btn text-white btn-create-your-story btn-w-md py-2 px-5 cursor-pointer"
                                    @click="show_more_text = !show_more_text">{{ show_more_text ? 'Show less' : 'Read more'
                                    }}</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>


        </div>

        <div class="side-app">
            <div class="main-container">
                <Footer />
            </div>
        </div>
    </div>
</template>
<style scoped>

@media (max-width: 768px) {
    .carousel-control-prev {
        margin-left: 0px !important;
    }
    .carousel-control-next {
        margin-right: 0px !important;
    }
}

.landing-top-header {
    background: linear-gradient(90deg, rgb(0 0 0 / 8%) 0%,
            rgba(224, 224, 224, 0.23862044817927175) 35%, rgba(255, 255, 255, 0) 100%);
}

li.testinomials-item.active {
    position: relative;
}

li.testinomials-item.active::before {
    content: "";
    width: 0;
    height: 0;
    border-left: 15px solid transparent;
    border-right: 15px solid transparent;
    border-bottom: 20px solid #faf8f9 !important;
    position: relative;
    /* Use position: relative to adjust the pseudo-element's position */
    top: -69px;
    /* Adjust the value to move it up */
    left: 60%;
    transform: rotate(180deg);

}




.carousel-control-prev-icon,
.carousel-control-next-icon {
    color: red;
}

a.carousel-control-prev,
a.carousel-control-next {
    width: 50px;
    height: 50px;
    background-color: whitesmoke;
    border-radius: 50%;
    border: 1px solid #cecccc;
}

.carousel-control-prev-icon,
.carousel-control-next-icon {
    filter: invert(100%);
    fill: black;
}

.carousel-indicators li {
    width: 100px;
    height: 100px;
    display: flex;
    justify-content: center;
    align-items: center !important;
    ;
}

.carousel-indicators {
    bottom: -125px;
}

.carousel-indicators li.active img {
    width: 100px;
    height: 100px;
    display: block;
}

.carousel-indicators li img {
    width: 60px;
    height: 60px;
    display: block;
    border: 3px solid #a7c7e7;
    border-radius: 50%;
}

@media (max-width: 767px) {

    /* Define your small screen breakpoint here */
    .sm-first {
        order: 2 !important;
    }

    .sm-second {
        order: 1 !important;
    }

    .sm-third {
        order: 3 !important;
    }

    .sm-fourth {
        order: 4 !important;
    }

    .sm-fifth {
        order: 5 !important;
    }

}

/*.sticky{
    position: -webkit-sticky;
    position: sticky;top: 0;  background-color: yellow;
}*/
</style>
<script>

import Sidebar from '../../components/front-end/Sidebar.vue';
import Footer from '../../components/front-end/Footer.vue';

export default {
    name: 'Landing',
    components: {
        Sidebar,
        Footer
    },
    data() {
        return {
            show_more_text: false,
        }
    },
    created() {
        if (this.$route.query.refresh) {
            this.$router.go(-1);
        }
    }
}
</script>
