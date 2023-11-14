<template>
    <Head></Head>
    <div id="main">
        <Sidebar></Sidebar>
        <div class="main-content">
            <div class="page-header">
                <div class="container-fluid d-sm-flex justify-content-between">
                    <h4 class="font-size-25">Transactions</h4>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb font-size-25">
                            <li class="breadcrumb-item">
                                <router-link to="/dashboard">Dashboard</router-link>
                            </li>
                            <li class="breadcrumb-item active" aria-current="page">
                                Transactions
                            </li>
                        </ol>
                    </nav>
                </div>
            </div>

            <div class="container-fluid font-size-25">
                <div class="card shadow-lg">

                    <div class="card-body">

                        <br>
                        <div class="d-flex justify-content-end">
                            <div class="form-group">
                                <input type="text" placeholder='Search' v-model="search" @input="searchData" class="form-control">
                            </div>
                        </div>
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped table-hover table-sm">
                                <thead class="bg-dark">
                                    <tr class="text-white">
                                        <th>Trx #</th>
                                        <th>Date</th>
                                        <th>Description</th>
                                        <th>Account</th>
                                        <th class="text-center">Amount</th>
                                    </tr>
                                </thead>
                                <tbody v-if="transactions && transactions.length > 0">
                                    <tr v-for="(transaction,index) in transactions" :key="index">
                                        <td>{{ transaction.transaction_id }}</td>
                                        <td style="white-space: nowrap">{{ $date(transaction.created_at) }}</td>
                                        <td>{{ transaction.description }}</td>
                                        <td style="white-space: nowrap">{{ transaction.user.fullName }}</td>
                                        <td class="text-right">+{{ transaction.credit }}</td>
                                    </tr>
                                </tbody>
                                <tbody v-else>
                                    <tr>
                                        <td align="center" colspan="100%    ">No record found.</td>
                                    </tr>
                                </tbody>
                                <tfoot v-if="transactions && transactions.length">
                                    <tr>
                                        <td class="text-right" colspan="100%"><strong>Total</strong> : {{ getTotalAmount(transactions) }}</td>
                                    </tr>
                                </tfoot>
                            </table>
                       </div>
                   </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import Head from "../../../components/admin/Header.vue";
import Sidebar from "../../../components/admin/Sidebar.vue";
import Pagination from "../../../components/admin/Pagination.vue";

export default {
    name: "TransactionList",
    components: {
        Head,
        Sidebar,
        Pagination
    },
    data() {
        return {
            search:'',
            transactions: [],
        };
    },
    methods: {
        getTotalAmount(transactions) {
            if(transactions.length)
            return transactions.reduce((total, transaction) => total + transaction.credit, 0);
            else return 0;
        },
        searchData()
        {
            this.list();
        },
        async list() {
            await this.axios.get(`transactions/fetch-all?search=${this.search}`)
                .then((response) => {
                    this.transactions = response.data.data;
                });
        },
    },
    created() {
        this.list();
    },
};
</script>

<style scoped></style>
