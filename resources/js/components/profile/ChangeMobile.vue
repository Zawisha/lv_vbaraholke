<template>
    <div class="container ">

        <div class="row newpassword newpasswordform col-lg-12 d-flex justify-content-center col-md-12 col-sm-12 col-12">

            <div class="col-6 col-md-8 col-12" v-if="err_mess_flag_email">
                <div class="col-12 newpassubmit d-flex justify-content-center" v-for="(mess) in error_arr_message_email">
                    <div >{{mess}}</div>
                </div>
            </div>
            <div class="col-6 col-md-8 col-12" v-if="success_change_email">
                <div class="col-12 newpassubmit d-flex justify-content-center">
                    Email успешно изменён
                </div>
            </div>
            <div class="col-md-8 col-12">
                    <div class="newpass col-sm-12 d-sm-flex ">
                        <div class="col-12 col-sm-6 newpass1">Email</div>
                        <div class="col-12 col-sm-6 newpass2">
                            <input class="input" type="email" name="email" v-model="email">
                        </div>
                    </div>
                    <div class="col-12 newpassubmit d-flex justify-content-center">
                        <button  type="button" v-on:click="change_email()">Сохранить email</button>
                    </div>
            </div>

                <div class="col-6 col-md-8 col-12" v-if="err_mess_flag">
                    <div class="col-12 newpassubmit d-flex justify-content-center" v-for="(mess) in error_arr_message">
                        <div >{{mess}}</div>
                    </div>
                </div>
                <div class="col-6 col-md-8 col-12" v-if="success_change_mobile">
                    <div class="col-12 newpassubmit d-flex justify-content-center">
                        Телефон успешно изменён
                    </div>
                </div>
                <div class="col-md-8 col-12">
                    <div class="newpass col-sm-12 d-sm-flex ">
                        <div class="col-12 col-sm-6 newpass1">Телефон</div>
                        <div class="col-12 col-sm-6 newpass2">
                            <masked-input mask="\+111-(11)-111-11-11" placeholder="+XXX(XX)-XXX-XX-XX"  v-model="mobile" />
                        </div>
                    </div>
                    <div class="col-12 newpassubmit d-flex justify-content-center">
                        <button  type="button" v-on:click="change()">Сохранить телефон</button>
                    </div>
                </div>

            </div>
        </div>
</template>

<script>
    export default {
        data() {
            return {
            mobile:auth.user.mobile,
            error_arr_message:[],
            err_mess_flag:false,
            success_change_mobile:false,
                error_arr_message_email:[],
                err_mess_flag_email:false,
                success_change_email:false,
                email:auth.user.email

            }
        },
        mounted() {
        },
        created() {

        },
        methods: {

            change_email()
            {
                this. err_mess_flag_email=false;
                this.error_arr_message_email=[];
                this.success_change_email=false;
                this.email.trim();
                axios
                    .post('/change_email', {
                        email: this.email
                    })               .then(({data}) => {

                        if (data.status == 'fail') {
                            this.err_mess_flag_email = true,
                            this.error_arr_new = Object.entries(data);
                            this.error_arr = this.error_arr_new[0][1];
                            let inp =[];
                            for (let key in this.error_arr) {
                                this.error_arr[key].forEach(function (item) {
                                    inp.push(item)
                                });
                            }
                            this.error_arr_message_email = inp;
                        }
                        else
                        {
                            if (data.status == 'success')
                            {
                                this.success_change_email=true;
                            }

                        }

                    }
                )
            },

            change()
            {

               this. err_mess_flag=false;
                this.success_change_mobile=false;
                this.error_arr_message=[];

                let telefon =this.mobile;
                telefon = telefon.replace(/-/g, '');
                telefon = telefon.replace(/\(/g, '');
                telefon = telefon.replace(/\)/g, '');
                telefon = telefon.replace(/\+/g, '');
                telefon.trim();
                axios
                    .post('/change_mobile', {
                        mobile: telefon
                    })               .then(({data}) => {

                        if (data.status == 'fail') {
                            this.err_mess_flag = true,
                                this.error_arr_new = Object.entries(data);
                            this.error_arr = this.error_arr_new[0][1];
                            let inp =[];
                            for (let key in this.error_arr) {
                                this.error_arr[key].forEach(function (item) {
                                    inp.push(item)
                                });
                            }
                            this.error_arr_message = inp;
                        }
                        else
                        {
                            if (data.status == 'success')
                            {
                               this.success_change_mobile=true;
                            }

                        }

                    }
                )

            },



        }
    }
</script>
