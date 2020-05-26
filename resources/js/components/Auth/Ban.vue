<template>
    <div>
    <div>
        Пользователь забанен
    </div>
    <div class="col-xl-3 col-lg-5 col-md-8 logout">
        <a v-on:click="logout">Выйти</a>
    </div>
    </div>
</template>

<script>
    export default {
        data() {
            return {


            }
        },
        mounted() {
            // this.test_login();
        },
        created() {
        },
        methods: {
            logout()
            {
                axios
                    .post('/logout',{
                    })
                    .then(({ data }) => (
                            auth.logout(),
                                Vue.router.push({path:'/'})
                        )
                    );
            }


        }
    }
</script>
