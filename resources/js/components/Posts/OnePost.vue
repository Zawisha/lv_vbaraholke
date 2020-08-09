<template>
    <div class="container ">
<div v-if="alert_flag==0">
        <div class="row userpostcolm"  v-for="(post, number) in post_data">
            <div class="col-12 comeback">
                <router-link  :to="{ path: '/'}">Вернуться назад</router-link>
            </div>

            <div class="col-12 headerpost">
                {{ post.title }}
            </div>

            <div class="col-12" v-if="posts_img_arr[number].img_in_arr.length !=0">
                <carousel :data="posts_img_arr[number].img_in_arr"></carousel>
            </div>
            <div v-else>
                <img :src="'/images/nophoto.png'" class="img-fluid">
            </div>


    <div class="col-12 viewposttext1 ">
        <a href="/user?id=<?php echo $id['0']['userid']; ?>"></a>
            Автор:{{ post.user_name }}
    </div>

    <div class="col-12 viewposttext1 ">
            {{post.description}}
    </div>
    <div class="col-12 viewposttext ">
        Город:{{post.city}}
    </div>
            <div class="col-12 viewposttext ">
                Область:{{post.region}}
            </div>

    <div class="col-12 viewposttext " v-if="post.condition==1">
       Состояние: Новое
    </div>
            <div v-else>
                Состояние: Б/У
            </div>

    <div class="col-12 viewposttext ">
       Добавлено: {{post.created_at}}
    </div>

    <div class="col-12 viewposttext ">
       Цена: {{post.price}} руб.
    </div>

        </div>

    <div class=" col-12  d-flex justify-content-center stic">
        <div class="col-6 col-sm-4 viewposttext2 " id="write_to_user" v-on:click="write_message()">
               <span> Написать</span>
        </div>
        <div class="col-6 col-sm-4 viewposttext3 " id="call"  v-on:click="show_mob()">
           <span>{{call_mobile}}</span>
        </div>
    </div>
<!--    <button type="button" class="btn btn-primary btn-block procedure_button" v-on:click="test">test</button>-->

</div>
        <div v-else>
            Нет такого поста
        </div>
    </div>
</template>

<script>
    export default {
        data() {
            return {
                post_data:'',
                posts_img_arr:[],
                alert_flag:0,
                call_mobile:'Позвонить'
            }
        },

        created() {
            this.get_post_data(this.post_data);
        },
        methods: {

            write_message()
            {
            Vue.router.push({name:'chat', params: { to_user_props:this.post_data[0]['user_id']}});
            },

            show_mob() {
                if(this.post_data[0]['mobile']!=null)
                {
                    this.call_mobile=this.post_data[0]['mobile'];
                }
                else
                {
                    this.call_mobile='Не указан';
                }
            },

            // test()
            // {
            //    console.log(this.post_data)
            //    console.log(this.posts_img_arr)
            // },

           get_post_data(inp)
            {
                this.alert_flag=0;
                let post_id= this.$route.params.id;
                axios
                    .post('/get_post_data',{
                        post_id:post_id,
                    })
                    .then(({ data }) => {
                            if(data.length != 0)
                            {
                                let inp = [];
                                let images = [];
                                data.forEach(function(entry) {
                                    let img_arr =[];
                                    for (let i = 0; i < entry.img.length ; i++) {
                                        img_arr.push('<img :src="'+'\'/images/posts/' +entry.img[i].path +'\'" alt=""/>' )
                                    }
                                    inp.push({
                                        id:entry.id,
                                        title:entry.title,
                                        description:entry.description,
                                        price:entry.price,
                                        region:entry.reg.region,
                                        city:entry.city,
                                        category:entry.category,
                                        condition:entry.condition,
                                        created_at:entry.created_at,
                                        img_in_arr:img_arr,
                                        user_id:entry.usr.id,
                                        user_name:entry.usr.name,
                                        mobile:entry.usr.mobile
                                    });
                                    images.push({
                                        id:entry.id,
                                        img_in_arr:img_arr
                                    });

                                })

                                this.post_data=inp;
                                this.posts_img_arr = images;

                             }
                            else
                            {
                                this.alert_flag=1;
                            }

                  }
                    )
                    .catch(error => {
                        this.alert_flag=1;
                    });
            },

        }
    }
</script>
