<template>
    <div class="d-flex justify-content-center align-items-center vh-100">
        <div class="text-center">
            <svg xmlns="http://www.w3.org/2000/svg" class="text-success bi bi-credit-card2-front-fill"
                width="75"
                height="75"
                fill="currentColor"
                viewBox="0 0 16 16">
                <path d="M15 3H1.999C.897 3 0 3.897 0 4.999v6.002C0 12.103.897 13 1.999 13H15c1.103 0 2-.897 2-1.999V4.999C17 3.897 16.103 3 15 3zm0 1a1 1 0 0 1 1 1v2H0V5a1 1 0 0 1 1-1h14zM1.999 12a.999.999 0 0 1-1-1V8h14v3a.999.999 0 0 1-1 1H1.999z"/>
                <path d="M1 6h14v2H1V6zm14-4H1a1 1 0 0 0-1 1v1h16V3a1 1 0 0 0-1-1z"/>
            </svg>
            <h3 class="mt-3">Subscribe to life story with your card</h3>
            <stripe-checkout ref="checkoutRef" mode="subscription"
                :pk="publishableKey"
                :line-items="lineItems"
                :success-url="successURL"
                :cancel-url="cancelURL"
                :customer-email="email"
                @loading="(v) => (loading = v)"/>
            <button @click="submit" class="btn btn-primary mt-3" :disabled="loading">
                {{ loading ? "Processing..." : "Subscribe" }}
            </button>
        </div>
    </div>
</template>

<script>
import { StripeCheckout } from "@vue-stripe/vue-stripe";
export default {
    name: "Subscription",
    components: {
        StripeCheckout,
    },
    data() {
        return {
            publishableKey:import.meta.env.VITE_STRIPE_PUBLISH_KEY,
            loading: false,
            email: '',
            lineItems: [
                {
                    price: "",
                    quantity: 1,
                },
            ],
            successURL: "",
            cancelURL: "",
        };
    },
    methods: {
        submit() {
            this.$refs.checkoutRef.redirectToCheckout();

        },
        async fetchDetails() {
            await this.axios.get("payment/stripe-redirect-urls").then((data) => {
                    this.successURL      = data.data.stripe.success_url;
                    this.cancelURL       = data.data.stripe.cancel_url;
                    this.email       = data.data.stripe.auth_email;
                    this.lineItems[0].price = data.data.stripe.price_id;
                })
                .catch((response) => {
                    console.error(response);
                });
        }
    },
    created() {
        this.fetchDetails();
    },
};
</script>
