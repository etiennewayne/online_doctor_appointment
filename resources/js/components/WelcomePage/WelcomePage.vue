<template>
    <div>
        <!-- <carousel-images></carousel-images> -->
        <div class="section">

            <div class="w-hero">
                <div class="columns mb-2">
                    <div class="column">
                        <div class="banner-title">
                            Online Doctor's Appointment System
                        </div>
                    </div>
                    <div class="column">
                        <div class="profile-picture"></div>
                        <!-- <div class="profile-picture-container">
                        </div> -->
                        <!-- <div class="profile-container">
                            <div class="svg-bg-container">
                                <svg viewBox="0 0 200 200" xmlns="http://www.w3.org/2000/svg" class="svg-bg">
                                    <path fill="#0F62FE" d="M24.1,-49C30.1,-38.3,32.9,-29.5,44,-21.6C55.2,-13.7,74.7,-6.9,80.1,3.1C85.5,13.1,76.8,26.2,67.5,37.3C58.2,48.4,48.4,57.6,37,59.4C25.7,61.2,12.8,55.7,1.3,53.3C-10.1,51,-20.3,51.8,-30.9,49.6C-41.5,47.3,-52.5,42,-57.5,33.2C-62.5,24.5,-61.4,12.2,-64.3,-1.7C-67.3,-15.6,-74.3,-31.3,-68.3,-38.4C-62.4,-45.4,-43.4,-43.9,-29.9,-50.2C-16.3,-56.5,-8.2,-70.6,0.5,-71.4C9.1,-72.2,18.2,-59.7,24.1,-49Z" transform="translate(100 100)" />
                                </svg>
                            </div>
                            
                        </div> -->
                    </div>
                </div>

            </div> <!--hero -->

            <div style="height: 10px; background-color: rgb(74, 74, 155);"></div>


            <b-notification class="is-info mt-5">
                Appointment beyond clinic hours is case-to-case basis.
            </b-notification>
            

            <div class="columns mt-5">
                <div class="column">
                    <div class="welcome-title">
                        Book your clinic appointment with Dr. Tilao.
                    </div>

                    <div class="welcome-subtitle">
                        Convenient, Fast, and Reliable
                    </div>
                    <div class="welcome-content">
                        Our clinic is a healthcare facility that is dedicated to providing high-quality medical care to our patients. 
                        We pride ourselves on delivering compassionate and personalized care that meets the unique needs of each patient. 
                        Our team of experienced healthcare professionals are committed to delivering excellent care in a comfortable and welcoming environment. 
                        We believe in patient-centered care and take the time to listen to our patients and understand their concerns. 
                        Our clinic is equipped with modern medical equipment and technology to ensure accurate diagnoses and effective treatment. 
                        We prioritize patient safety, confidentiality, and comfort, and we strive to create a positive healthcare experience for every patient. 
                        Thank you for considering our clinic for your healthcare needs.
                    </div>
                </div>
                <div class="column">
                    <div class="schedule-box" v-if="isLogin">
                        <div class="doctor-schedule">
                            <div class="doctor-schedule-header">
                                Doctor's Vacation Schedule
                            </div>

                            <div class="doctor-vacation-schedule content">
                                <ul>
                                    <li v-for="(ii, iix) in doctorVacations" :key="iix">
                                        {{ new Date(ii.vacation_date).toDateString() }}
                                    </li>
                                </ul>
                            </div>

                            <div class="doctor-schedule-header">
                                Patient Information
                            </div>
                            
                            <b-field
                                :type="this.errors.patients ? 'is-danger':''"
                                :message="this.errors.patients ? this.errors.patients[0] : ''"></b-field>
                            
                            <div v-for="(patient, pIndex) in fields.patients" :key="`patient${pIndex}`">
                                <div class="columns">
                                    <div class="column">
                                        <b-field label="Patient Last Name" label-position="on-border">
                                            <b-input type="text" v-model="patient.lname" placeholder="Patient Last Name">
                                            </b-input>
                                        </b-field>
                                    </div>
                                    <div class="column">
                                        <b-field label="Patient First Name" label-position="on-border">
                                            <b-input type="text" v-model="patient.fname" placeholder="Patient First Name">
                                            </b-input>
                                        </b-field>
                                    </div>
                                </div>

                                <div class="columns">
                                    <div class="column">
                                        <b-field label="Patient Middle Name" label-position="on-border">
                                            <b-input type="text" v-model="patient.mname" placeholder="Patient Middle Name">
                                            </b-input>
                                        </b-field>
                                    </div>
                                    <div class="column">
                                        <b-field label="Sex" label-position="on-border">
                                            <b-select v-model="patient.sex" placeholder="Sex">
                                                <option value="MALE">MALE</option>
                                                <option value="FEMALE">FEMALE</option>
                                            </b-select>
                                        </b-field>
                                    </div>

                                    <div class="column">
                                        <b-field label="Age" label-position="on-border">
                                            <b-numberinput type="text" v-model="patient.age" 
                                                placeholder="Age"
                                                :controls="false">
                                            </b-numberinput>
                                        </b-field>
                                    </div>
                                </div>

                                <div class="columns">
                                    <div class="column">
                                        <b-field class="mt-3" label="Illness Description/History (Optional)" label-position="on-border">
                                            <b-input type="textarea" placeholder="Illness Description/History (Optional)"
                                                v-model="patient.illness"></b-input>
                                        </b-field>
                                    </div>
                                </div>

                                <div class="columns">
                                    <div class="column">
                                        <b-field label="Province" label-position="on-border" expanded>
                                            <b-select v-model="patient.province" @focus="loadProvince(pIndex)" expanded>
                                                <option v-for="(item, index) in patient.provinces" :key="index" :value="item.provCode">{{ item.provDesc }}</option>
                                            </b-select>
                                        </b-field>
                                    </div>

                                    <div class="column">
                                        <b-field label="City" label-position="on-border" expanded>
                                            <b-select v-model="patient.city" @focus="loadCities(pIndex, patient.province)" expanded>
                                                <option v-for="(item, index) in patient.cities" :key="index" :value="item.citymunCode">{{ item.citymunDesc }}</option>
                                            </b-select>
                                        </b-field>
                                    </div>
                                </div>
                                 
                                <div class="columns">
                                    <div class="column">
                                        <b-field label="Barangay" label-position="on-border" expanded>
                                            <b-select v-model="patient.barangay" expanded @focus="loadBarangays(pIndex, patient.province, patient.city)">
                                                <option v-for="(item, index) in patient.barangays" :key="index" :value="item.brgyCode">{{ item.brgyDesc }}</option>
                                            </b-select>
                                        </b-field>
                                    </div>
                                    <div class="column">
                                        <b-field label="Street" label-position="on-border">
                                            <b-input v-model="patient.street"
                                                    placeholder="Street">
                                            </b-input>
                                        </b-field>
                                    </div>
                                </div>
                               
                                <div class="buttons">
                                    <b-button label="" icon-left="delete" type="is-danger"
                                        class="is-small"
                                        @click="removePatient(pIndex)"></b-button>
                                </div>
                                
                                <hr>
                            </div> <!--loop-->
                            <b-checkbox v-model="iamPatient"
                                @input="clearPatientForm"
                                :true-value="1"
                                :false-value="0">I am the Patient</b-checkbox>

                            <div v-if="iamPatient">
                                <b-field class="mt-3" label="Age" label-position="on-border"
                                    :type="this.errors.iamPatientFields ? 'is-danger':''"
                                    :mes="this.errors.iamPatientFields ? this.errors.iamPatientFields.age[0] : ''">
                                    <b-numberinput type="text" v-model="iamPatientFields.age" 
                                        placeholder="Age"
                                        :controls="false">
                                    </b-numberinput>
                                </b-field>
                                <b-field class="mt-3" label="Illness Description/History (Optional)" label-position="on-border">
                                    <b-input type="textarea" placeholder="Illness Description/History (Optional)"
                                        v-model="iamPatientFields.illness"></b-input>
                                </b-field>
                            </div>

                            <div class="buttons mt-5" v-if="!iamPatient">
                                <b-button class="button is-small is-info" 
                                    @click="addPatient"
                                    :disabled="fields.patients.length >= max">Add</b-button>
                            </div>

                            <div class="doctor-schedule-header">
                                Doctor's Schedules
                            </div>

                            <b-field label="Pick date" label-position="on-border">
                                <b-datepicker v-model="appointment_date"
                                    inline
                                    :min-date="new Date()"
                                    :unselectable-dates="vacations"
                                    @input="loadOpenSchedules">
                                </b-datepicker>
                            </b-field>
                            <div class="schedule-item" v-for="(item, index) in schedules"
                                :key="index">
                                <div class="schedule-item-time">{{ item.time_from | formatTime }} - {{ item.time_end | formatTime }}</div>
                                <div class="schedule-item-radio">
                                    <b-radio v-model="schedule_id"
                                        name="name"
                                        :native-value=item.schedule_id>
                                    </b-radio>
                                </div>
                            </div>

                            <div class="buttons">
                                <b-button
                                    @click="applyAppointment"
                                    :disabled="schedule_id < 1"
                                    class="mt-3"
                                    label="Make An Appointment"
                                    icon-left="calendar"
                                    type="is-primary is-outlined"></b-button>
                            </div>
                        </div>
                    </div>

                    <div class="login-box" v-else>
                        <div class="login-header">
                            <div class="login-header-text">
                                Login here
                            </div>
                            <p>
                                Or click <a href="/register-page">register here</a> to register an account.
                            </p>
                        </div>
                        
                        <div>
                            
                            <b-field label="Username"
                                :type="this.errors.username ? 'is-danger':''"
                                :message="this.errors.username ? this.errors.username[0] : ''">
                                <b-input type="text" 
                                    v-model="fields.username"
                                    required
                                    placeholder="Username"></b-input>
                            </b-field>
                            <b-field label="Password"
                                :type="this.errors.password ? 'is-danger':''"
                                :message="this.errors.password ? this.errors.password[0] : ''">
                                <b-input type="password"
                                    password-reveal 
                                    v-model="fields.password"
                                    required
                                    placeholder="Password"></b-input> 
                            </b-field>

                            <div class="buttons">
                                <b-button 
                                    :class="btnClass"
                                    label="Login"
                                    @click="submit"
                                    icon-left="login"></b-button>

                                <b-button tag="a" type="is-info is-outlined"
                                    label="Register here..."
                                    href="/register-page"
                                    icon-left="account"></b-button>
                            </div>

                            <p>
                                <a href="/forgot-password">Forgot Password</a>
                            </p>
                            
                        </div>
                       
                    </div> <!--login box -->
                </div>
            </div> <!--cols --->
        </div>

        <services-offered></services-offered>

        <about></about>

        <affiliation></affiliation>

    </div> <!-- root div -->
