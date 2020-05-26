<template>
    <div>
        <div class="container">
            <div class="row addnewpost  justify-content-center">
                <Loading_line></Loading_line>
                <div class="col-12 addnewuserposttext ">
                    <form v-on:submit.prevent="push_the_button" method="post" enctype="multipart/form-data" id="uploadImages"  name = 'validateform'>
                        Название товара (краткое название товара в именительном падеже)

                <div class="col-12 addnewuserpostinput">
                    <textarea @focus="delete_frame('product_name')" v-bind:class="{border_alert: elemInArr('product_name')}" v-model="product_arr['product_name']" class="input " rows="3" cols="40" type="text" name="header" placeholder="от 3 до 50 знаков"></textarea>
                </div>
                <div class="col-12 addnewuserposttext">
                    Описание товара
                </div>
                <div class="col-12 addnewuserpostinput">
                    <textarea @focus="delete_frame('product_desc')" v-bind:class="{border_alert: elemInArr('product_desc')}" v-model="product_arr['product_desc']" class="input changeinput" rows="10" type="text" name="description" placeholder="от 15 до 3000 знаков"></textarea>

                </div>
                <div class="col-12 addnewuserposttext">
                    Цена товара. (в белорусских рублях)
                </div>
                <div class="col-12 addnewuserpostinput">
                    <input  @focus="delete_frame('product_price')" v-bind:class="{border_alert: elemInArr('product_price')}" v-model="product_arr['product_price']" class="input" type="number" min="0" max="9999999" name="price"  placeholder="до 7 знаков"
                            onkeypress="return (event.charCode >= 48 && event.charCode <= 57 && /^\d{0,6}$/.test(this.value));"
                    >.руб
                </div>
                <div class="col-12 addnewuserposttext">
                    Выберите область
                </div>
                <div class="col-12 addnewuserpostinput">
                    <div class="col-12 addnewuserpostinput">
                        <select  @change="onChange('selected_region')" v-bind:class="{border_alert: elemInArr('selected_region')}" v-model="product_arr['selected_region']">
                            <option  v-for="region in regions_arr" v-bind:value="region.id">{{region.name}}</option>
                        </select>
                    </div>
                </div>
                <div class="col-12 addnewuserposttext">
                    Ваш город
                </div>
                <div class="col-12 addnewuserpostinput">
                    <input @focus="delete_frame('city')" v-bind:class="{border_alert: elemInArr('city')}" v-model="product_arr['city']" class="input" type="text" name="city">
                </div>
                <div class="col-12 addnewuserposttext">
                    Выберите категорию
                </div>
                <div class="col-12 addnewuserpostinput">
                    <select  @change="onChange('selected_category')" v-bind:class="{border_alert: elemInArr('selected_category')}" v-model="product_arr['selected_category']">
                        <option v-for="category in category_arr" v-bind:value="category.id">{{category.name}}</option>
                    </select>
                </div>
                <div class="col-12 addnewuserposttext">
                    Состояние товара
                </div>
                <div  class="col-12 addnewuserpostinput">
                    <select @change="onChange('product_cond')" v-bind:class="{border_alert: elemInArr('product_cond')}" v-model="product_arr['product_cond']" name="condition">
                        <option  value="1">Новый</option>
                        <option  value="0">Б/у</option>
                    </select>
                </div>
                <div class="col-12 addnewuserpostsubmit">
                    <input type="submit" value="Далее" >
                </div>
                </form>
            </div>
                <div v-if="alarm == true">
                    <b>Пожалуйста исправьте указанные ошибки:</b>
                <ul>
                    <li v-for="alarm_mes in alarm_mess_arr">{{ alarm_mes }}</li>
                </ul>
                </div>
                <button type="button" class="btn btn-primary btn-block procedure_button" v-on:click="push_the_button1">test</button>

            </div>
        </div>
    </div>
</template>

