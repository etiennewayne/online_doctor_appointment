<template>
    <div>
        <div class="section">
            <div class="columns is-centered">
                <div class="column is-10">
                    <div class="w-panel-card">
                        <div class="w-panel-heading">
                            <div class="mb-2" style="font-size: 20px; font-weight: bold;">LIST OF USER</div>
                        </div>

                        <div class="w-panel-body">

                            <div class="level">
                                <div class="level-left">
                                    
                                </div>
    
                                <div class="level-right">
                                    <div class="level-item">
                                        <b-field label="Search">
                                            <b-input type="text"
                                                    v-model="search.lname" placeholder="Search Lastname"
                                                    @keyup.native.enter="loadAsyncData"/>
                                            <p class="control">
                                                <b-tooltip label="Search" type="is-success">
                                                    <b-button type="is-primary" icon-right="account-filter" @click="loadAsyncData"/>
                                                </b-tooltip>
                                            </p>
                                        </b-field>
                                    </div>
                                </div>
                            </div>
    
                            <b-table class="admin-tables"
                                :data="data"
                                :loading="loading"
                                paginated
                                backend-pagination
                                pagination-rounded
                                :total="total"
                                :per-page="perPage"
                                @page-change="onPageChange"
                                aria-next-label="Next page"
                                aria-previous-label="Previous page"
                                aria-page-label="Page"
                                aria-current-label="Current page"
                                backend-sorting
                                :default-sort-direction="defaultSortDirection"
                                @sort="onSort">
    
                                <b-table-column field="user_id" label="ID" v-slot="props">
                                    {{ props.row.user_id }}
                                </b-table-column>
    
                                <b-table-column field="username" label="USERNAME" v-slot="props">
                                    {{ props.row.username }}
                                </b-table-column>
    
                                <b-table-column field="name" label="NAME" v-slot="props">
                                    {{ props.row.lname }}, {{ props.row.fname }} {{ props.row.mname }}
                                </b-table-column>
    
                                <b-table-column field="sex" label="SEX" v-slot="props">
                                    {{ props.row.sex }}
                                </b-table-column>
    
                                <b-table-column field="role" label="ROLE" v-slot="props">
                                    {{ props.row.role }}
                                </b-table-column>

                                <b-table-column field="contact_no" label="Contact No." v-slot="props">
                                    {{ props.row.contact_no }}
                                </b-table-column>

                                <b-table-column field="is_active" label="OTP" v-slot="props">
                                    <span v-if="props.row.is_activate">YES</span>
                                    <span v-else>NO</span>
                                </b-table-column>

                                <b-table-column field="otp_activate" label="OTP" v-slot="props">
                                    {{ props.row.otp_activate }}
                                </b-table-column>
    
                                <b-table-column label="Action" v-slot="props">
                                    <div class="is-flex">
                                        <b-tooltip label="Edit" type="is-warning">
                                            <b-button class="button is-small is-warning is-outlined mr-1" tag="a" icon-right="pencil" @click="getData(props.row.user_id)"></b-button>
                                        </b-tooltip>
                                        <b-tooltip label="Delete" type="is-danger">
                                            <b-button class="button is-small is-danger mr-1 is-outlined" icon-right="delete" @click="confirmDelete(props.row.user_id)"></b-button>
                                        </b-tooltip>
                                        
                                        <b-tooltip label="Options" type="is-info">
                                            <b-dropdown aria-role="list">
                                                <template #trigger="{ active }">
                                                    <b-button
                                                        label=""
                                                        type="is-primary"
                                                        class="is-outliend is-small"
                                                        :icon-right="active ? 'menu-up' : 'menu-down'" />
                                                </template>

                                                <b-dropdown-item @click="openModalResetPassword(props.row.user_id)"
                                                    aria-role="listitem">Reset Password</b-dropdown-item>

                                                <b-dropdown-item 
                                                    @click="activateAccount(props.row.user_id)" 
                                                    aria-role="listitem">Activate</b-dropdown-item>
                                                <b-dropdown-item 
                                                    @click="deactivateAccount(props.row.user_id)" 
                                                    aria-role="listitem">Deactivate</b-dropdown-item>
                                                
                                            </b-dropdown>
                                            
                                        </b-tooltip>


                                    </div>



                                </b-table-column>
    
                                <div class="is-flex mb-4">
                                    <b-field label="Page" label-position="on-border">
                                        <b-select v-model="perPage" @input="setPerPage">
                                            <option value="10">10 per page</option>
                                            <option value="20">20 per page</option>
                                            <option value="30">30 per page</option>
                                        </b-select>
                                        <b-select v-model="sortOrder" @input="loadAsyncData">
                                            <option value="asc">ASC</option>
                                            <option value="desc">DESC</option>
    
                                        </b-select>
                                    </b-field>
                                </div>
                                <div class="buttons mt-3">
                                    <b-button @click="openModal" 
                                        icon-left="plus" class="is-success">
                                        ADD USER
                                    </b-button>
                                </div>
                            </b-table>
    
                            
                        </div> <!--panel body-->

                    </div>
                </div><!--col -->
            </div><!-- cols -->
        </div><!--section div-->



        <!--modal create-->
        <b-modal v-model="isModalCreate" has-modal-card
                 trap-focus
                 :width="640"
                 aria-role="dialog"
                 aria-label="Modal"
                 aria-modal>

            <form @submit.prevent="submit">
                <div class="modal-card">
                    <header class="modal-card-head">
                        <p class="modal-card-title">User Information</p>
                        <button
                            type="button"
                            class="delete"
                            @click="isModalCreate = false"/>
                    </header>

                    <section class="modal-card-body">
                        <div class="">
                            <div class="columns">
                                <div class="column">
                                    <b-field label="Username" label-position="on-border"
                                             :type="this.errors.username ? 'is-danger':''"
                                             :message="this.errors.username ? this.errors.username[0] : ''">
                                        <b-input v-model="fields.username"
                                                 placeholder="Username" required>
                                        </b-input>
                                    </b-field>
                                </div>
                            </div>

                            <div class="columns">
                                <div class="column">
                                    <b-field label="Last Name" label-position="on-border"
                                             :type="this.errors.lname ? 'is-danger':''"
                                             :message="this.errors.lname ? this.errors.lname[0] : ''">
                                        <b-input v-model="fields.lname"
                                                 placeholder="Last Name" required>
                                        </b-input>
                                    </b-field>
                                </div>
                                <div class="column">
                                    <b-field label="First Name" label-position="on-border"
                                             :type="this.errors.fname ? 'is-danger':''"
                                             :message="this.errors.fname ? this.errors.fname[0] : ''">
                                        <b-input v-model="fields.fname"
                                                 placeholder="First Name" required>
                                        </b-input>
                                    </b-field>
                                </div>
                            </div>

                            <div class="columns">
                                <div class="column">
                                    <b-field label="Middle Name" label-position="on-border"
                                             :type="this.errors.mname ? 'is-danger':''"
                                             :message="this.errors.mname ? this.errors.mname[0] : ''">
                                        <b-input v-model="fields.mname"
                                                 placeholder="Middle Name">
                                        </b-input>
                                    </b-field>
                                </div>
                                <div class="column">
                                    <b-field label="Extension" label-position="on-border"
                                             :type="this.errors.extension ? 'is-danger':''"
                                             :message="this.errors.extension ? this.errors.extension[0] : ''">
                                        <b-input v-model="fields.extension"
                                                 placeholder="Extension">
                                        </b-input>
                                    </b-field>
                                </div>
                            </div>

                            <div class="columns">
                                <div class="column">
                                    <b-field label="Sex" label-position="on-border" expanded
                                             :type="this.errors.sex ? 'is-danger':''"
                                             :message="this.errors.sex ? this.errors.sex[0] : ''"
                                            >
                                        <b-select v-model="fields.sex" expanded>
                                            <option value="MALE">MALE</option>
                                            <option value="FEMALE">FEMALE</option>
                                        </b-select>
                                    </b-field>
                                </div>
                                <div class="column">
                                    <b-field label="Contact No" label-position="on-border"
                                             :type="this.errors.contact_no ? 'is-danger':''"
                                             :message="this.errors.contact_no ? this.errors.contact_no[0] : ''">
                                        <b-input type="number" v-model="fields.contact_no"
                                                 placeholder="Contact No" required>
                                        </b-input>
                                    </b-field>
                                </div>
                            </div>
                            <div class="columns" v-if="global_id < 1">
                                <div class="column">
                                    <b-field label="Password" label-position="on-border"
                                             :type="this.errors.password ? 'is-danger':''"
                                             :message="this.errors.password ? this.errors.password[0] : ''">
                                        <b-input type="password" password-reveal v-model="fields.password"
                                                 placeholder="Password" required>
                                        </b-input>
                                    </b-field>
                                </div>
                                <div class="column">
                                    <b-field label="Confirm Password" label-position="on-border"
                                             :type="this.errors.password_confirmation ? 'is-danger':''"
                                             :message="this.errors.password_confirmation ? this.errors.password_confirmation[0] : ''">
                                        <b-input type="password" v-model="fields.password_confirmation"
                                                 placeholder="Confirm Password" required>
                                        </b-input>
                                    </b-field>
                                </div>
                            </div>


                            <div class="columns">
                                <div class="column">
                                    <b-field label="Role" label-position="on-border" expanded
                                             :type="this.errors.role ? 'is-danger':''"
                                             :message="this.errors.role ? this.errors.role[0] : ''">
                                        <b-select v-model="fields.role" expanded>
                                            <option value="ADMINISTRATOR">ADMINISTRATOR</option>
                                            <option value="STAFF">STAFF</option>
                                            <option value="USER">USER</option>
                                        </b-select>
                                    </b-field>
                                </div>

                            </div>


                            <div class="columns">
                                <div class="column">
                                    <b-field label="Province" label-position="on-border" expanded
                                             :type="this.errors.province ? 'is-danger':''"
                                             :message="this.errors.province ? this.errors.province[0] : ''">
                                        <b-select v-model="fields.province" @input="loadCity" expanded>
                                            <option v-for="(item, index) in provinces" :key="index" :value="item.provCode">{{ item.provDesc }}</option>
                                        </b-select>
                                    </b-field>
                                </div>

                                <div class="column">
                                    <b-field label="City" label-position="on-border" expanded
                                             :type="this.errors.city ? 'is-danger':''"
                                             :message="this.errors.city ? this.errors.city[0] : ''">
                                        <b-select v-model="fields.city" @input="loadBarangay" expanded>
                                            <option v-for="(item, index) in cities" :key="index" :value="item.citymunCode">{{ item.citymunDesc }}</option>
                                        </b-select>
                                    </b-field>
                                </div>
                            </div>

                            <div class="columns">
                                <div class="column">
                                    <b-field label="Barangay" label-position="on-border" expanded
                                             :type="this.errors.barangay ? 'is-danger':''"
                                             :message="this.errors.barangay ? this.errors.barangay[0] : ''">
                                        <b-select v-model="fields.barangay" expanded>
                                            <option v-for="(item, index) in barangays" :key="index" :value="item.brgyCode">{{ item.brgyDesc }}</option>
                                        </b-select>
                                    </b-field>
                                </div>
                                <div class="column">
                                    <b-field label="Street" label-position="on-border">
                                        <b-input v-model="fields.street"
                                                 placeholder="Street">
                                        </b-input>
                                    </b-field>
                                </div>
                            </div>
                        </div>
                    </section>
                    <footer class="modal-card-foot">
                        <b-button
                            label="Close"
                            @click="isModalCreate=false"/>
                        <button
                            :class="btnClass"
                            label="Save"
                            type="is-success">SAVE</button>
                    </footer>
                </div>
            </form><!--close form-->
        </b-modal>
        <!--close modal-->




        <!--modal reset password-->
        <b-modal v-model="modalResetPassword" has-modal-card
                 trap-focus
                 :width="640"
                 aria-role="dialog"
                 aria-label="Modal"
                 aria-modal>

            <form @submit.prevent="resetPassword">
                <div class="modal-card">
                    <header class="modal-card-head">
                        <p class="modal-card-title">Change Password</p>
                        <button
                            type="button"
                            class="delete"
                            @click="modalResetPassword = false"/>
                    </header>

                    <section class="modal-card-body">
                        <div class="">
                            <div class="columns">
                                <div class="column">
                                    <b-field label="Password" label-position="on-border"
                                             :type="this.errors.password ? 'is-danger':''"
                                             :message="this.errors.password ? this.errors.password[0] : ''">
                                        <b-input type="password" v-model="fields.password" password-reveal
                                                 placeholder="Password" required>
                                        </b-input>
                                    </b-field>
                                    <b-field label="Confirm Password" label-position="on-border"
                                             :type="this.errors.password_confirmation ? 'is-danger':''"
                                             :message="this.errors.password_confirmation ? this.errors.password_confirmation[0] : ''">
                                        <b-input type="password" v-model="fields.password_confirmation"
                                                 password-reveal
                                                 placeholder="Confirm Password" required>
                                        </b-input>
                                    </b-field>
                                </div>
                            </div>
                        </div>
                    </section>
                    <footer class="modal-card-foot">
                        <button
                            :class="btnClass"
                            label="Save"
                            type="is-success">SAVE</button>
                    </footer>
                </div>
            </form><!--close form-->
        </b-modal>
        <!--close modal-->


    </div>
