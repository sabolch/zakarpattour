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
                >
                    <v-icon color="red">
                        shopping_cart
                    </v-icon>
                </v-btn>
            </template>

            <v-card >
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
                            max="20"
                            step="1"
                            type="Number"
                            prepend-icon="person"
                    ></v-text-field>
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
            itemId: {
                type: Number,
                required: true
            },
            price: {
                type: Number,
                required: true
            },
            date: {
                type: String,
                required: true
            }
        },
        data() {
            return {
                count: 1,
                dialog: false,
                loading:false
            }
        },
        methods: {
           async add() {
               this.loading = true
                await this.$store.dispatch('shopping_cart/pushItem', {
                    item: {
                        id: this.itemID,
                        user_id: this.$store.state.auth.user.id,
                        date: this.date,
                        price: (this.price * this.count)
                    }
                })
               this.loading = false
               this.dialog = false

           },
            openDialog(){
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
