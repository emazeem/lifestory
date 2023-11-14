<template>
    <div class="page landing-page-body">
        <div class="page-main">
            <div class="landing-top-header">
                <Sidebar></Sidebar>
            </div>
            <div class="section customizable pb-6 p-5" id="our-services">
                <div class="container">
                    <div class="row d-flex justify-content-center">
                        <div class="col-12" >
                            <div class="card">
                                <div class="card-body p-0">
                                    <div class="container">
                                        <div class="row" v-show="showThisPage">
                                            <div class="col-md-6 d-flex align-items-center justify-content-center border-right" >
                                                <div>
                                                    <center>
                                                        <img src='../../public/icons/card.png' alt="" style="height: 20vh">
                                                    </center>
                                                    <p v-show="amount" class="text-center ct-card-header"><span class="h2">${{amount}}</span><span class="h5" style="color: grey">per month</span></p>

                                                </div>
                                            </div>
                                            <div class="col-md-6 pb-5 mb-5">
                                                <div class="mt-5 d-flex justify-content-between align-items-center p-2 ">
                                                    <h4 class="text-primary ct-card-header">Subscribe Now</h4>
                                                    <div>
                                                        <button class="btn ct-p btn-sm mr-2 " @click="changeOpiton()" :class="(newSubForm?'bg-white border':'btn-primary')+cc" >Select Card</button>
                                                        <button @click="changeOpiton()" class="btn btn-sm ct-p " :class="(newSubForm?'btn-primary':'bg-white border')+cc" >Add Card</button>
                                                    </div>

                                                </div>
                                                <hr class="m-0">
                                                <div class="container" v-if="!newSubForm">
                                                    <div class="row border-bottom p-2 cursor-pointer align-items-center" :class="selectedPaymentMethod==payment_method.id?'bg-light':'bg-white'" v-for="(payment_method,i) in payment_methods" @click="selectPaymentMethod(payment_method.id)">
                                                        <div class="col-md-9 ct-p">
                                                            <img src="../../public/icons/Visa.png" v-if="payment_method.card_type==='Visa'" width="30">
                                                            <img src="../../public/icons/Mastercard.png" v-if="payment_method.card_type==='MasterCard'" width="30">
                                                            <img src="../../public/icons/AmericanExpress.png" v-if="payment_method.card_type==='American Express'" width="30">
                                                            <h6 class="m-0 pr-5 font-weight-normal">{{ payment_method.card_number }}</h6>
                                                        </div>
                                                        <div class="col-md-3 d-flex justify-content-end">
                                                            <p class="m-0 float-end" style="color: grey">{{ payment_method.expiry }}</p>
                                                            <i class="fa fa-check-circle mt-1 ml-3 " :class="selectedPaymentMethod==payment_method.id?'text-success':'text-white'"></i>
                                                        </div>
                                                    </div>
                                                    <div class=" col-md-12 text-center">
                                                        <p v-if="payment_methods.length==0" class="mt-3">No payment method.</p>
                                                    </div>
                                                </div>

                                                <div class="container">
                                                  <form @submit.prevent="addPaymentMethod()">
                                                    <div v-show="newSubForm" class="row py-5">
                                                        <div class="col-12">
                                                            <div id="card-element" ></div>
                                                        </div>
                                                        <div class="col-12 text-right mt-2">
                                                            <button class="btn btn-primary ct-p btn-sm" :disabled="isLoading">{{ isLoading?'Loading...':'Save' }}</button>
                                                        </div>
                                                      </div>
                                                    </form>
                                                </div>



                                                <div class="container" v-show="amount && payment_methods.length>0">
                                                    <div class="row">
                                                        <div class="col-12 mt-5">
                                                            <h4 class="text-primary ct-card-header">Summary</h4>
                                                            <table class="table">
                                                                <tbody class="ct-p">
                                                                <tr>
                                                                    <th>Plan</th>
                                                                    <th>Qty</th>
                                                                    <th>Price <small>per mo</small></th>
                                                                </tr>
                                                                <tr>
                                                                    <td> Subscription of life story</td>
                                                                    <td>1</td>
                                                                    <th class="text-right">{{ amount }}$</th>
                                                                </tr>
                                                                <tr>
                                                                    <td colspan="3" class="text-right">
                                                                        <button class="btn ct-p btn-primary btn-sm" @click="subscribe()" :disabled="isLoadingSubBtn">{{ isLoadingSubBtn?'Loading...':'Subscribe' }}</button>
                                                                    </td>
                                                                </tr>
                                                                </tbody>
                                                            </table>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row" v-show="showThisPage==false">
                                          <div class="col-12 d-flex justify-content-center align-items-center" style="height:60vh">
                                            <div class="text-center mt-5">
                                              <h5>You have already subscribed.</h5>
                                              <router-link class="btn btn-primary text-white mt-4" to="/setting"> Back to Settings</router-link>
                                            </div>
                                          </div>
                                        </div>
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
  import Loader from "../../views/front-end/Loader.vue";
  export default {
    name: "Subscription",
    data() {
      return {
        cc:" "+localStorage.getItem('font_size_class'),
        newSubForm:false,
        isLoading:false,
        isLoadingSubBtn:false,
        amount:'',
        stripe: null,
        card: null,
        currency: '',
        uuid: null,
        paid: false,
        selectedPaymentMethod: '',
        payment_methods:[],
        showThisPage: null,
      }
    },
    components:{
      Sidebar,
      Footer,
      Loader
    },
    mounted()
    {
      const stripeScript = document.createElement("script");
      stripeScript.src = "https://js.stripe.com/v3/";
      document.head.appendChild(stripeScript);

      stripeScript.onload = () => {
        this.initializeStripe();
      };
      this.checkStatus();
      this.fetchAmount();
      this.fetchBanks();
    },
    methods: {
      changeOpiton()
      {
        this.newSubForm=!this.newSubForm;

      },
      async checkStatus()
      {
        this.axios.post("/user/subscription-status").then((response) => {
            if(response.data.data.active==null){
              this.showThisPage = true;
            }else{
              this.showThisPage = false;
            }

          })
      },
      async subscribe()
      {
        if(!this.selectedPaymentMethod)
        {
          this.$moshaToast({title: "Select payment method to continue!",}, {type: 'danger', showIcon: true, hideProgressBar: true,});
        }
        else {
        this.isLoadingSubBtn = true;
        this.axios.post("/user/subscribe",{amount:this.amount,card_id:this.selectedPaymentMethod}).then((response) => {
            this.isLoadingSubBtn = false;
              this.$moshaToast({title: "You're all set! Your subscription is active, and the world of exclusive content awaits you.",}, {type: 'success', showIcon: true, hideProgressBar: true,});
              this.checkStatus();
          })
          .catch(() => {
              this.$moshaToast({title: 'Something went wrong'},
                    {type: 'danger',showIcon: true,hideProgressBar: true});
          });
        }
      },
      async addPaymentMethod()
      {
        const { token, error } = await this.stripe.createToken(this.card, {
          name: this.cardHolderName,
        });

        if (error) {
          console.error(error.message);
        } else {
            this.isLoading = true;
          this.savePaymentMethod(token);
        }
      },
      async savePaymentMethod(token)
      {
          this.axios.post("/user/add-payment-method",token).then((response) => {
              this.$moshaToast({title: "Payment method added successfully!",}, {type: 'success', showIcon: true, hideProgressBar: true,});
              this.initializeStripe();
              this.isLoading = false;
              this.fetchBanks();
          })
          .catch(() => {
            this.$moshaToast({title: 'Something went wrong'},
                    {type: 'danger',showIcon: true,hideProgressBar: true});
          });
      },
      fetchAmount()
      {
        this.axios.get('user/subscription-amount').then((response) => {
              this.amount = response.data.amount;
        })
      },
      fetchBanks()
      {
        var self=this;
        this.axios.post('user/payment-methods').then((response) => {
                    this.payment_methods = response.data.data;
                    this.payment_methods.forEach(function (method){
                       if(method.preferred==1){
                           self.selectedPaymentMethod=method.id;
                       }
                    });
        })
      },
      selectPaymentMethod(id){
        this.selectedPaymentMethod=id;
      },
      initializeStripe()
      {
        this.stripe = Stripe(import.meta.env.VITE_STRIPE_PUBLISH_KEY);
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
      }
    },
  };
  </script>
