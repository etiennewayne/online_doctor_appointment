<template>
    <div>
        <div class="section">
            <div class="columns is-centered">
                <div class="column is-8">
                    <div class="w-panel-card">
                        <div class="w-panel-heading">
                            <div class="mb-2" style="font-size: 20px; font-weight: bold;">CREATE/UPDATE NEW APPOINTMENT</div>
                        </div>

                        <div class="w-panel-body">
                            
                            <b-field label="Scheduler" label-position="on-border">
                                <modal-browse-user :prop-user="user_fullname" 
                                    @browseUser="emitBrowseUser($event)">
                                </modal-browse-user>
                            </b-field>

                            <b-field label="Pick date" label-position="on-border">
                                <b-datepicker v-model="appointment_date"
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
                                    @click="submit"
                                    :disabled="schedule_id < 1"
                                    class="mt-3"
                                    label="Save Schedule"
                                    icon-left="calendar"
                                    type="is-primary is-outlined"></b-button>
                            </div>
                            
                        </div> <!--panel body-->

                    </div>
                </div><!--col -->
            </div><!-- cols -->
        </div><!--section div-->


    </div>
</template>

<script>

export default{
    props: {
        propId: {
            type: Number,
            default: 0
        },

        propAppointment: {
            type: String,
            default: '',
        },
    },
    

    data() {
        return{
            fields: {},
            errors: {},

            appointment_date: new Date(),

            schedules: [],
            schedule_id: 0,

            user_fullname: '',

            btnClass: {
                'is-primary': true,
                'is-outlined': true,
                'is-loading': false,
                'button': true
            },

            appointment: {},
        }

    },

    methods: {
     
        loadOpenSchedules(){
            this.schedule_id = 0;
            const appdate = this.appointment_date.getFullYear() + '-' 
                + (this.appointment_date.getMonth() + 1).toString().padStart(2, "0") + '-' 
                + (this.appointment_date.getDate()).toString().padStart(2,'0')

                //yyyy-MM-dd

            const params = [
                `appdate=${appdate}`,
            ].join('&')

            axios.get(`/load-open-schedules?${params}`).then(res=>{
                this.schedules = res.data
                if(this.propId > 0){
                    this.schedule_id = this.appointment.schedule_id;
                }
            })
        },


        submit(){
             const appdate = this.appointment_date.getFullYear() + '-' 
                + (this.appointment_date.getMonth() + 1).toString().padStart(2, "0") + '-' 
                + (this.appointment_date.getDate()).toString().padStart(2,'0')

                //yyyy-MM-dd

             let appointment = {
                user_id: this.fields.user_id,
                appointment_date: appdate,
                schedule_id: this.schedule_id,
             };


            if(this.propId > 0){
                //logic
                axios.put('/appointments/' + this.propId, appointment).then(res=>{
                    if(res.data.status === 'updated'){
                        this.$buefy.dialog.alert({
                            title: 'Saved!',
                            message: 'Reservation successfully saved.',
                            type: 'is-success',
                            onConfirm: ()=>{
                                window.location = '/appointments'
                            }
                        });
                        this.fields = {};
                        this.errors = {};
                    }

                }).catch(err=>{
                    //console.log(err.response.data.errors);

                    if(err.response.status === 422){
                        this.errors = err.response.data.errors;

                        if(this.errors.max){
                            this.$buefy.dialog.alert({
                                title: 'Limit!',
                                message: this.errors.max[0],
                                type: 'is-danger'
                            });
                        }

                        if(this.errors.exists){
                            this.$buefy.dialog.alert({
                                title: 'Exist!',
                                message: this.errors.exists[0],
                                type: 'is-danger'
                            });
                        }
                    }
                })
            }else{
                axios.post('/appointments', appointment).then(res=>{
                    if(res.data.status === 'saved'){
                        this.$buefy.dialog.alert({
                            title: 'Saved!',
                            message: 'Reservation successfully saved.',
                            type: 'is-success',
                            onConfirm: ()=>{
                                window.location = '/appointments'
                            }
                        });
                        this.fields = {};
                        this.errors = {};
                    }

                }).catch(err=>{
                    //console.log(err.response.data.errors);

                    if(err.response.status === 422){
                        this.errors = err.response.data.errors;

                        if(this.errors.max){
                            this.$buefy.dialog.alert({
                                title: 'Limit!',
                                message: this.errors.max[0],
                                type: 'is-danger'
                            });
                        }

                        if(this.errors.exists){
                            this.$buefy.dialog.alert({
                                title: 'Exist!',
                                message: this.errors.exists[0],
                                type: 'is-danger'
                            });
                        }
                    }
                })
            }
            
        },


        getData(){
            //console.log(this.propAppointment)

            if(this.propId > 0){
                this.appointment = JSON.parse(this.propAppointment);
                console.log(this.appointment)

                this.appointment_date = new Date(this.appointment.appointment_date)
                this.fields.user_id = this.appointment.user_id;
                this.loadOpenSchedules()
                this.user_fullname = this.appointment.user.lname + ', ' + this.appointment.user.fname + ' ' + this.appointment.user.mname;
            }

            
        },

        emitBrowseUser(row){
            //console.log(row)
            this.fields.user_id =  row.user_id;
            this.user_fullname = row.lname + ', ' + row.fname + ' ' + row.mname;
        }


    },

    mounted() {
        this.getData();
    },

}
</script>


    <style scoped>
    .text-container {
        max-width: 200px; /* Set the width of the container to limit the text length */
        overflow: hidden; /* Hide overflowing text */
        white-space: nowrap; /* Prevent text from wrapping to new lines */
        text-overflow: ellipsis; /* Show ellipsis (...) when text overflows */
    }

    .long-text {
        max-height: 3em; /* Set the maximum height of the text to limit the number of lines */
        margin: 0; /* Reset margin */
        padding: 0; /* Reset padding */
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

</style>