</template>

<script>

export default{
    data() {
        return{
            data: [],
            total: 0,
            loading: false,
            sortField: 'user_id',
            sortOrder: 'desc',
            page: 1,
            perPage: 10,
            defaultSortDirection: 'asc',


            global_id : 0,

            modalResetPassword: false,


            search: {
                lname: '',
            },

            isModalCreate: false,

            fields: {
                username: '',
                lname: '', fname: '', mname: '',
                password: '', password_confirmation : '',
                sex : '', role: '', office: '', email : '', contact_no : '',
                province: '', city: '', barangay: '', street: ''
            },

            errors: {},
            offices: [],

            btnClass: {
                'is-success': true,
                'button': true,
                'is-loading':false,
            },

            provinces: [],
            cities: [],
            barangays: [],
        }

    },

    methods: {
        /*
        * Load async data
        */
        loadAsyncData() {
            const params = [
                `sort_by=${this.sortField}.${this.sortOrder}`,
                `lname=${this.search.lname}`,
                `perpage=${this.perPage}`,
                `page=${this.page}`
            ].join('&')

            this.loading = true
            axios.get(`/get-users?${params}`)
                .then(({ data }) => {
                    this.data = [];
                    let currentTotal = data.total
                    if (data.total / this.perPage > 1000) {
                        currentTotal = this.perPage * 1000
                    }

                    this.total = currentTotal
                    data.data.forEach((item) => {
                        //item.release_date = item.release_date ? item.release_date.replace(/-/g, '/') : null
                        this.data.push(item)
                    })
                    this.loading = false
                })
                .catch((error) => {
                    this.data = []
                    this.total = 0
                    this.loading = false
                    throw error
                })
        },
        /*
        * Handle page-change event
        */
        onPageChange(page) {
            this.page = page
            this.loadAsyncData()
        },

        onSort(field, order) {
            this.sortField = field
            this.sortOrder = order
            this.loadAsyncData()
        },

        setPerPage(){
            this.loadAsyncData()
        },

        openModal(){
            this.isModalCreate=true;
            this.fields = {};
            this.errors = {};
        },

        loadProvince: function(){
            axios.get('/load-provinces').then(res=>{
                this.provinces = res.data;
            })
        },

        loadCity: function(){
            axios.get('/load-cities?prov=' + this.fields.province).then(res=>{
                this.cities = res.data;
            })
        },

        loadBarangay: function(){
            axios.get('/load-barangays?prov=' + this.fields.province + '&city_code='+this.fields.city).then(res=>{
                this.barangays = res.data;
            })
        },


        submit: function(){
            if(this.global_id > 0){
                //update
                axios.put('/users/'+this.global_id, this.fields).then(res=>{
                    if(res.data.status === 'updated'){
                        this.$buefy.dialog.alert({
                            title: 'UPDATED!',
                            message: 'Successfully updated.',
                            type: 'is-success',
                            onConfirm: () => {
                                this.loadAsyncData();
                                this.clearFields();
                                this.global_id = 0;
                                this.isModalCreate = false;
                            }
                        })
                    }
                }).catch(err=>{
                    if(err.response.status === 422){
                        this.errors = err.response.data.errors;
                    }
                })
            }else{
                //INSERT HERE
                axios.post('/users', this.fields).then(res=>{
                    if(res.data.status === 'saved'){
                        this.$buefy.dialog.alert({
                            title: 'SAVED!',
                            message: 'Successfully saved.',
                            type: 'is-success',
                            confirmText: 'OK',
                            onConfirm: () => {
                                this.isModalCreate = false;
                                this.loadAsyncData();
                                this.clearFields();
                                this.global_id = 0;
                            }
                        })
                    }
                }).catch(err=>{
                    if(err.response.status === 422){
                        this.errors = err.response.data.errors;
                    }
                });


            }
        },


        //alert box ask for deletion
        confirmDelete(delete_id) {
            this.$buefy.dialog.confirm({
                title: 'DELETE!',
                type: 'is-danger',
                message: 'Are you sure you want to delete this data?',
                cancelText: 'Cancel',
                confirmText: 'Delete user account?',
                onConfirm: () => this.deleteSubmit(delete_id)
            });
        },
        //execute delete after confirming
        deleteSubmit(delete_id) {
            axios.delete('/users/' + delete_id).then(res => {
                this.loadAsyncData();
            }).catch(err => {
                if (err.response.status === 422) {
                    this.errors = err.response.data.errors;
                }
            });
        },

        clearFields(){
            this.fields.username = '';
            this.fields.lname = '';
            this.fields.fname = '';
            this.fields.mname = '';
            this.fields.suffix = '';
            this.fields.sex = '';
            this.fields.password = '';
            this.fields.password_confirmation = '';
            this.fields.role = '';

            this.fields.province = '';
            this.fields.city = '';
            this.fields.barangay = '';
            this.fields.street = '';
        },


        //update code here
        getData: function(data_id){
            this.clearFields();
            this.global_id = data_id;
            this.isModalCreate = true;


            //nested axios for getting the address 1 by 1 or request by request
            axios.get('/users/'+data_id).then(res=>{
                this.fields = res.data;
                this.fields.office = res.data.office_id;
                let tempData = res.data;
                //load city first
                axios.get('/load-cities?prov=' + this.fields.province).then(res=>{
                    //load barangay
                    this.cities = res.data;
                    axios.get('/load-barangays?prov=' + this.fields.province + '&city_code='+this.fields.city).then(res=>{
                        this.barangays = res.data;
                        this.fields = tempData;
                    });
                });
            });
        },

        openModalResetPassword(dataId){
            this.modalResetPassword = true;
            this.fields = {};
            this.errors = {};
            this.global_id = dataId;
        },

        resetPassword(){
            axios.post('/reset-password/' + this.global_id, this.fields).then(res=>{

                if(res.data.status === 'changed'){
                    this.$buefy.dialog.alert({
                        title: 'PASSWORD CHANGED',
                        type: 'is-success',
                        message: 'Password changed successfully.',
                        confirmText: 'OK',
                        onConfirm: () => {
                            this.modalResetPassword = false;
                            this.fields = {};
                            this.errors = {};
                            this.loadAsyncData()
                        }
                    });
                }

            }).catch(err=>{
                this.errors = err.response.data.errors;
            })
        },

        activateAccount(userId){
            axios.post('/user-activate-account/' + userId).then(res => {
                if(res.data.status === 'activate'){
                    this.$buefy.dialog.alert({
                        title: 'ACTIVATED!',
                        type: 'is-success',
                        message: 'Account successfully activated.',
                        confirmText: 'OK',
                        onConfirm: () => this.loadAsyncData()
                        
                    });
                }
            })
        },

        deactivateAccount(userId){
            axios.post('/user-deactivate-account/' + userId).then(res => {
                if(res.data.status === 'deactivate'){
                    this.$buefy.dialog.alert({
                        title: 'DEACTIVATED!',
                        type: 'is-warning',
                        message: 'Account deactivated.',
                        confirmText: 'OK',
                        onConfirm: () => this.loadAsyncData()
                    });
                }
            })
        }


    },

    mounted() {
        this.loadAsyncData();
        this.loadProvince();
    }
}
</script>


<style>


</style>
