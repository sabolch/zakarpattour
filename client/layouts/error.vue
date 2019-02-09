<template>
  <v-container fluid fill-height>
    <v-layout justify-center align-center>
      <v-flex sm10 xs8 md4>
        <v-card dark class="text-xs-center red lighten-3" style="padding:10%; border-radius:2px">
          <v-icon size="150">error</v-icon>
          <h1 class="display-2">Error 404</h1>
          <h2>Page not found!</h2>
          <p><v-btn fab large outline :to="{name: 'welcome'}"><v-icon large>home</v-icon></v-btn></p>
        </v-card>
      </v-flex>
    </v-layout>
  </v-container>
</template>
<style>

</style>
