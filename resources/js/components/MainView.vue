<template>

    <body>
    <header id="header" class="header">
        <div class="container-fluid head">

            <div class="row headimg">
                <div class="col-lg-2">
                    <a href="/" >
                    <img :src="'/images/logo.png'" class="img-fluid">
                    </a>
                </div>
                <div class="col-lg-10">
                    <div class="logo__text">
                        <a href="/" >Vbaraholke.by</a>
                    </div>
                </div>
            </div>
            <div class="info col-12">
                <div class="row justify-content-center">
                    <div class="col-12 col-sm-12 col-md-12 col-lg-4 col-xl-3 addob ">
                        <router-link  :to="{ name: 'add_advertisement' }">
                        ПОДАТЬ ОБЪЯВЛЕНИЕ
                        </router-link>

                    </div>

                    <div v-if="user == null" class="col-12 col-sm-6 col-md-6 menu  col-lg-8 col-xl-8 d-lg-flex justify-content-lg-end ">
                        <div class="col-12 col-sm-12 col-md-12 col-lg-4 col-xl-4 menuitem ">
                            <router-link  :to="{ name: 'register' }">
                            Регистрация
                            </router-link>
                        </div>
                        <div class="col-12 col-sm-12 col-md-12 col-lg-4 col-xl-4 menuitem1 ">
                            <router-link  :to="{ name: 'login' }">
                          Войти
                            </router-link>
                        </div>
                    </div>
                    <div v-else class="col-12 col-sm-6 col-md-6 menu  col-lg-3 col-xl-4 d-lg-flex justify-content-lg-end ">
                        <div class="col-12 col-sm-12 col-md-12 col-lg-12 col-xl-4 menuitem2 justify-content-center">
                            <router-link  :to="{ name: 'profile' }">Профиль</router-link>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </header>

    <router-view></router-view>

    <div class="container infosec">
        <div class="row justify-content-center justify-content-md-start ">
            <div class="col-5 justify-content-center col-lg-3 contacts">
                <router-link  :to="{ name: 'contacts' }">
                      Контакты
                </router-link>
            </div>
            <div class="col-6 justify-content-center col-lg-3 rules">
                <router-link  :to="{ name: 'rules' }">
                    Правила
                </router-link>
            </div>
        </div>
    </div>
    <div class="container footer">
        <div class="row">
            <div class="col-lg-12">
                <div class="credits ">
                    Бесплатная доска объявлений.<br> Ресублика Беларусь 2020.
                </div>
            </div>
        </div>

    </div>
    </body>


</template>

<script>
    export default {
        data() {
            return {

                user:''
            }
        },
        mounted() {
            this.user = auth.user;
            Event.$on('userLoggedIn', () => {
                this.user = auth.user;

            });
            // if(this.user)
            {
                Event.$on('userLoggedOut', () => {
                    this.user = auth.user;
                });
            }

        },
        created() {
        },
        methods: {
            test()
            {

                console.log(auth);
                // axios
                //     .post('/getUser1',{
                //     })
            },
            test_register()
            {
                axios
                    .post('/register',{
                        name:this.name,
                        password:this.password,
                        email:this.email,
                    })
                    .then(({ data }) => (
                           console.log(data)
                        )
                    );
            }

        }
    }
</script>