</template>

<script>
import CarouselImages from './CarouselImages.vue';

export default {
  components: { CarouselImages },

    props: {
        isLogin: {
            type: Boolean,
            default: false
        }
    },

    data(){
        return {
            fields: {
                patients: [],
            },

            iamPatient: 0,

            iamPatientFields: {
                illness: '',
                age: 0,
            },
            
           
            errors: {},

            appointment_date: null,
            vacations: [],
            max: 0,
            doctorVacations: [],



           

            schedules: [],
            schedule_id: 0,

            btnClass: {
                'is-primary': true,
                'is-outlined': true,
                'is-loading': false,
                'button': true
            },
        }
    },

    methods: {
        
        loadOpenSchedules(){
            this.schedule_id = 0;
            //this.vacations = [];
            
          

            const appdate = this.appointment_date.getFullYear() + '-' 
                + (this.appointment_date.getMonth() + 1).toString().padStart(2, "0") + '-' 
                + (this.appointment_date.getDate()).toString().padStart(2,'0')

                //yyyy-MM-dd
            

            const params = [
                `appdate=${appdate}`,
            ].join('&')


            axios.get(`/load-open-schedules?${params}`).then(res=>{
                this.schedules = res.data
            })
        },

        loadVacations(){

            const dDate = new Date();
            const appdate = dDate.getFullYear() + '-' 
                + (dDate.getMonth() + 1).toString().padStart(2, "0") + '-' 
                + (dDate.getDate()).toString().padStart(2,'0')

            const params = [
                `appdate=${appdate}`,
            ].join('&')

            axios.get(`/load-vacations?${params}`).then(res=>{
                //this.vacations = res.data
                res.data.forEach(element => {
                    const d = new Date(element.vacation_date)
                    this.vacations.push(d)
                });
                console.log(this.vacations);
                //tiwasonun and ma deact ang date..
            })
        },
       

        submit(){
            this.btnClass['is-loading'] = true;

            axios.post('/login', this.fields).then(res=>{
            this.btnClass['is-loading'] = false;
                if(res.data.role === 'ADMINISTRATOR' || res.data.role === 'STAFF'){
                    window.location = '/dashboard';
                }

                if(res.data.role === 'USER'){
                    window.location = '/';
                }
                
            }).catch(err=>{
                this.btnClass['is-loading'] = false;
                this.errors = err.response.data.errors;
            })
        },


        applyAppointment(){

             const appdate = this.appointment_date.getFullYear() + '-' 
                + (this.appointment_date.getMonth() + 1).toString().padStart(2, "0") + '-' 
                + (this.appointment_date.getDate()).toString().padStart(2,'0')

                //yyyy-MM-dd

             let appointment = {
                appointment_date: appdate,
                schedule_id: this.schedule_id,
                patients: this.fields.patients,
                iamPatientFields: this.iamPatientFields,
                iamPatient: this.iamPatient,
             };

            axios.post('/apply-appointment', appointment).then(res=>{
                if(res.data.status === 'saved'){
                    this.$buefy.dialog.alert({
                        title: 'Saved!',
                        message: 'Reservation successfully saved.',
                        type: 'is-success'
                    });
                    
                    this.fields = {
                        patients: []
                    };

                    this.errors = {};

                    window.location = '/my-appointment';
                }

            }).catch(err=>{
                //console.log(err.response.data.errors);

                if(err.response.status === 422){
                    this.errors = err.response.data.errors;
                    if(this.errors.patients){
                        this.errors.patients[0] = 'Please add patient.';
                    }

                    if(this.errors.max){

                        //save the new schedule found
                        let msg = this.errors.max[0] + ' Do you want to move schedule to <b>' + this.errors.max[2] + ', ' 
                            + this.$formatTime(this.errors.max[1].time_from) + ' - ' + this.$formatTime(this.errors.max[1].time_end) + '?</b><br>';
                        
                        if(this.errors.max[3]){
                            msg  +=  '<br><span style="font-weight:bold;color:red;">Are you willing to accept the schedule? The date was change from the original.</span>';
                        }

                        this.$buefy.dialog.confirm({
                            title: 'Limit!',
                            message: msg ,
                            type: 'is-info',
                            confirmText: 'Proceed schedule',
                            onConfirm: () => {
                                this.appointment_date = new Date(this.errors.max[2]);
                                const newData = this.errors.max[1];
                                this.schedule_id = newData.schedule_id;

                                this.applyAppointment() //recursive
                            }
                        });
                    }

                    if(this.errors.exists){
                        this.$buefy.dialog.alert({
                            title: 'Exist!',
                            message: this.errors.exists[0],
                            type: 'is-danger'
                        });
                    }

                    /**MISSED APPOINTMENT ERROR */
                    if(this.errors.multiple_missed_appointment){
                        this.$buefy.dialog.alert({
                            title: 'Missed Appointment.',
                            message: this.errors.multiple_missed_appointment[0],
                            type: 'is-danger'
                        });
                    }
                    
                }
            })
        },

        addPatient(){
            this.fields.patients.push({
                lname: '',
                fname: '',
                mname: '',
                sex: '',
                age: 0,
                illness: '',
                province: '',
                city: '',
                barangay: '',
                street: '',
                provinces: [],
                cities: [],
                barangays: [],
                street: '',
            });

         
        },

        removePatient(index){
            this.$buefy.dialog.confirm({
                title: 'DELETE?',
                message: 'Are you sure you want to remove this?',
                onConfirm: ()=>{
                    
                }
            });
        },
        clearPatientForm(){
            //alert('fire')
            this.fields.patients = [];
            // this.fields.partients.forEach(element, ix => {
            //     this.fields.patients.splice(index, ix);
            // });
            
        },

        loadProvince: function(index){
            axios.get('/load-provinces').then(res=>{
                this.fields.patients[index].provinces = res.data;
            })
        },

        loadCities: function(index, prov){
            axios.get('/load-cities?prov=' + prov).then(res=>{
                this.fields.patients[index].cities = res.data;
            })
        },

        loadBarangays: function(index, prov, city){
            axios.get('/load-barangays?prov=' + prov + '&city_code='+ city).then(res=>{
                this.fields.patients[index].barangays = res.data;
            })
        },

        loadMaxPatient(){
            axios.get('/load-max-no').then(res=>{
                this.max = res.data.max
            })
        },

        loadDoctorVacations(){
            axios.get('/load-doctor-vacations').then(res=>{
                this.doctorVacations = res.data
            })
        }
    },

    mounted(){
        //this.appointment_date = new Date();
        this.loadVacations()
        this.loadDoctorVacations()
        this.loadMaxPatient();
    }

}
</script>



