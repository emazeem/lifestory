<template>
    <div class="d-flex align-items-center justify-content-center vh-100">
            <div class="text-center">
                <h1 class="display-1 fw-bold">404</h1>
                <p class="fs-3"> <span class="text-danger">Opps!</span> Something went wrong.</p>
                <p class="lead">
                    There was some error so your request can not be completed.
                  </p>
                <router-link to="/" class="btn text-white btn-primary">Go Home</router-link>
            </div>
        </div>
</template>

<script>

export default {
    name: 'Error',
}

</script>
