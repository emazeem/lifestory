<template>
    <Header></Header>
    <div id="main">
        <Sidebar></Sidebar>
        <div class="main-content">
            <div class="page-header">
                <div class="container-fluid d-sm-flex justify-content-between">
                    <h4 class="font-size-25">Sessions Details</h4>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb font-size-25">
                            <li class="breadcrumb-item">
                                <router-link to="/dashboard">Dashboard</router-link>
                            </li>
                            <li class="breadcrumb-item active" aria-current="page">Sessions Details</li>
                        </ol>
                    </nav>
                </div>
            </div>

            <div v-show="isLoaded" class="container-fluid font-size-20">
                <div class="row mb-3">

                    <div class="col-md-6">
                        <div style="height: 100%" class="card shadow-lg">
                            <div class="card-header d-flex justify-content-between">
                                <h5 class="font-size-25">Customer Details</h5>
                                <button class="btn btn-primary" @click="changeOption()"><i class="fa fa-edit"></i></button>
                            </div>
                            <div class="card-body">
                                <div v-if="update_errors" class="alert alert-danger alert-dismissible fade show position-relative" role="alert">
                                    <ul>
                                        <li v-for="(e,index) in update_errors" :key="index"> {{ e }} </li>
                                    </ul>
                                    <p class="position-absolute top-0 right-0 mr-2 cursor-pointer" @click="update_errors = ''" aria-hidden="true">
                                        &times;
                                    </p>
                                </div>
                            <div class="table-responsive">
                                <table class="table table-sm table-bordered  table-hover">
                                    <tbody v-if="session.customer && !editUser">
                                        <tr>
                                            <th class="font-weight-bold">Name</th>
                                            <td>{{session.customer.fullName}}</td>
                                        </tr>
                                        <tr>
                                            <th class="font-weight-bold">Email</th>
                                            <td>{{session.customer.email}}</td>

                                        </tr>
                                        <tr>
                                            <th class="font-weight-bold">City</th>
                                            <td>{{session.customer.details.city}}</td>
                                        </tr>
                                        <tr>
                                            <th class="font-weight-bold">State</th>
                                            <td>{{session.customer.details.state}}</td>
                                        </tr>
                                        <tr>
                                            <th class="font-weight-bold">Country</th>
                                            <td>{{session.customer.details.country}}</td>
                                        </tr>
                                        <tr>
                                            <th class="font-weight-bold">Zip Code</th>
                                            <td>{{session.customer.details.zip_code}}</td>
                                        </tr>
                                        <tr>
                                            <th class="font-weight-bold">Date of Birth</th>
                                            <td>{{ $date(session.customer.details.dob)}}</td>
                                        </tr>
                                    </tbody>
                                    <tbody v-show="session.customer && editUser">
                                        <tr>
                                            <th class="font-weight-bold">First Name</th>
                                            <td>
                                                <input id="fname" v-model="session.customer.fname" type="text" placeholder="First Name" class="form-control mb-0 rounded-0">
                                            </td>
                                        </tr>
                                        <tr>
                                            <th class="font-weight-bold">Last Name</th>
                                            <td>
                                                <input id="lname" v-model="session.customer.lname" type="text" placeholder="Last Name" class="form-control mb-0 rounded-0">
                                            </td>
                                        </tr>
                                        <tr>
                                            <th class="font-weight-bold">Email</th>
                                            <td>
                                                <input id="email" readonly v-model="session.customer.email" type="text" placeholder="Email" class="form-control mb-0 rounded-0">
                                            </td>
                                        </tr>
                                        <tr>
                                            <th class="font-weight-bold">Contact</th>
                                            <td>
                                                <input id="contact" v-model="session.customer.contact" type="text" placeholder="Contact " class="form-control mb-0 rounded-0">
                                            </td>
                                        </tr>
                                        <tr>
                                            <th class="font-weight-bold">Country</th>
                                            <td>
                                                <select name="country" @change="fetchStates($event.target.selectedOptions[0].getAttribute('data-shortname'))" id="fetch-state" class="form-control" v-model="session.customer.details.country">
                                                    <option value="''">-- Select Country</option>
                                                    <option v-for="country in countries" :value="country.name" :data-shortname="country.iso2">{{ country.name }}</option>
                                                </select>
                                            </td>
                                        </tr>
                                        <tr>
                                            <th class="font-weight-bold">City</th>
                                            <td>
                                                <input type="text" v-model="session.customer.details.city" class="form-control">
                                            </td>
                                        </tr>
                                        <tr>
                                            <th class="font-weight-bold">State</th>
                                            <td>
                                                <select name="state" id="state" class="form-control" v-model="session.customer.details.state">
                                                    <option value="">-- Select State</option>
                                                    <option v-for="state in states" :data-stateshortname="state.iso2" :value="state.name">{{ state.name }}</option>
                                                </select>
                                            </td>
                                        </tr>
                                        <tr>
                                            <th class="font-weight-bold">Zip code</th>
                                            <td>
                                                <input id="zip_code" v-model="session.customer.details.zip_code" type="text" placeholder="Zip code" class="form-control mb-0 rounded-0">
                                            </td>
                                        </tr>
                                        <tr>
                                            <th class="font-weight-bold">Date of birth</th>
                                            <td>
                                                <input id="dob" v-model="session.customer.details.dob" type="date" class="form-control mb-0 rounded-0">
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                                <button v-if="editUser" @click="updateUser(session.customer)" class="btn btn-primary float-right">Submit</button>
                            </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="card shadow-lg" style="height: 100%">
                            <div class="card-header"><h5 class="font-size-25">Sessions details</h5></div>
                            <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-sm table-bordered table-hover">
                                    <tbody>
                                        <tr>
                                            <th class="font-weight-bold">Start Time</th>
                                            <td class="font-size-18">{{ $formatDateAsAmPm(session.start_time) }} (PST)</td>
                                        </tr>
                                        <tr>
                                            <th class="font-weight-bold">Duration</th>
                                            <td>{{session.duration}} Minutes</td>

                                        </tr>
                                        <tr class="d-none">
                                            <th class="font-weight-bold">Password</th>
                                            <td>{{session.password}}</td>
                                        </tr>
                                        <tr>
                                            <th class="font-weight-bold">Created On</th>
                                            <td>{{ $date(session.created_at) }}</td>
                                        </tr>
                                        <tr>
                                            <th class="font-weight-bold">Zoom Meeting</th>
                                            <td>
                                                <span v-show="session && session.customer && !session.customer.video && session.join_url" class="btn cursor-pointer text-white rounded btn-primary" @click="startSesion(session.join_url)">Start Session <span class="fa fa-external-link ml-1"></span> </span>
                                                <a class="border-0 btn text-white rounded btn-primary ml-1" href="#" v-if="session && session.customer && !session.customer.video && session.join_url" :href="session.join_url" title="Join the meeting" @click="copyLink(session.join_url)"><i class="fa-solid  rounded fa-copy"></i></a>
                                                <a v-if="!(session && session.customer && !session.customer.video && session.join_url) && isLoaded" class="badge badge-success text-light">Completed</a>
                                            </td>
                                        </tr>
                                        <tr>
                                            <th class="font-weight-bold">Payment Link</th>
                                            <td>

                                                <div class="d-flex">
                                                    <button @click="setPayment(1)" data-toggle="modal" data-target="#customerPaymentModal" class="btn text-white rounded btn-primary">
                                                        Buy Video <span class="fa fa-expand ml-1"></span>
                                                    </button>
                                                    <button class="border-0 btn text-white rounded btn-primary ml-1" @click="copyLink(session.link)" ><i class="fa-solid  rounded fa-copy"></i></button>
                                                </div>


                                                <div class="d-flex mt-1">
                                                    <button @click="setPayment(2)" data-toggle="modal" data-target="#customerPaymentModal" class="btn rounded btn-primary text-white" style="white-space: nowrap">
                                                        Buy Video & Hosting <span class="fa fa-expand ml-1"></span>
                                                    </button>
                                                    <button class="border-0 ml-1 btn rounded btn btn-primary text-white " @click="copyLink(session.link + '&hosting=true')"><span class="fa-solid fa-copy"></span></button>

                                                </div>

                                            </td>
                                        </tr>
                                        <tr>
                                            <th class="font-weight-bold">One Time Payment Status</th>
                                            <td><b :class="{'text-success': session.payment, 'text-danger': !session.payment}">{{ session.payment?'Completed':'Pending' }}</b></td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6 float-end">
                        <div class="card shadow-lg">
                            <div class="card-header">
                                <h5 class="font-size-25 d-md-none d-block mt-2"><i class="fa-solid fa-cloud-arrow-up"></i> Upload Meeting Recording here</h5>
                                <div for="file" class="mt-2 d-flex justify-content-between align-items-center">
                                    <h5 class="font-size-25 d-md-block d-none"><i class="fa-solid fa-cloud-arrow-up"></i> Upload Meeting Recording here</h5>
                                    <h5 v-if="session.customer && session.customer.video" @click="changeVideo()" class="badge p-2 cursor-pointer font-size-15 bg-primary mr-1">Replace video</h5>
                                    <h5 v-if="completed" class="badge p-2 font-size-15 bg-success">Completed</h5>
                                </div>
                            </div>
                            <div class="card-body" v-show="session.customer && !session.customer.video || replaceVideo">
                                <div class="col-md-12" >
                                    <div v-if="videoUploadErrors" class="alert alert-danger alert-dismissible fade show position-relative" role="alert">
                                        <ul>
                                            <li v-for="(e,index) in videoUploadErrors" :key="index"> {{ e }} </li>
                                        </ul>
                                        <p class="position-absolute top-0 right-0 mr-2 cursor-pointer" @click="videoUploadErrors = ''" aria-hidden="true">
                                            &times;
                                        </p>
                                    </div>
                                    <div class="form-group">
                                        <div class="div my-2" v-if="videoName"><strong>Video Name: {{ videoName }}</strong></div>

                                        <div class="upload-videos">
                                            <div class="video-dropzone" ref="videodropzone">
                                                <div class="dropzone-display">
                                                    <div class="p-5">
                                                        <img src="../../../public/cloud-computing.svg" />
                                                        <h3>Drop or click here to upload customer video</h3>
                                                    </div>
                                                </div>
                                            </div>

                                            <uploading-video v-for="(file, index) in files" v-bind:key="file.file.uniqueIdentifier + index"
                                                             :file="file.file" :status="file.status" :progress="file.progress" @cancel="cancelFile" />
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-12 text-right">
                                    <button :disabled="videoBtnDisabled" class="btn btn-primary d-none mt-3" @click="submitFile()">Upload</button>
                                </div>
                            </div>
                            <div class="card-body text-center" v-if="session.customer && session.customer.video">
                                <h4 class="text-left">File Name : <span class="text-primary">{{session.customer && session.customer.video.o_file}}</span></h4>
                                <video class="video-tag" v-if="session.customer && session.customer.video" controls :src="session.customer.video.file"></video>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 float-end">
                        <div v-if="session.payment" class="card">
                            <div class="card-header d-flex flex-column justify-content-between">
                                <h5 :class="(session.customer.account_created?'text-success':'text-danger')" class="font-size-25">{{ session.customer.account_created?'Change Password':'Create Password' }}</h5>
                                <h6 v-show="session.customer.account_created">Password is created and saved successfully</h6>
                            </div>
                            <div class="card-body">
                                    <div v-if="password_errors" class="alert alert-danger alert-dismissible fade show position-relative" role="alert">
                                        <ul>
                                            <li v-for="(e,index) in password_errors" :key="index"> {{ e }} </li>
                                        </ul>
                                        <p class="position-absolute top-0 right-0 mr-2 cursor-pointer" @click="password_errors = ''" aria-hidden="true">
                                            &times;
                                        </p>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="form-group">
                                            <label>Password</label>
                                            <input autocomplete="off" :type="passType" class="form-control" v-model="formData.password" :placeholder="(session.customer.account_created?'*********':'Password')">
                                            </div>
                                        </div>
                                        <div class="col-md-12 mt-2">
                                            <div class="form-group">
                                            <label for="">Confirm Password</label>
                                            <input autocomplete="off" :type="passType" class="form-control" v-model="formData.password_confirmation" :placeholder="(session.customer.account_created?'*********':'Confirm Password')">
                                            </div>
                                        </div>
                                        <div class="col-md-12 d-flex justify-content-between mt-3">
                                            <button @click="generatePassword()" class="btn btn-dark">Generate Password</button>
                                            <!-- <button @click="ank()" class="btn btn-dark">{{ passType=='password'?'Show':'Hide'  }}</button> -->
                                            <button class="btn btn-primary" @click="updateCustomer()">Save</button>
                                        </div>
                                    </div>
                               </div>
                        </div>
                        <div v-else class="mb-2">
                            <img class="w-50" src="../../../public/blur_account_create.png" alt="payment required">
                        </div>
                        <div v-if="completed==2" class="card">
                            <div class="card-body">
                                <div class="col-md-12">
                                    <button :disabled="disabledBtn" class="btn btn-primary btn-lg  w-100" @click="createCustomer(session.customer.id)">Create Customer</button>
                                </div>
                            </div>
                        </div>
                        <div v-else-if="completed==1" class="card overflow-scroll">
                            <div class="card-body text-center bg-success">
                                <span class=" rounded" style="white-space: nowrap">Account created successfully!  <i class="fa text-white fa-check"></i></span>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="card shadow-lg">
                            <div class="card-header d-flex justify-content-between">
                                <h5 class="font-size-25">Invited Friends and Family</h5>
                            </div>
                            <div class="card-body">
                                <div v-if="errors" class="alert alert-danger alert-dismissible fade show position-relative" role="alert">
                                    <ul>
                                        <li v-for="(e,index) in errors" :key="index"> {{ e }} </li>
                                    </ul>
                                    <p class="position-absolute top-0 right-0 mr-2 cursor-pointer" @click="errors = ''" aria-hidden="true">
                                        &times;
                                    </p>
                                </div>
                                <div class="table-responsive">
                                    <table class="table table-bordered table-hover">
                                        <thead class="bg-dark ">
                                        <tr class="text-white">
                                            <th>Name</th>
                                            <th>Email</th>
                                            <th>Phone <small>(optional)</small></th>
                                            <th class="d-none">Relationship</th>
                                            <th>Status</th>
                                            <th>Action</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        <tr  v-for="(row, index) in rows" :key="index">
                                            <td >
                                                <div class="row">
                                                    <div class="col-md-6 col-12 ">
                                                        <div class="d-flex justify-content-start">
                                                        <input type="text" class="form-control form-sm " v-model="row.fname" placeholder="First Name">
                                                        <span class="text-red"> *</span>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6 col-12">
                                                        <div class="d-flex justify-content-start">
                                                        <input type="text" class="form-control form-sm" v-model="row.lname" placeholder="Last Name">
                                                        <span class="text-red"> *</span>
                                                        </div>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="row">
                                                    <div class="col-md-6 col-12 ">
                                                        <div class="d-flex justify-content-start">
                                                            <input type="email" class="form-control form-sm" v-model="row.email" placeholder="Email">
                                                        <span class="text-red"> *</span>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6 col-12">
                                                        <div class="d-flex justify-content-start">
                                                        <input type="email" class="form-control form-sm" v-model="row.confirmation_email" placeholder="Confirm Email">
                                                        <span class="text-red"> *</span>
                                                        </div>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <input type="text" autocomplete="off" class="form-control form-sm" v-model="row.phone" placeholder="Phone">
                                            </td>
                                            <td class="d-none">
                                                <select class="form-control form-control-sm form-select" v-model="row.relationship">
                                                    <option value="">Select Relationship</option>
                                                    <option v-for="item in $relationships()" :key="item" :value="item">{{ item }}</option>
                                                </select>
                                            </td>
                                            <td>
                                            </td>
                                            <td class="text-center">
                                                <button v-if="index==0" title="Add new row" class="btn rounded btn-primary" @click="addRow"><i class="fa fa-plus"></i></button>
                                                <button v-else class="btn btn-sm rounded btn-danger" @click="removeRow(index)"><i class="fa fa-minus"></i></button>
                                            </td>
                                        </tr>
                                        <tr v-for="(user,index) in subUsers" :key="index">
                                            <td>{{user.fullName}}</td>
                                            <td>{{user.email}}</td>
                                            <td>{{user.contact}}</td>
                                            <td class="d-none">{{user.pivot.relationship}}</td>
                                            <td>{{user.is_active?'Created':'Invited'}}</td>
                                            <td>
                                                <div class="d-flex justify-content-center">
                                                <button @click="editCustomer(user)" class="btn btn-primary rounded cursor-pointer" title="Edit customer">
                                                    <i class="fa fa-pencil"></i>
                                                </button>
                                                <button @click="deleteCustomer(user.id)" class="btn d-none ml-1 btn-danger rounded cursor-pointer" title="Delete customer">
                                                    <i class="fa fa-trash"></i>
                                                </button>
                                                </div>
                                            </td>
                                        </tr>
                                        </tbody>
                                    </table>
                                    <button @click="addRelatives()" class="btn btn-primary float-right" :disabled="btnDisabled">{{ btnDisabled?'Loading...':'Submit' }}</button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-12">
                        <div class="card shadow-lg">
                            <div class="card-header d-flex justify-content-between">
                                <h5 class="font-size-25">Transactions and subscriptions</h5>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table table-bordered table-hover">
                                        <thead class="bg-dark ">
                                        <tr class="text-white">
                                            <th>Trx #</th>
                                            <th>Date</th>
                                            <th>Description</th>
                                            <th>Amount</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        <tr v-if="transactions && transactions.length>0" v-for="(transaction,index) in transactions" :key="index">
                                            <td>{{ transaction.transaction_id }}</td>
                                            <td style="white-space: nowrap">{{ $date(transaction.created_at)}}</td>
                                            <td style="white-space: nowrap">{{ transaction.description }}</td>
                                            <td>{{ transaction.credit?transaction.credit:transaction.debit}}</td>
                                            <!-- <td class="badge" :class="(transaction.credit?'bg-success':'bg-danger')">{{ transaction.credit?transaction.credit:transaction.debit}}</td> -->
                                        </tr>
                                        <tr v-else>
                                            <td colspan="4" class="text-center">
                                                No record found
                                            </td>
                                        </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
        </div>
    </div>
