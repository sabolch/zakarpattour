<template>
    <v-dialog
            v-model="dialog"
            width="500"
            persistent
    >
        <template v-slot:activator="{ on }">
            <v-btn
                    icon
                    @click="openDialog"
                    :disabled="disabled"
            >
                <v-icon color="red">
                    shopping_cart
                </v-icon>
            </v-btn>
        </template>

        <v-card>
            <v-card-title

                    class="headline primary white--text"
                    primary-title
            >
                Add to shopping cart
            </v-card-title>

            <v-card-text>
                <v-icon size="70" class="pa-3" color="red">add_shopping_cart</v-icon>
                <v-text-field
                        color="red"
                        label="Start date"
                        :value="date"
                        readonly
                        prepend-icon="event"
                ></v-text-field>
                <v-text-field
                        label="Count of persons"
                        v-model="count"
                        min="1"
                        :max="maxPersonCount"
                        step="1"
                        type="Number"
                        prepend-icon="person"
                ></v-text-field>
                <v-divider></v-divider>
                <div class="text-xs-left pt-3 pl-1 title font-weight-bold primary--text">Total price: ₴ {{totalprice}} UAH</div>
            </v-card-text>

            <v-divider></v-divider>

            <v-card-actions>
                <v-spacer></v-spacer>
                <v-btn
                        color="primary"
                        outline
                        @click="dialog = false"
                >
                    {{$t('btns.close')}}
                </v-btn>
                <v-btn
                        color="red"
                        outline
                        @click="add"
                        :loading="loading"
                >
                    Add
                </v-btn>
            </v-card-actions>
        </v-card>
    </v-dialog>
</template>

<script>
    export default {
        name: "add-to-cart",
        props: {
            tour: {
                type: Object,
                required: true
            },
            date: {
                type: String,
                required: true
            },
            disabled: {
                type: Boolean,
                default:false
            },
            maxPersonCount: {
                type: Number,
                required: true
            }
        },
        data() {
            return {
                count: 1,
                dialog: false,
                loading: false,
                datacheck:null
            }
        },
        mounted(){
            console.log(this.tour)
        },
        watch:{
          count:function (v) {
              clearTimeout(this.datacheck)
              this.datacheck = setTimeout(()=>{
                  if(!v){
                      this.count = 1
                  }
                  if(parseInt(v) >= this.maxPersonCount){
                      this.count = this.maxPersonCount
                  }
                  if(parseInt(v) <= 1){
                      this.count = 1
                  }
                  console.log(parseInt(v))
              },200)
          }
        },
        computed:{
            totalprice(){
                return this.tour.price * this.count;
            }
        },
        methods: {
            async add() {
                this.loading = true
                await this.$store.dispatch('shopping_cart/pushItem', {
                    item: {
                        tour_id: this.tour.id,
                        date: this.date,
                        persons: this.count,
                        price: (this.tour.price * this.count),
                        slug:this.tour.slug,
                        translations: this.tour.translations.map(obj => {
                            return {
                                locale: obj.locale,
                                title:obj.title,
                            }
                        })
                    }
                })
                this.loading = false
                this.dialog = false

            },
            openDialog() {
                if (!this.$store.state.auth.user) {
                    this.$router.push({name: 'login'})
                    return
                }
                this.dialog = !this.dialog
            }
        },

    }
</script>

<style scoped>

</style>
