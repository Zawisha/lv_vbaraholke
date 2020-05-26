<template>
    <div v-if="error_flag">
Не удалось войти через Вк
    </div>
</template>

<script>
    export default {
        data() {
            return {

                error_flag:false
            }
        },
        mounted() {
            // this.test_login();
        },
        created() {
           this.get_token();
        },
        methods: {

            get_token()
            {
                    axios
                        .post('/vk_first', {
                            code:this.$route.query.code
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
                            if (data.status_request == 'success_change') {
                                auth.user['vk_id']=data.vk_id
                                Vue.router.push({name:'profile'})
                            }
                        })
            }

        }
    }
</script>