</div>
<div class="modal fade" id="customerPaymentModal" tabindex="-1" role="dialog" aria-hidden="true" style="z-index: 10000;">
    <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 v-if="paymentOption==1" class="card-title mt-2">Payment for booking session</h4>
                <h4 v-if="paymentOption==2" class="card-title mt-2">Payment for video and hosting</h4>
                <button type="button" class="close" id="modal-hide" data-dismiss="modal" aria-label="Close">
                    <i class="ti-close"></i>
                </button>
            </div>
            <div class="modal-body p-0">
                <div class="card shadow-0 border-0 bg-transparent" style="min-height: 70vh">
                    <div class="card-body" v-if="isLoading" style="height: 70vh;display: flex;justify-content: center;align-items: center">
                        <Loader :show="isLoading"/>
                    </div>
                    <div class="card-body" v-show="!isLoading">
                        <div class="text-center">
                                <img src="../../../public/icons/card.png" alt="" style="height: 20vh">
                        </div>
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
                            <h4 class="text-center" v-if="paid">{{ paymentMessage }}</h4>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="modal font-size-20 fade" id="editSubUserModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Edit the friend and family member</h5>
                <button type="button" class="close" id="modal-hide" data-dismiss="modal" @click="clearModalData()" aria-label="Close">
                    <i class="ti-close"></i>
                </button>
            </div>
            <div class="modal-body">

                <div v-if="editSubUserErrors" class="alert alert-danger alert-dismissible fade show position-relative" role="alert">
                    <ul>
                        <li v-for="e in editSubUserErrors">{{e}}</li>
                    </ul>
                    <p class=" position-absolute top-0 right-0 mr-2 cursor-pointer" @click="editSubUserErrors=''" aria-hidden="true">&times;</p>
                </div>

                <form id="editform" method="post" @submit.prevent="updateSubUser(editSubUserForm)">

                    <div class="form-group row">
                        <label class="col-sm-3 col-form-label">First Name</label>
                        <div class="col-sm-9">
                            <input type="text" v-model="editSubUserForm.fname" class="form-control">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-3 col-form-label">Last Name</label>
                        <div class="col-sm-9">
                            <input type="text" v-model="editSubUserForm.lname" class="form-control">
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-sm-3 col-form-label">Email</label>
                        <div class="col-sm-9">
                            <input type="text" readonly v-model="editSubUserForm.email" class="form-control">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-3 col-form-label">Phone</label>
                        <div class="col-sm-9">
                            <input type="text" v-model="editSubUserForm.contact" class="form-control">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-3"></label>
                        <div class="col-sm-9 text-right">
                            <button :disabled="editSubUserBtn" type="submit" id="btn-save" class="btn btn-primary">{{ editSubUserBtn?'Loading...':'Update' }}</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
