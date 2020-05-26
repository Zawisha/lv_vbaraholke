<template>
    <div>
        <div class="container  logemail">
            <div class="row newpassword col-lg-12 d-flex justify-content-center col-md-12 col-sm-12 col-12">

                <div class="col-6 newpasswordform col-md-8 col-12" v-if="err_mess_flag">
                    <div class="col-12 newpassubmit d-flex justify-content-center" v-for="(mess,number) in error_arr_message">
                        {{mess}}
                    </div>
                </div>


                <div class="newpasswordform col-md-8 col-12">
                    <form v-on:submit.prevent="register(error_arr_message)" method="post">
                        <div class="newpass col-sm-12 d-sm-flex ">
                            <div class="col-12 col-sm-6 newpass1">Введите email</div>
                            <div class="col-12 col-sm-6 newpass2"><input class="input" type="email" name="email" v-model="email" v-bind:class="{border_alert: elemInArr(1)}"></div>
                        </div>

                        <div class=" newpass col-sm-12 d-sm-flex">
                            <div class="col-12 col-sm-6 newpass1">Введите никнейм</div>
                            <div class="col-12 col-sm-6 newpass2"><input class="input" type="text" name="username" v-model="username" v-bind:class="{border_alert: elemInArr(2)}"></div>
                        </div>

                        <div class=" newpass col-sm-12 d-sm-flex">
                            <div class="col-12 col-sm-6 newpass1">Введите пароль</div>
                            <div class="col-12 col-sm-6 newpass2"><input class="input" type="password" name="password" v-model="password" v-bind:class="{border_alert: elemInArr(3)}"></div>
                        </div>

                        <div class=" newpass col-sm-12 d-sm-flex">
                            <div class="col-12 col-sm-6 newpass1">Подтвердите пароль</div>
                            <div class="col-12 col-sm-6 newpass2"> <input class="input" type="password" name="password_confirmation" v-model="password_conf" v-bind:class="{border_alert: elemInArr(5)}"></div>
                        </div>

                        <div class=" newpass col-sm-12 d-sm-flex ">
                            <div class="col-12 col-sm-6 newpass1">Мобильный телефон</div>
                            <div class="col-12 col-sm-6 newpass2">
<!--                                <input  type="text" placeholder="+XXX(XX)-XXX-XX-XX" v-model="mobile" v-bind:class="{border_alert: elemInArr(4)}">-->
                                <masked-input mask="\+111-(11)-111-11-11" placeholder="+XXX(XX)-XXX-XX-XX"  v-model="mobile" v-bind:class="{border_alert: elemInArr(4)}" />
                            </div>
                        </div>

                        <div class="col-12 newpassubmit d-flex justify-content-center ">
                            <button  type="submit">Зарегистрироваться</button>
                        </div>
                    </form>
                </div>
            </div>
            <button type="button" class="btn btn-primary btn-block procedure_button" v-on:click="test">test</button>

        </div>

        <div class="container ">
            <div class="row newpassword col-lg-12 d-flex justify-content-center col-md-12 col-sm-12 col-12">

                <div class="col-6 newpasswordform col-md-8 col-12">
                    <div class="col-12 newpassubmit d-flex justify-content-center">
                        Войти через Вконтакте
                        <img src="images/vk.png" alt="vk" class="vk">
                        <a href= 'https://oauth.vk.com/authorize?client_id=6721477&redirect_uri=http://localhost/authvk&response_type=code'>Войти через ВК</a>
                    </div>

                </div>


            </div>
        </div>
    </div>
</template>

<script>


    export default {
        data() {
            return {
                email:'',
                username:'',
                password:'',
                password_conf:'',
                mobile:'',
                err_mess:'',
                err_mess_flag:false,
                error_arr:[],
                error_arr_new:[],
                error_arr_message:[],
                alarm_arr:[],


            }
        },
        mounted() {
        },
        created() {
        },
        methods: {
            test()
            {

            },
            elemInArr(numb)
            {
                return this.alarm_arr.indexOf(numb) === -1 ? false : true
            },

            register(inp) {
                this.error_arr_message = [];
                this.err_mess_flag = false;
                inp=[];
                this.alarm_arr=[];

                if (this.password !== this.password_conf) {
                    this.err_mess_flag = true,
                    this.error_arr_message.push('Пароли не совпадают'),
                    this.alarm_arr.push(3);
                    this.alarm_arr.push(5);
                }
                else
                {
                    this.email.trim();
                    this.username.trim();
                    let telefon =this.mobile;
                    telefon = telefon.replace(/-/g, '');
                    telefon = telefon.replace(/\(/g, '');
                    telefon = telefon.replace(/\)/g, '');
                    telefon = telefon.replace(/\+/g, '');
                    telefon.trim();
                axios
                    .post('/register', {
                        email: this.email,
                        username: this.username,
                        password: this.password,
                        mobile: telefon
                    })

                    .then(({data}) => {

                            if (data.status == 'fail') {
                                this.err_mess_flag = true,

                                    this.error_arr_new = Object.entries(data);
                                this.error_arr = this.error_arr_new[0][1];

                                for (let key in this.error_arr) {
                                    this.error_arr[key].forEach(function (item) {

                                        inp.push(item)
                                    });
                                    if (key == 'email') {
                                        this.alarm_arr.push(1);
                                    }
                                    if (key == 'username') {
                                        this.alarm_arr.push(2);
                                    }
                                    if (key == 'password') {
                                        this.alarm_arr.push(3);
                                    }
                                    if (key == 'mobile') {
                                        this.alarm_arr.push(4);
                                    }
                                }
                                this.error_arr_message = inp;
                            }
                            else
                            {
                                if (data.status == 'success')
                                {
                                    Vue.router.push({name:'thregister'})
                                }

                                }

                        }
                    )
                    .catch(({response}) => {

                    });

            }
            }

        }
    }
</script>
