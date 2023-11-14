<template>
    <form @submit.prevent="handleSubmit()">
    <div class="col-md-12">
      <div id="card-element"></div>
    </div>
    <div v-show="formReady" class="col-md-12 mt-4 text-right">
        <button
        class="btn btn-primary px-4 btn-sm"
        type="submit"
        :disabled="isLoading">
        {{ isLoading ? 'Adding...' : 'Add' }}
      </button>
    </div>
  </form>
</template>

<script>
export default {
    name: "AddPaymentMethod",
    data() {
      return {
        formReady:false,
        stripe: null,
        isLoading: false,
      }
    },
    props:[
        'refreshBanks',
    ],
    methods: {
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
        this.formReady = true;
      },
      async handleSubmit()
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
              this.refreshBanks();
          })
          .catch(() => {
            this.$moshaToast({ title: 'Something went wrong', }, { type: 'danger', showIcon: true, hideProgressBar: true, });
          });
      }
    },
    mounted()
    {
      const stripeScript = document.createElement("script");
      stripeScript.src = "https://js.stripe.com/v3/";
      document.head.appendChild(stripeScript);

      stripeScript.onload = () => {
        this.initializeStripe();
      };
    },
}
</script>
