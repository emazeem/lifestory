<template>
    <div class="container-fluid" style="width: 90%;">
        <div class="d-flex dd-flex justify-content-between align-items-center">
            <h1 class="feed-title ct-card-header mt-5 mb-5 d-md-block d-none">Invited Friends and Family</h1>
            <button @click="showFnfForm = !showFnfForm"
                class="btn add-photo-tour d-md-block d-none  add-post-modal-btn btn-primary text-white px-3 py-0 ct-p "
                v-if="!isAdmin && customer.id == userInfo.id" title="Add new" style="min-height: 40px">
                <i :class="(!showFnfForm ? 'ti-plus' : 'ti-minus')" class="ti-plus font-weight-800"></i>
                Add New</button>

        </div>
        <h1 :class="(!isAdmin?'mb-3':'mb-0 mt-0')" class="feed-title ct-card-header mt-1  d-md-none d-block">Invited Friends and Family</h1>
        <div class="col-12 align-items-center text-right">
            <button @click="showFnfForm = !showFnfForm"
            class="btn float-right mb-3 add-photo-tour d-md-none d-block add-post-modal-btn btn-primary text-white px-3 py-0 ct-p "
            v-if="!isAdmin && customer.id == userInfo.id" title="Add new" style="min-height: 40px">
            <i :class="(!showFnfForm ? 'ti-plus' : 'ti-minus')" class="ti-plus font-weight-800"></i>
            Add New</button>
            <br v-if="!isAdmin">
        </div>
        <div class="row mt-md-auto mt-5 ">

            <div class="table-responsive fnf-tour px-2 ">
                <table class="table table-bordered bg-white">
                    <thead>
                        <tr class="ct-p" style="background-color: #D5E9F4 !important;">
                            <th>First Name</th>
                            <th>Last Name</th>
                            <th>Email</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr class="ct-p" v-for="(user, index) in subUsers" :key="index" v-if="subUsers.length > 0">
                            <td class="ct-card-header">{{ user.fname }}</td>
                            <td class="ct-card-header">{{ user.lname }}</td>
                            <td class="ct-card-header">{{ user.email }}</td>
                            <td class="ct-card-header">{{ user.is_active ? 'Account Created' : 'Invited' }}</td>
                        </tr>
                        <tr v-else>
                            <td colspan="100%" class="text-center">No Friends & Family</td>
                        </tr>
                    </tbody>
                </table>
                <div v-if="add_family_errors" class="alert alert-danger alert-dismissible fade show position-relative"
                    role="alert">
                    <ul>
                        <li v-for="(e, index) in add_family_errors" :key="index"> {{ e }} </li>
                    </ul>
                    <p class="position-absolute top-0 right-0 mr-2 cursor-pointer" @click="add_family_errors = ''"
                        aria-hidden="true">
                        &times;
                    </p>
                </div>

                <div class="col-md-12 px-0 table-responsive mt-2" v-show="showFnfForm">
                    <table class="table table-bordered bg-white">
                        <thead>
                            <tr class="ct-p" style="background-color: #D5E9F4 !important;">
                                <th>Name</th>
                                <th>Email</th>
                                <th>Phone <small>(Optional)</small></th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="(row, index) in rows" :key="index">
                                <td>
                                    <div class="row">
                                        <div class="col-md-6 col-12">
                                            <div class="d-flex justify-content-start">
                                                <input type="text" class="form-control form-sm" v-model="row.fname"
                                                    placeholder="First Name">
                                                <span class="text-red"> *</span>
                                            </div>
                                        </div>
                                        <div class="col-md-6 col-12">
                                            <div class="d-flex justify-content-start">
                                                <input type="text" class="form-control form-sm" v-model="row.lname"
                                                    placeholder="Last Name">
                                                <span class="text-red"> *</span>
                                            </div>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <div class="row">
                                        <div class="col-md-6 col-12 ">
                                            <div class="d-flex justify-content-start">
                                                <input type="email" class="form-control form-sm" v-model="row.email"
                                                    placeholder="Email">
                                                <span class="text-red"> *</span>
                                            </div>
                                        </div>
                                        <div class="col-md-6 col-12">
                                            <div class="d-flex justify-content-start">
                                                <input type="email" class="form-control form-sm"
                                                    v-model="row.confirmation_email" placeholder="Confirm Email">
                                                <span class="text-red"> *</span>
                                            </div>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <input type="text" autocomplete="off" class="form-control form-sm" v-model="row.phone"
                                        placeholder="Phone">
                                </td>
                                <td class="d-none">
                                    <select class="form-control form-select ct-form-control" v-model="row.relationship">
                                        <option value="">Select Relationship</option>
                                        <option v-for="item in $relationships()" :key="item" :value="item">{{ item }}
                                        </option>
                                    </select>
                                </td>
                                <td >
                                    <button v-if="index == 0" title="Add new row" class="btn  btn-primary"
                                        @click="addRow()"><i class="fa fa-plus"></i></button>
                                    <button v-else class="btn  btn-danger" @click="removeRow(index)"><i
                                            class="fa fa-minus"></i></button>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                    <button @click="addRelatives()" class="btn add-post-modal-btn btn-primary m-md-auto mb-4 float-right ct-p mx-md-0 mx-3"
                        :disabled="btnDisabled">{{ btnDisabled ? 'Loading...' : 'Submit' }}</button>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    name: 'FriendFamily',
    props: {
        isAdmin: {
            type: Boolean,
            required: false,
            default: false
        },
        subUsers: undefined,
        customer: undefined,
        userInfo: undefined,
        getSubUsers: undefined,
    },
    data() {
        return {
            add_family_errors: '',
            rows: [
                {
                    name: '',
                    email: '',
                    phone: '',
                    relationship: 'Unknown'
                }
            ],
            showFnfForm: false,
            btnDisabled: false,
        }
    },
    methods: {
        async addRelatives() {
            this.add_family_errors = '';
            this.btnDisabled = true;
            await this.axios.post("/user/add-relatives/0", this.rows).then((data) => {
                this.$moshaToast({ title: data.data.message },
                    { type: 'success', showIcon: true, hideProgressBar: true });
                this.rows = [
                    {
                        fname: '',
                        lname: '',
                        email: '',
                        phone: '',
                        relationship: 'Unknown'
                    }
                ];
                this.getSubUsers();
            }).catch((error) => {
                this.add_family_errors = error.response.data.message;
            }).finally(() => {
                this.btnDisabled = false;
            });
        },
        removeRow(index) {
            if (this.rows.length != 1) {
                this.rows.splice(index, 1);
            }
        },
        addRow() {
            this.rows.push({
                fname: '',
                lname: '',
                email: '',
                phone: '',
                relationship: 'Unknown'
            });
            setTimeout(function () { this.$updateFont(); }, 1000);
        },
    }
}
</script>
