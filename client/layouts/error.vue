<template>
  <v-container fluid fill-height>
    <v-layout justify-center align-center>
      <v-flex sm10 xs8 md4>
        <v-card dark class="text-xs-center red lighten-3" style="padding:10%; border-radius:2px">
          <v-icon size="140">error</v-icon>
          <h1 class="error-title">404</h1>
          <h2>{{$t('page_not_found')}}</h2>
         <v-btn class="mt-4" medium outline :to="{name: 'welcome'}">{{$t('go_home')}}</v-btn>
        </v-card>
      </v-flex>
    </v-layout>
  </v-container>
</template>

<style>
  .error-title h1 {
    font-size: 100px;
    line-height: 100px;
    font-weight: 700;
    color: white;
    text-shadow: rgba(61, 61, 61, 0.3) 1px 1px, rgba(61, 61, 61, 0.2) 2px 2px, rgba(61, 61, 61, 0.3) 3px 3px;
  }
</style>
