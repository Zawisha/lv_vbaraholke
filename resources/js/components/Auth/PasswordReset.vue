<template>
    <div class="row newpassword col-lg-12 d-flex justify-content-center col-md-12 col-sm-12 col-12">
    <div class="col-6 newpasswordform col-md-8 col-12" v-if="flag">
        <div class="col-12 newpassubmit d-flex justify-content-center" v-for="mes in message_req">
          <div >{{mes}}</div>
        </div>
    </div>



    <div class=" newpasswordform col-md-8 col-12 ">
        <form v-on:submit.prevent="reset_password" method="post">
            <div class="newpass col-sm-12 d-sm-flex ">
                <div class="col-12 col-sm-6 newpass1">Email</div>
                <div class="col-12 col-sm-6 newpass2">
                    <input class="input" type="email" name="email" v-model="email">
                </div>
            </div>
            <div class="newpass col-sm-12 d-sm-flex " v-if="have_old_pass">
                <div class="col-12 col-sm-6 newpass1">Введите старый пароль</div>
                <div class="col-12 col-sm-6 newpass2">
                    <input class="input" type="password" name="password" v-model="password"></div>
            </div>
            <div class="newpass col-sm-12 d-sm-flex ">
                <div class="col-12 col-sm-6 newpass1">Введите новый пароль</div>
                <div class="col-12 col-sm-6 newpass2">
                <input class="input" type="password" name="password" v-model="new_password"></div>
            </div>
            <div class="col-12 newpassubmit d-flex justify-content-center">
                <button  type="submit">Обновить пароль</button>
            </div>
        </form>
    </div>
    </div>
</template>

<script>
    export default {
        data() {
            return {
                password:'',
                new_password:'',
                flag:false,
                message_req:[],
                email:auth.user.email,
                have_old_pass:false
            }
        },
        mounted() {

        },
        created() {
            this.user_pas();
        },
        methods: {

            user_pas()
            {
                axios
            .post('/is_pas',{
            }) .then(({data}) => {
                    if (data.length == 0)
                    {
                        this.have_old_pass=true;
                    }
                    else
                    {
                       this.have_old_pass=false;
                    }
                })
            },

            reset_password()
            {
                this.message_req=[];
                this.flag=false;
                //старого пароля нет (null) задаём с нуля
                if(this.have_old_pass==false)
                {
                    this.flag=false;
                    axios
                        .post('/set_password',{
                            password:this.new_password,
                            email:this.email,
                        })
                        .then(({data}) => {
                            if (data.status_request == 'success')
                            {
                                this.flag=true
                                this.message_req= data.message
                            }
                            if (data.status_request == 'fail') {
                                this.flag=true
                                this.message_req= data.message
                            }
                            if (data.status_request == 'er_message') {
                                this.flag=true
                                let arr_mes=data.message;

                                let arr_temp=[];
                                Object.keys(arr_mes).forEach(function(element, ) {
                                    for (let i = 0; i < arr_mes[element].length; i++) {
                                        arr_temp.push(arr_mes[element][i]);
                                    }
                                })
                                this.message_req=arr_temp;
                            }

                        })
                }
            //end старого пароля нет (null) задаём с нуля

                else {
                //есть и старый пароль и новый пароль
                this.flag=false;
                axios
                    .post('/reset_password',{
                        password:this.password,
                        new_password:this.new_password,
                        email:this.email,
                    })
                    .then(({data}) => {
                        if (data.status_request == 'success')
                        {
                            this.flag=true
                            this.message_req.push(data.message)
                        }
                        if (data.status_request == 'fail') {
                            this.flag=true
                            this.message_req.push(data.message)
                        }
                        if (data.status_request == 'er_message') {
                            this.flag=true
                            let arr_mes=data.message;

                            let arr_temp=[];
                            Object.keys(arr_mes).forEach(function(element, ) {
                                for (let i = 0; i < arr_mes[element].length; i++) {
                                      arr_temp.push(arr_mes[element][i]);
                                }
                            })
                            this.message_req=arr_temp;
                        }

                    })
                //end есть и старый пароль и новый пароль
                }

            }

        }
    }
</script>
