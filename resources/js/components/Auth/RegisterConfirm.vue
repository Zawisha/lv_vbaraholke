<template>
    <div>
    <div v-if="flag">
        Токен подтверждён
    </div>
    <div v-if="error_flag">
        Ошибка токена
    </div>
    </div>
</template>

<script>
    export default {
        data() {
            return {
            flag:false,
                error_flag:false
            }
        },
        mounted() {

        },
        created() {
            this.confirm_token();
        },
        methods: {

            confirm_token()
            {
                this.flag=false;
                this.error_flag=false;
                let user_id= this.$route.params.id;
                let token= this.$route.params.token;
                axios
                    .post('/confirm_token',{
                        user_id:user_id,
                        token:token
                    })
                    .then(({data}) => {
                        if (data.status_request == 'success')
                        {
                            this.flag=true,
                            auth.login(data.token, data.user),
                            Vue.router.push({name:'profile'})
                        }
                        if (data.status_request == 'fail') {
                            this.error_flag=true
                        }
                    })


            }

        }
    }
</script>