<script>
    export default {
        data() {
            return {

            regions_arr:[ ],
            category_arr:[ ],
            product_arr:{'product_name' : '', 'product_desc' : '', 'product_price': '', 'city': '', 'product_cond': '', 'selected_region' : '', 'selected_category' : ''},

             alarm_arr:[],
                alarm:'',
                alarm_mess_arr:[]



            }
        },
        mounted() {
           this.select_region(this.regions_arr);
           this.select_category(this.category_arr);
        },
        created() {
        },
        methods: {

            push_the_button1()
            {
                Vue.router.push({name:'add_advertisement2'})
            },

            delete_frame (product)
            {
                this.alarm_arr.splice(this.alarm_arr.indexOf(product), 1);
            },

            onChange:function(product){
                this.alarm_arr.splice(this.alarm_arr.indexOf(product), 1);
            },

            elemInArr(numb)
            {
                return this.alarm_arr.indexOf(numb) === -1 ? false : true
            },


            push_the_button()
    {

        this.alarm=false;
        this.alarm_arr=[];
        this.alarm_mess_arr=[];


        this.product_arr['product_name']=this.product_arr['product_name'].trim();
        this.product_arr['product_desc']=this.product_arr['product_desc'].trim();
        this.product_arr['city']=this.product_arr['city'].trim();
        if(this.product_arr['product_name'].length<3)
        {
            this.alarm = true;
            this.alarm_mess_arr.push('Название товара не должно быть короче 3 символов');
            this.alarm_arr.push('product_name');
        }
        if(this.product_arr['product_name'].length>50)
        {
            this.alarm = true;
            this.alarm_mess_arr.push('Название товара не должно быть длиннее 50 символов');
            this.alarm_arr.push('product_name');

        }
        if(this.product_arr['product_desc'].length<3)
        {
            this.alarm = true;
            this.alarm_mess_arr.push('Описание товара не должно быть короче 3 символов');
            this.alarm_arr.push('product_desc');

        }
        if(this.product_arr['product_desc'].length>50)
        {
            this.alarm = true;
            this.alarm_mess_arr.push('Описание товара не должно быть длиннее 50 символов');
            this.alarm_arr.push('product_desc');
        }


        let num = Number(this.product_arr['product_price'])
        if( num == 0)
        {
            this.alarm = true;
            this.alarm_mess_arr.push('Укажите цену товара');
            this.alarm_arr.push('product_price');

        }

        if(this.product_arr['selected_region']=='')
        {
            this.alarm = true;
            this.alarm_mess_arr.push('Выберите область');
            this.alarm_arr.push('selected_region');
        }

        if(this.product_arr['city'].length<3)
        {
            this.alarm = true;
            this.alarm_mess_arr.push('Название города не короче 3 символов');
            this.alarm_arr.push('city');
        }

        if(this.product_arr['city'].length>50)
        {
            this.alarm = true;
            this.alarm_mess_arr.push('Название города не длиннее 50 символов');
            this.alarm_arr.push('city');
        }

        if(this.product_arr['selected_category']=='')
        {
            this.alarm = true;
            this.alarm_mess_arr.push('Выберите категорию товара');
            this.alarm_arr.push('selected_category');
        }

        if(this.product_arr['product_cond']=='')
        {
            this.alarm = true;
            this.alarm_mess_arr.push('Выберите состояние товара');
            this.alarm_arr.push('product_cond');
        }

        if(this.alarm ==false)
        {
            this.$store.dispatch('setDescription',  this.product_arr);
            Vue.router.push({name:'add_advertisement2'})
        }

            },

            select_region(inp)
            {
                axios
                    .post('/select_region',{
                    })
                    .then(({ data }) => (
                        data.forEach(function(entry) {
                                inp.push({
                                    name:entry.region,
                                    id:entry.id
                                });
                        })
                        )
                    );
            },
            select_category(inp)
            {
                axios
                    .post('/select_category',{
                    })
                    .then(({ data }) => (
                            data.forEach(function(entry) {
                                inp.push({
                                    name:entry.category_name,
                                    id:entry.id
                                });
                            })
                        )
                    );
            },

        }
    }
</script>