<style scoped>
    .welcome-title{
        font-size: 3em;
        font-weight: bolder;
    }

    .welcome-subtitle{
        font-size: 1.5em;
        font-weight: bold;
    }

    .welcome-content{
        margin: 50px 0;
        font-size: 1.4em;
        text-align: justify;
    }


    .schedule-box{
        box-shadow: rgba(0, 0, 0, 0.16) 0px 1px 4px;
        max-width: 600px;
        margin: auto;
        padding: 25px;
    }

    .doctor-schedule{
        max-width: 500px;
        margin: 15px auto;
    }

    .doctor-schedule-header{
        font-weight: bold;
        font-size: 1.2em;
        margin: 15px 0;
    }

    .schedule-item{
        display: flex;
        padding: 15px;
        margin: 5px;
        border: 1px solid lightgray;
        border-radius: 10px;
        transition: all 0.5s;
    }

    .schedule-item:hover{
        border: 1.5px solid rgb(58, 176, 223);
    }

    .schedule-item-radio{
        margin-left: auto;
    }



    .login-box{
        box-shadow: rgba(0, 0, 0, 0.16) 0px 1px 4px;
        max-width: 600px;
        margin: auto;
        padding: 25px;
        border-top: 2px solid rgb(56, 56, 165);
    }

    .login-header{
        border-bottom: 1px solid blue;
        padding: 0 0 15px 0;
        margin: 0 0 10px 0;
    }
    .login-header-text{
        font-weight: bold; 
        font-size: 1.2em;
    }

    .height-150{
        height: 150px;
    }

    .height-200{
        height: 200px;
    }

    .height-300{
        height: 300px;
    }
    .height-500{
        height: 300px;
    }


    .banner-title{
        font-weight: bolder; 
        font-size: 4em;
        text-align: center;
    }

    .profile-container{
        position: relative;
    }

    

    .profile-picture-container{
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        /* border: 1px solid yellow; */
    }
    .profile-picture{
        background-image: url('/img/logo.jpg');
        width: 500px;
        height: 300px;
        background-size: cover; /* Adjust the background size as needed */
        background-position: center; /* Adjust the background position as needed */
        background-repeat: no-repeat; /* Adjust the background repeat as needed */
    }


    .doctor-vacation-schedule{

    }


    /* Styles for screens smaller than 600px */
    @media (max-width: 640px) {
        .banner-title{
            font-weight: bolder; 
            font-size: 2em;
            text-align: center;
        }

        .svg-bg{
            /* border: 1px solid blue; */
            width: 300px;
            height: 300px;
            display: block;
            margin: auto;
        }
        .profile-picture{
            width: 200px;
            height: 200px;
            margin: auto;
        }
        
    }

    /* Styles for screens between 600px and 900px */
    @media (min-width: 641px) and (max-width: 1007px) {
        .banner-title{
            font-weight: bolder; 
            font-size: 2em;
            text-align: center;
        }

        .profile-picture{
            width: 400px;
            height: 200px;
            margin: auto;
        }
    }

    /* Styles for screens larger than 1200px */
    @media (min-width: 1008px) {
        .w-hero{
            max-width: 1366px;
            /* border: 1px solid red; */
            margin: auto;
        }

        .svg-bg{
            width: 500px;
            height: 500px;
            /* border: 1px solid rgb(53, 177, 29); */
            display: block;
            margin: auto;
        }

        .svg-bg-container{
            position: relative;
            /* border: 1px solid blue; */
        }
    }



</style>