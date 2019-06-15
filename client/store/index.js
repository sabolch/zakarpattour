import Cookies from 'js-cookie'
import { cookieFromRequest } from '~/utils'

export const actions = {

  nuxtServerInit ({ commit }, { req }) {
    const token = cookieFromRequest(req, 'token')
    if (token) {
      commit('auth/SET_TOKEN', token)
    }

    const admin_token = cookieFromRequest(req, 'admin_token')
    if (admin_token) {
      commit('admin/SET_TOKEN', admin_token)
    }

    const locale = cookieFromRequest(req, 'locale')
    if (locale) {
      commit('lang/SET_LOCALE', { locale })
    }

    const shopping_cart = cookieFromRequest(req, 'shopping_cart')
    if (shopping_cart) {
      commit('shopping_cart/SET_ITEMS', { items:JSON.parse(decodeURIComponent(shopping_cart))  })
    }
  },

  nuxtClientInit ({ commit }) {
    const token = Cookies.get('token')
    if (token) {
      commit('auth/SET_TOKEN', token)
    }

    const admin_token = Cookies.get('admin_token')
    if (admin_token) {
      commit('admin/SET_TOKEN', admin_token)
    }

    const locale = Cookies.get('locale')
    if (locale) {
      commit('lang/SET_LOCALE', { locale })
    }

    const shopping_cart = Cookies.get('shopping_cart')
    if (shopping_cart) {
      commit('shopping_cart/SET_ITEMS', {  items:JSON.parse(decodeURIComponent(shopping_cart))})
    }
  }
}

export const state = () => ({
  user_navbar_mini: false,
  user_cart_mini:false,
  gMapStyles :{
    stylesZoomOut: [{
      polygonOptions: {
        strokeColor: "#FF0000",
        strokeOpacity: 1,
        strokeWeight: "1",
        fillOpacity: 0.0001
      },
    }],
    stylesZoomIn: [{
      polygonOptions: {
        strokeColor: "#FF0000",
        strokeOpacity: 1,
        strokeWeight: "0.5",
        fillOpacity: 0.05
      },
    }],
    showLabels:[
      {
        featureType: "administrative",
        elementType: "labels",
        stylers: [
          { visibility: "on" }
        ]
      },{
        featureType: "poi",
        elementType: "labels",
        stylers: [
          { visibility: "on" }
        ]
      },{
        featureType: "water",
        elementType: "labels",
        stylers: [
          { visibility: "on" }
        ]
      },{
        featureType: "road",
        elementType: "labels",
        stylers: [
          { visibility: "on" }
        ]
      }
    ],
    hideLabels:[
      {
        featureType: "administrative",
        elementType: "labels",
        stylers: [
          { visibility: "on" }
        ]
      },{
        featureType: "poi",
        elementType: "labels",
        stylers: [
          { visibility: "off" }
        ]
      },{
        featureType: "water",
        elementType: "labels",
        stylers: [
          { visibility: "on" }
        ]
      },{
        featureType: "road",
        elementType: "labels",
        stylers: [
          { visibility: "on" }
        ]
      }
    ],
  }
})

export const mutations = {
  user_navbar_mini (state) {
    state.user_navbar_mini = !state.user_navbar_mini
  },
  user_cart_mini (state) {
    state.user_cart_mini = !state.user_cart_mini
  }
}