</template>

<script>
import Resumable from 'resumablejs'

import Header from "../../../components/admin/Header.vue";
import Sidebar from "../../../components/admin/Sidebar.vue";
import UploadingVideo from "./UploadingVideo.vue";
import Loader from '../../front-end/Loader.vue';

export default {
    name: "SessionView",
    components: {
        Loader,
        Header,
        Sidebar,
        UploadingVideo,
    },
    data() {
        return {
            replaceVideo: false,
            isLoaded:false,
            paymentOption:0,
            files: [], // our local files array, we will pack in extra data to force reactivity
            r: false,
            transactions:[],
            videoBtnDisabled:false,
            disabledBtn:false,
            completed:'',
            videoName:'',
            passType:'text',
            user_id:'',
            formData:{
                password:'',
                password_confirmation:'',
            },
            session: {
                customer:{
                    fname:'',
                    lname:'',
                    email:'',
                    contact:'',
                    details:{
                        city:'',
                        state:'',
                        zip:'',
                        country:'',
                        dob:''
                    }
                }
            },
            subUsers:[],
            editSubUserForm:{},
            editSubUserErrors:'',
            editSubUserBtn:false,
            id:'',
            file: "",
            uploadPercentage: 0,
            errors:'',
            videoUploadErrors:'',
            password_errors:'',
            rows: [
                {
                    fname: '',
                    lname: '',
                    email: '',
                    confirmation_email: '',
                    phone: '',
                    relationship: ''
                }
            ],
            btnDisabled:false,
            editUser:false,
            countries:[],
            states:[],
            update_errors:'',
            stripe_key:'',
            paid: false,
            amount:'',
            currency:'',
            stripe: null,
            hosting:false,
            isLoading:true,
            payBtnLoading:false,
            paymentMessage: '',
        }
    },
    methods: {
        changeVideo()
        {
            this.replaceVideo=!this.replaceVideo;
            // this.files = [];
            this.file = "";
        },
        updateSubUser(user)
        {
            this.editSubUserBtn = true;
            this.editSubUserErrors = '';
            this.axios.post("user/fnf-update", user).then(() => {
                this.getSession();
                $("#editSubUserModal").modal('hide');
                this.$moshaToast({title: "Friends and family member updated successfully"},{type: 'success',showIcon: true,hideProgressBar: true});
                }).catch((error) => {
                    this.editSubUserErrors = this.$flattenErrors(error);
                }).finally(() => {
                    this.editSubUserBtn = false;
                });
        },
        clearModalData()
        {
            this.editSubUserForm = {};
            $("#editSubUserModal").modal('hide');
        },
        editCustomer(user)
        {
            this.editSubUserForm = user;
            $('#editSubUserModal').modal('show');
        },
        setPayment(option)
        {
            if(option==1 && this.session.payment)
            {
                this.paymentMessage = 'One time payment is already done';
                this.paid = true;
                this.isLoading = false;
                return;
            }
            else if(option==2 && this.session.subscribed)
            {
                this.paymentMessage = 'One time payment and subscription is already done';
                this.paid = true;
                this.isLoading = false;
                return;
            }
            else this.paid = false;

            this.initializeStripe();

            if(option==1)
            {
            this.paymentOption = 1;
            this.hosting = 0;
            this.fetchAmount();
            }
            if(option==2)
            {
                this.paymentOption = 2;
                this.hosting = 1;
                this.fetchAmount();
            }

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
        fetchAmount()
        {
            this.axios.post("payment/initiate",{hosting:this.hosting}).then((response) => {
                this.amount = response.data.amount;
                this.currency = response.data.currency;
            });
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
            meeting_id: this.session.meeting_id,
            amount: this.amount,
            currency: this.currency,
            type:this.hosting? 'video-and-hosting' : 'video'
            };
            this.axios.post("/payment/token",data).then(() => {
                document.getElementById('modal-hide').click();
                this.getSession();
                this.$moshaToast({title: "Payment successful"},{type: 'success',showIcon: true,hideProgressBar: true});
            }).finally(() => {
            this.payBtnLoading = false;
            });
        },
        deleteCustomer(id)
        {
            swal({
                    title: 'Are you sure to delete friends and family member?',
                    text: 'It will be permanently deleted!',
                    icon: "warning",
                    buttons: {
                    cancel: {
                        text: 'Cancel',
                        value: null,
                        visible: true,
                        className: 'swal-button--cancel bg-danger',
                        closeModal: true,
                    },
                    confirm: {
                        text: 'Yes, delete it!',
                        value: true,
                        visible: true,
                        className: 'swal-button--confirm',
                        closeModal: true,
                    },
                },
                    })
                    .then((result) => {
                    if (result) {
                        this.axios.post('user/delete-fnf-member', { 'id': id,'customer_id':this.user_id }).then(() => {
                            this.$moshaToast({ title: 'Friend and family member deleted successfully' }, { type: 'success', showIcon: true, hideProgressBar:true });
                            this.getSession();
                        }).catch(()=>{
                            this.$moshaToast({ title: 'Something went wrong' }, { type: 'danger', showIcon: true, hideProgressBar:true });
                        });
                    }
                });
        },
        startSesion(link)
        {
            window.open(link,'_blank');
        },
        findFile(file)
        {
            return this.files.find(item => item.file.uniqueIdentifier === file.uniqueIdentifier && item.status !== 'canceled') ?? {}
        },
        // cancel an individual file
        cancelFile(file) {
            this.findFile(file).status = 'canceled'
            file.cancel()
        },
        getTransactions(id)
        {
            this.axios.get(`transactions/user/${id}`).then((response) => {
                this.transactions = response.data;
            });
        },
        changeOption()
        {
            this.editUser=!this.editUser;
            if(this.editUser)
            {
                const selectElement  = document.getElementById("fetch-state");
                const selectedOption = selectElement.selectedOptions[0];
                const country        = selectedOption.getAttribute('data-shortname');
                this.fetchStates(country);
            }
        },
        updateUser(customer)
        {
            this.update_errors = '';
            this.axios.post("/user/update-user", customer).then((data) => {
                this.$moshaToast({title:data.data.message,}, {type: 'success', showIcon: true, hideProgressBar: true});
                    this.getUser();
                    this.editUser=false;
                })
                .catch((error) => {
                    this.update_errors = this.$flattenErrors(error);
                });
        },
        fetchCountries(){
            let config = {
            method: 'get',
            url: 'https://api.countrystatecity.in/v1/countries',
            headers: {
                'X-CSCAPI-KEY': import.meta.env.VITE_COUNTRY_API_KEY,
            }
            };

            this.axios(config).then((response) => {
                this.countries = response.data;
            });
        },
        fetchStates(country)
        {
            let config = {
            method: 'get',
            url: 'https://api.countrystatecity.in/v1/countries/'+country+'/states',
            headers: {
                'X-CSCAPI-KEY': import.meta.env.VITE_COUNTRY_API_KEY,
            }
            };

            this.axios(config).then((response) => {
                this.states = response.data.sort((a, b) => a.name.localeCompare(b.name));
            });
        },
        ank()
        {
            if(this.passType=='password') this.passType = 'text';
            else this.passType = 'password';
        },
        generatePassword()
        {
            let result = "";
            let characters = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789!@#$%^&*()_+-=[]{}|;':\"<>,.?/\\";
            let charactersLength = characters.length;
            for (let i = 0; i < 20; i++) {
                result += characters.charAt(Math.floor(Math.random() * charactersLength));
            }
            this.formData.password              = result;
            this.formData.password_confirmation = result;
            this.copyLink(result,'Password generated and copied to clipboard');
        },
        updateCustomer()
        {
            this.password_errors='';
            this.axios.post("/user/update-customer", {...this.formData,user_id:this.user_id}).then((data) => {
                this.$moshaToast({title:data.data.message,}, {type: 'success', showIcon: true, hideProgressBar: true,});
                    this.formData = [
                        {
                            password: '',
                            password_confirmation: '',
                        }
                    ];
                    this.getSession();
                })
                .catch((error) => {
                    this.password_errors = this.$flattenErrors(error);
                });
        },
        createCustomer(id)
        {
            this.disabledBtn = true;
            this.axios.post("user/create-customer-account", {id:id}).then(() => {
            this.$moshaToast({title:'Customer account created successfully and an email sent to the customer',}, {type: 'success', showIcon: true, hideProgressBar: true,});
                this.getSession();
            });
            this.disabledBtn = false;
        },
        copyLink(link,message='') {
            navigator.clipboard.writeText(link)
                .then(() => {
                    this.$moshaToast({
                    title: message==''?'Payment link copied successfully':message,
                    },
                    {
                    type: 'success',
                    showIcon: true,
                    hideProgressBar: true,
                    });
                });
        },
        async addRelatives()
        {
            this.btnDisabled = true;
            this.errors = '';
            await this.axios.post("/user/add-relatives/"+this.user_id, this.rows).then((data) => {
                this.$moshaToast({title:data.data.message},
                    {type: 'success',showIcon: true,hideProgressBar: true});
                    this.rows = [
                        {
                            fname: '',
                            lname: '',
                            email: '',
                            phone: '',
                            relationship: ''
                        }
                    ];
                    this.getSession();
                })
                .catch((error) => {
                    this.errors = error.response.data.message;
                }).finally(()=>{
                    this.btnDisabled = false;
                });
        },
        addRow() {
            this.rows.push({
                fname: '',
                lname: '',
                email: '',
                confirmation_email: '',
                phone: '',
                relationship: ''
            });
        },
        removeRow(index) {
            if (this.rows.length!=1){
                this.rows.splice(index, 1);
            }
        },
        async getSession() {

            await this.axios.post('sessions/fetch',{'id':this.id}).then((res) => {
                this.session   = res.data.session;
                this.subUsers  = res.data.subusers;
                this.completed = res.data.completed;
                this.user_id   = this.session.user_id;
                this.getTransactions(this.user_id);
            }).catch(() => {
                this.$moshaToast({title: 'Something went wrong with the session'},{type: 'danger',showIcon: true,hideProgressBar: true});
            }).finally(() => {
                this.isLoaded = true;
            });
        },
        handleFileUpload() {
            this.file = this.$refs.file.files[0];

            if (this.file) {
            this.videoName = this.file.name;
            }
        },
        submitFile() {
            this.videoBtnDisabled = true;
            this.errors = '';
            let formData = new FormData();
            formData.append("file", this.file);
            formData.append("session_id", this.id);

            this.axios.post("sessions/upload-video", formData, {
                headers: {
                    "Content-Type": "multipart/form-data",
                },
                onUploadProgress: (progressEvent) => {
                    this.uploadPercentage = parseInt(Math.round((progressEvent.loaded / progressEvent.total) * 100));
                }}).then(() => {
                    this.$moshaToast({title: 'Video Uploaded Successfully'},{type: 'success',showIcon: true,hideProgressBar: true});
                        this.getSession();
                }).catch((error) => {
                    this.videoUploadErrors = this.$flattenErrors(error);
                }).finally(()=>{this.videoBtnDisabled = true;});
        },
    },
    mounted()
    {

        const stripeScript = document.createElement("script");
        stripeScript.src = "https://js.stripe.com/v3/";
        document.head.appendChild(stripeScript);


      // init resumablejs on mount
      this.r = new Resumable({
        target: '/api/sessions/upload-customer-video',
        headers: {
          'Authorization': 'Bearer ' + localStorage.getItem('sanctum_token') // Replace with your Sanctum token
        },
        query: { session_id:this.$route.params.id },
        maxChunkRetries: 1,
        testChunks: false,
        chunkSize: 5 * 1024 * 1024, // 5 MB in bytes
        maxFiles: 1, // Allow only one file to be selected
      });

      // Resumable.js isn't supported, fall back on a different method
      if (!this.r.support) return alert('Your browser doesn\'t support chunked uploads. Get a better browser.');

      this.r.assignBrowse(this.$refs.videodropzone);
      this.r.assignDrop(this.$refs.videodropzone);

      // set up event listeners to feed into vues reactivity
      this.r.on('fileAdded', (file, event) => {
        file.hasUploaded = false;

        if (file.file.type !== 'video/mp4') {
          this.$moshaToast({ title: 'Choose a valid video file e.g mp4'},
                    {type: 'danger',showIcon: true,hideProgressBar: true});
          this.cancelFile(file); // Optionally cancel the upload
          return;
        }

        // keep a list of files with some extra data that we can use as props
        this.files.push({
          file,
          status: 'uploading',
          progress: 0
        })
        this.r.upload()
      })
      this.r.on('fileSuccess', (file, event) => {
        this.findFile(file).status = 'success';
        this.getSession();
      })
      this.r.on('fileError', (file, event) => {
        this.findFile(file).status = 'error'
      })
      this.r.on('fileRetry', (file, event) => {
        this.findFile(file).status = 'retrying'
      })
      this.r.on('fileProgress', (file) => {
        var localFile = this.findFile(file)
        // if we are doing multiple chunks we may get a lower progress number if one chunk response comes back early
        var progress = file.progress()
        if (progress > localFile.progress)
          localFile.progress = progress
      })
    },
    created() {
        this.id=this.$route.params.id;
        this.getSession();
        this.fetchCountries();
    }
};
</script>

<style scoped>
.input-with-progress {
    position: relative;
}

.input-with-progress input[type="file"] {
    width: 100%;
}

.input-with-progress progress {
    position: absolute;
    left: 0;
    top: 50px;
    width: 100%;
}
.video-tag{
    width: 100%;
    max-width: 600px !important;
}
@media (max-width: 400px) {
    .video-tag{
        width: 100%;
    }
}
  .p-5 {
    padding: 3rem;
  }

  .upload-videos {
    display: flex;
    flex-direction: column;
  }

  .video-dropzone {
    height: 340px;
    width: 340px;
    padding: 16px;
    display: flex;
    align-self: center;
    margin-bottom: 1.2rem;
    cursor: pointer;

    * {
      pointer-events: none;
    }

    .dropzone-display {
      height: 100%;
      width: 100%;
      display: flex;
      align-items: center;
      justify-content: center;
      flex-direction: column;
      border: 4px dashed #222;
      border-radius: 32px;

      img {
        width: 64px;
      }

      small {
        font-size: 0.65em;
        display: block;
      }
    }
  }

</style>

