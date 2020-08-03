<template>
    <div>
    <div class="container logemail">
        <div class="row newpassword col-lg-12 d-flex justify-content-center col-md-12 col-sm-12 col-12">
            <div class="col-6 newpasswordform col-md-8 col-12" v-if="err_mess_flag">
                <div class="col-12 newpassubmit d-flex justify-content-center" >
                        {{err_mess}}
                </div>
            </div>

            <div class="newpasswordform col-md-8 col-12 ">
                <form v-on:submit.prevent="login" method="post">
                    <div class="newpass col-sm-12 d-sm-flex ">
                        <div class="col-12 col-sm-6 newpass1" >Введите email</div>
                        <div class="col-12 col-sm-6 newpass2" >
                        <input class="input" type="text" name="email" v-model="email"></div>
                    </div>
                    <div class=" newpass col-sm-12 d-sm-flex">
                        <div class="col-12 col-sm-6 newpass1">Введите пароль</div>
                        <div class="col-12 col-sm-6 newpass2" >
                        <input class="input" type="password" name="password" v-model="password"></div>
                    </div>
                    <div class="col-12 newpassubmit d-flex justify-content-center">
                        <input type="submit" value="Войти">
                    </div>
                </form>
            </div>

        </div>
    </div>

    <div class="container ">
        <div class="row newpassword col-lg-12 d-flex justify-content-center col-md-12 col-sm-12 col-12">
            <div class="col-6 newpasswordform col-md-8 col-12">
                <div class="col-12 newpassubmit d-flex justify-content-center">
                       <a href= 'https://oauth.vk.com/authorize?client_id=6721477&redirect_uri=http://localhost/authvk&response_type=code'>Войти через ВК
                           <img :src="'/images/vk.png'" class="img-fluid">
                       </a>
                </div>
            </div>
        </div>
    </div>

    <div class="container ">
        <div class="row newpassword col-lg-12 d-flex justify-content-center col-md-12 col-sm-12 col-12">
            <div class="col-6 newpasswordform col-md-8 col-12">
                <div class="col-12 newpassubmit d-flex justify-content-center">
                    <router-link  :to="{ name: 'reset_password' }">
                        Восстановить пароль
                    </router-link>
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
                password:'',
                err_mess_flag:false,
                err_mess:''
            }
        },
        mounted() {
        },
        created() {
        },
        methods: {

            login()
            {
                this.err_mess_flag=false
                axios
                    .post('/login',{
                        password:this.password,
                        email:this.email,
                    })
                    .then(({ data }) => (
                        auth.login(data.token, data.user),
                        Vue.router.push({name:'profile'})

                        )
                    )
                    .catch(({response}) => {
                        this.err_mess_flag=true,
                            this.password='',
                        this.err_mess=response.data.message
                    });
            }

        }
    }
</script>
