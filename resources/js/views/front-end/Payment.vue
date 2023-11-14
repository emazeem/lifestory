<template>
  <div class="page landing-page-body">
      <div class="page-main">
          <div class="landing-top-header">
              <Sidebar></Sidebar>
          </div>
          <div class="section customizable pb-6 p-md-5 p-0" id="our-services">
              <div class="container">
                  <div class="row text-center justify-content-center">
                      <div class="col-lg-8">
                          <div class="card mt-3 shadow-lg bg-white mb-100" style="min-height: 70vh">
                              <div class="card-header bg-light">
                                  <h4 v-if="hosting" class="card-title mt-2">Payment for video and hosting</h4>
                                  <h4 v-else class="card-title mt-2">Payment for booking session</h4>
                              </div>
                              <div class="card-body" v-if="isLoading" style="height: 70vh;display: flex;justify-content: center;align-items: center">
                                  <Loader :show="isLoading"></Loader>
                              </div>
                              <div class="card-body" v-show="!isLoading">
                                  <center>
                                          <img src='../../public/icons/card.png' alt="" style="height: 20vh">
                                  </center>
                                  <div class="col-md-8 mt-5 mx-auto">
                                    <form v-show="!paid" @submit.prevent="handleSubmit()">
                                      <div class="col-md-12">
                                        <div id="card-element" ></div>

                                      </div>
                                      <div class="col-md-12 mt-3">
                                        <div class="form-group">
                                          <label for="price" class="float-left mt-2 mb-0">Amount</label>
                                          <input type="text" id="price" :value="amount+' '+currency" class="form-control" disabled readonly>
                                        </div>
                                      </div>
                                      <div class="col-md-12 mt-4 text-right">
                                        <button v-if="!isLoading" class="btn btn-success px-5" type="submit" :disabled="payBtnLoading">{{ payBtnLoading?'Loading...' : 'Pay' }}</button>
                                      </div>
                                    </form>
                                      <h4 v-if="paid">Payment already done</h4>
                                  </div>
                              </div>
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
import Loader from "./Loader.vue";
export default {
  name: "Payment",
  data() {
    return {
      hosting:false,
      payBtnLoading: false,
      isLoading:true,
      amount:'',
      stripe: null,
      card: null,
      currency: '',
      meeting_id: null,
      paid: false,
      stripe_key:'',
    }
  },
  components:{
    Sidebar,
    Footer,
    Loader
  },
  mounted()
  {

    if(this.$route.query.hosting==='true') this.hosting = this.$route.query.hosting;

    const stripeScript = document.createElement("script");
    stripeScript.src = "https://js.stripe.com/v3/";
    document.head.appendChild(stripeScript);

    stripeScript.onload = () => {
      this.initializeStripe();
    };
    this.fetchAmount();
  },
  created(){
    this.meeting_id = this.$route.query.meeting_id;
    this.checkPayment();
  },
  methods: {
    checkPayment()
    {
      this.axios.post("/payment/check-payment",{meeting_id:this.meeting_id}).then((response) => {
            if(response.data.success)
            this.paid = response.data.session.payment;
            else this.$router.push("/error");
        });
    },
    initializeStripe()
    {
      this.stripe_key = import.meta.env.VITE_STRIPE_PUBLISH_KEY;
      this.stripe = Stripe(this.stripe_key);
      const elements = this.stripe.elements();

      const style = {
        base: {
          color: "#32325d",
          fontFamily: '"Helvetica Neue", Helvetica, sans-serif',
          fontSmoothing: "antialiased",
          fontSize: "16px",
          "::placeholder": {
            color: "#aab7c4",
          },
        },
        invalid: {
          color: "#fa755a",
          iconColor: "#fa755a",
        },
      };

      this.card = elements.create("card", { style });
      this.card.mount("#card-element");
      this.isLoading = false;
    },
    async handleSubmit()
    {
      this.payBtnLoading = true;
      const { token, error } = await this.stripe.createToken(this.card, {
        name: this.cardHolderName,
      });

      if (error) {
        this.$moshaToast({title: error.message},{type: 'danger',showIcon: true,hideProgressBar: true});
        this.payBtnLoading = false;
      } else {
        this.sendToken(token);
      }
    },
    async sendToken(token)
    {
        const data = {
          ...token,
          meeting_id: this.meeting_id,
          amount: this.amount,
          currency: this.currency,
          type:this.hosting? 'video-and-hosting' : 'video'
        };
        this.axios.post("/payment/token",data).then((response) => {
          if(response.data.success) window.open("/thankyou");
        }).finally(() => {
          this.payBtnLoading = false;
        });
    },
    fetchAmount()
    {
      this.axios.post("/payment/initiate",{hosting:this.hosting}).then((response) => {
            this.amount = response.data.amount;
            this.currency = response.data.currency;
        });
    }
  },
};
</script>
